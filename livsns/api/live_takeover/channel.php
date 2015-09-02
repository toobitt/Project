<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 37953 2014-06-30 08:16:10Z zhuld $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR."global.php");
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','live_takeover');
class channelApi extends outerReadBase
{
	private $mChannel;
	private $mImgUrl;
	function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/channel.class.php';
		$this->mChannel = new channel();
		
		$ret_img_url   = $this->mChannel->get_img_url();
		$this->mImgUrl = '';
		if (!empty($ret_img_url))
		{
			$this->mImgUrl = $ret_img_url['define']['IMG_URL'];
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$condition 	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$count 		= $this->input['count'] ? intval($this->input['count']) : 20;
		$appid		= intval($this->input['appid']);
		
		$info = $this->mChannel->show($condition, $offset, $count);
		
		if ($info)
		{
			foreach ($info AS $v)
			{
				if ($v['client_logo'][$appid])
				{
					unset($v['client_logo'][$appid]['appid'], $v['client_logo'][$appid]['appname']);
					$v['logo_rectangle'] = $v['client_logo'][$appid];
				}
				$v['square'] = $v['logo_rectangle'];
				unset($v['client_logo']);
				
				if ($v['logo_rectangle'])
				{
					$v['logo_rectangle_url'] = hg_material_link($v['logo_rectangle']['host'], $v['logo_rectangle']['dir'], $v['logo_rectangle']['filepath'], $v['logo_rectangle']['filename'], '112x43/');
				}
				
				//频道截图
				
				if (!empty($v['snap']))
				{
					if (strstr($v['snap']['dir'], '{&#036;time}'))
					{
						$v['snap']['dir'] = str_replace('{&#036;time}', TIMENOW . '000', $v['snap']['dir']);
					}
				}
				else 
				{
					if (!$v['is_audio'] && $v['sys_id'])
					{
						$v['snap'] = array(
							'host' => $this->mImgUrl . LIVE_CONTROL_LIST_PREVIEWIMG_URL, 
							'dir' => '',
							'filepath' => date('Y') . '/' . date('m') . '/',
							'filename' => 'live_' . $v['sys_id'] . '.png?time=' . TIMENOW
						);
					}
					else 
					{
						$v['snap'] = $v['logo_rectangle'];
					}
				}
				
				$channel_stream = $record_stream = array();
				if (!empty($v['channel_stream']))
				{
					foreach ($v['channel_stream'] AS $kk => $vv)
					{
						if (!$v['is_sys'])
						{
							if ($vv['output_url'])
							{
								$vv['output_url'] = $vv['url'] . '/' . $vv['output_url'];
							}
							
							if ($vv['m3u8'])
							{
								$vv['m3u8'] 	  = $vv['url'] . '/' . $vv['m3u8'];
							}

							if ($vv['timeshift_url'])
							{
								$vv['timeshift_url']  = $vv['url'] . '/' . $vv['timeshift_url'];
							}
						}
						$vv['live_url']   	  = $vv['output_url'];
						$vv['live_m3u8']	  = $vv['m3u8'];
						$vv['live_url_rtmp']  = $vv['output_url_rtmp'];
						
					//	unset($vv['url'], $vv['timeshift_url']);
						
						$channel_stream[]	  = $vv;
						
						$record_stream[$kk]['output_url'] 		= $vv['output_url'];
						$record_stream[$kk]['output_url_rtmp'] 	= $vv['output_url_rtmp'];
						$record_stream[$kk]['m3u8'] 			= $vv['m3u8'];
					}
					$v['has_stream'] = 1;
				}
				else
				{
					$v['has_stream'] = 0;
				}
				
				$v['channel_stream'] = $channel_stream;
				$v['record_stream']  = $record_stream;
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	public function detail(){}
	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mChannel->count($condition);
		$this->addItem($info);
		$this->output();
	}
	
	private function get_condition()
	{
		$condition = ' AND status = 1';
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND name like \'%' . trim($this->input['k']) . '%\'';
		}
		
		if (isset($this->input['id']) && $this->input['id'])
		{
			$this->input['id'] = hg_filter_ids($this->input['id']);
			$condition .= " AND id IN (" . trim($this->input['id']) . ")";
		}
		
		if (isset($this->input['channel_id']) && $this->input['channel_id'])
		{
			$this->input['channel_id'] = hg_filter_ids($this->input['channel_id']);
			$condition .= " AND id IN (" . trim($this->input['channel_id']) . ")";
		}
		
		if (isset($this->input['is_mobile_phone']))
		{
			$condition .= " AND is_mobile_phone = " . intval($this->input['is_mobile_phone']);
		}
		
		if (isset($this->input['is_control']))
		{
			$condition .= " AND is_control = " . intval($this->input['is_control']);
		}
		
		if (isset($this->input['is_audio']))
		{
			$condition .= " AND is_audio = " . intval($this->input['is_audio']);
		}
		if (intval($this->input['get_record']))
		{
			$condition .= " AND can_record = 1";
		}
		
		if (isset($this->input['audio_only']))
		{
			$condition .= " AND is_audio = " . intval($this->input['audio_only']);
		}
		
		if (isset($this->input['is_sys']) && intval($this->input['is_sys']) != -1)
		{
			$condition .= " AND is_sys = " . intval($this->input['is_sys']);
		}
	
		if (isset($this->input['server_id']))
		{
			$condition .= " AND server_id = " . intval($this->input['server_id']);
		}
		return $condition;
	}
}

$out = new channelApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>