<?php
/***************************************************************************
* $Id: sys_update.php 22758 2013-05-24 07:36:11Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','sys');
require('global.php');
class sysUpdateApi extends appCommonFrm
{
	private $mLivemms;
	function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
	//	require_once ROOT_PATH . 'lib/class/curl.class.php';
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 删除直播服务器配置
	 * Enter description here ...
	 */
	public function delete_server()
	{
		$is_cache = intval($this->input['is_cache']);
		$timenow = TIMENOW;
		$server_config = $this->get_server_config($timenow, $is_cache);
		
		$id = array();
		if (!empty($server_config))
		{
			foreach ($server_config AS $v)
			{
				if ($v['id'])
				{
					$id[] = $v['id'];
				}
			}
		}
		$return = array('result' => 0);
		if (!empty($id))
		{
			$sql = "DELETE FROM " .DB_PREFIX . "server_config WHERE id IN (" . implode(',', $id) . ")";
			$q = $this->db->query($sql);
			$return = array('result' => 1);
		}
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 同步直播服务器数据
	 * $is_cache 缓存一份数据到cache目录
	 * Enter description here ...
	 */
	public function sys_server()
	{
		$is_cache = isset($this->input['is_cache']) ? intval($this->input['is_cache']) : 1;
		$timenow = TIMENOW;
		$server_config = $this->get_server_config($timenow, $is_cache);
		if (empty($server_config))
		{
			$this->errorOutput('直播服务器配置不存在,无法同步数据');
		}
		
		//检测直播服务器是否通路
		foreach ($server_config AS $v)
		{
			$application_data = array(
				'action'	=> 'select',
			);
			$host 		= $v['core_in_host'] ? $v['core_in_host'] : $v['host'];
			$input_port = $v['core_in_port'] ? $v['core_in_port'] : $v['input_port'];
			$host 		= $host . ':' . $input_port;
			$ret_select = $this->mLivemms->outputApplicationOperate($host, $v['output_dir'], $application_data);
	
			if (!$ret_select)
			{
				$this->errorOutput($host . ' 这台直播服务器配置有误或者已停止服务');
			}
		}
		
		//入新直播服务器库
		foreach ($server_config AS $v)
		{
			$data = array(
				'name'				=> $v['name'],
				'brief'				=> $v['brief'],
				'counts'			=> $v['counts'] ? $v['counts'] : 100,
				'protocol'			=> $protocol ? $protocol : 'http://',
				'host'				=> $v['core_in_host'] ? $v['core_in_host'] : $v['host'],
				'input_port'		=> $v['core_in_port'] ? $v['core_in_port'] : $v['input_port'],
				'output_port'		=> $v['core_out_port'] ? $v['core_out_port'] : $v['output_port'],
				'input_dir'			=> $v['input_dir'] ? $v['input_dir'] : 'inputmanager/',
				'output_dir'		=> $v['output_dir'] ? $v['output_dir'] : 'outputmanager/',
				'output_append_host'=> $v['dvr_append_host'] ? @serialize($v['dvr_append_host']) : @serialize($v['output_append_host']),
				'user_id'			=> $v['user_id'] ? $v['user_id'] : $this->user['user_id'],
				'user_name'			=> $v['user_name'] ? $v['user_name'] : $this->user['user_name'],
				'appid'				=> $v['appid'] ? $v['appid'] : $this->user['appid'],
				'appname'			=> $v['display_name'] ? $v['display_name'] : $this->user['display_name'],
				'create_time'		=> $v['create_time'] ? $v['create_time'] : TIMENOW,
				'update_time'		=> $v['update_time'] ? $v['update_time'] : TIMENOW,
				'ip'				=> $v['ip'] ? $v['ip'] : hg_getip(),
			);
			if ($v['id'])
			{
				$data['id'] = $v['id'];
				$ret = $this->update('server_config', $data);
			}
			else 
			{
				$data['id']	= 1;
				$ret = $this->create('server_config', $data);
			}
			
			$this->addItem($ret);
		}
		
		$this->output();
	}
	
	/**
	 * 同步频道数据
	 * $is_live 是否不支持播控 默认支持播控 (1-不支持播控 0-支持播控)
	 * $is_cache 缓存一份数据到cache目录
	 * Enter description here ...
	 */
	public function sys_live()
	{
		$is_live  = intval($this->input['is_live']);
		$is_cache = isset($this->input['is_cache']) ? intval($this->input['is_cache']) : 1;
		$timenow = TIMENOW;
		$live_info = $this->get_live_info($timenow, $is_cache);
		
		if (empty($live_info))
		{
			$this->errorOutput('直播频道老数据信息不存在');
		}
		
		//检测直播服务器是否通路
		foreach ($live_info AS $v)
		{
			$host 		 = $v['server_config']['host'];
			$input_port  = $v['server_config']['input_port'];
			$output_dir  = $v['server_config']['output_dir'];
			
			$application_data = array(
				'action' => 'select',
			);
			
			$ret_select = $this->mLivemms->outputApplicationOperate($host . ':' . $input_port, $output_dir, $application_data);
			
			if (!$ret_select['result'])
			{
				$this->errorOutput($host . ' 这台直播服务器不存在或已停止服务');
			}
		}
		
		$ret_live = $return = array();
		foreach ($live_info AS $k => $v)
		{
			$host 		 = $v['server_config']['host'] . ':' . $v['server_config']['input_port'];
			$input_port  = $v['server_config']['input_port'];
			$output_port = $v['server_config']['output_port'];
			$input_dir   = $v['server_config']['input_dir'];
			$output_dir  = $v['server_config']['output_dir'];
			
			$stream_name = $v['stream_info_all'];
			$delay 		 = $v['live_delay'];
			$is_control	 = $is_live ? 0 : $v['is_live'];
			
			$core_count = count($stream_name);
			
			$stream_count = $core_count;
			
			$level = 1;
			
			if ($delay)
			{
				$stream_count = $stream_count + $core_count;
				$level = $level + 1;
			}
			
			if ($is_control)
			{
				$stream_count = $stream_count + $core_count;
				$level = $level + 1;
			}
			
			if ($delay || $is_control)
			{
				$stream_count = $stream_count + $core_count;
				$level = $level + 1;
			}
			
			$channel_stream = $ret_input_msg = $ret_delay_msg = $ret_change_msg = $ret_output_msg = array();
			if (!empty($v['channel_stream']))
			{
				foreach ($v['channel_stream'] AS $kk => $vv)
				{
					$channel_stream[$kk] = array(
						'id'			=> $vv['id'],
						'channel_id'	=> $vv['channel_id'],
						'stream_name'	=> $vv['stream_name'],
						'url'			=> $vv['url'],
						'input_id'		=> $vv['input_id'],
						'delay_id'		=> $vv['delay_stream_id'],
						'change_id'		=> $vv['chg_stream_id'],
						'output_id'		=> $vv['out_stream_id'],
						'bitrate'		=> $vv['bitrate'],
						'is_main'		=> $vv['is_main'],
					);
					
					//默认带切播 如果去掉切播功能,则删除输入层、延时层、切播层
					if ($is_live && $vv['url'])
					{
						//输入层
						if ($vv['input_id'])
						{
							$input_data = array(
								'action' => 'delete',
								'id'	 => $vv['input_id'],
							);
							
							$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
							
							if (!$ret_input['result'])
							{
								$ret_input_msg[$kk] = $vv['input_id'];
								continue;
							}
							$channel_stream[$kk]['input_id'] = 0;
						}
						
						//延时层
						if ($vv['delay_stream_id'])
						{
							$delay_data = array(
								'action' => 'delete',
								'id'	 => $vv['delay_stream_id'],
							);
							
							$ret_delay = $this->mLivemms->inputDelayOperate($host, $input_dir, $delay_data);
							
							if (!$ret_delay['result'])
							{
								$ret_delay_msg[$kk] = $vv['delay_stream_id'];
								continue;
							}
							$channel_stream[$kk]['delay_id'] = 0;
						}
						
						//切播层
						if ($vv['chg_stream_id'])
						{
							$change_data = array(
								'action' => 'delete',
								'id'	 => $vv['chg_stream_id'],
							);
							
							$ret_change = $this->mLivemms->inputChangeOperate($host, $input_dir, $change_data);
							
							if (!$ret_change['result'])
							{
								$ret_change_msg[$kk] = $vv['chg_stream_id'];
								continue;
							}
							$channel_stream[$kk]['change_id'] = 0;
						}
						
						//更新输出层url
						if ($vv['out_stream_id'])
						{
							$output_data = array(
								'action'		=> 'update',
								'id'			=> $vv['out_stream_id'],
								'applicationId'	=> $v['ch_id'],
								'name'			=> $vv['stream_name'],
								'url'			=> $vv['url'],
							);
					
							$ret_output = $this->mLivemms->outputStreamOperate($host, $output_dir, $output_data);
				
							if (!$ret_output['result'])
							{
								$ret_output_msg[$kk] = $vv['out_stream_id'];
								continue;
							}
						}
					}
				}
			}
			
			$channel_data = array(
				'id'				=> $v['id'],
				'order_id'			=> $v['order_id'],
				'name'				=> $v['name'],
				'code'				=> $v['code'],
				'logo_rectangle'	=> $v['logo_info'] ? @serialize($v['logo_info']) : '',
				'logo_square'		=> $v['logo_mobile_info'] ? @serialize($v['logo_mobile_info']) : '',
				'main_stream_name'	=> $v['main_stream_name'],
				'stream_name'		=> $stream_name ? @serialize($stream_name) : '',
				'stream_count'		=> $stream_count,
				'level'				=> $level,
				'core_count'		=> $core_count,
		//		'output_count'		=> '',
				'application_id'	=> $v['ch_id'],
				'time_shift'		=> $v['save_time'],
				'delay'				=> $delay,
				'is_audio'			=> $v['audio_only'],
				'is_push'			=> $v['is_push'],
				'drm'				=> $v['drm'],
				'status'			=> $v['stream_state'],
				'is_control'		=> $is_control,
		//		'change_id'			=> $v['chg2_stream_id'],
		//		'change_name'		=> $v['chg2_stream_name'],
		//		'change_type'		=> $v['chg_type'],
		//		'stream_id'			=> $v[''],
		//		'input_id'			=> $v[''],
		//		'beibo'				=> $v[''],
				'is_mobile_phone'	=> $v['open_ts'],
				'record_time_diff'	=> $v['record_time'],
		//		'is_del'			=> $v[''],
				'server_id'			=> $v['server_id'] ? $v['server_id'] : 1,
		//		'weight'			=> $v[''],
				'column_id'			=> $v['column_id'] ? @serialize($v['column_id']) : '',
				'column_url'		=> $v['column_url'] ? @serialize($v['column_url']) : '',
				'expand_id'			=> $v['expand_id'],
				'node_id'			=> $v['node_id'],
				'user_id'			=> $v['user_id'],
				'user_name'			=> $v['user_name'],
				'appid'				=> $v['appid'],
				'appname'			=> $v['appname'],
				'create_time'		=> $v['create_time'],
				'update_time'		=> $v['update_time'],
				'ip'				=> $v['ip'],
		//		'client_logo'		=> '',
			);
			
			$ret_channel = $this->create('channel', $channel_data);
			
			foreach ($channel_stream AS $vv)
			{
				$channel_stream_data = array(
					'id'			=> $vv['id'],
					'channel_id'	=> $vv['channel_id'],
					'stream_name'	=> $vv['stream_name'],
					'url'			=> $vv['url'],
					'input_id'		=> $vv['input_id'],
					'delay_id'		=> $vv['delay_id'],
					'change_id'		=> $vv['change_id'],
					'output_id'		=> $vv['output_id'],
					'bitrate'		=> $vv['bitrate'],
					'is_main'		=> $vv['is_main'],
				);
				$ret_channel_stream = $this->create('channel_stream', $channel_stream_data);
			}
			
			$channel_data['channel_stream'] = $channel_stream;
			$ret_live[$k] = $channel_data;
			$return[$v['id']] = $channel_data['name'];
		}
		if ($is_cache)
		{
			file_put_contents(CACHE_DIR . $timenow . '_new_live.txt', var_export($ret_live,1));
		}
		$this->addItem($return);
		$this->output();
	}
	
	private function create($table, $data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . $table . " SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	private function update($table, $data)
	{
		$sql = "UPDATE " . DB_PREFIX . $table . " SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql.= " WHERE id = " . $data['id'];
		$this->db->query($sql);
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	/**
	 * 取直播频道相关信息
	 * $is_cache 是否需要缓存一份数据到cache目录 (默认开启)
	 * Enter description here ...
	 * @param unknown_type $is_cache
	 */
	private function get_live_info($timenow, $is_cache = 1)
	{
		$curl = new curl($this->settings['App_live']['host'],$this->settings['App_live']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'get_old_live_info');
		$curl->addRequestData('timenow', $timenow);
		$curl->addRequestData('is_cache', $is_cache);
		$ret = $curl->request('admin/sys.php');
		return $ret[0];
	}
	
	/**
	 * 取直播服务器信息
	 * $is_cache 是否需要缓存一份数据到cache目录 (默认开启)
	 * Enter description here ...
	 * @param unknown_type $is_cache
	 */
	private function get_server_config($timenow, $is_cache = 1)
	{
		$curl = new curl($this->settings['App_live']['host'],$this->settings['App_live']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'get_old_server_config');
		$curl->addRequestData('timenow', $timenow);
		$curl->addRequestData('is_cache', $is_cache);
		$ret = $curl->request('admin/sys.php');
		return $ret[0];
	}
	
	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
	
}
$out = new sysUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>