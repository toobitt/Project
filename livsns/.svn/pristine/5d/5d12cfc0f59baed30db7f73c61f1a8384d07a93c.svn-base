<?php

class deploy extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function insert_col_tem($data)
	{
		$sql="INSERT INTO " . DB_PREFIX . "deploy_template SET";
		
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
	
	public function update_col_tem($site_id,$column_id,$client_id,$type,$template_id)
	{
		$sql="UPDATE " . DB_PREFIX . "deploy_template SET template_id=".$template_id." WHERE site_id=".$site_id." AND type='".$type."' AND client_id=".$client_id;
		$sql .= $column_id?(" AND column_id=".$column_id):'';
		$this->db->query($sql);
		return true;
	}
	
	public function update($tablename,$id,$data)
	{
		$sql="UPDATE " . DB_PREFIX . $tablename." SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id=".$id;
		$this->db->query($sql);
	}
	
	public function delete($tablename,$ids)
	{
		$sql = "DELETE FROM ".DB_PREFIX.$tablename." WHERE id in(".$ids.")";
		$this->db->query($sql);
	}
	
	public function get_col_tem($site_id,$column_id,$client_id,$type)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "deploy_template WHERE site_id=".$site_id." AND type='".$type."' AND client_id=".$client_id;
		$sql .= $column_id?(" AND column_id=".$column_id):'';
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_page_tem($con = '')
	{
		$result = array();
		$sql = "SELECT * FROM " . DB_PREFIX . "deploy_template WHERE 1 ".$con;
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$result[$row['site_id']][$row['page_id']][$row['page_data_id']][$row['client_id']][$row['content_type']] = $row;
		}
		return $result;
	}
	
	public function get_deploy_template($site_id,$tem_style,$page_id,$page_data_id)
	{
		$ret = array();
//		$sql = "SELECT ct.site_id,ct.page_id,ct.page_data_id,ct.content_type,t.id as tid,t.title,ct.client_id,ct.template_sign FROM " . DB_PREFIX . "deploy_template ct LEFT JOIN " . DB_PREFIX . "templates t ON ct.template_sign=t.sign LEFT JOIN ".DB_PREFIX."templates t2 ON ct.site_id=t2.site_id" .
//				" WHERE 1 ";
//		$sql .= " AND ct.site_id in(".$site_id.") AND t.template_style in(".$tem_style.")";
//		$sql .= " AND ct.page_id in(".$page_id.") AND ct.page_data_id in(".$page_data_id.")";
//		$info = $this->db->query($sql);
		$sql = "SELECT dt.*,t.title FROM ".DB_PREFIX."deploy_template dt LEFT JOIN ".DB_PREFIX."templates t ON dt.template_sign=t.sign WHERE dt.site_id=".$site_id." AND t.site_id=".$site_id." AND t.template_style='".$tem_style."' AND dt.page_id=".$page_id." AND dt.page_data_id=".$page_data_id;
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[$row['site_id']][$row['page_id']][$row['page_data_id']][$row['client_id']][$row['content_type']] = $row;
		}
		return $ret;
	}
	
	public function get_deploy_template_all($site_id='',$tem_style='',$page_id='',$page_data_id='')
	{
		$ret = array();
//		$sql = "SELECT ct.* FROM " . DB_PREFIX . "deploy_template ct LEFT JOIN " . DB_PREFIX . "templates t ON ct.template_sign=t.sign LEFT JOIN ".DB_PREFIX."templates t2 ON ct.site_id=t2.site_id" .
//				" WHERE 1 ";
		$sql = "SELECT * FROM ".DB_PREFIX."deploy_template WHERE 1";
		if($site_id)
		{
			$sql .= " AND site_id=".$site_id;
		}
		if($page_id)
		{
			$sql .= " AND page_id=".$page_id;
		}
		if($page_data_id)
		{
			$sql .= " AND page_data_id=".$page_data_id;
		}
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[$row['site_id']][$row['page_id']][$row['page_data_id']][$row['client_id']][$row['content_type']] = $row;
		}
		return $ret;
	}
	
	public function get_tem_data($ids)
	{
		$sql = "SELECT id,title FROM " . DB_PREFIX . "templates WHERE id in(".$ids.")";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_deploy_by_sign($site_id,$sign)
	{
		$ret = array();
		$sql = "SELECT * FROM " . DB_PREFIX . "deploy_template WHERE site_id=".$site_id." AND template_sign='".$sign."'";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[] = $row;
		}		
		return $ret;
	}
}
?>