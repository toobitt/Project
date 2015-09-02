<?php
/***************************************************************************
 * $Id: member.php 46230 2015-06-17 02:12:16Z tandx $
 ***************************************************************************/
define('MOD_UNIQUEID','members');//模块标识
require('./global.php');
require_once(ROOT_PATH.'lib/class/im.class.php');
require_once CUR_CONF_PATH . 'lib/member_friend_mode.php';
class memberApi extends adminReadBase
{
	private $mMember;
	private $mMemberExtensionField;
	private $mMemberInfo;
	private $isSpread = 0;
	private $isIus = 0;
    private $im;
    private $friend;
	public function __construct()
	{
		//权限设置数据
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'manage'	=>'管理',
		);
		parent::__construct();

		require_once CUR_CONF_PATH . 'lib/member.class.php';
		$this->mMember = new member();

		require_once CUR_CONF_PATH . 'lib/member_info.class.php';
		$this->mMemberInfo = new memberInfo();

        $this->im = new im();

		$this->Members = new members();

        $this->friend = new member_friend_mode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	public function show()
	{
		try{
		$this->verify_content_prms(array('_action'=>'show'));
		$condition 	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$orderby 	= $this->get_orderby();
		$extend_sql='';
		if($this->input['medalid']&&$this->input['medalid'] != -1)
		{
			$extend_sql=' LEFT JOIN '.DB_PREFIX.'member_medal as mm ON mm.member_id=m.member_id';
		}
		if(isset($this->input['isBlackList'])&&$this->input['isBlackList'] == 1)
		{
			$extend_sql .= ' LEFT JOIN '.DB_PREFIX.'member_blacklist as mbl ON m.member_id=mbl.uid';
		}
		if($this->isSpread)
		{
			$extend_sql .= ' LEFT JOIN '.DB_PREFIX.'spread_record as sr ON m.member_id=sr.fuid';
		}
		if($this->isIus)
		{
			$extend_sql .= ' LEFT JOIN '.DB_PREFIX.'identifier_user_system as ius ON m.identifier=ius.identifier';
		}
		$info 	= $this->mMember->show($condition, $offset, $count,$orderby,$extend_sql);
		if($info&&is_array($info))
		{
			$member_id=array_keys($info);
			// 黑名单信息
			$blacklist = $this->Members->blacklist($member_id);
			// 黑名单信息
			$invite_user = $this->get_invite_user($member_id,false);
			$info = $this->mMember->getIdentifierName($info);
			$memberSpreadCode = memberSpread::getMemberIdToSpreadCode($member_id);
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
				unset($v['password'],$v['salt'],$v['myData']);
				$v['blacklist']=$blacklist[$v['member_id']];
				$v['inviteuser'] = $invite_user[$v['member_id']]?$invite_user[$v['member_id']]:array();
				$v['spreadcode'] = $memberSpreadCode[$v['member_id']]?$memberSpreadCode[$v['member_id']]:'';
				$this->addItem($v);
			}
		}

			$this->output();
		}
		catch(Exception $e) {
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}

