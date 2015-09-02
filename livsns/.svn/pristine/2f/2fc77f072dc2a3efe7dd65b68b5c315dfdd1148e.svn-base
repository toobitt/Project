<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');
class station extends InitFrm
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
	
	public function show($cond)
	{
		$sql = "SELECT t1.* ,t2.name region_name,t3.name company_name,t3.station_icon FROM " . DB_PREFIX . "station t1 
				LEFT JOIN " .DB_PREFIX . "region t2 
					ON t1.region_id=t2.id 
				LEFT JOIN " . DB_PREFIX . "company t3 
					ON t1.company_id=t3.id 
				WHERE 1 " . $cond; 
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{ 	
			if($row['station_icon'])
			{
				$row['station_icon'] = $this->make_url($row['station_icon']);
			}
			
			$row['create_time'] = date("Y-m-d H:i",$row['update_time']);
			
			//可停车位
			if($row['totalnum'] && $row['totalnum']>=$row['currentnum'])
			{
				$row['park_num'] = $row['totalnum'] - $row['currentnum'];
			}
			else 
			{
				$row['park_num'] = 0;
			}
			switch ($row['state'])
			{
				case 0 :
					$row['audit'] = '待审核';
					break;
				case 1 :
					$row['audit'] = '已审核';
					break;
				case 3 :
					$row['audit'] = '已下线';
					break;
				default:
					$row['audit'] = '已打回';
					break;
			}
			$info[$row['id']] = $row;
		}
		return $info;		
	}
		

	public function detail($id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "station WHERE id = " . $id;
		$info = $this->db->query_first($sql);
		if($info['totalnum'] && $info['totalnum']>$info['currentnum'])
		{
			$info['park_num'] = $info['totalnum'] - $info['currentnum'];
		}
		else 
		{
			$info['park_num'] = 0;
		}
		
		$sql = 'SELECT id,host,dir,filepath,filename FROM '.DB_PREFIX.'material  WHERE cid = '.$id;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{				
			$info['pic'][] = $row;
		}	
		$info['now_city_data'] = $this->get_city($info['province']);
		$info['now_area_data'] = $this->get_area($info['city']);
		return $info;
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
	
	public function get_city($province_id)
	{
		$sql = "SELECT id,city FROM " . DB_PREFIX . "city WHERE province_id = '" .$province_id. "'";
		$q = $this->db->query($sql);
		$city = array();
		while ($r = $this->db->fetch_array($q))
		{
			$city[] = $r;
		}
		return $city;
	}
	
	public function get_area($city_id)
	{
		$sql = "SELECT id,area FROM " . DB_PREFIX . "area WHERE city_id = '" .$city_id. "'";
		$q = $this->db->query($sql);
		$area = array();
		while ($r = $this->db->fetch_array($q))
		{
			$area[] = $r;
		}
		return $area;
	}
	function add_material($pic)
	{
		if($pic)
		{
			//检测图片服务器
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('图片服务器未安装!');
			}
			
			if ($pic['error']>0)
			{
				$this->errorOutput('图片上传异常');
			}
			if ($pic['size']>100000000)
			{
				$this->errorOutput('只允许上传100M以下的图片!');
			}
			//获取图片服务器上传配置
			/*$PhotoConfig = $this->getPhotoConfig();
			if (!$PhotoConfig)
			{
				$this->errorOutput('获取允许上传的图片类型失败！');
			}
			
			if (!in_array($pic['type'], $PhotoConfig['type']))
			{
				$this->errorOutput('只允许上传'.$PhotoConfig['hint'].'格式的图片');
			}*/
			$pic_data['Filedata'] = $pic;
			
			$fileinfo = $this->material->addMaterial($pic_data); //插入图片服务器
		}	
		if($fileinfo)
		{
			$arr = array(
				'host'			=>	$fileinfo['host'],
				'dir'			=>	$fileinfo['dir'],
				'filepath'		=>	$fileinfo['filepath'],
				'filename'		=>	$fileinfo['filename'],
			);
			$arr =	serialize($arr);
		}
		return $arr;
	}
	public function getMaterialById($cid)
	{	
		if(!$cid)
			return false;
		$sql = "SELECT * FROM " . DB_PREFIX . "material WHERE cid=" . $cid . " AND isdel=1"; //1表示没删除
		$q = $this->db->query($sql);
		$info = array();
		while(false != ($ret = $this->db->fetch_array($q)))
		{
			if(empty($ret))
			{
				continue;
			}
			switch($ret['mark'])
			{
				case 'img':
					$ret['pic'] = unserialize($ret['pic']);
					$info[$ret['material_id']] = $ret;
					$info[$ret['material_id']]['url'] = hg_fetchimgurl($ret['pic'],100,75);
					break;
				case 'doc':
					$info[$ret['material_id']] = $ret;
					break;
				default:
					break;
			}
		}
		return $info;
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
	
	//获取上传图片的类型
	public function getPhotoConfig()
	{
		$ret = $this->material->get_allow_type();
		if (!$ret) {
			return false;
		}
		$photoConfig = array();
		if (is_array($ret['img']) && !empty($ret['img']))
		{
			foreach ($ret['img'] as $type)
			{
				$photoConfig['type'][] =  'image/'.$type;
			}
			$photoConfig['hint'] = implode(',', $ret['img']);
		}
		return $photoConfig;	
	}
	
	//上传图片服务器
	public function uploadToPicServer($file)
	{
		$material = $this->material->addMaterial($file); //插入图片服务器
		return $material;
	}
	
	//插入素材表
	public function insert_material($data)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'material SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	//更新索引图
	public function update_indexpic($mid,$cid)
	{
		$sql = 'UPDATE '.DB_PREFIX.'station SET material_id = '.$mid.' WHERE id = '.$cid;
		$this->db->query($sql);
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'material WHERE id = '.$mid;
		$pic = $this->db->query_first($sql);
		if ($pic['host'] && $pic['dir'] && $pic['file_path'] && $pic['file_name'])
		{
			$url = array(
				'host'		=> $pic['host'],
				'dir'		=> $pic['dir'],
				'file_path' => $pic['file_path'],
				'file_name' => $pic['file_name'],
				'cid'		=> $cid
			);	
		}
		return $url;
	}
}
?>
