<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: site.class.php 6931 2012-05-31 07:33:56Z repheal $
***************************************************************************/
class site extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_site($field = '*',$condition = '')
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."site ".$condition;
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_site_by_id($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."site WHERE id=$id";
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function insert_site($data)
	{
		$sql="INSERT INTO " . DB_PREFIX . "site SET";
		
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
	
	public function update_site($site_id,$data)
	{
		$sql="UPDATE " . DB_PREFIX . "site SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra.' WHERE id='.$site_id;
		$this->db->query($sql);
		$site = $this->get_site_by_id($site_id);
		return $site;
	}
	
	public function delete($site_id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "site WHERE id=$site_id";
		$this->db->query($sql);
		$sql = "DELETE FROM " . DB_PREFIX . "domain WHERE type=".$this->settings['domain_type']['site']." AND from_id=".$site_id;
		$this->db->query($sql);
		$sql = "DELETE FROM " . DB_PREFIX . "site_col_sort WHERE site_id=".$site_id;
		$this->db->query($sql);
		return true;
	}
	
	public function update_site_col_sort($id,$updatedata)
	{
		$sql="UPDATE " . DB_PREFIX . "site_col_sort SET";
		
		$sql_extra=$space=' ';
		foreach($updatedata as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra.' WHERE id='.$id;
		$this->db->query($sql);
	}
	
}

?>