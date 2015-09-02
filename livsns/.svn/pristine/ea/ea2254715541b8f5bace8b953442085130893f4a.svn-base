<?php
require('./global.php');
require_once CUR_CONF_PATH.'lib/astro.class.php';
require_once CUR_CONF_PATH.'lib/constellation.class.php';
define('MOD_UNIQUEID', 'astroinfoApi');
class astroinfoApiupdate extends adminUpdateBase
{

	function __construct()
	{

		parent::__construct();
		$this->astro = new astro();
		$this->constellation = new constellation();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
	}
/*
 * 星座信息更新函数
 */
	public function update()
	{
	$starttime= explode('-', $this->input['astrostart']);
		$astrostart	=	mktime(0, 0, 0, $starttime[0], $starttime[1], 0);
		unset($starttime);
		$endtime	= 	explode('-', $this->input['astroend']);
		$astroend	=	mktime(0, 0, 0, $endtime[0], $endtime[1], 0);
		unset($endtime);
		$data = array(
		'id'	=> intval($this->input['id']),
		'name' => trim($this->input['name']),
		'astrointroduction' => trim($this->input['astrointroduction']),
		'astrostart'=>trim($astrostart),
		'astroend' => trim($astroend),
		);
		if(!$data['id'])
		{
			$this->errorOutput("ID不能为空");
		}
		if(!$data['name'])
		{
			$this->errorOutput("星座名不能为空");
		}
		
		$sql = 'SELECT astroimg FROM '.DB_PREFIX.'astro_app_info WHERE id = '.$data['id'];			
	    $res = $this->db->query_first($sql);
		$astroimg = unserialize($res['astroimg'])?unserialize($res['astroimg']):array();
		$logo['Filedata'] = $_FILES['logo'];
		if($logo['Filedata'])
		{
			include_once ROOT_PATH  . 'lib/class/material.class.php';
			$material = new material();
			$re = $material->addMaterial($logo);
			$logo  = array();
			$logo = array(
			'host' => $re['host'],
			'dir'=>$re['dir'],
			'filepath'=>$re['filepath'],
			'filename'=>$re['filename'],
			);
		}
		else $logo=$astroimg;
     	
		$where = ' WHERE 1 ';
		$where .= ' AND  id = '.$data['id'];
		$sql = 'UPDATE '.DB_PREFIX.'astro_app_info' . ' SET astrocn = "'.$data['name'].'",astrointroduction = "'.$data['astrointroduction'].'", astroimg ="'.addslashes(serialize($logo)).'",astrostart = "'.$data['astrostart'].'",astroend = "'.$data['astroend'].'"'.$where;
		//$this->errorOutput($sql);
		$this->db->query($sql);
		$this->constellation->updatetime_orerid($data['id'],'info');
		
		$data['logo'] =$logo;
		$this->addItem($data);
		$this->output();

	}
	public function sort(){}
	public function publish(){}

	public function audit()
	{

	}

	public function delete()
	{
	}



	/*
	 *
	 * 方法名不存在时调用的方法
	 */
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}


}
/*
 *  程序入口
 */
$o = new astroinfoApiupdate();
$action = $_INPUT['a'];
$action = $action && method_exists($o, $action) ? $action : 'unknow';
$o->$action();