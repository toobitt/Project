<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:  $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . 'lib/class/member.class.php');

class getMembersApi extends BaseFrm
{
	var $member;
	
	function __construct()
	{
		parent::__construct();
		$this->member = new member();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_members()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NEED_LOGIN);
		}
		$sessionID = trim(urldecode($this->input['sid']));// ? trim(urldecode($this->input['sid'])) : md5(md5($this->user['user_id']) . 'message') 
		$pp = intval($this->input['pp']) ? intval($this->input['pp']) : 0;
		$count = intval($this->input['count']) ? intval($this->input['count']) : 50;
		$user_id = intval($this->input['user_id']);
		if(!$sessionID && !$user_id)
		{
			$this->errorOutput(PARAM_NO_FULL);  //参数不完整错误
		}
		else if($sessionID && !$user_id)
		{
			$sql = 'SELECT pm_u.uid FROM ' . DB_PREFIX . 'pm_user pm_u  LEFT JOIN ' . DB_PREFIX . 's_pm pp ON pp.sid = pm_u.sid WHERE pp.sessionId="' . $sessionID . '"';
		}
		else if(!$sessionID && $user_id > 0)
		{
			$total = $this->db->query_first('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'pm_user WHERE 1 AND uid = ' . $user_id);
			$this->addItem($total['total']);
			$sql = 'SELECT s.* FROM ' . DB_PREFIX . 'pm_session s LEFT JOIN ' . DB_PREFIX . 'pm_user u ON u.sid = s.sid AND u.uid =' . $user_id . ' ORDER BY s.stime DESC LIMIT ' . $pp . ' , ' . $count;
		}
		else
		{
			$this->errorOutput('参数有误！');
		}
		$query = $this->db->query($sql);
		$u_arr = $u_sid = array();
		while(false != ($rows = $this->db->fetch_array($query)))
		{
			$ids = explode(',', $rows['ids']); 
			//这里目前暂时默认为两人聊天，如果有群聊的需要，这里还需要修改一下
			$uuid = ($ids[1] != $ids[0]) ? (($this->user['user_id'] == $ids[0]) ? $ids[1] : $ids[0]) : 0;
			if ($uuid)
			{
				$u_arr[$uuid] = $rows;
				$u_sid[$uuid] = $rows['sid'];
			} 
		}
		$userids = array_keys($u_arr);
		$_idstr = implode(',', $userids);
		
		if($_idstr && $u_sid)
		{
			$member = $this->member->getMemberById($_idstr);			
			$u = $u_user = $member[0];
			$this->setXmlNode("Users","User");
			//获取每个人的最后一条信息
			$sql = 'SELECT a.content,a.stime,a.fromID,a.sid 
			FROM ' . DB_PREFIX . 'pm a WHERE 1 AND stime = (SELECT max( stime ) 
			FROM ' . DB_PREFIX . 'pm b WHERE a.fromID = b.fromID AND a.sid=b.sid) 
			AND a.fromID in (' . $_idstr . ') AND a.sid in (' . implode(',',$u_sid) . ') 
			ORDER BY a.sid DESC LIMIT ' . $pp*$count . ' , ' . $count;
		
			$q = $this->db->query($sql);
			while(false != ($rows = $this->db->fetch_array($q)))
			{
				foreach($u as $k => $info)
				{
				 	if($info['id'] == $rows['fromID'])
				 	{
						$info['last_message'] = $rows['content'];
						$info['last_stime'] = $rows['stime'];
						$info['last_sid'] = $rows['sid'];
						$this->addItem($info);
				 	}
				}
			}
			$this->output();
		}
		else
		{
			$this->errorOutput('暂无信息');
		}
		
	}
	
	//检测两个用户之间是否有对话历史
	public function check_them()
	{
		$user_id = intval($this->input['user_id']);
		$to_id = intval($this->input['to_id']);
		$ids = '"' . $user_id . ',' . $to_id . '","' . $to_id . ',' . $user_id . '"';
		$sql = 'select sid from ' . DB_PREFIX . 'pm_session where ids in('.$ids.')';
		$ret = $this->db->query_first($sql);
		if ($ret)
		{
			$sql = 'select sessionId from ' . DB_PREFIX . 's_pm where sid = ' . $ret['sid'];
			$ss = $this->db->query_first($sql);
			$ss = $ss['sessionId'];
		}
		else
		{
			$ss = 0;
		}
		$this->setXmlNode("sid","sid");
		$this->addItem($ss);
		$this->output();
	}
	
	//获取所有联系人
	public function get_all_members()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NEED_LOGIN);
		}
		$pp = intval($this->input['pp']) ? intval($this->input['pp']) : 0;
		$count = intval($this->input['count']) ? intval($this->input['count']) : 20;
		
		$sql = 'SELECT s.* FROM ' . DB_PREFIX . 'pm_user u JOIN ' . DB_PREFIX . 'pm_session s 
		ON u.sid = s.sid AND u.uid = ' . $this->user['user_id'] . ' ORDER BY s.stime DESC LIMIT ' . $pp . ',' . $count;
		$query = $this->db->query($sql);
		$u_arr = $u_sid = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$ids = explode(',', $rows['ids']); 
			//这里目前暂时默认为两人聊天，如果有群聊的需要，这里还需要修改一下
			$uuid = ($ids[1] != $ids[0]) ? (($this->user['user_id'] == $ids[0]) ? $ids[1] : $ids[0]) : 0;
			if ($uuid)
			{
				$u_arr[$uuid] = $rows;
				$u_sid[$uuid] = $rows['sid'];
			}
		}
		$userids = array_keys($u_arr);
		$_idstr = implode(',', $userids);
		$member = $this->member->getMemberById($_idstr);
		$u = $u_user = $member[0];
		$session_id = array();
		if(!empty($u_sid))
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "s_pm WHERE sid IN(" . implode(',',$u_sid) . ") ";
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$session_id[$row['sid']] = $row['sessionId'];
			}
		}
		
		
		foreach ($u_sid as $k=>$v)
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'pm WHERE sid =' . $v . ' ORDER BY stime ASC';
			$qid = $this->db->query($sql);
			$space = "";
			while ($row = $this->db->fetch_array($qid))
			{
				$u[$k]['pm'][$row['pid']] = $row;
				$u[$k]['pids'] .= $space . $row['pid'];
				$u[$k]['session_id'] = $session_id[$v];
				$u[$k]['first'] = $row;
				$space = ',';
			}
		}
		$this->setXmlNode("Users","User");
		$this->addItem($u);
		$this->output();
	}
	
	//获取相关联系人的数目
	public function get_member_count()
	{
		if (!$this->user['user_id'])
		{
			$this->errorOutput(NEED_LOGIN);
		}
		$sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'pm_user 
		WHERE uid = ' . $this->user['user_id'];
		$result = $this->db->query_first($sql);
		$this->addItem($result['total']);
		$this->output();
	}
}

$getMembersApi = new getMembersApi();
$action = $_INPUT['a'];
if (!method_exists($getMembersApi,$action))
{
	 $action = 'get_members';
}
$getMembersApi->$action();
?>