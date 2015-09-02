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
	
	public function get_site($field = '*',$condition = '',$key = '')
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."site WHERE 1".$condition;
		$info = $key?$this->db->fetch_all($sql,$key):$this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_site_by_id($id,$field = '*')
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."site WHERE id=$id";
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_client($field = '*',$id)
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."client WHERE 1";
		$info = $this->db->fetch_all($sql,$id);
		return $info;
	}
		
	public function get_column_by_id($field,$ids,$key='')
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."column WHERE 1 AND id in ($ids)";
		if($key)
		{
			$info = $this->db->fetch_all($sql,$key);
		}
		else
		{
			$info = $this->db->fetch_all($sql);
		}
		return $info;
	}
	
	public function get_column($field = '*',$con = '',$offset,$count)
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."column WHERE 1 ".$con." LIMIT {$offset},{$count} ";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_column_by_con($field = '*',$con = '')
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."column WHERE 1 ".$con;
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_column_by_fid($field,$fid)
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."column WHERE 1 AND fid=".$fid;
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_column_first($field = '*',$id)
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."column WHERE id=".$id;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_column_info($field = '*',$con = '')
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."column WHERE 1 ".$con;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_column_all_icon($column_id)
	{
		$sql = "SELECT *  FROM ".DB_PREFIX."column_icon WHERE column_id=".$column_id;
		$info = $this->db->fetch_all($sql,'client');
		return $info;
	}
	
	public function get_column_icon($column_id,$client)
	{
		$sql = "SELECT *  FROM ".DB_PREFIX."column_icon WHERE column_id=".$column_id." AND client=".$client;
		$info = $this->db->query_first($sql);
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
	
	public function insert_column_icon($id,$client,$default,$activation,$no_activation)
	{
		$data = array(
			'column_id' => $id,
			'client' => $client,
			'icon_default' => empty($default)?'':serialize($default),
			'activation' => empty($activation)?'':serialize($activation),
			'no_activation' => empty($no_activation)?'':serialize($no_activation),
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
	
	public function update_column_icon($column_id,$client,$data)
	{
		$sql="UPDATE " . DB_PREFIX . "column_icon SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra.' WHERE column_id='.$column_id.' AND client='.$client;
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
	
	public function delete($ids)
	{
		$sql = "DELETE FROM " . DB_PREFIX ." column WHERE id in ($ids)";
		$this->db->query($sql);
		$sql = "DELETE FROM " . DB_PREFIX ." column_icon WHERE column_id in ($ids)";
		$this->db->query($sql);
		return true;
	}
	
	//获取栏目支持的客户端
	public function get_column_support_client($support_client,$site_id,$column_fid)
	{
		if($column_fid)
		{
			$column_detail = $this->get_column_first($field = 'support_client',$column_fid);
			$all_support_client = $column_detail['support_client'];
		}
		else
		{
			if($site_id)
			{
				$site_detail = $this->get_site_by_id($site_id,$field = ' support_client ');
				$all_support_client = $site_detail['support_client'];
			}
			else
			{
				return false;
			}
		}
		if($support_client)
		{
			$all_support_client_arr = explode(',',$all_support_client);
			$support_client_arr = explode(',',$support_client);
			foreach($support_client_arr as $k=>$v)
			{
				if(!in_array($v,$all_support_client_arr))
				{
					unset($support_client_arr[$k]);
				}
			}
			if(empty($support_client_arr))
			{
				return false;
			}
			else
			{
				return implode(',',$support_client_arr);
			}
		}
		else
		{
			return $all_support_client;
		}
	}
	
}

?>