<?php
class Cemail extends InitFrm
{
	private $mEmail;
	private $mEmailLog;
	private $mEmailQueue;

	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/email.class.php';
		$this->mEmail = new email();

		require_once CUR_CONF_PATH . 'lib/email_log.class.php';
		$this->mEmailLog = new emailLog();

		require_once CUR_CONF_PATH . 'lib/email_queue.class.php';
		$this->mEmailQueue = new emailQueue();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 发送邮件
	 *
	 * $emailsend 用于发送信件的邮箱地址
	 * $emailtype 发送类型 mail sendmail smtp (推荐sendmail)
	 * $smtpport SMTP 端口号
	 * $smtphost SMTP 主机
	 * $smtpuser SMTP 账号
	 * $smtppassword SMTP 密码
	 * $from 发送邮件
	 * $fromname 发件人名
	 * $to 收件人邮箱
	 * $subject 标题
	 * $body 内容
	 *
	 * Enter description here ...
	 */
	public function send_mail()
	{
		$email_queue = $this->mEmailQueue->getEmailQueueDetail('eq.id', 'ASC');
		if (empty($email_queue))
		{
			return 'Queue does not exist';
		}
		if($this->sendtempRecord($email_queue['id'], 'select'))
		{
			return 'Queue is running';
		}
		else
		{
			if(!$this->sendtempRecord($email_queue['id'], 'insert'))
			{
				return 'Queue failed to run';
			}
		}
		
		$smtp = array(
			'from' 		=> $email_queue['emailsend'],
			'fromname' 	=> $email_queue['fromname'],
		);

		$emailtype = $email_queue['emailtype'];

		if ($emailtype == 'smtp')
		{
			$smtp['smtpauth'] 		= $email_queue['smtpauth'];
			$smtp['usessl']   		= $email_queue['usessl'];
			$smtp['smtpport'] 		= $email_queue['smtpport'];
			$smtp['smtphost'] 		= $email_queue['smtphost'];
			$smtp['smtpuser'] 		= $email_queue['smtpuser'];
			$smtp['smtppassword'] 	= hg_encript_str($email_queue['smtppassword'], false);
			$smtp['from'] 			= $email_queue['fromemail'];
		}
		$to 	 = $email_queue['toemail'];
		$subject = htmlspecialchars_decode($email_queue['subject']);
		$body	 = htmlspecialchars_decode($email_queue['body']);
		//发送邮件
		$ret_send_mail = $this->mEmail->send_mail($smtp, $to, $subject, $body, $emailtype);
		if($ret_send_mail==1||$email_queue['ret_send_mail']>=5)
		{
			//删除队列
			$ret_deleteEmailQueue = $this->mEmailQueue->deleteEmailQueue($email_queue['id']);
		}
		else
		{
			$this->mEmailQueue->editEmailQueue($email_queue['id'],$email_queue['ret_send_mail']);
		}

		//记录日志
		$ret_editEmailSendLog = $this->mEmailLog->editEmailSendLog($email_queue['id'], $ret_send_mail);
		$this->sendtempRecord($email_queue['id'], 'delete');//队列运行结束
		//if(!$this->sendtempRecord(0, 'select','COUNT(*)'))//内存回收
		//{
			//$this->sendtempRecord(0, 'truncate');
		//}
		return $ret_send_mail;
	}

	public function sendtempRecord($queueid,$type,$field = 'queueid')
	{
		if($type == 'select')
		{
			$where = '';
			if($queueid)
			{
				$where = ' AND queueid = '.(int)$queueid;
			}
			$sql = 'SELECT '.$field.' FROM '.DB_PREFIX.'queue_temp WHERE 1'.$where;
			$ret = $this->db->query_first($sql);
			return $ret[$field];
		}
		elseif($queueid)
		{			
			if($type == 'insert')
			{
				$sql = 'INSERT INTO '.DB_PREFIX.'queue_temp (queueid) VALUES ('.(int)$queueid.')';
			}
			elseif($type == 'delete')
			{
				$sql = 'DELETE FROM '.DB_PREFIX.'queue_temp WHERE 1 AND queueid = '.(int)$queueid;	
			}
			if($this->db->query($sql))
			{
				return $this->db->affected_rows();
			}
		}
		elseif($type == 'truncate')
		{
			$sql = 'TRUNCATE TABLE '.DB_PREFIX.'queue_temp';	
			if ($this->db->query($sql))
			{
				return 1;
			}
		}
		return 0;
	}
}
?>