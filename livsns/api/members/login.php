<?php
/***************************************************************************
 * $Id: login.php 46330 2015-06-23 08:33:52Z tandx $
 ***************************************************************************/
define('MOD_UNIQUEID','login');//模块标识
require('./global.php');
class_exists('member') or require CUR_CONF_PATH . 'lib/member.class.php';
class_exists('memberInfo') or require CUR_CONF_PATH . 'lib/member_info.class.php';
class_exists('memberBlacklist') or require CUR_CONF_PATH . 'lib/memberblacklist.class.php';
class loginApi extends appCommonFrm
{
	private $mMember;
	private $oldtype = '';//用户传的TYPE
	public function __construct()
	{
		parent::__construct();
		$this->mMember = new member();
		$this->mMemberInfo = new memberInfo();
		$this->Members = new members();
		$this->Blacklist = new memberblacklist();

	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 会员登录
	 * $member_name
	 * $password
	 * $type
	 * $type_name
	 * $appid
	 * $appkey
	 *
	 * 返回
	 * member_id
	 * member_name
	 * type
	 * avatar
	 * access_token
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
	 */
	public function login()
	{
		try{
			$member_name = $this->checkUserName(trimall($this->input['member_name']));
			$password 	 = trim($this->input['password']);
			$ip			 = hg_getip();
			$type = $this->input['type'];
			$this->checkLoginTypeSwitch();
			$this->checkLoginTypeError($member_name, $type);
			$this->checkLoginPassword($password, $type);
			$this->check_verifycode($type);//验证码
			$_type = '';//防止本地M2O同步至UC后，再次验证本地密码BUG。
			$platform_id = trim($this->input['platform_id']);
			$identifierUserSystem = new identifierUserSystem();
			$identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
			$appid  = intval($this->input['appid']);
			$appkey = trim($this->input['appkey']);
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
			//登陆类型 shouji、sina、txweibo、qq、renren、douban
			if (!$type)
			{
				$this->errorOutput(NO_EXTERNAL_TYPE);
			}
			if( $type=='uc' && $identifier )//如果传多用户系统标记，则不支持UC方式登陆。
			{
				$this->input['type'] = '';
				$this->checkUserName($member_name,1);
				$this->input['type'] && $type = $this->input['type'];
			}
			if($type == 'm2o'&&$this->settings['ucenter']['open']&&!$identifier)//多套用户系统不支持UC接入
			{
				$check_login = $this->oAuthUc(true,true);//修复手机端传m2o类型，但是帐号属于UC类型，登陆失败问题
				if($check_login>0)
				{
					$type = 'uc';
				}
			}
			if($type == 'uc'&&$this->settings['ucenter']['open']&&!$identifier)
			{
				$uc_user = $this->oAuthUc(true);
				if($uc_user['user_id'] == -1)
				{
					$type = 'm2o';
				}
			}
			elseif ($type == 'uc'&&!$this->settings['ucenter']['open']&&!$identifier)
			{
				$this->errorOutput(UC_LOGIN_ERROR);
			}
			$check_Bind = new check_Bind();
			//所有类型的邮箱登陆
			if($member_name && $type == 'email')
			{
				$platform_id = $platform_id?$platform_id:$member_name;
				if(!$check_Bind->bind_to_memberid($member_name,$type,true,$identifier))
				{
					$this->errorOutput(LOGIN_NOMEMBER_ERROR);
				}
			}
			else if ($type == 'shouji')
			{
				//会员名
				if (!$member_name)
				{
					$this->errorOutput(NO_MEMBER_NAME);
				}
				$platform_id = $platform_id?$platform_id:$member_name;
				if(!$check_Bind->bind_to_memberid($member_name,$type,true,$identifier))
				{
					$this->errorOutput(LOGIN_NOMEMBER_ERROR);
				}				
			}
			else if($type == 'm2o')
			{
				$is_mobile_login = false;
				$where=' AND member_name="'.$member_name.'" AND type="m2o" AND identifier = \''.$identifier.'\'';
				$sql = 'SELECT member_id FROM ' . DB_PREFIX . 'member WHERE 1';
				$memberinfo = $this->db->query_first($sql . $where);
				if(!$memberinfo)
				{
					if(hg_verify_mobile($member_name))
					{
						$where=' AND member_name=\''.$member_name.'\' AND type=\'shouji\' AND identifier = \''.$identifier.'\'';
						$memberinfo = $this->db->query_first($sql . $where);
						if($memberinfo)
						{
							$type = 'shouji';
							$platform_id = $check_Bind->check_uc($memberinfo['member_id'],$type);//修复同步UC后，登陆密码错误的bug
							$platform_id = $platform_id?$platform_id:$member_name;
						}
						if(empty($memberinfo))
						{
							$type = 'shouji';
							$member_id = $check_Bind->bind_to_memberid($member_name,$type,true,$identifier);
							if($member_id)
							{
								$memberinfo = array('member_id'=>$member_id);
								$platform_id = $member_name;
							}
						}
					}
					$memberinfo ? $memberinfo : $this->errorOutput(LOGIN_NOMEMBER_ERROR);
				}
				if($type!='shouji')
				{
					$bindinfo = $this->db->query_first('SELECT inuc FROM ' . DB_PREFIX . 'member_bind WHERE member_id='.$memberinfo['member_id'] . ' AND type="m2o"');
					$platform_id = $bindinfo['inuc'] ? $bindinfo['inuc'] : $memberinfo['member_id'];
				}
			}
			else
			{
				//新浪微博、腾讯微博、QQ、人人网、豆瓣 uc等
				$nick_name 	 = trimall($this->input['nick_name']);
				$type_name	 = trim($this->input['type_name']);
				$avatar_url	 = trim($this->input['avatar_url']);
				if($type == 'uc'&&$uc_user)
				{
					//$platform_id = $uc_user['user_id'];
					//手机 m2o注册至uc之后登陆类型使用“uc”导致的bug
					$sql = 'SELECT * FROM '.DB_PREFIX .'member_bind WHERE type=\'m2o\' AND inuc='.$uc_user['user_id'];
					$bind_uc = $this->db->query_first($sql);
					if($bind_uc)
					{
						$platform_id = $bind_uc['platform_id'];
						$nick_name = $bind_uc['nick_name'];
						$type_name = $bind_uc['type_name'];
						$avatar_url = $bind_uc['avatar_url'];
						$_type = $type;
						$type = $bind_uc['type'];
					}
					else//uc直接登陆
					{
						$platform_id = $uc_user['user_id'];
						$nick_name = $uc_user['user_name'];
						$type_name = 'UC会员';
						$avatar_url = $uc_user['avatar'];
						$email = $uc_user['email'];
					}
				}
				if (!$platform_id)
				{
					$this->errorOutput(NO_MEMBER_ID);
				}

				if (!$nick_name)
				{
					$this->errorOutput(NO_NICKNAME);
				}

				$member_name = $nick_name;

				$condition = " AND mb.platform_id = '" . $platform_id . "' AND mb.type = '" . $type . "' AND mb.identifier = '".$identifier.'\'';
				$bind = $this->mMember->get_bind_info($condition);
				$bind = $bind[0];
				if(empty($type_name))
				{
					$platformInfo = $this->Members->get_platform_name($type);
					if(empty($platformInfo))
					{
						$this->errorOutput(LOGIN_MEMBER_TYPE_ERROR);
					}
					else if (!$platformInfo['status'])
					{
						$this->errorOutput(LOGIN_MEMBER_TYPE_CLOSE);
					}
					$type_name = $platformInfo['name'];
				}
				$avatar_array = array();
				$avatar_array = $this->mMember->update_avatar($avatar_url,$bind);
				//会员表
				$data = array(
				'member_name'	=> $nick_name,
				'email'  => $email,
				'type'			=> $type,
				'type_name'		=> $type_name,
				'update_time'	=> TIMENOW,
				'avatar'		=> daddslashes(serialize($avatar_array)),
				'guid'			=> guid(),
				);
				//绑定表
				$bind_data = array(
				'platform_id'	=> $platform_id,
				'type'			=> $type,
				'avatar_url'	=> $avatar_url,
				'reg_device_token' => $device_token,
				'reg_udid'      => $udid,
				);
				if (empty($bind))	//未绑定
				{
					if($type=='uc')
					{
						$isBindUc = 0;
						if($memberId = $this->mMember->verifyPassword($member_name, $password, 'm2o'))
						{
							$isBindUc = $this->mMember->bind_uc($memberId, $uc_user['user_id']);
						}
						if(!$isBindUc&&$password)//uc登陆后本地记录密码
						{
							//随机串
							$salt = hg_generate_salt();
							$data['salt'] = $salt;
							//密码md5
							$data['password'] = md5(md5($password) . $salt);

						}
					}
					if($type != 'uc' || !$isBindUc)
					{
						//新增会员
						$groupInfo = $this->Members->checkgroup_credits(0);
						$gradeInfo = $this->Members->checkgrade_credits(0);
						$data['gid']   = $groupInfo['gid'];
						$data['gradeid'] = $gradeInfo['gradeid'];
						$data['status'] 		= $this->settings['member_status'];
						$data['identifier']    = $identifier;
						$data['appid'] 			= intval($this->user['appid']);
						$data['appname'] 		= trim($this->user['display_name']);
						$data['create_time'] 	= TIMENOW;
						$data['ip'] 			= $ip;
						$data['reg_device_token'] = $device_token;
						$data['reg_udid'] = $udid;

						//会员数据入库
						$ret = $this->mMember->create($data);

						if (!$ret['member_id'])
						{
							$this->errorOutput(MEMBER_DATA_ADD_FAILED);
						}

						$member_id = $ret['member_id'];
						//绑定表

						$bind_data['nick_name'] = $nick_name;
						$bind_data['member_id'] = $member_id;
						$bind_data['type_name'] = $type_name;
						$bind_data['bind_time'] = TIMENOW;
						$bind_data['bind_ip'] 	= $ip;
						$bind_data['is_primary']= 1;
						$bind_data['identifier']    = $identifier;
						if($bind_data['type'] == 'uc')//修复uc类型，inuc字段无ucid问题。统一化
						{
							$bind_data['inuc']  = $bind_data['platform_id'];
						}
						$ret_bind = $this->mMember->bind_create($bind_data);
						if (empty($ret_bind))
						{
							$this->errorOutput(BIND_DATA_ADD_FAILED);
						}
						$this->registerCreditRules($member_id, $type);//新注册会员积分规则
					}
					else if ($type =='uc'&&$isBindUc)//如果为真则代表m2o绑定uc成功
					{
						$type = 'm2o';
					}
				}
				else	//已绑定
				{
					//更新会员
					$member_id = $bind['member_id'];
					//验证会员是否存在
					$condition  = " AND m.member_id = " . $member_id;
					$ret_member = $this->mMember->get_member_info($condition);
					$ret_member = $ret_member[0];
					if (empty($ret_member))
					{
						$this->errorOutput(LOGIN_NOMEMBER_ERROR);
					}
					$update_bind_data = array(
					'member_id'=>$member_id,
					'platform_id'	=> $platform_id,
					'type'			=> $type,
					'avatar_url'	=> $avatar_url,
					);
					$ret_bind = $this->mMember->bind_update($update_bind_data);
					if (empty($ret_bind))
					{
						$this->errorOutput(BIND_DATA_UPDATE_FAILED);
					}
				}
			}
			//到auth接口取access_token
			$encryptPassword = urlencode(passport_encrypt($password, CUSTOM_APPKEY));
			$callback = 'http://' . $this->settings['App_members']['host'] . '/' . $this->settings['App_members']['dir'] . 'login.php?';
			$func = 	'a=verify_member&appid=' . $appid . '&appkey=' . $appkey;
			$callback .= urlencode($func);
			$extend   = 'platform_id=' . $platform_id . '&password=' . $encryptPassword . '&encrypt=1&type=' . $type.'&_type='.$_type.'&identifier='.$identifier;
			$auth_data = array(
			'user_name'		 => $member_name,
			'appid'			 => $appid,
			'appkey'		 => $appkey,
			'ip'			 => $ip,
			'verify_user_cb' => $callback,
			'extend' 		 => urlencode($extend),
			);
			$auth = $this->mMember->get_access_token($auth_data);
			if (!$auth['token'])
			{
				$this->errorOutput(MEMBERS_LOGIN_ERROR);
			}
			//黑名单用户判断
			$blacklist=$this->Members->blacklist($auth['user_id']);
			if($blacklist[$auth['user_id']]['isblack'])//黑名单用户
			{
				$this->errorOutput(MEMBER_BLACKLIST);
			}
			//判断结束
			//权限判断
			//判断结束
			//编辑扩展信息
			$this->mMemberInfo->extension_edit($auth['user_id'], $this->input['member_info'],$_FILES);

            //获取扩展信息
            $extension = $this->getExtensionInfo($auth['user_id'],$identifier);

			//会员痕迹
			$member_trace_data = array(
			'member_id'		=> $auth['user_id'],
			'member_name'	=> $member_name,
			'content_id'	=> $auth['user_id'],
			'title'			=> $member_name,
			'type'		=> 'login',
			'op_type'		=> '登陆',
			'appid'			=> $this->user['appid'],
			'appname'		=> $this->user['display_name'],
			'create_time'	=> TIMENOW,
			'ip'			=> hg_getip(),
			'device_token'	=> $device_token,
			'udid'          => $udid,
			);
			$memberTrace = $this->mMember->getMemberTrace(array('member_id'=>$auth['user_id'],'type'=>'login'), 'create_time');
			$this->mMember->member_trace_create($member_trace_data);

			$return = array(
			'member_id' 	=> $auth['user_id'],//用户id
			'platform_id'   => $auth['platform_id'],//平台id,目前用于用户中心同步uc使用.
			'inuc'   => $auth['inuc']?$auth['inuc']:0,//UC_id
			'member_name' 	=> $auth['user_name'],//用户名
			'nick_name' 	=> $auth['nick_name'],//昵称
			'type' 			=> $auth['type'],//会员初始注册类型
			'type_name'		=> $auth['type_name'],//会员类型名
			'avatar' 		=> $auth['avatar'] ? $auth['avatar'] : '',
			'access_token' 	=> $auth['token'],
			'guid'			=> $auth['guid'],
			'gid'			=> $auth['gid'],
			'gradeid'			=> $auth['gradeid'],
			'copywriting_credit'=>$auth['copywriting_credit'],
			'copywriting'=>$auth['copywriting'],
			'signature'		=> $auth['signature'],
			'mobile'		=> $auth['mobile'],
			'email'			=> $auth['email'],
            'extension'     => $extension ? $extension : array(),
			'isVerify'		=> $auth['isVerify'],
			'isComplete'	=> $auth['isComplete'],
			'identifier'	=> $auth['identifier'],
			'last_login_device'	=> $auth['last_login_device'],
			'last_login_time'	=>	date('Y-m-d H:i:s',$memberTrace['create_time']),
			);
			//记录登陆信息
			$loginInfoRecord = array(
                    'last_login_device'=>$member_trace_data['device_token'],
                    'final_login_time'=>$member_trace_data['create_time'],
                    'last_login_time'=>$memberTrace['create_time'],
                    'last_login_udid'=>$member_trace_data['udid'],
            );
            $this->mMember->loginInfoRecord($return['member_id'], $loginInfoRecord);
            $return = hg_mermber2members_compatible(array('member_name'=>'nick_name', 'access_token'=>'token'),$return, false);
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

	/**
	 *
	 * 检测登陆类型
	 */
	private function checkLoginTypeSwitch()
	{
		$ArrAppid = dexplode($this->settings['closeLoginTypeSwitchAppid'],3);
		if(!$this->settings['loginConfig']['close'] || ($ArrAppid && !in_array($this->user['appid'], $ArrAppid)))
		{
			return true;
		}
		if($this->settings['closeLoginTypeSwitch']['m2o']&&(in_array($this->input['type'], array('m2o','uc'))))
		$this->errorOutput(M2O_CLOSE_ORDINARY_LOGIN);

		if($this->settings['closeLoginTypeSwitch']['shouji']&&($this->input['type'] == 'shouji'))
		$this->errorOutput(SHOUJI_CLOSE_ORDINARY_LOGIN);

		if($this->settings['closeLoginTypeSwitch']['email']&&($this->input['type'] == 'email'))
		$this->errorOutput(EMAIL_CLOSE_ORDINARY_LOGIN);
	}

	private function checkLoginPassword($password,$type)
	{
		if(!$password && in_array($type, array('m2o','uc','shouji','email')))
		{
			$this->errorOutput(NO_PASSWORD);
		}
	}

	private function checkLoginTypeError($member_name,$type)
	{
		if(! $this->settings['checkLoginType'] && ($type=='email'&&!hg_check_email_format($member_name) || $type == 'shouji' && !hg_verify_mobile($member_name) || ($type =='m2o' || $type == 'uc')&&(hg_verify_mobile($member_name) || hg_check_email_format($member_name))))
		{
			$this->errorOutput(LOGIN_MEMBERNAME_ERROR);
		}
	}

	private function checkUserName($memberName,$isEnforce = 0)
	{
		!$this->oldtype  && $this->oldtype = $this->input['type'] = trimall($this->input['type']);
		if(!$this->oldtype || $this->settings['autoLoginReviseType'] || $isEnforce)
		{
			if(hg_verify_mobile($memberName))
			{
				$this->input['type'] = 'shouji';
			}
			else if(hg_check_email_format($memberName))
			{
				$this->input['type'] = 'email';
			}
			else if (!$this->oldtype || $isEnforce)
			{
				$this->input['type'] = 'm2o';
			}
		}
		return $memberName;
	}

	protected function verify_email_format($email)
	{
		return hg_check_email_format($email);
	}
	/**
	 * 验证会员
	 * $member_name 会员名
	 * $password 密码
	 * $type 登陆类型
	 * $appid
	 * $appkey
	 */
	public function verify_member()
	{
		try{
			$encrypt  = $this->input['encrypt']?intval($this->input['encrypt']):0;
			$platform_id = trim($this->input['platform_id']);
			$password 	 = $encrypt?passport_decrypt((trim($this->input['password'])),CUSTOM_APPKEY):trim($this->input['password']);
			$type 		 = trim($this->input['type']);
			$_type 		 = trim($this->input['_type']);//防止本地M2O同步至UC后，再次验证本地密码BUG。
			$identifierUserSystem = new identifierUserSystem();
			$identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
			$ip			 = hg_getip();
			$need_password_type = array('shouji', 'm2o', 'email');
			$appid  = intval($this->input['appid']);
			$appkey = trim($this->input['appkey']);
			//会员名
			if (!$platform_id)
			{
				$this->errorOutput(NO_MEMBER_NAME);
			}
			//密码
			if (!$password && in_array($type, $need_password_type))
			{
				$this->errorOutput(NO_PASSWORD);
			}
			$condition = " AND mb.platform_id = '" . $platform_id . "' AND mb.type = '" . $type . "' AND mb.identifier = '".$identifier.'\'';
			$_bind = $this->mMember->get_bind_info($condition);
			$bind = array();
			if(is_array($_bind)&&count($_bind)>1)//修正本地同步至uc会员bug
			{
				foreach ($_bind as $v)
				{
					if($v['inuc']>0)
					{
						$bind = $v;
						break;
					}
				}
			}
			elseif (is_array($_bind))
			{
				$bind = $_bind[0];
			}
			$member_id = intval($bind['member_id']);
			$platform_id = $bind['platform_id'];
			$condition = " AND m.member_id = " . $member_id;
			$fileds_array = array(
			'member_id',
			'member_name',
			'password',
			'salt',
			'avatar',
			'type',
			'type_name',
			'gid',
			'gradeid',
			'guid',
			'signature',
			'mobile',
			'email',
			'isVerify',
			'identifier',
			'last_login_device',
			);
			$fields 	   = implode(',', $fileds_array);
			$member_info = $this->mMember->get_member_info($condition, $fields);
			$member_info = $member_info[0];
			if (empty($member_info))
			{
				$this->errorOutput(LOGIN_NOMEMBER_ERROR);
			}
			$isComplete = isUserComplete($member_info['type']);
			if (in_array($type, $need_password_type)&&$_type!='uc')
			{
				$encrypt_num  = intval($this->input['encrypt_num']);
				if ($encrypt_num == 1)
				{
					$md5_password = md5($password . $member_info['salt']);
				}
				else
				{
					$md5_password = md5(md5($password) . $member_info['salt']);
				}
				if ($md5_password != $member_info['password'])
				{					
					$this->errorOutput(PASSWORD_ERROR);
				}
			}
			else
			{
				//验证新浪微博、腾讯微博、QQ、人人、豆瓣 等 用户信息
				//暂时不作处理
			}
			//积分规则调用
			$credit_rules = $this->Members->credits_rule('members_login_login',$member_info['member_id'],$coef=1,$update=1,APP_UNIQUEID);
			$copywriting_credit = copywriting_credit(array($credit_rules));
			$check_Bind = new check_Bind();
			$inuc = $check_Bind -> check_uc($member_id, $type);
			if(!$inuc&&($_type=='m2o'||$type =='m2o')&&$member_info['email'])//处理本地没同步到UC，但是uc无此用户，测同步。
			{
				if($inuc = $this->mMember->syncUcRegister($member_id, $member_info['member_name'], $password, $member_info['email']))
				{
					$platform_id = $inuc;
				}
			}
			$return = array(
			'user_id'	=> $member_info['member_id'],
			'platform_id'=>(string)$platform_id,//绑定平台id(uc为uc_id,M2O会员为会员id,其它平台为平台返回id)
			'inuc' => $inuc,
			'user_name'	=> $member_info['member_name'],
			'nick_name' => $bind['nick_name'],
			'type'		=> $member_info['type'],
			'avatar'	=> $member_info['avatar'],
			'copywriting'=>'登录成功',
			'copywriting_credit'=>$copywriting_credit,
			'isVerify'=>$member_info['isVerify'],
			'identifier'=>$member_info['identifier'],
			'isComplete'=>$isComplete,
			);
			$return = array_merge($return, $member_info);
			$this->addItem($return);
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

	private function oAuthUc($is_return = false,$check_login = false)
	{
		$member_name = urldecode($this->input['member_name']);
		$password = $this->input['password'];
		if(empty($member_name))
		{
			$this->errorOutput(NO_MEMBER_NAME);
		}
		elseif (empty($password))
		{
			$this->errorOutput(NO_PASSWORD);
		}
		include_once (UC_CLIENT_PATH . 'uc_client/client.php');
		$is_success = uc_user_login($member_name, $password);
		$uc_id = $is_success[0];
		if($check_login)
		{
			return $uc_id;
		}
		$email = $is_success[3];
		switch ($uc_id)
		{
			case -1 :
				//$this->errorOutput(LOGIN_NOMEMBER_ERROR);
				break;
			case -2 :
				$this->errorOutput(PASSWORD_ERROR);
				break;
			case -3 :
				$this->errorOutput(SECURITY_QUESTION_WRONG);
				break;
			default:
				break;
		}
		$userinfo = array(
		'user_id'	=> $uc_id,
		'user_name'	=> $is_success['1'],
		'email'		=> $email,
		'avatar'	=> UC_API  . '/avatar.php?uid='.$uc_id . '&size=big',
		);
		if($is_return)
		{
			return $userinfo;
		}
		$this->addItem($userinfo);
		$this->output();
	}

	private function check_verifycode($type = '')
	{
		/*********** 验证码 ***********/
		require ROOT_PATH . 'lib/class/verifycode.class.php';
		$mVerifyCode = new verifyCode();
		$avoidLoginVerifyCode = dexplode($this->settings['avoidLoginVerifyCode'],1);
		if(defined('IS_LOGIN_VERIFYCODE')&&IS_LOGIN_VERIFYCODE&&$this->settings['App_verifycode']&&empty($this->input['is_mobile_verifycode'])&&empty($this->input['isSynLogin'])&&(!in_array($type,$avoidLoginVerifyCode)))//is_mobile_client 控制是否需要验证码,需要则不需要传此参数.不需要则传,在移动APP里设置为自动传,禁止用手机端传,容易被捕获，isSynLogin为uc发起同步登陆请求不使用验证码
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
	private function registerCreditRules($member_id,$type)
	{
		if($member_id){
			//会员注册增加初始积分,积分规则调用
			$this->Members->credits_rule('members_register_register',$member_id,$coef=1,$update=1,APP_UNIQUEID);
			if($type == 'shouji')
			{
				//积分规则调用
				$this->Members->credits_rule('members_register_register_shouji',$member_id,$coef=1,$update=1,APP_UNIQUEID);
			}
	 }
	}
}

$out = new loginApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'login';
}
$out->$action();
?>