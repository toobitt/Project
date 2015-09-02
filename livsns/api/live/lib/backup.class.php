<?php
/***************************************************************************
* $Id: backup.class.php 19895 2013-04-08 02:42:01Z lijiaying $
***************************************************************************/
class backup extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition, $offset = 0, $count = 20, $orderby = '')
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$orderby = $orderby ? $orderby : " ORDER BY id DESC ";
		
		$sql = "SELECT * FROM " . DB_PREFIX . "backup ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
			
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
				
		$sql = "SELECT * FROM " . DB_PREFIX . "backup " . $condition;		
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
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "backup WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "backup SET ";
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
		$sql = "UPDATE " . DB_PREFIX . "backup SET ";
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
		$sql = "DELETE FROM " . DB_PREFIX . "backup WHERE id IN (" . $id . ")";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function get_backup_info($condition, $offset = 0, $count = 20, $orderby = '', $field = ' * ')
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$orderby = $orderby ? $orderby : " ORDER BY id DESC ";
		
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "backup ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit; 
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if ($row['create_time'])
			{
				$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			}
			if ($row['update_time'])
			{
				$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
			}

			$return[] = $row;
		}
		
		return $return;
	}
	
	public function get_backup_info_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "backup ";
		$sql.= " WHERE id IN (" . $id . ")"; 
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if ($row['create_time'])
			{
				$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			}
			if ($row['update_time'])
			{
				$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
			}

			$return[] = $row;
		}
		
		return $return;
	}
	
	public function get_backup_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "backup ";
		$sql.= " WHERE id = " . $id;
		
		if ($server_id)
		{
			$sql .= " AND server_id = " . $server_id;
		}
		
		$row = $this->db->query_first($sql);
		
		if ($row['create_time'])
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
		}
		
		if ($row['update_time'])
		{
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
		}
		
		return $row;
	}
	
	public function get_backup_by_vod_id($vod_id, $field = ' * ', $server_id = '')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "backup ";
		$sql.= " WHERE vod_id = " . $vod_id;
		
		if ($server_id)
		{
			$sql .= " AND server_id = " . $server_id;
		}
		
		$row = $this->db->query_first($sql);
		
		if ($row['create_time'])
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
		}
		
		if ($row['update_time'])
		{
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
		}
		
		return $row;
	}
	
	public function get_server_info($server_info)
	{
		if ($server_info['host'])
		{
			$host = $server_info['host'] . ':' . $server_info['input_port'];
			$wowzaip_input  = $server_info['host'];
			$wowzaip_output = $server_info['host'] . ':' . $server_info['output_port'];
			$output_append_host = $server_info['output_append_host'];
		}
		else 
		{
			$host = $this->settings['wowza']['live_server']['host'] . ':' . $this->settings['wowza']['live_server']['input_port'];
			$wowzaip_input  = $this->settings['wowza']['live_server']['host'];
			$wowzaip_output = $this->settings['wowza']['live_server']['host'] . ':' . $this->settings['wowza']['live_server']['output_port'];
			$output_append_host = $this->settings['wowza']['output_append_host'];
		}
		
		$input_dir  = $this->settings['wowza']['live_server']['input_dir'];
		$output_dir = $this->settings['wowza']['live_server']['output_dir'];
		
		$return = array(
			'host'					=> $host,
			'input_dir'				=> $input_dir,
			'output_dir'			=> $output_dir,
			'wowzaip_input'			=> $wowzaip_input,
			'wowzaip_output'		=> $wowzaip_output,
			'output_append_host'	=> $output_append_host,
		);
		return $return;
	}
}
?>