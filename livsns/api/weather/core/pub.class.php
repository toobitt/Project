<?php
/**
 * 此类用于存储外部接口共用方法
 */
require_once(ROOT_PATH . 'lib/class/material.class.php');
class common_Weather extends InitFrm
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
	/**
	 * 
	 * 公共入库方法 ...
	 * @param array $data 数据
	 * @param string $dbName  数据库名
	 */
	public function storedIntoDB($data,$dbName,$flag=0)
	{		
		if (!$data || !is_array($data) || !$dbName)
		{
			return false;
		}
		$sql = 'REPLACE INTO '.DB_PREFIX.$dbName.' SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		if ($flag)
		{
			return $this->db->insert_id();
		}
		return true;
	}
	/**
	 * 
	 * 公共取图片的方法，有则返回，没有采集入库 ...
	 * @param int $id  图片id
	 * @param string $title 图片名称
	 */
	public function get_system_material_id($id,$title,$sourceId,$userInfo)
	{
		$materialId = '';
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_material_buffer WHERE img_id = '.$id.' AND source_id ='.$sourceId ;
		$ret = $this->db->query_first($sql);
		if ($ret && $ret['id'])
		{
			$materialId = $ret['id'];
			
		}else {
			$sql = 'SELECT inner_func FROM '.DB_PREFIX.'weather_source WHERE id ='.$sourceId;
			$ret = $this->db->query_first($sql);
			if ($ret['inner_func'])
			{
				$func = $ret['inner_func'].'_img';
				$materialId = $this->$func($id,$title,$sourceId,$userInfo);
			}else {
				return false;
			}
		}	
		return $materialId;
	}
	/**
	 * 
	 * 抓取天气网上的图片 ...
	 * @param int		$id   		天气网的图片id
	 * @param string 	$title  	图片的标题
	 * @param int 		$sourceId	天起源id
	 * @param array	 	$userInfo	用户信息数据，$this->user
	 */
	private function cn_com_weather_img($id,$title,$sourceId,$userInfo)
	{
		if (!isset($id) || !$title || !$sourceId)
		{
			return false;
		}
		//创建系统图片信息,创建之前检查是否被其他天起源处创建
		$mid = '';
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_material WHERE title="'.$title.'"';
		$ret = $this->db->query_first($sql);
		if ($ret['id'] && $ret['system_img'])
		{
			$mid = $ret['id'];
		}else {
			$url = 'http://m.weather.com.cn/img/a'.$id.'.gif';
			//尝试5次
			for ($i=0;$i<5;$i++)
			{
				$material = $this->material->localMaterial($url);			
				if (!empty($material))
				{
					break;
				}
			}
			$material = $material[0];	
			if ($material) 
			{
				if (!$ret['id'])
				{
					$sql = 'INSERT INTO '.DB_PREFIX.'weather_material SET title = "'.$title.'",img_id=' . $id;
					$this->db->query($sql);
					$mid = $this->db->insert_id();
				}else {
					$mid = $ret['id'];
				}
				$photo = array(
						'host'=>$material['host'],
						'dir'=>$material['dir'],
						'filepath'=>$material['filepath'],
						'filename'=>$material['filename'],
					);
				$source_img = serialize($photo);
				//存入数据库,
				$data = array(
						'id'=>$mid,
						'source_id'=>$sourceId,
						'img_id'=>$id,
						'img_title'=>$title,
						'source_img'=>addslashes($source_img),
						'create_time'=>TIMENOW,
						'update_time'=>TIMENOW,
						'user_id'=>$userInfo['user_id'],
						'user_name'=>$userInfo['user_name'],
						'ip'=>$userInfo['ip'],			
					);
				$res = $this->storedIntoDB($data, 'weather_material_buffer');
				//将天气网的图片更新进系统内置图片
				if ($mid)
				{
					$sql = 'UPDATE '.DB_PREFIX.'weather_material SET 
							system_img = "'.addslashes($source_img).'"
							WHERE id ='.$mid;
					$this->db->query($sql);
				}
			}	
		}
		
		
		return $mid;
	}
	
}