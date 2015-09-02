<?php
//接口API地址
define('APIURI', 'http://api.uihoo.com/astro/astro.http.php');
//令牌
define('USERID', '8f8eb34bab4c2c7a9c0df5e3d63396dc38d98441');
require('./global.php');
require(ROOT_PATH . 'lib/class/curl.class.php');
require_once CUR_CONF_PATH.'lib/constellation.class.php';
require_once CUR_CONF_PATH.'lib/astro.class.php';
define('MOD_UNIQUEID', 'constellation');
class constellationUpdateApi extends adminUpdateBase
{

	protected $api_uri;
	protected $dataType;
	function __construct()
	{

		$this->api_uri = APIURI;
		$this->dataType = 'json';

		parent::__construct();
		$this->constellation = new constellation();
		$this->astro = new astro();
	}
	public function __destruct()
	{
		parent::__destruct();
	}


	public function create(){}
	public function update()
	{
		//更新后台运势
		//$id 数据库数据所在id，$astroid，curl运势数据需要.
		$this->verify_setting_prms(array('_action'=>'manage'));
		if (!$this->input['id'])
		{
			$this->errorOutput(NO_ASTRO_ID);
		}
		elseif (!$this->input['astroid'])
		{
			$this->errorOutput(NO_ASTRO_ID);
		}
		elseif (!$this->input['fun'])
		{
			$this->errorOutput('没传运势类型');
		}
		$astroid = intval($this->input['astroid']-1);
		$id = intval($this->input['id']);
		$field = $this->settings['astro'];
		$fun=trim($this->input['fun']);
		$data[$fun]=$this->astro->show($fun,$astroid);//如果说数据库无数据，则curl请求查询。
		$starttimes=strtotime(trim($data[$fun]['starttime']));
		$endtimes=strtotime(trim($data[$fun]['endtime']));
		$update_time=TIMENOW;
		unset($data[$fun]['starttime']);
		unset($data[$fun]['endtime']);
		$astrojson = $data[$fun][$field[$astroid]];
		$astrojson=addslashes(json_encode($astrojson));

		$sql = 'UPDATE '.DB_PREFIX.'astro_app_'.$fun.' SET astrojson = '."'$astrojson'".',starttime='.$starttimes.',endtime='.$endtimes.',update_time='.$update_time.' WHERE id IN ('.$id.')';
		//$this->errorOutput($sql);
		$this->db->query($sql);
		$sql ='UPDATE '.DB_PREFIX.'astro_app_fortuneinfo SET fortuneinfostart = '.strtotime(trim($starttime)).',fortuneinfoend ='.strtotime(trim($endtime)).' WHERE 1 AND astrofun = '."'$fun'";
		$this->db->query($sql);
		$data[$fun]['id']="$id";//数组格式转换
		$data[$fun]['astrojson']=$data[$fun][$field[$astroid]];
		unset($data[$fun][$field[$astroid]]);
		$astrofun=$this->constellation->fortuneinfo($fun);
		$data[$fun]['astrofuncn']=$astrofun['astrofuncn'];
		$data[$fun]['astrofunimg']=$astrofun['astrofunimg'];
		$data[$fun]['fortuneinfostart']=$astrofun['fortuneinfostart'];
		$data[$fun]['fortuneinfoend']=$astrofun['fortuneinfoend'];
		$data[$fun]['fun'] = $fun;
		$data['astroid'] = intval($astroid+1);
		$data['name'] = $field[$astroid];
			
		$this->addItem($data);
		$this->output();
	}
	public function sort(){}
	public function publish(){}
	public function audit()
	{

	}
	/**
	 * 删除
	 */
	public function delete()
	{
		$this->verify_setting_prms(array('_action'=>'manage'));
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}



		$this->constellation->delete($this->input['id'],$this->input['fun']);
		$this->addItem(array('id' => urldecode($this->input['id'])));


		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}


}
/*
 *  程序入口
 */
$o = new constellationUpdateApi();
$action = $_INPUT['a'];
$action = $action && method_exists($o, $action) ? $action : 'unknow';
$o->$action();
