<?php
/***************************************************************************
* $Id: dvr_checked_log.class.php 19895 2013-04-08 02:42:01Z lijiaying $
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
		$orderby = $orderby ? $orderby : " ORDER BY id DESC ";
		$limit 	 = " LIMIT " . $offset . "," . $count;
		
		$sql  = "SELECT * FROM " . DB_PREFIX . "dvr_checked_log ";
		$sql .= " WHERE 1 " . $condition . $orderby . $limit;
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			
			$return[] = $row;
		}
		return $return;
	}

	public function count($condition)
	{
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "dvr_checked_log WHERE 1 " . $condition;
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
}
?>