<?php

class catalog extends InitFrm
{
	public function __construct()
	{
		parent::__construct();

	}

	public function __destruct()
	{
		parent::__destruct();
	}


	public function all_catalog_field($rows)//获取全部编目标识
	{
		foreach ($rows as $sortk=>$sortv)
		{
			if(!empty($rows[$sortk]['html']) && is_array($rows[$sortk]['html']))
			{
				foreach ($rows[$sortk]['html'] as $sortvk=>$sortvv)
				{
					$all_catalog_field[$sortvk] = array('zh_name'=>$sortvv['zh_name'],'type'=>$sortvv['type'],'bak'=>$sortvv['bak']);
					unset($sortvv);
				}
			}
			unset($sortv);
			unset($sortk);
		}

		return $all_catalog_field;
	}
	//获取已开启当前应用的编目
	public function app_field($app_uniqueid)
	{
		$condition = ' AND am.app_uniqueid like "%'.$app_uniqueid.'%"';
		$sql='SELECT f.zh_name,f.catalog_field,s.type,f.bak,f.required,f.batch FROM '.DB_PREFIX.'field as f left join '.DB_PREFIX.'app_map as am on am.field_id = f.id
		 left join '.DB_PREFIX.'style as s on s.id = f.form_style';
		$sql .=' WHERE 1 AND f.switch = 1'.$condition;
		$q = $this->db->query($sql);
		while($data = $this->db->fetch_array($q))
		{
			if(strcasecmp($data['catalog_field'], 'catalog')==0)
			{
				$data['catalog_field']=CATALOG_PREFIX.$data['catalog_field'];
			}
			if (stripos($data['catalog_field'], 'catalog') === false)
			{
				$data['catalog_field']=CATALOG_PREFIX.$data['catalog_field'];
			}
			$datas[$data['catalog_field']]=array('zh_name'=>$data['zh_name'],'field'=>$data['catalog_field'],'type'=>$data['type'],'bak'=>$data['bak'],'required'=>$data['required'],'batch'=>$data['batch']);
		}
		return $datas;
	}
    //获取某用户的编目
	public function getUser_field($identifier,$user_id)
	{
		$condition = ' AND f.identifier='.$identifier.' AND f.user_id='.$user_id.'';
		$sql='SELECT f.zh_name,f.catalog_field,s.type,f.bak,f.required,f.batch FROM '.DB_PREFIX.'field as f left join '.DB_PREFIX.'app_map as am on am.field_id = f.id
		 left join '.DB_PREFIX.'style as s on s.id = f.form_style';
		$sql .=' WHERE 1 AND f.switch = 1'.$condition;
		$q = $this->db->query($sql);
		while($data = $this->db->fetch_array($q))
		{
			if(strcasecmp($data['catalog_field'], 'catalog')==0)
			{
				$data['catalog_field']=CATALOG_PREFIX.$data['catalog_field'];
			}
			if (stripos($data['catalog_field'], 'catalog') === false)
			{
				$data['catalog_field']=CATALOG_PREFIX.$data['catalog_field'];
			}
			$datas[$data['catalog_field']]=array('zh_name'=>$data['zh_name'],'field'=>$data['catalog_field'],'type'=>$data['type'],'bak'=>$data['bak'],'required'=>$data['required'],'batch'=>$data['batch']);
		}
		return $datas;
	}

	//获取某个编目属性
	public function field_property($field)
	{
		if(empty($field))
		{
			return -1;
		}
		if(stripos($field, ',')!==false)
		{
			$condition = ' AND f.catalog_field IN ('.$field.')';
		}
		else
		{
			$condition = ' AND f.catalog_field = '.$field;
		}
		$sql='SELECT f.zh_name,f.catalog_field,s.type,f.bak,f.required,f.batch FROM '.DB_PREFIX.'field as f
		 left join '.DB_PREFIX.'style as s on s.id = f.form_style';
		$sql .=' WHERE 1 AND f.switch = 1'.$condition;
		$q = $this->db->query($sql);
		while($data = $this->db->fetch_array($q))
		{
			$datas[$data['catalog_field']]=array('zh_name'=>$data['zh_name'],'field'=>$data['catalog_field'],'type'=>$data['type'],'bak'=>$data['bak'],'required'=>$data['required'],'batch'=>$data['batch']);
		}
		return $datas;
	}


	public function all_catalog_sort($rows)//获取全部编目分类
	{
		foreach ($rows as $sortk=>$sortv)
		{
			$return[]=$sortk;
			unset($sortv);
		}

		return $return;

	}

