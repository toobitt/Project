<?php
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class server extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$data_limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "time_shift_server WHERE 1 " . $condition . $orderby . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['url'] = 'http://' . $row['host'] . ($row['port'] ? ':' . $row['port']  : '');
			$row['isSuccess'] = check_shift_server($row['url']);
			//获取版本号
			$version = $this->get_version($row['url'] . '/?action=GET_VERSION');
			$version['version'] ? $row['version'] = $version['version'] : $row['version'] = '未获取版本号';
			$info[] = $row;
		}
		return $info;		
	}
	//获取服务器版本号
	public function get_version($url)
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
		$ret = curl_exec($ch);
        curl_close($ch);
       	$ret = xml2Array($ret);
		return $ret;
	}
	public function create($info = array())
	{
		if(empty($info['name']) || empty($info['host']))
		{
			return false;
		}
		
		$sql = "INSERT INTO " . DB_PREFIX . "time_shift_server SET ";
		$space = "";
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space = ',';
		}
		$this->db->query($sql);
		$info['id'] = $this->db->insert_id();
		return $info;
	}
	
	public function update($id = 0,$info = array())
	{
		if(empty($id) || empty($info))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "time_shift_server WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			return false;
		}
		$sql = "UPDATE " . DB_PREFIX . "time_shift_server SET ";
		$space = "";
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space = ',';
		}
		$sql .= " WHERE id=" . $id;
		$this->db->query($sql);
		$info['id'] = $id;
		return $info;
	}
	
	public function update_state($id = 0,$state = 0)
	{
		if(empty($id))
		{
			return false;
		}
		$sql = "UPDATE " . DB_PREFIX . "time_shift_server SET is_open =" . $state . " WHERE id IN(" . $id . ")";
		$this->db->query($sql);
		return true;
	}
	
	public function detail($condition = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "time_shift_server WHERE 1" . $condition;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			return false;
		}
		return $f;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "time_shift_server WHERE 1" . $condition;
		$f = $this->db->query_first($sql);
		return $f;
	}
	
	public function delete($id = 0)
	{
		if(empty($id))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "time_shift_server WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			return $id;
		}
		else
		{
			$sql = "DELETE FROM " . DB_PREFIX . "time_shift_server WHERE id=" . $id;
			$this->db->query($sql);
			return $id;
		}
	}
}
?>