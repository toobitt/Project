<?php
/***************************************************************************
* $Id: schedule_auto.php 21634 2013-05-07 02:43:19Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','fetch_live_stream');
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR.'global.php');
class fetch_live_stream extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '获取串联单直播和时移流',	 
			'brief' => '',
			'space' => '2',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}

	public function show()
	{
		$this->fetch_live();
		$this->fetch_file();
	}
	public function test()
	{
		$this->fetch_file();
	}

	private function fetch_file()
	{		
		$timenow = time() + 600;
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'schedule WHERE start_time <= ' . $timenow . ' AND (start_time+toff) > ' . $timenow . ' AND type IN (2,3) AND is_run=0';
		$q = $this->db->query($sql);
		
		$curl = new curl($this->settings['App_live']['host'], $this->settings['App_live']['dir'] . 'admin/');
		$curl->setReturnFormat('json');
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','create');
		$curl->addRequestData('level',2);
		while($r = $this->db->fetch_array($q))
		{
			$curl->addRequestData('channel_id',$r['channel_id']);
			if ($r['type'] == 2)
			{
				$type = $r['type'];
				$start_time = $r['start_time'] * 1000;
				$f_start_time = $start_time;
				$toff = $r['toff'];
				$m3u8_url = $r['url'];
				$tmp = explode('/', $m3u8_url);
				$root_url = $tmp[0] . '//' . $tmp[2] . '/';
				unset($tmp[count($tmp) - 1]);
				$cur_url = implode('/', $tmp) . '/';
				$m3u8 = file_get_contents($m3u8_url);
				$m3u8_list = $this->parse_m3u8($m3u8);
				$lefttime = $r['toff'] * 1000;
				$lenth = count($m3u8_list) - 1;
				foreach($m3u8_list AS $k => $v)
				{	
					if (($lefttime - $v['dur']) < 500)
					{
						break;
					}
					if (!$k)
					{
						$file_start = 1;
					}
					else
					{
						$file_start = 0;
					}
					if ($k == $lenth)
					{
						$file_end = 1;
					}
					else
					{
						$file_end = 0;
					}
					$duration[] = $v['dur'];
					
					if (substr($v['ts'], 0, 4) != 'http')
					{
						$c = substr($v['ts'], 0, 1);
						if ($c == '/')
						{
							$ts = $root_url . $v['ts'];
						}
						else
						{
							$ts = $cur_url . $v['ts'];
						}
					}
					else
					{
						$ts = $v['ts'];
					}
					$postdata[] = $start_time . '#' . $v['dur'] . '#' .$ts . '#0#' . $r['change2_id'] . '#' . $f_start_time . '#' . $lefttime . '#' . $file_start . '#' . $file_end;
					//$curl->addRequestData('data[' . $i . ']', $start_time . '#' . $v['dur'] . '#' .$ts . '#0#' . $r['change2_id'] . '#' . $f_start_time . '#' . $lefttime . '#' . $file_start . '#' . $file_end);
					$lefttime = $lefttime - $v['dur'];
					$start_time = $start_time + $v['dur'];
				}
				if ($postdata)
				{
					$curl->addRequestData('data', implode(']ts[', $postdata));
					$ret = $curl->request('dvr_update.php');
				}
			}
			elseif ($r['type'] == 3)
			{
				$f_start_time = intval($r['start_time']) * 1000;
				$start_time_shift = intval($r['start_time_shift']) * 1000;
				$toff = $r['toff'] * 1000;
				$f_end_time = $f_start_time + $toff;
				$r['url'] = str_replace('playlist.m3u8', $start_time_shift . ',' . $toff . '.m3u8', $r['url']);
				if ($r['url'])
				{
					$type = $r['type'];
					$toff = $r['toff'];
					if (!$start_time)
					{
						$start_time = $f_start_time;
					}
					$lefttime = $r['toff'] * 1000;
					$m3u8list = $this->parse_top_m3u8($r['url']);
					if ($m3u8list)
					{
						$content = file_get_contents($m3u8list);
						$m3u8_list = $this->parse_m3u8($content);
						$lenth = count($m3u8_list) - 1;
						$postdata = array();
						foreach($m3u8_list AS $k => $v)
						{	
							if (($lefttime - $v['dur']) < 500)
							{
								break;
							}
							if (!$k)
							{
								$file_start = 1;
							}
							else
							{
								$file_start = 0;
							}
							if ($k == $lenth)
							{
								$file_end = 1;
							}
							else
							{
								$file_end = 0;
							}
							$duration[] = $v['dur'];
							if (substr($v['ts'], 0, 4) != 'http')
							{
								$c = substr($v['ts'], 0, 1);
								if ($c == '/')
								{
									$ts = $root_url . $v['ts'];
								}
								else
								{
									$ts = $cur_url . $v['ts'];
								}
							}
							else
							{
								$ts = $v['ts'];
							}
							$postdata[] = $start_time . '#' . $v['dur'] . '#' .$ts . '#0#' . $r['change2_id'] . '#' . $f_start_time . '#' . $lefttime . '#' . $file_start . '#' . $file_end;
							$lefttime = $lefttime - $v['dur'];
							$start_time = $start_time + $v['dur'];
						}
						
						if ($postdata)
						{
							$curl->addRequestData('data', implode(']ts[', $postdata));
							$ret = $curl->request('dvr_update.php');
						}
						print_r($ret);
					}
				}
			}
			$sql = 'UPDATE ' . DB_PREFIX . 'schedule SET is_run=2 WHERE id=' . $r['id'];
			$this->db->query($sql);
		}
	}
	
	private function fetch_dvr()
	{		
		$timenow = time();
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'schedule WHERE (start_time_shift+toff) <= ' . $timenow . ' AND type=3 AND is_run=0';
		$q = $this->db->query($sql);
		
		$curl = new curl($this->settings['App_live']['host'], $this->settings['App_live']['dir'] . 'admin/');
		$curl->setReturnFormat('json');
		$curl->setSubmitType('post');
		while($r = $this->db->fetch_array($q))
		{
			$f_start_time = intval($r['start_time']) * 1000;
			$start_time_shift = intval($r['start_time_shift']) * 1000;
			$toff = $r['toff'] * 1000;
			$f_end_time = $f_start_time + $toff;
			$r['url'] = str_replace('playlist.m3u8', $start_time_shift . ',' . $toff . '.m3u8', $r['url']);
			if ($r['url'])
			{
				$curl->initPostData();
				$curl->addRequestData('a','create');
				$curl->addRequestData('channel_id',$r['channel_id']);
				$curl->addRequestData('level',2);
				$type = $r['type'];
				$toff = $r['toff'];
				if (!$start_time)
				{
					$start_time = $f_start_time;
				}
				$lefttime = $r['toff'] * 1000;
				$m3u8list = $this->parse_top_m3u8($r['url']);
				if ($m3u8list)
				{
					$content = file_get_contents($m3u8list);
					$m3u8_list = $this->parse_m3u8($content);
					$lenth = count($m3u8_list) - 1;
					$postdata = array();
					foreach($m3u8_list AS $k => $v)
					{	
						if (($lefttime - $v['dur']) < 500)
						{
							break;
						}
						if (!$k)
						{
							$file_start = 1;
						}
						else
						{
							$file_start = 0;
						}
						if ($k == $lenth)
						{
							$file_end = 1;
						}
						else
						{
							$file_end = 0;
						}
						$duration[] = $v['dur'];
						if (substr($v['ts'], 0, 4) != 'http')
						{
							$c = substr($v['ts'], 0, 1);
							if ($c == '/')
							{
								$ts = $root_url . $v['ts'];
							}
							else
							{
								$ts = $cur_url . $v['ts'];
							}
						}
						else
						{
							$ts = $v['ts'];
						}
						$postdata[] = $start_time . '#' . $v['dur'] . '#' .$ts . '#0#' . $r['change2_id'] . '#' . $f_start_time . '#' . $lefttime . '#' . $file_start . '#' . $file_end;
						$lefttime = $lefttime - $v['dur'];
						$start_time = $start_time + $v['dur'];
					}
					
					if ($postdata)
					{
						$curl->addRequestData('data', implode(']ts[', $postdata));
						$ret = $curl->request('dvr_update.php');
					}
					print_r($ret);
					$sql = 'UPDATE ' . DB_PREFIX . 'schedule SET is_run=2 WHERE id=' . $r['id'];
					$this->db->query($sql);
				}
			}
		}
	}

	private function fetch_live()
	{
		$timenow = time();
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'schedule WHERE start_time < ' . $timenow . ' AND (start_time+toff + 20) >= ' . $timenow . ' AND type=1';
		//$sql = 'SELECT * FROM ' . DB_PREFIX . 'schedule WHERE (start_time+toff) >= ' . $timenow . ' AND type=3';
		$q = $this->db->query($sql);
		
		$curl = new curl($this->settings['App_live']['host'], $this->settings['App_live']['dir'] . 'admin/');
		$curl->setReturnFormat('json');
		$curl->setSubmitType('post');
		while($r = $this->db->fetch_array($q))
		{
			$tmpfilename = CACHE_DIR . 'live_stream_' . $r['id'] . '_' . $r['start_time'] . '.php';
			if (is_file($tmpfilename))
			{
				$first_switch_live = false;
				$content = @file_get_contents($tmpfilename);
				$content = substr($content, 14);
				$live_stream_info = json_decode($content, 1);
			}
			else
			{
				$first_switch_live = true;
				$live_stream_info = array();
			}
			$f_start_time = intval($r['start_time']) * 1000;
			$f_end_time = $f_start_time + $r['toff'] * 1000;
			$last_start_time = intval($live_stream_info['last_start_time']);
			if ($r['url'])
			{
				$curl->initPostData();
				$curl->addRequestData('a','create');
				$curl->addRequestData('channel_id',$r['channel_id']);
				$curl->addRequestData('ischannel',1);
				$curl->addRequestData('level',2);
				$type = $r['type'];
				$toff = $r['toff'];
				$start_time = intval($live_stream_info['cur_start_time']);
				$lefttime = intval($live_stream_info['lefttime']);
				if (!$start_time)
				{
					$start_time = $f_start_time;
				}
				if (!$lefttime)
				{
					$lefttime = $r['toff'] * 1000;
				}
				$m3u8list = $this->parse_top_m3u8($r['url']);
				if ($m3u8list)
				{
					$content = file_get_contents($m3u8list);
					$m3u8_list = $this->parse_m3u8($content);
					$lenth = count($m3u8_list) - 1;
					if ($first_switch_live)
					{
						$m3u8_list = array($m3u8_list[$lenth]);
					}
					$postdata = array();
					foreach($m3u8_list AS $k => $v)
					{	
						$file_start = 0;
						if (($lefttime - $v['dur']) < 500)
						{
							break;
						}
						if ($last_start_time && $v['start_time'] <= $last_start_time)
						{
							continue;
						}
						if(!$last_start_time)
						{
							$file_start = 1;
							$last_start_time = 1;
						}
						echo 'start_time:' . date('Y-m-d H:i:s', $v['start_time'] / 1000) . 'file_start:' . $file_start . '<br />';
						echo 'f_start_time:' . date('Y-m-d H:i:s', $f_start_time / 1000) . '<br />';
						echo 'f_end_time:' . date('Y-m-d H:i:s', $f_end_time / 1000) . '<br />';
						echo 'last_start_time:' . date('Y-m-d H:i:s', $last_start_time / 1000) . '<br />';
						$file_end = 0;
						$duration[] = $v['dur'];
						if (substr($v['ts'], 0, 4) != 'http')
						{
							$c = substr($v['ts'], 0, 1);
							if ($c == '/')
							{
								$ts = $root_url . $v['ts'];
							}
							else
							{
								$ts = $cur_url . $v['ts'];
							}
						}
						else
						{
							$ts = $v['ts'];
						}
						$postdata[] = $start_time . '#' . $v['dur'] . '#' .$ts . '#0#' . $r['change2_id'] . '#' . $f_start_time . '#' . $lefttime . '#' . $file_start . '#' . $file_end;
						$lefttime = $lefttime - $v['dur'];
						$start_time = $start_time + $v['dur'];
						$last_ts_start_time = $v['start_time'];
					}
				}
				if ($postdata)
				{
					$live_stream_info = array(
						'cur_start_time' => $start_time,
						'last_start_time' => $last_ts_start_time,
						'lefttime' => $lefttime,
					);
					file_put_contents($tmpfilename, '<?php exit; ?>' . json_encode($live_stream_info));
					$curl->addRequestData('data', implode(']ts[', $postdata));
					$ret = $curl->request('dvr_update.php');
				}
				print_r($ret);
			}
		}
	}
	private function parse_top_m3u8($m3u8)
	{
		$tmp = explode('/', $m3u8);
		$root_url = $tmp[0] . '//' . $tmp[2];
		unset($tmp[count($tmp) - 1]);
		$cur_url = implode('/', $tmp) . '/';
		$content = file_get_contents($m3u8);
		preg_match_all('/\#EXT\-X\-STREAM\-INF\:PROGRAM\-ID\=\d+,BANDWIDTH=\d+[\r\n]*(.*?\.m3u8)/is', $content, $match);
		if ($match)
		{
			$m3u8addr = $match[1][0];
			$c = substr($m3u8addr, 0, 1);
			if ($c == '/')
			{
				$m3u8addr = $root_url . $m3u8addr;
			}
			else
			{
				$m3u8addr = $cur_url . $m3u8addr;
			}
			return $m3u8addr;
		}
		return $m3u8;

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
}

$out = new fetch_live_stream();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>