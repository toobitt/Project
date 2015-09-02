<?php
/***************************************************************************
* $Id: live.class.php 17481 2013-02-21 09:36:46Z gaoyuan $
***************************************************************************/
define('MOD_UNIQUEID','program_record_server');//模块标识
class server extends InitFrm
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
	
	public function show($condition,$data_limit)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 " . $condition . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['url'] = $row['protocol'] . $row['host'] . ($row['port'] ? ':' . $row['port'] . '/' : '/') . $row['dir'];
			$row['isSuccess'] = $this->checkServer($row['host'] . ':' . $row['port'] .'/'. $row['dir']);
			//获取版本号
			//$version = $this->get_version($row['host'] . ':' . $row['port'] . $row['dir'] . '?action=GET_VERSION');
			//$version['version'] ? $row['version'] = $version['version'] : $row['version'] = '未获取版本号';
			$info[] = $row;
		}
		return $info;		
	}
	
	//获取服务器版本号
	public function get_version($url)
	{
		//$url = 'http://10.0.1.58:8089/control/recordserver/task?action=GET_VERSION';
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
		
		$sql = "INSERT INTO " . DB_PREFIX . "server_config SET ";
		$space = "";
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space = ',';
		}
		$this->db->query($sql);
		$info['id'] = $this->db->insert_id();
		$info['isSuccess'] = $this->checkServer($info['host'] . ':' . $info['port']);
		return $info;
	}
	
	public function update($id = 0,$info = array())
	{
		if(empty($id) || empty($info))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			return false;
		}
		$sql = "UPDATE " . DB_PREFIX . "server_config SET ";
		$space = "";
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space = ',';
		}
		$sql .= " WHERE id=" . $id;
		$this->db->query($sql);
		$info['id'] = $id;
		$info['isSuccess'] = $this->checkServer($info['host'] . ':' . $info['port']);
		return $info;
	}
	
	public function update_state($id = 0,$state = 0)
	{
		if(empty($id))
		{
			return false;
		}
		$sql = "UPDATE " . DB_PREFIX . "server_config SET state=" . $state . " WHERE id IN(" . $id . ")";
		$this->db->query($sql);
		return true;
	}
	
	public function detail($condition = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1" . $condition;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			return false;
		}
		$f['isSuccess'] = $this->checkServer($f['host'] . ':' . $f['port']);
		return $f;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "server_config WHERE 1" . $condition;
		$f = $this->db->query_first($sql);
		return $f;
	}
	
	public function delete($id = 0)
	{
		if(empty($id))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			return $id;
		}
		else
		{
			$sql = "DELETE FROM " . DB_PREFIX . "server_config WHERE id=" . $id;
			$this->db->query($sql);
			return $id;
		}
	}
	private function checkServer($url)
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
}
?>