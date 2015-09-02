<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel_checked.php 17966 2013-02-26 06:11:46Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','channel_checked');
require('global.php');
class channelCheckedApi extends adminBase
{
	private $mLivmms;
	private $mLive;
	private $mSync;
	function __construct()
	{
		parent::__construct();

		require_once CUR_CONF_PATH . 'lib/livmms.class.php';
		$this->mLivmms = new livmms();
		
		require_once CUR_CONF_PATH . 'lib/sync.class.php';
		$this->mSync = new sync();
		
		$this->mLive = $this->settings['wowza']['live_output_server'];
	}

	function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 检测直播线路
	 * $input	输入
	 * $delay	延时
	 * $chg		切播
	 * $output	时移输出
	 * $dvr		dvr
	 * $_output	直播输出
	 * $m3u8	时移手机
	 * $_m3u8	直播手机
	 * Enter description here ...
	 */
	function channel_checked()
	{
		$channel_id = $this->input['channel_id'];
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		$type = $this->input['type'];
		
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 40;
		$condition = ' AND t1.id IN (' . $channel_id . ')';
		$channel_info = $this->mSync->get_channel_info($offset, $count, $condition);

		if (empty($channel_info))
		{
			$this->errorOutput('频道不存在或已被删除');
		}
		
		$ret_channel = array();
		foreach ($channel_info AS $channel_id => $channel)
		{
			foreach ($channel['other_info']['input'] AS $input_info)
			{
				$ret_channel_stream[] = array();
				foreach ($channel['channel_stream'] AS $k => $channel_stream)
				{
					if ($input_info['name'] == $channel_stream['stream_name'])
					{
						$ret_channel_stream[$k]['stream_name']     = $channel_stream['stream_name'];
						$ret_channel_stream[$k]['input_stream_id'] = $input_info['id'];
						$ret_channel_stream[$k]['delay_stream_id'] = $channel_stream['delay_stream_id'];
						$ret_channel_stream[$k]['chg_stream_id']   = $channel_stream['chg_stream_id'];
						$ret_channel_stream[$k]['out_stream_id']   = $channel_stream['out_stream_id'];

						if (!empty($channel['server_info']))
						{
							//输入
							$wowzaip_input 	= $channel['server_info']['core_in_host'];
							
							//切播
							$wowzaip_chg	= $channel['server_info']['core_in_host'] . ':' . $channel['server_info']['core_out_port'];
						}
						else 
						{
							$wowzaip_input 	= $this->settings['wowza']['core_input_server']['host'];
							
							$wowzaip_chg	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['out_port'];
						}
						
						//延时
						$wowzaip_delay	= $wowzaip_input;
						//输出
						$wowzaip_output	= $wowzaip_chg;
						
						$app_name_input	= $this->settings['wowza']['input']['app_name'];
						$suffix_input 	= $channel['type'] ? $this->settings['wowza']['list']['suffix'] : $this->settings['wowza']['input']['suffix'];
						
						$app_name_delay	= $this->settings['wowza']['delay']['app_name'];
						$suffix_delay	= $this->settings['wowza']['delay']['suffix'];
						
						$app_name_chg	= $this->settings['wowza']['chg']['app_name'];
						$suffix_chg		= $this->settings['wowza']['chg']['suffix'];

						$suffix_output	= $this->settings['wowza']['dvr_output']['suffix'];
						
						//输入
						$ret_channel_stream[$k]['input_url'] = '';
						if (isset($this->input['input']) && $input_info['id'])
						{
							$ret_channel_stream[$k]['input_url'] = hg_streamUrl($wowzaip_input, $app_name_input, $input_info['id'] . $suffix_input);
						}
						
						//延时
						$ret_channel_stream[$k]['delay_url'] = '';
						if (isset($this->input['delay']) && $channel_stream['delay_stream_id'])
						{
							$ret_channel_stream[$k]['delay_url'] = hg_streamUrl($wowzaip_delay, $app_name_delay, $channel_stream['delay_stream_id'] . $suffix_delay);
						}
						
						//切播
						$ret_channel_stream[$k]['chg_url'] = '';
						if (isset($this->input['chg']) && $channel_stream['chg_stream_id'])
						{
							$ret_channel_stream[$k]['chg_url']   = hg_streamUrl($wowzaip_chg, $app_name_chg, $channel_stream['chg_stream_id'] . $suffix_chg);
						}
						
						//live
						$ret_channel_stream[$k]['_out_url'] = '';
						if ($this->mLive)
						{
							if ($channel['server_info']['live_in_host'])
							{
								$_wowzaip_output = $channel['server_info']['live_in_host'] . ':' . $channel['server_info']['live_out_port'];
							}
							else
							{
								$_wowzaip_output = $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
							}
							
							$_suffix_output 	= $this->settings['wowza']['live_output']['suffix'];
							if (isset($this->input['_output']) && $channel_stream['out_stream_name'])
							{
								$ret_channel_stream[$k]['_out_url'] = hg_streamUrl($_wowzaip_output, $channel['code'], $channel_stream['out_stream_name'] . $_suffix_output, 'flv');
							}
						}
						
						//输出
						$ret_channel_stream[$k]['out_url'] = '';
						if (isset($this->input['output']) && $channel_stream['out_stream_name'])
						{
							$ret_channel_stream[$k]['out_url'] = hg_streamUrl($wowzaip_output, $channel['code'], $channel_stream['out_stream_name'] . $suffix_output, 'flv');
						}
						
						//dvr
						$ret_channel_stream[$k]['dvr_url'] = '';
						if (isset($this->input['dvr']) && $channel_stream['out_stream_name'])
						{
							$ret_channel_stream[$k]['dvr_url'] = hg_streamUrl($wowzaip_output, $channel['code'], $channel_stream['out_stream_name'] . $suffix_output, 'flv', '', 'dvr');
						}
						
						$ret_channel_stream[$k]['m3u8_url'] = '';
						$ret_channel_stream[$k]['_m3u8_url'] = '';
						if ($channel['open_ts'])
						{
							//live
							if ($this->mLive)
							{
								if (isset($this->input['_m3u8']) && $channel_stream['out_stream_name'])
								{
									$ret_channel_stream[$k]['_m3u8_url'] = hg_streamUrl($_wowzaip_output, $channel['code'], $channel_stream['out_stream_name'] . $_suffix_output, 'm3u8');
								}
							}
							
							if (isset($this->input['m3u8']) && $channel_stream['out_stream_name'])
							{
								$ret_channel_stream[$k]['m3u8_url'] = hg_streamUrl($wowzaip_output, $channel['code'], $channel_stream['out_stream_name'] . $suffix_output, 'm3u8');
							}
						}
					}
				}
			}
			
			$channel['channel_stream'] = $ret_channel_stream;
			
			unset ($channel['other_info'], $channel['server_info']);
			
			$ret_channel[$channel_id] = $channel;
		}
		$this->addItem($ret_channel);
		$this->output();
	}
}

$out = new channelCheckedApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'channel_checked';
}
$out->$action();
?>