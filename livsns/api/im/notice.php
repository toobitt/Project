<?php
require('global.php');
define(MOD_UNIQUEID,'notice');
include_once ROOT_PATH.'lib/class/members/class.php';
class noticeApi extends outerReadBase
{
	private $members;
	public function __construct()
	{
		parent::__construct();
		include CUR_CONF_PATH.'lib/notice.class.php';
		include_once ROOT_PATH.'lib/class/auth.class.php';
		$this->mode = new notice();
		$this->auth = new auth();
		$this->members = new members();
		
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 通知列表
	 * @param offset
	 * @param count
	 * @param access_token
	 * @param user_id 如果未传入access_token值，则需要传入user_id
	 * @param utype 1-会员（默认） 2-m2o用户
	 * @param statu 0-未读 1-已读
	 */
	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$conditon = $this->get_condition();
		$orderby = '  ORDER BY n.from_time DESC,n.id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$uid = $this->user['user_id']; //提交会员id		
		$owner_utype = $this->input['utype'] ? intval($this->input['utype']) : 1; //提交用户为会员还是m20管理员
		if(!$uid)
		{
			$this->errorOutput(NOT_LOGIN);
		}
		$sql = 'SELECT n.id,n.send_uname,n.title,n.content,n.type,n.send_time,n.from_time,n.to_time,nl.statu FROM '.DB_PREFIX.'notice n LEFT JOIN '.DB_PREFIX.'notice_log nl ON n.id = nl.notice_id and nl.user_id ='.$uid.'  WHERE 1 ';
		$sql .= $conditon;
		$sql .= ' AND ( ( n.type = 1 and n.owner_uid = '. $uid .' and n.owner_utype = '.$owner_utype .') ';//查询个人通知 
		if($owner_utype == 1)  //如果是会员用户
		{
			$gid = $this->members->get_member_infoByuid($uid);
			if($gid)
			{
				$sql .= ' OR  ( n.type = 2 and n.owner_uid = '.$gid.') ';//查询会员通知
			}
			$sql .=  ' OR ( n.type = 2 and n.owner_uid = 0) ';//查询全局会员通知
		}
		if($owner_utype == 2) //如果是M2O用户
		{
			$org_id = $this->user['org_id'];
			$role = $this->user['slave_group'];
			if($org_id)
			{
				$ret_org = $this->auth->get_one_org($org_id);
				$orgs = $ret_org[0]['parents'];
				if($orgs)
				{
					$sql .= ' OR ( n.type = 4 and n.owner_uid in ('.$orgs.')) ';//查询组织通知
				}
		    }
		    if($role)
		    {
		    	$sql .= ' OR ( n.type = 3 and n.owner_uid in ('.$role.')) ';//查询部门通知
		    }
		    $sql .=  ' OR ( ( n.type = 3 or n.type = 4 ) and n.owner_uid = 0) ';//查询全局组织或部门通知
		}
		$sql .=  ' OR ( n.type = 5)) ';//查询全局通知
		$sql .= $orderby.$limit;
		$q = $this->db->query($sql);		
		while ($r = $this->db->fetch_array($q))
		{
			$r['statu'] = $r['statu'] ? $r['statu'] : 0;			
			$r['statu_zh'] = $this->settings['statu'][$r['statu']];
			$r['send_time'] = $r['send_time'] ? date('Y-m-d H:i:s',$r['send_time']) : 0;
			$r['from_time'] = $r['from_time'] ? date('Y-m-d H:i:s',$r['from_time']) : 0;
			$r['to_time'] = $r['to_time'] ? date('Y-m-d H:i:s',$r['to_time']) : 0;
			$r['notice_state'] = get_states($r['from_time'], $r['to_time']);
			$notice[] = $r;
		}
		if(!$notice)
		{
			$this->errorOutput(NO_NOTICES);
		}
		foreach ($notice as $k=>$v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	/**
	 * 用户读取通知
	 * @param access_token
	 * @param user_id 如果未传入access_token值，则需要传入user_id
	 * @param utype 1-会员（默认） 2-m2o用户
	 */
	public function read()
	{
		$uid = $this->user['user_id']; //提交会员id
		$utype = $this->input['utype'] ? intval($this->input['utype']) : 1; //提交用户为会员还是m20管理员
		if(!$uid)
		{
			$this->errorOutput(NOT_LOGIN);
		}
		$notice_id = intval($this->input['id']);//读取通知的id
		if(!$notice_id)
		{
			$this->errorOutput(NOID);
		}
		$sql = 'SELECT id,send_uname,title,content,send_time,to_time FROM '.DB_PREFIX.'notice WHERE id ='.$notice_id ;
		$notice = $this->db->query_first($sql);
		if(!$notice)
		{
			$this->errorOutput(NO_NOTICE);
		}
		$notice['send_time'] = $notice['send_time'] ? date('Y-m-d H:i:s',$notice['send_time']) : 0;
		$notice['content'] = htmlspecialchars_decode(stripslashes($notice['content']));
		$notice['notice_state'] = get_states($notice['from_time'], $notice['to_time']);
		
		//查询通知日志中是否有此通知
		$sql = 'SELECT * FROM '.DB_PREFIX.'notice_log WHERE notice_id ='.$notice_id .' AND user_id = '.$uid .' AND user_type = ' .$utype;
		$log = $this->db->query_first($sql);
		if($log && $log['statu'] == 0) //如果该通知已经存在，且未阅读
		{
			$sql = 'UPDATE '.DB_PREFIX.'notice_log SET statu = 1 WHERE notice_id ='.$notice_id .' AND user_id = '.$uid .' AND user_type = ' .$utype;
			$this->db->query($sql);
		}
		elseif(!$log)
		{
			$sql = 'INSERT INTO ' . DB_PREFIX . 'notice_log' . '(notice_id,user_id,statu,user_type) VALUES ('.$notice_id.','.$uid.',1,'.$utype.')';
			$this->db->query($sql);
		}
		$this->addItem($notice);
		$this->output();
	}
	
	/**
	 * 用户将通知置为已读，支持多个
	 * @param access_token
	 * @param user_id 如果未传入access_token值，则需要传入user_id
	 * @param id 通知id，多个id逗号隔开
	 * @param utype 1-会员（默认） 2-m2o用户
	 */
	public function read_already()
	{
		$uid = $this->user['user_id']; //提交会员id
		if(!$uid)
		{
			$this->errorOutput(NOT_LOGIN);
		}
		$utype = $this->input['utype'] ? intval($this->input['utype']) : 1; //提交用户为会员还是m20管理员
		$notice_id = trim($this->input['id'],',');//通知的ids
		if(!$notice_id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->mode->set_statu($notice_id, 1,$uid,$utype);
		$this->addItem($data);
		$this->output();	
	}
	/**
	 * 用户删除通知，支持多个
	 * @param access_token
	 * @param user_id 如果未传入access_token值，则需要传入user_id
	 * @param id 通知id，多个id逗号隔开
	 * @param utype 1-会员（默认） 2-m2o用户
	 */
	public function delete()
	{
		$uid = $this->user['user_id']; //提交会员id
		if(!$uid)
		{
			$this->errorOutput(NOT_LOGIN);
		}
		$utype = $this->input['utype'] ? intval($this->input['utype']) : 1; //提交用户为会员还是m20管理员
		$notice_id = trim($this->input['id'],',');//通知的ids
		if(!$notice_id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->mode->set_statu($notice_id, 2,$uid,$utype);
		$this->addItem($data);
		$this->output();	
	}

	public function detail()
	{
		
	}
	public function count()
	{
		$conditon = $this->get_condition();
		$uid = $this->user['user_id']; //提交会员id
		$owner_utype = $this->input['utype'] ? intval($this->input['utype']) : 1; //提交用户为会员还是m20管理员
		if(!$uid)
		{
			$this->errorOutput(NOT_LOGIN);
		}
		$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'notice n LEFT JOIN '.DB_PREFIX.'notice_log nl ON n.id = nl.notice_id and nl.user_id ='.$uid.'  WHERE 1';
		//查询个人通知 组通知 全局通知
		$sql .= ' AND ( ( n.type = 1 and n.owner_uid = '. $uid .' and n.owner_utype = '.$owner_utype .')';
		if($owner_utype == 1)  //如果是会员用户
		{
			$gid = $this->members->get_member_infoByuid($uid);
			if($gid)
			{
				$sql .= ' OR  ( n.type = 2 and n.owner_uid = '.$gid.') ';
			}
			$sql .=  ' OR ( n.type = 2 and n.owner_uid = 0) ';//查询全局会员通知
		}
		if($owner_utype == 2) //如果是M2O用户
		{
			$org_id = $this->user['org_id'];
			$role = $this->user['slave_group'];
			if($org_id)
			{
				$orgs = $ret_org[0]['parents'];
				if($orgs)
				{
					$sql .= ' OR ( n.type = 4 and n.owner_uid in ('.$orgs.')) ';//查询组织通知
				}
		    }
		    if($role)
		    {
		    	$sql .= ' OR ( n.type = 3 and n.owner_uid in ('.$role.')) ';//查询角色通知
		    }
		    $sql .=  ' OR ( ( n.type = 3 or n.type = 4 ) and n.owner_uid = 0) ';//查询全局组织或部门通知
		}
		$sql .=  ' OR ( n.type = 5))';
		$sql .= $conditon;
		$return = $this->db->query_first($sql);		
		$this->addItem($return);
		$this->output();
	}
	
	public function get_condition()
	{
		$con = '';
	    $con .= ' AND n.status = 1 ' ; //获取已审核的通知
	    $con .= ' AND n.send_time <=' . TIMENOW ; //获取已发送的通知
	    $con .= ' AND ( nl.statu != 2 or nl.statu is null ) '; //获取未删除的通知
	    $con .= ' AND ( n.from_time <= ' . TIMENOW . ' ) ';//全局通知开始时间和结束时间限制
	    //$con .= ' AND ( n.from_time <= ' . TIMENOW . ' ) AND ( n.to_time >= '. TIMENOW .' or n.to_time = 0) ';//全局通知开始时间和结束时间限制
	    if($this->input['statu']!='' && intval($this->input['statu']) === 0)//获取未阅读的通知
		{
			$con .= ' AND (nl.statu = ' .intval($this->input['statu']) .' or nl.statu is null )' ;
		}
		if(intval($this->input['statu']) == 1)//获取已阅读的通知
		{
			$con .= ' AND nl.statu = ' .intval($this->input['statu']);
		}
		if(trim($this->input['title']))//搜索通知
		{
			$con .= ' AND n.title like "%' .trim($this->input['title']).'%"' ;
		}
		return $con;
	}
	
}

$out = new noticeApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
    $action = 'show';
}
$out->$action();

?>