	public function detail()
	{
		$this->verify_content_prms(array('_action'=>'show'));
		$member_id = trim($this->input['id']);
		if(!$member_id)
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		$member = $this->mMember->detail($member_id);
		if(empty($member))
		{
			$this->errorOutput(NO_MEMBER);
		}
		$member_medal=$this->Members->get_member_medal(array($member_id));
		$member['medal_id']=@array_keys($member_medal[$member_id]);
		$member['medal_id']=$member['medal_id']?$member['medal_id']:array();
		if(empty($member['graname']))
		{
			$grade_info=$this->Members->updategrade($member_id);
			if($grade_info&&is_array($grade_info))
			{
				foreach ($grade_info as $key => $val)
				{
					$member[$key] = $val;
				}
			}
		}
		if(empty($member['groupname']))//判断是否无效组
		{
			$group_info=$this->Members->updategroup($member_id,0);
			if($group_info&&is_array($group_info))
			{
				foreach ($group_info as $key => $val)
				{
					$member[$key] = $val;
				}
			}
		}

		if (!empty($member) && $member_id)
		{
			unset($member['password'],$member['salt']);
			// 积分信息
			$credits=$this->Members->membercredit($member_id,$is_on=1);
			// 黑名单信息
			$blacklist=$this->Members->blacklist($member_id);
			//绑定信息
			$bind = $this->mMember->get_bind_info(" AND mb.member_id = " . $member_id);
			//获取邀请人
			$invite_user = $this->get_invite_user($member_id);
			//扩展信息
			$member_info = $this->mMemberInfo->show(" AND member_id = " . $member_id);
		}
		//扩展字段表信息处理
        if(intval($this->input['app_id']))
        {
            $extension = $this->mMemberInfo->extendDataProcessByApp($member_info,1,$this->input['app_id']);
        }
        else
        {
            $extension = $this->mMemberInfo->extendDataProcess($member_info);
        }

		$return = $member;
		if($bind&&is_array($bind))
		{
			$is_flag = true;
			foreach ($bind as $bin)
			{
				if($is_flag)
				{
					$return['nick_name'] = $bin['nick_name'];
					$return['nick_name']&&$is_flag = false;
				}
			}
		}
		if(!$return['nick_name']){
			$return['nick_name'] = $return['member_name'];
		}
		$check_bind = new check_Bind();
		$inuc = $check_bind -> check_uc($member['member_id'], $member['type']);
		$tmp_return = $this->mMember->getIdentifierName(array($return));

        //获取会员加入的群组
        if($this->input['need_group_count'])
        {
            $groupCount = $this->im->getGroupCountBymemberId($member_id);
        }

        //获取好友粉丝数量
        if($this->input['need_friend_count'])
        {
            //好友的
            $condition = " AND (member_id=".$member_id." AND relation_type=3) or (friend_id=".$member_id." AND relation_type=3)";
            $friend_num = $this->friend->count($condition);
            //获取粉丝数
            $condition = " AND (member_id=".$member_id." AND relation_type<>1) or (friend_id=".$member_id." AND relation_type<>2)";
            $fans_num = $this->friend->count($condition);
        }

		$return = $tmp_return[0];
		$return['inuc'] = $inuc;
		$return['isNameUpdate'] = $this->mMember->isMemberNameUpdate($member['member_id'],1);
		$return['inviteuser']=$invite_user?$invite_user:array();
		$return['credit']	=$credits[$member['member_id']];
		$return['blacklist']=$blacklist[$member['member_id']];
		$return['bind'] 	 = $bind;
		$return['extension'] = $extension;
        $return['groupCount'] = $groupCount['total'] ? $groupCount['total'] : 0;
        $return['friendCount'] = $friend_num['total'] ? $friend_num['total'] : 0;
        $return['fansCount'] = $fans_num['total'] ? $fans_num['total'] : 0;
		$this->addItem($return);
		$this->output();
	}

