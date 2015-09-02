<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function create
*
* $Id: stream_create.php 9828 2012-08-24 05:31:21Z lijiaying $
***************************************************************************/
require('global.php');
class streamCreateApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 创建频道信号
	 * @name create
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $ch_name string 信号名称
	 * @param $s_name string 信号标识
	 * @param $save_time int 回看时间 (小时)
	 * @param $live_delay int 延时时间 (分钟)
	 * @param $uri string 信号流地址
	 * @param $name string 流名称
	 * @param $bitrate int 码流
	 * @param $backstore array 支持格式(flv,ts)
	 * @param $audio_only tinyint 是否音频 (1-是 0-否)
	 * @param $wait_relay tinyint 是否推送 (1-是 0-否)
	 * @param $other_info string 流信息
	 * @param $server_id int 服务器ID
	 * @param $create_time int 创建时间
	 * @param $update_time int 更新时间
	 * @param $ip string 创建者IP
	 * @return $info['id'] int 频道信号ID
	 * @include tvie_api.php
	 */
	function create()
	{
		$ch_name = urldecode($this->input['ch_name']);//信号名称
		if(!ch_name)
		{
			$this->errorOutput('信号名称的不能为空，请重新填写！');
		}
		
		$s_name = urldecode($this->input['s_name']);//信号标识
		if(!$s_name)
		{
			$this->errorOutput('信号标识的不能为空，请重新填写！');
		}
		
		$save_time = intval($this->input['save_time']);//回看(小时)
		$live_delay = intval($this->input['live_delay']);//延时(分钟)
		
		$type = 'live';
		$recover_cache = 1;
		$source_name = 'tvie-live-encoder';
		$streams_info = $uri_arr = array();
		if(is_array($this->input['counts']) && $this->input['counts'])
		{
			for($i = 0;$i< count($this->input['counts']);$i++)
			{
				$streams_info[$i]['name'] = urldecode($this->input['name_'.$i]);
				$streams_info[$i]['uri'] = urldecode($this->input['uri_'.$i]);
				if(!$streams_info[$i]['name'])
				{
					$this->errorOutput('输出标识不能为空');
				}
				if(!$streams_info[$i]['uri'])
				{
					$this->errorOutput('来源地址不能为空');
				}
				$streams_info[$i]['bitrate'] = $this->input['bitrate_'.$i];
				$streams_info[$i]['backstore'][] = $this->input['flv_'.$i];
				$streams_info[$i]['backstore'][] = $this->input['ts_'.$i];
				$streams_info[$i]['audio_only'] = $this->input['audio_only'] ? $this->input['audio_only'] : 0;
				$streams_info[$i]['wait_relay'] = $this->input['wait_relay'] ? $this->input['wait_relay'] : 0;
				$uri_arr[] = urldecode($this->input['uri_'.$i]);
			}
		}

		//开启
		if($this->settings['tvie']['open'])
		{
			include(CUR_CONF_PATH . 'lib/tvie_api.php');
			$up_tvie = new TVie_api($this->settings['tvie']['up_stream_server']);

			$servers = $up_tvie->get_all_servers();//获取媒体服务器ID

			if(!$servers)
			{
				$this->errorOutput("网络延时");
			}
		
			if (is_array($streams_info[0]['backstore']))
			{
				$backstore = implode(',', $streams_info[0]['backstore']);
			}
			else
			{
				$backstore = $streams_info[0]['backstore'];
			}
			//创建直播频道
			$ret_channel = $up_tvie->create_channel(
												$ch_name,
												$s_name,
												$servers['items'][0]['id'],
												$save_time,
												$live_delay,
												$type,
												$streams_info[0]['name'],
												$recover_cache,
												$source_name,
												$streams_info[0]['uri'],
												urldecode($this->input['bitrate']),
												0,
												$streams_info[0]['wait_relay'],
												'flv'
											);
			$ch_id = $ret_channel['channel_id'];	//返回虚拟频道id

			if (!$ch_id)
			{
				$this->errorOutput('媒体服务器数据异常');
			}
			
    		$ret_channel_info = $up_tvie->get_channel_by_id($ch_id);
    		$ret_stream_info = $ret_channel_info['channel']['streams'];
	    	$first_stream_id = $ret_stream_info[0]['id'];
			
	    	if (!$first_stream_id)
	    	{
	    		$this->errorOutput('媒体服务器创建信号流失败');
	    	}
	    	
			//创建流	
			foreach($streams_info as $key => $value)
			{
				$ret = $up_tvie->create_channel_stream(
											$value['name'],
		    								$recover_cache,
		    								$source_name,
		    								$value['uri'],
		    								0,
		    								'flv',
		    								$value['wait_relay'],
		    								$value['audio_only'],
		    								$value['bitrate'],
		    								$ch_id
	    								);
	    								
	    		if (!$ret['stream_id'])
	    		{
	    			$this->errorOutput('媒体服务器创建信号失败');
	    		}
	    		
	    		$backstore = array(0=>'flv');
    			$other_info[$key] = array(
									'id' =>  $key == 0 ? $first_stream_id : $ret['stream_id'],
									'name' =>  $value['name'],
									'ch_name' =>  $ch_name,
									'uri' => $value['uri'],
									'recover_cache' =>  $recover_cache,
									'source_name' =>  $source_name,
									'drm' =>  0,
									'backstore' =>  $backstore,
									'wait_relay' =>  $value['wait_relay'],
									'audio_only' =>  $value['audio_only'],
									'bitrate' =>  $value['bitrate']
								);
			}

		}
		else
		{
			$this->errorOutput('媒体服务器未启动');
		}
		
		$info = array(
			'ch_id' => $ch_id,
			's_name' => $s_name,
			'ch_name' => $ch_name,
			'uri' => serialize($uri_arr),
			'type' => $type,
			'save_time' => $save_time,
			'live_delay' => $live_delay,
			'other_info' => serialize($other_info),
			'server_id' => $this->input['server_id'] ? intval($this->input['server_id']) : $servers['items'][0]['id'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
		);
		
		$sql = "INSERT INTO " . DB_PREFIX . "stream SET ";
		$space = "";
		foreach($info as $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($sql);
	
		$info['id'] = $this->db->insert_id();
		
		$this->setXmlNode('stream','info');
		$this->addItem($info['id']);
		$this->output();
	}
}
$out = new streamCreateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>