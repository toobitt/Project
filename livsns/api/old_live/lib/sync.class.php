<?php
/***************************************************************************
* $Id: sync.class.php 19886 2013-04-08 02:01:25Z lijiaying $
***************************************************************************/
class sync extends InitFrm
{
	private $mServerConfig;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_channel_info($offset, $count, $condition = '')
	{
		$limit = " LIMIT " . $offset . " , " . $count;
		
		$sql = "SELECT t1.id, t1.code, t1.name, t1.ch_id, t1.save_time, t1.live_delay, t1.stream_state, t1.drm, t1.stream_id, t1.stream_info_all, t1.stream_mark, t1.main_stream_name, t1.create_time, t1.open_ts, t1.record_time, t1.audio_only, t1.server_id,
						t2.s_status, t2.type, t2.other_info
				FROM " . DB_PREFIX . "channel t1 ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "stream t2 ON t2.id=t1.stream_id ";
		$sql.= " WHERE 1 " . $condition . " ORDER BY t1.id ASC " . $limit;

		$q = $this->db->query($sql);
		
		$channel = $server_id = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			$row['stream_info_all'] = @unserialize($row['stream_info_all']);
			$row['other_info'] 		= @unserialize($row['other_info']);
			
			$channel[$row['id']] 	= $row;
			
			$server_id[] = $row['server_id'];
		}
		
		if (empty($channel))
		{
			return -1;
		}
		
		//服务器配置
		if (!empty($server_id))
		{
			$server_id	   	= implode(',', @array_unique($server_id));
			$server_infos   = $this->mServerConfig->get_server_config($server_id);
		}
		
		$channel_id = @array_keys($channel);
		
		if ($channel_id)
		{
			$channel_id = implode(',', $channel_id);
			$channel_stream = $this->get_channel_stream_info($channel_id);
		}
	
		//频道内容信息
		$channel_info = array();
		foreach ($channel AS $k => $v)
		{
			/*
			if ($channel_stream[$k])
			{
				$channel_info[$k] = @array_merge($channel[$k],$channel_stream[$k]);
			}
			else
			{
				$channel_info[$k] = $channel[$k];
			}
			*/
			$v['channel_stream'] = $channel_stream[$k];
			$v['server_info']  	 = $server_infos[$v['server_id']];
			$channel_info[$k] 	 = $v;
		}

		return $channel_info;
	}

	public function get_channel_stream_info($channel_id)
	{
		$sql = "SELECT id, channel_id, stream_id, stream_name, out_stream_name, delay_stream_id, chg_stream_id, out_stream_id FROM " . DB_PREFIX . "channel_stream ";
		$sql.= " WHERE channel_id IN (" . $channel_id .") ORDER BY id ASC";
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
		//	$return[$row['channel_id']]['channel_stream'][] = $row;
			$return[$row['channel_id']][] = $row;
		}
		return $return;
	}
	
	public function get_stream_info($offset, $count)
	{
		$data_limit = " LIMIT " . $offset . " , " . $count;
		
		$sql = "SELECT id, ch_name, s_status, type, wait_relay, audio_only, other_info FROM " . DB_PREFIX . "stream ";
		$sql.= " WHERE 1 ORDER BY id ASC " . $data_limit;

		$q = $this->db->query($sql);
		
		$stream = array();
		
		while ($row = $this->db->fetch_array($q))
		{
			$row['other_info'] 		= unserialize($row['other_info']);
			
			$stream[$row['id']] 	= $row;
		}
		
		return $stream;
	}
}
?>