<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: site.class.php 6931 2012-05-31 07:33:56Z repheal $
***************************************************************************/
class block extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_app()
	{
		$ret = array();
		$sql = "SELECT * FROM " . DB_PREFIX ."app WHERE father=0";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[$row['bundle']] = $row['name'];
		}
		return $ret;
	}
	
	public function get_block($condition,$offset,$count)
	{
		$ret = $block_record = array();
		$sql = "SELECT b.*,r.site_id,r.page_id,r.page_data_id FROM ". DB_PREFIX ."block_relation r left join ".DB_PREFIX."block b on r.block_id=b.id WHERE 1 ".$condition." LIMIT {$offset},{$count} ";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			if($row['datasource_argument'])
			{
				$datasource_argument = unserialize($row['datasource_argument']);
				$row['weight_min'] = isset($datasource_argument['weight_min'])?$datasource_argument['weight_min']:'';
				$row['weight_max'] = isset($datasource_argument['weight_max'])?$datasource_argument['weight_max']:'';
			}
			else
			{
				$row['weight'] = '';
			}
			$ret[] = $row;
			$rid[] = $row['id'];
		}
		$result['block'] = $ret;
		$result['block_record'] = $block_record;
		return $result;
	}
	
	public function get_block_first($id)
	{
		$sql = "SELECT * FROM ". DB_PREFIX ."block WHERE 1 "." AND id=".$id;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_all_block()
	{
		$sql = "SELECT * FROM ". DB_PREFIX ."block ";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_block_by_condition($condition , $more = false)
	{
		$sql = "SELECT * FROM ". DB_PREFIX ."block WHERE 1 ".$condition;
		$info = $more?$this->db->fetch_all($sql):$this->db->query_first($sql);
		return $info;
	}
	
	public function get_group_block($id)
	{
		$ret = $block_record = array();
		$sql = "SELECT b.* FROM ". DB_PREFIX ."block b LEFT JOIN ".DB_PREFIX."block b1 ON b.group_id=b1.id WHERE b1.id=".$id;
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$row['datasource_argument'] = @unserialize($row['datasource_argument']);
			$ret[$row['id']] = $row;
			$rid[] = $row['id'];
		}
		if($rid)
		{
//			$sql = "SELECT * FROM ".DB_PREFIX."block_record WHERE block_id in (".implode(',',$rid).")";
//			$info = $this->db->query($sql);
//			while($row = $this->db->fetch_array($info))
//			{
//				$block_record[$row['block_id']][] = $row['column_id'];
//			}
		}
		$result['block'] = $ret;
		$result['block_record'] = $block_record;
		return $result;
	}
	
	public function insert($data,$tablename = 'block')
	{
		$sql="INSERT INTO " . DB_PREFIX . $tablename." SET ";
		
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
	
	public function update($data,$id,$tablename = 'block')
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
	
	public function update_block_use_num($block_id , $tag = true)
	{
		if($tag)
		{
			$sql = "UPDATE " . DB_PREFIX . "block SET use_num=use_num+1 WHERE id=".$block_id;
		}
		else
		{
			$sql = "UPDATE " . DB_PREFIX . "block SET use_num=use_num-1 WHERE id=".$block_id;
		}
		$this->db->query($sql);
	}
	
	public function delete($ids)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "block WHERE id in(".$ids.")";
		$this->db->query($sql);
	}
	
	//检测有无此区块关联
	public function check_block_relation($site_id,$block_id=0,$page_id=0,$page_data_id=0,$expand_name='')
	{
		$sql = "SELECT id FROM ".DB_PREFIX."block_relation WHERE site_id={$site_id} AND page_id={$page_id} AND page_data_id={$page_data_id}";
		$info = $this->db->query_first($sql);
		if(empty($info))
		{
			$rid = self::insert(array('block_id'=>$block_id,'site_id'=>$site_id,'page_id'=>$page_id,'page_data_id'=>$page_data_id,'expand_name'=>$expand_name),'block_relation');
			return $block_id;
		}
		if($info['block_id']!=$block_id)
		{
			self::update(array('block_id'=>$block_id),$info['id'],'block_relation');
			return $block_id;
		}
		return false;
	}
	
}

?>