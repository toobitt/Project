<?php
define('MOD_UNIQUEID','auth_emailcode');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/auth_emailcode_mode.php');
require_once(CUR_CONF_PATH . 'lib/DDMail.class.php');
require_once(CUR_CONF_PATH . 'lib/SendCloud.class.php');
require_once(CUR_CONF_PATH . 'lib/dingdone_user_mode.php');

class auth_emailcode extends outerReadBase
{
	private $mode;
	private $code;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new auth_emailcode_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function send_emailcode()
	{
		$email = hg_clean_email(trim($this->input['email']));
		if (!$email)
		{
			$this->errorOutput(PARAM_WRONG);
		}
		//验证邮箱
		$this->verify_email($email);
		
		$condition = " AND email='".$email."' AND status = 0";
		$authCode = $this->mode->detail('', $condition);
		//邮箱验证码的有效时间
		$_expire_time = defined('EMAIL_AUTHCODE_EXPIRED') ? TIMENOW + EMAIL_AUTHCODE_EXPIRED : TIMENOW + 600;
		
		if (!$authCode)
		{
			$this->mkAuthCode();
		}
		else
		{
			$this->mode->audit($authCode['id']);   //标记为已使用 一个邮箱最多只能有一个未使用的验证码
			$this->code = $authCode['code'];
			//如果该验证码已经过期就重新生成验证码
			if($authCode['expire_time'] < TIMENOW)
			{
				$this->mkAuthCode();
			}
			else
			{
				//如果没有过期，过期时间还是原来的
				$_expire_time = $authCode['expire_time'];
			}
		}
		
		//发送验证码
		if($this->sendemail($email,$this->code))
		{
			//保存发送的验证码
			$ip = hg_getip();
			$data = array(
					'email'     => $email,
					'code'          => $this->code,
					'create_time'   => TIMENOW,
					'expire_time'  	=> $_expire_time,
					'status'        => 0,
					'ip'            => $ip,
			);
			$this->mode->create($data);
			//返回结果
			$this->addItem(array('error' => 0,'code' => $this->code));
			$this->output();
		}
		else
		{
			$this->errorOutput(SEND_CODE_FALSE);
		}
	}
	
	/**
	 * 发送邮箱验证码
	 */
	private function sendemail($email,$code)
	{
		if(!$code)
		{
			$this->errorOutput(PARAM_WRONG);
		}
		
		$user = new dingdone_user_mode();
		$condition = " AND email='".$email."'";
		$user_info = $user->getUserByCond($condition);
		//准备发送邮件
		$preg    = array('{username}', '{code}');
		//产生一个用于邮箱验证的识别码
		$replace = array($user_info['user_name'],$code);
		$htmlBody = file_get_contents(ROOT_DIR . 'email/find_password.html');
		$content = str_replace($preg, $replace,$htmlBody);
		
		//发送邮件
		$sendCloud = new DDSendCloud('find_password');
		$ret = $sendCloud->sendTo(array(
				'to'      => $email,
				'content' => $content,
		));
		
		return $ret;
	}
	
	/**
	 * 更新邮箱code使用状态
	 */
	public function verify_emailcode()
	{
		$email = hg_clean_email(trim($this->input['email']));
		$submit_emailcode = trim($this->input['submit_emailcode']);
		if (!$email)
		{
			$this->errorOutput(PARAM_WRONG);
		}
		$condition = " AND email='".$email."'";
		$condition .= " AND code = '" .$submit_emailcode. "' ";
		$authCode = $this->mode->detail('', $condition);
		if(!$authCode)
		{
			$this->errorOutput(VERIFICATION_CODE_WRONG);
		}
		if($authCode['expire_time'] < TIMENOW)
		{
			$this->errorOutput(CODE_OVERDUE);
		}
		if($authCode['code'] == $submit_emailcode)
		{
			$this->mode->audit($authCode['id']);
		}
		
		//返回结果
		$this->addItem(array('error' => 0,'status' => 0));
		$this->output();
	}
	
	/**
	 * 验证邮箱是否有限制
	 * @param unknown $email
	 */
	private function verify_email($email)
	{
		//检查同一邮箱的频率有没有在规定的时间内超过限制
		if($this->settings['sms_code_limit']['email']['time_limit'])
		{
			$stime = TIMENOW - $this->settings['sms_code_limit']['email']['time_limit'];
			$condition = " AND create_time > " . $stime . " AND create_time < " . TIMENOW . " AND email = '".$email."'";
			$total = $this->mode->count($condition);
			if ($total['total'] >= $this->settings['sms_code_limit']['email']['num_limit'])
			{
				$this->errorOutput(EMAIL_RATE_FAST);
			}
		}
		
		//同一ip限制
		$ip = hg_getip();
		if ($this->settings['sms_code_limit']['ip']['time_limit'])
		{
			$stime = TIMENOW - $this->settings['sms_code_limit']['ip']['time_limit'];
			$condition = " AND create_time > " . $stime . " AND create_time < " . TIMENOW . " AND ip = '".$ip."'";
			$total = $this->mode->count($condition);
			if ($total['total'] >= $this->settings['sms_code_limit']['ip']['num_limit'])
			{
				$this->errorOutput(BEYOND_THE_IP_LIMIT);
			}
		}
	}
	
	//产生验证码
	private function mkAuthCode()
	{
		$this->code = hg_generate_user_salt(6);
	}
	//用于微信关注 获取最新验证码
	public function get_latest_verifycode()
	{
		$email = $this->input['email'];
		$salt = '';
    	$chars = '0123456789';
	    $salt = '';
	    for ( $i = 0; $i < 6; $i++ ) 
	    {
	        $salt .= $chars[ mt_rand(0, strlen($chars) - 1) ];
	    }
    	$data = array(
				'email'     	=> $email,
				'code'          => $salt,
				'create_time'   => TIMENOW,
				'expire_time'  	=> defined('EMAIL_AUTHCODE_EXPIRED') ? TIMENOW + EMAIL_AUTHCODE_EXPIRED : TIMENOW + 600,
				'status'        => 0,
				'ip'            => hg_getip(),
    			'type'			=> 1,
		);
		$this->mode->create($data);	
		$this->addItem($data);
		$this->output();	
	}
	public function show(){}
	public function detail(){}
	public function count(){}
	protected function verifyToken(){}
}
$out = new auth_emailcode();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$out->$action();