<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function create
*
* $Id: channel_create.php 7438 2012-07-02 01:08:38Z zhuld $
***************************************************************************/
define('MODULE_UNIQUEID','channel');
require('global.php');
require_once(ROOT_PATH.'lib/class/statistic.class.php');
class channelCreateApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 创建频道 (当tvie为open时开始创建，创建顺序：延时层-->切播层-->输出层-->本地)
	 * @name create
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $name string 频道名称
	 * @param $code string 台号
	 * @param $code_2 string 台号(修改用)
	 * @param $logo string 台标
	 * @param $stream_id int 信号ID
	 * @param $delay_id int 延时层ID
	 * @param $chg_id int 切播层ID
	 * @param $ch_id int 输出层ID
	 * @param $save_time int 回看时间(小时)
	 * @param $live_delay int 延时时间 (分钟)
	 * @param $is_live tinyint 是否播控 (1-是 0-否)
	 * @param $stream_name string 流名称	not null
	 * @param $main_stream_name string 主信号名称
	 * @param $beibo string 备播信号  (暂最多支持2个)
	 * @param $uri_in_num tinyint 输入流数目
	 * @param $uri_out_num tinyint 输出流数目
	 * @param $level tinyint 频道层数
	 * @param $open_ts tinyint 是否支持手机流 (1-是 0-否)
	 * @param $record_time int 自动收录节目时间偏差设置 (±30秒 大于30秒就等于30秒，小于-30秒就等于-30秒)
	 * @param $audio_only tinyint 记录是否是音频 (1-是 0-否 )
	 * @param $create_time int 创建时间
	 * @param $update_time int 更新时间
	 * @param $ip string 创建者ip
	 * @param $channel_id int 频道ID
	 * @param $delay_stream_id int 延时层流ID
	 * @param $chg_stream_id int 切播层流ID
	 * @param $out_stream_id int 输出层流ID
	 * @param $stream_name string 流名称
	 * @param $out_stream_name string 输出流名称
	 * @param $is_main tinyint 是否主流 (1-是 0-否)
	 * @param $bitrate int 码流
	 * @param $flag_stream 标识(单流改为多流)
	 * @param $drm tyinint 防盗链设置 (1-启用  0-关闭)	
	 * @param $logo_info array logo素材信息
	 * @return $ret['id'] int 频道ID
	 * @include tvie_api.php
	 */
	public function create()
	{
		$name = urldecode($this->input['name']);
		if(!$name)
		{
			$this->errorOutput('频道名称不能为空！');
		}	
		$code = urldecode($this->input['code']);
		if(!$code)
		{
			$this->errorOutput('台号不能为空！');
		}
		$stream_id = $this->input['stream_id'] ? intval($this->input['stream_id']) : 0;
		
		$save_time = $this->input['save_time'] ? intval($this->input['save_time']) : 0;		//回看时间
		$live_delay = $this->input['live_delay'] ? intval($this->input['live_delay']) : 0;	//延时时间
		$is_live = intval($this->input['is_live']);	//是否播控    1表示有播控  0表示无播控
		
		$drm = intval($this->input['drm']);	//防盗链设置
		
		$stream_name = $this->input['stream_name'];
		if (!$stream_name)
		{
			$this->errorOutput('至少为频道选择一条流');
		}
		$main_stream_name = $this->input['main_stream_name'];
		if($this->input['beibo'])
		{
			if($is_live && count($this->input['beibo']) > 2)
			{
				$this->errorOutput('最多两个备播信号，请重新选择！');
			}
			foreach($this->input['beibo'] as $value)
			{
				$beibo_key_value = explode('#',urldecode($value));
				$beibo[$beibo_key_value[0]] = $beibo_key_value[1];
			}
			$beibo = serialize($beibo);
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "stream WHERE id=" . $stream_id;
		$streams = $this->db->query_first($sql);
		if (!$streams)
		{
			$this->errorOutput('所选信号不存在，请重新选择');
		}
		$other_info = unserialize($streams['other_info']);
		
		if (!$other_info)
		{
			$this->errorOutput('所选信号没有信号流');
		}
		$stream_name_arr = array();
		foreach($other_info as $v)
		{
			$stream_name_arr[$v['name']] = $v;
		}
	 	$stream_info = array();
		foreach ($stream_name AS $n)
		{
			if ($stream_name_arr[$n])
			{
				$stream_info[] = $stream_name_arr[$n];
			}
		}

		if (!$stream_info)
		{
			$this->errorOutput('所选流不存在或已被删除');
		}
		
		//输入流的数目
		$uri_in_num = count($stream_name); 
		//层数目
		if($uri_in_num > 1) //多流
		{
			$level = 1;
		}
		else //单流
		{
			if (!$is_live) //无播控
			{
				$level = 1;
			}
			elseif(!$live_delay) //有播控无延时
			{
				$level = 2;
			}
			else //有播控有延时
			{
				$level = 3;
			}
		}
	 	
	 	//选择流与选择信号流的差集
	 	$diff_stream_name = array_diff($stream_name_arr, $stream_name);
	 	
		//开启tvie
		if($this->settings['tvie']['open'])
		{
			include(CUR_CONF_PATH . 'lib/tvie_api.php');
			$delay_tvie = new TVie_api($this->settings['tvie']['up_stream_server']);
			$delay_type = 'normal_virtual';
			$chg_tvie = $delay_tvie;
			$out_tvie = new TVie_api($this->settings['tvie']['stream_server']);

			$first_stream = $stream_info[0]; //获取主信号流
			unset($stream_info[0]);
		
			$delay_stream_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $first_stream['name']), 'channels', 'tvie://');
			
			if (is_array($first_stream['backstore']))
			{
				$backstore = implode(',', $first_stream['backstore']);
			}
			else
			{
				$backstore = $first_stream['backstore'];
			}
			
			//32创建延时层
			$delay_channel = $delay_tvie->create_channel(
											'delay_'.$code,
											$name,
											$streams['server_id'],
											1,
											$live_delay,
											$delay_type,
											$first_stream['name'],
											$first_stream['recover_cache'],
											$first_stream['source_name'],
											$delay_stream_uri,
											$first_stream['bitrate'],
											$first_stream['drm'],
											$first_stream['wait_relay'],
											$backstore
										);
			$delay_channel_id = $delay_channel['channel_id'];
			

			if (!$delay_channel_id)
			{
				$this->errorOutput('频道创建失败，原因：' . serialize($delay_channel)  . $delay_channel['message'] . $delay_channel['errors']);
			}
			$ret_delay_channel_info = $delay_tvie->get_channel_by_id($delay_channel_id);
    		$ret_delay_stream_info = $ret_delay_channel_info['channel']['streams'];
	    	$first_delay_stream_id = $ret_delay_stream_info[0]['id'];
			//延时层创建流
			$stream_ids = array(
				'delay_stream_id' =>  array($first_stream['name'] => $first_delay_stream_id),
			);
			if ($stream_info)
			{
				foreach($stream_info as $key => $value)
				{
					$delay_uri =  hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $value['name']), 'channels', 'tvie://');

					if(is_array($value['backstore']))
					{
						$backstore = implode(',', $value['backstore']);
					}
					else 
					{
						$backstore = $value['backstore'];
					}
					$ret_delay_stream = $delay_tvie->create_channel_stream(
												$value['name'],
												$value['recover_cache'],
												$value['source_name'],
												$delay_uri,
												$value['drm'],
												$backstore,
												$value['wait_relay'],
												0,
												$value['bitrate'],
												$delay_channel_id
											);
					if($ret_delay_stream['stream_id'])
					{
						$stream_ids['delay_stream_id'][$value['name']] = $ret_delay_stream['stream_id'];
					}
				}
			}
			
			//创建切播层

			$chg_type = 'normal_virtual';
			
			if(!$live_delay)
			{
				//没有延时，上游流地址直接是信号流地址
				$chg_stream_uri =  hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $first_stream['name']));
			}
			else
			{
				//有延时，上游流地址延迟层流地址
				$chg_stream_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => 'delay_' . $code, 'stream_name' => $first_stream['name']));
			}
		
			if (is_array($first_stream['backstore']))
			{
				$backstore = implode(',', $first_stream['backstore']);
			}
			else
			{
				$backstore = $first_stream['backstore'];
			}
			
			//32创建切播层
			$chg_channel = $chg_tvie->create_channel(
										'chg_'.$code,
										$name,
										$streams['server_id'],
										0,
										0,
										$chg_type,
										$first_stream['name'],
										$first_stream['recover_cache'],
										$first_stream['source_name'],
										$chg_stream_uri,
										$first_stream['bitrate'],
										$first_stream['drm'],
										$first_stream['wait_relay'],
										$backstore
									);
			$chg_channel_id = $chg_channel['channel_id'];
			
			if (!$chg_channel_id)
			{
				$delay_tvie->delete_channel($delay_channel_id);
				$this->errorOutput('频道创建失败，原因：' . $chg_channel['message'] . $chg_channel['errors']);
			}
			$ret_chg_channel_info = $chg_tvie->get_channel_by_id($chg_channel_id);
			$ret_chg_stream_info = $ret_chg_channel_info['channel']['streams'];
			$first_chg_stream_id = $ret_chg_stream_info[0]['id'];
			
			$stream_ids['chg_stream_id'][$first_stream['name']] = $first_chg_stream_id;
			//创建切播层流

			$chg_url = array();
			foreach($stream_info as $key => $value)
			{
				if(!$live_delay)
				{
					//没有延时，上游流地址直接是信号流地址
					$chg_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $value['name']), 'channels', 'tvie://');
					$chg_uri_http = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $value['name']));
				}
				else
				{
					//有延迟，上游流地址是延迟层流地址
					$chg_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => 'delay_' . $code, 'stream_name' => $value['name']), 'channels', 'tvie://');
					$chg_uri_http = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => 'delay_' . $code, 'stream_name' => $value['name']));
				}

				$chg_url['tvie'][] = $chg_uri;
				$chg_url['http'][] = $chg_uri_http;
				if(is_array($value['backstore']))
				{
					$backstore = implode(',', $value['backstore']);
				}
				else 
				{
					$backstore = $value['backstore'];
				}
				$ret_chg_stream = $chg_tvie->create_channel_stream(
											$value['name'],
											$value['recover_cache'],
											$value['source_name'],
											$chg_uri_http,
											$value['drm'],
											$backstore,
											$value['wait_relay'],
											0,
											$value['bitrate'],
											$chg_channel_id
										);
				if ($ret_chg_stream['stream_id'])
				{
					$stream_ids['chg_stream_id'][$value['name']] = $ret_chg_stream['stream_id'];
				}
			}
			
			//32延时层或者切播层创建成功后，再向21创建输出层
			
			$out_type = 'normal_virtual';
		
			if(!$is_live)
			{
				//无切播层，直接信号流地址
				$out_stream_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $first_stream['name']), 'channels', 'tvie://');
			}
			else 
			{
				//有切播层，切播层流地址
				$out_stream_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => 'chg_' . $code, 'stream_name' => $first_stream['name']), 'channels', 'tvie://');
			}
			if ($this->input['open_ts'])
			{
				$first_stream['backstore'] = array(
					0 => 'flv',
					1 => 'ts'
				);
			}
			if (is_array($first_stream['backstore']))
			{
				$backstore = implode(',', $first_stream['backstore']);
			}
			else
			{
				$backstore = $first_stream['backstore'];
			}
		
			$out_live_delay = $live_delay;
			if($level != 1)
			{
				$out_live_delay = 0;
			}
			else
			{
				if (!$save_time)
				{
					$save_time = 1;
				}
			}
			//创建频道
			$out_channel = $out_tvie->create_channel(
											$code,
											$name,
											$streams['server_id'],
											$save_time,
											$out_live_delay,
											$out_type,
											$first_stream['name'],
											$first_stream['recover_cache'],
											$first_stream['source_name'],
											$out_stream_uri,
											$first_stream['bitrate'],
											$drm,
											$first_stream['wait_relay'],
											$backstore
										);
			$out_channel_id = $out_channel['channel_id'];	//返回频道id	

			if(!$out_channel_id)
			{		
				//删除切播层、延时层
				$delay_tvie->delete_channel($delay_channel_id);
				$chg_tvie->delete_channel($chg_channel_id);
				$this->errorOutput('频道创建失败，原因：' . $out_channel['message'] . $out_channel['errors']);
			}
			$ret_out_channel_info = $out_tvie->get_channel_by_id($out_channel_id);
			$ret_out_stream_info = $ret_out_channel_info['channel']['streams'];
			$first_out_stream_id = $ret_out_stream_info[0]['id'];
			$stream_ids['out_stream_id'][$first_stream['name']] = $first_out_stream_id;
			
			//创建输出层流	
		
			foreach($stream_info as $key => $value)
			{
				if(!$is_live)
				{
					//无切播层，直接信号流地址
					$out_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $streams['ch_name'], 'stream_name' => $value['name']), 'channels', 'tvie://');
				}
				else 
				{
					//有切播层，切播层流地址
					$out_uri = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => 'chg_' . $code, 'stream_name' => $value['name']), 'channels', 'tvie://');
				}
				if ($this->input['open_ts'])
				{
					$value['backstore'] = array(
						0 => 'flv',
						1 => 'ts'
					);
				}
				if(is_array($value['backstore']))
				{
					$backstore = implode(',', $value['backstore']);
				}
				else 
				{
					$backstore = $value['backstore'];
				}
		
				
				$ret_out_stream = $out_tvie->create_channel_stream(
											$value['name'],
											$value['recover_cache'],
											$value['source_name'],
											$out_uri,
											$drm,
											$backstore,
											$value['wait_relay'],
											0,
											$value['bitrate'],
											$out_channel_id
										);
				if ( $ret_out_stream['stream_id'])
				{
					$stream_ids['out_stream_id'][$value['name']] = $ret_out_stream['stream_id'];
				}
			}	
			
		}
		//录制节目时间偏差设置
		if($this->input['record_time'] >= 0)
		{
			if($this->input['record_time'] > 30)
			{
				$record_time = 30;
			}
			else
			{
				$record_time = $this->input['record_time'];
			}
		}
		else 
		{
			if($this->input['record_time'] < -30)
			{
				$record_time = -30;
			}
			else
			{
				$record_time = $this->input['record_time'];
			}
		}
		
		$info = array(
			'user_id' => $this->user['user_id'],
			'user_name' => $this->user['user_name'],
			'code' => $code,
			'code_2' => $code,
			'name' => $name,
			'delay_id' =>$delay_channel_id,
			'chg_id' =>$chg_channel_id,
			'ch_id' => $out_channel_id,
			'is_live' => $is_live,
			'drm' => $drm,
			'open_ts' => $this->input['open_ts'],
			'uri_in_num' => $uri_in_num,
			'uri_out_num' => $uri_out_num,
			'save_time' => $save_time ? $save_time : 0,
			'live_delay' => $live_delay ? $live_delay : 0,
			'stream_display_name' => $streams['s_name'],
			'stream_mark' => $streams['ch_name'],
			'level' => $level,
			'beibo' => $beibo,
			'stream_id' => $stream_id,
			'main_stream_name' => $main_stream_name[0],
			'stream_info_all' => serialize($stream_name),
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
			'record_time' => $record_time,
			'audio_only' => $first_stream['audio_only']
		);
		$createsql = "INSERT INTO " . DB_PREFIX . "channel SET ";
		$space = "";
		foreach($info as $key => $value)
		{
			$createsql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$ret = array();
		$this->db->query($createsql);
		
		$ret['id'] = $this->db->insert_id();
		//插入排序id
		
		//插入工作量统计
		$statistic = new statistic();
		$statistics_data = array(
			'content_id' => $ret['id'],
			'contentfather_id' => '',
			'type' => 'insert',
			'user_id' => $this->user['user_id'],
			'user_name' => $this->user['user_name'],
			'app_uniqueid' => APP_UNIQUEID,
			'module_uniqueid' => MODULE_UNIQUEID,
			'before_data' => '',
			'last_data' => $name,
			'num' => 1,
		);
		$statistic->insert_record($statistics_data);
		if ($_FILES['files']['tmp_name'])
		{
			include_once ROOT_PATH . 'lib/class/material.class.php';
			$this->mMaterial = new material();

			$file['Filedata'] = $_FILES['files'];
			
			$material = $this->mMaterial->addMaterial($file, $ret['id'], intval($this->input['mmid']), 'img4');
			
			$logo_info['id'] = $material['id'];
			$logo_info['type'] = $material['type'];
			$logo_info['server_mark'] = $material['server_mark'];
			$logo_info['filepath'] = $material['filepath'];
			$logo_info['name'] = $material['name'];
			$logo_info['filename'] = $material['filename'];
			$logo_info['url'] = $material['url'];
		}
		
		$sql = "UPDATE " . DB_PREFIX . "channel SET order_id = " . $ret['id'] . ", logo_info = '" . serialize($logo_info) . "' WHERE id=" . $ret['id'];	
		$this->db->query($sql);
		
		//流信息
		$stream_info[] = $first_stream;
		$i = 0;
		$stream_num = count($stream_info) - 1;
		foreach($stream_info as $k => $v)
		{
			$main_stream_info = array(
					'channel_id' => $ret['id'],
					'stream_id' => $stream_id,
					'delay_stream_id' => $stream_ids['delay_stream_id'][$v['name']],
					'chg_stream_id' => $stream_ids['chg_stream_id'][$v['name']],
					'out_stream_id' => $stream_ids['out_stream_id'][$v['name']],
					'stream_name' => $v['name'],
					'out_stream_name' => $v['name'],
					'is_main' => ($main_stream_name[0] == $v['name']) ? 1 : 0 ,
					'bitrate' => $v['bitrate'],
					'flag_stream' => '',
					'create_time' => TIMENOW,
					'update_time' => TIMENOW,
					'ip' => hg_getip()
			);
			$cresql = "INSERT INTO " . DB_PREFIX . "channel_stream SET ";
			$space = "";
			foreach($main_stream_info as $key => $value)
			{
				$cresql .= $space . $key . "=" . "'" . $value . "'";
				$space = ",";
			}
			$this->db->query($cresql);
			$i++;
		}
		$this->setXmlNode('channel','info');
		$this->addItem($ret['id']);
		$this->output();
	}
}
$out = new channelCreateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>