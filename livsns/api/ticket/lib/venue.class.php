<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');
class Ticket extends InitFrm
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
	
	
	public function create($data,$table)
	{
		if(!$table)
		{
			return false;
		}
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
	
	public function update($data, $table, $where = '') 
	{
		if($table == '' or $where == '') 
		{
			return false;
		}
		$where = ' WHERE '.$where;
		$field = '';
		if(is_string($data) && $data != '') 
		{
			$field = $data;
		} 
		elseif (is_array($data) && count($data) > 0) 
		{
			$fields = array();
			foreach($data as $k=>$v) 
			{
				$fields[] = $k."='".$v . "'";
			}
			$field = implode(',', $fields);
		} 
		else 
		{
			return false;
		}
		$sql = 'UPDATE '. DB_PREFIX . $table . ' SET '.$field.$where;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	public function delete($table, $where) 
	{
		if ($table == '' || $where == '') 
		{
			return false;
		}
		$where = ' WHERE '.$where;
		$sql = 'DELETE FROM ' . DB_PREFIX . $table . $where;
		return $this->db->query($sql);
	}
	
	
	public function make_url($info,$size = '40x30/')
	{
		if($info)
		{
			$url = '';
			$url = unserialize($info);
			$url = hg_material_link($url['host'], $url['dir'], $url['filepath'], $url['filename'],$size);
		}
		return $url;
	}
	
	//上传图片服务器
	public function uploadToPicServer($file)
	{
		$material = $this->material->addMaterial($file); //插入图片服务器
		return $material;
	}
	
}
?>
