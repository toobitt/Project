<?php
define('MOD_UNIQUEID','creditrulesdiy');//模块标识
require('./global.php');
require CUR_CONF_PATH . 'lib/CreditRulesDiy.class.php';
class memberCreditRulesDiyUpdate extends adminUpdateBase
{
	private $CreditRulesDiy;
	private $membersql;
	public function __construct()
	{
		parent::__construct();
		$this->CreditRulesDiy = new CreditRulesDiy();
		$this->Members = new members();
		$this->membersql = new membersql();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 *新增规则
	 * @see adminUpdateBase::create()
	 */
	public function create()
	{
		if(!($app_uniqueid = trimall($this->input['app_uniqueid'])))
		{
			$this->errorOutput('应用标识未传值');
		}
		if(!($operation = trimall($this->input['operation'])))
		{
			$this->errorOutput('积分规则操作key未传值');
		}
		$data = $this->filter_data();      //获取提交的数据
		//验证标识是否重复
		$checkResult = $this->membersql->verify('credit_rules_custom_app',array('appid'=>$app_uniqueid,'operation' => $operation));
		if ($checkResult) $this->errorOutput('此应用积分规则已被自定义');
		if($data)
		{
			$creditsRulesDiy = $this->Members->getDiyRulesInfo($app_uniqueid);
			$newDiyRules = array_merge($creditsRulesDiy,array($operation=>$data));
			$reDiyApp =  $this->Members->credits_rules_diy_app($app_uniqueid,$newDiyRules);
			$this->diyOutPut($reDiyApp);
		}
	}
	private function diyOutPut($reDiyApp)
	{
			switch ($reDiyApp[status])
			{
				case 0:
					$this->errorOutput(NO_APPUNIQUEID);
					break;
				case -1:
					$this->errorOutput('参数小于最小限制，请参照积分规则设置页');
					break;
				case -2:
					$this->errorOutput('参数大于最大限制，请参照积分规则设置页');
					break;
				case -3:
					$this->errorOutput('参数设置不合法，请参照积分规则设置页');
					break;
				default:
					$this->addItem($reDiyApp);
					$this->output();
					break;
			}
	}
	/**
	 *
	 * 更新
	 */
	public function update()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(NO_DATA_ID);
		$info = $this->CreditRulesDiy->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if ($data)
		{
			$creditsRulesDiy = $this->Members->getDiyRulesInfo($info[appid]);
			$newDiyRules = array_merge($creditsRulesDiy,array($info[operation]=>$data));
			$reDiyApp =  $this->Members->credits_rules_diy_app($info[appid],$newDiyRules);
			$this->diyOutPut($reDiyApp);
		}
	}

	/**
	 * 删除
	 */
	public function delete()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NO_DATA_ID);
		}
		$data = $this->CreditRulesDiy->delete($ids);
		$this->addItem($data);
		$this->output();
	}

	public function audit()
	{
		//
	}
	public function sort()
	{

	}
	public function publish()
	{
		//
	}

	/**
	 * 处理提交的数据
	 */
	private function filter_data()
	{
		if(!($creditRulesDiy = $this->input['credits_rules_diy'])||!is_array($this->input['credits_rules_diy']))
		{
			$this->errorOutput('请选择设置相应的积分规则自定义属性或者需要全部取消设置请删除即可');
		}
		return $creditRulesDiy;
	}


	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new memberCreditRulesDiyUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>