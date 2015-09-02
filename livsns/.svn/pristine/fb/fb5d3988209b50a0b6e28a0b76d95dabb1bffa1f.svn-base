<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');
class constellation extends InitFrm
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

	public function show($condition,$fun,$astro,$orderby)//前台接口
	{
		$table=DB_PREFIX.'astro_app_'.$fun;
		$sql = 'SELECT astrojson FROM '.$table.' WHERE 1 '.$condition.$orderby ;

		//$this->errorOutput($sql);
		$data = $this->db->query_first($sql);
		$data = json_decode($data['astrojson'],true);
		return $data;

	}

	public function showadmin($condition,$fun,$astro,$orderby)//后台接口
	{
		$table=DB_PREFIX.'astro_app_'.$fun;
		$sql = 'SELECT id,astrojson FROM '.$table.' WHERE 1 '.$condition.$orderby ;
		//$this->errorOutput($sql);
		$data = $this->db->query_first($sql);
		return $data;

	}
	public  function fortuneinfo($fun)//运势信息查询
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'astro_app_fortuneinfo WHERE astrofun = '.'\''.$fun.'\'';
		$res = $this->db->query_first($sql);
		$astrofunimg = unserialize($res['astrofunimg'])?unserialize($res['astrofunimg']):array();
		$astrofun['astrofuncn']=$res['astrofuncn'];
		$astrofun['astrofunimg'] = hg_fetchimgurl($astrofunimg);
		$astrofun['fortuneinfostart']=date("Y年m月d日",$res['fortuneinfostart']);
		$astrofun['fortuneinfoend']=date("Y年m月d日",$res['fortuneinfoend']);
			
		return $astrofun;
	}

	function date2astro($data = '') //当前月星座判断
	{
		if(!$data)
		{
			$data = time();
		}
		$month 	= date('n');
		$day 	= date('j');
		// 检查参数有效性
		if ($month < 1 || $month > 12 || $day < 1 || $day > 31)
		{
			return (false);
		}
		// 星座名称以及开始日期
		$signs = array(
		array( "20" => "aquarius"),
		array( "19" => "pisces"),
		array( "21" => "aries"),
		array( "20" => "taurus"),
		array( "21" => "gemini"),
		array( "22" => "cancer"),
		array( "23" => "leo"),
		array( "23" => "virgo"),
		array( "23" => "libra"),
		array( "24" => "scorpio"),
		array( "22" => "sagittariu"),
		array( "22" => "capricorn")
		);
		list($sign_start, $sign_name) = each($signs[(int)$month-1]);
		if ($day < $sign_start)
		list($sign_start, $sign_name) = each($signs[($month -2 < 0) ? $month = 11: $month -= 2]);
		if(!$sign_name)
		{
			return -1;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX . 'astro_app_info where astroen = "'.$sign_name.'"';
		$result = $this->db->query_first($sql);
		return $result['astroid'];

	}



	public function insertastro($fun,$astroen,$data,$starttime,$endtime)//插入数据
	{   $funs=$fun;
	//foreach ($data as $astro =>$astrodata){
	$data=addslashes(json_encode($data));
	//}
	$starttimes=strtotime(trim($starttime));
	$endtimes=strtotime(trim($endtime));

	$sql ='INSERT INTO '.DB_PREFIX.'astro_app_'.$funs.'(astroen,astrojson,astrofun,starttime,endtime)
			VALUES (\''.$astroen.'\',\''.$data.'\',\''.$funs.'\','.$starttimes.','.$endtimes.')';

	$this->db->query($sql);
	$ids = $this->db->insert_id();
	return $ids;

	}
	public function updatetime_orerid($ids,$funs) // 排序id和更新时间
	{
		$update_time=TIMENOW;
		$where = ' WHERE 1 ';
		$where .= ' AND  id = '.$ids;
		$sql = 'UPDATE '.DB_PREFIX.'astro_app_'.$funs. ' SET order_id = '.$ids.',update_time = '.$update_time.$where;
		//$this->errorOutput($sql);
		$this->db->query($sql);
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


	public function delete($id,$fun)//删除运势
	{

		$sql = 'DELETE FROM '.DB_PREFIX.'astro_app_'.$fun.' WHERE id IN ('.$id.')';
		$this->db->query($sql);
	}
}