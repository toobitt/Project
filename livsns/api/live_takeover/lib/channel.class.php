<?php
/***************************************************************************
* $Id: channel.class.php 26355 2013-07-24 06:12:15Z lijiaying $
***************************************************************************/
class channel extends InitFrm
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
		$orderby = $orderby ? $orderby : " ORDER BY order_id DESC ";
		
		$sql = "SELECT * FROM " . DB_PREFIX . "channel ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		
		$q = $this->db->query($sql);
		
		$channel_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
			$row['logo_rectangle'] 	= unserialize($row['logo_rectangle']);
			$row['logo_square'] 	= unserialize($row['logo_square']);
			$row['client_logo'] 	= unserialize($row['client_logo']);
			$row['stream_name'] 	= unserialize($row['stream_name']);
			$row['snap'] 			= unserialize($row['snap']);
			
			$channel_info[$row['id']] = $row;
		}
		
		if (!empty($channel_info))
		{
			$channel_id = array_keys($channel_info);
			
			$channel_stream = $this->get_channel_stream(implode(',', $channel_id));
		}
		
		$return = array();
		foreach ($channel_info AS $k => $v)
		{
			if ($channel_stream[$k])
			{
				$return[] = array_merge($channel_info[$k],$channel_stream[$k]);
			}
			else 
			{
				$return[] = $channel_info[$k];
			}
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
				
		$sql = "SELECT * FROM " . DB_PREFIX . "channel " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
			$row['logo_rectangle'] 	= unserialize($row['logo_rectangle']);
			$row['logo_square'] 	= unserialize($row['logo_square']);
			$row['client_logo'] 	= unserialize($row['client_logo']);
			$row['stream_name'] 	= unserialize($row['stream_name']);
			$row['snap'] 			= unserialize($row['snap']);
			
			$row['channel_stream'] = $this->get_channel_stream_by_channel_id($row['id']);
			
			return $row;
		}
		return false;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "channel WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "channel SET ";
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
		$sql = "UPDATE " . DB_PREFIX . "channel SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = " . $data['id'];
		
		$q = $this->db->query($sql);
		
		if ($data['id'])
		{
			$data['affected_rows'] = 0;
			if ($this->db->affected_rows($q))
			{
				$data['affected_rows'] = 1;
			}
			return $data;
		}
		return false;
	}
	
	public function delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "channel WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		
		$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}

	public function get_channel_info_by_id($id, $field = ' * ', $is_stream = 1)
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "channel ";
		$sql.= " WHERE id IN (" . $id . ") ORDER BY order_id DESC"; 
		
		$q = $this->db->query($sql);
		
		$channel_info = array();
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
			if ($row['logo_rectangle'])
			{
				$row['logo_rectangle'] 	= unserialize($row['logo_rectangle']);
			}
			if ($row['logo_square'])
			{
				$row['logo_square'] 	= unserialize($row['logo_square']);
			}
			if ($row['client_logo'])
			{
				$row['client_logo'] 	= unserialize($row['client_logo']);
			}
			if ($row['stream_name'])
			{
				$row['stream_name'] 	= unserialize($row['stream_name']);
			}
			if ($row['snap'])
			{
				$row['snap'] 			= unserialize($row['snap']);
			}

			$channel_info[$row['id']] = $row;
		}
		
		if (!empty($id) && $is_stream)
		{
			$channel_stream = $this->get_channel_stream($id);
		}
		
		$return = array();
		foreach ($channel_info AS $k => $v)
		{
			if ($channel_stream[$k])
			{
				$return[] = array_merge($channel_info[$k],$channel_stream[$k]);
			}
			else 
			{
				$return[] = $channel_info[$k];
			}
		}
		
		return $return;
	}
	
	public function get_channel_by_id($id, $is_stream = 0, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "channel ";
		$sql.= " WHERE id = " . $id;
		
		$row = $this->db->query_first($sql);
		
		if ($row['create_time'])
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
		}
		
		if ($row['update_time'])
		{
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
		}
		
		if ($row['logo_rectangle'])
		{
			$row['logo_rectangle'] 	= unserialize($row['logo_rectangle']);
		}
		
		if ($row['logo_square'])
		{
			$row['logo_square'] 	= unserialize($row['logo_square']);
		}
		
		if ($row['client_logo'])
		{
			$row['client_logo'] 	= unserialize($row['client_logo']);
		}
		if ($row['stream_name'])
		{
			$row['stream_name'] 	= unserialize($row['stream_name']);
		}
		if ($row['snap'])
		{
			$row['snap'] 			= unserialize($row['snap']);
		}

		if ($is_stream)
		{
			$row['channel_stream'] = $this->get_channel_stream_by_channel_id($id);
		}
		
		return $row;
	}
	
	public function get_channel_stream($channel_id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "channel_stream ";
		$sql.= " WHERE channel_id IN (" . $channel_id . ")";
		$sql.= " ORDER BY order_id ASC ";
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[$row['channel_id']]['channel_stream'][] = $row;
		}
		return $return;
	}
	
	public function get_channel_stream_by_channel_id($channel_id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "channel_stream ";
		$sql.= " WHERE channel_id = " . $channel_id;
		$sql.= " ORDER BY order_id ASC ";
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		return $return;
	}
	
	public function channel_stream_create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "channel_stream SET ";
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
	
	public function channel_stream_update($data)
	{
		$sql = "UPDATE " . DB_PREFIX . "channel_stream SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = " . $data['id'];
		
		$q = $this->db->query($sql);
		
		if ($data['id'])
		{
			$data['affected_rows'] = 0;
			if ($this->db->affected_rows($q))
			{
				$data['affected_rows'] = 1;
			}
			return $data;
		}
		return false;
	}
	
	public function channel_stream_delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	/**
	 * 获取图片配置
	 * Enter description here ...
	 */
	public function get_img_url()
	{
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['App_material']['host'], $this->settings['App_material']['dir']);
		
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_config');
		$retutn = $this->curl->request('configuare.php');
		return $retutn;
	}
	
	public function add_material($file, $id)
	{
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$mMaterial = new material();
		if (!$mMaterial)
		{
			return false;
		}
		
		$files['Filedata'] = $file;
		$material = $mMaterial->addMaterial($files, $id);
		$return = array();
		if (!empty($material))
		{
			$return['host'] 	= $material['host'];
			$return['dir'] 		= $material['dir'];
			$return['filepath'] = $material['filepath'];
			$return['filename'] = $material['filename'];
		}
		
		return $return;
	}
	
	/**
	 * 取后台频道接口
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function getChannelInfo($data = array())
	{
		if($this->settings['App_live'])
		{
			if (!class_exists('curl'))
			{
				include_once (ROOT_PATH . 'lib/class/curl.class.php');
			}
			$this->curl = new curl($this->settings['App_live']['host'], $this->settings['App_live']['dir'] . 'admin/');
		}
		
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('channel.php');
		return $ret;
	}
}
?>