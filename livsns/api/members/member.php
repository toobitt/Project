<?php
/***************************************************************************
 * $Id: member.php 47018 2015-07-31 04:01:26Z develop_tong $
 ***************************************************************************/
define('MOD_UNIQUEID','members');//模块标识
require('./global.php');
class memberApi extends outerReadBase
{
	private $mMember;
	private $mMemberExtensionField;
	private $mMemberInfo;
    private $friend;
	public function __construct()
	{
		parent::__construct();

		require_once CUR_CONF_PATH . 'lib/member.class.php';
		$this->mMember = new member();
		
		require_once CUR_CONF_PATH . 'lib/member_info.class.php';
		$this->mMemberInfo = new memberInfo();
		$this->mMemberExtensionField = new memberExtensionField();
		
		require_once CUR_CONF_PATH . 'lib/member_extension_sort.class.php';
		$this->mmemberextensionsort = new mmemberextensionsort();

        require_once CUR_CONF_PATH . 'lib/member_friend_mode.php';
        $this->friend = new member_friend_mode();

		$this->Members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 取出会员列表
	 * Enter description here ...
	 */
	public function show()
	{
		try{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		$orderby='';
		$field='m.member_id,m.guid,m.member_name,mb.nick_name,m.type,m.type_name,m.gid,m.gradeid,m.groupexpiry,m.avatar,m.credits,m.status,m.isVerify,m.identifier,m.appid,m.appname,m.create_time,m.update_time';
		$info = $this->mMember->show($condition, $offset, $count,$orderby,'',$field);
		$member_id=array_keys($info);
		//获取勋章信息
		$member_medal=$this->Members->get_member_medal($member_id,$field='member_id,medalid,expiration',2);
		$medal_info = $this->Members->get_medal(array_keys($member_medal),'id,name,image,brief');
		$info = $this->Members->make_medal($info, $medal_info, $member_medal);
		// 积分信息
		$credits=$this->Members->membercredit($member_id,$is_on=1);
		$staricon=$this->Members->staricon();
		if (!empty($info))
		{
			foreach ($info AS $v)
			{

				if(empty($v['graname']))
				{
					$grade_info=$this->Members->updategrade($v['member_id']);
					if($grade_info&&is_array($grade_info))
					{
						foreach ($grade_info as $key => $val)
						{
							$v[$key] = $val;
						}
					}
				}
				if(empty($v['groupname']))//判断是否无效组
				{
					$group_info=$this->Members->updategroup($v['member_id'],0);
					if($group_info&&is_array($group_info))
					{
						foreach ($group_info as $key => $val)
						{
							$v[$key] = $val;
						}
					}
				}
				$v['credit']=$credits[$v['member_id']];
				$v['showstar']=$this->Members->showstar($v['starnum'], $staricon);
				unset($v['password'], $v['salt'],$v['starnum']);
				$this->addItem($v);
			}
		}

		$this->output();
		}
		catch(Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}

	public function detail()
	{
		$is_private=false;//是否允许显示隐私信息
		if($this->input['member_id'])
		{
			$field='m.member_id,m.guid,m.member_name,m.signature,m.type,m.type_name,m.gid,m.gradeid,m.groupexpiry,m.avatar,m.credits,m.status,m.isVerify,m.appid,m.appname,m.create_time,m.update_time,m.last_login_time,m.final_login_time,mb.nick_name,g.name as groupname,g.starnum,g.usernamecolor,g.icon as groupicon';
			$condition = " AND m.member_id = " . intval($this->input['member_id']);
		}
		else if($this->input['guid'])
		{
			$field='m.member_id,m.guid,m.member_name,m.signature,m.type,m.type_name,m.gid,m.gradeid,m.groupexpiry,m.avatar,m.credits,m.status,m.isVerify,m.appid,m.appname,m.create_time,m.update_time,mb.nick_name,g.name as groupname,g.starnum,g.usernamecolor,g.icon as groupicon';
			$condition = " AND m.guid = '" . trim($this->input['guid'])."'";
		}
		else if($this->user['user_id'])
		{
			$field='m.*,g.name as groupname,g.starnum,g.usernamecolor,g.icon as groupicon';
			$is_private=true;//是否只可以取隐私数据
			$condition = " AND m.member_id = " . intval($this->user['user_id']);
		}
		else
		{
			$this->errorOutput(NO_MEMBER_ID);
		}

		//会员信息
		$leftjoin = '';
		if(!$is_private)
		{
			$leftjoin=' LEFT JOIN '.DB_PREFIX.'member_bind as mb ON mb.member_id = m.member_id';
		}
		$leftjoin .=' LEFT JOIN '.DB_PREFIX.'group as g ON m.gid=g.id';
		$member = $this->mMember->get_member_info($condition,$field,$leftjoin);
		$member = $member[0];
		if (empty($member))
		{
			$this->errorOutput(NO_MEMBER);
		}
		$member_id=$member['member_id'];
		// 积分信息
		$credits=$this->Members->membercredit($member_id,$is_on=1,true,true);
		$member['credit'] = $credits[$member_id];
		$this->Members->setMemberId($member_id);
		$gradeInfo = $this->Members->getMemberGrade(array($member['gradeid']),$member['credit']);
		if(is_array($gradeInfo))
		{
			$member = array_merge($member,$gradeInfo);
		}
		if(empty($member['groupname']))//判断是否无效组
		{
			$group_info = $this->Members->updategroup($member_id,0);
			if($group_info&&is_array($group_info))
			{
				foreach ($group_info as $key => $val)
				{
					$member[$key] = $val;
				}
			}
		}
		//获取勋章信息
		$member_medal=$this->Members->get_member_medal(array($member_id),$field='member_id,medalid,expiration',2);
		$medal_info = $this->Members->get_medal(array_keys($member_medal),'id,name,image,brief');
		$member = $this->Members->make_medal(array($member_id=>$member), $medal_info, $member_medal,false);
		//绑定信息
		//星星图标数据开始
		$staricon=$this->Members->staricon();
		$member['showstar']=$this->Members->showstar($member['starnum'], $staricon);
		unset($member['starnum']);
		//星星图标数据结束
		//获取用户签到信息
		include CUR_CONF_PATH . 'lib/member_sign.class.php';
		$Osign = new sign();
		$member['isSign'] = $Osign->getIsSign($member_id);
		//获取用户签到信息结束
		if($is_private)
		{
			$condition = " AND mb.member_id = " . $member_id;
			$bind = $this->mMember->get_bind_info($condition);
			$blacklist=$this->Members->blacklist($member_id);
			$purview=$this->Members->showpurview($member['gid']);

			$is_exist_password = trim($member['password']) ? 1 : 0;
			unset($member['password'], $member['salt']);
		}
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
		    	
		    if(intval($this->input['app_id']))
		    {
		        $extension = $this->mMemberInfo->extendDataProcessByApp($member_info,1,$this->input['app_id']);
		    }
		    else
		    {
		        $extension = $this->mMemberInfo->extendDataProcess($member_info,1,$condition);
		    }
		}

		$return = $member;
		if($is_private)
		{
			$return['bind'] 	 = $bind;
		}
		if($is_private)
		{
			//是否绑定手机
			if($bind)
			{
				$this->mMember->ExportbindData($bind, $return);
			}

			if(!$return['nick_name']){
				$return['nick_name'] = $return['member_name'];
			}
            $return['nick_name'] = hg_hide_mobile($return['nick_name']);
			$return['mobile'] = $member['mobile'] ? $member['mobile'] : $return['mobile'];
			$return['email'] = $member['email'] ? $member['email'] : $return['email'];
			$return['is_exist_password'] = $is_exist_password;
			$return['blacklist'] = $blacklist[$member['member_id']];
			$return['purview'] = $purview[$member['gid']]?$purview[$member['gid']]:array();
			$return['isComplete'] = isUserComplete($member['type']);
			$return['profilePercent'] = $this->mMember->profilePercentComplete($return);
		}
		//
		if($this->input['version'] == CLIENT_VERSION)
		{
			$return = array(
			'nick_name'=>$return['member_name'],
			'member_id'=>$return['member_id'],
			'avatar'=>$return['avatar'] ? $return['avatar'] : array('host'=>"",'dir'=>"",'filepath'=>"",'filename'=>""),
			);
			if($is_private)
			{
				$return['token']=$this->input['access_token'];
				$return['email']=$return['email'];
				$return['is_exist_password']=$is_exist_password;
				$return['is_exist_email']=$return['email'] ? 1 : 0;
				$return['mobile']=$return['mobile'];
			}
		}
		$return['extension'] = $extension ? $extension : array();

        //查询好友关系
        if($this->input['member_id'])
        {
            $friend_ship = $this->getFriendship($member_id);
            $return['friendship'] =  $friend_ship;
        }

		//		
		$this->addItem($return);
		$this->output();
	}
	/**
	 *
	 * 根据会员名或者会员id获取会员基本信息
	 */
	public function get_member_info()
	{
		if($this->input['member_name'])
		{
			$member_name=$this->input['member_name'];
			if(empty($member_name))
			{
				return false;
			}
			if(is_string($member_name)&&(stripos($member_name, ',')!==false))
			{
				$member_name=explode(',', trim(urldecode($member_name)));//转为数组方便字符串转换
				if($member_name&&is_array($member_name))
				{
					$member_name=trim("'".implode("','", $member_name )."'");
					$where=' AND m.member_name IN( '.$member_name.')';
				}
			}
			elseif ($member_name&&is_array($member_name))
			{
				$member_name=trim("'".implode("','", $member_name )."'");
				$where=' AND m.member_name IN( '.$member_name.')';
			}
			else
			{
				$member_name=trim(urldecode($member_name));
				$where=' AND m.member_name =\''.$member_name.'\'';
			}
			$type=$this->input['type']?trim(urldecode($this->input['type'])):'m2o';
			$where .= ' AND m.type=\''.$type.'\'';
			$m_field='member_name';
		}
		elseif ($this->input['member_id'])
		{
			$member_id=$this->input['member_id'];
			if(!$member_id)
			{
				return false;
			}
			if(is_string($member_id)&&!is_numeric($member_id)&&(stripos($member_id, ',')!==false))
			{
				$member_id=explode(',', $member_id);
				$member_id=array_filter($member_id,"clean_array_null");
				$member_id=array_filter($member_id,"clean_array_num");
				if($member_id&&is_array($member_id))
				{
					$member_id=trim(implode(',', $member_id));
					$where=' AND m.member_id IN( '.$member_id.')';

				}
			}
			elseif ($member_id&&is_array($member_id))
			{
				$member_id=array_filter($member_id,"clean_array_null");
				$member_id=array_filter($member_id,"clean_array_num");
				$member_id=trim(implode(',', $member_id));
				$where=' AND m.member_id IN( '.$member_id.')';
			}
			else {
				$member_id=intval($member_id);
				$where=' AND m.member_id ='.$member_id;
			}
			$m_field='member_id';
		}
		//会员信息
		if(empty($where))
		{
			$this->errorOutput('请传会员名或者会员id');
		}
   		$field='m.member_id,m.member_name,mb.nick_name,m.avatar,m.signature,m.mobile,m.gid,m.im_token,m.type,m.identifier,m.last_login_udid,m.last_login_device,g.name as groupname,g.usernamecolor,g.icon as groupicon';
		$leftjoin=' LEFT JOIN '.DB_PREFIX.'member_bind as mb ON mb.member_id = m.member_id';
		$leftjoin .=' LEFT JOIN '.DB_PREFIX.'group as g ON m.gid=g.id';
		$condition = $where;
		$info = $this->mMember->get_member_info($condition,$field,$leftjoin);
		if(is_array($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem_withkey($v[$m_field], $v);
			}
		}
		else $this->addItem($info);
		$this->output();

	}


	/**
	 *
	 * 取积分和经验接口，带id键值输出.
	 */
	public function get_member_credits()
	{
		if($this->input['member_id'])
		{
			$member_id = $this->input['member_id'];//历史参数待废弃
		}
		else if ($this->user['user_id'])
		{
			$member_id = $this->user['user_id'];
		}
		if(empty($member_id))
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		$member_credits = $this->Members->membercredit($member_id);
		if($member_credits&&is_array($member_credits))
		{
			foreach ($member_credits as $k=>$v)
			{
				$data['credits'] = $this->Members->credits_count($id=0,$v,false);
				$data['credit'] = $v;
				$this->addItem_withkey($k, $data);
			}
		}
		else $this->addItem(false);
		$this->output();
	}
	/**
	 *
	 * 取用户自己积分和经验接口，不带id键值输出.
	 */
	public function getMemberCredits()
	{
		if ($this->user['user_id'])
		{
			$member_id = $this->user['user_id'];
		}
		if(empty($member_id))
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		$member_credits = $this->Members->membercredit($member_id);
		if($member_credits&&is_array($member_credits))
		{
				$data['credits'] = $this->Members->credits_count(0,$member_credits[$member_id],false);
				$data['credit'] = $member_credits[$member_id];
				$this->setAddItemValueType();
				$this->addItem($data);
		}
		$this->output();
	}
	/**
	 *
	 * 获取积分日志
	 */
	public function get_credit_log()
	{
		$outPutType = intval($this->input['outputtype']);
		if($this->input['member_id'])
		{
			$condition = " AND member_id = " . intval($this->input['member_id']);
		}
		else if($this->user['user_id'])
		{
			$condition = " AND member_id = " . intval($this->user['user_id']);
		}
		else
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		include (CUR_CONF_PATH."lib/member_credit_log.class.php");
		$credit_log = new credit_log();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		$credit_type=$this->Members->get_credit_type();
		$credit_type_field='';
		if($credit_type)
		{
			$credit_type_field=','.implode(',', array_keys($credit_type));
		}
		$info=$credit_log->show($condition, $offset, $count,'icon,title,remark'.$credit_type_field.',dateline');
		$outPut = array();
		if (!empty($info))
		{
			if($credit_type)
			{
				foreach ($info AS $v)
				{
					if($credit_type&&is_array($credit_type))
					foreach ($credit_type as $kk=>$vv)
					{
						if($v[$kk]>0)
						{
							$v[$kk] ='+'.$v[$kk].$vv['title'];
						}
						elseif ($v[$kk]<0)
						{
							$v[$kk] .=$vv['title'];
						}
					}
					if(!$outPutType)
					{
						$v['dateline'] 	= date('m/d', $v['dateline']);
						$outPut[] = $v;
					}
					elseif($outPutType) {
						$v['dateline'] 	= date('Y年m月d日', $v['dateline']);
						$outPut[$v['dateline']]['dateline'] = $v['dateline'];
						$outPut[$v['dateline']]['lists'][] = $v;
					}
				}
			}
		}
		if($outPut&&is_array($outPut))
		{
			foreach ($outPut as $k => $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();

	}

	/*
	 * 分类输出函数
	 */
	public function extensionsort()
	{
		$info 	= $this->mmemberextensionsort->show();

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}

		$this->output();
	}

	public function count()
	{
		try{
			$condition = $this->get_condition();
			$info = $this->mMember->count($condition);
			$this->addItem($info);
			$this->output();
		}
		catch (Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}

	private function get_condition()
	{
		$condition = " AND m.status = 1 ";

		if((isset($this->input['k']) && !empty($this->input['k']))||(trim($this->input['key']) || trim(urldecode($this->input['key']))== '0'))
		{
			if(isset($this->input['k']) && !empty($this->input['k']))//兼容老的搜索
			{
				$key = trim($this->input['k']);
			}
			elseif(trim($this->input['key']) || trim(urldecode($this->input['key']))== '0')
			{
				$key = trim($this->input['key']);
			}
			$binary = '';//不区分大小些
			if(defined('IS_BINARY') && !IS_BINARY)//区分大小些
			{
				$binary = 'binary ';
			}
			$condition .= ' AND ' . $binary . ' m.member_name like \'%'.$key.'%\'';
		}
		
		if(isset($this->input['identifier']))
		{
			$identifierUserSystem = new identifierUserSystem();
			$identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
			$condition .= " AND m.identifier = " . $identifier;
		}

		if ($this->input['member_id'])
		{
			$condition .= " AND m.member_id IN (" . trim($this->input['member_id']) . ")";
		}

		if($this->input['member_type'])
		{
			$condition .= " AND mb.type = '" . trim(urldecode($this->input['member_type']))."'";
		}

		return $condition;
	}
	//根据手机号和验证码验证是否存在
	public function check_verifycode()
	{
		$mobile = $this->input['mobile'];
		$code = $this->input['verifycode'];
		$output = array('result'=>"0");
		if($mobile && $code)
		{
			require_once CUR_CONF_PATH . 'lib/sms_server.class.php';
			$mSmsServer = new smsServer();
			$verifycode = $mSmsServer->get_verifycode_info($mobile, $code);
			if($verifycode)
			{
				//$mSmsServer->mobile_verifycode_delete($mobile, $code);
				$output['result'] = "1";
			}
		}
		$this->addItem($output);
		$this->output();
	}
	/**
	 *
	 * 查询会员之间黑名单关系.
	 */
	public function check_friend_blacklist()
	{
		$member_id=$this->input['member_id']?intval($this->input['member_id']):0;
		$fb_uid=$this->input['fb_uid']?intval($this->input['fb_uid']):0;
		if (!$member_id)
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		if (!$fb_uid)
		{
			$this->errorOutput(FRIEND_BLACKLIST_ID);
		}
		$checkfriend_blacklist=$this->Members->check_friend_blacklist($member_id,$fb_uid);
		$this->addItem_withkey('result', $checkfriend_blacklist);
		$this->output();
	}
	/**
	 *
	 * 检测会员是否是黑名单 ...
	 */
	public function check_blacklist()
	{
		$member_id=$this->input['member_id']?intval($this->input['member_id']):0;
		if (!$member_id)
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		$blacklist=$this->Members->blacklist($member_id);
		if(is_array($blacklist)&&$blacklist)
		{
			foreach ($blacklist as $member_id => $data)
			{
				$this->addItem_withkey($member_id, $data);
			}
		}
		else $this->addItem($blacklist);
		$this->output();
	}
	/**
	 *
	 *用户名状态检测
	 */
	public function check_membername_exists()
	{
		try{
		$member_name  = $this->input['member_name'];
		$identifierUserSystem = new identifierUserSystem();
		$identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
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
				$this->addItem(array('member_name'=>$member_name));
				$this->output();
				break;
		}
		}
		catch(Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}
	/**
	 *
	 *根据会员类型获取已绑定会员(默认:手机类型)列表,支持传platform_id判断是否已被绑定.
	 */
	public function get_bindinfo()
	{

		try{
		$condition =$this->get_condition();
		if(empty($this->input['member_type']))
		{
			$condition .= " AND m.status = 1 AND mb.type = 'shouji'";
		}
		if($this->input['platform_id'])//支持查询邮箱或者手机(根据会员绑定类型)是否被绑定,返回空未绑定,有资料返回已绑定.
		{
			$condition .="AND platform_id=".intval($this->input['platform_id']);
		}
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$orderby = $orderby ? $orderby : " ORDER BY m.member_id DESC ";

		$sql = "SELECT m.member_id,m.member_name,mb.nick_name,m.type,m.mobile,m.email,m.create_time,m.update_time,m.ip,mb.type as mbtype,mb.platform_id FROM " . DB_PREFIX . "member as m
		LEFT JOIN ".DB_PREFIX."member_bind as mb ON mb.member_id=m.member_id ";
		$sql.= " WHERE 1 " . $condition .' GROUP BY m.member_id'. $orderby . $limit;
		$q = $this->db->query($sql);

		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
			if($row['mbtype']=='shouji'&&empty($row['mobile']))
			{
				$row['mobile']=$row['platform_id'];
			}
			unset($row['platform_id'],$row['mbtype']);
			$info[$row['member_id']] = $row;
		}

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}

		$this->output();
		}
		catch(Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}
	/**
	 *
	 * 消费积分是否满足检测
	 */
	public function check_consume_credits()
	{
		if($this->user['user_id']||$this->input['member_id'])
		{
			$member_id=$this->input['member_id']?intval($this->input['member_id']):intval($this->user['user_id']);
		}
		$credit_type=$this->Members->get_trans_credits_type();
		if(empty($credit_type))
		{
			$this->errorOutput('无可消费积分,请联系管理员');
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
			if($old_credit<$new_credit)
			{
				$this->addItem(false);
				$this->output();
			}
			$this->addItem(true);
			$this->output();
		}
		else
		{
			$this->errorOutput('未传消费积分数值');
		}
	}
	/**
	 *
	 * 获取用户勋章信息
	 */
	public function get_member_medal()
	{
		$member_id=intval($this->user['user_id']);
		if(empty($member_id))
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		if($member_id)
		{
			$member_info = $this->Members->get_member_info('AND member_id ='.($member_id),'m.member_name,m.avatar','','member_id',false);
		}
		//获取勋章信息
		$member_medal=$this->Members->get_member_medal(array($member_id),$field='member_id,medalid,expiration',2);
		$medal_info = $this->Members->get_medal(array_keys($member_medal),'id,name,image,brief');
		$member = $this->Members->make_medal(array($member_id=>$member_info), $medal_info, $member_medal,false);
		if($member&&is_array($member))
		{
			foreach ($member as $k => $v)
			{
				$this->addItem_withkey($k, $v);
			}
		}
		else {
			$this->addItem($member);
		}
		$this->output();
	}
/**
 * 
 * 获取会员扩展信息 ...
 */
	public function extension_fields()
	{
		$extension = $this->mMemberExtensionField->show();
		$ret = array();
		if($extension && is_array($extension))
		{
			foreach ($extension as $k=>$v)
			{
				$ret[] = array(
					'field'=>$v['extension_field'],
					'field_name'=>$v['extension_field_name']
				);
			}
		}
		//会员表中的部分会员信息字段
		$member = array();
		if($this->input['is_base_field'])
		{
			$member = $this->base_fields(1);
		}
		$extend = array_merge($member,$ret);
		if(is_array($extend))
		foreach ($extend as $v)
		$this->addItem($v);
		else $this->addItem($extend);
		$this->output();
	}
	/**
	 * 
	 * 会员基础资料字段 ...
	 */
	public function base_fields($is_ret = 0)
	{
		$member_base_info = $this->settings['member_base_info'];
		if($is_ret)return $member_base_info;
		if(is_array($member_base_info))
		foreach ($member_base_info as $v)
		$this->addItem($v);
		else $this->addItem($member_base_info);
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

    /**
     * 获取简单的会员信息
     */
    public function getSimpleInfo()
    {
        if($this->input['member_id'])
        {
            $member_id = intval($this->input['member_id']);
        }
        elseif($this->user['user_id'])
        {
            $member_id = $this->user['user_id'];
        }
        if(!$member_id)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        $condition = 'AND m.member_id='.$member_id;
        $field = 'm.member_id,m.member_name,mb.nick_name,m.avatar';
        $leftjoin = ' LEFT JOIN ' . DB_PREFIX . 'member_bind mb ON m.member_id=mb.member_id';
        $info = $this->Members->get_member_info($condition,$field,$leftjoin);

        if($this->user['user_id'])
        {
            $friendship = $this->getFriendship($member_id);
        }

        if($info)
        {
            $info['friendship'] = $friendship ? $friendship : array();
            $this->addItem($info);
            $this->output();
        }
        else
        {
            $this->errorOutput(NO_MEMBER_INFO);
        }
    }

	/**
	 * 叮当生产出的APP覆盖会员用户数的存量
	 */
	public function getActivateMemberCount()
	{
		$start_time = intval($this->input['start_time']);
		$end_time = intval($this->input['end_time']);
		$info = $this->mMember->getActivateMemberCount($start_time,$end_time);
		if($info)
		{
			$this->addItem($info);
		}
		$this->output();
	}

    /**
     * 获取好友关系
     *
     */
    private function getFriendship($friend_id)
    {
        $member_id = intval($this->user['user_id']);
        $data = array();
        $remark = '';
        $is_attention = 0;
        $is_friend = 0;
        $is_fans = 0;
        if(!$friend_id)
        {
            $this->errorOutput(NO_FRIEND_ID);
        }

        if($member_id < $friend_id)
        {
            $data['member_id'] = $member_id;
            $data['friend_id'] = $friend_id;
        }
        elseif($member_id == $friend_id)
        {
            return false;
        }
        else
        {
            $data['member_id'] = $friend_id;
            $data['friend_id'] = $member_id;
        }

        $condition = ' AND member_id='.$data['member_id'].' AND friend_id='.$data['friend_id'];
        $info = $this->friend->show($condition);
        if($info[0])
        {
            $result =  $info[0];
            //解释关系
            if($result['member_id'] == $member_id)
            {
                if($result['relation_type'] == 1)
                {
                    $is_attention = 1;
                }
                elseif($result['relation_type'] == 3)
                {
                    $is_attention = 1;
                    $is_friend = 1;
                    $is_fans = 1;
                }
                else
                {
                    $is_attention = 0;
                    $is_fans = 1;
                }
                $remark = $result['member_remark'];
            }
            elseif($result['friend_id'] == $member_id)
            {
                if($result['relation_type'] == 2)
                {
                    $is_attention = 1;
                }
                elseif($result['relation_type'] == 3)
                {
                    $is_attention = 1;
                    $is_friend = 1;
                    $is_fans = 1;
                }
                else
                {
                    $is_attention = 0;
                    $is_fans = 1;
                }
                $remark = $result['friend_remark'];
            }
        }
        else
        {
            $is_attention = 0;
        }


        //查询是否是黑名单
        $black_info = $this->Members->check_friend_blacklist($member_id,$friend_id);

        return $res = array(
            'is_followed' => $is_attention,
            'is_fans'   => $is_fans,
            'is_friend' => $is_friend,
            'is_black'  => $black_info ? 1 : 0,
            'remark'    => $remark,
        );


    }

	
}

$out = new memberApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>