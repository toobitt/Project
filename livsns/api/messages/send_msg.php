<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:  $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class sendMsgApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function send_msg()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NEED_LOGIN); //用户未登录
		}
		$this->setXmlNode("SessionID","SessionID");
		$sessionID = trim(urldecode($this->input['sid'])) ? trim(urldecode($this->input['sid'])) : md5(md5($this->user['user_id']) . 'message') ;
		$content = trim(urldecode($this->input['content']));
		if(!$sessionID && !$content)
		{
			$this->errorOutput(PARAM_NO_FULL); //未传递$sessionID或消息内容为空,参数不完整错误
		}
	 	$time = TIMENOW;
		$pid = intval($this->input['pid']);//上条消息id
		$users = trim(urldecode($this->input['uid']));
	 	$users = explode(',', $users);
	 	array_filter($users);

	 	if(empty($users))
	 	{
	 		$this->errorOutput(USERNOTEXIST);
	 	}
	
	 	$type = (count($users) == 1) ? 0 : 1; //对话人数大于1为群聊，否则为对话
	 	$users[] = $this->user['user_id'];	
	 	natsort($users); //将参与人员数组按照自然数顺序排序
		$ids = implode(',', $users);
		
		//参与对话的人员相同，那么就看做是一个session对话,这里可能有bug，如果是群聊的话，那么如果有人退出了群聊，可能会出现问题
		$sql = "SELECT max( pm.pid ) AS pid, s.sessionId, pm_s.sid
					FROM " . DB_PREFIX . "pm_session pm_s
					LEFT JOIN " . DB_PREFIX . "s_pm s ON s.sid = pm_s.sid
					LEFT JOIN " . DB_PREFIX . "pm pm ON pm.sid = pm_s.sid
					WHERE 1 
					AND pm_s.ids = '" . $ids . "'";
		$f = $this->db->query_first($sql);
		if(!empty($f))
		{
			$q_sid = $f['sid'];
			$q_sessionId = $f['sessionId'];
			$pid = ($pid > 0) ? $pid : $f['pid'];
		}
		else
		{
			$q_sid = $q_sessionId = 0;
			$pid = ($pid > 0) ? $pid : 0;
		}
		
	 	//如果没有传递$pid,按照发布新对话处理，否则就按照追加对话消息处理(即，回复对话)
		if (!$pid)
		{
			if (!$q_sid)
			{
				$sql = 'INSERT INTO ' . DB_PREFIX . 'pm_session (type,uid,uname,ids,stime) VALUES("' . $type . '","' . $this->user['user_id'] . '","' . $this->user['user_name'] . '","' . implode(',', $users) . '","' . $time . '");';
			 	$this->db->query($sql);
			 	$sid = $this->db->insert_id();
			 	$sql = 'INSERT IGNORE INTO ' . DB_PREFIX . 's_pm VALUES("' . $sessionID .'","' . $sid . '")';
		 		$this->db->query($sql);
			}
		 	$sql = 'INSERT INTO ' . DB_PREFIX . 'pm (sid,fromID,fromwho,content,stime,flag) VALUES("' . ($q_sid > 0 ? $q_sid : $sid) . '","' . $this->user['user_id'] . '","' . $this->user['user_name'] . '","' . addslashes($content) . '","' . $time . '","0")';
		 	
		 	$this->db->query($sql);
		 	$pid = $this->db->insert_id();
		 	
		 	$sql = 'INSERT IGNORE INTO ' . DB_PREFIX . 'pm_user (sid,uid,pid,rtime,new) VALUES ';
		 	$sp = '';
		 	foreach($users as $key => $uid)
		 	{
		 		$values .= $sp . '(' . (($q_sid >0) ? $q_sid : $sid) . ',' . $uid . ',' . $pid . ',';
		 		if($uid == $this->user['user_id'])
		 		{
		 			$values .= '"' . $time . '",0)';
		 		}
		 		else
		 		{
		 			$values .= '"",1)'; 
		 		}
		 		$sp = ',';
		 		
		 	}
		 	$sql .= $values;
		 	$this->db->query($sql);
		 	$return = array(
		 		'sid' => (($q_sessionId > 0 ) ? $q_sessionId : $sessionID),
		 		'pid' => $pid,
		 		'content' => $content,
		 		'stime' => date("m-d H:i",$time),
		 		'fromwho' => $this->user['user_name'],
		 	);
		 	$this->addItem($return); 
		 	$this->output();
		}
		else 
		{
			$query = $this->db->query_first('SELECT pm_u.rtime,pm_u.pid,pm_u.sid FROM ' . DB_PREFIX . 'pm_user pm_u LEFT JOIN ' . DB_PREFIX . 's_pm pp ON pp.sid = pm_u.sid WHERE pp.sid="' . $q_sid . '" AND pm_u.uid=' . $this->user['user_id']);
 			if(!$query['rtime'] && !$query['pid'])
			{
				 
				$this->errorOutput(NO_AUTHORITY);//用户未参与对话，不可发消息
			}
			else
			{
				$sql = 'INSERT INTO ' . DB_PREFIX . 'pm (sid,fromID,fromwho,toID,content,stime) VALUES("' . $query['sid'] . '","' . $this->user['user_id'] . '","' . $this->user['user_name'] . '","' . $pid . '","' . addslashes($content) . '","' . $time . '")';
				$this->db->query($sql);
				$n_pid = $this->db->insert_id();
				 
				$sql = 'UPDATE ' . DB_PREFIX . 'pm_user SET pid = ' . $n_pid . ', new = CASE WHEN uid = ' . $this->user['user_id'] . ' THEN 0 ELSE 1 END WHERE sid = ' . $query['sid'];
				$this->db->query($sql); 
				
				//回复对话消息返回内容信息
				$this->setXmlNode("MSGContent","Content");
				$return = array(
					'sid' => $sessionID,
					'pid' => $n_pid,
					'content' => $content,
					'stime' => date("m-d H:i",$time),
					'fromwho' => $this->user['user_name'],
					'to' => $pid,
				);
				$this->addItem($return);
				$this->output();
			}
		}	
	}
}

$sendMsgApi = new sendMsgApi();
$sendMsgApi->send_msg();
?>