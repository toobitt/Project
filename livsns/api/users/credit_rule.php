
<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: credit_rule.php 17947 2013-02-26 02:57:46Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

/**
 * 用户积分API
 */
class userCreditApi extends appCommonFrm
{
	private $mUser;
	
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'api/lib/user.class.php');
		$this->mUser = new user();		
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 添加积分规则
	 */
	public function add_credit_rule()
	{
		$user_info = $this->mUser->verify_user();

		if(!$user_info)
		{
			$this -> errorOutput(USENAME_NOLOGIN);  //用户未登录
		}
		
		$user_name = $user_info['username'];
		$rule_name = trim(urldecode($this->input['rule_name'])); //积分规则名称
		$cycle_type = intval($this->input['cycle_type']); 		 //周期类型
		$reward_num = intval($this->input['reward_num']);		 //奖励次数
		$credit =  intval($this->input['credit']);				 //积分数
		
		$sql = "INSERT INTO " . DB_PREFIX . "member_credit_rule 
				( rule_name , cycle_type , reward_num , credit , add_person ) 
				VALUE ('$rule_name' , $cycle_type , $reward_num , $credit , '$user_name')";

		$this->db->query($sql);
	}
	
	/**
	 * 获取所有积分的规则
	 */
	public function get_credit_rule()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "member_credit_rule";	
		$q = $this->db->query($sql);
		
		$this->setXmlNode('credit','rule');
		while($row = $this->db->fetch_array($q))
		{
			$this->addItem($row);	
		}
		
		$this->output();
	}
	
	/**
	 * 获取单条积分规则
	 */
	public function get_single_credit_rule()
	{
		$rid = intval($this->input['rid']);		
		$sql = "SELECT * FROM " . DB_PREFIX . "member_credit_rule WHERE rid = " . $rid;
		$r = $this->db->query_first($sql);
		$this->setXmlNode('credit','singlerule');
		$this->addItem($r);
		$this->output();		
	}
	
	/**
	 * 编辑积分规则
	 */
	public function update_credit_rule()
	{
		$user_info = $this->mUser->verify_user();

		if(!$user_info)
		{
			$this -> errorOutput(USENAME_NOLOGIN);  //用户未登录
		}
		
		$user_name = $user_info['username'];
		$rid = intval($this->input['rule_id']);
		$rule_name = trim(urldecode($this->input['rule_name'])); //积分规则名称
		$cycle_type = intval($this->input['cycle_type']); 		 //周期类型
		$reward_num = intval($this->input['reward_num']);		 //奖励次数
		$credit =  intval($this->input['credit']);				 //积分数
		$is_use = intval($this->input['is_use']);
		
		$sql = "UPDATE " . DB_PREFIX . "member_credit_rule 
				SET rule_name = '" . $rule_name . "' , 
					cycle_type = " . $cycle_type . " , 
					reward_num = " . $reward_num . " , 
					credit = " . $credit . " , 
					is_use = " . $is_use . " , 
					add_person = '" . $user_name . "' 
				WHERE rid = " . $rid;
		
		$this->db->query($sql);
	}
	
	/**
	 * 删除积分规则
	 */
	public function delete_credit_rule()
	{
		$rid = intval($this->input['rule_id']);
		$sql = "DELETE FROM " . DB_PREFIX . "member_credit_rule WHERE rid = " . $rid;
		$this->db->query($sql);
	}
}

$out = new userCreditApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'add_credit_rule';
}
$out->$action();

?>