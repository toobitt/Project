<?php
/***************************************************************************
* $Id: sys_update.php 22758 2013-05-24 07:36:11Z lijiaying $
***************************************************************************/
define('WITHOUT_DB', true);
define('MOD_UNIQUEID','dvr');
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . 'global.php');
require(CUR_CONF_PATH . 'lib/functions.php');
require(CUR_CONF_PATH . 'lib/m3u8.class.php');
class live extends outerReadBase
{
	private $mSequence = 0;
	private $mTimenow = 0;
	private $mChannelinfo = array();
	function __construct()
	{
		parent::__construct();
		$this->mTimenow = time() * 1000;
		if (!$this->input['debug'])
		{
			header('Content-Type: application/vnd.apple.mpegurl');
		}
		$channel_stream = $this->input['channel_stream'];
		if ($channel_stream)
		{
			$tmp = explode('_', $channel_stream);
			$this->input['channel'] = $tmp[0];
			$this->input['stream'] = $tmp[1];
		}
		$channel_id = addslashes($this->input['channel']);
		if (!@include(CACHE_DIR . 'channel/' . $channel_id . '.php'))
		{
			$this->db = hg_ConnectDB();
			include_once CUR_CONF_PATH . 'lib/channel.class.php';
			$mChannel = new channel();
			$mChannel->cache_channel($channel_id);
			@include(CACHE_DIR . 'channel/' . $channel_id . '.php');
		}
		if (!$channel_info['channel'])
		{
			$this->errorOutput('NO_CHANNEL_NAME');
		}
		if (!$channel_info['channel']['status'])
		{
			$this->errorOutput('CHANNEL_STOP');
		}
		$this->mChannelinfo = $channel_info;
		if ($this->input['stream'])
		{
			if (!array_key_exists($this->input['stream'], $this->mChannelinfo['stream']))
			{
				$this->errorOutput('NO_STREAM_NAME');
			}
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$dir = DATA_DIR . $this->mChannelinfo['channel']['code'] . '/';
		hg_mkdir($dir);
		$start_time = intval($this->input['starttime']);
		$duration = intval($this->input['duration']);
		$static = true;
		if ($start_time)
		{
			$m3u8 = $start_time . '.m3u8';
			$m3u8name = $m3u8;
			
			if ($duration)
			{
				if ($this->mChannelinfo['channel']['time_shift'])
				{
					if (($start_time + $duration) < $this->mTimenow)
					{
						$m3u8 = $start_time . ',' . $duration . '.m3u8';
						$m3u8name = $m3u8;
					}
					else
					{
						$static = false;
					}
				}
				else
				{
					$m3u8 = 'live.m3u8';
					$m3u8name = 'playlist.m3u8';
				}
			}
		}
		else
		{
			$m3u8 = 'live.m3u8';
			$m3u8name = 'playlist.m3u8';
		}
		$stream_m3u8 = '#EXTM3U
';
		$default_stream = '';
		foreach ($this->mChannelinfo['stream'] AS $name => $val)
		{
			if (!$val['bitrate'])
			{
				$bit = 600000;
			}
			else
			{
				$bit = $val['bitrate'] . '000';
			}
			
			$path = '/' . $this->mChannelinfo['channel']['code'] . '/' . $name . '/' . $m3u8;
			$sign = hg_sign_uri($path, $this->settings['live_expire'], $this->settings['sign_type']);
			$stream_m3u8 .= '#EXT-X-STREAM-INF:PROGRAM-ID=' . $this->mChannelinfo['channel']['id'] . ',BANDWIDTH=' . $bit . '
' . $name . '/' . $m3u8 . $sign[0] . '
';
		}
		if ($static)
		{
			file_put_contents($dir . $m3u8name, $stream_m3u8);
		}
		echo $stream_m3u8;		
		if ($this->settings['open_push_cdn'])
		{
			include_once(ROOT_PATH . 'lib/class/cdn.class.php');
			$cdn = new cdn();
			$cdn->push($this->mChannelinfo['channel']['config']['out_host'] . $this->mChannelinfo['channel']['code'] . '/' . $m3u8name,'');
		}
	}

	public function livebystart()
	{
		$stream = addslashes($this->input['stream']);
		$starttime = intval($this->input['starttime']);
		$dir = DATA_DIR . $this->mChannelinfo['channel']['code'] . '/' . $stream . '/';
		hg_mkdir($dir);
		$m3u8name = 'cache_' . $starttime . '.m3u8';
		if (!is_file($dir . $m3u8name) || (time() - filemtime($dir . $m3u8name)) > 5)
		{
			$this->db = hg_ConnectDB();
			$mkm3u8 = new m3u8();
			$channel_stream = $this->mChannelinfo['channel']['code'] . '_' . $stream;
			$table_name = $this->mChannelinfo['channel']['table_name'];
			$ts = $mkm3u8->get_ts($channel_stream, $table_name, $starttime);
			$m3u8 = $this->build_m3u8($ts, '', $this->mChannelinfo['channel']['config']['ts_host']);
			file_put_contents($dir . $m3u8name, $m3u8);
		}
		else
		{
			$m3u8 = file_get_contents($dir . $m3u8name);
		}	
		if (!$m3u8)
		{
			$this->header(404);
		}
		if($this->settings['ts_host'])
		{
			$m3u8 = str_replace($this->mChannelinfo['channel']['config']['ts_host'], $this->settings['ts_host'], $m3u8);
		}
		echo $m3u8;
	}

	public function live()
	{
		$this->db = hg_ConnectDB();
		$mkm3u8 = new m3u8();
		$channel_streams = $this->input['channel_stream'];
		$table_name = $this->mChannelinfo['channel']['table_name'];
		$ts = $mkm3u8->get_ts($channel_stream, $table_name);
		if ($ts)
		{
			$tmp = explode('_', $channel_stream);
			$m3u8 = $mkm3u8->build_live_m3u8($channel_stream, $ts, $this->mChannelinfo['channel']['config']['ts_host']);
			$dir = DATA_DIR . $tmp[0] . '/' . $tmp[1] . '/';
			hg_mkdir($dir);
			file_put_contents($dir . 'live.m3u8', $m3u8);
			$urls = $this->mChannelinfo['channel']['config']['out_host'] . $tmp[0] . '/' . $tmp[1] . '/live.m3u8';
			if($this->settings['ts_host'])
			{
				$m3u8 = str_replace($this->mChannelinfo['channel']['config']['ts_host'], $this->settings['ts_host'], $m3u8);
				file_put_contents($dir . 'mlive.m3u8', $m3u8);
				$urls .= "\n" . $this->mChannelinfo['channel']['config']['out_host'] . $tmp[0] . '/' . $tmp[1] . '/mlive.m3u8';
			}		
			if ($this->settings['open_push_cdn'])
			{
				include_once(ROOT_PATH . 'lib/class/cdn.class.php');
				$cdn = new cdn();
				$cdn->push($urls,'');
			}
		}		
		if (!$m3u8)
		{
			$this->header(404);
		}
		echo $m3u8;
	}

	public function dvr()
	{
		$stream = addslashes($this->input['stream']);
		$start_time = intval($this->input['starttime']);
		$duration = intval($this->input['duration']);
		$endtime = $start_time + $duration;
		if ($endtime > $this->mTimenow)
		{
			$endtime = $this->mTimenow;
		}
		$time_shift = $this->mChannelinfo['channel']['time_shift'];
		$shift_stime = $this->mTimenow - $time_shift * 3600000;
		if ($start_time < $shift_stime)
		{
			$start_time = $shift_stime;
		}
		$this->mChannelinfo['channel']['table_name'] = $this->mChannelinfo['channel']['table_name'] ? $this->mChannelinfo['channel']['table_name'] : 'dvr';
		$channel_stream = $this->mChannelinfo['channel']['code'] . '_' . $stream;
		$this->db = hg_ConnectDB();
		$sql = 'SELECT * FROM ' . DB_PREFIX . $this->mChannelinfo['channel']['table_name'] ." WHERE stream_name='$channel_stream' AND start_time >= $start_time AND start_time < $endtime ORDER BY start_time ASC ";
		$q = $this->db->query($sql);
		$ts = array();
		while($r = $this->db->fetch_array($q))
		{
			$ts[$r['level']][] = $r;
		}
		$ts = $this->get_dvr_ts($ts);
		$sdate = date('Y-m-d', $start_time / 1000);
		$edate = date('Y-m-d', $endtime / 1000);
		$shield_time_zone = $this->get_shield($sdate);
		if ($sdate != $edate)
		{
			$shield_time_zone = array_merge($shield_time_zone, $this->get_shield($edate));
		}
		$m3u8 = $this->build_m3u8($ts, '#EXT-X-ENDLIST', rtrim($this->mChannelinfo['channel']['config']['ts_host'], '/'), $shield_time_zone);
		$dir = DATA_DIR . $this->mChannelinfo['channel']['code'] . '/' . $stream . '/';
		hg_mkdir($dir);
		$m3u8name = $start_time . ',' . $duration . '.m3u8';
		$m3u8 = trim($m3u8);
		if (!$m3u8)
		{
			$this->header(404);
		}
		file_put_contents($dir . $m3u8name, $m3u8);	
		$urls = $this->mChannelinfo['channel']['config']['out_host'] . $this->mChannelinfo['channel']['code'] . '/' . $stream . '/' . $m3u8name;
		$sm3u8 = $m3u8;
		if($this->settings['ts_host'])
		{
			$m3u8 = str_replace($this->mChannelinfo['channel']['config']['ts_host'], $this->settings['ts_host'], $m3u8);
			file_put_contents($dir . 'm_' . $m3u8name, $m3u8);	
			$urls .= "\n" . $this->mChannelinfo['channel']['config']['out_host'] . $this->mChannelinfo['channel']['code'] . '/' . $stream . '/m_' . $m3u8name;
		}		
		if ($this->settings['open_push_cdn'])
		{
			include_once(ROOT_PATH . 'lib/class/cdn.class.php');
			$cdn = new cdn();
			$cdn->push($urls,'');
		}
		if ($this->settings['m3u8_host'] == 'http://' . $this->input['host'])
		{
			echo $m3u8;
		}
		else
		{
			echo $sm3u8;
		}
	}

	private function get_shield($dates)
	{
		if ($this->settings['bantype'] == 'player')
		{
			return array();//采用播放器屏蔽
		}
		$program_shield_dir = $this->settings['program_shield_dir'] ? $this->settings['program_shield_dir'] : 'program_shield';
		$dir  = $dates;
		$cache_file 	  = CACHE_DIR . $program_shield_dir . '/' . $dir . '/' . $this->mChannelinfo['channel']['code'] . '.php';
		if (!@include($cache_file))
		{
			include_once (CUR_CONF_PATH . 'lib/program_shield.class.php');
			$mProgramShield = new programShield();
			$mProgramShield->cache_program_shield($this->mChannelinfo['channel']['id'], $dates, $this->mChannelinfo['channel']['code']);
			include($cache_file);
		}
		if (!$program_shield_zone)
		{
			$program_shield_zone = array();
		}
		return $program_shield_zone;
	}


	/**
	* 合并串联单，播控ts流
	*
	*/
	private function get_dvr_ts($ts)
	{
		if (count($ts) == 1)
		{
			$ts = array_pop($ts);
			return $ts;
		}
		$dvrts = $ts[0];
		if ($ts[1])
		{
			$dvrts = hg_merge_ts($dvrts, $ts[1]);
		}
		
		if ($ts[2])
		{
			$dvrts = hg_merge_ts($dvrts, $ts[2]);
		}
		if ($ts[3])
		{
			$dvrts = hg_merge_ts($dvrts, $ts[3]);
		}
		return $dvrts;
	}

	private function build_m3u8($ts, $end = '', $preurl = '', $shield_time_zone = array())
	{
		if (!$ts)
		{
			return '';
		}
		$m3u8_str = '';
		$cur_last_ts = array();
		foreach ($ts AS $r)
		{
			if ($shield_time_zone)
			{
				$go = false;
				$stime = intval($r['start_time'] / 1000);
				foreach ($shield_time_zone AS $zone)
				{
					if ($stime >= $zone['start_time'] && $stime <= $zone['end_time'])
					{
						$go = true;
						break;
					}
				}
				if ($go)
				{
					continue;
				}
			}
			$source = $r['source'];
			if ($r['file_start'] || ($source == 0 && $cur_last_ts && $source != $cur_last_ts['source']))
			{
				$m3u8_str .= '#EXT-X-DISCONTINUITY
';
			}
			if (substr($r['path'], 0, 4) != 'http')
			{
				if ($preurl && substr($r['path'],0,1) == '/')
				{
					
					$sign = hg_sign_uri($r['path'], $this->settings['live_expire'], $this->settings['sign_type']);
					$r['path'] = $preurl . $r['path'] . $sign[0];
				}
			}
			$m3u8_str .= '#EXTINF:' . round($r['duration'] / 1000, 4) . ',
' . $r['path'] .  '
';
			$cur_last_ts = $r;
		}
		$m3u8_str = '#EXTM3U
#EXT-X-VERSION:3
#EXT-X-ALLOW-CACHE:NO
#EXT-X-TARGETDURATION:20
#EXT-X-MEDIA-SEQUENCE:' . $this->mSequence . '
' . $end . '
' . $m3u8_str;
		return $m3u8_str;
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
$out = new live();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>