<?php
/***************************************************************************
 * $Id: member_update.php 46022 2015-06-03 12:53:25Z tandx $
 ***************************************************************************/
define('MOD_UNIQUEID','members');//模块标识
require('./global.php');
class memberUpdateApi extends appCommonFrm
{
	private $mMember;
	private $mMemberExtensionField;
	private $mMemberInfo;
	private $im;
	public function __construct()
	{
		parent::__construct();
		$this->Members = new members();
		require_once CUR_CONF_PATH . 'lib/member.class.php';
		$this->mMember = new member();

		require_once CUR_CONF_PATH . 'lib/member_info.class.php';
		$this->mMemberInfo = new memberInfo();
		$this->mMemberExtensionField = new memberExtensionField();

		require_once CUR_CONF_PATH . 'lib/sms_server.class.php';
		$this->mSmsServer = new smsServer();
		
		require_once(ROOT_PATH.'lib/class/im.class.php');
        $this->im = new im();
		
		$this->memberverifycode = new member_verifycode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 会员编辑
	 * $member_id
	 * $member_name
	 * $password
	 * $old_password
	 * $signature
	 * $avatar file
	 * $member_info json
	 */
	public function edit()
	{
		$this->input = hg_mermber2members_compatible(array('new_password'=>'password'),$this->input,false);
		$member_id 		 = intval($this->user['user_id']);
        $app_id = $this->input['app_id']; //应用id
		if (!$member_id)
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		//检测该会员是否存在
		$condition = " AND m.member_id = '" . $member_id . "' ";
		$field 	   = 'm.member_id , m.member_name, m.password, m.salt, m.avatar, m.type,mb.nick_name';
		$_member_info = $this->mMember->get_member_info($condition, $field,' LEFT JOIN '.DB_PREFIX.'member_bind as mb ON mb.member_id = m.member_id');
		$member_info = array();
		$member_info = $_member_info[0];
		if (empty($member_info))
		{
			$this->errorOutput(NO_MEMBER);
		}
		$this->ReplaceCheck($member_info);//判定是否有值不更新
		$member_name  = trim($this->input['member_name']);
		if($this->input['nick_name'])
		{
			$nick_name = trim($this->input['nick_name']);
		}
		if (!$nick_name && $this->settings['memberNameToNickName'])
		{
			$nick_name = $member_name;
			unset($member_name,$this->input['member_name']);
		}
		$password 		 = trim($this->input['password'])?trim($this->input['password']):'';
		$old_password 	 = trim($this->input['old_password']);
		$signature		 = trim($this->input['signature']);
		$mobile		 	 = trim($this->input['mobile']);
		$email 			 = trim($this->input['email'])?trim($this->input['email']):'';
		$verifycode		 = trim($this->input['verifycode']);
		$device_token = $this->Members->check_device_token(trim($this->input['device_token']));
		$udid = $this->Members->check_udid(trim($this->input['uuid'])); //检查唯一设备号
		if($device_token===0)
		{
			$this->errorOutput(ERROR_DEVICE_TOKEN);
		}
		if($udid===0)
		{
			$this->errorOutput(ERROR_UDID);
		}
		//会员名、更新时间
		$data = array(
			'member_id'		=> $member_id,
			'update_time'	=> TIMENOW,
		);
		$check_bind = new check_Bind();
		if($mobile)
		{
			if(!hg_verify_mobile($mobile))
			{
				$this->errorOutput(MOBILE_NUMBER_FORMAT_ERROR);
			}
			else if($check_bind->checkMobileBind($member_id) != $mobile)
			{
				$this->errorOutput(MOBILE_BIND_NOT_UPDATE);
			}
			$data['mobile'] = $mobile;
		}
		if($email)
		{
			if(!hg_check_email_format($email))
			{
				$this->errorOutput(EMAIL_FORMAT_ERROR);
			}
			elseif ($check_bind->checkEmailBind($member_id) == $email )
			{
				$this->errorOutput(EMAIL_BIND_NOT_UPDATE);
			}
			$data['email'] = $email;
		}
		if($member_name&&(!$this->mMember->isMemberNameUpdate($member_id)))
		{
			$this->errorOutput(NOT_EDIT_MEMBERNAME);
		}
		else if ($member_name)
		{
			$member_name_exists = $this->mMember->member_name_exists($member_name, $member_id);
			if (!empty($member_name_exists))
			{
				$this->errorOutput(MEMBER_NAME_EXISTS);
			}

			$data['member_name'] = $member_name;
		}

		if($this->settings['App_banword'])
		{
			include (ROOT_PATH.'lib/class/banword.class.php');
			$banword = new banword();
			$signature_banword = $banword->exists($signature);//个性签名检测
			if ($signature_banword && is_array($signature_banword))
			{
				$this->errorOutput(SIGNATURE_INVALID);
			}
			$nick_name_banword = $banword->exists($nick_name);//个性签名检测
			if ($nick_name_banword && is_array($nick_name_banword))
			{
				$this->errorOutput(NICKNAME_ILLEGAL);
			}
		}
		//如果是m2o注册类型屏蔽字检测
		if(in_array($member_info['type'], array('m2o','uc'))&&$this->settings['App_banword'])
		{

			$member_name_banword = $banword->exists($member_name);
			if ($member_name_banword && is_array($member_name_banword))
			{
				//$this->errorOutput(var_export($banword,1));
				$this->errorOutput(MEMBER_NAME_INVALID);
			}
		}
		$_old_password ='';//用户原始密码
		$is_no_old_password = 1;//是否需要旧密码修改资料,不涉及用户密码操作直接修改
		//密码、随机串
		if ((isset($this->input['password']) && $password) || (isset($this->input['verifycode']) && $verifycode))
		{
			if(!$old_password && !$verifycode)
			{
				$this->errorOutput(OLD_PASSWORD_ERROR);
			}
			//根据原始密码修改密码
			if($old_password)
			{
				$is_no_old_password = 0;
				$_old_password = $old_password;//用作uc更改密码
				$old_password = md5(md5($old_password) . $member_info['salt']);
				if ($old_password != $member_info['password'])
				{
					$this->errorOutput(OLD_PASSWORD_ERROR);
				}
			}

			//根据验证码修改密码
			if($verifycode)//此段代码不合理，因为忘记密码只能通过找回密码接口进行，而非在资料修改里进行，待删除
			{
				if($this->mSmsServer->get_verifycode_info($member_name, $verifycode))
				{
					//验证成功之后删除
					$this->mSmsServer->mobile_verifycode_delete($member_name, $verifycode);
				}
				else
				{
					$this->errorOutput(VERIFY_FAILED);
				}
			}
		}
		/**
		 * 同步UC信息
		 */
		if($this->settings['ucenter']['open']&&in_array($member_info['type'], array('m2o','uc'))){
			$_member_name = $this->user['user_name'];
			$is_password = $this->mMember->uc_user_edit($_member_name,$_old_password, $password, $email,$is_no_old_password);
			if($is_password<0)
			{
				if($is_password == -1)
				{
					$this->errorOutput(OLD_PASSWORD_ERROR);
				}
				else if($is_password == -4) {
					$this->errorOutput(EMAIL_FORMAT_ERROR);
				} elseif($is_password == -5) {
					$this->errorOutput(EMAIL_NO_REGISTER);
				} elseif($is_password == -6) {
					$this->errorOutput(EMAIL_HAS_BINDED);
				}
			}
		}
		if ($password)
		{
			$salt = hg_generate_salt();
			$md5_password = md5(md5($password) . $salt);
			$data['password'] = $md5_password;
			$data['salt'] 	  = $salt;
		}
		//个性签名
		if (isset($this->input['signature']))
		{
			$data['signature'] = $signature;
		}

		//头像
		if ($_FILES['avatar']['tmp_name'])
		{
			$avatar = $_FILES['avatar'];
		}
		elseif ($this->input['avatar'])
		{
			$avatar = $this->input['avatar'];
		}
		
		//背景图
		if ($_FILES['background']['tmp_name'])
		{
		    $background = $_FILES['background'];
		}
		elseif ($this->input['background'])
		{
		    $background = $this->input['background'];
		}
		$_avatar = array();
		$_background = array();
		//编辑头像
		if (!empty($avatar)&&is_array($avatar))
		{
			if($this->input['version'] == CLIENT_VERSION)
			{
				$avatar['name'] .= '.png';
			}
			$_avatar = $this->mMember->add_material($avatar, $member_id);
		}
		elseif ($avatar&&is_string($avatar)&&is_url($avatar))
		{
			$_avatar = $this->mMember->update_avatar($avatar, array(),$member_id,true);
		}
		//叮当更换头像时刷新im用户信息
		if($_avatar && $this->input['platformMark'] && $this->input['platformMark'] == 'dingdone' && $app_id)
		{
		    $param = array(
		            'app_id' => $app_id,
		            'member_id' => $member_id,
		            'member_name' => $member_info['member_name'],
		    );
		    $param['avatar_url'] = $_avatar['host'].$_avatar['dir'].$_avatar['filepath'].$_avatar['filename'];
		    $imInfo = $this->refreshImInfo($param);
		}
		//编辑背景图
		if (!empty($background) && is_array($background))
		{
		    $_background = $this->mMember->add_material($background, $member_id);
		}
		if (!empty($_avatar))
		{
			$data['avatar'] = serialize($_avatar);
		}
		$bind_info = array();
		if($nick_name)
		{
			$bind_info = array('nick_name' => $nick_name);
		}
		if($_background)
		{
		    $bind_info['background'] = serialize($_background);
		}
		$copywriting_credit = $this->editCreditRules($member_id,$data,$bind_info);//判断积分规则
		//会员数据入库
		$ret = $this->mMember->update($data);
		if($bind_info)
		$this->mMember->bind_update($bind_info,'WHERE member_id = \''.$member_id.'\'');
		if (!$ret['member_id'])
		{
			$this->errorOutput(EDIT_FAILED);
		}
		
		//编辑扩展信息
		if($this->input['platformMark'] && $this->input['platformMark'] == 'dingdone' && $app_id)
		{
		    //为叮当注册根据app配置不同的扩展信息
		    $extension = $this->mMemberInfo->extension_editByApp($member_id, $this->input['member_info'], $app_id, $_FILES);
		}
		else 
		{
    		$extension = $this->mMemberInfo->extension_edit($member_id, $this->input['member_info'],$_FILES);
		}

		//会员痕迹
		$member_trace_data = array(
			'member_id'		=> $member_id,
			'member_name'	=> $member_name?$member_name:$member_info['member_name'],
			'content_id'	=> $member_id,
			'title'			=> $member_name?$member_name:$member_info['member_name'],
			'type'			=> 'editmember',
			'op_type'		=> '修改会员资料',
			'appid'			=> $this->user['appid'],
			'appname'		=> $this->user['display_name'],
			'create_time'	=> TIMENOW,
			'ip'			=> hg_getip(),
			'device_token'	=> $device_token,
			'udid'          => $udid,
		);
		$this->mMember->member_trace_create($member_trace_data);

		$return = array(
			'member_id'		=> $member_id,
			'member_name'	=> !$member_name ? $member_info['member_name'] : $member_name,
			'nick_name'	=> !$nick_name ? $member_info['nick_name'] : $nick_name,
			'type'			=> $member_info['type'],
			'avatar'		=> !$_avatar ? $member_info['avatar'] : $_avatar,
			'access_token'	=> $this->user['token'],
			'update_avatar'	=> $avatar ? 1 : 0,
			'copywriting_credit' => $copywriting_credit,
		    'background'    => $_background ? $_background : array(), 
		    'email' => $email ? $email : '',       
		);
	    if (isset($this->input['signature']))
		{
			$return['signature'] = $signature;
		}

        //获取扩展信息
		if ($app_id)
		{
            $extension = $this->getExtensionInfo($member_id,$app_id);
		    $return['extension'] = $extension;
		}
        //获取会员基本信息返回
        $condition = '';
        $condition = " AND m.member_id = '" . $member_id . "' AND mb.is_primary=1";
        $field 	   = 'm.member_id , m.member_name, m.signature, m.email,m.mobile, mb.background, m.salt, m.avatar, m.type,mb.nick_name';
        $memberBaseInfo = $this->mMember->get_member_info($condition, $field,' LEFT JOIN '.DB_PREFIX.'member_bind as mb ON mb.member_id = m.member_id');
        if(!empty($memberBaseInfo[0]))
        {
            foreach($memberBaseInfo[0] as $k=>$v)
            {
                $return[$k] = $v;
            }
        }

		if($this->input['version'] == CLIENT_VERSION)
		{
			//头像
			if($this->input['m_avatar'])
			{
				$return = $return['avatar'];
			}
			//昵称
			if($this->input['m_name'])
			{
				if($this->input['appid'] == 7 && $this->input['appkey']=='upnKAycZKVw4D7QSXH7D8uFrFOpRQyXb')
				{
					$return = 'success';
				}
				else
				{
					$return = array(
					'nick_name'=> $return['member_name'],
					'update_time'=>	'',
					'member_name'=>	$return['member_name'],
					'member_id'	=> $return['member_id'],
					);
				}
			}
			//密码
			if($this->input['m_password'])
			{
				$return = array('member_id'=>$return['member_id']);
			}
			//邮箱
			if($this->input['m_mail'] || $this->input['m_mobile'])
			{
				$return = "success";
			}
		}
		$this->addItem($return);
		$this->output();
	}



	private function ReplaceCheck($member_info=array(),$extension_info=array())
	{
		$isReplace = (int)$this->input['isReplace'];
		if(empty($isReplace)){
			return array();
		}
		$member_id = intval($this->user['user_id']);
		if(empty($member_info)&&$member_id)
		{
			$cond = ' AND m.member_id = '.$member_id;
			$field = 'm.avatar,m.mobile,m.email,mb.nick_name';
			$leftjoin=' LEFT JOIN '.DB_PREFIX.'member_bind as mb ON mb.member_id = m.member_id';
			$member_info = $this->Members->get_member_info($cond,$field,$leftjoin);
		}
		if($member_info)
		{
			if(is_array($this->settings['member_base_info']))
			foreach ($this->settings['member_base_info'] as $k => $v)
			{
				if($v['field'] != 'avatar')
				{
					if($this->input[$v['field']]&&$member_info[$v['field']])
					{
						unset($this->input[$v['field']]);
					}
				}
				elseif($v['field'] == 'avatar'){
					if($this->input[$v['field']]&&$member_info[$v['field']])
					{
						unset($this->input[$v['field']]);
					}
					if($_FILES[$v['field']]&&$member_info[$v['field']])
					{
						unset($_FILES[$v['field']]);
					}
				}
			}
		}
		else return array();
		if(empty($extension_info)&&$member_id)
		{
			$_extension_info = $this->mMemberInfo->show(" AND member_id = " . $member_id);
			$extension_info = $this->mMemberInfo->extendDataProcess($_extension_info,0);
		}
		if(is_array($extension_info))
		{
			foreach ($extension_info as $k=>$v)
			{
				if($v['type'] != 'img')
				{
					if($this->input['member_info'][$k]&&$extension_info[$k]['value'])
					{
						unset($this->input['member_info'][$k]);
					}
				}
				elseif($v['type'] == 'img'){
					if($this->input['member_info'][$k]&&$extension_info[$k]['value'])
					{
						unset($this->input['member_info'][$k]);
					}
					if($_FILES[$k]&&$extension_info[$k]['value'])
					{
						unset($_FILES[$k]);
					}
				}
			}
		}
		else return array();
	}
	private function editCreditRules($member_id,$_new_member_info = array(),$bind_info=array())
	{
		$creditRule = array();
		if($member_id)
		{
			$condition = 'AND m.member_id = \''.$member_id.'\'';
			$member_info = $this->Members->get_member_info($condition,'m.member_name,m.avatar,mb.nick_name','LEFT join '.DB_PREFIX.'member_bind mb ON mb.member_id = m .member_id ');
			if($_new_member_info['member_name']&&$_new_member_info['member_name']!=$member_info['member_name'])
			{
				$reCreditsRule = $this->Members->credits_rule('members_editMemberName',$member_id,$coef=1,$update=1,APP_UNIQUEID);
				$creditRule[] = $this->CreditRulesError($reCreditsRule);
			}
			if($_new_member_info['avatar']&&empty($member_info['avatar']))
			{
				$reCreditsRule = $this->Members->credits_rule('members_editAvatar',$member_id,$coef=1,$update=1,APP_UNIQUEID);
				$creditRule[] = $this->CreditRulesError($reCreditsRule);
			}
			if($bind_info['nick_name']&&$bind_info['nick_name']!=$member_info['nick_name'])
			{
				$reCreditsRule = $this->Members->credits_rule('members_editNickName',$member_id,$coef=1,$update=1,APP_UNIQUEID);
				$creditRule[] = $this->CreditRulesError($reCreditsRule);
			}
		}
		return copywriting_credit($creditRule);
	}
	private function CreditRulesError($reCreditsRule = 0)
	{
		if($reCreditsRule==-6)
		{
			$this->errorOutput(NO_CREDIT_ERROR);
		}
		return $reCreditsRule;
	}

	/**
	 *
	 * 找回密码验证方法（支持验证输入的手机号是否已经绑定） ...
	 */
	public function reSetPasswordUser()
	{
		$type 		 = isset($this->input['type'])?intval($this->input['type']):-1;//找回类型
        $identifierUserSystem = new identifierUserSystem();
        $identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
		$memberId = 0;
		$isEmail = 0;
		$isMobile = 0;
		if($memberName = trimall($this->input['member_name']))
		{
			if(hg_check_email_format($memberName))
			{
				$memberId = $this->Members->get_member_id($memberName,false,false,'email',$identifier);
				if($memberId)
				{
					$isEmail  = 1;
					$platform_id = $memberName;
				}
			}
			elseif(hg_verify_mobile($memberName))
			{
				$memberId = $this->Members->get_member_id($memberName,false,false,'shouji',$identifier);
				if($memberId)
				{
					$isMobile  = 1;
					$platform_id = $memberName;
				}
			}
			if(!$memberId)
			{
				$memberId = $this->Members->get_member_id($memberName,false,false,'m2o',$identifier);
			}
			if(!$memberId)
			{
				$memberId = $this->Members->get_member_id($memberName,false,false,'uc',$identifier);
			}
			if(!$memberId)
			{
				$this->errorOutput(NO_MEMBER);
			}
			if($type==1)
			{
				if(!$isEmail)
				{
					if($email = trimall($this->input['email']))
					{
						$checkBind = new check_Bind();
						$platform_id = $checkBind->check_Bind($memberId,'email');
						if($platform_id&&$platform_id!=$email)
						{
							$this->errorOutput(EMAIL_BIND_ACCOUNT_ERROR);
						}else if(!$platform_id) {
							$this->errorOutput(EMAIL_NO_BIND_ACCOUNT);
						}
					}
					else {
						$this->errorOutput(EMAIL_INPUT_BIND_ACCOUNT);
					}
				}
			}
			elseif($type == 0) {
				if(!$isMobile)
				{
					if($mobile = trimall($this->input['mobile']))
					{
						$checkBind = new check_Bind();
						$platform_id = $checkBind->check_Bind($memberId,'shouji');
						if($platform_id&&$platform_id!=$mobile)
						{
							$this->errorOutput(MOBILE_BIND_ACCOUNT_ERROR);
						}elseif(empty($platform_id)) {
							$this->errorOutput(MOBILE_NO_BIND_ACCOUNT);
						}
					}
					else {
						$this->errorOutput(MOBILE_INPUT_BIND_ACCOUNT);
					}
				}
			}
			else {
				$this->errorOutput(REPASSWORD_TYPE_ERROR);
			}

			$this->input['member_name'] = $platform_id;
			$this->reset_password();
		}
		else $this->errorOutput(NO_MEMBER_NAME);

	}
	//通过验证码重置密码
	public function reset_password()
	{
		$this->check_verifycode();
		$verifycode		 = trim($this->input['verifycode']);
		$member_name 	 = trim($this->input['member_name']);
		$password 		 = trim($this->input['password']);
		$type 		 = isset($this->input['type'])?intval($this->input['type']):-1;//验证码类型
        $identifierUserSystem = new identifierUserSystem();
        $identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
		if(!$verifycode)
		{
			$this->errorOutput(VERIFY_NULL);
		}
		if(!$password)
		{
			$this->errorOutput(NO_NEW_PASSWORD);
		}
		if($type=='-1'&&hg_check_email_format($member_name))
		{
			$member_type='email';
			$type = 1;
		}
		elseif($type=='-1'&&hg_verify_mobile($member_name))
		{
			$member_type='shouji';
			$type = 0;
		}
		else if($type==0)
		{
			$member_type='shouji';
		}
		else if($type==1)
		{
			$member_type='email';
		}
		$condition = " AND platform_id = '" . $member_name . "' AND mb.type='$member_type' AND mb.identifier=".$identifier."";
		$field 	   = 'mb.member_id,platform_id,mb.type';

		$bind_info = $this->mMember->get_bind_info($condition, $field);

		$bind_info = $bind_info[0];
		if (empty($bind_info))
		{
			$this->errorOutput(NO_MEMBER);
		}
		$data = array();
		$data['member_id'] = $bind_info['member_id'];
		//根据验证码修改密码、
		if(!$type)//根据手机重置
		{
			if($this->mSmsServer->get_verifycode_info($member_name, $verifycode))
			{
				//验证成功之后删除
				$this->mSmsServer->mobile_verifycode_delete($member_name, $verifycode);
				if($this->settings['ucenter']['open']){
					$_member_name = $member_name;
					$is_password = $this->mMember->uc_user_edit($_member_name, '', $password, '',1);
				}
				if ($password&&($is_password>=0||!$this->settings['ucenter']['open']))
				{
					$salt = hg_generate_salt();
					$data['salt'] 	  = $salt;
					$md5_password = md5(md5($password) . $salt);
					$data['password'] = $md5_password;
				}
				elseif($password&&($is_password<0&&$this->settings['ucenter']['open']))
				{
					$this->errorOutput('UC密码同步失败');
				}
				if($this->mMember->update($data))
				{
					$bind_info['status'] = 1;
					$this->addItem($bind_info);
					$this->output();
				}
					
			}
			else
			{
				$this->errorOutput(MOBILE_VERIFY_FAILED);
			}
		}//根据邮箱重置
		else
		{
			if($this->memberverifycode->get_verifycode_info($member_name, $verifycode,$type,$action=1))
			{
				//验证成功之后删除
				$this->memberverifycode->verifycode_delete($member_name, $verifycode,$type,$action=1);
				if($this->settings['ucenter']['open']){
					$_member_name = $member_name;
					$is_password = $this->mMember->uc_user_edit($_member_name, '', $password, '',1);
				}
				if ($password&&($is_password>0||!$this->settings['ucenter']['open']))
				{
					$salt = hg_generate_salt();
					$data['salt'] 	  = $salt;
					$md5_password = md5(md5($password) . $salt);
					$data['password'] = $md5_password;
				}
				elseif($password&&($is_password<0&&$this->settings['ucenter']['open']))
				{
					$this->errorOutput('UC密码同步失败');
				}
				if($this->mMember->update($data))
				{
					$bind_info['status'] = 1;
					$this->addItem($bind_info);
					$this->output();
				}
					
			}
			else
			{
				$this->errorOutput(EMAIL_VERIFY_FAILED);
			}
		}
	}

	//通过验证码才可以修改手机号 主表的mobile，其它接口以满足此接口功能，此接口新版本客户端请勿使用
	public function resetting_mobile()
	{
		$verifycode		 = trim($this->input['verifycode']);
		$new_mobile 	 = trim($this->input['new_mobile']);
		$member_id 		 = intval($this->user['user_id']);
		if(!$member_id)
		{
			$this->errorOutput(USER_NO_LOGIN);
		}
		if(empty($new_mobile))
		{
			$this->errorOutput(NEW_MOBILE_NOT_NUMBER);
		}
		if($mobileStatus = $this->mMember->checkMobile($new_mobile,$member_id))
		{
			if($mobileStatus == 1)
			{
				$this->errorOutput(MOBILE_USED);
			}
			elseif ($mobileStatus == 2)
			{
				$this->errorOutput(MOBILE_NEW_OLD_SAME);
			}
			elseif ($mobileStatus == -1)
			{
				$this->errorOutput(MOBILE_NUMBER_FORMAT_ERROR);
			}
		}
		if($this->mSmsServer->get_verifycode_info($new_mobile, $verifycode))
		{
			//验证成功之后删除
			$this->mSmsServer->mobile_verifycode_delete($new_mobile, $verifycode);
			$sql = 'UPDATE ' . DB_PREFIX .'member SET mobile = "'.$new_mobile.'" WHERE member_id = '.$member_id;
			$this->db->query($sql);
			$this->addItem(array('member_id'=>$member_id, 'new_mobile'=>$new_mobile));
			$this->output();
		}
		else
		{
			$this->errorOutput(VERIFY_FAILED);
		}
	}

	private function check_verifycode()
	{
		/*********** 验证码 ***********/
		include ROOT_PATH . 'lib/class/verifycode.class.php';
		$mVerifyCode = new verifyCode();
		if(defined('IS_RESETPASSWORD_VERIFYCODE')&&IS_RESETPASSWORD_VERIFYCODE&&$this->settings['App_verifycode']&&empty($this->input['is_mobile_verifycode']))//is_mobile_client 控制是否需要验证码,需要则不需要传此参数.不需要则传,在移动APP里设置为自动传,禁止用手机端传,容易被捕获
		{
			$code = trim($this->input['verify_code']); //验证码
			$session_id = $this->input['session_id']; //标识
			if(!$code)
			{
				$this->errorOutput(NO_VERIFY_CODE);
			}
			if(!$session_id)
			{
				$this->errorOutput(NO_SESSION_ID);
			}
			$check_result = $mVerifyCode->check_verify_code($code, $session_id);  //验证验证码
			if($check_result != 'SUCCESS')
			{
				$this->errorOutput(VERIFY_FAILED);
			}
		}
		/***************************/
	}
	
	/**
	 * 请求融云token
	 */
	private function refreshImInfo($data = array())
	{
        if(!$data || !$data['app_id'] || !$data['member_id'] || !$data['member_name'] || !$data['avatar_url'])
        {
           return array();
        }
        $param = array(
               'userId' => $data['member_id'],
               'userName' => $data['member_name'],
               'app_id' => $data['app_id'],
               'avatar' => $data['avatar_url']  
        );
        $info = $this->im->refreshImInfo($param);
        
	    return $info; 
	}

    private function getExtensionInfo($member_id,$app_id = 0)
    {
        $extension = array();
        if($member_id)
        {
            //扩展信息
            $condition = " AND member_id = " . $member_id;
            $member_info = $this->mMemberInfo->show($condition);
            //可以根据分类id和分类字段分别查询，同时传值只有一个有效，分类id具有更高优先级
            $condition = '';
            //根据分类id查询
            if(isset($this->input['extension_sort_id']) && !empty($this->input['extension_sort_id']))
            {
                $condition = " AND field.extension_sort_id IN (" . trim($this->input['extension_sort_id']) . ")";
            }
            //根据分类字段查询
            if(empty($condition))
            {
                if(isset($this->input['extension_sort']) && !empty($this->input['extension_sort']))
                {
                    $extension_sort	=	trim($this->input['extension_sort']);
                    $condition = " AND sort.extension_sort IN ('" . $extension_sort ."')";

                }
            }

            if(intval($app_id))
            {
                $extension = $this->mMemberInfo->extendDataProcessByApp($member_info,1,$app_id);
            }
            else
            {
                $extension = $this->mMemberInfo->extendDataProcess($member_info,1,$condition);
            }
        }
        return $extension;
    }

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

}

$out = new memberUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>