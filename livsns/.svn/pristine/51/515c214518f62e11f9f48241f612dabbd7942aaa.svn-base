<?php
define('MOD_UNIQUEID','member_credits');//模块标识
require('./global.php');
class member_creditsApi extends outerReadBase
{
	private $memberCredits = null;
	public function __construct()
	{
		parent::__construct();
		$this->memberCredits = new memberCredits();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		try {
			$type = (int)$this->input['type'];
			if($type == 1)
			{
				$this->showCredit1();
			}
			else if ($type == 2)
			{
				$this->showCredit2();
			}
			else
			{
				$this->showCreditAll();
			}
		}
		catch (Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}
	public function showCreditAll()
	{
		$this->memberCredits->setFieldS('u_id,credit1,credit2');
		$this->MemberSshow();
	}
	private function MemberSshow()
	{
		$this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$this->memberCredits->setLimit($offset,$count);
		$info 	= $this->memberCredits->MemberSshow();
		foreach ($info as $k => $data)
		{
			$this->addItem($data);
		}
		$this->output();
	}

	private function showCredit1()
	{
		$this->memberCredits->setFieldS('u_id,credit1');
		$this->memberCredits->setOrderbyS('ORDER BY credit1'.$this->memberCredits->setObType($this->input['obtype']));
		$this->MemberSshow();
	}

	private function showCredit2()
	{
		$this->memberCredits->setFieldS('u_id,credit2');
		$this->memberCredits->setOrderbyS('ORDER BY credit2'.$this->memberCredits->setObType($this->input['obtype']));
		$this->MemberSshow();
	}

	public function detail()
	{

	}

	public function count()
	{
		$data = array();
		try {
			$condition = $this->get_condition();
			$data['total'] = $this->memberCredits->count();
			$this->setAddItemValueType();
			$this->addItem($data);
			$this->output();
		}
		catch (Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}

	private function get_condition()
	{
		$condition = '';
		if(($gid = (int)$this->input['groupid'])>0 || ($gradeid = (int)$this->input['gradeid'])>0)//根据用户组和等级
		{
			$this->memberCredits->setJoin(' LEFT JOIN '.DB_PREFIX.'member as m ON m.member_id = mc.u_id');
			$gid && $condition = ' AND m.gid = \'' . $gid . '\'';
			$gradeid && $condition = ' AND m.gradeid = \'' . $gradeid . '\'';
			$this->memberCredits->setWhere($condition);
		}
		else if (isset($this->input['startcredit1']) || isset($this->input['endcredit1']))//根据积分(金币)范围
		{
			if(($startcredit1 = (int)$this->input['startcredit1']) || isset($this->input['startcredit1']))
			{
				$condition = ' AND credit1>='.$startcredit1;
				$this->memberCredits->setWhere($condition);
			}
			if((($endcredit1   = (int)$this->input['endcredit1']) || isset($this->input['endcredit1']) )&& $endcredit1>=$startcredit1)
			{
				$condition = ' AND ' .'credit1<='.$endcredit1;
				$this->memberCredits->setWhere($condition);
			}			
		}
		else if (isset($this->input['startcredit2']) || isset($this->input['endcredit2']))//根据经验范围
		{
			if(($startcredit2 = (int)$this->input['startcredit2']) || isset($this->input['startcredit2']))
			{
				$condition = ' AND credit2>='.$startcredit2;
				$this->memberCredits->setWhere($condition);
			}
			if((($endcredit2   = (int)$this->input['endcredit2']) || isset($this->input['endcredit2']) )&& $endcredit2>=$startcredit2)
			{
				$condition = ' AND ' .'credit2<='.$endcredit2;
				$this->memberCredits->setWhere($condition);
			}	
		}
		else if (($fbid = (int)$this->input['fbid'])>0)//根据反馈表单id(可以作为活动查询)
		{
			$feedback = new feedback();
			$membersId = $feedback->get_feed_members($fbid,'0,1');
			if(!$membersId)
			{
				throw new Exception(FEEDBACK_ID_NO_MEMBER, 200);
			}
			$this->memberCredits->setWhere(array('u_id'=>$membersId['member_id']));
		}
		else if ($spreadCode = trim($this->input['spreadcode']))//根据推广码
		{
			$this->memberCredits->setJoin(' LEFT JOIN '.DB_PREFIX.'spread_record as sr ON mc.u_id=sr.fuid');
			$condition = " AND sr.spreadcode = '" . $spreadCode . "'";
			$this->memberCredits->setWhere($condition);
		}
		else if(($invite_userid = (int)$this->input['invite_userid'])>0)//根据邀请会员ID
		{
			$_invite = new invite();
			$fuidArr = $_invite->select_uid_to_fuid($invite_userid,'mi.fuid','fuid',3,false);
			if(!$fuidArr)
			{
				throw new Exception(MEMBER_NO_INVITE_MEMBERS,200);
			}
			$this->memberCredits->setWhere(array('u_id'=>$fuidArr));
		}
		else if (($medalid = (int)$this->input['medalid'])>0)//根据勋章ID
		{
			$this->memberCredits->setJoin(' LEFT JOIN '.DB_PREFIX.'member_medal as mm ON mm.member_id=mc.u_id');
			$condition = " AND mm.medalid = " . $medalid." AND (mm.expiration=0 OR mm.expiration>".TIMENOW.")";
			$this->memberCredits->setWhere($condition);
		}
		else if ($member_type = trim($this->input['member_type']))//根据会员类型
		{
			$this->memberCredits->setJoin(' LEFT JOIN '.DB_PREFIX.'member_bind as mb ON mb.member_id=mc.u_id');
			$condition = ' AND mb.type = \''.$member_type.'\'';
			$this->memberCredits->setWhere($condition);
		}
		else if($this->input['start_time'] || $this->input['end_time'])//根据注册时间范围
		{
			$this->memberCredits->setJoin(' LEFT JOIN '.DB_PREFIX.'member as m ON m.member_id = mc.u_id');
			if($start_time = trim(urldecode($this->input['start_time'])))
			{
				$start_time = strtotime($start_time);
				$condition = " AND m.create_time >= ".(int)$start_time;
				$this->memberCredits->setWhere($condition);
			}
			if($end_time = trim(urldecode($this->input['end_time'])))
			{
				$end_time = strtotime($end_time);
				if($end_time>=$start_time)
				{
				 $condition = " AND m.create_time <= ".(int)$end_time;
				 $this->memberCredits->setWhere($condition);
				}
			}
		}
		
		if (($appid = (int)$this->input['mappid'])>0)//根据注册来源（APPID）
		{
			$this->memberCredits->setJoin(' LEFT JOIN '.DB_PREFIX.'member as m ON m.member_id = mc.u_id');
			$condition = " AND m.appid = " . $appid;
			$this->memberCredits->setWhere($condition);
		}
		
		if(isset($this->input['identifier']))//根据多套用户系统ID
		{
			$identifierUserSystem = new identifierUserSystem();
			$identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
			$this->memberCredits->setJoin(' LEFT JOIN '.DB_PREFIX.'member as m ON m.member_id = mc.u_id');
			$condition = " AND m.identifier = " . $identifier;
			$this->memberCredits->setWhere($condition);
		}
		
		$this->memberCredits->setAs('mc');
		return $condition;
	}
	
	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

}

$out = new member_creditsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>