<?php
/***************************************************************************
* $Id: email_manually_send.php 33987 2014-02-13 03:47:35Z youzhenghuan $
***************************************************************************/
define('MOD_UNIQUEID', 'email_log');
require('global.php');
class emailManuallySendApi extends appCommonFrm
{
	private $mEmail;
	private $mEmailLog;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/email.class.php';
		$this->mEmail = new email();
		
		require_once CUR_CONF_PATH . 'lib/email_log.class.php';
		$this->mEmailLog = new emailLog();

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
	public function email_manually_send()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		$email_log = $this->mEmailLog->getEmailLogById($id);

		if (empty($email_log))
		{
			$this->errorOutput('该记录不存在或已被删除');
		}
		
		if ($email_log['ret_send_mail'] == 1)
		{
			$this->errorOutput('此记录已发送成功，无需手动发送');
		}
		
		if (!$email_log['toemail'] || !$email_log['subject'] || !$email_log['body'])
		{
			$this->errorOutput('发送信息不完整');
		}
		
		$smtp = array(
			'from' 		=> $email_log['emailsend'],
			'fromname' 	=> $email_log['fromname'],
		);
		
		$emailtype = 'sendmail';
		
		$to 	 = $email_log['toemail'];
		$subject = htmlspecialchars_decode($email_log['subject']);
		$body 	 = htmlspecialchars_decode($email_log['body']);
	
		$ret_manually_send = $this->mEmail->send_mail($smtp, $to, $subject, $body, $emailtype);
		//删除队列
		$sql = "DELETE FROM " . DB_PREFIX . "email_queue WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		//更新记录日志
		if ($ret_manually_send == 1)
		{
			$ret_editEmailSendLogManuallySend = $this->mEmailLog->editEmailSendLogManuallySend($id, $ret_manually_send, 1);
		}
		
		if (!$ret_editEmailSendLogManuallySend)
		{
			$this->errorOutput('error');
		}
		
		$this->addItem($id);
		$this->output();
	}
}

$out = new emailManuallySendApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'email_manually_send';
}
$out->$action();
?>