<?php
/***************************************************************************
 * $Id: register.php 46330 2015-06-23 08:33:52Z tandx $
 ***************************************************************************/
define('MOD_UNIQUEID','register');//模块标识
require('./global.php');
class_exists('member') or require CUR_CONF_PATH . 'lib/member.class.php';
class_exists('memberInfo') or require CUR_CONF_PATH . 'lib/member_info.class.php';
class_exists('smsServer') or require CUR_CONF_PATH . 'lib/sms_server.class.php';
class_exists('memberBlacklist') or require CUR_CONF_PATH . 'lib/memberblacklist.class.php';
class registerApi extends appCommonFrm
{
	private $mMember;
	private $mSmsServer;
	private $memberName = '';
	private $type;//经过系统修改的TYPE
	private $oldtype;//用户传的TYPE
	private $memberverifycode;
	private $ismobileverify;
	private $isemailverify;
	public function __construct()
	{
		parent::__construct();

		$this->mMember = new member();
		$this->mSmsServer = new smsServer();
		$this->mMemberInfo = new memberInfo();
		$this->Members = new members();
		$this->memberverifycode = new member_verifycode();
		$this->Blacklist = new memberblacklist();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 会员注册
	 *  member_id 会员id
		member_name 会员名
		password 密码
		salt 随机数
		type 会员类型
		type_name 会员类型名
		avatar 头像
		signature 个性签名
		appid 应用id
		appname 应用名
		create_time 注册时间
		update_time 更新时间
		ip 注册ip
	 *
	 * $appid
	 * $appkey
	 * $callback
	 *
	 * $mobile_verifycode
	 *
	 * 绑定表
	 *  member_id 会员id
		platform_id 第三方平台会员id char
		nick_name 昵称
		type 会员类型
		type_name 会员类型名称
		avatar_url 头像地址
		bind_time 绑定时间
		bind_ip 绑定ip
	 *
	 * 返回
	 * member_id
	 * member_name
	 * type
	 * avatar
	 * access_token
	 */

	public function register()
	{
		try{
			$this->check_verifycode();//验证码
			$this->oldtype = $this->type = trim($this->input['type']);
			$member_name = $this->checkRegMemberName();
			$this->checkRegType();
			$this->checkRegMemberNameError();
			$password 	 = trim($this->input['password']);
			$identifierUserSystem = new identifierUserSystem();
			$identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
			if(empty($this->type))
			{
				$this->errorOutput(NO_MEMBER_TYPE);
			}
			$platformInfo = $this->Members->get_platform_name($this->type);
			if(empty($platformInfo))
			{
				$this->errorOutput(REG_MEMBER_TYPE_ERROR);
			}
			else if (!$platformInfo['status'])
			{
				$this->errorOutput(REG_MEMBER_TYPE_CLOSE);
			}
			$type_name = $platformInfo['name'];
			$signature 	 = trim($this->input['signature']);
			$ip			 = hg_getip();
			$appid  = intval($this->input['appid']);
			$appkey = trim($this->input['appkey']);
			$platform_id = '';
			$mobile_verifycode = trim($this->input['mobile_verifycode']);
			$email 		 = trim($this->input['email']);
			$reg_mail = $this->Members->check_reg_mail($email,0,$identifier);
			if($reg_mail == -4) {
				$this->errorOutput(EMAIL_FORMAT_ERROR);
			}
			elseif($reg_mail == -5) {
				$this->errorOutput(EMAIL_NO_REGISTER);
			}
			elseif($reg_mail == -6)
			{
				$this->errorOutput(EMAIL_HAS_BINDED);
			}
			$this->type == 'email' && $this->checkEmailVerifyCode($member_name);
			$this->type != 'email' && $email && $this->checkEmailVerifyCode($email);
			$_mobile = trim($this->input['mobile']);
			//简单验证手机号格式
			if ($_mobile&&!hg_verify_mobile($_mobile))
			{
				$this->errorOutput(MOBILE_NUMBER_FORMAT_ERROR);
			}
			else if($_mobile&&(isset($this->input['mobile_verifycode'])||defined('NO_VERIFY_MOBILEBIND')&&NO_VERIFY_MOBILEBIND))
			{
				$check_bind = new check_Bind();
				if($check_bind->checkmembernamereg($_mobile, $identifier))
				{
					$this->errorOutput(MOBILE_REG_BIND);
				}
			}

			if($this->type!='shouji' && $_mobile && isset($this->input['mobile_verifycode']))
			{
				//验证码
				$verifycode = $this->mSmsServer->get_verifycode_info($_mobile, $mobile_verifycode);
				if (empty($verifycode))
				{
					$this->errorOutput(VERIFY_FAILED);
				}
				//删除验证码
				$this->mSmsServer->mobile_verifycode_delete($_mobile, $mobile_verifycode);
				if (TIMENOW > ($verifycode['create_time'] + VERIFYCODE_EXPIRED_TIME))
				{
					$this->errorOutput(VERIFY_EXPIRED);
				}
				$this->ismobileverify = 1;
			}
			else if ($this->type!='shouji' && $_mobile && defined('NO_VERIFY_MOBILEBIND') && NO_VERIFY_MOBILEBIND)
			{
				$this->ismobileverify = 1;
			}

			$device_token = $this->Members->check_device_token(trim($this->input['device_token']));
			if($device_token===0)
			{
				$this->errorOutput(ERROR_DEVICE_TOKEN);
			}
			$udid = $this->Members->check_udid(trim($this->input['uuid']));
			if($udid===0)
			{
				$this->errorOutput(ERROR_UDID);
			}
			//验证设备号和ip是否在黑名单
			if($udid)
			{
				$device_res = $this->Blacklist->detailDeviceBlacklist(array('device_token' => $udid,'identifier' => $identifier));
                if($device_res[0]['deadline'] == -1 && $device_res[0]['type'] == 2)
                {
                    $this->errorOutput(DEVICE_BLACKLIST_FOREVER);
                }
                elseif($device_res[0]['deadline'] == -1)
                {
                    $this->errorOutput(DEVICE_BLACKLIST);
                }
			}
			if($ip)
			{
				$ip_res = $this->Blacklist->detailIpBlacklist(array('ip' => ip2long($ip),'identifier' => $identifier));
                if($ip_res[0]['deadline'] == -1 && $ip_res[0]['type'] == 2)
                {
                    $this->errorOutput(IP_BLACKLIST_FOREVER);
                }
                elseif($ip_res[0]['deadline'] == -1)
                {
                    $this->errorOutput(IP_BLACKLIST);
                }
			}

			//密码
			if (!$password)
			{
				$this->errorOutput(NO_PASSWORD);
			}
			//验证手机验证码
			if ($this->type == 'shouji')
			{
				$check_bind = new check_Bind();
				if($check_bind->checkmembernamereg($member_name, $identifier))
				{
					$this->errorOutput(MOBILE_REG_BIND);
				}

				$platform_id = $mobile = $member_name;

				$_mobile = $mobile?$mobile:$_mobile;

				//简单验证手机号格式
				if (!hg_verify_mobile($mobile))
				{
					$this->errorOutput(MOBILE_NUMBER_FORMAT_ERROR);
				}
				if (!$mobile_verifycode)
				{
					$this->errorOutput(MOBILE_NOT_VERIFY);
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
			//如果是m2o注册类型屏蔽字检测
			if($this->settings['App_banword'])
			{
				include (ROOT_PATH.'lib/class/banword.class.php');
				$banword = new banword();
				$signature_banword = $banword->exists($signature);
				if ($signature_banword && is_array($signature_banword))
				{
					$this->errorOutput(SIGNATURE_INVALID);
				}
			}
			if($this->type == 'm2o'&&$this->settings['App_banword'])
			{
				$member_name_banword = $banword->exists($member_name);
				if ($member_name_banword && is_array($member_name_banword))
				{
					$this->errorOutput(MEMBER_NAME_INVALID);
				}
			}
			//头像
			$avatar = array();
			if (isset($this->input['avatar']) && $_FILES['avatar']['tmp_name'])
			{
				$avatar = $_FILES['avatar'];
			}

			//验证会员名
            $ret_verify = $this->mMember->verify_member_name($member_name,0,$identifier,$type);
			switch ($ret_verify)
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
			//随机串
			$salt = hg_generate_salt();

			//密码md5
			$md5_password = md5(md5($password) . $salt);
			$groupInfo = $this->Members->checkgroup_credits(0);
			$gradeInfo = $this->Members->checkgrade_credits(0);
			$data = array(
			'member_name'	=> $member_name,
			'password'		=> $md5_password,
			'salt'			=> $salt,
			'type'			=> $this->type,
			'type_name'		=> $type_name,
			'gid'		    => $groupInfo['gid'],
			'gradeid'		=> $gradeInfo['gradeid'],
			'signature'		=> $signature,
			'mobile'		=> $_mobile,
			'email'			=> $email,
			'status'		=> $this->settings['member_status'],
			'identifier'	=> $identifier,
			'appid'			=> $this->user['appid'],
			'appname'		=> $this->user['display_name'],
			'create_time'	=> TIMENOW,
			'update_time'	=> TIMENOW,
			'ip'			=> $ip,
			'guid'			=> guid(),
			'reg_device_token'=>$device_token,
			'reg_udid'      => $udid,
			);
			//入ucenter
			$inuc = 0;
			if(($this->type == 'm2o') && $this->settings['ucenter']['open']&&!$identifier)
			{
				//邮箱 m2o类型必须传入email

				if(!$email)
				{
					$this->errorOutput(NO_EMAIL);
				}
				$virtual_email = $email;
				//忽略返回值
				$reinfo = $this->uc_register(array('member_name'=>$data['member_name'], 'password'=>$password, 'email'=>$virtual_email));
				$inuc = $reinfo['member_id'];
			}
			//会员数据入库
			$ret = $this->mMember->create($data);
			if (!$ret['member_id'])
			{
				$this->errorOutput(MEMBER_DATA_ADD_FAILED);
			}
			$member_id = $ret['member_id'];

			//编辑扩展信息 #@param platformMark 平台标示
			if($this->input['platformMark'] && $this->input['platformMark'] == 'dingdone' && $this->input['identifier'])
			{
			    //为叮当注册根据app配置不同的扩展信息
			    $this->mMemberInfo->extension_editByApp($member_id, $this->input['member_info'], $this->input['identifier'], $_FILES);
			}
			else
			{
    			$this->mMemberInfo->extension_edit($member_id, $this->input['member_info'],$_FILES);
			}
            //获取扩展信息
            $extension = $this->getExtensionInfo($member_id,$identifier);

			if(!$identifier)//目前多用户系统不支持邀请码功能
			{
				$invite_user = new invite();
				$id=$this->input['invite_id']?$this->input['invite_id']:0;//邀请码id
				$invite_code=$this->input['invite_code']?$this->input['invite_code']:$member_name;//如果未传邀请码则已用户名为邀请码去邀请数据库查询是否存在邀请信息,目前仅支持手机注册类型用户名;
				$invite = $invite_user->invite_rules($member_id,$invite_code,$id);//邀请用户处理
				$this->invite_error($invite);
			}
			//uc打开平台id为uc 否则为自身id
			if($this->type == 'm2o')
			{
				$platform_id = $this->settings['ucenter']['open']&&$reinfo['member_id']>0&&!$identifier ? $reinfo['member_id'] : $member_id;
			}
			elseif($this->type == 'email')
			{
				$platform_id = $member_name;
			}
			$data['member_id'] = $member_id;

			//绑定表
			$bind_data = array(
			'member_id'		=> $member_id,
			'platform_id'	=> $platform_id,
			'nick_name'		=> $member_name,
			'type'			=> $this->type,
			'type_name'		=> $type_name,
			'bind_time'		=> TIMENOW,
			'bind_ip'		=> $ip,
			'inuc'			=> $inuc,
			'is_primary'	=> 1,
			'identifier'	=> $identifier,
			'reg_device_token' => $device_token,
			'reg_udid'      => $udid,
			);

			$ret_bind = $this->mMember->bind_create($bind_data);

			if (empty($ret_bind))
			{
				$this->errorOutput(BIND_DATA_ADD_FAILED);
			}
			$this->registerCreditRules($member_id);//注册相关积分规则
			//如果注册时填写邮箱则可以同时入绑定表
			if($data['email'])
			{
				if($this->type != 'email' && $this->isemailverify)
				{
					$_bind_data = $bind_data;
					$_bind_data['platform_id'] = $data['email'];
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
			if($data['mobile'])
			{
				if($this->type != 'shouji' && $this->ismobileverify)
				{
					$_bind_data = $bind_data;
					$_bind_data['platform_id'] = $data['mobile'];
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
			
			//头像入库
			if (!empty($avatar))
			{
				$avatar = $this->mMember->add_material($avatar, $member_id);

				if (!empty($avatar))
				{
					$update_data = array(
					'member_id' => $member_id,
					'avatar' 	=> daddslashes(serialize($avatar)),
					);

					$ret_updata = $this->mMember->update($update_data);

					if (!$ret_updata['member_id'])
					{
						$this->errorOutput(AVATAR_ADD_FAILED);
					}
				}
			}
			else
			{
				$avatar_url = $this->input['avatar_url'] ? trim($this->input['avatar_url']) : '';
				if($avatar_url)
				{
					$avatar = $this->mMember->local_material($avatar_url, $member_id);

					if (!empty($avatar))
					{
						$update_data = array(
							'member_id' => $member_id,
							'avatar' 	=> daddslashes(serialize($avatar)),
						);
						
						$ret_updata = $this->mMember->update($update_data);
	
						if (!$ret_updata['member_id'])
						{
							$this->errorOutput(AVATAR_ADD_FAILED);
						}
					}
				}
			}
			//到auth接口取access_token
			$callback = 'http://' . $this->settings['App_members']['host'] . '/' . $this->settings['App_members']['dir'] . 'login.php?a=verify_member&appid=' . $appid . '&appkey=' . $appkey;
			$encryptPassword = urlencode(passport_encrypt($password, CUSTOM_APPKEY));
			$auth_data = array(
			'user_name'		 => $member_name,
			'appid'			 => $appid,
			'appkey'		 => $appkey,
			'ip'			 => $ip,
			'verify_user_cb' => $callback,
			'extend' 		 => 'platform_id=' . $platform_id . '&password=' . $encryptPassword . '&encrypt=1&type=' . $this->type.'&identifier='.$identifier,
			);
			$auth = $this->mMember->get_access_token($auth_data);
			if (!$auth['token'])
			{
				$this->errorOutput(MEMBERS_LOGIN_ERROR);
			}
			$return = array(
			'member_id' 	=> $member_id,
			'member_name' 	=> $ret['member_name'],
			'nick_name' 	=> $auth['nick_name'],
			'platform_id'   => $auth['platform_id'],//平台id,目前用于用户中心同步uc使用.
			'inuc'          => $auth['inuc']?$auth['inuc']:0,
			'type' 			=> $this->type,
			'type_name'		=> $auth['type_name'],
			'avatar' 		=> $avatar,
			'access_token' 	=> $auth['token'],
			'guid'			=> $auth['guid'],
			'gid'			=> $auth['gid'],
			'gradeid'			=> $auth['gradeid'],
			'copywriting_credit'=>$auth['copywriting_credit'],
			'copywriting'=>$auth['copywriting'],
			'signature'		=> $auth['signature'],
			'mobile'		=> $auth['mobile'],
			'email'			=> $auth['email'],
			'isVerify'		=> $auth['isVerify'],
			'isComplete'	=> $auth['isComplete'],
			'identifier'	=> $auth['identifier'],
			'last_login_device'	=> $auth['last_login_device'],
			);

            if($extension)
			{
			    $return['extension'] = $extension;
			}

			//会员痕迹
			$member_trace_data = array(
			'member_id'		=> $member_id,
			'member_name'	=> $member_name,
			'content_id'	=> $member_id,
			'title'			=> $member_name,
			'type'			=> 'register',
			'op_type'		=> '注册',
			'appid'			=> $this->user['appid'],
			'appname'		=> $this->user['display_name'],
			'create_time'	=> TIMENOW,
			'ip'			=> hg_getip(),
			'device_token'	=> $device_token,
			'udid'          => $udid,
			);
			$this->mMember->member_trace_create($member_trace_data);
			//记录登陆信息
			$loginInfoRecord = array(
                    'last_login_device'=>$member_trace_data['device_token'],
                    'final_login_time'=>$member_trace_data['create_time'],
                    'last_login_time'=>$member_trace_data['create_time'],
                    'last_login_udid'=>$member_trace_data['udid'],
			);
			$this->mMember->loginInfoRecord($return['member_id'], $loginInfoRecord);
			$return = hg_mermber2members_compatible(array('member_name'=>'nick_name','access_token'=>'token'), $return, false);
			$this->addItem($return);
			$this->output();
		}
		catch (Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}

    /**
     * 获取用户的扩展信息
     *
     * @param $member_id
     * @param int $app_id
     * @return Ambigous|array
     */
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

	private function checkRegMemberName()
	{
		$this->memberName = trimall($this->input['member_name']);
		if(!$this->type || $this->settings['autoRegReviseType'])
		{
			if(hg_verify_mobile($this->memberName))
			{
				$this->type = 'shouji';
				$this->input['mobile'] = $this->memberName;
			}
			else if(hg_check_email_format($this->memberName))
			{
				$this->type = 'email';
				$this->input['email'] = $this->memberName;
			}
			else if (!$this->type)
			{
				$this->type = 'm2o';
			}
		}
		return $this->memberName;
	}

	private function checkRegMemberNameError()
	{
		!$this->memberName && $this->errorOutput(NO_MEMBER_NAME);
        $identifierUserSystem = new identifierUserSystem();
        $identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
		if($this->memberName&&$this->type=='m2o'&&is_numeric($this->memberName))
		{
			$this->errorOutput(MEMBER_NO_NUM);
		}
		else if (($this->memberName&&$this->type=='m2o')&&($memberNameStatus = $this->mMember->member_name_auth($this->memberName))!=1)
		{
            $ret_verify = $this->mMember->verify_member_name($this->memberName,0,$identifier);
            switch ($ret_verify)
			{
				case -4 :
					$this->errorOutput(MEMBER_NAME_EXCEEDS_MAX);
					break;
				case -5 :
					$this->errorOutput(USERNAME_BELOW_MINIMUM);
					break;
				case -6 :
					$this->errorOutput(MEMBER_NAME_ERROR);
					break;
				default:
					break;
			}
		}

		if ($this->memberName && $this->type=='shouji' && !hg_verify_mobile($this->memberName))
		{
			$this->errorOutput(MOBILE_NUMBER_FORMAT_ERROR);
		}

		if($this->memberName && $this->type =='email'&&!hg_check_email_format($this->memberName))
		{
			$this->errorOutput(EMAIL_FORMAT_ERROR);
		}
	}


    /**
     * 验证邮箱验证码
     * @param $email
     */
    private function checkEmailVerifyCode($email)
	{
		$email_verifycode = trim($this->input['email_verifycode']);
		if($this->type =='email' && empty($email_verifycode) && !defined(NO_VERIFY_EMAILBIND) && !NO_VERIFY_EMAILBIND)
		{
            $this->errorOutput(EMAIL_VERIFY_NULL);
		}

		if($email_verifycode && $this->memberverifycode->get_verifycode_info($email,$email_verifycode,1,1))
		{
			$this->memberverifycode->verifycode_delete($email,$email_verifycode,1,1);	//验证成功之后删除
			$this->isemailverify = 1;
		}
		else if($email_verifycode)
		{
			$this->errorOutput(EMAIL_VERIFY_FAILED);
		}
		if($this->type != 'email' && $email && !defined(NO_VERIFY_EMAILBIND))
		{
			$this->isemailverify = 1;
		}
	}

	/**
	 *
	 * 检测注册类型
	 */
	private function checkRegType()
	{
		$ArrAppid = dexplode($this->settings['closeRegTypeSwitchAppid'],3);
		if(!$this->settings['regConfig']['close'] || ($ArrAppid && !in_array($this->user['appid'], $ArrAppid)))
		{
			return true;
		}
		if($this->settings['closeRegTypeSwitch']['m2o']&&($this->type == 'm2o' || $this->type == 'uc'))
		$this->errorOutput(M2O_CLOSE_ORDINARY_REGISTERED);

		if($this->settings['closeRegTypeSwitch']['shouji']&&($this->type == 'shouji'))
		$this->errorOutput(SHOUJI_CLOSE_ORDINARY_REGISTERED);

		if($this->settings['closeRegTypeSwitch']['email']&&($this->type == 'email'))
		$this->errorOutput(EMAIL_CLOSE_ORDINARY_REGISTERED);
	}

	private function registerCreditRules($member_id)
	{
		if($member_id){
			//会员注册增加初始积分,积分规则调用
			$this->Members->credits_rule('members_register_register',$member_id,$coef=1,$update=1,APP_UNIQUEID);
			if($this->type == 'shouji')
			{
				//积分规则调用
				$this->Members->credits_rule('members_register_register_shouji',$member_id,$coef=1,$update=1,APP_UNIQUEID);
			}
	 }
	}
	//注册数据至uc
	private function uc_register($register_data = array())
	{
		if(!$this->settings['ucenter']['open'])
		{
			$this->errorOutput(UC_IS_CLOSED);
		}
		$is_return = true;
		if(!$register_data)
		{
			$is_return = false;
			$register_data = array(
		 	'member_name'	=> trim($this->input['member_name']),
		 	'password'		=> $this->input['password'],
		 	'email'			=> $this->input['email'],
			);
		}
		$register_data = $this->mMember->uc_register($register_data);
		if(!is_array($register_data)&&$register_data <= 0)
		{
			if($register_data == -1) {
				$this->errorOutput(MEMBER_NAME_ILLEGAL);
			} elseif($register_data == -2) {
				$this->errorOutput(PROHIBITED_WORDS);
			} elseif($register_data == -3) {
				$this->errorOutput(UC_MEMBER_NAME_REGISTER);
			} elseif($register_data == -4) {
				$this->errorOutput(EMAIL_FORMAT_ERROR);
			} elseif($register_data == -5) {
				$this->errorOutput(EMAIL_NO_REGISTER);
			} elseif($register_data == -6) {
				$this->errorOutput(EMAIL_HAS_BINDED);
			} else {
				$this->errorOutput(UC_REGISTER_ERROR);
			}
		}
		if($is_return)
		{
			return $register_data;
		}
		$this->addItem($register_data);
		$this->output();
	}
	/**
	 *
	 * 邀请错误处理
	 */
	private function invite_error($invite)
	{
		//此函数为预留函数
	}
	/**
	 *
	 * 邮箱状态检测
	 */
	public function checkmail()
	{
		try{
			$email = $this->input['email']?trim($this->input['email']):'';
			$memberId = $this->user['user_id']?$this->user['user_id']:0;
			$identifierUserSystem = new identifierUserSystem();
			$identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
			$reg_mail = $this->Members->check_reg_mail($email,$memberId,$identifier);
			if(empty($reg_mail))
			{
				$data = array('status'=>$reg_mail,'msg'=>'对不起，请传需要检测的邮箱');
			}
			elseif ($reg_mail>0)
			{
				$data = array('status'=>$reg_mail,'msg'=>'Email 未注册且格式正确');
			}
			elseif ($reg_mail==-4)
			{
				$data = array('status'=>$reg_mail,'msg'=>'Email 格式不正确');
			}
			elseif($reg_mail == -5)
			{
				$data = array('status'=>$reg_mail,'msg'=>'Email 不允许注册');
			}
			elseif ($reg_mail == -6)
			{
				$data = array('status'=>$reg_mail,'msg'=>'该 Email 已经被绑定');
			}
			$this->addItem($data);
			$this->output();
		}
		catch(Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}

    /**
     *  检查手机号 格式 是否被注册
     */
    public function checkmobile()
    {
        try{
            $identifierUserSystem = new identifierUserSystem();
            $mobile = $this->input['mobile'] ? $this->input['mobile'] : 0;
            $identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
            if ($mobile&&!hg_verify_mobile($mobile))
            {
                $this->errorOutput(MOBILE_NUMBER_FORMAT_ERROR);
            }
            else if($mobile || defined('NO_VERIFY_MOBILEBIND') && NO_VERIFY_MOBILEBIND)
            {
                $check_bind = new check_Bind();
                if($check_bind->checkmembernamereg($mobile, $identifier))
                {
                    $this->errorOutput(MOBILE_REG_BIND);
                }
            }
        }
        catch(Exception $e)
        {
            $this->errorOutput($e->getMessage(),$e->getCode());
        }
    }

	private function check_verifycode()
	{
		/*********** 验证码 ***********/
		require ROOT_PATH . 'lib/class/verifycode.class.php';
		$mVerifyCode = new verifyCode();
		if(defined('IS_REGISTER_VERIFYCODE')&&IS_REGISTER_VERIFYCODE&&$this->settings['App_verifycode']&&empty($this->input['is_mobile_verifycode']))//is_mobile_client 控制是否需要验证码,需要则不需要传此参数.不需要则传,在移动APP里设置为自动传,禁止用手机端传,容易被捕获
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

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}
}

$out = new registerApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'register';
}
$out->$action();
?>