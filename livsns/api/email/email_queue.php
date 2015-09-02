<?php
/***************************************************************************
* $Id: email_queue.php 46068 2015-06-09 02:44:03Z tandx $
***************************************************************************/
define('MOD_UNIQUEID', 'email_queue');
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
require(CUR_CONF_PATH . 'core/Cclass.core.php');
class emailQueueApi extends appCommonFrm
{
	private $mEmailQueue;
	private $mEmail;
	private $mEmailLog;
	private $mEmailSettings;
	private $email_module;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/email_queue.class.php';
		$this->mEmailQueue = new emailQueue();
		
		require_once CUR_CONF_PATH . 'lib/email.class.php';
		$this->mEmail = new email();
		
		require_once CUR_CONF_PATH . 'lib/email_log.class.php';
		$this->mEmailLog = new emailLog();

		require_once CUR_CONF_PATH . 'lib/email_settings.class.php';
		$this->mEmailSettings = new emailSettings();
		
		$this->email_module = new email_content_template();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function addEmailQueue()
	{
		$appuniqueid = trim($this->input['appuniqueid']);

		if (!$appuniqueid)
		{
			$this->errorOutput(NO_APPUNIQUEID);
		}

		//获取邮件配置
		$email_settings = $this->mEmailSettings->getEmailSettings($appuniqueid);

		if (!$email_settings)
		{
			$this->errorOutput('配置信息不存在');
		}

		if (!$email_settings['status'])
		{
			$this->errorOutput('配置信息未审核');
		}

		$emailsend 		= $email_settings['emailsend'];
		$usessl   		= $email_settings['usessl'];
		$smtpauth  		= $email_settings['smtpauth'];
		$smtphost  		= $email_settings['smtphost'];
		$smtpuser  		= $email_settings['smtpuser'];
		$smtppassword 	= $email_settings['smtppassword'];
		$from 			= $email_settings['smtpuser'] ? $email_settings['smtpuser'] : $emailsend;
		$fromname 		= $email_settings['fromname'];
		$emailtype 		= $email_settings['emailtype'];

		$smtpport		= $email_settings['smtpport'] ? $email_settings['smtpport'] : 25;

		$to 		 = trim($this->input['to']);
		$subject	 = trim($this->input['subject']);
		$body	 	 = trim($this->input['body']);
		if(!$body||!$subject)
		{
			$emailContent = $this->email_module->getEmailContentSettings($appuniqueid);
			$replaceData = $this->input['tspace']?$this->input['tspace']:array();
			if($replaceData&&$emailContent['subject'])
			{
				$subject = replaceContent('tspace', $replaceData, html_entity_decode($emailContent['subject']));
			}
			$replaceData = $this->input['bspace']?$this->input['bspace']:array();
			if($replaceData&&$emailContent['body'])
			{
				$body = replaceContent('bspace', $replaceData, html_entity_decode($emailContent['body']));
			}
		}

		if (!$subject)
		{
			$this->errorOutput('邮件标题不能为空');
		}
		
		if (!$body)
		{
			$this->errorOutput('邮件内容不能为空');
		}
		
		$htmlbody = '';
		if ($email_settings['is_head_foot'] && $email_settings['header'])
		{
			$htmlbody .= $email_settings['header'];
		}
		
		if (get_magic_quotes_gpc())
		{
			$htmlbody .= stripslashes($body);
		} 
		else 
		{
			$htmlbody .= $body;
		}
		
		if ($email_settings['is_head_foot'] && $email_settings['footer'])
		{
			$htmlbody .= $email_settings['footer'];
		}

		if (!$emailsend)
		{
			$this->errorOutput('发件邮箱不能为空');
		}
		
		if (!$to)
		{
			$this->errorOutput('收件人邮箱不能为空');
		}
	
		if (!$this->mEmail->check_emailformat($to))
		{
			$this->errorOutput('发件人邮箱不合法');
		}
		
		if (!$emailtype)
		{
			$emailtype = 'sendmail';
		}
		
		$smtp = array(
			'from' 		=> $emailsend,
			'fromname' 	=> $fromname,
		);
		
		if ($emailtype == 'smtp')
		{
			if (!$smtphost)
			{
				$this->errorOutput('SMTP主机不能为空');
			}
			
			if (!$smtpuser)
			{
				$this->errorOutput('SMTP发件人邮箱不能为空');
			}
			
			if (!$smtppassword)
			{
				$this->errorOutput('SMTP发件人邮箱密码不能为空');
			}
		
			$smtp['smtpauth'] 		= $smtpauth;
			$smtp['smtpport'] 		= $smtpport;
			$smtp['smtphost'] 		= $smtphost;
			$smtp['smtpuser'] 		= $smtpuser;
			$smtp['smtppassword'] 	= $smtppassword;
			$smtp['from'] 			= $from;
		}
	
		$queue_info = array(
			'emailsend' 	=> $emailsend,
			'emailtype' 	=> $emailtype,
			'usessl' 		=> $usessl,
			'smtpauth' 		=> $smtpauth,
			'smtphost' 		=> $smtphost,
			'smtpport' 		=> $smtpport,
			'smtpuser' 		=> $smtpuser,
			'smtppassword' 	=> $smtppassword,
			'from' 			=> $smtp['from'],
			'fromname' 		=> $fromname,
		);
		
		//入队列
		$ret_addEmailQueue = $this->mEmailQueue->addEmailQueue($queue_info, $to, $subject, $htmlbody);

		//记录日志
		if ($ret_addEmailQueue['id'])
		{
			$ret_email_log = $this->mEmailLog->addEmailSendLog($ret_addEmailQueue['id'], $queue_info, $to, $subject, $htmlbody);
		}

		$this->addItem($ret_addEmailQueue['id']);
		$this->output();
	}
}

$out = new emailQueueApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'addEmailQueue';
}
$out->$action();
?>