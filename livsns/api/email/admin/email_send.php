<?php
/***************************************************************************
* $Id: email_send.php 33987 2014-02-13 03:47:35Z youzhenghuan $
***************************************************************************/
define('MOD_UNIQUEID', 'email_send');
require('global.php');
class emailSendApi extends appCommonFrm
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
			$this->errorOutput('error');
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
	
		//删除队列
		$ret_deleteEmailQueue = $this->mEmailQueue->deleteEmailQueue($email_queue['id']);

		//记录日志
		$ret_editEmailSendLog = $this->mEmailLog->editEmailSendLog($email_queue['id'], $ret_send_mail);
		
		$this->addItem($ret_editEmailSendLog);
		$this->output();
	}
}

$out = new emailSendApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'send_mail';
}
$out->$action();
?>