<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: site.class.php 6931 2012-05-31 07:33:56Z repheal $
***************************************************************************/
class client extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_client($field = '*',$condition = '',$offset,$count)
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."client WHERE 1".$condition."  ORDER BY id ASC LIMIT {$offset},{$count}";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_all_client($field = '*',$condition = '',$key = '')
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."client WHERE 1".$condition;
		$info = $key?$this->db->fetch_all($sql,$key):$this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_client_by_id($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."client WHERE id=$id";
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function insert_client($data)
	{
		$sql="INSERT INTO " . DB_PREFIX . "client SET";
		
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
	
	public function update_client($client_id,$data)
	{
		$sql="UPDATE " . DB_PREFIX . "client SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra.' WHERE id='.$client_id;
		$this->db->query($sql);
		$site = $this->get_client_by_id($client_id);
		return $site;
	}
	
	public function delete($client_id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "client WHERE id=$client_id";
		$this->db->query($sql);
		return true;
	}
	
}

?>