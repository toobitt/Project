<?php
define('MOD_UNIQUEID','creditrulesdiy');//模块标识
require('./global.php');
require CUR_CONF_PATH . 'lib/CreditRulesDiy.class.php';
require CUR_CONF_PATH . 'lib/member_credit_rules.class.php';
class memberCreditRulesDiy extends adminReadBase
{
	private $CreditRulesDiy;
	public function __construct()
	{
		parent::__construct();
		$this->CreditRulesDiy = new CreditRulesDiy();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	public function show()
	{	
	
		$this->verify_setting_prms();
		$condition 	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$info 	= $this->CreditRulesDiy->show($condition,$offset,$count);

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}
	
		$this->output();
	}
	
	/**
	 * 
	 * 获取允许自定义积分规则 ...
	 */
	public function getNoSetDiyRules()
	{
		$creditRules = new creditrules();
		$Members = new members();
		$appid = trimall($this->input['app_uniqueid']);
		if(empty($appid))
		{
			$this->errorOutput('请传应用标识');
		}
		$appDiyRule = $Members->getDiyRulesInfo($appid);
		$diy = $creditRules->getDiyRules('operation,rname,opened');
		foreach ($diy as $k=>$v)
		{
			if(!array_key_exists($k, $appDiyRule)){
				$this->addItem_withkey($k, $v);	
			}
		}
		$this->output();
		
	}
	/**
	 * 
	 * 获取还未自定义积分规则的应用标识和名称 ...
	 */
	public function getNotSetApp()
	{
		if($this->input[id])//传id则不需要输出应用列表
		{
			$this->addItem(array());
			$this->output();
		}
		$appInfo = $this->CreditRulesDiy->getApp();
		$Members = new members();
		$creditRules = new creditrules();
		$appid = array();
		if($appInfo&&is_array($appInfo))
		{
			$appid = array_keys($appInfo);
			$appDiyRule = $Members->getDiyRulesInfo($appid,true);
			$diyRule = $creditRules->getDiyRules();
			$diyRuleKey = array();
			if(is_array($diyRule))
			{
				$diyRuleKey = array_keys($diyRule);
			}
			foreach ($appDiyRule as $k =>$v)
			{
				$noSetRuleKey = array();
				if(is_array($v))
				{
					$setRuleKey = array_keys($v);
					$noSetRuleKey = array_diff($diyRuleKey,$setRuleKey);
				}
				if(empty($noSetRuleKey))
				{
					unset($appInfo[$k]);
				}
			}
			foreach ($appInfo as $k => $v)
			{
					$this->addItem_withkey($k, $v);
			}
		}
		else{
			$this->addItem($appid);
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
		$info = $this->CreditRulesDiy->detail($id);
		$this->addItem($info);
		$this->output();
	}

	
	public function count()
	{
		$condition = $this->get_condition();
		echo json_encode($this->CreditRulesDiy->count($condition));
	}
	
	private function get_condition()
	{
		$condition = '';		 
		return $condition;
	}

}

$out = new memberCreditRulesDiy();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>