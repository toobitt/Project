<?php
/***************************************************************************
* $Id: server_config.class.php 19895 2013-04-08 02:42:01Z lijiaying $
* 服务器配置类
***************************************************************************/
class serverConfig extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '', $offset = 0, $count = 100, $orderby = '')
	{
		/******* 查询所有fid *******/
		$sql = "SELECT fid FROM " .DB_PREFIX. "server_config";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			if($row['fid'])
			{
				$re[] = $row['fid'];
			}
		}
		/*************************/
		$orderby = $orderby ? $orderby : " ORDER BY id DESC ";
		$limit 	 = " LIMIT " . $offset . "," . $count;
		
		$sql = "SELECT * FROM " . DB_PREFIX . "server_config ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if($re && in_array($row['id'],$re))
			{
				$row['is_bhost'] = 1; //记录该主机是否有备份主机
			}
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			$row['output_append_host'] = @unserialize($row['output_append_host']);
			$return[$row['id']] = $row;
		}
		return $return;
	}

	public function detail($id)
	{
		if(!$id)
		{
			$condition = " ORDER BY id DESC LIMIT 1";
		}
		else
		{
			$condition = " WHERE id IN (" . $id . ")";
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "server_config " . $condition;		
		$row = $this->db->query_first($sql);
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['output_append_host']	= @unserialize($row['output_append_host']);
			return $row;
		}
		return false;	
	}
	
	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "server_config WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "server_config SET ";
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
	
	public function update($data)
	{
		$sql = "UPDATE " . DB_PREFIX . "server_config SET ";
		$space = "";
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
		$sql = "DELETE FROM " . DB_PREFIX . "server_config ";
		$sql.= " WHERE id IN (" . $id . ")";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function get_server_config($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "server_config ";
		$sql.= " WHERE id IN (" . $id . ")";
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if ($row['output_append_host'])
			{
				$row['output_append_host'] = @unserialize($row['output_append_host']);
			}
			//如果主控主机不用于收录,则选择用于收录的备份主机
			/*
			if(!$row['is_record'])
			{
				$sql = "SELECT host,input_port,input_dir FROM " .DB_PREFIX. "server_config WHERE fid = " .$row['id']. " and is_record = 1";
				$tmp = $this->db->query_first($sql);
				$row['host'] = $tmp['host'];
				$row['input_port'] = $tmp['input_port'];
				$row['input_dir'] = $tmp['input_dir'];
			}
			*/
			$return[$row['id']] = $row;
		}
		return $return;
	}
	
	public function get_server_config_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "server_config WHERE id = " . $id;
		$return = $this->db->query_first($sql);
		if ($return['output_append_host'])
		{
			$return['output_append_host'] = @unserialize($return['output_append_host']);
		}
		return $return;
	}
	
}
?>