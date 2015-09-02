<?php
/***************************************************************************
* $Id: record_config.class.php 23181 2013-06-05 09:47:01Z lijiaying $
***************************************************************************/
class recordConfig extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		require_once(ROOT_PATH . 'lib/class/curl.class.php');
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '', $offset = 0, $count = 100, $orderby = '')
	{
		$orderby = $orderby ? $orderby : " ORDER BY id DESC ";
		$limit 	 = " LIMIT " . $offset . "," . $count;
		
		$sql = "SELECT * FROM " . DB_PREFIX . "record_config ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "record_config " . $condition;		
		$row = $this->db->query_first($sql);

		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);

			return $row;
		}
		
		return false;	
	}
	
	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "record_config WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "record_config SET ";
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
		$sql = "UPDATE " . DB_PREFIX . "record_config SET ";
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
		$sql = "DELETE FROM " . DB_PREFIX . "record_config ";
		$sql.= " WHERE id IN (" . $id . ")";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function get_record_config($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "record_config ";
		$sql.= " WHERE id IN (" . $id . ")";
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[$row['id']] = $row;
		}
		return $return;
	}
	
	public function get_record_config_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "record_config WHERE id = " . $id;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function check_server($url)
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_exec($ch);
		$head_info = curl_getinfo($ch);
        curl_close($ch);
		if ($head_info['http_code'] != 200)
		{
			return false;
		}
		return true;
	}
	
	/**
	 * 取 mediaserver 应用配置
	 * Enter description here ...
	 */
	public function get_mediaserver_config()
	{
		//获取需要修改的配置
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'settings');
		$settings = $curl->request('configuare.php');
		$config = array(
			'default_record_file_path' 		=> $settings['define']['TARGET_DIR'],
			'default_timeshift_file_path' 	=> $settings['define']['UPLOAD_DIR'],
		);
		return $config;
	}
}
?>