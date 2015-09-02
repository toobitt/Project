<?php
/***************************************************************************

* (C)2004-2015 HOGE Software.
*
* $Id: dvr_checked_auto.php 19895 2013-04-08 02:42:01Z lijiaying $
***************************************************************************/
class m3u8 extends InitFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function build_live_m3u8($channel_stream, $ts, $preurl = '', $end = '')
	{
		$tmpfilename = CACHE_DIR . 'ts_sequence_' . $channel_stream . '.php';
		$content = @file_get_contents($tmpfilename);
		$content = substr($content, 14);
		$gLastTsInfo = json_decode($content, 1);
		if (!$gLastTsInfo)
		{
			$gLastTsInfo = array();
		}
		$ts_num_count = intval($gLastTsInfo['tsount']);
		$last_last_time = intval($gLastTsInfo['start_time']);
		$m3u8_str = '';
		$sequences = array();
		$last_ts = array();
		$i = 0;
		$live_ts = array();
		foreach ($ts AS $r)
		{
			if ($r['source'] && $last_ts && !$last_ts['source'] && ($last_ts['start_time'] + $last_ts['duration']) < ($r['start_time'] - 2000))
			{
				//等待ts补齐时间
				break;
			}
			if ($r['start_time'] > $last_last_time)
			{
				$ts_num_count++;
				$r['sequence'] = $ts_num_count;
				if (!$last_ts)
				{
					$tmp = $gLastTsInfo['sequence'];
					$last_ts = array_pop($tmp);
				}
				unset($last_ts['last_ts']);
				$r['build_time'] = time();
				$sequences[$r['start_time']] = array(
					'sequence' => $r['sequence'],
					'last_ts' => $last_ts,
					'build_time' => $r['build_time'],
					'source' => $r['source'],
					'start_time' => $r['start_time'],
					'duration' => $r['duration'],
				);
				$p = 'n_';
			}
			else
			{
				if($gLastTsInfo['sequence'][$r['start_time']])
				{
					$p = 'l_';
					$r['sequence'] = $gLastTsInfo['sequence'][$r['start_time']]['sequence'];
					$r['build_time'] = $gLastTsInfo['sequence'][$r['start_time']]['build_time'];
					$sequences[$r['start_time']] = $gLastTsInfo['sequence'][$r['start_time']];
				}
				else
				{
					continue;
					/*
					$p = 'u_';
					$ts_num_count++;
					$r['sequence'] = $ts_num_count;
					$r['last_ts'] = $last_ts;
					$sequences[$r['start_time']] = $r;
					*/
				}
			}
			if (!$i)
			{
				$cur_sequence = $sequences[$r['start_time']]['sequence'];
			}
			$cur_last_ts = $sequences[$r['start_time']]['last_ts'];
			//$r['path'] .= '?' . ($cur_last_ts['sequence'] + 1) . '&start_time=' . date('H:i:s', $r['start_time'] / 1000) . '&build_time='. date('H:i:s', $r['build_time']);
			$source = $r['source'];

			if ($r['file_start'] || ($cur_last_ts && $source != $cur_last_ts['source']))
			{
				$switch = '#EXT-X-DISCONTINUITY
';
				$m3u8_str .= $switch;
			}
			else
			{
				$switch = '';
			}
			if (substr($r['path'], 0, 4) != 'http')
			{
				if ($preurl && substr($r['path'],0,1) == '/')
				{
					$sign = hg_sign_uri($r['path'], $this->settings['live_expire'], $this->settings['sign_type'], '?', $r['build_time']);
					$r['path'] = $preurl . $r['path'] . $sign[0];
				}
			}
			$dur = round($r['duration'] / 1000, 4);
			$m3u8_str .= '#EXTINF:' . $dur . ',
' . $pre . $r['path'] .  '
';
			$live_ts[] = array(
				'dur' => 	$dur,
				'ts' => $r['path'],
				'switch' => $switch,
				'sequence' => $sequences[$r['start_time']]['sequence'],
			);
			$cur_last_start_time = $r['start_time'];
			$last_ts = array(
				'sequence' => $r['sequence'],
				'source' => $r['source'],
				'start_time' => $r['start_time'],
				'duration' => $r['duration'],
				'build_time' => $r['build_time'],
			);
			$i++;
		}
		$m3u8_str = '#EXTM3U
#EXT-X-VERSION:3
#EXT-X-ALLOW-CACHE:NO
#EXT-X-TARGETDURATION:20
#EXT-X-MEDIA-SEQUENCE:' . $cur_sequence . '
' . $m3u8_str . $end;
		$last_info = array(
			'tsount' => $ts_num_count,
			'start_time' => $cur_last_start_time,
			'sequence' => $sequences,
		);
		file_put_contents(CACHE_DIR . 'ts_sequence_' . $channel_stream . '.php', '<?php exit; ?>' . json_encode($last_info));
		if ($this->settings['ts_num'])
		{
			$m3u8_str = $this->build_m3u8($live_ts, $num = $this->settings['ts_num']);
		}
		return $m3u8_str;
	}

	public function purge_upyun($url)
	{
		if (!$this->settings['upyun'])
		{
			return;
		}

	}

	public function get_ts($channel_stream, $table_name ,$start_time = 0)
	{
		$live_time_shift = intval($this->settings['live_time_shift']);
		$live_time_shift = $live_time_shift ? $live_time_shift : 1;
		$live_time_shift = $live_time_shift > 180 ? 180 : $live_time_shift;
		$live_time_shift = $live_time_shift * 60 * 1000;
		$timenow = time() * 1000;
		if (!$start_time)
		{
			$start_time = $timenow - $live_time_shift;
		}
		$timenow = $timenow;
		$sql = 'SELECT * FROM ' . DB_PREFIX . $table_name. " WHERE stream_name='$channel_stream' AND start_time >= $start_time AND start_time <= $timenow ORDER BY start_time ASC";
		$q = $this->db->query($sql);
		$ts = array();
		$lasttime = 0;
		while($r = $this->db->fetch_array($q))
		{
			$ts[$r['level']][] = $r;
			$lasttime = $r['start_time'];
		}
		$cha = $timenow - $lasttime;
		if (($timenow - $lasttime) > 30000) //若最新的ts文件时间是30秒前的，启用备份表
		{
			if(substr($table_name,-2) == '_1')
			{
				return false;
			}
			//重写频道缓存文件
			if ($channel_stream)
			{
				$tmp = explode('_', $channel_stream);
				$channel_code = $tmp[0];
				$sql = "SELECT sc.ts_host FROM " . DB_PREFIX . "channel c 
							LEFT JOIN " . DB_PREFIX . "server_config sc ON c.server_id=sc.fid  
							WHERE c.code = '{$channel_code}'";
				$re = $this->db->query_first($sql);
				if(!$re['ts_host'])
				{
					return false;
				}
				@include(CACHE_DIR . 'channel/' . $channel_code . '.php');
				$table_name == 'dvr' ? $table_name_1 = 'dvr1' : $table_name_1 = $table_name.'_1';
				$channel_info['channel']['table_name'] = $table_name_1;
				$channel_info['channel']['config']['ts_host'] = $re['ts_host'];
				file_put_contents(CACHE_DIR . 'channel/' . $channel_code . '.php', '<?php $channel_info = ' . var_export($channel_info, 1) . ';?>');
			}
			//启用备份表
			$sql = 'SELECT * FROM ' . DB_PREFIX . $table_name_1. " WHERE stream_name='$channel_stream' AND start_time >= $start_time AND start_time <= $timenow ORDER BY start_time ASC";
			$q = $this->db->query($sql);

			$ts = array();
			while($r = $this->db->fetch_array($q))
			{
				$ts[$r['level']][] = $r;
			}
		}
		$ret_ts = $this->get_live_ts($ts);
		return $ret_ts;
	}
	
	private function build_m3u8($ts, $num = 3)
	{
		if (!$ts)
		{
			return '';
		}
		$m3u8_str = '';
		$lent = count($ts) - 1;
		for ($i = $lent - $num; $i <= $lent ; $i++)
		{
			$r = $ts[$i];
			if (!$r)
			{
				continue;
			}
			if ($i == $lent)
			{
				$seq = $r['sequence'];
			}
			$m3u8_str .= $r['switch'] . '#EXTINF:' . $r['dur'] . ',
' . $r['ts'] .  '
';
		}
		$m3u8_str = '#EXTM3U
#EXT-X-VERSION:3
#EXT-X-ALLOW-CACHE:NO
#EXT-X-TARGETDURATION:20
#EXT-X-MEDIA-SEQUENCE:' . $seq . '
' . $m3u8_str;
		return $m3u8_str;
	}

	/**
	* 合并串联单，播控ts流
	*
	*/
	private function get_live_ts($ts)
	{
		if (count($ts) == 1)
		{
			$ts = array_pop($ts);
			return $ts;
		}
		$livets = $ts[0];
		if ($ts[1])
		{
			$livets = hg_merge_ts($livets, $ts[1]);
		}
		
		if ($ts[2])
		{
			$livets = hg_merge_ts($livets, $ts[2]);
		}
		if ($ts[3])
		{
			$livets = hg_merge_ts($livets, $ts[3]);
		}
		return $livets;
	}
}
?>