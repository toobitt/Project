<?php
require('global.php');
define('MOD_UNIQUEID','memmodule');//模块标识
class memmoduleApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/mcache.class.php');
		$this->obj = new mcache();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$uniqueid = '';
		$appname = $record = array();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):1000;
		include_once(ROOT_PATH.'lib/class/auth.class.php');
		$auth = new auth();
		$app = $auth->get_app('','','',0,1000,array('use_textsearch'=>1));
		
		if($app && is_array($app))
		{
			foreach($app as $k=>$v)
			{
				$appid[] = $v['bundle'];
				$module = $auth->get_module('','',implode(',',$appid),'',0,1000);
			}
		}
		if(is_array($module))
		{
			foreach($module as $k=>$v)
			{
				$module_idarr[] = $v['mod_uniqueid'];
			}
			$all_relation = $this->obj->get_relation_by_m(implode("','",$module_idarr));
//			print_r($all_relation);exit;
			foreach($module as $k=>$v)
			{
				$server_count[$v['app_uniqueid']][$v['mod_uniqueid']] = count($all_relation[$v['app_uniqueid']][$v['mod_uniqueid']]);
			}
		}
		
		$result['app'] = $app;
		$result['module'] = $module;
		$result['server_count'] = $server_count;
		$this->addItem($result);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."memcache WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

	private function get_condition()
	{
		$condition = '';
		return $condition;	
	}
	
	public function detail()
	{
		$app = $this->input['app'];
		$module = $this->input['module'];
		
		if(!$app || !$module)
		{
			$this->errorOutput('NO_APP_MODULE');
		}
		
		//查出这个应用模块使用的服务器记录
		$relation = $this->obj->get_relation_by_am($app,$module);
		
		//获取所有服务器信息
		$server = $this->obj->get_memcache(0,10000,'');
		
		$result['relation'] = $relation['relation'];
		$result['app'] = $app;
		$result['module'] = $module;
		$result['server'] = $server;
//		print_r($result);exit;
		$this->addItem($result);
		$this->output();
	}
	
	public function index(){}
	
}

$out = new memmoduleApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>


			