	public function get_condition($app_uniqueid,$mod_uniqueid,$content_id='')
	{
		$where="";

		if (!empty($app_uniqueid))
		{
			$where.=" AND app_uniqueid = '".$app_uniqueid.'\'';
		}
		if (!empty($mod_uniqueid))
		{
			$where.=" AND mod_uniqueid = '".$mod_uniqueid.'\'';
		}
		if(!empty($content_id))//如果传内容id则查询该内容id.
		{
			$where.=" AND content_id = ".$content_id;
		}
		return $where;
	}
	public function error($app_uniqueid,$mod_uniqueid,$content_id='',$flag=TRUE)//当flag为false不检测content_id
	{
		if(empty($app_uniqueid))
		{
			return APP_UNIQUEID_ERROR;
		}
		if(empty($mod_uniqueid))
		{
			return MOD_UNIQUEID_ERROR;
		}
		if(empty($content_id)&&$flag)
		{
			return CONTENT_ID_ERROR;
		}
	}
	public function catalog_unset($rows,$arr_catalog_field)
	{
		foreach ($rows as $sortk=>$sortv)
		{
			if(!empty($sortv['html']) && is_array($sortv['html']))
			{
				if(is_array($arr_catalog_field))
				{
					foreach ($arr_catalog_field as $catalog_field_value)
					{
						unset($rows[$sortk]['html'][$catalog_field_value]);//unset掉存在数据.
					}
				}
			}
			if(empty($rows[$sortk]['html']) && is_array($rows[$sortk]['html']))
			{
				unset($rows[$sortk]);//unset掉空分类
			}
		}
		return $rows;
	}

	//根据素材id删除图片.
	public function deleteMaterial_id($ids)
	{
		$sql = 'DELETE FROM ' . DB_PREFIX . 'materials WHERE id IN (' . $ids . ')';
		$this->db->query($sql);
		return $ids;
	}
	
	/**
	 * 
	 * 根据素材id取素材
	 */
	public function get_Material($ids)
	{
		if(is_array($ids)||empty($ids))
		{
			return array();
		}
		$sql = 'SELECT id,host,dir,filepath,filename FROM '.DB_PREFIX.'materials WHERE id IN (' . $ids . ')';
		$query = $this->db->query($sql);
		$data = array();
		while($row = $this->db->fetch_array($query))
		{
			$data[] = $row;
		}
		return is_array($data)?$data:array();
	}

	//根据删除编目判断是否需要删除素材图片
	public function deleteMaterial_field($app_uniqueid,$mod_uniqueid,$content_id='',$catalogdel=array(),$prefix=false)
	{
		if(is_array($catalogdel)&&$prefix)
		{
			foreach ($catalogdel as $val)
			{
				$tmp[]=	catalog_prefix($val,'del');
			}
			$catalogdel=$tmp;
			unset($tmp);
		}
		$where=' AND c.app_uniqueid=\''.$app_uniqueid.'\' AND c.mod_uniqueid=\''.$mod_uniqueid.'\'';
		if($content_id)
		{
				
			if(stripos($content_id, ',')!==false)
			{
				$where .= ' AND c.content_id IN ('.$content_id.')';
			}
			else
			{
				$where .= ' AND c.content_id = '.$content_id;
			}
		}
		if($catalogdel&&is_array($catalogdel))
		{
			$catalogdels = "'".implode("','", $catalogdel )."'";
		}
		elseif($catalogdel)
		{
			$catalogdels = "'$catalogdel'";
		}
		$field_property=$this->field_property($catalogdels);
		$img_field=array();//用于储存将要删除的img类型的标识
		if($field_property&&is_array($field_property))
		{
			foreach ($field_property as $key=>$val)
			{
				if($val['type']=='img'&&in_array($key, $catalogdel))
				{
					$img_field[]=$key;//取出属于视频或者图像的编目key,用于删除素材表使用;
				}
			}
			if($img_field)
			{
				$img_field="'".implode("','", $img_field )."'";
				
				if (stripos($img_field, ',')!==false)
				{
					$where.= ' AND c.catalog_field IN ('.$img_field.')';
				}
				else 
				{
					$where .=' AND c.catalog_field ='.$img_field;
				}
				$sql='SELECT id FROM '.DB_PREFIX.'content AS c WHERE 1'.$where;
				$sql = 'DELETE FROM ' . DB_PREFIX . 'materials WHERE cid IN ('.$sql.')';
			}
			else return false;
			
			$this->db->query($sql);
		}
		else return $field_property;

		return true;
	}
	//获取已使用编目标识
	function get_catalog_field($where,$prefix=true)
	{
		$sql = "SELECT catalog_field FROM " . DB_PREFIX . "content WHERE 1 " . $where;
		$q = $this->db->query($sql);//当前内容id是否已存在与内容表,并取出编目标识
		while($data = $this->db->fetch_array($q))
		{
			if($prefix)
			{
				$data['catalog_field']=catalog_prefix($data['catalog_field']);
			}
				
			$arr_catalog_field[] =  $data['catalog_field']; //编目标识
		}
		return $arr_catalog_field;
	}
}

?>