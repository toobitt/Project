<?php
define('MOD_UNIQUEID','verify_email');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/dingdone_user_mode.php');

class verify_email extends outerReadBase
{
    private $user_mode;
    public function __construct()
	{
		parent::__construct();
		$this->user_mode = new dingdone_user_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail(){}
	public function count(){}

	public function show()
	{
	    $token = $this->input['dd_token'];
		if(!$token)
		{
		    $this->errorOutput(NO_EMAIL_TOKEN);
		}
		
		//验证是否存在这个token
		$user_info = $this->user_mode->getUserByCond(" AND token = '" .$token. "' AND is_activate = 0 ");
		if($user_info)
		{
		    //验证成功，将激活状态置为1
		    $this->user_mode->update($user_info['id'],array(
		               'token'       => '',
		               'is_activate' => 1,
		    ));
		    
		    $this->addItem(array('return' => 1));
		    $this->output();
		}
		else 
		{
		    $this->errorOutput(VERIFY_FAIL);
		}
	}
	
	public function confirm_email()
	{
	    $token = $this->input['dd_token'];
		if(!$token)
		{
		    $this->errorOutput(NO_EMAIL_TOKEN);
		}
		
		//验证是否存在这个token
		$user_info = $this->user_mode->getUserByCond(" AND c_token = '" .$token. "' ");
		if($user_info)
		{
		    if(!$user_info['email_ctime'] || !$user_info['change_email'])
		    {
		        $this->errorOutput(YOU_HAVE_NOT_CHANGE_EMAIL);
		    }
		    
		    if(($user_info['email_ctime'] + $this->settings['sendcloud']['confirm_expire']) < TIMENOW)
		    {
		        $this->errorOutput(EMAIL_ACTIVATE_TIME_OVER);
		    }
		    
		    //判断有没有其他人已经用了该邮箱
		    $_isExistEmail = $this->user_mode->getUserByCond(" AND email = '" .$user_info['change_email']. "' AND id != '" .$user_info['id']. "' ");
		    if($_isExistEmail)
		    {
		        $this->errorOutput(THIS_EMAIL_HAS_EXISTS);
		    }

		    //验证成功，将激活状态置为1
		    $this->user_mode->update($user_info['id'],array(
		               'c_token'       => '',
		               'change_email'  => '',
		               'email_ctime'   => 0,
		               'email'         => $user_info['change_email'],
		    ));
		    $this->addItem(array('return' => 1));
		    $this->output();
		}
		else 
		{
		    $this->errorOutput(VERIFY_FAIL);
		}
	}
	
	protected function verifyToken()
	{
	    
	}
}

$out = new verify_email();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();