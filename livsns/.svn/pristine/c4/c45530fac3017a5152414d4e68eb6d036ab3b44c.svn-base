<?php
define('MOD_UNIQUEID','member_credit_rules');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_credit_rules.class.php';
class membercreditrulesApi extends outerReadBase
{
	private $Members;
	private $creditrules;
	public function __construct()
	{
		parent::__construct();
		$this->creditrules = new creditrules();
		$this->Members = new members();	
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 取出积分规则.
	 * 
	 */
	public function show()
	{	
		$condition 	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$AppDiyRulesInfo = $this->getAppDiyRulesInfo(TRUE);//获取某个应用自定义规则
		if($AppDiyRulesInfo)
		{
			$selectDiykey = array_keys($AppDiyRulesInfo);
			$strSelectDiykey = trim("'".@implode("','", $selectDiykey )."'");
			if($strSelectDiykey){
				$condition = ' AND operation IN ('.$strSelectDiykey.')';
			}
		}	
		$info = $this->creditrules->show($condition,$offset,$count);

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				if(!$v['opened'])
				{
					continue;
				}
				if(is_array($v))
				foreach ($v as $kk=>$vv)
				{
					$v[$kk] = isset($AppDiyRulesInfo[$v['operation']][$kk])?$AppDiyRulesInfo[$v['operation']][$kk]:valueTypeChange($vv, $this->KeyRules($kk));
				}
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
			$this->errorOutput(NO_DATA_ID);
		}
		$info = $this->creditrules->detail($id);
		$this->addItem($info);
		$this->output();	
	}
	/**
	 * 
	 * 获取可以自定义的积分规则 ...
	 */
	public function getDiyRules()
	{
		$info 	= $this->creditrules->getDiyRules('*');
		if(is_array($info))
		foreach ($info as $val)
		{
			$this->addItem($val);
		}
		$this->output();
	}
	/**
	 * 
	 * 获取已经定义的应用积分规则信息 ...
	 */
	public function getAppDiyRulesInfo($iSret = false)
	{
		$app_uniqueid=trimall($this->input['app_uniqueid']);
		if(!$app_uniqueid&&!$iSret)
		{
			$this->errorOutput(NO_APPUNIQUEID);
		}
		$AppDiyRulesInfo = $this->Members->getDiyRulesInfo($app_uniqueid);
		if($iSret)
		{
			return $AppDiyRulesInfo;
		}
		if(is_array($AppDiyRulesInfo))
		{
			foreach ($AppDiyRulesInfo as $k => $v)
			{
				unset($v['appids'],$v['gids']);
				$this->addItem_withkey($k, $v);
			}
		}else $this->addItem($AppDiyRulesInfo);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		echo json_encode($this->creditrules->count($condition));
	}
	private function get_condition()
	{
		$condition = " AND opened = 1";
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$binary = '';//不区分大小些
			if(defined('IS_BINARY') && !IS_BINARY)//区分大小些
			{
				$binary = 'binary ';
			}	
			$condition .= ' AND ' . $binary . ' rname like \'%'.trim($this->input['k']).'%\'';
		}
		if (isset($this->input['iscustom']) && $this->input['iscustom'] != -1)
		{
			$condition .= " AND iscustom = " . intval($this->input['iscustom']);
		}
		if (isset($this->input['cycletype']) && $this->input['cycletype'] != -1)
		{
			$condition .= " AND cycletype = " . intval($this->input['cycletype']);
		}
		if (!empty($this->input['credittype']))//根据积分类型取相应的积分规则，1取积分类型规则，2取经验类型规则
		{
			$credittype = '';
			if($this->input['credittype'] == 2){
				$credittype = $this->Members->get_grade_credits_type();
			}
			elseif ($this->input['credittype'] == 1){
				$credittype = $this->Members->get_trans_credits_type();
			}
			if($credittype)
			{
				$condition .= " AND $credittype !=0";
			}
		}
		if (isset($this->input['cycletype']) && $this->input['cycletype'] != -1)
		{
			$condition .= " AND cycletype = " . intval($this->input['cycletype']);
		}		 
		return $condition;
		
		
		return $condition;
	}
	
	/**
	 * 
	 * 字段输出规则 ...
	 */
	private function KeyRules($key)
	{
		$keyType = array(
		'id'=>array('type'=>'int'),
		'issystem'=>array('type'=>'int'),
		'iscustom'=>array('type'=>'int'),
		'rname'=>array('type'=>'string'),
		'opened'=>array('type'=>'int'),
		'cyclelevel'=>array('type'=>'int'),
		'cycletype'=>array('type'=>'int'),
		'cycletime'=>array('type'=>'int'),
		'rewardnum'=>array('type'=>'int'),
		'credit1'=>array('type'=>'int'),
		'credit2'=>array('type'=>'int'),
		);
		return  $keyType[$key];
	}

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}
	
}

$out = new membercreditrulesApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>