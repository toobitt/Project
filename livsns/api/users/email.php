<?php
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
set_time_limit(0);
class emailApi extends appCommonFrm
{
	var $from_user = "";
	var $from = "";
	var $to = "";
	var $subject = "";
	var $message = "";
	var $error = "";
	var $parts = array();
	var $mail_headers = "";
	var $multipart = "";
	var $boundry = "";
	var $char_set = 'UTF-8';
	var $smtp_fp = false;
	var $smtp_msg = "";
	var $smtpport = "";
	var $smtphost = "localhost";
	var $smtpuser = "";
	var $smtppassword = "";
	var $smtp_code = "";
	var $emailwrapbracket = 0;
	var $emailtype = 'mail';
	var $lang = array();
	function __construct()
	{
		parent::__construct();
		$this->userinfo['id'] = $this->input['id'];
		$this->userinfo['username'] = urldecode($this->input['username']);
		$this->userinfo['email'] = urldecode($this->input['email']);
		//获取发邮件配置
		require(ROOT_PATH . 'lib/class/uset.class.php');
		$mUset = new uset();
		$arr = array(
			'emailsend',
			'emailwrapbracket',
			'emailtype',
			'usessl' ,
			'smtphost',
			'smtpport' ,
			'smtpuser' ,
			'smtppassword' ,
			'email_footer',
			'email_title',
			'system_name',
			'verify_url',
			'email_content',
			'pwd_title',
			'pwd_content',
			'pwd_verify_url'
		);
		$rt = $mUset->get_desig_uset($arr);
		unset($rt['result']);
		$config = array();
		foreach($rt as $k => $v)
		{
			$config[$v['identi']] = $v['status'];
		}
		
		$this->settings = $config;
		$this->from = $this->settings['emailsend'];
		$this->emailwrapbracket = $this->settings['emailwrapbracket'];
		if ($this->settings['emailtype'] == 'smtp')
		{
			$this->emailtype = 'smtp';
			$this->smtpport = empty($this->settings['smtpport']) ? 25 : intval($this->settings['smtpport']);
			$this->smtphost = empty($this->settings['smtphost']) ? 'localhost' : $this->settings['smtphost'];
			if ($this->settings['usessl']) 
			{
				$this->smtphost = 'ssl://' . $this->smtphost;
			}
			$this->smtpuser = $this->settings['smtpuser'];
			$this->smtppassword = $this->settings['smtppassword'];
		}
		$this->boundry = "----=_NextPart_000_0022_01C1BD6C.D0C0F9F0";
		$this->settings['system_name'] = $this->clean_message($this->settings['system_name']);
		$this->from_user = $this->settings['system_name'];
	}
	
	function __destruct()
	{
		parent::__destruct(); 
	}

	private function build_headers()
	{
		$this->from_user = hg_convert_encoding($this->from_user, 'UTF-8', $this->char_set);
		$this->mail_headers .= "From: \"" . $this->from_user . "\" <" . $this->from . ">\n";
		if ($this->emailtype == 'smtp')
		{
			if ($this->to)
			{
				$this->mail_headers .= "To: " . $this->to . "\n";
			}
			$this->mail_headers .= "Subject:=?UTF-8?B?" . base64_encode($this->subject) . "?=\n";//"=?UTF-8?B?".base64_encode($subject)."?=";
		}
		$this->mail_headers .= "Return-Path: " . $this->from . "\n";
		$this->mail_headers .= "X-Priority: 3\n";
		$this->mail_headers .= "Content-Type: text/html ;charset=\"UTF-8\"\n";
		$this->mail_headers .= "Content-Transfer-Encoding: base64\n";
		if (count ($this->parts) > 0)
		{
			$this->mail_headers .= "MIME-Version: 1.0\n";
			$this->mail_headers .= "Content-Type: multipart/mixed;\n\tboundary=\"" . $this->boundry . "\"\n\nThis is a MIME encoded message.\n\n--" . $this->boundry;
			$this->mail_headers .= "\nContent-Type: text/html;\n\tcharset=\"" . $this->char_set . "\"\nContent-Transfer-Encoding: quoted-printable\n\n" . $this->message . "\n\n--" . $this->boundry;
			//$this->message = "";
		}
	}

