<?php
define('MOD_UNIQUEID','member_credits');//模块标识
require('./global.php');
require CUR_CONF_PATH . 'lib/member_credit_log.class.php';
class membercreditsUpdateApi extends appCommonFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->Members = new members();
		$this->membersql = new membersql();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 *
	 * 积分消费函数
	 */
	public function consume_credit()
	{
		$member_id=$this->get_condition();//获取member_id
		$credit_type=$this->Members->get_trans_credits_type();
		if(empty($credit_type))
		{
			$this->errorOutput(NO_CONSUME_CREDIT);
		}
		$new_credit=intval($this->input['credit']);
		if($new_credit)
		{
			$member_credit=$this->Members->membercredit($member_id,false,false);
			$old_credit=0;
			if($member_credit&&is_array($member_credit))
			{
				$old_credit=$member_credit[$credit_type];
			}
			if($old_credit<abs_num($new_credit))
			{
				$this->errorOutput(CONSUME_CREDIT_ERROR);
			}
			$credits[$credit_type]=neg_num($new_credit);
			$relatedid=$this->input['relatedid']?trim($this->input['relatedid']):0;//消费订单号
			$app_uniqueid=$this->input['app_uniqueid']?$this->input['app_uniqueid']:APP_UNIQUEID;//应用id
			$mod_uniqueid=$this->input['mod_uniqueid']?$this->input['mod_uniqueid']:MOD_UNIQUEID;//模块id
			$action=$this->input['action']?$this->input['action']:$this->input['a'];
			$method='create';//操作
			$remark=$this->input['remark']?trim($this->input['remark']):'';
			if(empty($remark)||empty($app_uniqueid)||empty($mod_uniqueid)||empty($method)||empty($relatedid))
			{
				$this->errorOutput(CREDIT_OP_ERROR);
			}
			$isFrozen = $this->addfrozenCredit($member_id, abs_num($new_credit));
			$credit_log=array(
			'app_uniqueid'=>$app_uniqueid,
			'mod_uniqueid'=>$mod_uniqueid,
			'action'=>$action,
			'method'=>$method,
			'relatedid'=>$relatedid,
			'remark'=>$remark,
			'isFrozen'=>$isFrozen['status'],
			);
			if($title = $this->input['creditlogtitle'])
			{
				$credit_log['title'] = $title;
			}
			if($icon = $this->input['creditlogicon'])
			{
				$credit_log['icon'] = maybe_serialize($icon);
			}
			$re_credit =  $this->Members->credits($credits, $member_id,$coef=1,true,true,false,array(),$member_credit,$credit_log);
			if($re_credit<=0&&$isFrozen['isFrozen'])//如果积分扣除失败，则扣除冻结金额
			{
				$where = array(	'member_id'=>$member_id,
					   	'app_uniqueid'=>$app_uniqueid,
						'mod_uniqueid'=>$mod_uniqueid,
						'method'=>$method,
						'relatedid'=>$relatedid,
					);
				$this->finalFrozenCredit($member_id,true,$where);
			}
			$this->error($re_credit);
		}
		else if (!isset($this->input['credit'])) 
		{
			$this->errorOutput(NO_CREDITS);
		}
		$ret_credit=array();
		if($re_credit&&is_array($re_credit))
		{
			$ret_credit['logid']=$re_credit['logid'];
			$ret_credit['credit']=$new_credit;
			$ret_credit['isFrozen']=$isFrozen['status'];
		}
		if($ret_credit&&is_array($ret_credit))
		{
			foreach ($ret_credit as $k=>$v)
			{
				$this->addItem_withkey($k, $v);
			}
		}
		$this->output();

	}
	
	/**
	 *
	 * 积分消费撤销函数（如果开启冻结功能，则减少冻结金额，在返还积分）
	 */
	public function return_credit()
	{
		$member_id=$this->get_condition();//获取member_id
		$credit_type=$this->Members->get_trans_credits_type();
		if(empty($credit_type))
		{
			$this->errorOutput(NO_CONSUME_CREDIT);
		}
			$relatedid=$this->input['relatedid']?trim($this->input['relatedid']):0;//消费订单id
			$app_uniqueid=$this->input['app_uniqueid']?$this->input['app_uniqueid']:APP_UNIQUEID;//应用id
			$mod_uniqueid=$this->input['mod_uniqueid']?$this->input['mod_uniqueid']:MOD_UNIQUEID;//模块id
			$action=$this->input['action']?$this->input['action']:$this->input['a'];
			$method='cancel';//操作方法
			$remark=$this->input['remark']?trim($this->input['remark']):'';
			if(empty($remark)||empty($app_uniqueid)||empty($mod_uniqueid)||empty($method)||empty($relatedid))
			{
				$this->errorOutput(CREDIT_OP_ERROR);
			}
				$where = array(	'member_id'=>$member_id,
					   	'app_uniqueid'=>$app_uniqueid,
						'mod_uniqueid'=>$mod_uniqueid,
						'method'=>'cancel',
						'relatedid'=>$relatedid,
						);
			$cond= $this->membersql->where($where);
			$creditlog=$this->Members->getcreditlog($cond,$credit_type);//查询积分撤销日志，防止重复撤销.
			if($creditlog)
			{
				$this->errorOutput(RETURN_CREDIT_REPEAT);
			}
			$where['method'] = 'create';
			$this->membersql->unsetWhere();
			$cond = $this->membersql->where($where);
			$creditlog = $this->Members->getcreditlog($cond,$credit_type);//查询积分日志.
			$isFrozen = $this->finalfrozenCredit($member_id,true,$where);
			if($creditlog)
			{
			$credit_log=array(
			'app_uniqueid'=>$app_uniqueid,
			'mod_uniqueid'=>$mod_uniqueid,
			'action'=>$action,
			'method'=>$method,
			'relatedid'=>$relatedid,
			'remark'=>$remark,
			);
			if($title = $this->input['creditlogtitle'])
			{
				$credit_log['title'] = $title;
			}
			if($icon = $this->input['creditlogicon'])
			{
				$credit_log['icon'] = maybe_serialize($icon);
			}
			$creditlog[$credit_type] = abs_num($creditlog[$credit_type]);
				$re_credit =  $this->Members->credits($creditlog,$member_id,$coef=1,true,true,false,array(),$member_credit=array(),$credit_log);
				$this->error($re_credit);
			}
		$ret_credit=array();
		if($re_credit)
		{
			$ret_credit['logid']=$re_credit['logid'];
			$ret_credit['credit']=$creditlog[$credit_type];
			$ret_credit['isFrozen'] = $isFrozen['status'];
		}
		if($ret_credit&&is_array($ret_credit))
		{
			foreach ($ret_credit as $k=>$v)
			{
				$this->addItem_withkey($k, $v);
			}
		}
		$this->output();

	}
	/**
	 * 
	 * 增加冻结积分
	 */
	private function addfrozenCredit($member_id,$credit)
	{
		$isFrozen = array('member_id'=>$member_id,'credit'=>0,'status'=>0);
		if($this->settings['isFrozen'])
		{
			$isFrozen = $this->Members->addfrozenCredit($member_id,$credit);
		}
		return $isFrozen;
	}
	
	/**
	 * 
	 * 取消冻结积分
	 */
	public function finalFrozenCredit($member_id = 0,$isRe = false,$where = array())
	{
		$isFrozen = array('member_id'=>$member_id,'credit'=>0,'status'=>0);
		$is_update = false;
		$credit_type=$this->Members->get_trans_credits_type();
		if(empty($credit_type))
		{
			$this->errorOutput(NO_CONSUME_CREDIT);
		}
		$member_id =  !$member_id?($this->get_condition()):$member_id;
		$relatedid =  !$where['relatedid']?($this->input['relatedid']?trim($this->input['relatedid']):0):$where['relatedid'];//消费订单id
		$app_uniqueid=!$where['app_uniqueid']?($this->input['app_uniqueid']?$this->input['app_uniqueid']:APP_UNIQUEID):$where['app_uniqueid'];//应用id
		$mod_uniqueid=!$where['mod_uniqueid']?($this->input['mod_uniqueid']?$this->input['mod_uniqueid']:MOD_UNIQUEID):$where['mod_uniqueid'];//模块id
		$method      =!$where['method']?'create':$where['method'];//操作方法
		$_isFrozen=$this->input['isFrozen']?$this->input['isFrozen']:0;
		if($this->settings['isFrozen']||$_isFrozen)
		{
			if(empty($where))
			{
				$where = array(	'member_id'=>$member_id,
					   	'app_uniqueid'=>$app_uniqueid,
						'mod_uniqueid'=>$mod_uniqueid,
						'method'=>$method,
						'relatedid'=>$relatedid,
					);
			}
			$_where['isFrozen'] = $this->settings['isFrozen']?1:$_isFrozen;
			$cond= $this->membersql->where(array_merge($where,$_where));
			$creditlog=$this->Members->getcreditlog($cond,'id,'.$credit_type);//查询积分日志.
			if($creditlog)
			{
				$isFrozen = $this->Members->finalFrozenCredit($member_id,abs_num($creditlog[$credit_type]));
				credit_log::updateCreditLogByIsFrozen($creditlog['id'],0);
			}
		}
		if($isRe)
		{
			return $isFrozen;
		}
		foreach ($isFrozen as $k => $v)
		$this->addItem_withkey($k, $v);
		$this->output();
	}
	
	/**
	 * 增加积分和经验 ...
	 */
	public function add_credit()
	{
		$member_id=$this->get_condition();//获取member_id
		$credit_type_info=$this->Members->get_credit_type();
		$credit_type=array_keys($credit_type_info);
		if(empty($credit_type))
		{
			$this->errorOutput(NO_CREDITS);
		}
		$credits=array();
		$re_credit = array();
		foreach ($credit_type as $v)
		{
			$credit=intval($this->input[$v]);
			if($credit)
			{
				$credits[$v]=abs_num($credit);
			}
		}
		if($credits)
		{
			$relatedid=$this->input['relatedid']?$this->input['relatedid']:0;//操作物品相关id
			$app_uniqueid=$this->input['app_uniqueid']?$this->input['app_uniqueid']:APP_UNIQUEID;//应用id
			$mod_uniqueid=$this->input['mod_uniqueid']?$this->input['mod_uniqueid']:MOD_UNIQUEID;//模块id
			$action=$this->input['action']?$this->input['action']:$this->input['a'];
			$method='add';//操作类型
			$remark=$this->input['remark']?trim($this->input['remark']):'';
			if(empty($remark))
			{
				$this->errorOutput(CREDIT_OP_ERROR);
			}
			$credit_log=array(
			'app_uniqueid'=>$app_uniqueid,
			'mod_uniqueid'=>$mod_uniqueid,
			'action'=>$action,
			'method'=>$method,
			'relatedid'=>$relatedid,
			'remark'=>$remark,
			);
			if($title = $this->input['creditlogtitle'])
			{
				$credit_log['title'] = $title;
			}
			if($icon = $this->input['creditlogicon'])
			{
				$credit_log['icon'] = maybe_serialize($icon);
			}
			$re_credit = $this->Members->credits($credits, $member_id,$coef=1,true,true,false,$credit_type,array(),$credit_log);
			$this->error($re_credit);
		}
		is_array($re_credit)&&$credits=array_merge($credits,$re_credit);
		$credits['credit_type']=$credit_type_info;
		$credits['copywriting_credit']=copywriting_credit(array($credits),true);
		unset($credits['credit_type']);
		$this->addItem($credits);
		$this->output();

	}

	/**
	 * 减少积分和经验 ...
	 */
	public function sub_credit()
	{
		$member_id=$this->get_condition();//获取member_id
		$credit_type_info=$this->Members->get_credit_type();
		$credit_type=array_keys($credit_type_info);
		if(empty($credit_type))
		{
			$this->errorOutput(NO_CONSUME_CREDIT);
		}
		$credits=array();
		foreach ($credit_type as $k=>$v)
		{
			if($credit=intval($this->input[$v]))
			{
				$credits[$v]=neg_num($credit);
			}
		}
		if($credits)
		{
			$relatedid=$this->input['relatedid']?$this->input['relatedid']:0;//操作物品相关id
			$app_uniqueid=$this->input['app_uniqueid']?$this->input['app_uniqueid']:APP_UNIQUEID;//应用id
			$mod_uniqueid=$this->input['mod_uniqueid']?$this->input['mod_uniqueid']:MOD_UNIQUEID;//模块id
			$action=$this->input['action']?$this->input['action']:$this->input['a'];
			$method='sub';//操作方法
			$remark=$this->input['remark']?trim($this->input['remark']):'';
			if(empty($remark))
			{
				$this->errorOutput(CREDIT_OP_ERROR);
			}
			$credit_log=array(
			'app_uniqueid'=>$app_uniqueid,
			'mod_uniqueid'=>$mod_uniqueid,
			'action'=>$action,
			'method'=>$method,
			'relatedid'=>$relatedid,
			'remark'=>$remark,
			);
			if($title = $this->input['creditlogtitle'])
			{
				$credit_log['title'] = $title;
			}
			if($icon = $this->input['creditlogicon'])
			{
				$credit_log['icon'] = maybe_serialize($icon);
			}
			$re_credit = $this->Members->credits($credits, $member_id,$coef=1,true,true,false,$credit_type,array(),$credit_log);
			$this->error($re_credit);
		}
		$credits=array_merge($credits,$re_credit);
		$credits['credit_type']=$credit_type_info;
		$credits['copywriting_credit']=copywriting_credit(array($credits),true);
		unset($credits['credit_type']);
		$this->addItem($credits);
		$this->output();

	}

	private function get_condition()
	{
		$member_id = 0;
		if($this->input['member_id']&&$this->user['group_type'] <= MAX_ADMIN_TYPE)//如果直接传会员ID，必须为系统内应用间调用，group_type为系统应用之间调用会自动重置为1
		{
			$member_id = $this->input['member_id']? intval($this->input['member_id']) : 0;
		}
		elseif ($this->user['user_id'])
		{
			$member_id = $this->user['user_id'];
		}
		return $member_id;
	}

	private function error($status)
	{
		switch ($status)
		{
			case 0:
				$this->errorOutput(NO_MEMBER_ID);
				break;
			case -1:
				$this->errorOutput(NO_MEMBER);
				break;
			case -2:
				$this->errorOutput(NO_CREDITS);
				break;
			case -3:
				$this->errorOutput(NO_CREDIT_UPDATE);
				break;
			case -4:
				$this->errorOutput(CREDIT_LOG_REQUIRED_PARAME_ERROR);
			case -5:
				$this->errorOutput(CREDIT_LOG_PARAME_FORMAT_ERROR);
				
		}
	}
	//空方法
	public function unknow()
	{

		$this->errorOutput("此方法不存在");
	}


}

$out = new membercreditsUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>