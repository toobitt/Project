<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: site.class.php 6931 2012-05-31 07:33:56Z repheal $
***************************************************************************/
class column extends InitFrm
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
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."site WHERE 1".$condition;
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_sort_by_id($sort_id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."site_col_sort WHERE id=$sort_id";
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_site_by_sort_id($sort_id)
	{
		$sql = "SELECT id FROM ".DB_PREFIX."site WHERE sort_id=$sort_id";
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_column_by_sort_id($sort_id)
	{
		$sql = "SELECT id FROM ".DB_PREFIX."column WHERE sort_id=$sort_id";
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_column_by_id($field,$ids)
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."column WHERE 1 AND id in ($ids)";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_column($field = '*',$con = '')
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."column WHERE 1 ".$con;
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_column_first($field = '*',$id)
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."column WHERE id=".$id;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_column_all($col_id , $site_id = '')
	{
		$sql = "SELECT c.* FROM ".DB_PREFIX."column c " .
				"LEFT JOIN ".DB_PREFIX."column_icon i on c.id=i.column_id " .
				"WHERE 1 ";
		if($site_id)
		{
			$sql .= " AND c.site_id=".$site_id;
		}
		$sql .= " AND c.fid=".$col_id;
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_columnall($ids)
	{
		$sql = "SELECT c.*,s.create_time  FROM ".DB_PREFIX."column c " .
				"LEFT JOIN ".DB_PREFIX."column_icon i on c.id=i.column_id " .
				"LEFT JOIN ".DB_PREFIX."site_col_sort s on c.sort_id=s.id " .
				"WHERE c.sort_id in (".$ids.")";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_columninfo($id)
	{
		$sql = "SELECT c.*,i.type,i.icon_default,i.activation,i.no_activation  FROM ".DB_PREFIX."column c LEFT JOIN ".DB_PREFIX."column_icon i on c.id=i.column_id WHERE c.id=".$id;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_column_icon($column_id)
	{
		$sql = "SELECT *  FROM ".DB_PREFIX."column_icon WHERE column_id=".$column_id;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_sort($field = '*',$con = '')
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."site_col_sort WHERE 1 ".$con;
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_domain($domain)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."domain WHERE domain='".$domain."'";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function insert_column($data)
	{
		$sql="INSERT INTO " . DB_PREFIX . "column SET";
		
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
	
	public function insert_column_icon($id,$default,$activation,$no_activation)
	{
		$data = array(
			'column_id' => $id,
			'icon_default' => json_encode($default),
			'activation' => json_encode($activation),
			'no_activation' => json_encode($no_activation),
		);
		$sql="INSERT INTO " . DB_PREFIX . "column_icon SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return true;
	}
	
	public function insert_domain($domain,$point_dir)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "domain(domain,point_dir) values('$domain','$point_dir')";
		$this->db->query($sql);
	}
	
	public function update_column_icon($column_id,$data)
	{
		$sql="UPDATE " . DB_PREFIX . "column_icon SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra.' WHERE column_id='.$column_id;
		$this->db->query($sql);
//		$site = $this->get_site_by_id($site_id);
		return true;
	}
	
	public function update_column($id,$data)
	{
		$sql="UPDATE " . DB_PREFIX . "column SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra.' WHERE id='.$id;
		$this->db->query($sql);
//		$site = $this->get_site_by_id($site_id);
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
	
	public function delete($ids)
	{
		$sql = "DELETE FROM " . DB_PREFIX ." column WHERE id in ($ids)";
		$this->db->query($sql);
		$sql = "DELETE FROM " . DB_PREFIX ." column_icon WHERE column_id in ($ids)";
		$this->db->query($sql);
		return true;
	}
	
}

?>