	public function send_mail($email_data = array())
	{
		$this->to = preg_replace(array("/[ \t]+/", "/,,/", "#\#\[\]'\"\(\):;/\$!?\^&\*\{\}#"), array('', ',', ''), $email_data['to']);
		if ($email_data['from']) 
		{
			$this->from = preg_replace(array("/[ \t]+/", "/,,/", "#\#\[\]'\"\(\):;/\$!?\^&\*\{\}#"), array('', ',', ''), $email_data['from']);
		}
		if ($email_data['from_user']) 
		{
			$this->from_user = $email_data['from_user'];
		}
		$this->subject = $this->clean_message($email_data['subject']);
		if ($email_data['message']) 
		{
			$this->build_message($email_data['message']);
		}
		if (($this->from) AND ($this->subject))
		{
			//$this->subject .= " ( From " . $this->from . " )";
			//$this->subject = hg_convert_encoding($this->subject, 'UTF-8', $this->char_set);
			//$this->message = hg_convert_encoding($this->message, 'UTF-8', $this->char_set);
			$this->message = base64_encode($this->message);
			$this->build_headers();
			/*
			$this->message = str_replace(
				array("\r\n", "\r", "\n", '<br />'),
				array("\n", "\n", "\r\n", "\r\n"),
				$this->message
			);*/
			$this->mail_headers = str_replace(
				array("\r\n", "\r", "\n", '<br />'),
				array("\n", "\n", "\r\n", "\r\n"),
				$this->mail_headers
			);
			if ($this->emailtype != 'smtp')
			{
				return @mail($this->to, $this->subject, $this->message, $this->mail_headers);
			}
			else
			{
				return $this->smtp_send_mail();
			}
		}
		else
		{
			return false;
		}
	}
	public function send_verify_mail()
	{
		$email_type =0;
		if($this->input['type'])
		{
			$email_type =$this->input['type'];
		}
		$verify_data = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'verify_code WHERE type='.$email_type.' and user_id=' . intval($this->userinfo['id']));
		$verify_code = $this->rand_verify_code();
		if ($verify_data) 
		{		
			$update_verify = array(
				'verify_code' => $verify_code,
				'verify_send_time' => TIMENOW,
			);
			$sql = "update ".DB_PREFIX."verify_code set verify_code='".$update_verify['verify_code']."',verify_send_time=".
			$update_verify['verify_send_time']." where type=$email_type and user_id=".intval($this->userinfo['id']);
			$this->db->query($sql);
		}
		else 
		{
			$verify_data = array(
				'user_id' => $this->userinfo['id'],
				'user_name' => $this->userinfo['username'],
				'verify_code' => $verify_code,
				'verify_send_time' => TIMENOW,
			);
			$sql = "insert into ".DB_PREFIX."verify_code(user_id,user_name,verify_code,verify_send_time,type) values(".
			$verify_data['user_id'].",'".$verify_data['user_name']."','".$verify_data['verify_code']."',".$verify_data['verify_send_time'].",$email_type);";
			$this->db->query($sql);
		}
		if($email_type ==0)
		{
			$verify_link = $this->settings['verify_url'] . '?verify_code=' . $verify_code;
			$mail = array(
				'to' => $this->userinfo['email'],
				'subject' => sprintf($this->settings['email_title'],$this->userinfo['username']),
				'message' => sprintf($this->settings['email_content'],$this->userinfo['username'],$verify_link,$verify_link,$verify_link)	
			);
		}
		else if($email_type ==1)
		{
			$verify_link = $this->settings['pwd_verify_url'] . '?verify_code=' . $verify_code;
			$mail = array(
				'to' => $this->userinfo['email'],
				'subject' => sprintf($this->settings['pwd_title'],$this->userinfo['username']),
				'message' => sprintf($this->settings['pwd_content'],$this->userinfo['username'],$verify_link)	
			);
		}
		$this->setXmlNode('email','done');
		$result['done'] =0;
		if($this->send_mail($mail))
		{
			$result['done'] = 1;
		}
		$this->addItem($result);
		return $this->output();
	}
	
	public function send_findpass_mail($data)
	{
		$this->load_lang('email');
		$verify_code = $this->generate_findpass_code($data);
		$verify_link = '<a href="' . $this->settings['verify_url'] . '?m=register&a=changepass&verify_code=' . $verify_code . '&user_id='.$data['user_id'].'">' . $this->settings['verify_url'] . '?m=register&a=changepass&verify_code=' . $verify_code . '&user_id='.$data['user_id'].'/</a>';
		$mail = array(
			'to' => $data['email'],
			'subject' => sprintf($this->lang['findpass_email_title'], $this->settings['system_name']),
			'message' => sprintf($this->lang['findpass_email_body'], $data['user_name'],get_date(TIMENOW,2,1),$verify_link),			
		);
		return $this->send_mail($mail);
	}
	
	#2009-3-4 npc0der 新的注册模式
	public function send_email_verify($email_verify = '',$salt = '',$invite_code = '')
	{
		$this->load_lang('email');
		$verify_link = $this->settings['verify_url'].'index.php#m=register&email_verify='.$salt.'&ic='.$invite_code;
		$verify_link = '<a href="' . $verify_link . '">' . $verify_link . '/</a>';
		$user_name = explode('@',$email_verify);
		$mail = array(
			'to' => $email_verify,
			'subject' => sprintf($this->lang['email_verify_title'], $this->settings['system_name']),
			'message' => sprintf($this->lang['email_verify_body'], $user_name[0],$verify_link),			
		);
		return $this->send_mail($mail);
	}

	public function send()
	{
		if (!$this->input['sendto'])
		{
			$this->errorOutput('请提供收信人的邮箱地址');
		}
		if (!$this->input['message'])
		{
			$this->errorOutput('请填写邮件内容');
		}
		$this->load_lang('email');
		$sendto = str_replace(array(',','；', ':'), ';', $this->input['sendto']);
		$sendto = array_filter(explode(';', $sendto));
		$sucess = array();
		$message = urldecode($this->input['message']);
		$subject = urldecode($this->input['subject']);
		foreach ($sendto AS $to)
		{
			$mail = array(
				'to' => $to,
				'subject' => $subject ? $subject : '分享',
				'message' => $message,			
			);
			$s = $this->send_mail($mail);
			$sucess = array();
			if ($s == 1)
			{
				$sucess[$to] = $s;
			}
			$this->addItem($sucess);
		}
		$this->output();
	}
	
	private function generate_findpass_code($data)
	{
		return md5($data['user_id'].$data['salt']);
	}
	
	private function rand_verify_code()
	{
		$randstr = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM=';
		$length = strlen($randstr);
		do 
		{
			$verify_code = '';
			for ($i = 0; $i < 20; $i++)
			{
				$n = mt_rand(0, $length);
				$verify_code .= $randstr[$n];
			}
		}
		while ($this->db->query_first('SELECT verify_code FROM ' . DB_PREFIX . 'verify_code WHERE verify_code=\'' . $verify_code . "'"));
		return $verify_code;
	}

	private function build_message($message = '')
	{
		$message .= $this->fetch_email_footer();
		$message = str_replace("\t", '', $message);
		$this->message = $this->clean_message($message);
	}

	private function clean_message($message = "")
	{
		return $message;
		$pregfind = array
		("/^(\r|\n)+?(.*)$/",
			"#<b>(.+?)</b>#",
			"#<strong>(.+?)</strong>#",
			"#<i>(.+?)</i>#",
			"#<s>(.+?)</s>#",
			"#<u>(.+?)</u>#",
			"#<!--quote-->(.+?)<!--quote1-->#",
			"#<!--quote--(.+?)\+(.+?)-->(.+?)<!--quote1-->#",
			"#<!--quote--(.+?)\+(.+?)\+(.+?)-->(.+?)<!--quote1-->#",
			"#<!--quote2-->(.+?)<!--quote3-->#",
			"#<!--Flash (.+?)-->.+?<!--End Flash-->#e",
			"#<img[^>]+src=[\"'](\S+?)[\"'].+?" . ">screen??(.*)>#",
			"#<img[^>]+src=[\"'](\S+?)['\"].+?" . ">#",
			"#<a href=[\"'](http|news|https|ftp|ed2k|rtsp|mms)://(\S+?)['\"].+?" . ">(.+?)</a>#",
			"#<a href=[\"']mailto:(.+?)['\"]>(.+?)</a>#",
			);
		$pregreplace = array
		("\\2",
			"\\1",
			"\\1",
			"\\1",
			"--\\1--",
			"-\\1-",
			"\n\n------------ QUOTE ----------\n",
			"\n\n------------ QUOTE ----------\n",
			"\n\n------------ QUOTE ----------\n",
			"\n-----------------------------\n\n",
			"(FLASH MOVIE)",
			"(IMAGE: \\1)",
			"(IMAGE: \\1)",
			"\\1://\\2",
			"(EMAIL: \\2)",
			);
		$message = preg_replace($pregfind, $pregreplace, $message);
		$message = str_replace("#<br.*>#siU", "\r\n", $message);
		$message = preg_replace("#<.+?>#", '', $message);
		$pregfind = array
		("&quot;",
			"&#092;",
			"&#160;",
			"&#036;",
			"&#33;",
			"&#39;",
			"&lt;",
			"&gt;",
			"&#124;",
			"&amp;",
			"&#58;",
			"&#91;",
			"&#93;",
			"&#064;",
			"&#60;",
			"&#62;",
			);
		$pregreplace = array
		("\"",
			"\\",
			"\r\n",
			"\$",
			"!",
			"'",
			"<",
			">",
			'|',
			"&",
			":",
			"[",
			"]",
			'@',
			'<',
			'>',
			);
			
		return str_replace($pregfind, $pregreplace, $message);
	}

	private function smtp_get_line()
	{
		$this->smtp_msg = "";
		while ($line = fgets($this->smtp_fp, 515))
		{
			$this->smtp_msg .= $line;
			if (substr($line, 3, 1) == " ")
			{
				break;
			}
		}
				if ($this->input['debug'] == 1)
				{
					print_r($this->smtp_msg);
				}
	}

	private function smtp_send_cmd($cmd)
	{
				if ($this->input['debug'] == 1)
				{
					print_r($cmd);
				}
		$this->smtp_msg = "";
		$this->smtp_code = "";
		fputs($this->smtp_fp, $cmd . "\r\n");
		$this->smtp_get_line();
		$this->smtp_code = substr($this->smtp_msg, 0, 3);
		return $this->smtp_code == "" ? false : true;
	}

	private function smtp_crlf_encode($data)
	{
		$data .= "\n";
		return str_replace(array("\r", "\n", "\n.\r\n"), array("", "\r\n", "\n. \r\n"), $data);
	}

	private function smtp_send_mail()
	{
				if ($this->input['debug'] == 1)
				{
					print_r($this->smtphost);
				}
		$this->smtp_fp = fsockopen($this->smtphost, intval($this->smtpport), $errno, $errstr, 30);

				if ($this->input['debug'] == 1)
				{
					print_r($errstr);
				}
		if (! $this->smtp_fp)
		{
			return false;
		}
		$this->smtp_get_line();
		$this->smtp_code = substr($this->smtp_msg, 0, 3);
	
		if ($this->smtp_code == 220)
		{
			$data = $this->smtp_crlf_encode($this->mail_headers . "\n" . $this->message);
			$this->smtp_send_cmd("HELO " . $this->smtphost);
			if ($this->smtp_code != 250)
			{
				return false;
			}
			if ($this->smtpuser AND $this->smtppassword)
			{
				$this->smtp_send_cmd("AUTH LOGIN");
				if ($this->smtp_code == 334)
				{
					$this->smtp_send_cmd(base64_encode($this->smtpuser));
					if ($this->smtp_code != 334)
					{
						return false;
					}
					$this->smtp_send_cmd(base64_encode($this->smtppassword));
					if ($this->smtp_code != 235)
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
			if ($this->emailwrapbracket)
			{
				if (! preg_match("/^</", $this->from))
				{
					$this->from = "<" . $this->from . ">";
				}
			}
			$this->smtp_send_cmd("MAIL FROM:" . $this->from);
			if ($this->smtp_code != 250)
			{
				return false;
			}
			$to_arry = array($this->to);
			foreach($to_arry AS $to_email)
			{
				if ($this->emailwrapbracket)
				{
					$this->smtp_send_cmd("RCPT TO:<" . $to_email . ">");
				}
				else
				{
					$this->smtp_send_cmd("RCPT TO:" . $to_email);
				}
				if ($this->smtp_code != 250)
				{
					return false;
				}
			}
			$this->smtp_send_cmd("DATA");
			if ($this->smtp_code == 354)
			{
				fputs($this->smtp_fp, $data . "\r\n");
			}
			else
			{
				return false;
			}
			$this->smtp_send_cmd(".");
			if ($this->smtp_code != 250)
			{
				return false;
			}
			$this->smtp_send_cmd("quit");
			if ($this->smtp_code != 221)
			{
				return false;
			}
			@fclose($this->smtp_fp);
			return true;
		}
		else
		{
			return false;
		}
	}
	private function fetch_email_footer()
	{
		$email_footer = sprintf($this->lang['email_footer'], $this->settings['system_name']);
		return $email_footer;
	}
}

$out = new emailApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'send_verify_mail';
}
$out->$action();
?>