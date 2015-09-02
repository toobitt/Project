<?php

class textsearch extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_db($offset,$count,$condition)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."db ".$condition." ORDER BY id ASC LIMIT {$offset},{$count}";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_db_first($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."db WHERE id=".$id;
		$plan = $this->db->query_first($sql);
		return $plan;
	}
	
	public function insert($table,$data)
	{
		$sql="INSERT INTO " . DB_PREFIX . $table." SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	public function update($table,$data,$con)
	{
		$sql="UPDATE " . DB_PREFIX . $table." SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= ' WHERE 1 '.$con;
		$this->db->query($sql);
	}
	
	public function delete_db($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "db WHERE id=".$id;
		$this->db->query($sql);
		return true;
	}
	
	public function delete_relation($db_id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "db_relation WHERE db_id=".$db_id;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	public function replace_relation($table,$data)
	{
		$sql="REPLACE INTO " . DB_PREFIX .$table." SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	public function get_relation($db_id)
	{
		$ret = array();
		$sql = "SELECT * FROM ".DB_PREFIX."db_relation WHERE db_id=".$db_id;
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[$row['bundle_id']][$row['module_id']] = $row;
		}
		return $ret;
	}
}
?>