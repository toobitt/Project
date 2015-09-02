<?php
/***************************************************************************
* $Id: sys_update.php 22758 2013-05-24 07:36:11Z lijiaying $
***************************************************************************/
//define('DEBUG_MODE','schedule');
define('MOD_UNIQUEID','dvr');
require('global.php');
class dvr extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function create()
	{
		$data = $this->input['data'];
		$data = explode(']ts[', $data);
		$level = $this->input['level'];
		$channel_stream = $this->input['stream_name'];
		$dates = $this->input['dates'];
		$ischannel = intval($this->input['ischannel']);
		if (!$channel_stream)
		{
			$channel_id = $this->input['channel_id'];
			$sql = 'SELECT code, main_stream_name FROM ' . DB_PREFIX . "channel WHERE id='$channel_id'";
			$channel_info = $this->db->query_first($sql);
			if ($channel_info)
			{
				$channel_stream = $channel_info['code'] . '_' . $channel_info['main_stream_name'];
			}
		}
		if (!$channel_stream)
		{
			$this->errorOutput('NO_STREAM_NAME');
		}
		if (!$dates)
		{
			$dates = date('Y-m-d');
		}
		$today_time = strtotime($dates . ' 00:00:00') * 1000;
		$today_end_time = strtotime($dates . ' 23:59:59') * 1000;
		if ($today_time == strtotime(date('Y-m-d') . ' 00:00:00') * 1000)
		{
			//$today_time = time() * 1000;
		}
		if (is_array($data) && $data)
		{
			$insert_type = 'REPLACE';
			$sqlhead = $insert_type . ' INTO ' . DB_PREFIX . 'dvr (stream_name, start_time, path, duration, level, type, source, file_start_time, file_left_time, file_start, file_end, ischannel) VALUES ';
			$values = array();
			foreach ($data AS $k => $v)
			{
				$tsinfo = explode('#', $v);
				$values[] = "('{$channel_stream}', '{$tsinfo[0]}', '{$tsinfo[2]}', '{$tsinfo[1]}', '{$level}', '{$tsinfo[3]}', '{$tsinfo[4]}', '{$tsinfo[5]}', '{$tsinfo[6]}', '{$tsinfo[7]}', '{$tsinfo[8]}', {$ischannel})";
			}
			$group_val = array_chunk($values, 100);
			foreach ($group_val AS $values)
			{
				$sql = $sqlhead . implode(',', $values);
				$this->db->query($sql);
			}
		}
		$this->addItem($sql);
		$this->output();
	}
	public function update_left_time()
	{
		$channel_stream = $this->input['stream_name'];
		$endtime = $this->input['endtime'];
		$source = $this->input['source'];
		if (!$channel_stream)
		{
			$channel_id = $this->input['channel_id'];
			$sql = 'SELECT code, main_stream_name FROM ' . DB_PREFIX . "channel WHERE id='$channel_id'";
			$channel_info = $this->db->query_first($sql);
			if ($channel_info)
			{
				$channel_stream = $channel_info['code'] . '_' . $channel_info['main_stream_name'];
			}
		}
		if (!$channel_stream)
		{
			$this->errorOutput('NO_STREAM_NAME');
		}
		$dates = date('Y-m-d');
		if (!$endtime)
		{
			$endtime = time() * 1000;
		}
		$level = 3;
		$sql = 'UPDATE ' . DB_PREFIX . "dvr SET file_left_time=($endtime - start_time) WHERE level='$level' AND stream_name='$channel_stream' AND source=$source";
		$this->db->query($sql);
		$this->addItem($sql);
		$this->output();

	}

	public function test()
	{
		$ret_ts = $this->build_ts_group('test2_rd');
		print_r($ret_ts);
	}
	public function rebuild_m3u8()
	{
		$ret_ts = $this->build_ts_group('test2_rd');
		print_r($ret_ts);
	}

	private function build_ts_group($channel_stream)
	{
		$ret_ts = array();
		$last_ts_start_time = intval(@file_get_contents(CACHE_DIR . 'last_ts_' . $channel_stream));
		$sql = 'SELECT start_time FROM ' . DB_PREFIX . "dvr WHERE stream_name='$channel_stream' AND start_time > $last_ts_start_time AND level=0 ORDER BY start_time ASC LIMIT 1";
		$next_ts = $this->db->query_first($sql);
		if (!$next_ts['start_time'])
		{
			return $ret_ts;
		}
		file_put_contents(CACHE_DIR . 'last_ts_' . $channel_stream, $next_ts['start_time']);
		$sql = 'SELECT * FROM ' . DB_PREFIX . "dvr WHERE stream_name='$channel_stream' AND start_time <= $last_ts_start_time AND level=0 ORDER BY start_time DESC LIMIT 3 ";
		$q = $this->db->query($sql);
		$num_rows = $this->db->num_rows($q);
		if ($num_rows < 3)
		{
			return;
		}
		$ts = array();
		while($r = $this->db->fetch_array($q))
		{
			$ts[] = $r;
		}
		$start_time = $ts[2]['start_time'];
		$end_time = $ts[0]['start_time'];
		
		$sql = 'SELECT * FROM ' . DB_PREFIX . "dvr WHERE stream_name='$channel_stream' AND start_time >= $start_time AND start_time <= $end_time AND level>0 ORDER BY start_time ASC";
		$q = $this->db->query($sql);
		$ts1 = array();
		while($r = $this->db->fetch_array($q))
		{
			$ts1[$r['level']][] = $r;
		}
		if ($ts1)
		{
			foreach ($ts AS $r)
			{
				$section = $r;
				if ($ts1[1])
				{
					$start_time = $r['start_time'];
					$end_time = $r['start_time'] + $r['duration'];
					foreach($ts1[1] AS $k => $v)
					{
						if ($v['start_time'] <= $end_time)
						{
							$section = $v;
							unset($ts1[1][$k]);
							break;
						}
					}
				}
				if ($ts1[2])
				{
					$start_time = $r['start_time'];
					$end_time = $r['start_time'] + $r['duration'];
					foreach($ts1[2] AS $k => $v)
					{
						if ($v['start_time'] <= $end_time)
						{
							$section = $v;
							unset($ts1[2][$k]);
							break;
						}
					}
				}
				$ret_ts[] = $section;
			}
		}
		else
		{
			$ret_ts = $ts;
		}
		return $ret_ts;
	}
	
	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
	
}
$out = new dvr();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>