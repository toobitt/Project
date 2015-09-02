<?php
require('global.php');
define('MOD_UNIQUEID','textsearch');//模块标识
class textsearchApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/textsearch.class.php');
		$this->obj = new textsearch();
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
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
		$result = $this->obj->get_db($offset,$count,$this->get_condition);
                foreach($result as $k=>$v)
                {
                    $conf = realpath(CUR_CONF_PATH . 'data/' . MOD_UNIQUEID.'_'.MOD_UNIQUEID . '.ini');
                    if ($conf)
                    {
			try
			{
				include_once (CUR_CONF_PATH . 'lib/xunsearch/XS.php');
				$xs = new XS($conf); // 建立 XS 对象，项目名称为：demo
				$index = $xs->index; // 获取 索引对象
			}
			catch(XSException $e)
			{
				$result[$k]['error'] = 1;
			}
                    }
                }
		
		$this->addItem($result);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."db WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

	private function get_condition()
	{
		$condition = '';
		return $condition;	
	}
	
	public function detail()
	{
		$id = $this->input['id'];
		if($id)
		{
			$info = $this->obj->get_db_first($id);
			$db_relation = $this->obj->get_relation($id);
		}
		//获取支持全文检索的应用模块
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
		
		$result['info'] = $info;
		$result['app'] = $app;
		$result['module'] = $module;
		$result['db_relation'] = $db_relation;
//		print_r($result);exit;
		$this->addItem($result);
		$this->output();
	}
	
	public function index(){}
	
}

$out = new textsearchApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>


			