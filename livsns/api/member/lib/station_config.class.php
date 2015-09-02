<?php 
/***************************************************************************

* $Id: station_config.class.php 15421 2012-12-12 09:28:06Z repheal $

***************************************************************************/
class stationConfig extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function _show($condition, $offset, $count)
	{
		$data_limit = " LIMIT " . $offset . " , " . $count;

		$sql = "SELECT * FROM " . DB_PREFIX . "station_config ";
		
		$sql .= " WHERE 1 " . $condition . " ORDER BY order_id DESC " . $data_limit;
		
		$q = $this->db->query($sql);

		$station_config = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			
			$station_config[$row['id']] = $row;
		}
	
		if (!empty($station_config))
		{
			return $station_config;
		}
		return false;
	}
	
	public function _detail($id)
	{
		if(!$id)
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id IN (' . $id .')';
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "station_config " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			
			return $row;
		}
		return false;
	}

	public function _count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "station_config WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function _create($info,$user)
	{

		$data = array(
			'name' 			=> $info['name'],
			'platform' 		=> $info['platform'],
			'callback' 		=> $info['callback'],
			'user_id' 		=> $user['user_id'],
			'user_name' 	=> $user['user_name'],
			'appid' 		=> $user['appid'],
			'appname' 		=> $user['display_name'],
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
			'ip' 			=> hg_getip()
		);
		
		$sql = "INSERT INTO " . DB_PREFIX . "station_config SET ";
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
			$sql = "UPDATE " . DB_PREFIX . "station_config SET order_id = " . $data['id'] . " WHERE id = " . $data['id'];
			$this->db->query($sql);
			return $data;
		}

		return false;
	}
	
	public function _update($id, $info)
	{

		$data = array(
			'name' 			=> $info['name'],
			'platform' 		=> $info['platform'],
			'callback' 		=> $info['callback'],
			'update_time' 	=> TIMENOW,
		);
		
		$sql = "UPDATE " . DB_PREFIX . "station_config SET ";
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
	
	public function _delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "station_config ";
		$sql.= " WHERE id IN (" . $id . ")";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function _audit($id, $table, $type)
	{
		$sql = "SELECT " . $type . " FROM " . DB_PREFIX . $table . " WHERE id = " . $id;
		$info = $this->db->query_first($sql);

		$status = $info[$type];
		
		$new_status = 0; //操作失败
		
		if (!$status)	//已审核
		{
			$sql = "UPDATE " . DB_PREFIX . $table . " SET ".$type." = 1 WHERE id = " . $id;
			$this->db->query($sql);
			$new_status = 1;
		}
		else			//待审核
		{
			$sql = "UPDATE " . DB_PREFIX . $table . " SET ".$type." = 0 WHERE id = " . $id;
			$this->db->query($sql);
			$new_status = 2;
		}
		return $new_status;
	}
	
	public function _get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND name LIKE \'%'.urldecode($this->input['k']).'%\'';
		}
		
		if(isset($this->input['start_time']) && !empty($this->input['start_time']))
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if(isset($this->input['end_time']) && !empty($this->input['end_time']))
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		if($this->input['status'] && urldecode($this->input['status'])!= -1)
		{
			$condition .= " AND status = '".urldecode($this->input['status'])."'";
		}
		else if(urldecode($this->input['status']) == '0')
		{
			$condition .= " AND status = 0 ";
		}
		
		if(isset($this->input['date_search']) && !empty($this->input['date_search']))
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		return $condition;
	}

	public function _check_platform_exists($platform)
	{
		$sql = "SELECT platform FROM " . DB_PREFIX . "station_config WHERE platform='$platform'";
		$data = $this->db->query_first($sql);
		return $data;
	}
}

?>