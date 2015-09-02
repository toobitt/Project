<?php 
/***************************************************************************

* $Id: email.class.php 11491 2012-09-18 07:07:02Z lijiaying $

***************************************************************************/
class email extends BaseFrm
{
	private $phpmailer;
	public function __construct()
	{
		parent::__construct();
		require_once 'phpmailer.class.php';
		$this->phpmailer = new PHPMailer(true);
		$this->phpmailer->CharSet = 'utf-8';
		$this->phpmailer->WordWrap   = 80; // set word wrap
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function send_mail($smtp, $to, $subject, $body, $emailtype, $ishtml = true)
	{
		try 
		{
			$body = preg_replace('/\\\\/','', $body); //Strip backslashes

			switch ($emailtype)
			{
				case 'sendmail' :
					$this->phpmailer->IsSendmail();
					break;
				case 'smtp' :
			//		$this->phpmailer->SMTPDebug  = 1;	// 启用SMTP调试功能
					$this->phpmailer->IsSMTP();
					$this->phpmailer->SMTPAuth 	 = $smtp['smtpauth'];
					$this->phpmailer->SMTPSecure = $smtp['usessl'];
					$this->phpmailer->Port       = $smtp['smtpport'];               // SMTP server port
					$this->phpmailer->Host       = $smtp['smtphost']; 				// SMTP server smtp.163.com
					$this->phpmailer->Username   = $smtp['smtpuser'];     			// SMTP server username 
					$this->phpmailer->Password   = $smtp['smtppassword'];           // SMTP server password
					break;
				default:
					break;
			}
		
			$this->phpmailer->AddReplyTo($smtp['from'], $smtp['fromname']);
		
			$this->phpmailer->From       = $smtp['from']; //发件人
			$this->phpmailer->FromName   = $smtp['fromname'];//发件人名
		
			$this->phpmailer->AddAddress($to);	//收件人
		
			$this->phpmailer->Subject  = $subject;//标题
		
	//		$this->phpmailer->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		
			$this->phpmailer->MsgHTML($body);
		
			$this->phpmailer->IsHTML($ishtml); // send as HTML
		
			$ret = $this->phpmailer->Send();
			
			if ($ret == 1)
			{
				return $ret;
			}
			return false;
		} 
		catch (phpmailerException $e) 
		{
			return $e->errorMessage();
		}
	}
	
	function check_emailformat($email)
	{
		return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
	}
}

?>