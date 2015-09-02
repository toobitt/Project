<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: site.class.php 6931 2012-05-31 07:33:56Z repheal $
***************************************************************************/
class mkpublish extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function insert($table,$data)
	{
		$sql="INSERT INTO " . DB_PREFIX .$table. " SET ";
		
		if(is_array($data))
		{
			$sql_extra=$space=' ';
			foreach($data as $k => $v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
			}
			$sql .=$sql_extra;
		}
		else
		{
			$sql .= $data;
		}
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	public function update($tablename,$con,$data)
	{
		$sql="UPDATE " . DB_PREFIX .$tablename. " SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE ".$con;
		$this->db->query($sql);
	}
	
	public function insert_plan($sqlarr)
	{
		$deleteid = $insertsql = $allplan = array();
		
		$sql = "SELECT * FROM ".DB_PREFIX."mking ";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$allplan[$row['site_id']][$row['page_id']][$row['page_data_id']][$row['client_type']][$row['content_type']] = $row;
		}
		foreach($sqlarr as $k=>$v)
		{
			if(isset($allplan[$v['site_id']][$v['page_id']][$v['page_data_id']][$row['client_type']][$v['content_type']]))
			{
				$deleteid[] = $allplan[$v['site_id']][$v['page_id']][$v['page_data_id']][$row['client_type']][$v['content_type']]['id'];
			}
		}
		
		if($deleteid)
		{
			$sql = "DELETE FROM ".DB_PREFIX."mking WHERE id IN(".implode(',',$deleteid).")";
			$this->db->query($sql);
		}
		
		$i = 0;
		$tag = '';
		foreach($sqlarr as $k=>$v)
		{
			$insertsql[$i] = empty($insertsql[$i])?'':$insertsql[$i];
			$insertsql[$i] = $insertsql[$i].$tag."('".$v['site_id']."','".$v['page_id']."','".$v['page_data_id']."','".$v['client_type']."','".$v['content_type']."','".$v['publish_time']."','".$v['publish_user']."','".$v['content_param'].".','".$v['count']."')";
			$tag = ',';
			if($i%50==0)
			{
				$i++;
				$tag = '';
			}
		}
		foreach($insertsql as $k=>$v)
		{
			$sql = "INSERT INTO ".DB_PREFIX."mking(site_id,page_id,page_data_id,client_type,content_type,publish_time,publish_user,content_param,count) VALUES ".$v;
			$this->db->query($sql);
		}
	}
	
	public function get_plan($condition='',$limit='')
	{
		$ret = array();
		$sql = "SELECT * FROM ".DB_PREFIX."mking WHERE 1 ".$condition.$limit;
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[] = $row;
		}
		return $ret;
	}
	
	public function get_plan_first()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."mking WHERE publish_time<=".TIMENOW." ORDER BY publish_time LIMIT 1";
		$info = $this->db->query_first($sql);
		if($info)
		{
			$info['content_param'] = $info['content_param']?unserialize($info['content_param']):array();
		}
		return $info;
	}
	
	public function delete($tablename,$con)
	{
		$sql = "DELETE FROM ".DB_PREFIX.$tablename." WHERE ".$con;
		$this->db->query($sql);
	}
	
	public function get_mking($condition,$offset,$count)
	{
		$ret = array();
		$sql = "select * from ".DB_PREFIX."mking where 1 ".$condition." limit {$offset},{$count}";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[] = $row;
		}
		return $ret;
	}
	
}

?>