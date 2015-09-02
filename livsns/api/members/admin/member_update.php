<?php
/***************************************************************************
 * $Id: member_update.php 46659 2015-07-16 08:49:27Z tandx $
 ***************************************************************************/
define('MOD_UNIQUEID','members');//模块标识
require('./global.php');
require (ROOT_PATH.'lib/class/material.class.php');
require CUR_CONF_PATH . 'lib/member_medal.class.php';
require CUR_CONF_PATH . 'lib/member.class.php';
require CUR_CONF_PATH . 'lib/member_info.class.php';
class memberUpdateApi extends adminUpdateBase
{
	private $mMember;
	private $Members;
	private $mMemberInfo;
	private $member_medal;
	public function __construct()
	{
		parent::__construct();
		$this->verify_content_prms(array('_action'=>'manage'));
		$this->mMember = new member();
		$this->Members = new members();
		$this->mMemberInfo = new memberInfo();
		$this->member_medal = new medal();
        $this->Blacklist = new memberblacklist();
        $this->members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		try {
		$member_name = trim($this->input['member_name']);
		$nick_name   = trim($this->input['nick_name']);
		if(!$nick_name)
		{
			$nick_name = $member_name;
		}
		$password 	 = trim($this->input['password']);
		$mobile 	 = $this->input['mobile']?intval($this->input['mobile']):'';
		$email	 	 = $this->input['email']?trim($this->input['email']):'';
		$signature 	 = $this->input['signature']?trim(urldecode($this->input['signature'])):'';
		$identifierUserSystem = new identifierUserSystem();
		$identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
		if (!$member_name)
		{
			$this->errorOutput(NO_MEMBER_NAME);
		}
		if (!$password)
		{
			$this->errorOutput(NO_PASSWORD);
		}
		if(!hg_verify_mobile($mobile)&&!empty($mobile))
		{
			$this->errorOutput(MOBILE_NUMBER_FORMAT_ERROR);
		}
		$reg_mail=$this->Members->check_reg_mail($email,0,$identifier);
		if ($reg_mail==-4)
		{
			$this->errorOutput(EMAIL_FORMAT_ERROR);
		}
		elseif($reg_mail == -5)
		{
			$this->errorOutput(EMAIL_NO_REGISTER);
		}
		elseif ($reg_mail == -6)
		{
			$this->errorOutput(EMAIL_HAS_BINDED);
		}

		//头像
		$avatar = array();
		if ($_FILES['avatar']['tmp_name'])
		{
			$avatar = $_FILES['avatar'];
		}

		//验证会员名
		$ret_verify = $this->mMember->verify_member_name($member_name,0,$identifier);
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
		
		//更新积分
		$credits=array();
		if($this->input['credit']&&is_array($this->input['credit']))
		{
			foreach ($this->input['credit'] as $key=>$val)
			{
				if($val!=='')
				{
					$credits[$key] = intval($val);
				}
			}
			
			if($grade_credits_type = $this->Members->get_grade_credits_type(1))
			{
				if(isset($credits[$grade_credits_type['db_field']]) && $credits[$grade_credits_type['db_field']]<0)
				{
				  $this->errorOutput($grade_credits_type['title'].'不允许为负数');
				}
			}
			
		}
		
		$register_data = array();
		if($this->input['member_type'] == 'm2o'&&$this->settings['ucenter']['open']&&!$identifier)
		{
			$register_data['member_name'] = $member_name;
			$register_data['password'] = $password;
			$register_data['email'] = $email;
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
		}
		//随机串
		$salt = hg_generate_salt();

		//密码md5
		$md5_password = md5(md5($password) . $salt);
		if ($type = $this->input['member_type']?trim($this->input['member_type']):'m2o')
		{
			$platformInfo = $this->Members->get_platform_name($type);
			if(empty($platformInfo))
			{
				$this->errorOutput(REG_MEMBER_TYPE_ERROR);
			}
			$type_name = $platformInfo['name'];

		}
		$ip 		= hg_getip();
		$data = array(
			'guid'			=> guid(),
			'member_name'	=> $member_name,
			'password'		=> $md5_password,
			'mobile'		=> $mobile,
			'email'			=> $email,
			'signature'		=> $signature,
			'salt'			=> $salt,
			'type'			=> $type,
			'type_name'		=> $type_name,
			'status'		=> $this->settings['member_status'],
			'identifier'	=> $identifier,
			'appid'			=> $this->user['appid'],
			'appname'		=> $this->user['display_name'],
			'create_time'	=> TIMENOW,
			'update_time'	=> TIMENOW,
			'ip'			=> $ip,
			'reg_device_token' => 'admin',
			'reg_udid'      => 'admin',
		);

		//会员数据入库
		$ret = $this->mMember->create($data);
		if (!$ret['member_id'])
		{
			$this->errorOutput(MEMBER_DATA_ADD_FAILED);
		}

		$member_id = $ret['member_id'];
		
		if($credits&&is_array($credits))
		{	
			$credit_log=array(
		'app_uniqueid'=>APP_UNIQUEID,
		'mod_uniqueid'=>MOD_UNIQUEID,
		'action'=>$this->input['a'],
		'method'=>'admin_reg_members',
		'relatedid'=>$this->user['user_id'],
		'title'=>'积分变更',
		'remark'=>'管理员操作',
			);
			$this->Members->credits($credits,$member_id,$coef=1,false,false,true,null,array(),$credit_log);
		}

		//更新用户组
		$gid		=	intval($this->input['groupid']);
		$groupexpiry=   $this->input['groupexpiry']?trim($this->input['groupexpiry']):0;
		$this->Members->updategroup($member_id,$gid,$groupexpiry);
		//更新黑名单
		$deadline = !empty($this->input['blacklist'])?(!empty($this->input['isblack'])?$this->input['isblack']:-1):0;
		$this->Members->blacklist_set($member_id, $deadline);
		//更新勋章
		$medalid = !empty($this->input['medal_id'])?$this->input['medal_id']:array();
		$this->member_medal->edit_member_medal($member_id, $medalid);
		$data['member_id'] = $member_id;
		$this->mMemberInfo->extension_edit($member_id, $this->input['member_info'],$_FILES);//扩展信息编辑
		//绑定表
		if($type == 'm2o')
		{
			$platform_id = $this->settings['ucenter']['open'] &&$register_data['member_id'] > 0&&!$identifier?$register_data['member_id']:$member_id;
		}
		elseif($type =='shouji')
		{
			$platform_id = $member_name;
		}
		$bind_data = array(
			'member_id'		=> $member_id,
			'platform_id'	=> $platform_id,
			'nick_name'		=> $nick_name,
			'type'			=> $type,
			'type_name'		=> $type_name,
			'bind_time'		=> TIMENOW,
			'bind_ip'		=> $ip,
			'is_primary'	=> 1,
			'identifier'	=> $identifier,
			'inuc'	=> $this->settings['ucenter']['open'] && !$identifier && $register_data['member_id']>0?$register_data['member_id']:0,
			'reg_device_token' =>'admin',
			'reg_udid'      => 'admin',	
			);

			$ret_bind = $this->mMember->bind_create($bind_data);
			if (empty($ret_bind))
			{
				$this->errorOutput(BIND_DATA_ADD_FAILED);
			}
			$this->registerCreditRules($member_id, $type);
			//如果注册时填写邮箱则可以同时入绑定表
			if($data['email'])
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
			if($data['mobile'])
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
		
			//头像入库
			if (!empty($avatar))
			{
				$avatar = $this->mMember->add_material($avatar, $member_id);

				if (!empty($avatar))
				{
					$update_data = array(
					'member_id' => $member_id,
					'avatar' 	=> maybe_serialize($avatar),
					);

					$ret_updata = $this->mMember->update($update_data);

					if (!$ret_updata['member_id'])
					{
						$this->errorOutput(AVATAR_ADD_FAILED);
					}
				}
			}
			//会员痕迹
			$member_trace_data = array(
					'member_id'		=> $this->user['user_id'],
					'member_name'	=> $this->user['user_name'],
					'content_id'	=> $member_id,
					'title'			=> $member_name,
					'type'			=>'adminreg',
					'op_type'		=> '管理员注册会员',
					'appid'			=> $this->user['appid'],
					'appname'		=> $this->user['display_name'],
					'create_time'	=> TIMENOW,
					'ip'			=> hg_getip(),
					'device_token'	=> 'admin',
					'udid'          => 'admin',
			);
			$this->mMember->member_trace_create($member_trace_data);
			$this->addItem($member_id);
			$this->output();
		}
		catch (Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}

	public function update()
	{
	    //guid 会员唯一标示
        if($guid = $this->input['guid'])
        {
            $condition = ' AND guid="'.$guid.'"';
            $memberInfo = $this->mMember->get_member_info($condition);
            if($memberInfo)
            {
                $member_id = $memberInfo[0]['member_id'];
            }
        }
        else
        {
            $member_id	 = intval($this->input['member_id']);
        }
		$member_name = trim($this->input['member_name']);
		$nick_name   = trim($this->input['nick_name']);
		$password 	 = trim($this->input['password']);
		$mobile 	 = $this->input['mobile']?intval($this->input['mobile']):'';
		$email	 	 = $this->input['email']?trim($this->input['email']):'';
		$im_token	 	 = $this->input['im_token'] ? trim($this->input['im_token']) : '';
		$signature 	 = $this->input['signature']?trim(urldecode($this->input['signature'])):'';
		if (!$member_id)
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		$identifier = $this->mMember->getIdentifierForMemberId($member_id);
		if (!empty($member_name)&&!$this->mMember->isMemberNameUpdate($member_id,1))
		{
			$this->errorOutput(NOT_EDIT_MEMBERNAME);
		}
		if(!$nick_name)
		{
			$nick_name = $member_name?$member_name:$this->Members->get_member_name($member_id,false);
		}
		if(!hg_verify_mobile($mobile)&&!empty($mobile))
		{
			$this->errorOutput(MOBILE_NUMBER_FORMAT_ERROR);
		}
		$reg_mail=$this->Members->check_reg_mail($email,$member_id,$identifier);
		if ($reg_mail==-4)
		{
			$this->errorOutput(EMAIL_FORMAT_ERROR);
		}
		elseif ($reg_mail == -6)
		{
			$this->errorOutput(EMAIL_HAS_BINDED);
		}

		//头像
		$avatar = array();
		if ($_FILES['avatar']['tmp_name'])
		{
			$avatar = $_FILES['avatar'];
		}
		
		$data = array(
			'member_id'		=> $member_id,
			'update_time'	=> TIMENOW,
		);
		if($im_token)
		{
		    $data['im_token'] = $im_token;
		}
		if($mobile)
		{
		    $data['mobile'] = $mobile;
		}
		if($email)
		{
		    $data['email'] = $email;
		}
		if($signature)
		{
		    $data['signature'] = $signature;
		}
			//验证会员名
		if($member_name)
		{
			switch ($this->mMember->verify_member_name($member_name,$member_id,$identifier))
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
			$data['member_name'] = $member_name;
		}
		$member_name = $this->Members->get_member_name($member_id);
		if($this->settings['ucenter']['open']&&!$identifier){
			$is_password = $this->mMember->uc_user_edit($member_name[$member_id], $oldpw, $password, $email,1);
			if($is_password<0)
			{
				if($is_password == -4) {
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
			//随机串
			$salt = hg_generate_salt();
			//密码md5
			$md5_password = md5(md5($password) . $salt);

			$data['password'] = $md5_password;
			$data['salt']	  = $salt;
		}
		
		//更新积分
		if($this->input['credit']&&is_array($this->input['credit']))
		{
			$credit_log=array(
		'app_uniqueid'=>APP_UNIQUEID,
		'mod_uniqueid'=>MOD_UNIQUEID,
		'action'=>$this->input['a'],
		'method'=>'admin_update_members',
		'relatedid'=>$this->user['user_id'],
		'title'=>'积分变更',
		'remark'=>'管理员操作',
			);
			if($grade_credits_type = $this->Members->get_grade_credits_type(1))
			{
				if($this->input['credit'][$grade_credits_type['db_field']]<0)
				{
				  $this->errorOutput($grade_credits_type['title'].'不允许为负数');
				}
			}
			$this->Members->credits($this->input['credit'],$member_id,$coef=1,false,false,true,null,array(),$credit_log);
		}
		//更新用户组
		$gid		=	intval($this->input['groupid']);
		$groupexpiry=   $this->input['groupexpiry']?trim($this->input['groupexpiry']):0;
		$this->Members->updategroup($member_id,$gid,$groupexpiry);
		//更新黑名单
		$deadline = !empty($this->input['blacklist'])?(!empty($this->input['isblack'])?$this->input['isblack']:-1):0;
        if(!empty($this->input['blacklist']))
        {
            $this->Members->blacklist_set($member_id, $deadline);
        }

		//更新勋章
		$medalid = !empty($this->input['medal_id'])?$this->input['medal_id']:'';
		$this->member_medal->edit_member_medal($member_id, $medalid);
		//会员数据入库
		$ret = $this->mMember->update($data);
		if (!$ret['member_id'])
		{
			$this->errorOutput(MEMBER_DATA_UPDATE_FAILED);
		}

		$data['member_id'] = $member_id;
		$this->mMemberInfo->extension_edit($member_id,$this->input['member_info'],$_FILES);//扩展信息编辑

		//头像入库
		if (!empty($avatar))
		{
			$avatar = $this->mMember->add_material($avatar, $member_id);

			if (!empty($avatar))
			{
				$update_data = array(
					'member_id' => $member_id,
					'avatar' 	=> maybe_serialize($avatar),
				);

				$ret_updata = $this->mMember->update($update_data);

				if (!$ret_updata['member_id'])
				{
					$this->errorOutput(AVATAR_ADD_FAILED);
				}
			}
		}
		$bind_info = array();
		if($nick_name)
		{
			$bind_info = array('nick_name' => $nick_name);
		}
		if($bind_info)
		{
			$this->mMember->bind_update($bind_info,'WHERE member_id = \''.$member_id.'\'');	
		}
		//会员痕迹
		$member_trace_data = array(
			'member_id'		=> $this->user['user_id'],
			'member_name'	=> $this->user['user_name'],
			'content_id'	=> $member_id,
			'title'			=> $member_name[$member_id],
			'type'			=>'adminedit',
			'op_type'		=> '管理员更新会员资料',
			'appid'			=> $this->user['appid'],
			'appname'		=> $this->user['display_name'],
			'create_time'	=> TIMENOW,
			'ip'			=> hg_getip(),
			'device_token'	=> 'admin',
			'udid'          => 'admin',	
		);
		$this->mMember->member_trace_create($member_trace_data);

		$this->addItem($member_id);
		$this->output();
	}

	public function delete()
	{		
		$member_id = trim($this->input['id']);
		if (!$member_id)
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		$this->settings['ucenter']['open']&&$this->mMember->delUcUser($member_id);
		if($this->mMember->delete($member_id))
		{
			$this->Members->force_logout_user($member_id,null,1);
			$this->member_medal->del_member_medal($member_id);//删除勋章记录
		}
		else {
			$this->errorOutput(DELETE_FAILED);
		}
		$this->addItem($member_id);
		$this->output();
	}

	public function audit()
	{
		$member_id = trim($this->input['member_id']);

		if (!$member_id)
		{
			$this->errorOutput(NO_DATA_ID);
		}

		$field = 'member_id, status';
		$condition   = " AND member_id = " . $member_id;
		$member_info = $this->mMember->get_member_info($condition, $field);
		$member_info = $member_info[0];

		if (empty($member_info))
		{
			$this->errorOutput(NO_MEMBER);
		}

		$status = $member_info['status'];

		$ret = 0;

		$update_data = array(
			'member_id'	=> $member_id,
		);

		if (!$status) //启动
		{
			$update_data['status'] = 1;
			$ret = 1;
		}
		else	//停止
		{
			$update_data['status'] = 0;
			$ret = 0;
		}

		$this->mMember->update($update_data);

		$this->addItem($ret);
		$this->output();
	}

	public function verify()
	{
		$member_id = trim($this->input['member_id']);
		if (!$member_id)
		{
			$this->errorOutput(NO_DATA_ID);
		}

		$field = 'member_id, isVerify';
		$condition   = " AND member_id = " . $member_id;
		$member_info = $this->mMember->get_member_info($condition, $field);
		$member_info = $member_info[0];
		if (empty($member_info))
		{
			$this->errorOutput(NO_MEMBER);
		}

		$isVerify = $member_info['isVerify'];

		$ret = 0;

		$update_data = array(
			'member_id'	=> $member_id,
		);

		if (!$isVerify) //启动
		{
			$update_data['isVerify'] = 1;
			$ret = 1;
		}
		else	//停止
		{
			$update_data['isVerify'] = 0;
			$ret = 0;
		}

		if($this->mMember->update($update_data))
		{
			if(!$isVerify)
			{
				$this->Members->credits_rule('members_verify',$member_id,$coef=1,$update=1,APP_UNIQUEID);
			}
		}
		$this->addItem($ret);
		$this->output();
	}

	/**
	 *
	 * 黑名单处理函数
	 */
	public function blacklistset()
	{
        if($guid = $this->input['guid'])
        {
            $condition = ' AND guid="'.$guid.'"';
            $memberInfo = $this->mMember->get_member_info($condition);
            if($memberInfo)
            {
                $member_id = $memberInfo[0]['member_id'];
            }
        }
        else
        {
            $member_id	 = intval($this->input['member_id']);
        }

		$deadline=trim($this->input['deadline']);
        $black_device = intval($this->input['black_device']);
        $black_ip = intval($this->input['black_ip']);
        if($deadline == 0)
        {
            $type = 0;
        }
        else
        {
            $type = intval($this->input['type']) ? $this->input['type'] : 1;
        }

		$res=$this->Members->blacklist_set($member_id, $deadline,$type);
        //官方封禁
        $this->initblacklist(array('member_id' => $member_id),$black_device,$black_ip,$type);

		if($res&&$deadline<0||strtotime($deadline)>TIMENOW)
		{
			$re=array('member_id'=>$member_id,'isblack'=>1,'type' => $type);
		}
		else
		{
			$re=array('member_id'=>$member_id,'isblack'=>0,'type' => $type);
		}
		$this->addItem($re);
		$this->output();
	}

	public function sort(){}
	public function publish(){}

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

	public function unbind()
	{
		$bind = trim($this->input['bind']);
		$bind_arr = explode('_', $bind);
		if(count($bind_arr)==3)
		{
			$condition = ' AND m.member_id = '.intval($bind_arr[0]);
			$bindinfo = $this->mMember->get_bind_info($condition,'mb.*,m.member_name,m.avatar');
			if($bindinfo)
			{
				$sql = '';
				$delete_bind = array();
				foreach ($bindinfo as $bind_plate)
				{
					if($bind_plate['type'] == $bind_arr[2] && $bind_plate['platform_id'] == $bind_arr[1])
					{
						$delete_bind = $bind_plate;
						$sql = "DELETE FROM " . DB_PREFIX . 'member_bind WHERE member_id='.$bind_arr[0].' AND type="'.$bind_arr[2].'" AND platform_id="'.$bind_arr[1].'"';
						if($bind_plate['is_primary'])
						{
							$this->errorOutput('主帐号关系不允许解除!');
						}
						break;
					}
				}
				if($sql)
				{
					$this->db->query($sql);
					$this->addItem($delete_bind);
					//会员痕迹
					$member_trace_data = array(
					'member_id'		=> $this->user['user_id'],
					'member_name'	=> $this->user['user_name'],
					'content_id'	=> $bind_arr[0],
					'title'			=> $bindinfo[0]['member_name'],
					'type'			=> 'adminunbind',
					'op_type'		=> '管理员解除'.$bind_plate['type_name'].'绑定关系',
					'appid'			=> $this->user['appid'],
					'appname'		=> $this->user['display_name'],
					'create_time'	=> TIMENOW,
					'ip'			=> hg_getip(),
					'device_token'	=> 'admin',
					'udid'          => 	$bindinfo[0]['reg_udid'],	
					);
					$this->mMember->member_trace_create($member_trace_data);
				}else
				{
					$this->errorOutput('绑定关系不存在');
				}
			}
			else
			{
				$this->errorOutput('绑定关系不存在');
			}
			$this->output();
		}
		else {
			$this->errorOutput('参数不完整，解除绑定需要:会员id_平台id_平台类型,例:888_888_m2o');
		}
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

    /**
     * 设置设备 IP黑名单
     *
     * @param $params
     * @param $black_device
     * @param $black_ip
     * @return array|bool
     */
    private function initblacklist($params,$black_device,$black_ip,$type)
    {
        //type 1:app应用管理员拉黑 2:官方拉黑

        $limit = "limit 0,5";
        //获取用户登录注册日志
        $field = '*';
        $key='';
        $orderby = 'ORDER BY create_time DESC';
        $isBatch = 1;
        $member_log = $this->mMember->getMemberTrace($params,$field,$key,$orderby,$isBatch,$limit);
        $device_arr = $this->unique_arr($member_log,'udid');
        $ip_arr = $this->unique_arr($member_log,'ip');
        $uid = $params['member_id'];
        $member_info = $this->mMember->detail($uid);
        //设置device黑名单
        if($black_device)
        {
            foreach ($device_arr as $k=>$v)
            {
                if($v && !in_array($v, array('unknown','admin','www')))
                {
                    //查询是否存在
                    $device_log = $this->Blacklist->detailDeviceBlacklist(array('device_token'=>$v,'identifier'=>$member_info['identifier']));
                    if(!$device_log)
                    {
                        $Devicedata = array(
                            'device_token'  => $v,
                            'member_id' => $uid,
                            'member_name' => $member_info['member_name'],
                            'type' => $type,
                            'identifier' => $member_info['identifier'],
                            'deadline'   => '-1',
                        );
                        $res_Device = $this->Blacklist->createDeviceBlacklist($Devicedata);
                    }
                    else
                    {
                        //增加黑名单统计次数
                        $total = $device_log[0]['total'] + 1;
                        $res_Device = $this->Blacklist->updateDeviceBlacklist(array('total' => $total,'deadline' => '-1','type' => $type),array('device_token'=>$v,'identifier'=>$member_info['identifier']));
                    }
                }
            }
            //强制用户退出
            $this->members->force_logout_user($uid);
        }
        else
        {
            //取消黑名单  将deadine置为0
            foreach ($device_arr as $k=>$v)
            {
                $res_Device = $this->Blacklist->updateDeviceBlacklist(array('deadline' => '0','type' => 0),array('device_token'=>$v,'identifier' => $member_info['identifier']));
            }
        }

        //设置ip黑名单
        if($black_ip)
        {
            foreach ($ip_arr  as $k => $v)
            {
                if($v && !in_array($v, array('unknown')))
                {
                    //查询是否存在
                    $ip_log = $this->Blacklist->detailIpBlacklist(array('ip'=>ip2long($v),'identifier'=>$member_info['identifier']));
                    if(!$ip_log)
                    {
                        $Ipdata = array(
                            'ip'  => ip2long($v),
                            'member_id' => $uid,
                            'member_name' => $member_info['member_name'],
                            'type' => $type,
                            'identifier' => $member_info['identifier'],
                            'deadline'   => '-1',
                        );
                        $res_Ip = $this->Blacklist->createIpBlacklist($Ipdata);
                    }
                    else
                    {
                        //增加黑名单统计次数
                        $total = $ip_log[0]['total'] + 1;
                        $res_Ip = $this->Blacklist->updateIpBlacklist(array('total' => $total,'deadline' => '-1','type' => $type),array('identifier'=>$member_info['identifier'],'ip'=>ip2long($v)));
                    }
                }
            }
            //强制用户退出
            $this->members->force_logout_user($uid);
        }
        else
        {
            //取消黑名单  将deadine置为0
            foreach ($ip_arr  as $k=>$v)
            {
                $res_Ip = $this->Blacklist->updateIpBlacklist(array('deadline' => '0','type' => 0),array('ip'  => ip2long($v),'identifier' => $member_info['identifier']));
            }
        }
        if($res_Device || $res_Ip)
        {
            $info = array();
            $info['device_token'] = $res_Device;
            $info['ip'] = $res_Ip;
            return $info;
        }
        return false;
    }


    private function unique_arr($arr2D,$key)
    {
        $temp = array();
        foreach($arr2D as $k=>$v)
        {
            array_push($temp,$v[$key]);
        }
        $res = array_unique($temp);
        return $res;
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