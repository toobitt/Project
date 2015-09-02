<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
class imgWeather extends InitFrm
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
	public function show($conditions = '', $orderby=' ORDER BY id ASC ', $limit='')
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_material WHERE 1'.$conditions.$orderby.$limit;
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['system_img'] = unserialize($row['system_img']);
			$row['user_img'] = unserialize($row['user_img']);
			$row['update_time'] = $row['update_time']?date('Y-m-d H:i:s',$row['update_time']) : '';
			$row['bg_image'] = unserialize($row['bg_image']);
			$k[$row['id']] = $row;
		}
		return $k;
	}
	public function count($condition = '')
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'weather_material WHERE 1 '.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	public function detail($id)
	{
		if (!intval($id))
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_material WHERE id ='.$id;
		$ret = $this->db->query_first($sql);
		$ret['system_img'] = unserialize($ret['system_img']);
		$ret['bg_image'] = unserialize($ret['bg_image']);
		$ret['user_img'] = unserialize($ret['user_img']);
		$ret['app_user_image'] = unserialize($ret['app_user_image']);
		$ret['app_bg_image'] = unserialize($ret['app_bg_image']);
		return $ret;
	}
	//图片插入图片服务器
	public function uploadToPicServer($file,$id)
	{
		$material = $this->material->addMaterial($file,$id); //插入图片服务器
		return $material;
	}
	public function update($data,$id)
	{
		if (!$data || !is_array($data) || !$id) 
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'weather_material SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE id = '.$id;
		$this->db->query($sql);
		$this->update_data();
		return true;
	}
	public function delete($id)
	{
		/*
		$sql = 'UPDATE '.DB_PREFIX.'weather_material SET 
				user_img = "",bg_image = "", is_update = 0 , update_time='.TIMENOW.' , user_id = '.$this->user['user_id'].' 
			    , user_name = "'.addslashes($this->user['user_name']).'" , ip ="'.$this->user['ip'].'" 
			    WHERE id IN ('.$id.')';
		$this->db->query($sql);
		*/
		$sql = 'DELETE FROM '.DB_PREFIX.'weather_material WHERE id IN ('.$id.')';
		$this->db->query($sql);
		$sql = 'DELETE FROM '.DB_PREFIX.'weather_material_buffer WHERE id IN ('.$id.')';
		$this->db->query($sql);
		$this->update_data();
		return $id;
	}
	public function check_title($title)
	{
		if (!$title)
		{
			return false;
		}
		$sql = 'SELECT id FROM '.DB_PREFIX.'weather_material WHERE title="'.$title.'"';
		$ret = $this->db->query_first($sql);
		if ($ret['id'])
		{
			return false;
		}else {
			return true;
		}
	}
	public function create($data)
	{
		$sql = 'INSERT INTO '.DB_PREFIX.'weather_material SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$this->update_data();
		return $id;
	}
	public function update_data()
	{
		$this->db->query('UPDATE '.DB_PREFIX.'weather_refresh_queue SET offset = 0, locked=1');
	}
	public function del_img($id,$type)
	{
		//1为自定义图片，2为背景图
		if ($type==1)
		{
			$sql = 'UPDATE '.DB_PREFIX.'weather_material SET user_img="" ,is_update=0 WHERE id IN ('.$id.')';
			$this->db->query($sql);
			//$this->update_data();
			return true;
		}
		if ($type==2)
		{
			$sql = 'UPDATE '.DB_PREFIX.'weather_material SET bg_image="" WHERE id IN ('.$id.')';
			$this->db->query($sql);
			//$this->update_data();
			return true;
		}
	}
	public function get_apps()
	{
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'effective_app');
		$ret = $curl->request('get_app_info.php');
		return $ret[0];
	}
}