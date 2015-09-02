<?php
/***************************************************************************
 * $Id: bind.php 46035 2015-06-04 06:42:42Z tandx $
 ***************************************************************************/
define('MOD_UNIQUEID','bind');//模块标识
require('./global.php');
class bindApi extends appCommonFrm
{
	private $mMember;
	private $mSmsServer;
	private $memberverifycode;
	public function __construct()
	{
		parent::__construct();

		require_once CUR_CONF_PATH . 'lib/member.class.php';
		$this->mMember = new member();

		require_once CUR_CONF_PATH . 'lib/sms_server.class.php';
		$this->mSmsServer = new smsServer();
		
		$this->Members = new members();
		$this->memberverifycode = new member_verifycode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 *  member_id 会员id
		platform_id 第三方平台会员id char
		nick_name 昵称
		type 会员类型
		type_name 会员类型名称
		avatar_url 头像地址
		bind_time 绑定时间
		bind_ip 绑定ip
	 * Enter description here ...
	 */
	public function bind()
	{
		$memberUpdataField = array();//主表修改字段
		$member_id	 = intval($this->user['user_id']);
		if(!$member_id)
		{
			$this->errorOutput(USER_NO_LOGIN);
		}
		$platform_id = trim($this->input['platform_id']);
		$password = $this->input['password']?trim($this->input['password']):'';
		$type	 	 = trim($this->input['type']);
		$platformInfo = $this->Members->get_platform_name($type);
        $identifierUserSystem = new identifierUserSystem();
        $identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
		if(in_array($type, array('m2o','uc'))||empty($platformInfo))
		{
			$this->errorOutput(BIND_MEMBER_TYPE_ERROR);
		}
		else if (!$platformInfo['status'])
		{
			$this->errorOutput(BIND_MEMBER_TYPE_CLOSE);
		}
		$type_name = $platformInfo['name'];
		$device_token = $this->Members->check_device_token(trim($this->input['device_token']));
		$udid = $this->Members->check_udid(trim($this->input['uuid']));  //唯一设备号
		if($device_token===0)
		{
			$this->errorOutput(ERROR_DEVICE_TOKEN);
		}
		if($udid===0)
		{
			$this->errorOutput(ERROR_UDID);
		}
		$avatar_url	 = trim($this->input['avatar_url']);
		$ip = hg_getip();
	    //验证会员是否存在
		$condition  = " AND m.member_id=" . $member_id;
        $left_join = 'LEFT JOIN ' . DB_PREFIX . 'member_bind as mb ON m.member_id=mb.member_id AND m.type=mb.type';
		$ret_member = $this->mMember->get_member_info($condition,'m.*,mb.nick_name',$left_join,0);
		$ret_member = $ret_member[0];
		if (empty($ret_member))
		{
			$this->errorOutput(NO_MEMBER);
		}
		$callback_sql = '';

        if (!empty($ret_member['nick_name']))
        {
            $nick_name = $ret_member['nick_name'];
        }
        else
        {
            $nick_name = $platform_id;
        }
        if (empty($avatar_url))
        {
            $avatar = array('host'=>'','dir'=>'','filepath'=>'','filename'=>'');
            if(is_serialized_string($ret_member['avatar']))
            {
                $avatar = unserialize($ret_member['avatar']);
            }
            $avatar_url = $avatar['host'].$avatar['dir'].$avatar['filepath'].$avatar['filename'];
        }
        else
        {
            $avatar_url =  trim($this->input['avatar_url']);
        }

        if(hg_check_email_format($platform_id))
		{
			$sql = 'SELECT platform_id FROM ' . DB_PREFIX . 'member_bind WHERE platform_id="'.$platform_id.'" AND identifier='.$identifier;
			$result = $this->db->query_first($sql);
			if($result)
			{
				$this->errorOutput(EMAIL_HAS_BINDED);
			}

			if(defined(BIND_EMAIL_NEED_VERIFYCODE))
            {
                $email_verifycode = trim($this->input['email_verifycode']);
                if (!$email_verifycode)
                {
                    $this->errorOutput(VERIFY_NULL);
                }
                if($this->memberverifycode->get_verifycode_info($platform_id, $email_verifycode,1,$action=1)){
                    //验证成功之后删除
                    $this->memberverifycode->verifycode_delete($platform_id, $email_verifycode,1,$action=1);
                }
                else {
                    $this->errorOutput(VERIFY_FAILED);
                }
            }

			$type = 'email';
			$type_name = '邮箱';
		}
		elseif(hg_verify_mobile($platform_id)) {

			$type = 'shouji';
			$type_name = '手机';
		}
		$need_password_type = array('shouji', 'm2o' , 'email');
		if(in_array($type, $need_password_type) && $password)
		{
			//随机串
			$salt = hg_generate_salt();	
			//密码md5
			$md5_password = md5(md5($password) . $salt);
			$memberUpdataField['password'] = $md5_password;
			$memberUpdataField['salt'] = $salt;
		}
		elseif(in_array($type, $need_password_type)&&empty($ret_member['password']))
		{
			$this->errorOutput(NO_PASSWORD);//如果绑定类型为手机，M2O，email，但是主表未设置密码，则需要设置密码
		}

		if (!$member_id)
		{
			$this->errorOutput(NO_MEMBER_ID);
		}

		if (!$platform_id)
		{
			$this->errorOutput(NO_EXTERNAL_MEMBER_ID);
		}

		if (!$nick_name)
		{
			$this->errorOutput(NO_NICKNAME);
		}

		if (!$type)
		{
			$this->errorOutput(NO_EXTERNAL_TYPE);
		}

		//验证手机验证码
		if ($type == 'shouji')
		{
			$mobile_verifycode = trim($this->input['mobile_verifycode']);
				
			if (!$mobile_verifycode)
			{
				 $this->errorOutput(MOBILE_NOT_VERIFY);
			}
				
			$mobile = $platform_id;
				
			//简单验证手机号格式
			if (!hg_verify_mobile($mobile))
			{
				$this->errorOutput(MOBILE_NUMBER_FORMAT_ERROR);
			}
			//验证码
			$verifycode = $this->mSmsServer->get_verifycode_info($mobile, $mobile_verifycode);
				
			if (empty($verifycode))
			{
				$this->errorOutput(VERIFY_FAILED);
			}
				
			//删除验证码
			$this->mSmsServer->mobile_verifycode_delete($mobile, $mobile_verifycode);
								
			if (TIMENOW > ($verifycode['create_time'] + VERIFYCODE_EXPIRED_TIME))
			{
				$this->errorOutput(VERIFY_EXPIRED);
			}

		}
		
		$condition = " AND mb.platform_id = '" . $platform_id . "' AND mb.type = '" . $type . "' AND mb.identifier=".$identifier;
		$_bind = $this->mMember->get_bind_info($condition);
		if($_bind[0]&&$member_id!=$_bind[0]['member_id'])
		{
			$this->errorOutput(ACCOUNT_BIND);//验证此账户类型是否已被其他用户绑定
		}
		$condition = " AND mb.member_id = '" . $member_id . "' AND mb.type = '" . $type . "' AND mb.identifier=".$identifier;
		$bind = $this->mMember->get_bind_info($condition);
		$bind = $bind[0];
		if($bind)
		{
			$this->errorOutput(BIND_TYPE_EXISTS);//强制用户解除已有该类型绑定，防止原先绑定信息未经验证被串改!
		}
		$avatar_array = $this->mMember->update_avatar($avatar_url, $bind,$member_id);
		if($avatar_array&&is_array($avatar_array))
		{
			$sql = 'UPDATE ' . DB_PREFIX . 'member SET avatar =\''.daddslashes(serialize($avatar_array)).'\' WHERE member_id='.intval($member_id);			
			$this->db->query($sql);			
		}
		
		$bind_data = array(
			'member_id'		=> $member_id,
			'platform_id'	=> $platform_id,
			'nick_name'		=> $nick_name,
			'type'			=> $type,
			'type_name'		=> $type_name,
			'avatar_url'	=> $avatar_url,
            'identifier'    => $identifier,
			'reg_device_token' => $device_token,
			'reg_udid'          => $udid,
		);
			
		if (empty($bind))	//未绑定
		{
			$checkBind = new check_Bind();
			$isUc = 0;
			$isUc = $checkBind->check_Bind($member_id,'uc');
			if(empty($isUc)){
				$isUc = $checkBind->check_uc($member_id);
				if($isUc)
				{
					$bind_data['inuc'] = $isUc;
				}
			}
		
			//新增绑定表
			$bind_data['bind_time'] = TIMENOW;
			$bind_data['bind_ip'] 	= $ip;
				
			$ret_bind = $this->mMember->bind_create($bind_data);
			if (empty($ret_bind))
			{
				$this->errorOutput(BIND_DATA_ADD_FAILED);
			}
		}
		else	
		{
			//更新绑定表
			$ret_bind = $this->mMember->bind_update($bind_data);
			if (empty($ret_bind))
			{
				$this->errorOutput(BIND_DATA_UPDATE_FAILED);
			}
		}
		if($type == 'shouji')//更新主表,因为如果传了密码,之前就修改过主表mobile就不需要重新修改.
		{
			$memberUpdataField['mobile'] = $platform_id;
		}
		elseif ($type =='email')
		{
			$memberUpdataField['email'] = $platform_id;
		}
		if($ret_member['type'] =='email' || $ret_member['type'] =='shouji')
		{
			$memberUpdataField['member_name'] = $platform_id;
		}
		$return = array(
			'member_id'	  => $member_id,
			'member_name' => in_array($ret_member['member_name'], array('m2o','uc'))?$ret_member['member_name']:$platform_id,
			'type'		  => $type,
			'nick_name'	  => $nick_name,
			'is_exist_password'=>$ret_member['password'] ? 1 : 0,
		);
		if($memberUpdataField && $this->mMember->update($memberUpdataField,array('member_id'=>$member_id)))
		{
		   if($this->settings['ucenter']['open'])
		   {
		   	 if($ret_member['type'] =='m2o' && $type == 'email')
		   	 {
		   	 	$this->mMember->uc_user_edit($ret_member['member_name'], '',$password,$platform_id,1);
		   	 }
		   }
		}
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 
	 * 用于本地状态正常，而UC未同步的帐号，重新同步至UC后，更改uc与本地帐号绑定关系问题
	 */
	public function uc_bind()
	{
		$member_id	 = intval($this->user['user_id']);
		$ucid = intval($this->input['ucid']);//UC_id
		$avatar_url	 = trim($this->input['avatar_url']);
		if (!$member_id)
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		if (!$ucid)
		{
			$this->errorOutput(NO_EXTERNAL_MEMBER_ID);
		}
		if(!$this->settings['ucenter']['open'])
		{
			$this->errorOutput(UC_IS_CLOSED);
		}
		include_once (UC_CLIENT_PATH . 'uc_client/client.php');
		$uc_info = uc_get_user($ucid,1);
		if($uc_info[1] != $this->user['user_name']||empty($uc_info))
		{
			$this->errorOutput('UC帐号错误');
		}
		$condition = " AND mb.member_id = " . $member_id;
		$_bind = $this->mMember->get_bind_info($condition);
		if(is_array($_bind))
		foreach ($_bind as $v)
		{
			if($v['type'] != 'uc' &&$v['inuc'] == '0')
			{
				$bind[] = $v;
			}
			elseif ($v['type'] == 'uc' && $v['inuc'] == '0')
			{
				$this->errorOutput('UC已存在，请勿重复绑定');
			}
		}
		$ret_bind = array();
		if(empty($bind))//如果全为空的话，那么可绑定的类型全部已被绑定完毕。
		{
			$this->errorOutput('UC已存在，请勿重复绑定');
		}
		elseif($bind&&is_array($bind))	//已绑定
		{	
			//更新绑定表
			foreach ($bind as $v)
			{
				$bind_data = array();
				if($v['type'] == 'm2o')
				{
					$bind_data = array(
					'platform_id' => $ucid,
					'avatar_url'	=> $avatar_url,
					'inuc' => $ucid,
					);
					$where = 'WHERE member_id = '.$member_id.' AND type = \'m2o\'';	
				}
				elseif ($v['type'] != 'm2o'&&$v['type']!='uc')
				{
					$bind_data = array(
					'avatar_url'	=> $avatar_url,
					'inuc' => $ucid,
					);
					$where = 'WHERE member_id = '.$member_id.' AND type = \''.$v['type'].'\'';	
				}
				if($bind_data)
				{
					$_ret_bind = $this->mMember->bind_update($bind_data,$where);
					$_ret_bind['member_name'] = $this->user['user_name'];
					$_ret_bind['member_id'] = $member_id;
					$ret_bind[] = $_ret_bind;
				}
				if (empty($ret_bind))
				{
					$this->errorOutput(BIND_DATA_UPDATE_FAILED);
				}
			}
		}
		if($ret_bind&&is_array($ret_bind))
		foreach ($ret_bind as $v)
		$this->addItem($v);
		else $this->addItem($ret_bind);
		$this->output();
	}
	/**
	 * 
	 * 补充新浪绑定，QQ绑定等第三方绑定信息为正常M2O账号 ...
	 * 目的是为了解决 新浪、QQ等第三方平台首次直接登陆系统后,资料信息不完善问题
	 */
	public function supplementaryBindInfo()
	{
		try {
		$identifierUserSystem = new identifierUserSystem();
		$identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
		$member_name = $this->input['member_name'];
		$nick_name = $this->input['nick_name'];
		if(empty($member_name))
		{
			$this->errorOutput(NO_MEMBER_NAME);
		}
		//如果是m2o注册类型屏蔽字检测
		if($this->settings['App_banword'])
		{
			include (ROOT_PATH.'lib/class/banword.class.php');
			$banword = new banword();
			$member_name_banword = $banword->exists($member_name);
			if ($member_name_banword && is_array($member_name_banword))
			{
				$this->errorOutput(MEMBER_NAME_INVALID);
			}
		}
		switch ($this->mMember->verify_member_name($member_name,$user_id,$identifier))
		{
			case -1 :
				$this->errorOutput(MEMBER_NAME_ILLEGAL);
				break;
			case -2 :
				$this->errorOutput(PROHIBITED_WORDS);
				break;
			case -3 :
				$this->errorOutput(UC_MEMBER_NAME_REGISTER);
				break;
			case -4 :
				$this->errorOutput(MEMBER_NAME_EXCEEDS_MAX);
				break;
			case -5 :
				$this->errorOutput(USERNAME_BELOW_MINIMUM);
				break;
			case -6 :
				$this->errorOutput(MEMBER_NAME_ERROR);
				break;
			case -7 :
				$this->errorOutput(MEMBER_NAME_REGISTER);
				break;	
			default:
				break;
		}

		$mobile_verifycode = trim($this->input['mobile_verifycode']);
		$email_verifycode = trim($this->input['email_verifycode']);
		$email = $this->input['email'];
		if(empty($email))
		{
			$this->errorOutput(NO_EMAIL);
		}
		$reg_mail = $this->Members->check_reg_mail($email,0,$identifier);
		if($reg_mail == -4) {
			$this->errorOutput(EMAIL_FORMAT_ERROR);
		} elseif($reg_mail == -5) {
			$this->errorOutput(EMAIL_NO_REGISTER);
		} elseif($reg_mail == -6) {
			$this->errorOutput(EMAIL_HAS_BINDED);
		}
		if($email&&isset($this->input['email_verifycode']))
		{
			
			if($this->memberverifycode->get_verifycode_info($email, $email_verifycode,1,$action=1)){
				//验证成功之后删除
				$this->memberverifycode->verifycode_delete($member_name, $email_verifycode,1,$action=1);
			}
			else {
				$this->errorOutput(VERIFY_FAILED);
			}
			
			$this->isemailverify = 1;
		}
		
		$mobile = $this->input['mobile'];
		//简单验证手机号格式
		if ($mobile&&!hg_verify_mobile($mobile))
		{
			$this->errorOutput(MOBILE_NUMBER_FORMAT_ERROR);
		}
		else if($mobile&&(isset($this->input['mobile_verifycode'])||defined('NO_VERIFY_MOBILEBIND')&&NO_VERIFY_MOBILEBIND))
		{
			$check_bind = new check_Bind();
			if($check_bind->checkmembernamereg($mobile, $identifier))
			{
				$this->errorOutput(MOBILE_REG_BIND);
			}
		}
		if($mobile&&isset($this->input['mobile_verifycode']))
		{
						//验证码
			$verifycode = $this->mSmsServer->get_verifycode_info($mobile, $mobile_verifycode);
			if (empty($verifycode))
			{
				$this->errorOutput(VERIFY_FAILED);
			}
				//删除验证码
			$this->mSmsServer->mobile_verifycode_delete($mobile, $mobile_verifycode);
			if (TIMENOW > ($verifycode['create_time'] + VERIFYCODE_EXPIRED_TIME))
			{
				$this->errorOutput(VERIFY_EXPIRED);
			}
			$this->ismobileverify = 1;
		}
		$password = $this->input['password'];
		
		$user_id = $this->user['user_id'];
		if(!$user_id)
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		$cond = ' AND member_id = '.$user_id;
		$memberInfo = $this->Members->get_member_info($cond);
		if(!$memberInfo)
		{
			$this->errorOutput(NO_MEMBER);
		}
		if($memberInfo['type']=='m2o'||$memberInfo['type']=='uc')//UC类型也不需要补充。
		{
			$this->errorOutput(UPDATEM2O);
		}
		$updateMemberInfo['member_id'] = $user_id;
		$updateMemberInfo['type'] = 'm2o';
		$updateMemberInfo['type_name'] = 'M2O';
		$updateMemberInfo['member_name'] = $member_name;
		if(empty($password))
		{
			$this->errorOutput(NO_PASSWORD);
		}
		$salt = hg_generate_salt();
		$updateMemberInfo['salt'] = $salt;
		$md5_password = md5(md5($password) . $salt);
		$updateMemberInfo['password'] = $md5_password;
		$email&&$updateMemberInfo['email'] = $email;
		$mobile&&$updateMemberInfo['mobile'] = $mobile;
		$this->mMember->update($updateMemberInfo);
		$membersql = new membersql();
		$this->mMember->bind_update(array('is_primary'=>0),$membersql->where(array('member_id'=>$memberInfo['member_id'],'type'=>$memberInfo['type'])));
		$platform_id = $user_id;
		$inuc = 0;
		if(!$identifier&&$this->settings['ucenter']['open'])
		{
			$register_data = array(
			'member_name' => $member_name,
			'password' => $password,
			'email' => $email,
			);
			$registerInfo = $this->mMember->uc_register($register_data);
			if($registerInfo['member_id']>0)
			$inuc = $platform_id = $registerInfo['member_id'];
		}
		//M2O绑定关系
		$bind_data = array(
			'member_id'		=> $user_id,
			'platform_id'	=> $platform_id,
			'nick_name'		=> $nick_name,
			'type'			=> 'm2o',
			'type_name'		=> 'M2O',
			'bind_time'		=> TIMENOW,
			'bind_ip'		=> hg_getip(),
			'inuc'			=> $inuc,
			'is_primary'	=> 1,
			'identifier'	=> $identifier,
			'reg_device_token' => 'www',
			'reg_udid'      => $udid,
		);
		$ret_bind = $this->mMember->bind_create($bind_data);
		
		//如果注册时填写邮箱则可以同时入绑定表
		if($email)
		{
			if($this->isemailverify||defined('NO_VERIFY_EMAILBIND')&&NO_VERIFY_EMAILBIND)
			{
				$_bind_data = $bind_data;
				$_bind_data['platform_id'] = $email;
				$_bind_data['is_primary'] = 0;
				$_bind_data['type']  = 'email';
				$_bind_data['type_name']  = '邮箱';
				$_ret_bind = $this->mMember->bind_create($_bind_data);
				if (empty($_ret_bind))
				{
					$this->errorOutput(BIND_DATA_ADD_FAILED);
				}
				unset($_bind_data,$_ret_bind);
			}

		}
		if($mobile)
		{
			if($this->ismobileverify||defined('NO_VERIFY_MOBILEBIND')&&NO_VERIFY_MOBILEBIND)
			{
				$_bind_data = $bind_data;
				$_bind_data['platform_id'] = $mobile;
				$_bind_data['is_primary'] = 0;
				$_bind_data['type']  = 'shouji';
				$_bind_data['type_name']  = '手机';
				$_ret_bind = $this->mMember->bind_create($_bind_data);
				if (empty($_ret_bind))
				{
					$this->errorOutput(BIND_DATA_ADD_FAILED);
				}
				unset($_bind_data,$_ret_bind);
			}
		}
		if($inuc)//所有绑定类型补充UCID，如果UCID存在的话
		{
			$_updateBind = array(
			'inuc'	=>  $inuc,
			);
			$this->mMember->bind_update($_updateBind,' WHERE member_id = '.$user_id);
		}
		$this->addItem($bind_data);
		$this->output();
		}
		catch (Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}
	
	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

}

$out = new bindApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'bind';
}
$out->$action();
?>