<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: affix_setting.class.php 6171 2012-03-23 02:11:37Z wangleyuan $
***************************************************************************/

class affixSetting extends InitFrm
{
	public function __cosntruct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition)
	{
		$sql="SELECT * FROM " . DB_PREFIX . "affix_setting WHERE 1 " . $condition;
		$ret=$this->db->query($sql);
		$info=array();
		while($row=$this->db->fetch_array($ret))
		{
			$info[]=$row;
		}
		return $info;
	}

	public function count($condition)
	{
		$sql="SELECT COUNT(*) as total FROM " . DB_PREFIX . "affix_setting WHERE 1 " . $condition;
		$info=$this->db->query_first($sql);
		return $info;
	}

	public function detail($condition)
	{
		$sql="SELECT * FROM " . DB_PREFIX . "affix_setting WHERE 1 " .$condition;
		$info=$this->db->query_first($sql);
		if(!empty($info))
		{
			return $info;
		}
		else
		{
			return false;
		}
	}

	public function create($data)
	{
		if(!$data) return false;
		$sql="INSERT INTO " . DB_PREFIX . "affix_setting SET";
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		$id=$this->db->insert_id();
		return $id;
	}

	public function update($data,$id)
	{
		if(!$data) return false;
		$sql = "UPDATE " . DB_PREFIX . "affix_setting SET";
		$sql_extra = $space = ' ';
		foreach($data as $k => $v)
		{
			$sql_extra .= $space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .= $sql_extra;
		$sql = $sql . ' WHERE aid =' . $id;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}

	public function delete($ids)
	{
		if(!$ids)
		{
			return false;
		}
		$sql="DELETE FROM " . DB_PREFIX . "affix_setting WHERE aid IN(" . $ids . ")";
		return $this->db->query($sql);
	}
	
	public function is_open($id)
	{
		$sql="SELECT * FROM " . DB_PREFIX . "affix_setting WHERE aid =" . $id;
		$r=$this->db->query($sql);
		return $r['is_open'];
	}
	
	function verifyToken()
	{
				
	}
}
?>