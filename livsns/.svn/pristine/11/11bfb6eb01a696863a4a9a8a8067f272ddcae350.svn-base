<?php
/***************************************************************************
* $Id: sys_update.php 22758 2013-05-24 07:36:11Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','build_m3u8');
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . 'global.php');
require(CUR_CONF_PATH . 'lib/functions.php');
require(CUR_CONF_PATH . 'lib/m3u8.class.php');
class build_m3u8 extends outerReadBase
{
	private $mSequence = 0;
	private $mTimenow = 0;
	private $mChannelinfo = array();
	function __construct()
	{
		parent::__construct();
		if ($this->input['m2o_ckey'] != CUSTOM_APPKEY)
		{
			exit('No Auth');
		}
		$this->mTimenow = time() * 1000;
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$mkm3u8 = new m3u8();
		$channel_streams = explode(',', $this->input['channel_stream']);
		if ($this->settings['open_push_cdn'])
		{
			include_once(ROOT_PATH . 'lib/class/cdn.class.php');
			$cdn = new cdn();
        }
		foreach ($channel_streams AS $channel_stream)
		{
			$channel_stream = trim($channel_stream);
			if (!$this->verify_channel_stream($channel_stream))
			{
				continue;
			}
			$ts = $mkm3u8->get_ts($channel_stream, $this->mChannelinfo['channel']['table_name']);
			if ($ts)
			{
				$tmp = explode('_', $channel_stream);
				$m3u8 = $mkm3u8->build_live_m3u8($channel_stream, $ts, $this->mChannelinfo['channel']['config']['ts_host']);
				$dir = DATA_DIR . $tmp[0] . '/' . $tmp[1] . '/';
				hg_mkdir($dir);
				file_put_contents($dir . 'live.m3u8', $m3u8);
				if($this->settings['ts_host'])
				{
					file_put_contents($dir . 'mlive.m3u8', str_replace($this->mChannelinfo['channel']['config']['ts_host'], $this->settings['ts_host'], $m3u8));
				}		
				if ($this->settings['open_push_cdn'])
				{
					$cdn->push($this->mChannelinfo['channel']['config']['out_host'] . $tmp[0] . '/' . $tmp[1] . '/live.m3u8','');
				}
			}
		}
	}

	private function verify_channel_stream($channel_stream)
	{
		if ($channel_stream)
		{
			$tmp = explode('_', $channel_stream);
			$this->input['channel'] = $tmp[0];
			$this->input['stream'] = $tmp[1];
		}
		$channel_id = addslashes($this->input['channel']);
		if (!@include(CACHE_DIR . 'channel/' . $channel_id . '.php'))
		{
			include_once CUR_CONF_PATH . 'lib/channel.class.php';
			$mChannel = new channel();
			$mChannel->cache_channel($channel_id);
			@include(CACHE_DIR . 'channel/' . $channel_id . '.php');
		}
		if (!$channel_info['channel'])
		{
			return false;
		}
		if (!$channel_info['channel']['status'])
		{
			return false;
		}
		$this->mChannelinfo = $channel_info;
		if ($this->input['stream'])
		{
			if (!array_key_exists($this->input['stream'], $this->mChannelinfo['stream']))
			{
				return false;
			}
		}
		return true;
	}

	private function parse_m3u8($m3u8)
	{
		$m3u8_list = array();
		preg_match_all('/\#EXTINF\:([0-9\.]+)\,[\r\n]*(.*?\.ts)/is', $m3u8, $match);
		if ($match)
		{
			foreach ($match[1] AS $k => $v)
			{
				$tmp = explode('/', $match[2][$k]);
				$start_time = intval($tmp[count($tmp) - 1]);
				$m3u8_list[] = array(
					'dur' => intval($v * 1000),
					'ts' => $match[2][$k],
					'start_time' => $start_time,
				);
			}
		}
		return $m3u8_list;
	}

	/**
	* 无需验证授权
	*/
	protected function verifyToken()
	{
	}

	public function detail()
	{
	}
	public function count()
	{
	}
}
$out = new build_m3u8();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
