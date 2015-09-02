<?php
define('MOD_UNIQUEID','member_credit_rules');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_credit_rules.class.php';
class membercreditrulesUpdateApi extends appCommonFrm
{
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

	public function credit_rules()
	{
		if($this->user['user_id']||$this->input['member_id'])
		{
			$member_id=$this->input['member_id']?intval($this->input['member_id']):intval($this->user['user_id']);
		}
		$operation=$this->input['operation']?trim($this->input['operation']):'';
		$app_uniqueid=$this->input['app_uniqueid']?trim($this->input['app_uniqueid']):'';
		$mod_uniqueid = $this->input['mod_uniqueid']?trim($this->input['mod_uniqueid']):'';
		$sid=$this->input['sid']?intval($this->input['sid']):0;
		$cid=$this->input['cid']?intval($this->input['cid']):0;
		$ret=$this->Members->credits_rule($operation,$member_id,$coef=1,$update=1,$app_uniqueid,$mod_uniqueid,$sid,$cid);
		switch ($ret)
		{
			case 0:
				$this->errorOutput(NO_MEMBER_ID);
				break;
			case -1:
				$this->errorOutput(NO_CREDIT_RULE_KEY);
				break;
			case -2:
				$this->errorOutput(NO_DATA_ID);
				break;
			case -3:
				$this->errorOutput(NO_MEMBER);
				break;
			case -4:
				$this->errorOutput(NO_CREDIT_RULE);
				break;
			case -5:
				$this->errorOutput(NO_CREDITS);
				break;
			case -6:
				$this->errorOutput(NO_CREDIT_ERROR);
				break;
			case -7:
				$this->errorOutput(CREDIT_RULE_NO_OPEN);
				break;
			case -8:
				$this->errorOutput(NO_APPUNIQUEID);
				break;
			case -9:
				$this->errorOutput(NO_MODUNIQUEID);
				break;
			case -10:
				$this->errorOutput(NO_SID);
				break;
			case -11:
				$this->errorOutput(NO_CID);
				break;
		}
		$data = array(
		'id'=> $ret['id'],
		'rname'=>$ret['rname'],
		'operation' => $ret['operation'],
		'opened' => $ret['opened'],
		'credit1' => $ret['credit1'],
		'credit2' => $ret['credit2'],
		'member_info' => $ret['member_info'],
		'updatecredit' => $ret['updatecredit'],
		'copywriting_credit'=>$ret['copywriting_credit'],
		);
        $data = $data+$ret;
		$this->addItem($data);
		$this->output();
	}
	//空方法
	public function unknow()
	{

		$this->errorOutput("此方法不存在");
	}


}

$out = new membercreditrulesUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>