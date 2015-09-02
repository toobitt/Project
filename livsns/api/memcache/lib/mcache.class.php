<?php

class mcache extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_memcache($offset,$count,$condition)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."memcache ".$condition." ORDER BY id ASC LIMIT {$offset},{$count}";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_memcache_first($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."memcache WHERE id=".$id;
		$plan = $this->db->query_first($sql);
		return $plan;
	}
	
	public function get_memcaches($ids,$key='')
	{
		$sql = "SELECT * FROM ".DB_PREFIX."memcache WHERE id in(".$ids.")";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			if($key)
			{
				$ret[$row[$key]] = $row;
			}
			else
			{
				$ret[] = $row;
			}
		}
		return $ret;
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
	
	public function delete_memcache($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "memcache WHERE id=".$id;
		$this->db->query($sql);
		return true;
	}
	
	public function delete_relation($db_id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "memcache_relation WHERE memcache_id=".$db_id;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	public function delete_relation_by_am($bundle_id,$module_id,$memcache_id='')
	{
		$sql = "DELETE FROM " . DB_PREFIX . "memcache_relation WHERE bundle_id='".$bundle_id."' AND module_id='".$module_id."'";
		if($memcache_id)
		{
			$sql .= " AND memcache_id not in(".$memcache_id.")";
		}
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
		$sql = "SELECT * FROM ".DB_PREFIX."memcache_relation WHERE memcache_id=".$db_id;
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[$row['bundle_id']][$row['module_id']] = $row;
		}
		return $ret;
	}
	
	public function get_relation_by_am($bundle_id,$module_id)
	{
		$ret = array();
		$sql = "SELECT * FROM ".DB_PREFIX."memcache_relation WHERE bundle_id='".$bundle_id."' AND module_id='".$module_id."'";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$row['param'] = $row['param']?unserialize($row['param']):array();
			$ret['relation'][$row['bundle_id']][$row['module_id']][$row['memcache_id']] = $row;
			$ret['memcache_id'][$row['memcache_id']] = $row['memcache_id'];
		}
		return $ret;
	}
	
	public function get_relation_by_m($module_id)
	{
		$ret = array();
		$sql = "SELECT * FROM ".DB_PREFIX."memcache_relation WHERE  module_id in ('".$module_id."')";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[$row['bundle_id']][$row['module_id']][$row['memcache_id']] = $row;
		}
		return $ret;
	}
	
}
?>