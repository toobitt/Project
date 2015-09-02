<?php
define('MOD_UNIQUEID','configSet');//模块标识
require('./global.php');
class config_Set extends adminReadBase
{
	private $configSet;
	public function __construct()
	{
		parent::__construct();
		$this->configSet = new configSet();
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
		$info 	= $this->configSet->show($condition,$offset,$count,'*','','ORDER BY order_id DESC',array('limitapps'=>array('type'=>'explode','delimiter'=>"\n")));
		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				if($app_uniqueid&&($v['limitapps']&&!in_array($app_uniqueid,$v['limitapps'])))
				{
					continue;
				}
				$v['value'] = outPutFormat($v['type'],$v['value'],array('img'=>1));
				$v['html'] = $this->configSet->makehtml($v);
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
		$info = $this->configSet->detail($id);
		if(is_array($info))
		{
			$info['islimits'] = 0;
			$info['value'] = $info['type']!='checkbox'?outPutFormat($info['type'],$info['value']):$info['value'];
			foreach ($info as $k => $v)
			{
				if($k =='limitapps'&&$v)
				{
					$info['islimits'] = 1;
				}
			}
		}
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$ret[total] = $this->configSet->count($condition);
		echo json_encode($ret);
	}

	public function getSetSort()
	{
		$groupmark = array();
		if (isset($this->input['app_uniqueid'])&&$this->input['app_uniqueid'] != -1)
		{
			if($app_uniqueid = trim($this->input['app_uniqueid']))
			{
				$settingRelation = new settingRelation();
				$groupmark['groupmark'] = $settingRelation->show(array('app_uniqueid'=>$app_uniqueid), 0, 0,'groupmark','groupmark',0);
			}
		}
		$setSortInfo = $this->configSet->getSort($groupmark);
		if(is_array($setSortInfo))
		foreach ($setSortInfo as $v)
		$this->addItem($v);
		$this->output();
	}

	public function getUseSetApp()
	{
		$settingRelation = new settingRelation();
		$setAppInfo = $settingRelation->show($condition, 0, 0,'distinct ac.id,t.app_uniqueid,ac.appname','',1,'','LEFT JOIN '.DB_PREFIX.'appconfig ac ON t.app_uniqueid = ac.app_uniqueid');
		if(is_array($setAppInfo))
		foreach ($setAppInfo as $v)
		$this->addItem($v);
		$this->output();
	}
	public function getSetApp()
	{
		$setAppInfo = $this->configSet->getApp();
		if(is_array($setAppInfo))
		foreach ($setAppInfo as $v)
		$this->addItem($v);
		$this->output();
	}
	/**
	 *
	 * 获取分类绑定的应用 ...
	 */
	public function getSetSortBindApp()
	{
		$groupmark = trim($this->input['groupmark']);
		if(empty($groupmark))
		{
			$setid = intval($this->input['id']);
			$configSetInfo = $this->configSet->detail($setid,'groupmark');
			$groupmark = $configSetInfo[groupmark];
		}
		if($groupmark)
		{
			$settingrelation = new settingRelation();
			$relationInfo = $settingrelation->show(array('groupmark'=>$groupmark), 0, 0,'app_uniqueid','app_uniqueid',0);
			if($relationInfo)
			{
				$configApp = new configApp();
				$configAppInfo = $configApp->show(array('app_uniqueid'=>$relationInfo), 0, 0,'appname,app_uniqueid');
				foreach ($configAppInfo as $v)
				$this->addItem($v);
				$this->output();
			}
		}
	}
	/**
	 *
	 * 获取配置
	 */
	public function getConfig()
	{
		$app_uniqueid = trim($this->input['app_uniqueid']);
		$config = $this->configSet->getConfig(array($app_uniqueid));
		foreach ($config as $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}

	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND grouptitle LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		if (isset($this->input['groupmark'])&&$this->input['groupmark'] != -1)
		{
			$groupmark = $this->input['groupmark'];
			$condition .= ' AND groupmark = \''.$groupmark.'\'';
		}
		else if (isset($this->input['app_uniqueid'])&&$this->input['app_uniqueid'] != -1&&(empty($this->input['groupmark'])||$this->input['groupmark'] == -1))
		{
			if($app_uniqueid = trim($this->input['app_uniqueid']))
			{
				$settingRelation = new settingRelation();
				$groupmark = $settingRelation->show(array('app_uniqueid'=>$app_uniqueid), 0, 0,'groupmark','groupmark',0);
				if($groupmark)
				{
					$groupMarkWhere = trim("'".implode("','", $groupmark )."'");
					if(count($groupmark)>1)
					{
						$condition .= ' AND groupmark IN ('.$groupMarkWhere.')';
					}
					else
					{
						$condition .= ' AND groupmark = '.$groupMarkWhere;	
					}
				}
			}
		}
		return $condition;
	}

}

$out = new config_Set();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>