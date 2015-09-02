<?php 
/***************************************************************************

* $Id: email_log.class.php 33987 2014-02-13 03:47:35Z youzhenghuan $

***************************************************************************/
class emailLog extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 发送邮件记录日志
	 * Enter description here ...
	 * @param unknown_type $info
	 * @param unknown_type $to
	 * @param unknown_type $subject
	 * @param unknown_type $body
	 * @param unknown_type $ret_send_mail
	 */
	public function addEmailSendLog($queue_id, $info ,$to, $subject, $body)
	{
		$htmlbody = '';
		if (!empty($body))
		{
			if (get_magic_quotes_gpc())
			{
				$htmlbody = stripslashes($body);
			} 
			else 
			{
				$htmlbody = $body;
			}
		}
		
		$data = array(
			'queue_id' => $queue_id,
			'emailsend' => $info['emailsend'],
			'emailtype' => $info['emailtype'],
			'usessl' => $info['usessl'],
			'smtpauth' => $info['smtpauth'],
			'smtphost' => $info['smtphost'],
			'smtpport' => $info['smtpport'],
			'smtpuser' => $info['smtpuser'],
			'smtppassword' => $info['smtppassword'],
			'fromemail' => $info['from'],
			'fromname' => $info['fromname'],
			'toemail' => $to,
			'subject' => $subject,
			'body' => $htmlbody,
			'appid' => intval($this->user['appid']),
			'appname' => urldecode($this->user['display_name']),
			'user_id' => intval($this->user['user_id']),
			'user_name' => urldecode($this->user['user_name']),
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
		);
		
		$sql = "INSERT INTO " . DB_PREFIX . "email_send_log SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		if ($data['id'])
		{
			return $data;
		}

		return false;
	}
	
	function editEmailSendLog($queue_id, $ret_send_mail)
	{
		$sql = "UPDATE " . DB_PREFIX . "email_send_log SET ";
		$sql.= " ret_send_mail = '" . $ret_send_mail . "',";
		$sql.= " update_time = " . TIMENOW ;
		$sql.= " WHERE queue_id = " . $queue_id;
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	function getEmailLogById($id,$field='id')
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'email_send_log WHERE '. $field . '='  . $id;
		$email_send_log = $this->db->query_first($sql);
		return $email_send_log;
	}
	
	function editEmailSendLogManuallySend($id, $ret_manually_send, $manuall_send)
	{
		$sql = "UPDATE " . DB_PREFIX . "email_send_log SET ";
		$sql.= " ret_manually_send = '" . $ret_manually_send . "',";
		$sql.= " manually_send = " . $manuall_send . ", ";
		$sql.= " update_time = " . TIMENOW ;
		$sql.= " WHERE id = " . $id;
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	function delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "email_send_log WHERE id IN (" . $id . ")";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	function show($condition, $offset, $count)
	{
		$offset = $offset ? $offset : 0;
		$count = $count ? $count : 25;
		$orderby = " ORDER BY id DESC ";
		$limit = " LIMIT " . $offset . " , " . $count;
		
		$sql = "SELECT * FROM " . DB_PREFIX . "email_send_log ";
		$sql .= " WHERE 1 " . $condition . $orderby . $limit;
		
		$q = $this->db->query($sql);
		
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			$info[] = $row;
		}
		return $info;
	}
	
	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "email_send_log WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		return $info;
	}

	public function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND emailsend like \'%'.urldecode($this->input['k']).'%\'';
		}
		
		if(isset($this->input['start_time']) && !empty($this->input['start_time']))
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if(isset($this->input['end_time']) && !empty($this->input['end_time']))
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
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
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		return $condition;
	}
}

?>