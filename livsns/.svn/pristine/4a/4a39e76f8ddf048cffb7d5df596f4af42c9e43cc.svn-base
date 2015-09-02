<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: site.class.php 6931 2012-05-31 07:33:56Z repheal $
***************************************************************************/
class content extends InitFrm
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
	
	public function get_app()
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."app WHERE father=0";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_app_child($app)
	{
		$con = empty($app)?" AND b.father=0":" AND b.bundle='".$app."'" ;
		$sql = "SELECT a.* FROM " . DB_PREFIX ."app a LEFT JOIN " . DB_PREFIX ."app b ON a.father=b.id WHERE 1 ".$con;
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_content($condition,$offset,$count)
	{
		$sql = "SELECT c.*,r.client_type FROM " . DB_PREFIX ."content_relation r LEFT JOIN " . DB_PREFIX ."content c on r.content_id=c.id " .
				"WHERE 1 ".$condition." ORDER BY r.create_time DESC LIMIT {$offset},{$count} ";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_content_by_ids($fields,$ids)
	{
		$sql = "SELECT ".$fields." FROM " . DB_PREFIX ."content WHERE id in (".$ids.")";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_content_by_fromid($fields,$bundle_id,$module_id,$struct_id,$fromid)
	{
		$sql = "SELECT ".$fields." FROM " . DB_PREFIX ."content WHERE bundle_id='".$bundle_id."' AND module_id='".$module_id."' AND struct_id='".$struct_id."' AND content_fromid='".$fromid."'";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_content_by_id($fields,$id)
	{
		$sql = "SELECT ".$fields." FROM " . DB_PREFIX ."content WHERE id =".$id;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_field($bundle_id,$module_id,$struct_id,$struct_ast_id = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."content_field WHERE bundle_id='".$bundle_id."' AND module_id='".$module_id."' AND struct_id='".$struct_id."' AND struct_ast_id='".$struct_ast_id."'";
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_field_by_id($id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."content_field WHERE id=".$id;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_expand($tablename,$id,$offset = '',$count = '')
	{
		$con = '';
		if($offset !== '' && $count !== '')
		{
			$con = " LIMIT {$offset},{$count}  ";
		}
		$sql = "SELECT * FROM " . DB_PREFIX .$tablename." WHERE id=".$id.$con;
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_expand_by_expand_id($tablename,$ids,$offset = '',$count = '')
	{
		$con = '';
		if($offset !== '' && $count !== '')
		{
			$con = " LIMIT {$offset},{$count}  ";
		}
		$sql = "SELECT * FROM " . DB_PREFIX .$tablename." WHERE expand_id in (".$ids.")".$con;
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_content_relation($cids)
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."content_relation WHERE content_id=".$cids;
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function delete_content_relation($cids,$column_ids = '')
	{
		$sql = "DELETE  FROM " . DB_PREFIX ."content_relation WHERE content_id in (".$cids.")";
		if($column_ids)
		{
			$sql .= " AND column_id in(".$column_ids.")";
		}
		$this->db->query($sql);
	}
	
	public function delete_expand($tablename,$ids)
	{
		$sql = "DELETE  FROM " . DB_PREFIX .$tablename." WHERE id in (".$ids.")";
		
		$this->db->query($sql);
	}
	
	public function delete_child_expand($tablename,$expand_ids)
	{
		$sql = "DELETE  FROM " . DB_PREFIX .$tablename." WHERE expand_id in (".$expand_ids.")";
		$this->db->query($sql);
	}
	
	public function update_content($bundle_id,$module_id,$struct_id,$fromid,$con)
	{
		$sql = "UPDATE " . DB_PREFIX ."content SET ".$con." WHERE bundle_id='".$bundle_id."' AND module_id='".$module_id."' AND struct_id='".$struct_id."' AND content_fromid in (".$fromid.")";
		$this->db->query($sql);
	}
	
	public function update_child_table($tablename,$con,$fromid)
	{
		$sql = "UPDATE " . DB_PREFIX .$tablename." SET ".$con." WHERE content_fromid in (".$fromid.")";
		$this->db->query($sql);
	}
	
	public function check_content($data)
	{
		//先查询出这个内容原id是否存在
		$sql = "SELECT id FROM " . DB_PREFIX ."content WHERE bundle_id='".$data['bundle_id']."' AND module_id='".$data['module_id']."' AND content_fromid=".$data['content_fromid'];
		$info1 = $this->db->query_first($sql);
		if(empty($info1))
		{
			return 'new';
		}
		else
		{
			//判断这个栏目下的客户端
			$sql = "SELECT r.id FROM " . DB_PREFIX ."content_relation r LEFT JOIN " . DB_PREFIX ."content c ON r.content_id=c.id WHERE r.bundle_id='".$data['bundle_id']."' AND r.module_id='".$data['module_id']."' AND r.column_id=".$data['column_id']." AND c.content_fromid=".$data['content_fromid']." AND r.client_type=".$data['client_type'];
			$info3 = $this->db->fetch_all($sql);
			if(empty($info3))
			{
				
				return $info1['id'];
			}
			else
			{
				return false;
			}
			
		}
		
	}
	
}

?>