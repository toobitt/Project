<?php
/***************************************************************************
* $Id: interactive_member.class.php 16100 2012-12-26 07:19:20Z lijiaying $
***************************************************************************/
class interactiveMember extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function show($condition, $offset = '', $count = '')
	{
		if ($count)
		{
			$limit 	 = " LIMIT " . $offset . " , " . $count;
		}
		
		$orderby = " ORDER BY id DESC ";
		
		$sql = "SELECT * FROM " . DB_PREFIX . "interactive_member ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			$row['week_day']	= $row['week_day'] ? @unserialize($row['week_day']) : array();
			$row['channel_id']	= $row['channel_id'] ? @unserialize($row['channel_id']) : array();	
			$row['_end_time']  	= date('H:i:s' , ($row['start_time'] + $row['toff']));
			$row['_start_time']	= date('H:i:s' , $row['start_time']);
			$row['plat_since_id']	= $row['plat_since_id'] ? @unserialize($row['plat_since_id']) : array();	
			
			$return[$row['id']] = $row;
		}
		
		return $return;
	}

	public function detail($id)
	{
		if(!$id)
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id IN (' . $id .')';
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "interactive_member " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			
			$row['end_time']  	= date('Y-m-d H:i:s' , ($row['start_time'] + $row['toff']));
			$row['start_time']	= date('Y-m-d H:i:s' , $row['start_time']);
			$row['week_day']	= $row['week_day'] ? unserialize($row['week_day']) : array();	
			$row['channel_id']	= $row['channel_id'] ? unserialize($row['channel_id']) : array();	
			return $row;
		}

		return false;
	}
	
	function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "interactive_member WHERE 1" . $condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	function create($info)
	{
		$data = array(
	//		'channel_id' 	=> $info['channel_id'],
			'member_id' 	=> $info['member_id'],
			'member_name'	=> $info['member_name'],
			'nick_name'		=> $info['nick_name'],
			'avatar'		=> $info['avatar'],
			'plat_id'		=> $info['plat_id'],
			'plat_name'		=> $info['plat_name'],
			'plat_type' 	=> $info['plat_type'],
			'plat_token' 	=> $info['plat_token'],
	//		'start_time' 	=> $info['start_time'],
	//		'toff'		 	=> $info['toff'],
	//		'week_day'	 	=> $info['week_day'],
			'appid' 	 	=> $info['appid'],
			'appname' 	 	=> $info['appname'],
			'user_id' 	 	=> $info['user_id'],
			'user_name'  	=> $info['user_name'],
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
			'ip' 			=> hg_getip(),
			'plat_can_access' 	=> $info['plat_can_access'],
			'plat_expired_time' => $info['plat_expired_time'],
		);

		$sql = "INSERT INTO " . DB_PREFIX . "interactive_member SET ";
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
	
	function update($info, $id)
	{
		$data = array(
			'channel_id' 	=> $info['channel_id'],
			'member_id' 	=> $info['member_id'],
			'member_name'	=> $info['member_name'],
			'nick_name'		=> $info['nick_name'],
			'avatar'		=> $info['avatar'],
			'plat_id'		=> $info['plat_id'],
			'plat_name'		=> $info['plat_name'],
			'plat_type' 	=> $info['plat_type'],
			'plat_token' 	=> $info['plat_token'],
			'start_time' 	=> $info['start_time'],
			'toff'		 	=> $info['toff'],
			'week_day'	 	=> $info['week_day'],
			'update_time' 	=> TIMENOW,
			'plat_can_access' 	=> $info['plat_can_access'],
			'plat_expired_time' => $info['plat_expired_time'],
		);

		$sql = "UPDATE " . DB_PREFIX . "interactive_member SET ";
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
	
	function member_edit($info, $id)
	{
		$data = array(
			'update_time' 	=> TIMENOW,
	//		'plat_token' 	=> $info['plat_token'],
			'plat_can_access' 	=> $info['plat_can_access'],
			'plat_expired_time' => $info['plat_expired_time'],
		);

		$sql = "UPDATE " . DB_PREFIX . "interactive_member SET ";
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
	
	function interactive_member_relation_edit($info, $week_num)
	{
		$data = array(
			'm_id'			=> $info['m_id'],
		//	'channel_id' 	=> $info['channel_id'],
			'member_id' 	=> $info['member_id'],
			'start_time'	=> date('H:i:s', $info['start_time']),
			'end_time'		=> date('H:i:s', $info['end_time']),
		);
		
		$sql = "DELETE FROM " . DB_PREFIX . "interactive_member_relation WHERE m_id = " . $info['m_id'];
		$this->db->query($sql);
		
		if (!$week_num)
		{
			$data['is_repeat'] = 0;
			$data['week_num']  = date('N', $info['start_time']);
			
			$sql = "INSERT INTO " . DB_PREFIX . "interactive_member_relation SET ";
			$space = "";
			foreach ($data AS $key => $value)
			{
				$sql .= $space . $key . "=" . "'" . $value . "'";
				$space = ",";
			}
	
			$this->db->query($sql);
		}
		else 
		{
			$data['is_repeat'] = 1;
			
			foreach ($week_num AS $k => $v)
			{
				$data['week_num'] = $v;
				
				$sql = "INSERT INTO " . DB_PREFIX . "interactive_member_relation SET ";
				$space = "";
				foreach ($data AS $key => $value)
				{
					$sql .= $space . $key . "=" . "'" . $value . "'";
					$space = ",";
				}
		
				$this->db->query($sql);
			}
		}
		return $data;
	}
	
	function delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "interactive_member WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		
		$sql = "DELETE FROM " . DB_PREFIX . "interactive_member_relation WHERE m_id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		
		return false;
	}
	
	public function audit($id, $audit)
	{
		$sql = "UPDATE " . DB_PREFIX . "interactive_member SET status = " . $audit . " WHERE id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function member_queue_add($info)
	{
		$data = array(
			'program_id'	=> $info['program_id'],
			'channel_id' 	=> $info['channel_id'],
			'member_id' 	=> $info['member_id'],
			'member_name' 	=> $info['member_name'],
			'nick_name' 	=> $info['nick_name'],
			'plat_id'		=> $info['plat_id'],
			'plat_type' 	=> $info['plat_type'],
			'plat_name' 	=> $info['plat_name'],
			'plat_token' 	=> $info['plat_token'],
			'plat_since_id' => $info['plat_since_id'],
			'start_time' 	=> $info['start_time'],
			'end_time'		=> $info['end_time'],
			'create_time'	=> $info['create_time'],
		);

		$sql = "INSERT INTO " . DB_PREFIX . "interactive_member_queue SET ";
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
	
	function check_member_exists_by_id($member_id, $plat_id)
	{
		$sql = "SELECT id FROM " . DB_PREFIX . "interactive_member WHERE member_id = " . $member_id . " AND plat_id = " . $plat_id;
		$data = $this->db->query_first($sql);
		return $data;
	}
	
}
?>