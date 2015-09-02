<?php
define('MOD_UNIQUEID','configSetSort');//模块标识
require('./global.php');
class configSet_Sort extends adminReadBase
{
	private $configSetSort;
	public function __construct()
	{
		parent::__construct();
		$this->configSetSort = new configSetSort();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	public function show()
	{
		$this->verify_setting_prms();
		$condition=$this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$info 	= $this->configSetSort->show($condition,$offset,$count);
		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}

		$this->output();
	}

	public function detail()
	{
		$id = intval($this->input['id']);
		if(empty($id))
		{
			return false;
		}
		$info = $this->configSetSort->detail($id);
		$settingRelation = new settingRelation();
		if($info)
		{
			$info['app_uniqueid'] = $settingRelation->show(array('groupmark'=>$info[groupmark]), 0, 0,'app_uniqueid','app_uniqueid',0);
			$this->addItem($info);
		}
		$this->output();
	}
	
	public function getUseSetApp()
	{
		$settingRelation = new settingRelation();
		$configAppInfo= $settingRelation->show($condition, 0, 0,'distinct ac.id,t.app_uniqueid,ac.appname','',1,'','LEFT JOIN '.DB_PREFIX.'appconfig ac ON t.app_uniqueid = ac.app_uniqueid');
		if(is_array($configAppInfo))
	 	foreach ($configAppInfo as $v)
	 	$this->addItem($v);
	 	$this->output();
	}
	public function getSetApp()
	{
		$configSetApp = new configApp();
		$configAppInfo = $configSetApp->show('',0,0,'id,app_uniqueid,appname');
		if(is_array($configAppInfo))
	 	foreach ($configAppInfo as $v)
	 	$this->addItem($v);
	 	$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$ret[total] = $this->configSetSort->count($condition);
		echo json_encode($ret);
	}

	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND grouptitle LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		if (isset($this->input['app_uniqueid'])&&$this->input['app_uniqueid'] != -1)
		{
			$app_uniqueid = $this->input['app_uniqueid'];
			$settingRelation = new settingRelation();
			$groupmark = $settingRelation->show(array('app_uniqueid'=>$app_uniqueid), 0, 0,'groupmark','groupmark',0);
			if($groupmark){
				$condition .= ' AND groupmark IN (\''.implode('\',\'', $groupmark).'\')';
			}
		}
		return $condition;
	}

}

$out = new configSet_Sort();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>