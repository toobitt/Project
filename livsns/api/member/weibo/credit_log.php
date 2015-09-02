<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: credit_log.php 3214 2011-03-31 01:37:03Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class userCreditApi extends BaseFrm
{
	private $mUser;
	
	function __construct()
	{
		parent::__construct();	
	}
	
	function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 积分操作日志
	 */
	public function add_credit_log()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);  //用户未登录
		}
		
		$user_id = $this->user['user_id'];  //用户ID
		$rule_type = intval($this->input['rule_type']);  //添加积分的类型
		$oid = intval($this->input['oid']);  			 //回复或评论的ID
		
		if(!$this->check_credit_start($rule_type))       //检测是否开启
		{
			return;
		}
		
		if($this->check_credit_add($user_id , $rule_type , $oid))  //检测积分添加，防止刷积分
		{
			return true;
		}
		else
		{
			return false;
		} 		
	}

	/**
	 * 检测积分添加，防止刷积分
	 */
	public function check_credit_add($user_id , $rule_type , $oid = 0)
	{		
		$sql = "SELECT * FROM " . DB_PREFIX . "member_credit_rule WHERE rid = " . $rule_type;

		$credit_rule_info = $this->db->query_first($sql);  //取出该积分设置的信息
		
		$credit_rule_info['cycle_type'];    //周期类型
		$credit_rule_info['reward_num'];    //奖励次数
		$credit_rule_info['credit'];        //积分值
		
		$end_time = mktime(59 , 59 , 59 , date('n') , date('d') , date('Y'));
		
		$start_time = $this->get_credit_cycle($credit_rule_info['cycle_type'] , $end_time);
		
		$time = time();
		$sql = "SELECT count(*) AS num 
				FROM " . DB_PREFIX . "member_credit_log 
				WHERE uid = " . $user_id . " 
				AND rid = " . $rule_type . " 
				AND $time >= $start_time 
				AND $time < $end_time";
		
		$r = $this->db->query_first($sql);
		
		if($r['num'] < $credit_rule_info['reward_num'])
		{

			$sql = "INSERT  INTO " . DB_PREFIX . "member_credit_log 
						(uid , rid , oid , credit , time) 
					VALUE 
						($user_id , $rule_type , $oid , {$credit_rule_info['credit']} , $time)";
			$this->db->query($sql);
			
			/**
			 * 更新用户扩展表中的积分信息
			 */
			$sql = "UPDATE " . DB_PREFIX . "member_extra 
					SET credit = credit + " . $credit_rule_info['credit'] . " 
					WHERE member_id = " . $user_id;
			$this->db->query($sql);
													
			return true;
		}
		else
		{
			return false;	
		} 				
	}
	
	/**
	 * 获取积分周期开始时间
	 */
	public function get_credit_cycle($type , $end_time)
	{
		switch ($gDateconfig[$type])
		{
			case 'EVERYDAY' : 
							 $start_time = $end_time + 24*60*60;break;
			case 'NOLIMIT' : 
							 $start_time = 0;		
			default:         $start_time = 0;			
		}
		
		return $start_time;		
	}

	/**
	 * 检测积分类型是否开启
	 */
	public function check_credit_start($rule_type)
	{
		$sql = "SELECT is_use FROM " . DB_PREFIX . "member_credit_rule WHERE rid = " . $rule_type;

		$r = $this->db->query_first($sql);
		
		if($r['is_use'])
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
}

$out = new userCreditApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'add_credit_log';
}
$out->$action();
?>