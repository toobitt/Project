<?php
/***************************************************************************
* $Id: dvr_checked_log.class.php 17154 2013-01-29 13:55:33Z lijiaying $
***************************************************************************/
class dvrCheckedLog extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition, $offset, $count, $orderby = '')
	{
		$orderby = $orderby ? $orderby : " ORDER BY t1.id DESC ";
		$limit 	 = " LIMIT " . $offset . "," . $count;
		
		$sql  = "SELECT t1.*, t2.ch_name, t3.name AS server_name FROM " . DB_PREFIX . "dvr_checked_log t1 ";
		$sql .= "LEFT JOIN " . DB_PREFIX . 	"stream t2 ON t2.id = t1.stream_id ";
		$sql .= "LEFT JOIN " . DB_PREFIX . 	"server_config t3 ON t3.id = t1.server_id ";
		$sql .= " WHERE 1 " . $condition . $orderby . $limit;
		
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			
			$return[] = $row;
		}
		return $return;
	}

	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "dvr_checked_log t1 WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "dvr_checked_log SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function delete($id)
	{
		$sql  = "DELETE FROM " .DB_PREFIX . "dvr_checked_log ";
		$sql .= " WHERE id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function get_channel_stream_info()
	{
		$sql  = "SELECT t1.id AS channel_id, t1.name, t1.code, t1.stream_state, t1.stream_id, t1.server_id, t1.ch_id, 
					t2.id, t2.stream_name, t2.delay_stream_id, t2.chg_stream_id, t2.out_stream_id
				 FROM " . DB_PREFIX . "channel t1 ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "channel_stream t2 ON t1.id = t2.channel_id ";
		$sql .= " WHERE t1.stream_state = 1 ";
		$q = $this->db->query($sql);
		
		$channel_info = $server_id = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel_info[] = $row;
		}
		
		return $channel_info;
	}
}
?>