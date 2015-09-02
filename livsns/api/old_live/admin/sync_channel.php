<?php
/***************************************************************************
* $Id: sync_channel.php 17966 2013-02-26 06:11:46Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','sync');
require('global.php');
class syncApi extends adminBase
{
	private $mSync;
	private $mLivmms;
	private $mLive;
	
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/livmms.class.php';
		$this->mLivmms = new livmms();
		
		$this->mLive = $this->settings['mms']['live_stream_server'];
		
		require_once CUR_CONF_PATH . 'lib/sync.class.php';
		$this->mSync = new sync();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 同步时移服务器、直播服务器信息
	 * Enter description here ...
	 */
	public function sync_channel()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 40;
		
		$channel_info = $this->mSync->get_channel_info($offset, $count);

		if (empty($channel_info))
		{
			$this->errorOutput('直播频道不存在或已被删除');
		}
		
		$ret_app_info = $ret_application_id = $_ret_application_id = array();
		$ret_app_error  = $ret_app_msg = $_ret_app_msg = array();
		
		foreach ($channel_info AS $channel_id => $channel)
		{
			//application
			
			//时移
			$application_id = $channel['ch_id'];
			$appName_output = $channel['code'];
			$length 		= $channel['save_time'] * 3600;
			$delay 			= $channel['live_delay'];
			$drm 			= $channel['drm'];
			$type 			= $channel['type'];
			
			$outputType = 0;
			
			if ($channel['open_ts'])
			{
				$outputType = 3;
			}
			else
			{
				$outputType = 1;
			}
			
			$ret_app_output = $this->mLivmms->outputApplicationInsert($application_id, $appName_output, $length, $drm, $outputType);
			
			if (!$ret_app_output['result'])
			{
				$ret_app_msg[$channel_id]['name'] = $channel['name'];
				$ret_app_msg[$channel_id]['code'] = $channel['code'];
				continue;
			}
			
			$ret_application_id[$channel_id] = $ret_app_output['application']['id'];
		//	$ret_application_id[$channel_id] = $application_id;
			
			//live
			if ($this->mLive)
			{
				$_ret_app_output = $this->mLivmms->_outputApplicationInsert($application_id, $appName_output, 0, $drm, $outputType);
				
				if (!$_ret_app_output['result'])
				{
					$_ret_app_msg[$channel_id]['name'] = $channel['name'];
					$_ret_app_msg[$channel_id]['code'] = $channel['code'];
					continue;
				}
				
				$_ret_application_id[$channel_id] = $_ret_app_output['application']['id'];
		//		$_ret_application_id[$channel_id] = $application_id;
			}
			
			//stream
			
			foreach ($channel['other_info']['input'] AS $input_info)
			{
				$ret_delay_id = $ret_chg_id = $ret_out_id = $_ret_out_id = array();
				$ret_delay_id_msg = $ret_chg_id_msg = $ret_out_id_msg = $_ret_out_id_msg = array();
				foreach ($channel['channel_stream'] AS $k => $channel_stream)
				{
					if ($input_info['name'] == $channel_stream['stream_name'])
					{
			/*	
						//延时层
						if ($delay)
						{
							$ret_delay = $this->mLivmms->inputDelayInsert($channel_stream['delay_stream_id'], $input_info['id'], $delay);
							
							$ret_delay_id[$k] = $ret_delay['delay']['id'];
			//				$ret_delay_id[$k] = $channel_stream['delay_stream_id'];

							if (!$ret_delay['result'])
							{
								$ret_delay_id_msg[$k]['id']   = $channel_stream['delay_stream_id'];
								$ret_delay_id_msg[$k]['name'] = $channel_stream['stream_name'];
								continue;
							}
							
							$sourceId = $ret_delay_id[$k];
							
							$sourceType = 2;						
						}
						else 
						{
							$sourceId = $input_info['id'];
							
							$sourceType = !$type ? 1 : 3;
						}
						
						//切播层
					
						$ret_chg = $this->mLivmms->inputChgStreamInsert($channel_stream['chg_stream_id'], $sourceId, $sourceType);
							
						if (!$ret_chg['result'])
						{
							$ret_chg_id_msg[$k]['id']   = $channel_stream['chg_stream_id'];
							$ret_chg_id_msg[$k]['name'] = $channel_stream['stream_name'];
							continue;
						}
						
						$ret_chg_id[$k] = $ret_chg['output']['id'];
			//			$ret_chg_id[$k] = $channel_stream['chg_stream_id'];
				*/
						//时移输出层流
						$wowzaip_chg 	= $this->settings['mms']['chg']['wowzaip'];
						$appName_chg 	= $this->settings['mms']['chg']['appName'];
						$streamName_chg = $channel_stream['chg_stream_id'] . $this->settings['mms']['chg']['suffix'];
						$output_url 	= hg_streamUrl($wowzaip_chg, $appName_chg, $streamName_chg);
						
						$ret_output = $this->mLivmms->outputStreamInsert($channel_stream['out_stream_id'], $application_id, $channel_stream['stream_name'], $output_url);
						
						if (!$ret_output['result'])
						{
							$ret_out_id_msg[$k]['id']   = $channel_stream['out_stream_id'];
							$ret_out_id_msg[$k]['name'] = $channel_stream['stream_name'];
							continue;
						}
						
						$ret_out_id[$k] = $ret_output['stream']['id'];
				//		$ret_out_id[$k] = $channel_stream['out_stream_id'];
						
						//live
						if ($this->mLive)
						{
							$_ret_output = $this->mLivmms->_outputStreamInsert($channel_stream['out_stream_id'], $application_id, $channel_stream['stream_name'], $output_url);
							
							if (!$_ret_output['result'])
							{
								$_ret_out_id_msg[$k]['id']   = $channel_stream['out_stream_id'];
								$_ret_out_id_msg[$k]['name'] = $channel_stream['stream_name'];
								continue;
							}
							
							$_ret_out_id[$k] = $_ret_output['stream']['id'];
				//			$_ret_out_id[$k] = $channel_stream['out_stream_id'];
						}
					}
				}
			}
			
			$ret_app_info[$channel_id]['name'] = $channel['name'];
			$ret_app_info[$channel_id]['code'] = $channel['code'];
			$ret_app_info[$channel_id]['output']['application_id']   = $ret_application_id[$channel_id];
			$ret_app_info[$channel_id]['output']['delay_stream_id']  = $ret_delay_id;
			$ret_app_info[$channel_id]['output']['chg_stream_id']  	 = $ret_chg_id;
			$ret_app_info[$channel_id]['output']['out_stream_id']    = $ret_out_id;
			
			$ret_app_error[$channel_id]['output']['application'] 	 = $ret_app_msg[$channel_id];
			$ret_app_error[$channel_id]['output']['delay_stream'] 	 = $ret_delay_id_msg;
			$ret_app_error[$channel_id]['output']['chg_stream'] 	 = $ret_chg_id_msg;
			$ret_app_error[$channel_id]['output']['out_stream'] 	 = $ret_out_id_msg;
			
			//live
			if ($this->mLive)
			{
				$ret_app_info[$channel_id]['_output']['application_id'] = $_ret_application_id[$channel_id];
				$ret_app_info[$channel_id]['_output']['out_stream_id']  = $_ret_out_id;
				
				$ret_app_error[$channel_id]['_output']['application']   = $_ret_app_msg[$channel_id];
				$ret_app_error[$channel_id]['_output']['out_stream'] 	= $_ret_out_id_msg;
			}
			
		}
		
		//返回添加失败信息
		$msg = $_msg = '';
		if (!empty($ret_app_error))
		{
			foreach ($ret_app_error AS $channel_id => $channel)
			{
				$msg .= $channel['output']['application']['name'] . '[' . $channel['output']['application']['code'] . ']';
				
				if ($channel['output']['delay_stream'])
				{
					$msg .= '{ delay:';
					foreach ($channel['output']['delay_stream'] AS $k => $stream)
					{
						$msg .= $stream['name'] . ' ';
					}
					$msg .= '},';
				}
				
				if ($channel['output']['chg_stream'])
				{
					$msg .= '{ chg:';
					foreach ($channel['output']['chg_stream'] AS $k => $stream)
					{
						$msg .= $stream['name'] . ' ';
					}
					$msg .= '},';
				}
				
				if ($channel['output']['out_stream'])
				{
					$msg .= '{ out:';
					foreach ($channel['output']['out_stream'] AS $k => $stream)
					{
						$msg .= $stream['name'] . ' ';
					}
					$msg .= '},';
				}
				
				//live
				if ($this->mLive)
				{
					$_msg .= $channel['_output']['application']['name'] . '[' . $channel['_output']['application']['code'] . ']';
					
					if ($channel['_output']['out_stream'])
					{
						$_msg .= '{ out:';
						foreach ($channel['_output']['out_stream'] AS $k => $stream)
						{
							$_msg .= $stream['name'] . ' ';
						}
						$_msg .= '},';
					}
				}
			}
		}
		
		//返回添加成功信息
		$ret = $_ret = '';
		if (!empty($ret_app_info))
		{
			foreach ($ret_app_info AS $channel_id => $channel)
			{
				$ret .= $channel['name'] . '[' . $channel['code'] . ']' . ' ';
				
				//live
				if ($this->mLive)
				{
					$_ret .= $channel['name'] . '[' . $channel['code'] . ']' . ' ';
				}
			}
		}
				
		$return = array(
			'output' => array(
				'msg' => $msg,
				'ret' => $ret,
			),
		);
		if ($this->mLive)
		{
			$return['_output'] =  array(
				'msg' => $_msg,
				'ret' => $_ret,
			);
		}
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 删除原始时移服务器输出数据
	 * $offset 索引参数
	 * $count 索引参数
	 * Enter description here ...
	 */
	function delete_channel()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 40;
		
		$channel_info = $this->mSync->get_channel_info($offset, $count);
		
		if (empty($channel_info))
		{
			$this->errorOutput('直播频道不存在或已被删除');
		}
		
		$msg = '';
		
		foreach ($channel_info AS $channel)
		{
			$ret_output = $this->mLivmms->outputApplicationOperate('delete', $channel['ch_id']);
			
			if (!$ret_output['result'])
			{
				$msg .= $channel['name'] . '[' . $channel['code'] . ']' . ' ';
				continue;
			}
		}
		
		$return = !$msg ? 'success' : $msg;
		
		$this->addItem($return);
		$this->output();
	}
	
	function sync_stream()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 40;
		
		$stream_info = $this->mSync->get_stream_info($offset, $count);
		
		if (empty($stream_info))
		{
			$this->errorOutput('备播信号不存在或已被删除');
		}
		
		$ret_stream = $ret_stream_msg = array();

		foreach ($stream_info AS $id => $stream)
		{
			$type = $stream['type'];
			
			$ret_input_id = $ret_file_msg = $ret_input_msg = $ret_stream_name = array();
			
			foreach ($stream['other_info']['input'] AS $k => $input_info)
			{
				$ret_stream_name[$k] = $input_info['name'];
				if (!empty($input_info['source_name']))
				{
					$ret_file = $this->mLivmms->inputFileListInsert($input_info['id'], implode(',', $input_info['source_name']));
	
					if (!$ret_file['result'])
					{
						$ret_input_msg[$k] = $input_info['name'];
						continue;
					}
					$ret_input_id[$k] = $ret_file['list']['id'];
			//		$ret_input_id[$k] = $input_info['id'];
				}
				elseif (!$type && empty($input_info['source_name']))
				{
					$ret_input = $this->mLivmms->inputStreamInsert($input_info['id'], $input_info['uri'], $input_info['wait_relay']);
				
					if (!$ret_input['result'])
					{
						$ret_input_msg[$k] = $input_info['name'];
						continue;
					}
					$ret_input_id[$k] = $ret_input['input']['id'];
			//		$ret_input_id[$k] = $input_info['id'];
				}
			}
			
			$ret_stream_msg[$id]['ch_name'] 	 = $stream['ch_name'];
			$ret_stream_msg[$id]['type'] 		 = $type;
			$ret_stream_msg[$id]['stream_name']  = $ret_input_msg;
			
			$ret_stream[$id]			 		 = $stream['ch_name'];
		//	$ret_stream[$id]['ch_name'] 		 = $stream['ch_name'];
		//	$ret_stream[$id]['stream_id'] 	 	 = $ret_input_id;
		}
		
		//返回添加失败信息
		$msg = '';
		if (!empty($ret_stream_msg))
		{
			foreach ($ret_stream_msg AS $id => $stream)
			{
				$msg .= $stream['ch_name'];
				$type = $stream['type'] ? 'file' : 'stream';
				if ($stream['stream_name'])
				{
					$msg .= '{' . $type  . ':' . implode(',', $stream['stream_name']) . '} ';
				}
			}
		}
		
		//返回添加成功信息
		$ret = '';
		if (!empty($ret_stream))
		{
			$ret .= implode(',', $ret_stream);
		}
		
		$return = array(
			'msg' => $msg,
			'ret' => $ret,
		);
		
		$this->addItem($return);
		$this->output();
	}
	
	public function delete_stream()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 40;
		
		$stream_info = $this->mSync->get_stream_info($offset, $count);
		
		if (empty($stream_info))
		{
			$this->errorOutput('备播信号不存在或已被删除');
		}
		
		$ret_stream_msg = array();
		foreach ($stream_info AS $id => $stream)
		{
			$ret_stream_name = array();
			foreach ($stream['other_info']['input'] AS $k => $input_info)
			{
				if (!$type)
				{
					$ret_delete = $this->mLivmms->inputStreamOperate($input_info['id']);
				}
				else 
				{
					$ret_delete = $this->mLivmms->inputFileListDelete($input_info['id']);
				}
				
				if (!$ret_delete['result'])
				{
					$ret_stream_name[$k] = $input_info['name'];
					continue;
				}
			}
			
			$ret_stream_msg[$id]['ch_name'] 	= $stream['ch_name'];
			$ret_stream_msg[$id]['type'] 		= $stream['type'] ? 'file' : 'stream';
			$ret_stream_msg[$id]['stream_name'] = $ret_stream_name;
		}
		
		$msg = '';
		
		if (!empty($ret_stream_msg))
		{
			foreach ($ret_stream_msg AS $stream_msg)
			{
				if (!empty($stream_msg['stream_name']))
				{
					$msg .= $stream_msg['ch_name'] . '[' . $stream_msg['type'] . ':' . implode(',', $stream_msg['stream_name']) . '] ';
				}
			}
		}
		
		$return = !$msg ? 'success' : $msg;
		
		$this->addItem($return);
		$this->output();
	}
	
	function delete_delay_chg_stream()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 40;
		
		$channel_info = $this->mSync->get_channel_info($offset, $count);
		
		if (empty($channel_info))
		{
			$this->errorOutput('直播频道不存在或已被删除');
		}
		
		$ret_app_info = $ret_app_error = $ret_app_msg = $_ret_app_msg = $ret_app = $_ret_app = array();
		
		foreach ($channel_info AS $channel_id => $channel)
		{
			$delay = $channel['live_delay'];
			
			$ret_output = $this->mLivmms->outputApplicationOperate('delete', $channel['ch_id']);
			
			if (!$ret_output['result'])
			{
				$ret_app_msg[$channel_id] = $channel['code'];
				continue;
			}
			
			$ret_app[$channel_id] = $channel['code'];
			
			//live
			if ($this->mLive)
			{
				$_ret_output = $this->mLivmms->_outputApplicationOperate('delete', $channel['ch_id']);
				
				if (!$_ret_output['result'])
				{
					$_ret_app_msg[$channel_id] = $channel['code'];
					continue;
				}
				
				$_ret_app[$channel_id] = $channel['code'];
			}
			
			$ret_delay_msg = $ret_chg_msg = $ret_delay = $ret_chg = array();
			
			foreach ($channel['channel_stream'] AS $k => $channel_stream)
			{
				if ($channel_stream['delay_stream_id'])
				{
					$ret_delay_delete = $this->mLivmms->inputDelayOperate('delete', $channel_stream['delay_stream_id']);
					
					if (!$ret_delay_delete['result'])
					{
						$ret_delay_msg[$k] = $channel_stream['stream_name'];
						continue;
					}
					
					$ret_delay[$k] = $channel_stream['stream_name'];
				}
				
				$ret_chg_delete = $this->mLivmms->inputChgStreamOperate('delete', $channel_stream['chg_stream_id']);
					
				if (!$ret_chg_delete['result'])
				{
					$ret_chg_msg[$k] = $channel_stream['stream_name'];
					continue;
				}
				
				$ret_chg[$k] = $channel_stream['stream_name'];
			}
			
			$ret_app_info[$channel_id]['application']  = $ret_app[$channel_id];
			$ret_app_error[$channel_id]['application'] = $ret_app_msg[$channel_id];
			
			//live
			if ($this->mLive)
			{
				$ret_app_info[$channel_id]['_application']  = $_ret_app[$channel_id];
				$ret_app_error[$channel_id]['_application'] = $_ret_app_msg[$channel_id];
			}
			
			if ($delay)
			{
				$ret_app_info[$channel_id]['delay']	 = $ret_delay;
				$ret_app_error[$channel_id]['delay'] = $ret_delay_msg;
			}
			
			$ret_app_info[$channel_id]['chg']  = $ret_chg;
			$ret_app_error[$channel_id]['chg'] = $ret_chg_msg;
		}

		//返回删除失败信息
		$msg = $_msg = '';
		if (!empty($ret_app_error))
		{
			foreach ($ret_app_error AS $channel_id => $channel)
			{
				$msg .= $channel['application'] . '{';
				$_msg .= $channel['_application'] . ' ';
				if (!empty($channel['delay']))
				{
					$msg .= 'delay:' . implode(',', $channel['delay']) . ' ';
				}
				$msg .= 'chg:' .implode(',', $channel['chg']) . '} ';
			}
		}
		
		//返回删除成功信息
		$ret = $_ret = '';
		if (!empty($ret_app_info))
		{
			foreach ($ret_app_info AS $channel_id => $channel)
			{
				$ret .= $channel['application'] . '{';
				$_ret .= $channel['_application'] . ' ';
				if (!empty($channel['delay']))
				{
					$ret .= 'delay:' . implode(',', $channel['delay']) . ' ';
				}
				$ret .= 'chg:' .implode(',', $channel['chg']) . '} ';
			}
		}
		
		$return = array(
			'ret'  => $ret,
			'_ret' => $_ret,
			'msg'  => $msg,
			'_msg' => $_msg,
		);

		$this->addItem($return);
		$this->output();
	}
	
	function unknow()
	{
		$this->errorOutput('未实现的空方法');
	}
}

$out = new syncApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>