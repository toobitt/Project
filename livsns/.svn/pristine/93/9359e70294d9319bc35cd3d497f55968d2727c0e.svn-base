<?php
/***************************************************************************
 * $Id: bind.php 26851 2013-08-01 09:12:30Z lijiaying $
 ***************************************************************************/
define('MOD_UNIQUEID','bind');//模块标识
require('./global.php');
class unbindapi extends appCommonFrm
{
	private $mMember;
	private $mSmsServer;
	public function __construct()
	{
		parent::__construct();

		require_once CUR_CONF_PATH . 'lib/member.class.php';
		$this->mMember = new member();

		require_once CUR_CONF_PATH . 'lib/sms_server.class.php';
		$this->mSmsServer = new smsServer();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	//解除绑定
	public function unbind()
	{
		$member_id = intval($this->user['user_id']);
		$platform_id =  $this->input['platform_id'];
		$type = $this->input['type'];
		if(!$member_id)
		{
			$this->errorOutput(USER_NO_LOGIN);
		}
		else if (!$type)
		{
			$this->errorOutput(NO_MEMBER_TYPE);
		}
		else if (!$platform_id)
		{
			$this->errorOutput(NO_EXTERNAL_MEMBER_ID);
		}
		if(($sType = $this->mMember->getMemberType($member_id))==$type)
		{
			$this->errorOutput(MEMBER_TYPE_NOT_UNBIND);
		}
		else if (!$sType)
		{
			$this->errorOutput(NO_MEMBER);
		}

		$code = $this->input['verifycode'];
		if($type == 'shouji')
		{
			if(empty($code))
			{
				$this->errorOutput(MOBILE_NOT_VERIFY);
			}
			$verifycode = $this->mSmsServer->get_verifycode_info($platform_id, $code);
			if(!$verifycode)
			{
				$this->errorOutput(MOBILE_VERIFY_FAILED);
			}
		}
		else if ($type == 'email')
		{
			if(empty($code))
			{
				$this->errorOutput(EMAIL_VERIFY_NULL);
			}
			$memberverifycode = new member_verifycode();		
			if(! $memberverifycode->get_verifycode_info($platform_id,$code,1,1))
			{
				$this->errorOutput(EMAIL_VERIFY_FAILED);
			}
		}
		$condition = ' AND m.member_id = '.intval($member_id);
		$bindinfo = $this->mMember->get_bind_info($condition);
		if($bindinfo)
		{
			$sql = '';
			$delete_bind = array();
			foreach ($bindinfo as $bind_plate)
			{
				if($bind_plate['type'] == $type && $bind_plate['platform_id'] == $platform_id)
				{
					$delete_bind = $bind_plate;
					$sql = "DELETE FROM " . DB_PREFIX . 'member_bind WHERE member_id='.$member_id.' AND type="'.$type.'" AND platform_id="'.$platform_id.'"';
					if($bind_plate['is_primary'])
					{
						$this->errorOutput(PRIMARY_MEMBER_DATA);
					}
					if($type == 'shouji')
					{
						$this->mSmsServer->mobile_verifycode_delete($platform_id, $code);
					}
					else if ($type == 'email')
					{
						$memberverifycode->verifycode_delete($platform_id,$code,1,1);
					}
					break;
				}
			}
			if($sql)
			{
				$this->db->query($sql);
				$this->addItem($delete_bind);
			}
		}
		$this->output();
	}
	//登录之后修改手机绑定
	public function reset_mobile_bind()
	{
		$check_bind = new check_Bind();
		$identifier = 0;//多套会员系统标记
		$member_id = intval($this->user['user_id']);
		$new_mobile = $this->input['new_mobile'];
		$old_mobile = $this->input['old_mobile'];
		$code = $this->input['verifycode'];
		if(!$member_id)
		{
			$this->errorOutput(USER_NO_LOGIN);
		}
		//新手机确认
		if(empty($new_mobile))
		{
			$this->errorOutput(NEW_MOBILE_NOT_NUMBER);
		}
		elseif ($check_bind->checkmembernamereg($new_mobile,$identifier))
		{
			$this->errorOutput(MOBILE_REG_BIND);
		}
		elseif (empty($old_mobile)){
			$this->errorOutput(OLD_MOBILE_NOT_NUMBER);
		}
		$verifycode = $this->mSmsServer->get_verifycode_info($new_mobile, $code);
		if(!$verifycode)
		{
			$this->errorOutput(VERIFY_FAILED);
		}
		else
		{
			$this->mSmsServer->mobile_verifycode_delete($new_mobile, $code);
		}
		$where = ' AND member_id = '.$member_id . ' AND type ="shouji" AND platform_id="'.$old_mobile.'"';
		$sql = 'SELECT member_id,platform_id FROM '. DB_PREFIX .'member_bind WHERE 1 ' . $where;
		$bind = $this->db->query_first($sql);
		if(!$bind)
		{
			$this->errorOutput(MOBILE_NOT_BINDED);
		}
		$sql = 'UPDATE '.DB_PREFIX.'member_bind set nick_name="'.$new_mobile.'",platform_id="'.$new_mobile.'"  WHERE 1 ' . $where;
		$this->db->query($sql);
		//如果主表的name
		$sql = 'UPDATE '.DB_PREFIX.'member set member_name="'.$new_mobile.'" WHERE member_id = '.$member_id . ' AND member_name = "'.$old_mobile.'"';
		$this->db->query($sql);
		//修改主表mobile字段
		$sql = 'UPDATE '.DB_PREFIX.'member set mobile="'.$new_mobile.'" WHERE member_id = '.$member_id;
		$this->db->query($sql);
		$this->addItem(array('mobile'=>$new_mobile,'member_id'=>$member_id));
		$this->output();
	}
	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

}

$out = new unbindapi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>