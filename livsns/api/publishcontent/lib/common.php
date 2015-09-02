<?php
class common extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//获取应用模块
	public function get_module()
	{
		$sql = "SELECT a1.id as id,a1.name as name FROM ".DB_PREFIX."app a1 LEFT JOIN ".DB_PREFIX."app a2 ON a1.father=a2.id WHERE a2.father=0 ";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	//根据id获取应用模块
	public function get_app($ids)
	{
		$sql = "SELECT a1.id as id,a1.name as mname,a1.bundle as mbundle,a2.name as aname,a2.bundle as abundle FROM ".DB_PREFIX."app a1 LEFT JOIN ".DB_PREFIX."app a2 ON a1.father=a2.id WHERE a1.id in (".$ids.")";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	/*
	 * 检测域名对应目录是否存在
	 */
	public function check_domain2($domain,$path,$type='',$from_id='')
	{
		$str = '';
		if(!empty($type))
		{
			$str .= " AND type!=".$type;
		}
		if(!empty($from_id))
		{
			$str .= " AND from_id!=".$from_id;
		}
		$sql = "SELECT * FROM ". DB_PREFIX ."domain WHERE domain='".$domain."'";
		$sql .= $str;
		$info = $this->db->fetch_all($sql);
		if(!empty($info))
		{
			return false;
		}
		$sql = "SELECT * FROM ". DB_PREFIX ."domain WHERE  domain='".$domain."' AND path='".$path."'";
		$sql .= $str;
		$info = $this->db->fetch_all($sql);
		if(!empty($info))
		{
			return false;
		}
		//当更新时，如果除了自己的记录，没有相同记录，则更新当前域名
		if(!empty($type) && !empty($from_id))
		{
			$sql = "UPDATE ". DB_PREFIX ."domain SET domain='".$domain."',path='".$path."' WHERE type=".$type." AND from_id=".$from_id;
			$this->db->query($sql);
		}
		return true;
	}
	
	public function check_domain($data)
	{
		$str = '';
		if($data['from_id'])
		{
			$sql = "SELECT id FROM ".DB_PREFIX."domain WHERE type=".$data['type']." AND from_id=".$data['from_id'];
			$info = $this->db->query_first($sql);
		}
		if(!empty($info['id']))
		{
			$str .= " AND id!=".$info['id'];
		}
		if($data['sub_domain'])
		{
			$str .= " AND sub_domain='".$data['sub_domain']."'";
		}
		if($data['domain'])
		{
			$str .= " AND domain='".$data['domain']."'";
		}
//		if($data['path'])
//		{
//			$str .= " AND path='".$data['path']."'";
//		}
		$sql = "SELECT id FROM ". DB_PREFIX ."domain WHERE 1";
		$sql .= $str;
		$info = $this->db->query_first($sql);
		if(!empty($info))
		{
			return false;
		}
		return true;
	}
	
	public function insert_domain($data)
	{
		if($data['path'])
		{
			hg_mkdir($data['path']);
		}
		$insert_data = array(
			'type' => $data['type'],
			'from_id' => $data['from_id'],
			'sub_domain' => $data['sub_domain'],
			'domain' => $data['domain'],
			'path' => $data['path']?realpath($data['path']):'',
		);
		$sql="INSERT INTO " . DB_PREFIX . "domain SET";
		
		$sql_extra=$space=' ';
		foreach($insert_data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
	}
	
	public function update_domain($data)
	{
		$sql = "SELECT id FROM ".DB_PREFIX."domain WHERE type=".$data['type']." AND from_id=".$data['from_id'];
		$info = $this->db->query_first($sql);
		if(empty($info))
		{
			common::insert_domain($data);
		}
		else
		{
			if($data['path'])
			{
				hg_mkdir($data['path']);
			}
			$update_data = array(
				'sub_domain' => $data['sub_domain'],
				'domain' => $data['domain'],
				'path' => $data['path']?realpath($data['path']):'',
			);
			$sql="UPDATE " . DB_PREFIX . "domain SET";
			
			$sql_extra=$space=' ';
			foreach($update_data as $k => $v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
			}
			$sql .=$sql_extra." WHERE type=".$data['type']." AND from_id=".$data['from_id'];
			$this->db->query($sql);
		}
	}
	
	public function delete_domain($type,$from_id)
	{
		$sql = "DELETE FROM ".DB_PREFIX."domain WHERE type=".$type." AND from_id=".$from_id;
		$this->db->query($sql);
	}
	
	public function get_content_type_by_colid($column_id,$expand_module='')
	{
		$cont_type = array();
		$sql = "SELECT * FROM ". DB_PREFIX ."column WHERE id=".$column_id;
		$coldata = $this->db->query_first($sql);
		
		if($coldata['support_module'])
		{
			$supp_modu = self::get_app($coldata['support_module']);
			foreach($supp_modu as $k=>$v)
			{
				if($expand_module)
				{
					$content_type = self::get_content_type($v['abundle'],$v['mbundle'],$coldata['support_content_type'],true);
				}
				else
				{
					$content_type = self::get_content_type($v['abundle'],$v['mbundle']);
				}
				$cont_type = array_merge($cont_type,$content_type);
			}
		}
		return $cont_type;
	}
	
	//获取内容的类型 ，如：文章，图片，调查
	public function get_content_type($bundle_id = '',$module_id = '',$support_content = '',$is_sup = false)
	{
		$sql = "SELECT id,content_type FROM ". DB_PREFIX ."content_field WHERE 1 AND content_type!=''";
		if($bundle_id)
		{
			$sql .= " AND bundle_id='".$bundle_id."' ";
		}
		if($module_id)
		{
			$sql .= " AND module_id='".$module_id."' ";
		}
		if($is_sup)
		{
			$support_content = $support_content?$support_content:0;
			$sql .= " AND id in(".$support_content.")";
		}
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	/**
	//获取应用模块
	public function get_module()
	{
		$sql = "SELECT a1.id as id,a1.name as name FROM ".DB_PREFIX."app a1 LEFT JOIN ".DB_PREFIX."app a2 ON a1.father=a2.id WHERE a2.father=0 ";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	//根据id获取应用模块
	public function get_app($ids)
	{
		$ret = array();
		$sql = "SELECT a1.id as id,a1.name as mname,a1.bundle as mbundle,a2.name as aname,a2.bundle as abundle FROM ".DB_PREFIX."app a1 LEFT JOIN ".DB_PREFIX."app a2 ON a1.father=a2.id WHERE a1.id in (".$ids.")";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[] = $row;
		}
		return $ret;
	}
	*/
	
	public function get_app_data($ids)
	{
		$ret = array();
		$sql = "SELECT * FROM ".DB_PREFIX."app WHERE id in (".$ids.")";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[] = $row;
		}
		return $ret;
	}

}

?>
