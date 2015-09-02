<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: dvr_checked.php 17159 2013-01-29 17:00:03Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','dvr_checked_log');
require('global.php');
class dvrCheckedApi extends BaseFrm
{
	private $mLivemms;
	private $mDvrCheckedLog;
	private $mServerConfig;
	function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
		require_once CUR_CONF_PATH . 'lib/dvr_checked_log.class.php';
		$this->mDvrCheckedLog = new dvrCheckedLog();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * dvr检测接口
	 * 如果dvr不存在且流是开启的 则重启并且记录
	 * Enter description here ...
	 */
	function dvr_checked()
	{
		$channel_info = $this->mDvrCheckedLog->get_channel_stream_info();
		
		$ret = array();
		if (!empty($channel_info))
		{
			$server_id = array();
			foreach ($channel_info AS $v)
			{
				$server_id[] = $v['server_id'];
			}
			//服务器配置
			if (!empty($server_id))
			{
				$server_id = implode(',', @array_unique($server_id));
				$server_infos 	= $this->mServerConfig->get_server_config($server_id);
			}
			foreach ($channel_info AS $v)
			{
				$server_info 	= $server_infos[$v['server_id']];
				if ($server_info['core_in_host'])
				{
					if ($server_info['is_dvr_output'])
					{
						$wowzaip = $server_info['dvr_in_host'] . ':' . $server_info['dvr_out_port'];
					}
					else 
					{
						$wowzaip = $server_info['core_in_host'] . ':' . $server_info['core_out_port'];
					}
				}
				else 
				{
					if ($this->settings['wowza']['dvr_output_server'])
					{
						$wowzaip = $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
					}
					else 
					{
						$wowzaip = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['out_port'];
					}
				}
				
				$suffix	 = $this->settings['wowza']['dvr_output']['suffix'];
				
				$v['out_url'] = $v['stream_name'] ? hg_streamUrl($wowzaip, $v['code'], $v['stream_name'] . $suffix, 'flv', '', 'dvr') : '';
			
				$ret[$v['id']]['channel_id'] = $v['channel_id'];
				$ret[$v['id']]['name'] 		 = $v['name'];
				$ret[$v['id']]['code'] 		 = $v['code'];
				$ret[$v['id']]['stream_name']= $v['stream_name'];
				$ret[$v['id']]['out_url']	 = $v['out_url'];
				$ret[$v['id']]['status']	 = 1;
			//	$time = microtime(true);
				if (!$v['out_url'])
				{
					$ret[$v['id']]['status'] = 0;
					continue;
				}
				
				$ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, $v['out_url']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 2);
		        curl_exec($ch);
				$head_info = curl_getinfo($ch);
		        curl_close($ch);
			//	$ret[$v['id']]['time'] = microtime(true) - $time;
				if ($head_info['http_code'] != 200)
				{
					if ($server_info['core_in_host'])
					{
						$host			= $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
						$apidir_input	= $server_info['input_dir'];
						$apidir_output	= $server_info['output_dir'];
						
						if ($server_info['is_dvr_output'])
						{
							$dvr_host	= $server_info['dvr_in_host'] . ':' . $server_info['dvr_in_port'];
						}
						else 
						{
							$dvr_host	= $host;
						}
					}
					else 
					{
						$host			= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
						$apidir_input	= $this->settings['wowza']['core_input_server']['input_dir'];
						$apidir_output	= $this->settings['wowza']['core_input_server']['output_dir'];
						
						if ($this->settings['dvr_output_server'])
						{
							$dvr_host	= $this->settings['dvr_output_server']['host'] . ':' . $this->settings['dvr_output_server']['port'];
						}
						else 
						{
							$dvr_host	= $host;
						}
					}
					
					$ret[$v['id']]['status'] = 0;
					//停止
					//延时层
					if ($v['delay_stream_id'])
					{
						$ret_delay = $this->mLivemms->inputDelayOperate($host, $apidir_input, 'stop', $v['delay_stream_id']);
					}
					//切播层
					if ($v['chg_stream_id'])
					{
						$ret_chg = $this->mLivemms->inputChgStreamOperate($host, $apidir_input, 'stop', $v['chg_stream_id']);
					}
					//输出层
					if ($v['out_stream_id'])
					{
						$ret_output = $this->mLivemms->outputStreamOperate($dvr_host, $apidir_output, 'stop', $v['out_stream_id']);
					}
					
					sleep($this->settings['dvr_sleep_time']);
					
					//启动
					//延时层
					if ($v['delay_stream_id'])
					{
						$ret_delay = $this->mLivemms->inputDelayOperate($host, $apidir_input, 'start', $v['delay_stream_id']);
					}
					//切播层
					if ($v['chg_stream_id'])
					{
						$ret_chg = $this->mLivemms->inputChgStreamOperate($host, $apidir_input, 'start', $v['chg_stream_id']);
					}
					//输出层
					if ($v['out_stream_id'])
					{
						$ret_output = $this->mLivemms->outputStreamOperate($dvr_host, $apidir_output, 'start', $v['out_stream_id']);
					}
					//记录数据
					$add_input = array(
						'channel_stream_id'	=> $v['id'],
						'channel_id'		=> $v['channel_id'],
						'name'				=> $v['name'],
						'code'				=> $v['code'],
						'stream_id'			=> $v['stream_id'],
						'server_id'			=> $v['server_id'],
						'stream_name'		=> $v['stream_name'],
						'delay_stream_id'	=> $v['delay_stream_id'],
						'chg_stream_id'		=> $v['chg_stream_id'],
						'out_stream_id'		=> $v['out_stream_id'],
						'create_time'		=> TIMENOW,
					);
					$ret_input = $this->mDvrCheckedLog->create($add_input);
				}
			}
		}
		$this->addItem($ret);
		$this->output();
	}
}

$out = new dvrCheckedApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'dvr_checked';
}
$out->$action();
?>