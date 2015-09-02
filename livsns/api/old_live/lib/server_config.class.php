<?php
/***************************************************************************
* $Id: server_config.class.php 17154 2013-01-29 13:55:33Z lijiaying $
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
	
	public function show($condition, $offset, $count, $orderby = '', $field = ' * ')
	{
		$orderby = $orderby ? $orderby : " ORDER BY id DESC ";
		$limit 	 = " LIMIT " . $offset . "," . $count;
		
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "server_config ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			$row['live_append_host']	= @unserialize($row['live_append_host']);
			
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
			$row['out_port'] 	= $row['out_port'] ? $row['out_port'] : '';
			$row['dvr_append_host']		= @unserialize($row['dvr_append_host']);
			$row['live_append_host']	= @unserialize($row['live_append_host']);
			//输出配置信息
			$sql = "SELECT * FROM " . DB_PREFIX . "server_output WHERE server_id IN (" . $row['id'] . ") ";
			$q = $this->db->query($sql);
			
			$row['output'] = array();
			while ($r = $this->db->fetch_array($q))
			{
				$row['output'][] = $r;
			}
			
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
	
	public function update($data, $id)
	{
		$sql = "UPDATE " . DB_PREFIX . "server_config SET ";
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
	
	public function output_show($server_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "server_output ";
		$sql.= " WHERE server_id IN (" . $server_id . ") ";
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[$row['server_id']][] = $row;
		}
		return $return;
	}
	
	public function output_replace($data)
	{
		$sql = "REPLACE INTO " . DB_PREFIX . "server_output SET ";
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

	public function output_create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "server_output SET ";
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
	
	public function output_update($data, $id)
	{
		$sql = "UPDATE " . DB_PREFIX . "server_output SET ";
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
	
	public function output_delete_by_server_id($server_id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "server_output ";
		$sql.= " WHERE server_id IN (" . $server_id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}

	public function get_server_config($server_id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "server_config WHERE id IN (" . $server_id . ")";
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['dvr_append_host']  = @unserialize($row['dvr_append_host']);
			$row['live_append_host'] = @unserialize($row['live_append_host']);
			$return[$row['id']] = $row;
		}
		return $return;
	}
	
	public function get_server_output($server_id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "server_output ";
		$sql.= " WHERE server_id IN (" . $server_id . ")";
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[$row['server_id']][$row['mark']] = $row;
		}
		return $return;
	}
	
	public function get_server_config_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "server_config WHERE id = " . $id;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function get_server_output_by_id($server_id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "server_output ";
		$sql.= " WHERE server_id IN (" . $server_id . ")";
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[$row['mark']] = $row;
		}
		return $return;
	}
	
	public function get_stream_by_server_id($server_id, $field = ' * ', $stream_id = '')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "stream WHERE server_id = " . $server_id;
		if ($stream_id)
		{
			$sql .= " AND id NOT IN (" . $stream_id . ") ";
		}
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		return $return;
	}
	
	public function get_channel_by_server_id($server_id, $field = ' * ', $channel_id = '')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "channel WHERE server_id = " . $server_id;
		if ($channel_id)
		{
			$sql .= " AND id NOT IN (" . $channel_id . ") ";
		}
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		return $return;
	}

	/**
	 * 检测服务器剩余流数目
	 * Enter description here ...
	 * @param unknown_type $server_id
	 * @param unknown_type $stream_id
	 * @param unknown_type $server_output
	 */
	public function get_server_info($server_id, $stream_id = '', $channel_id = '', $server_output = '')
	{
		$server_info = $this->get_server_config_by_id($server_id);
		
		if (empty($server_info))
		{
			return -1;
			//$this->errorOutput('该服务器信息不存在或已被删除');
		}
		
		$stream_info 	= $this->get_stream_by_server_id($server_id, 'stream_count', $stream_id);
		$channel_info 	= $this->get_channel_by_server_id($server_id, 'uri_in_num', $channel_id);
		
		//备播信号流数目
		$stream_count = 0;
		if (!empty($stream_info))
		{
			foreach ($stream_info AS $v)
			{
				$stream_count = $v['stream_count'] + $stream_count;
			}
		}
		//直播频道流数目
		$channel_stream_count = 0;
		if (!empty($channel_info))
		{
			foreach ($channel_info AS $v)
			{
				$channel_stream_count = $v['uri_in_num'] + $channel_stream_count;
			}
		}
		//已用数目
		$used_count = $stream_count + $channel_stream_count;
		
		if ($used_count > $server_info['counts'])
		{
			return -2;
			//$this->errorOutput('该服务器已经无法再添加信号，请选择其他服务器');
		}
		else if ($used_count == $server_info['counts'])
		{
		//	return 1;
		}
		//剩余数目
		$over_count = $server_info['counts'] - $used_count;
		
		$ret = array(
			'over_count'	=> $over_count,
			'used_count'	=> $used_count,
			'server_info'	=> $server_info,
		);
	
		//输出配置
		if ($server_output == 'server_output')
		{
			$server_output = $this->get_server_output_by_id($server_id);
			$ret['server_output'] = $server_output;
		}
		return $ret;
	}
}
?>