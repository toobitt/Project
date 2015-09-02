<?php
/***************************************************************************
* $Id: member_edit.php 30599 2013-10-18 03:30:48Z zhuld $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('UC_CLIENT_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class memberEditApi extends appCommonFrm
{
	private $mMember;
	private $mMemberInfo;
	private $mEmail;
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/member.class.php';
		$this->mMember = new member();
		
		include_once CUR_CONF_PATH . 'lib/member_info.class.php';
		$this->mMemberInfo = new memberInfo();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 会员信息编辑
	 * 
	 * Enter description here ...
	 */
	public function member_edit()
	{
		$member_id = intval($this->input['member_id']);
		if (!$member_id)
		{
			$this->errorOutput('未传入会员ID');
		}
		
		//头像
		if (isset($this->input['member_avatar']) == 'member_avatar' && $_FILES['avatar']['tmp_name'])
		{
			$files = $_FILES['avatar'];
			
			$ret_avatar = $this->mMember->avatarEdit($member_id, $files);
			
			if (!$ret_avatar)
			{
				$this->errorOutput('头像更新失败');
			}
		}
		
		//昵称、手机、性别
		if (isset($this->input['member']) && trim($this->input['member']) == 'member')
		{
			if (isset($this->input['mobile']))
			{
				$mobile = trim($this->input['mobile']);
				if (!$mobile)
				{
					$this->errorOutput('手机号不能为空');
				}
				
				if ($this->mMember->check_mobile_exists($mobile))
				{
					$this->errorOutput('该手机号已被绑定');
				}
			}
			
			if (isset($this->input['nick_name']))
			{
				$nick_name = trim(urldecode($this->input['nick_name']));
				if (!$nick_name)
				{
					$this->errorOutput('昵称不能为空');
				}
				
				if ($this->mMember->check_nick_name_exists($nick_name, $member_id))
				{
					$this->errorOutput('昵称已存在');
				}
			}
			if (isset($this->input['email']))
			{
				$email = trim(urldecode($this->input['email']));
				if (!$email)
				{
					$this->errorOutput('邮箱不能为空');
				}
				$re  = $this->mMember->_check_email($email);
				if ($re == -3)
				{
					$this->errorOutput('邮箱不合法');
				}
				/*
				if ($re == -4)
				{
					$this->errorOutput('邮箱已存在');
				}
				*/
			}
			if (isset($this->input['sex']))
			{
				$sex = intval($this->input['sex']);
			}
			
			$ret_member = $this->mMember->memberEdit($member_id, $nick_name, $mobile, $sex, $email);
			
			if (!$ret_member)
			{
				$this->errorOutput('绑定手机号失败');
			}
		}

		//基本信息
		if (isset($this->input['member_info']) && trim($this->input['member_info']) == 'member_info')
		{
			$member_info = array(
				'member_id' => $member_id,
				'cn_name' => trim($this->input['cn_name']),
				'en_name' => trim($this->input['en_name']),
				'sex' => intval($this->input['sex']),
				'birth' => strtotime(urldecode($this->input['birth'])),
				'constellation' => intval($this->input['constellation']),
				'bloodtype' => intval($this->input['bloodtype']),
				'language' => trim($this->input['language']),
				'live_country' => trim($this->input['live_country']),
				'live_prov' => intval($this->input['live_prov']),
				'live_city' => intval($this->input['live_city']),
				'live_dist' => intval($this->input['live_dist']),
				'home_country' => urldecode($this->input['home_country']),
				'home_prov' => urldecode($this->input['home_prov']),
				'home_city' => urldecode($this->input['home_city']),
				'home_dist' => urldecode($this->input['home_dist']),
				'introduce' => trim($this->input['introduce']),
				'mark' => trim(urldecode($this->input['mark']),','),
			);
		
			if ($this->mMemberInfo->checkMemberInfo('member_info', $member_id))
			{
				$ret_info = $this->mMemberInfo->memberInfoCreate($member_info, $member_id);
			}
			else 
			{
				$ret_info = $this->mMemberInfo->memberInfoUpdate($member_info, $member_id);
			}
			if (!$ret_info['member_id'])
			{
				$this->errorOutput('会员基本信息编辑失败');
			}
				
			if($this->settings['is_open_xs'])
			{
				require_once ROOT_PATH . 'lib/class/team.class.php';
				$obj_team = new team();
				$ret = $obj_team->update_search($ret_info['member_id'],'user');
			}
		}
		
		//联系方式
		if (isset($this->input['member_contact']) && trim($this->input['member_contact']) == 'member_contact')
		{
			$member_contact = array(
				'member_id' => $member_id,
				'qq_num' => trim($this->input['qq_num']),
				'other_com' => trim($this->input['other_com']),
				'mobile' => trim($this->input['_mobile']),
				'phone' => trim($this->input['phone']),
				'email' => trim($this->input['_email']),
				'address_country' => trim($this->input['address_country']),
				'address_prov' => intval($this->input['address_prov']),
				'address_city' => intval($this->input['address_city']),
				'address_dist' => intval($this->input['address_dist']),
				'address' => trim($this->input['address']),
				'zipcode' => intval($this->input['zipcode']),
				'website' => trim($this->input['website']),
			);
			
			if ($this->mMemberInfo->checkMemberInfo('member_contact', $member_id))
			{
				$ret_contact = $this->mMemberInfo->memberContactCreate($member_contact, $member_id);
			}
			else 
			{
				$ret_contact = $this->mMemberInfo->memberContactUpdate($member_contact, $member_id);
			}
		
			if (!$ret_contact['member_id'])
			{
				$this->errorOutput('会员联系方式编辑失败');
			}
		}
		
		$this->addItem(array('success'=>$member_id));
		$this->output();
	}
	
	/**
	 * 邮箱激活
	 * Enter description here ...
	 */
	public function member_email_activate()
	{
		$activate_key = trim($this->input['activate_key']);
		if (!$activate_key)
		{
			$this->errorOutput('未传入激活码');
		}
		
		$ret_activate_key = $this->mMember->activate_key_detail($activate_key);
		
		if (!$ret_activate_key)
		{
			$this->errorOutput('激活码不存在');
		}
		
		$time = $ret_activate_key['toff'] + $ret_activate_key['create_time'];
		if ($time < TIMENOW)
		{
			$this->errorOutput('激活码已过期');
		}
	
		$member_id = $ret_activate_key['member_id'];
		if (!$member_id)
		{
			$this->errorOutput('会员不存在');
		}
		
		if ($this->mMember->check_email_activate($member_id))
		{
			//$this->errorOutput('已被激活');
		}
		
		$info = $this->mMember->memberEmailActivate($member_id);
		
		if ($info)
		{
			$this->mMember->activate_key_delete($activate_key);
		}
		
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 修改密码
	 * Enter description here ...
	 */
	public function member_password_edit()
	{
		$member_id = intval($this->input['member_id']);
		if (!$member_id)
		{
			$this->errorOutput('未传入会员id');
		}
		
		$old_password = trim(urldecode($this->input['old_password']));
		if (!$old_password)
		{
			$this->errorOutput('请输入旧密码');
		}
		
		$new_password = trim(urldecode($this->input['new_password']));
		$renew_password = trim(urldecode($this->input['renew_password']));
		if (!$new_password && !$renew_password)
		{
			$this->errorOutput('请输入新密码');
		}
		
		if ($new_password != $renew_password)
		{
			$this->errorOutput('两次新密码不一致');
		}
		
		$info = $this->mMember->memberPasswordEdit($member_id, $old_password, $new_password);
		if($info > 0)
		{
			$this->addItem($info);
			$this->output();
		}
		else
		{
			switch($info)
			{
				case -1 :
					$this->errorOutput('旧密码不正确');
					break;
				case -2 :
					$this->errorOutput('该用户不存在或已被删除');
					break;
				case -4 :
					$this->errorOutput('Email 格式有误');
					break;
				case -5 :
					$this->errorOutput('Email 不允许注册');
					break;
				case -6 :
					$this->errorOutput('该 Email 已经被注册');
					break;
				case -7 :
					$this->errorOutput('没有做任何修改');
					break;
				case -8 :
					$this->errorOutput('该用户受保护无权限更改');
					break;
				default:
					break;
			}			
		}		
	}
	
	/**
	 * 创建忘记密码key
	 * Enter description here ...
	 */
	public function password_forget_key_add()
	{
		$email = trim(urldecode($this->input['email']));
		
		if (!$email)
		{
			$this->errorOutput('邮箱不能为空');
		}
		
		$ret_email = $this->mMember->check_email_exists($email);
		if (!$ret_email)
		{
			$this->errorOutput('邮箱不存在');
		}
		
		$info = $this->mMember->password_forget_key_add($email, $ret_email['member_name']);
		if (!empty($info))
		{
			$info['member_name'] = $ret_email['member_name'];
		}
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 单条忘记密码key
	 * Enter description here ...
	 */
	public function password_forget_key_detail()
	{
		$password_forget_key = trim(urldecode($this->input['password_forget_key']));
		
		if (!$password_forget_key)
		{
			$this->errorOutput('key不能为空');
		}
		
		$info = $this->mMember->password_forget_key_detail($password_forget_key);

		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 忘记密码
	 * Enter description here ...
	 */
	public function member_password_forget()
	{
		$password_forget_key = trim($this->input['password_forget_key']);
		if (!$password_forget_key)
		{
			$this->errorOutput('未传入忘记密码key');
		}
		
		$ret_password_forget_key = $this->mMember->password_forget_key_detail($password_forget_key);
		
		if (!$ret_password_forget_key)
		{
			$this->errorOutput('忘记密码key不存在');
		}
		
		$time = $ret_password_forget_key['toff'] + $ret_password_forget_key['create_time'];
		if ($time < TIMENOW)
		{
			$this->errorOutput('忘记密码key已过期');
		}
	
		$email = $ret_password_forget_key['email'];
		if (!$email)
		{
			$this->errorOutput('邮箱不存在');
		}
		
		$password = trim(urldecode($this->input['password']));
		$repassword = trim(urldecode($this->input['repassword']));
		
		if (!$password)
		{
			$this->errorOutput('密码不能为空');
		}
		
		if ($password !=$repassword)
		{
			$this->errorOutput('两次密码不一致');
		}
		
		$info = $this->mMember->memberPasswordForget($email, $password);
		
		if ($info == -1)
		{
			$this->errorOutput('该会员不存在');
		}
		
		if (!$info)
		{
			$this->errorOutput('修改密码失败');
		}
	
		$this->mMember->password_forget_key_delete($password_forget_key);
		
		$appid = intval($this->input['appid']);
		$appkey = trim($this->input['appkey']);
		
		if (!$appid || !$appkey)
		{
			$this->errorOutput('数据来源不合法');
		}
		
		$ret_login = $this->mMember->login($info['member_name'], $password, $appid, $appkey);
	
		switch ($ret_login)
		{
			case -1 :
				$this->errorOutput('用户名不存在');
				break;
			case -2 :
				$this->errorOutput('密码不正确');
				break;
			case -3 :
				$this->errorOutput('站外标识不正确');
				break;
			case -4 :
				$this->errorOutput('该用户未绑定');
				break;
			default :
				break;
		}
		
		$this->addItem($ret_login);
		$this->output();
	}
	
	/**
	 * 获取会员基本信息
	 * Enter description here ...
	 */
	public function member_info_detail()
	{
		$member_id = intval($this->input['member_id']);
		if (!$member_id)
		{
			$this->errorOutput('未传入会员ID');
		}
		$info = $this->mMemberInfo->memberInfoDetail($member_id);
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 会员基本信息编辑
	 * Enter description here ...
	 */
	public function member_info_edit()
	{
		$member_id = intval($this->input['member_id']);
		if (!$member_id)
		{
			$this->errorOutput('未传入会员ID');
		}
		
		$birth = '';
		if (trim($this->input['birth']))
		{
			$birth = strtotime(trim($this->input['birth']));
		}
		
		$member_info = array(
			'member_id' => $member_id,
			'cn_name' => trim($this->input['cn_name']),
			'en_name' => trim($this->input['en_name']),
			'sex' => intval($this->input['sex']),
			'birth' => $birth,
			'constellation' => intval($this->input['constellation']),
			'bloodtype' => intval($this->input['bloodtype']),
			'language' => trim($this->input['language']),
			'live_country' => trim($this->input['live_country']),
			'live_prov' => intval($this->input['live_prov']),
			'live_city' => intval($this->input['live_city']),
			'live_dist' => intval($this->input['live_dist']),
			'home_country' => trim($this->input['home_country']),
			'home_prov' => intval($this->input['home_prov']),
			'home_city' => intval($this->input['home_city']),
			'home_dist' => intval($this->input['home_dist']),
			'introduce' => trim($this->input['introduce']),
		);
		
		if ($this->mMemberInfo->checkMemberInfo('member_info', $member_id))
		{
			$info = $this->mMemberInfo->memberInfoCreate($member_info, $member_id);
		}
		else 
		{
			$info = $this->mMemberInfo->memberInfoUpdate($member_info, $member_id);
		}
		
		$this->addItem($info);
		$this->output();
	}

	/**
	 * 获取会员联系方式
	 * Enter description here ...
	 */
	public function member_contact_detail()
	{
		$member_id = intval($this->input['member_id']);
		if (!$member_id)
		{
			$this->errorOutput('未传入会员ID');
		}
		$info = $this->mMemberInfo->memberContactDetail($member_id);
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 会员联系方式编辑
	 * Enter description here ...
	 */
	public function member_contact_edit()
	{
		$member_id = intval($this->input['member_id']);
		if (!$member_id)
		{
			$this->errorOutput('未传入会员ID');
		}
		
		$member_contact_info = array(
			'member_id' => $member_id,
			'qq_num' => trim($this->input['qq_num']),
			'other_com' => trim($this->input['other_com']),
			'mobile' => trim($this->input['mobile']),
			'phone' => trim($this->input['phone']),
			'email' => trim($this->input['email']),
			'address_country' => trim($this->input['address_country']),
			'address_prov' => intval($this->input['address_prov']),
			'address_city' => intval($this->input['address_city']),
			'address_dist' => intval($this->input['address_dist']),
			'address' => trim($this->input['address']),
			'zipcode' => intval($this->input['zipcode']),
			'website' => trim($this->input['website']),
		);
		
		if ($this->mMemberInfo->checkMemberInfo('member_contact', $member_id))
		{
			$info = $this->mMemberInfo->memberContactCreate($member_contact_info, $member_id);
		}
		else 
		{
			$info = $this->mMemberInfo->memberContactUpdate($member_contact_info, $member_id);
		}
		
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 编辑会员各种信息数目
	 * Enter description here ...
	 */
	public function eidt_member_info_count()
	{
		$member_id = intval($this->input['member_id']);
		if (!$member_id)
		{
			$this->errorOutput('未传入会员ID');
		}

		$appid = intval($this->input['appid']);
		if (!$appid)
		{
			$this->errorOutput('appid不能为空');
		}
		
		$appuniqueid = trim($this->input['appuniqueid']);
		if (!$appuniqueid)
		{
			$this->errorOutput('类型不能为空');
		}
		
		$name = trim($this->input['name']);

		if (!$name)
		{
			$this->errorOutput('中文名不能为空');
		}
	
		$counts = intval($this->input['counts']);
		if (!$counts)
		{
			$this->errorOutput('数目不能为空');
		}
		
		$info = array(
			'member_id' => $member_id,
			'appid' => $appid,
			'appuniqueid' => $appuniqueid,
			'name' => $name,
			'counts' => $counts,
		);
		if (!$this->mMember->check_member_count($member_id, $appid))
		{
			$ret_info = $this->mMember->addMemberInfoCount($member_id, $info);
		}
		else 
		{
			$ret_info = $this->mMember->editMemberInfoCount($member_id, $info);
		}

		$this->addItem($ret_info);
		$this->output();
	}
	
	public function add_activity_account()
	{
		$this->mMember->add_activity_account($this->user['user_id']);
		$this->addItem($this->user['user_id']);
		$this->output();
	}
	
	public function del_activity_account()
	{
		$this->mMember->del_activity_account($this->user['user_id']);
		$this->addItem($this->user['user_id']);
		$this->output();
	}
	
	public function edit_member_mark()
	{
		$mark =  trim(urldecode($this->input['mark']),',');
		if(empty($mark))
		{
			$this->errorOutput('内容不为空！');
		}
		$ret = $this->mMember->edit_member_mark($mark,$this->user['user_id']);
		$this->addItem($ret);
		$this->output();
	}
	
	public function add_visit()
	{
		$ret = array();
		if($this->input['member_id'])
		{
			$ret = $this->mMember->add_visit($this->input['member_id'],intval($this->input['scan_num']));
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function mobile_edit()
	{
		$access_token = $this->input['access_token'];
		$mobile 	  = trim($this->input['mobile']);
		$member_id	  = intval($this->user['user_id']);
		
		if (!$access_token)
		{
			$this->errorOutput('NO_ACCESS_TOKEN');
		}
	
		if (!$mobile)
		{
			$this->errorOutput('手机号不能为空');
		}
		
		if(!preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/", $mobile))
		{
			$this->errorOutput('手机号不符合规范');
		}
		
		if (!$member_id)
		{
			$this->errorOutput('NO_MEMBER_ID');
		}
		
		$member = $this->mMember->_get_member_by_id($member_id);
		
		if (empty($member))
		{
			$this->errorOutput('该会员不存在或已被删除');
		}
		
		$ret_mobile = $this->mMember->check_mobile_exists($mobile, $member_id);
		
		if (!empty($ret_mobile))
		{
			$this->errorOutput('手机号已被使用过');
		}
		
		$data = array(
			'id'		=> $member_id,
			'mobile'	=> $this->settings['mobile_prefix'] . $mobile,
		);
		
		$ret = $this->mMember->update_member($data);
		
		if (!$ret)
		{
			$this->errorOutput('手机号修改失败');
		}
	
		$this->addItem('success');
		$this->output();
	}
	
	public function email_edit()
	{
		$access_token = $this->input['access_token'];
		$email 		  = trim($this->input['email']);
		$member_id	  = intval($this->user['user_id']);
		
		if (!$access_token)
		{
			$this->errorOutput('NO_ACCESS_TOKEN');
		}
	
		if (!$email)
		{
			$this->errorOutput('NO_EMAIL');
		}
		
		if (!$member_id)
		{
			$this->errorOutput('NO_MEMBER_ID');
		}
		
		$member = $this->mMember->_get_member_by_id($member_id,'', 'email');
		
		if (empty($member))
		{
			$this->errorOutput('该会员不存在或已被删除');
		}
		
		if ($email != $member['email'])
		{
			$ret_email = $this->mMember->_check_email($email);
			if ($ret_email == -3)
			{
				$this->errorOutput('邮箱不合法');
			}
			else if($ret_email == -4)
			{
				$this->errorOutput('邮箱已被注册');
			}
		}
		
		$ret = $this->mMember->email_edit($member_id, $email);
		
		if (!$ret)
		{
			$this->errorOutput('邮件修改失败');
		}
	
		$this->addItem('success');
		$this->output();
	}
	
	/**
	 * 编辑头像
	 * Enter description here ...
	 */
	public function avatar_edit()
	{
		$access_token = $this->input['access_token'];
		if (!$access_token)
		{
			$this->errorOutput('NO_ACCESS_TOKEN');
		}
		
		$member_id = intval($this->user['user_id']);

		if (!$member_id)
		{
			$this->errorOutput('NO_MEMBER_ID');
		}
		
		$avatar_url = trim($this->input['avatar_url']);
		$files = $_FILES['avatar'];
		
		if ($avatar_url)
		{
			$type = 1;
			if(!filter_var($avatar_url, FILTER_VALIDATE_URL))
			{
				$this->errorOutput('不是有效头像地址');
			}
			$files = $avatar_url;
		}
		else 
		{
			$type = 2;
			if (empty($files))
			{
				$this->errorOutput('NO_AVATAR');
			}
		}
		
		$ret = $this->mMember->avatarEdit($member_id, $files, $type);
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 修改密码
	 * Enter description here ...
	 */
	public function password_edit()
	{
		$access_token = $this->input['access_token'];
		$member_id	  = intval($this->user['user_id']);
		$old_password = trim($this->input['old_password']);
		$new_password = trim($this->input['new_password']);
		if (!$access_token)
		{
			$this->errorOutput('NO_ACCESS_TOKEN');
		}
		
		if (!$member_id)
		{
			$this->errorOutput('NO_MEMBER_ID');
		}

		if (!$new_password)
		{
			$this->errorOutput('请输入新密码');
		}
		
		$member = $this->mMember->_get_member_by_id($member_id,'', 'email, password, salt, member_name');
		$member = $member[$member_id];
		
		if (empty($member))
		{
			$this->errorOutput('该会员不存在或已被删除');
		}
	
		if ($member['password'])
		{
			if (!$old_password)
			{
				$this->errorOutput('请输入旧密码');
			}
		
			if (md5(md5($old_password) . $member['salt']) != $member['password'])
			{
				$this->errorOutput('旧密码不正确');
			}
		}
	
		if ($this->settings['ucenter']['open'] && $member['uc_id'])
		{
			$ret_uc = $this->mMember->uc_user_edit($member['uc_id'], $member['member_name'], '', $old_password, $new_password);
			
			if ($ret_uc < 0)
			{
				switch($ret_uc)
				{
					case -1 :
						$this->errorOutput('旧密码不正确');
						break;
					case -2 :
						$this->errorOutput('该用户不存在或已被删除');
						break;
					case -4 :
						$this->errorOutput('Email 格式有误');
						break;
					case -5 :
						$this->errorOutput('Email 不允许注册');
						break;
					case -6 :
						$this->errorOutput('该 Email 已经被注册');
						break;
					case -7 :
						$this->errorOutput('没有做任何修改');
						break;
					case -8 :
						$this->errorOutput('该用户受保护无权限更改');
						break;
					default:
						break;
				}
			}
		}
		
		$salt = hg_generate_salt();
		$password = md5(md5($new_password).$salt);
		
		$member_data = array(
			'id'		=> $member_id,
			'salt'		=> $salt,
			'password'	=> $password,
		);
		
		$ret = $this->mMember->update_member($member_data);
		
		$return = array(
			'member_id'	=> $ret['id'],
		);
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 修改昵称
	 * Enter description here ...
	 */
	public function nick_name_edit()
	{
		$access_token = $this->input['access_token'];
		if (!$access_token)
		{
			$this->errorOutput('NO_ACCESS_TOKEN');
		}
		
		$member_id = intval($this->user['user_id']);
		if (!$member_id)
		{
			$this->errorOutput('NO_MEMBER_ID');
		}
		
		$nick_name = trim(urldecode($this->input['nick_name']));
		if (!$nick_name)
		{
			$this->errorOutput('NO_NICK_NAME');
		}
		
		if ($this->mMember->check_nick_name_exists($nick_name, $member_id))
		{
			$this->errorOutput('NICK_NAME_EXIST');
		}
		
		$add_input = array(
			'nick_name' 	=> $nick_name,
			'update_time'	=> TIMENOW,
		);
	
		if ($this->settings['nick_name_unique']['sync_member_name'])
		{
			if ($this->mMember->check_member_name_exists($nick_name, '', $member_id))
			{
				$this->errorOutput('MEMBER_NAME_EXIST');
			}
			$add_input['member_name'] = $nick_name;
		}
		
		$ret = $this->mMember->member_info_edit($member_id, $add_input);
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 取用户信息
	 * Enter description here ...
	 */
	public function get_member_by_token()
	{
		$access_token = $this->input['access_token'];
		if (!$access_token)
		{
			$this->errorOutput('NO_ACCESS_TOKEN');
		}
		
		$member_id = intval($this->user['user_id']);
		if (!$member_id)
		{
			$this->errorOutput('NO_MEMBER_ID');
		}
		
		$ret = $this->mMember->_get_member_by_id($member_id,'', 'email, password, mobile');
		$ret = $ret[$member_id];
		
		if (empty($ret))
		{
			$this->errorOutput('NO_MEMBER_INFO');
		}
		
		$ret['member_id'] = $ret['id'];
		$ret['token'] 	  = $access_token;
		
		$avatar = array(
			'host'		=> $ret['host'],
			'dir'		=> $ret['dir'],
			'filepath'	=> $ret['filepath'],
			'filename'	=> $ret['filename'],
		);
		
		$ret['is_exist_email'] = $ret['email'] ? 1 : 0;
		$ret['is_exist_password'] = $ret['password'] ? 1 : 0;
			
		unset($ret['id'], $ret['host'], $ret['dir'], $ret['filepath'], $ret['filename'], $ret['password']);
		
		$ret['avatar'] = $avatar;
		
		$ret_bound = $this->mMember->get_member_bound_info($member_id, 'member_name, platform, platform_id, plat_member_name');
		
		$ret['bound'] = $ret_bound;
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
	
}

$out = new memberEditApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>