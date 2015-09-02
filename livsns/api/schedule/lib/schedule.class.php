<?php
/***************************************************************************
* $Id: schedule.class.php 31959 2013-11-26 08:06:01Z tong $
***************************************************************************/
class schedule extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition, $orderby = '')
	{
		$orderby = $orderby ? $orderby : " ORDER BY start_time ASC ";
		
		$sql = "SELECT * FROM " . DB_PREFIX . "schedule ";
		$sql.= " WHERE 1 " . $condition . $orderby;
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
			
	//		$row['start_time_num']	= $row['start_time'] - strtotime($row['dates']);
	//		$row['end_time_num']	= $row['start_time_num'] + $row['toff'];
			
			$row['_start_time'] 	= $row['start_time'];
			$row['end_time'] 		= date('H:i:s', ($row['start_time'] + $row['toff']));
			$row['start_time'] 		= date('H:i:s', $row['start_time']);
			
			if ($row['start_time_shift'])
			{
				//$row['start_time_shift'] = date('Y-m-d H:i:s', $row['start_time_shift']);
			}
			$return[] = $row;
		}
		
		return $return;
	}
	
	public function detail($id)
	{
		if(!$id)
		{
			$condition = " ORDER BY id DESC LIMIT 1 ";
		}
		else
		{
			$condition = " WHERE id IN (" . $id .")";
		}
				
		$sql = "SELECT * FROM " . DB_PREFIX . "schedule " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
			
			return $row;
		}
		return false;
	}
	
	public function count($condition)
	{
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "schedule WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "schedule SET ";
		$space = '';
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
	
	public function update($data)
	{
		$sql = "UPDATE " . DB_PREFIX . "schedule SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = " . $data['id'];
		
		$this->db->query($sql);
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "schedule WHERE id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function get_schedule_info_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "schedule ";
		$sql.= " WHERE id IN (" . $id . ")";
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		return $return;
	}
	
	public function get_schedule_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "schedule ";
		$sql.= " WHERE id = " . $id;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function get_schedule_stream($channel_id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "schedule_stream ";
		$sql.= " WHERE 1 AND channel_id IN (" . $channel_id . ")";
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[$row['channel_id']] = $row;
		}
		return $return;
	}
	
	public function schedule_stream_create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "schedule_stream SET ";
		$space = '';
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
	
	public function schedule_stream_delete($id)
	{
		$sql  = "DELETE FROM " . DB_PREFIX . "schedule_stream ";
		$sql .= " WHERE id IN (" . $id . ")";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
}
?>