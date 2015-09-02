<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');
class ClassLBSSort extends InitFrm
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
	public function show($condition, $orderby = ' ORDER BY order_id ASC ',$field='*',$limit='')
	{
		$sql = "SELECT {$field} FROM ".DB_PREFIX."sort  WHERE 1 ".$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$k = array();
		while(!false == ($row = $this->db->fetch_array($query)))
		{
			if($row['create_time'])
			{
				$row['create_time'] = date('Y-m-d h:i:s',$row['create_time']);
			}
			if($row['update_time'])
			{
				$row['update_time'] = date('Y-m-d h:i:s',$row['update_time']);
			}
			if($row['image'])
			{
				$row['image'] = $row['image'] ? unserialize($row['image']) : '';
			}
			$k[$row['id']] = $row;
		}
		return $k;	
	}
	public function detail($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."sort  WHERE id = ".$id;
		$res = $this->db->query_first($sql);
		
		$res['create_time'] = date('Y-m-d h:i:s',$res['create_time']);
		$res['update_time'] = date('Y-m-d h:i:s',$res['update_time']);
		if ($res['image'])
		{
			$res['image'] = unserialize($res['image']);
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
	/**
	 * 删除
	 * @paramString $table
	 * @param Array $data
	 */
	function delete($table, $data)
	{
		if (empty($table) || !is_array($data)) return false;
		$sql = 'DELETE FROM ' . DB_PREFIX . $table . ' WHERE 1';
		foreach ($data as $k => $v)
		{
			if (is_int($v) || is_float($v))
			{
				$sql .= ' AND ' . $k . ' = ' . $v;
			}
			elseif (is_string($v))
			{
				$sql .= ' AND ' . $k . ' IN (' . $v . ')';
			}
		}
		return $this->db->query($sql);
	}
}