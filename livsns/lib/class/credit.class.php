<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: credit.class.php 2595 2011-03-09 08:50:06Z chengqing $
***************************************************************************/

class credit
{
	private $curl;
	
	function __construct()
	{
		global $gCreditApiConfig;

		include_once (ROOT_DIR . 'lib/class/curl.class.php');
		$this->curl = new curl($gCreditApiConfig['host'], $gCreditApiConfig['apidir']);
	}

	function __destruct()
	{
	}

	/**
	 * 
	 * 添加积分规则
	 * @param string $rule_name
	 * @param int $cycle_type
	 * @param int $reward_num
	 * @param int $credit
	 */
	public function add_credit_rule($rule_name , $cycle_type , $reward_num , $credit)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('rule_name', $rule_name);
		$this->curl->addRequestData('reward_num', $reward_num);
		$this->curl->addRequestData('credit', $credit);		
		$this->curl->addRequestData('cycle_type', $cycle_type);
		$this->curl->request('users/credit_rule.php');
	}
	
	/**
	 * 
	 * 获取所有积分规则
	 */
	public function get_credit_rule()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_credit_rule');
		$r = $this->curl->request('users/credit_rule.php');
		return $r;
	}
	
	/**
	 * 
	 * 获取单个积分规则
	 * @param int $rid
	 */
	public function get_single_credit_rule($rid)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('rid', $rid);
		$this->curl->addRequestData('a', 'get_single_credit_rule');
		$r = $this->curl->request('users/credit_rule.php');
		return $r[0];
	}
	
	/**
	 * 
	 * 更新积分规则
	 * @param int $rule_id
	 * @param string $rule_name
	 * @param int $cycle_type
	 * @param int $reward_num
	 * @param int $credit
	 * @param int $is_use
	 */
	public function update_credit_rule($rule_id , $rule_name , $cycle_type , $reward_num , $credit , $is_use)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('rule_id', $rule_id);
		$this->curl->addRequestData('rule_name', $rule_name);
		$this->curl->addRequestData('cycle_type', $cycle_type);
		$this->curl->addRequestData('reward_num', $reward_num);
		$this->curl->addRequestData('credit', $credit);
		$this->curl->addRequestData('is_use', $is_use);
		$this->curl->addRequestData('a', 'update_credit_rule');
		$this->curl->request('users/credit_rule.php');		
	}

	public function delete_credit_rule($rule_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('rule_id', $rule_id);
		$this->curl->addRequestData('a', 'delete_credit_rule');
		$this->curl->request('users/credit_rule.php');	
	}
	
	/**
	 * 
	 * 添加积分日志
	 * @param int $rule_id 积分类型
	 * @param int $oid 被回复或被评论的ID 注册和登录为0
	 */
	public function add_credit_log($rule_id , $oid = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('rule_type', $rule_id);
		$this->curl->addRequestData('oid', $oid);
		$this->curl->request('users/credit_log.php');	
	}
}