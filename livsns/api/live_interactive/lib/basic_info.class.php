<?php
/***************************************************************************
* $Id: basic_info.class.php 16445 2013-01-07 05:11:27Z lijiaying $
***************************************************************************/
class basicInfo extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_program_by_time($channel_id, $dates, $start_time='', $end_time='', $condition = '', $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "program ";
		$sql.= " WHERE channel_id=" . $channel_id . " AND dates = '" . $dates . "' " . $condition;
		if ($start_time && $end_time)
		{
			$sql.= " AND start_time >= " . $start_time . " AND start_time < " . $end_time;
		}
		$sql.= " ORDER BY start_time ASC ";

		$q = $this->db->query($sql);
		
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['is_now'] = 0;
			if ($row['start_time'] < TIMENOW && ($row['start_time'] + $row['toff']) > TIMENOW)
			{
				$row['is_now'] = 1;
			}
			
			$row['start']		= date('H:i' , $row['start_time']);
			$row['end']		  	= date('H:i' , ($row['start_time'] + $row['toff']));
			$row['member_id']	= $row['member_id'] ? unserialize($row['member_id']) : array();
			$info[]   = $row;
		}
		return $info;
	}
	
	public function show($program_id, $table, $orderby = '', $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . $table; 
		$sql.= " WHERE program_id = " . $program_id . $orderby;
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		return $return;
	}
	
	public function create($data, $table)
	{
		$sql = "INSERT INTO " . DB_PREFIX . $table . " SET ";
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
	
	public function update($data, $table, $id)
	{
		$sql = "UPDATE " . DB_PREFIX . $table . " SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = " . $id;
		
		$this->db->query($sql);
		
		$data['id'] = $id;
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function delete($id, $table)
	{
		$sql = "DELETE FROM " . DB_PREFIX . $table . " WHERE id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	/**
	 * 导播、主持人添加
	 * Enter description here ...
	 * @param unknown_type $data
	 * @param unknown_type $table
	 */
	public function dir_pre_add($data, $table)
	{
		$sql = "INSERT INTO " . DB_PREFIX . $table . " SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		if ($this->db->query($sql))
		{
			return $data;
		}
		return false;
	}
	
	public function delete_by_program_id($program_id, $table)
	{
		$sql = "DELETE FROM " . DB_PREFIX . $table . " WHERE program_id IN (" . $program_id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
}
?>