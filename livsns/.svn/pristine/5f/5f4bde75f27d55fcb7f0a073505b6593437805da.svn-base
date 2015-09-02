<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: auth.php 519 2010-12-14 06:12:26Z develop_tong $
***************************************************************************/

!defined('IN_UC') && exit('Access Denied');
define('ROOT_DIR' , '../');

require (ROOT_DIR . 'lib/func/functions.php');

class control extends adminbase
{
	private $mCredit;
	
	function __construct() 
	{
		$this->control();
	}

	function control() 
	{
		parent::__construct();
		$this->check_priv();
		if(!$this->user['isfounder'] && !$this->user['allowadminbadword']) {
			$this->message('no_permission_for_this_module');
		}
		
		require (ROOT_DIR . 'lib/class/credit.class.php');
		$this->mCredit = new credit();

		//$this->load('credit');
	}
	
	/**
	 * 
	 * 显示积分规则列表
	 */
	function onls()
	{
		$a = getgpc('a');
		
		//如果添加积分规则
		if(getgpc('add_credit_rule', 'P'))
		{
			$rule_name = getgpc('rule_name', 'P');
			$cycle_type = getgpc('cycle_type', 'P');
			$reward_num = getgpc('reward_num', 'P');
			$credit = getgpc('credit', 'P');
			
			$this->mCredit->add_credit_rule($rule_name , $cycle_type , $reward_num , $credit);				
		}
		
		//如果删除积分规则
		if(getgpc('action', 'G') == 'delete')
		{
			$rule_id = getgpc('rule_id', 'G');
			$single_rule = $this->mCredit->delete_credit_rule($rule_id);	
		}
		
		$all_credit_rule = $this->mCredit->get_credit_rule(); //获取所有积分规则
		$this->view->assign('a', $a);
		$this->view->assign('all_credit_rule', $all_credit_rule);
		$this->view->display('admin_credit');
	}
	
	/**
	 * 编辑积分规则
	 */
	public function onedit()
	{
		$a = getgpc('a');
		$rule_id = getgpc('rule_id', 'G');
		
		if(getgpc('edit_credit_rule', 'P')) //提交编辑
		{
			$rule_id = getgpc('rid');
			$rule_name = getgpc('rule_name' , 'P');
			$cycle_type = getgpc('cycle_type' , 'P');
			$reward_num = getgpc('reward_num' , 'P');
			$credit = getgpc('credit' , 'P');
			$is_use = getgpc('is_use' , 'P');
			
			$this->mCredit->update_credit_rule($rule_id , $rule_name , $cycle_type , $reward_num , $credit , $is_use);
			$this->view->assign('status', 1);
		}
		
		$single_rule = $this->mCredit->get_single_credit_rule($rule_id);

		$this->view->assign('a', $a);
		$this->view->assign('single_rule', $single_rule);
		$this->view->display('admin_credit');
	}	
}
