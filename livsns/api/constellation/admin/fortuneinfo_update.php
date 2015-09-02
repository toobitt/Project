<?php
require('./global.php');
require_once CUR_CONF_PATH.'lib/astro.class.php';
require_once CUR_CONF_PATH.'lib/constellation.class.php';
define('MOD_UNIQUEID', 'fortuneinfoApi');
class fortuneinfoApiupdate extends adminUpdateBase
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

	public function update()
	{
		$data = array(
		'id'	=> intval($this->input['id']),
		'name' => trim($this->input['name']),
		);
		if(!$data['id'])
		{
			$this->errorOutput("ID不能为空");
		}
		if(!$data['name'])
		{
			$this->errorOutput("运势名不能为空");
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'astro_app_fortuneinfo WHERE id = '.$data['id'];			
	    $res = $this->db->query_first($sql);
		$astrofunimg = unserialize($res['astrofunimg'])?unserialize($res['astrofunimg']):array();
		
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
		else $logo=$astrofunimg;
     	
		$where = ' WHERE 1 ';
		$where .= ' AND  id = '.$data['id'];
		$sql = 'UPDATE '.DB_PREFIX.'astro_app_fortuneinfo' . ' SET astrofuncn = "'.$data['name'].'", astrofunimg ="'.addslashes(serialize($logo)).'"'.$where;
		//$this->errorOutput($sql);
		$this->db->query($sql);
		$this->constellation->updatetime_orerid($data['id'],'fortuneinfo');
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
$o = new fortuneinfoApiupdate();
$action = $_INPUT['a'];
$action = $action && method_exists($o, $action) ? $action : 'unknow';
$o->$action();