<?php
/***************************************************************************
 * $Id: bind.php 26851 2013-08-01 09:12:30Z lijiaying $
 ***************************************************************************/
define('MOD_UNIQUEID','bind');//模块标识
require('./global.php');
class checkbind extends appCommonFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	//根据手机号码 判定是否已经注册
	public function checkBindByTel()
	{
		try {
		$tel = $this->input['tel'];
		$identifierUserSystem = new identifierUserSystem();
		$identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
		$output = array('is_bind'=>0);
		if(!$tel)
		{
			$this->addItem($output);
		}
		else
		{
			$sql = 'SELECT platform_id FROM ' . DB_PREFIX . 'member_bind WHERE platform_id = "' . $tel . '" AND identifier = \''.$identifier.'\'';
			if($row = $this->db->query_first($sql))
			{
				$output['is_bind'] = $row['platform_id'];
			}
			$this->addItem($output);
		}
		$this->output();
		}
		catch(Exception $e) {
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}

	public function checkbind()
	{
		try{
		$platform_id = $this->input['platform_id'];
		$type = $this->input['type'];
		$identifierUserSystem = new identifierUserSystem();
		$identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
		$check_Bind = new check_Bind();
		$member_id = $check_Bind->bind_to_memberid($platform_id, $type,true,$identifier);
		$is_bind = $check_Bind->check_bind($member_id,$type,0);
		if($is_bind)
		{
			$Members = new members();		
			$is_bind = array_merge($is_bind,$Members->get_member_info(' AND member_id = '.$member_id,'member_name,type'));
		}
		if(is_array($is_bind))
		foreach ($is_bind as $k => $v)
		{
			$this->addItem_withkey($k, $v);
		}else {
			$this->addItem_withkey('is_bind', $is_bind);
		}
		$this->output();
		}
		catch(Exception $e) 
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}

	public function checkUc()
	{
		try{
		$identifierUserSystem = new identifierUserSystem();
		$identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
		$check_Bind = new check_Bind();
		if($this->input['platform_id'])//检测绑定表获取会员id
		{
			$platform_id = $this->input['platform_id'];
			$type = $this->input['type'];
		}
		elseif($this->input['member_name'])//检测主表获取会员id，如果非第三方会员，如果不想多次请求接口分开查询m2o或者uc是否绑定，请用此参数传会员名过来，优先使用uc和m2o类型检测
		{
			$user_name = trim($this->input['member_name']);
			$member_id = $check_Bind->bind_to_memberid($user_name,'uc',false,$identifier);//优先检测uc类型
			$type = 'uc';
			if(empty($member_id))//如果uc类型不存在则检测m2o
			{
				$member_id = $check_Bind->bind_to_memberid($user_name,'m2o',false,$identifier);
				$type = 'm2o';
			}
			if(empty($member_id))
			{
				$member_id = $check_Bind->bind_to_memberid($user_name,'shouji',false,$identifier);
				$type = 'shouji';
			}
			if(empty($member_id))//如果uc和m2o都不存在，则以用户传的类型检测bind表
			{
				$platform_id = $this->input['member_name'];
				$type = $this->input['type'];
			}
		}
		$is_bind = 0;
		if(empty($member_id))
		{
			$member_id = $check_Bind->bind_to_memberid($platform_id,$type,true,$identifier);
		}
		$is_bind = $check_Bind->check_uc($member_id,$type);
		$this->addItem_withkey('is_bind', $is_bind);
		$this->output();
		}
		catch(Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}
}

$out = new checkbind();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'checkBindByTel';
}
$out->$action();
?>