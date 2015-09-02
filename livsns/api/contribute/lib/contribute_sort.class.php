<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');
class contribute_sort extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show($condition, $orderby=' ORDER BY id DESC', $offset, $count)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = "SELECT * FROM ".DB_PREFIX."sort  WHERE 1 ".$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$k =array();
		while(!false == ($row = $this->db->fetch_array($query)))
		{
			$row['create_time'] = date('Y-m-d h:i:s',$row['create_time']);
			$row['update_time'] = date('Y-m-d h:i:s',$row['update_time']);
			if (!empty($row['input_sort']))
			{
				$row['sortname'] = $this->get_sortName($row['input_sort']);
			}		
			$row['input_sort'] = explode(',', $row['input_sort']);
			switch ($row['is_auto'])
			{
				case  1: $row['auto'] = '开启';break;
				default: $row['auto'] = '';
			}
			$k[] = $row;
		}
		return $k;	
	}
	public function fastInput_sort()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput_sort';
		$query = $this->db->query($sql);
		$k = array();
		while (!false == ( $row = $this->db->fetch_array($query)))
		{
			$k[$row['id']] = $row['name'];
		}
		return $k;
	}
	public function get_sortName($ids)
	{
		$sql = 'SELECT id,name FROM '.DB_PREFIX.'fastInput_sort WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$k = array();
		while (!false == ($row = $this->db->fetch_array($query)))
		{
			$k[$row['id']] = $row['name'];
		} 
		return $k;
	}
	public function detail($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."sort  WHERE id = ".$id;
		$res = $this->db->query_first($sql);
		
		$res['create_time'] = date('Y-m-d h:i:s',$res['create_time']);
		$res['update_time'] = date('Y-m-d h:i:s',$res['update_time']);
		if ($res['input_sort'])
		{
			$res['sortname'] = $this->get_sortName($res['input_sort']);
			$res['input_sort'] = explode(',', $res['input_sort']);
		}
		switch ($res['is_auto'])
		{
			case  1: $res['auto'] = '开启';break;
			default: $res['auto'] = '';
		}
		if ($res['userinfo'])
		{
			$res['userinfo'] = unserialize($res['userinfo']);
		}
		if ($res['image'])
		{
			$res['image'] = unserialize($res['image']);
		}
		$configs = $this->get_configs($id);
		$res['configs'] = '';
		if ($configs)
		{
			$res['configs'] = $configs;
		}
		return $res;	
	}
	//图片插入图片服务器
	public function uploadToPicServer($file,$id)
	{
		$material = $this->material->addMaterial($file,$id); //插入图片服务器
		return $material;
	}
	public function delSortImage($id)
	{
		if ($id)
		{
			$sql = 'UPDATE '.DB_PREFIX.'sort SET image="" WHERE id = '.$id;
			$this->db->query($sql);
			
		}
		return true;
	}
	//配置入库
	public function update_configs($data)
	{
		if (is_array($data) && !empty($data))
		{
			$sql = 'REPLACE INTO '.DB_PREFIX.'forward SET ';
			foreach ($data as $key=>$val)
			{
				$sql.= $key.'="'.$val.'",';
			}
			$sql = rtrim($sql,',');
			$this->db->query($sql);
			
		}
	}
	//根据分类id取配置
	public function get_configs($id)
	{
		if (intval($id))
		{
			$k = array();
			$sql = 'SELECT * FROM '.DB_PREFIX.'forward WHERE sort_id IN ('.$id.')';
			$query=$this->db->query($sql);
			while ($row = $this->db->fetch_array($query))
			{
				$row['match_rule'] = unserialize($row['match_rule']);
				$k[] = $row;
			}
			if (!empty($k))
			{
				return $k;
			}else {
				return false;
			}
		}else {
			return false;
		}
	}
	//删除配置
	public function del_configs($ids)
	{
		if ($ids)
		{
			$sql ='DELETE FROM '.DB_PREFIX.'forward WHERE id IN ('.$ids.')';
			$this->db->query($sql);
			return true;
		}else {
			return false;
		}
	}
	//根据分类id获取配置id
	public function getIdBySortid($id)
	{
		$sql = 'SELECT id FROM '.DB_PREFIX.'forward WHERE sort_id ='.$id;		
		$query = $this->db->query($sql);
		$k = array();
		while ($row=$this->db->fetch_array($query))
		{
			$k[]=$row['id'];
		}
		
		return $k;
	}
	//查询某个分类下是否存在数据
	public function checkDataBysort($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'SELECT id FROM '.DB_PREFIX.'content WHERE sort_id IN ('.$ids.')';
		$ret = $this->db->query_first($sql);
		if ($ret)
		{
			return true;
		}
		else
		{
			return false;
		} 
		
	}
}