	public function count()
	{
		try{
			$this->verify_content_prms(array('_action'=>'show'));
			$condition = $this->get_condition();
			$this->extend_sql($extend_sql);
			$info = $this->mMember->count($condition,$extend_sql);
			echo json_encode($info);
		}
		catch(Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
	}
	
	private function extend_sql(&$extend_sql)
	{
		$extend_sql = '';
		if ($this->input['medalid']&&$this->input['medalid'] != -1)
		{
			$extend_sql=' LEFT JOIN '.DB_PREFIX.'member_medal as mm ON mm.member_id=m.member_id';
		}
		if(isset($this->input['isBlackList'])&&$this->input['isBlackList'] == 1)
		{
			$extend_sql .= ' LEFT JOIN '.DB_PREFIX.'member_blacklist as mbl ON m.member_id=mbl.uid';
		}
		if($this->isSpread)
		{
			$extend_sql .= ' LEFT JOIN '.DB_PREFIX.'spread_record as sr ON m.member_id=sr.fuid';
		}
		if($this->isIus)
		{
			$extend_sql .= ' LEFT JOIN '.DB_PREFIX.'identifier_user_system as ius ON m.identifier=ius.identifier';
		}
		//return $extend_sql;
	}
	/**
	 *
	 * 获取会员详细信息 ...
	 */
	public function get_member_info()
	{
		$this->verify_content_prms(array('_action'=>'show'));
		$member_id = intval($this->input['member_id']);
		if (!$member_id)
		{
			$this->errorOutput(NO_MEMBER_ID);
		}

		$condition 	 = $this->get_condition();;

		//会员信息
		$leftjoin=' LEFT JOIN '.DB_PREFIX.'member_bind as mb ON mb.member_id = m.member_id';
		$leftjoin.=' LEFT JOIN '.DB_PREFIX.'group as g ON m.gid=g.id LEFT JOIN '.DB_PREFIX.'grade gra ON gra.id=m.gradeid';
		$member = $this->mMember->get_member_info($condition,'m.*,mb.nick_name,g.name as groupname,gra.name as graname',$leftjoin);
		$member = $member[0];
		if (empty($member))
		{
			$this->errorOutput(NO_MEMBER);
		}
		if(empty($member['graname']))
		{
			$grade_info=$this->Members->updategrade($member_id);
			if($grade_info&&is_array($grade_info))
			{
				foreach ($grade_info as $key => $val)
				{
					$member[$key] = $val;
				}
			}
		}
		// 黑名单信息
		$blacklist=$this->Members->blacklist($member_id);
		//绑定信息
		$bind = $this->mMember->get_bind_info(" AND mb.member_id = " . $member_id);

		//扩展信息
		$member_info = $this->mMemberInfo->show(" AND member_id = " . $member_id);

		//扩展字段表信息处理
		$extension = $this->mMemberInfo->extendDataProcess($member_info);
		unset($member['password'], $member['salt']);

		$return = $member;
		if($bind&&is_array($bind))
		{
			foreach ($bind as $k=>$v)
			{
				if($v['type']=='shouji'&&empty($return['mobile']))//如果为手机绑定类型则显示mobile为绑定表手机号
				{
					$return['mobile']=$v['platform_id'];
				}
			}
		}
		$return['blacklist']=$blacklist[$member_id];
		$return['bind'] 	 = $bind;
		$return['extension'] = $extension;

		$this->addItem($return);
		$this->output();
	}

	private function get_condition()
	{
		$condition = '';
		//搜索标签
		if ($this->input['searchtag_id']) {
			$searchtag = $this->searchtag_detail(intval($this->input['searchtag_id']));
			foreach ((array)$searchtag['tag_val'] as $k => $v) {
				if ( in_array( $k, array('_id') ) )
				{
					//防止左边栏分类搜索无效
					continue;
				}
				$this->input[$k] = $v;
			}
		}
		//搜索标签
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
		if(isset($this->input['identifier']) && ($this->input['identifier'] >0 || $this->input['identifier'] === "0"))
		{
			$identifierUserSystem = new identifierUserSystem();
			$identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
			$condition .= " AND m.identifier = " . $identifier;
		}
		if($this->input['invite_user'])
		{
			if($inviteUserId = $this->Members->get_member_id($this->input['invite_user'],false,false,'',$identifier))
			{		
				$fuidArr = $this->get_Invitees_user($inviteUserId,'mi.fuid','fuid','3',false);				
				if($fuidArr)
				{
					$this->input['member_id'] = implode(',', $fuidArr);
				}
				else
				{
					$this->errorOutput(MEMBER_NO_INVITE_MEMBERS);
				}
			}
			else 
			{
				$this->errorOutput(NOT_INVITE_MEMBER);
			}
		}
		if($spreadCode = trim($this->input['spreadCode']))
		{
			$condition .= " AND sr.spreadcode = '" . $spreadCode . "'";
			$this->isSpread = 1;
		}
		if ($this->input['member_id'])
		{
			$condition .= " AND m.member_id IN (" . trim($this->input['member_id']) . ")";
		}
		if(isset($this->input['_id'])&&intval($this->input['_id'])||isset($this->input['gid'])&&intval($this->input['gid']))
		{
			$gid = $this->input['_id']?intval($this->input['_id']):intval($this->input['gid']);
			$condition .= " AND m.gid = " . $gid;
				
		}
		if(isset($this->input['gradeid'])&&$this->input['gradeid'] != -1)
		{
			$condition .= " AND m.gradeid = " . $this->input['gradeid'];
		}
		if ($this->input['medalid']&&$this->input['medalid'] != -1)
		{
			$condition .= " AND mm.medalid = " . intval($this->input['medalid'])." AND (mm.expiration=0 OR mm.expiration>".TIMENOW.")";
		}
		if (isset($this->input['member_type'])&&$this->input['member_type'] != -1)//查询会员类型
		{
			$member_type = $this->input['member_type'];
			$condition .= ' AND mb.type = \''.$member_type.'\'';
		}
		if(isset($this->input['isBlackList'])&&$this->input['isBlackList'] == 1)
		{
			$condition .= ' AND (mbl.deadline = -1 OR mbl.deadline != 0 AND mbl.deadline>'.TIMENOW.')';
		}
		if (isset($this->input['status']) && $this->input['status'] != -1)
		{
			$condition .= " AND m.status = " . intval($this->input['status']);
		}
		if (isset($this->input['isVerify']) && $this->input['isVerify'] != -1)
		{
			$condition .= " AND m.isVerify = " . intval($this->input['isVerify']);
		}
		if (isset($this->input['member_appid']) && $this->input['member_appid'] != -1)
		{
			$condition .= " AND m.appid = " . intval($this->input['member_appid']);
		}
		if($identifierName = trim($this->input['identifierName']))
		{
			$condition .= ' AND ius.iusname like \'%'.$identifierName.'%\'';
			$this->isIus = 1;
		}
		if ($this->input['device_token'])
		{
			$condition .= " AND m.reg_device_token like '%" . trim($this->input['device_token'])."%'";
		}
		if($this->input['ip'])
		{
			$condition .= " AND m.ip like '%" . trim($this->input['ip'])."%'";
		}
		if(isset($this->input['is_avatar']) && $this->input['is_avatar'] != -1)
		{
			if($this->input['is_avatar'])
			$condition .= " AND ( m.avatar != '' AND m.avatar !='a:0:{}')";
			else {
				$condition .= " AND (m.avatar = '' OR m.avatar ='a:0:{}')";
			}
		}
		if(isset($this->input['is_mobile']) && $this->input['is_mobile'] != -1)
		{
			if($this->input['is_mobile'])
			$condition .= " AND m.mobile != ''";
			else {
				$condition .= " AND m.mobile = ''";
			}
		}
		elseif(!empty($this->input['mobile']))
		{
			$condition .= " AND m.mobile Like '%".$this->input['mobile']."%'";
		}
		
		if(!empty($this->input['email']))
		{
			$condition .= " AND m.email Like '%".$this->input['email']."%'";
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND m.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND m.create_time <= ".$end_time;
		}
		if(isset($this->input['date_search']) && !empty($this->input['date_search']))
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND m.create_time > '".$yesterday."' AND m.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND m.create_time > '".$today."' AND m.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND m.create_time > '".$last_threeday."' AND m.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND m.create_time > '".$last_sevenday."' AND m.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}

	private function get_orderby()
	{
		$orderby ='';
		if(isset($this->input['is_regtime_sort']) && $this->input['is_regtime_sort'] != -1)
		{
			if($this->input['is_regtime_sort'])
			$orderby .= " ORDER BY create_time ASC";
			else {
				$orderby .= " ORDER BY create_time DESC";
			}
		}
		if(isset($this->input['is_credits_sort']) && $this->input['is_credits_sort'] != -1)
		{
			if($this->input['is_credits_sort'])
				if(empty($orderby)){
					$orderby .= " ORDER BY credits ASC";
				}
				else {
					$orderby .= ",credits ASC";
				}
			else {
				if(empty($orderby)){
					$orderby .= " ORDER BY credits DESC";
				}
				else {
					$orderby .= ",credits DESC";
				}
			}
		}
		return $orderby;
	}
	/**
	 *
	 * 获取会员来自应用 ...
	 */
	public function get_member_app()
	{
		$query=$this->db->query('SELECT m.appid,m.appname FROM '.DB_PREFIX.'member as m GROUP BY m.appname');
		while ($row = $this->db->fetch_array($query))
		{
			if($row['appname']==''&&$row['appid']==0)
			{
				$row['appname']='其它';
			}
			$this->addItem($row);
		}
		$this->output();
	}

	/**
	 *
	 * 获取会员类型 ...
	 */
	public function get_member_type()
	{
		$query=$this->db->query('SELECT m.type,m.type_name FROM '.DB_PREFIX.'member as m GROUP BY m.type');
		while ($row = $this->db->fetch_array($query))
		{
			$this->addItem($row);
		}
		$this->output();
	}
	/**
	 *
	 * 获取勋章数据
	 */
	public function get_medal_info()
	{
		include CUR_CONF_PATH . 'lib/medal_manage.class.php';
		$medalmanage = new medalmanage();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 200;
		$info 	= $medalmanage->show($condition,$offset,$count);
		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				if($v['expiration']!=0)
				{
					$v['expiration']=$v['expiration'].'天';
				}
				else
				{
					$v['expiration']='永久有效';
				}
				$v['type_name']=$this->settings['medal_type'][$v['type']];
				$this->addItem($v);
			}
		}

		$this->output();
	}

	public function get_member_extension()
	{
		$mMemberExtensionField = new memberExtensionField();
		$condition='';
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$info = $mMemberExtensionField->show($condition, $offset, $count);
		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	/**
	 *
	 * 通过被邀请人获取邀请人 ...
	 * @param unknown_type $fuid
	 */
	private function get_invite_user($fuid,$isRow = TRUE)
	{
		$_invite = new invite();
		return $_invite->select_fuid_to_uid($fuid,$isRow);
	}
	/**
	 *
	 *  通过邀请人获取被邀请人 ... ...
	 * @param unknown_type $uid
	 */
	private function get_Invitees_user($uid,$field = 'm.member_name,mi.member_id',$id = 'fuid',$process = '2',$isExtend = true,$extend_sql = '')
	{
		$_invite = new invite();
		return $_invite->select_uid_to_fuid($uid,$field,$id,$process,$isExtend,$extend_sql);
	}

	/**
	 *
	 *  获取已经邀请人数 ... ...
	 * @param unknown_type $uid
	 */
	private function get_Invitees_count($uid)
	{
		$_invite = new invite();
		return $_invite->select_uid_to_count($uid);
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