<?php
/***************************************************************************
 * $Id: send_sms.php 33732 2014-01-17 05:56:13Z develop_tong $
 ***************************************************************************/
define('MOD_UNIQUEID','reset_password');//模块标识
require('./global.php');
require CUR_CONF_PATH . 'lib/email_token_mode.php';
class sendSmsApi extends appCommonFrm
{
	private $Members;
	private $memberverifycode;
	private $email = '';
	private $appuniqueid = '';
	private $subject = '';
	private $body = '';
	private $tspace = array();
	private $bspace = array();
	private $type = 1;
	private $action = 1;
	private $verfiycode = '';
    private $token;
    private $expire_time;
	public function __construct()
	{
		parent::__construct();
		$this->Members = new members();
		$this->memberverifycode = new member_verifycode();
        $this->email_token = new email_token_mode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 *
	 * 获取注册邮箱验证码接口...
	 */
	public function getRegVerifyCode()
	{
		$this->getEmail();
		$this->makeVerifycode();
		$this->appuniqueid = 'member_register';
		$this->tspace = array(
		$this->input['member_name']?$this->input['member_name']:'用户'
		);
		$this->bspace = array($this->verfiycode);
		$sendInfo = $this->generate_verifycode_email();
		if($sendInfo){
			$output = array('account'=>$sendInfo['account'],'status'=>$sendInfo['status']);
			$this->addItem($output);
		}
		$this->output();
	}

	/**
	 *
	 * 获取绑定邮箱验证码接口...
	 */
	public function getBindVerifyCode()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(USER_NO_LOGIN);
		}
		$this->getEmail();
		$this->makeVerifycode();
		$this->appuniqueid = 'member_bind';
		$this->tspace = array(
		$this->user['user_name']?$this->user['user_name']:'*'
		);
		$this->bspace = array($this->verfiycode);
		$sendInfo = $this->generate_verifycode_email();
		if($sendInfo){
			$output = array('account'=>$sendInfo['account'],'status'=>$sendInfo['status']);
			$this->addItem($output);
		}
		$this->output();
	}

	/**
	 *
	 * 获取解除邮箱绑定验证码接口...
	 */
	public function getUnBindVerifyCode()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(USER_NO_LOGIN);
		}
		$checkBind = new check_Bind();
		if(! ($platform_id = $checkBind->check_Bind($this->user['user_id'],'email')))
		{
			$this->errorOutput(EMAIL_NO_BIND_ACCOUNT);
		}
		$this->email = $platform_id;
		$this->makeVerifycode();
		$this->appuniqueid = 'member_unbind';
		$this->tspace = array(
		$this->user['user_name']?$this->user['user_name']:'火星人'
		);
		$this->bspace = array($this->verfiycode);
		$sendInfo = $this->generate_verifycode_email();
		if($sendInfo){
			$output = array('account'=>$sendInfo['account'],'status'=>$sendInfo['status']);
			$this->addItem($output);
		}
		$this->output();
	}

	/**
	 *
	 * 获取注册或者绑定接口需要的EMAIL ...
	 */
	private function getEmail()
	{
		$this->email = $this->input['email'];
		$reg_mail = $this->Members->check_reg_mail($this->email);
		if($reg_mail == -5)
		{
			$this->errorOutput(EMAIL_NO_REGISTER);
		}
		elseif ($reg_mail == -6)
		{
			$this->errorOutput(EMAIL_HAS_BINDED);
		}
	}

	/**
	 *
	 * 获取找回密码邮箱验证码接口...
	 */
	public function getRePasswordVerifyCode()
	{
		$memberId = 0;
		if($memberName = trimall($this->input['member_name']))
		{
            $this->checkMemberName($memberName);
			$this->makeVerifycode();
			$this->appuniqueid = 'member_password';
			$this->tspace = array(
			hg_verify_mobile($memberName)?hg_hide_mobile($memberName):$memberName
			);
			$this->bspace = array($this->verfiycode);
			$sendInfo = $this->generate_verifycode_email();
			if($sendInfo){
				$output = array('account'=>$sendInfo['account'],'status'=>$sendInfo['status']);
				$this->addItem($output);
			}
			$this->output();
		}
		else $this->errorOutput(NO_MEMBER_NAME);
	}

    /**
     * 检查绑定
     */
    private function checkMemberName($memberName)
    {
        if(hg_verify_mobile($memberName))
        {
            $this->errorOutput('请填写正确的用户名');
        }
        if(hg_check_email_format($memberName))
        {
            $memberId = $this->Members->get_member_id($memberName,false,false,'email');
            if($memberId)
            {
                $isEmail  = 1;
                $platform_id = $memberName;
                $this->email = $memberName;
            }
        }
        if(!$memberId)
        {
            $memberId = $this->Members->get_member_id($memberName,false,false,'m2o');
        }
        if(!$memberId)
        {
            $memberId = $this->Members->get_member_id($memberName,false,false,'uc');
        }
        if(!$memberId)
        {
            $this->errorOutput(NO_MEMBER);
        }
        if(!$isEmail)
        {
            $this->email = trimall($this->input['email']);
            if($this->email&&hg_check_email_format($this->email))
            {
                $checkBind = new check_Bind();
                $platform_id = $checkBind->check_Bind($memberId,'email');
                if($platform_id && $platform_id != $this->email)
                {
                    $this->errorOutput(EMAIL_BIND_ACCOUNT_ERROR);
                }elseif(empty($platform_id)) {
                    $this->errorOutput(EMAIL_NO_BIND_ACCOUNT);
                }
            }
            else if ($this->email) {
                $this->errorOutput(EMAIL_FORMAT_ERROR);
            }
            else {
                $this->errorOutput(NO_EMAIL);
            }
        }

        return $memberId;
    }

	/**
	 * 生成邮箱验证码不发送接口（旧接口，为兼容而生）
	 * $mail 邮箱
	 *
	 * 返回
	 * success
	 */
	public function generate_verifycode($_email)
	{
		$email = trim($this->input['email']);
		if (empty($email))
		{
			$this->errorOutput(NO_EMAIL);
		}
		//验证邮箱!
		$reg_mail=$this->Members->check_reg_mail($email);
		if ($reg_mail==-4)
		{
			$this->errorOutput(EMAIL_FORMAT_ERROR);
		}
		elseif($reg_mail>0)
		{
			$this->errorOutput(EMAIL_RIGHT);
		}
		$_email .= hg_set_verifycode($length = 6, $chars = '0123456789abcdefghijjklmnopqrstuvwxyzABCDEFGHIIJKLMNOPQRSTUVWXYZ');//获取随机数
		$hash_verifycode=hash('md5',$_email);
		$data=array(
			'account'=>$email,
			'type'=>1,
			'action'=>1,
			'verifycode'=>$hash_verifycode,
			'create_time'=>TIMENOW,
		);
		$this->memberverifycode->verifycode_create($data);
		$this->addItem($hash_verifycode);
		$this->output();

	}

	/**
	 * 生成邮箱验证码并发送接口
	 * $mail 邮箱
	 *
	 * 返回
	 * success
	 */
	private function generate_verifycode_email()
	{
		if (!$this->settings['App_email'])
		{
			$this->errorOutput('邮箱验证码发送失败!请联系管理员');
		}
		if (empty($this->email))
		{
			$this->errorOutput(NO_EMAIL);
		}
		else if (!hg_check_email_format($this->email))
		{
			$this->errorOutput(EMAIL_FORMAT_ERROR);
		}
		if(!$this->appuniqueid)
		{
			$this->errorOutput('发送配置标识不能为空');
		}
		if(!$this->verfiycode)
		{
			$this->errorOutput('对不起验证码生成失败');
		}
		include (ROOT_PATH.'lib/class/email.class.php');
		$Oemail = new email();
		$params = array(
			'to'=>$this->email,
			'appuniqueid'=> $this->appuniqueid,
		);
		if($this->subject)
		{
			$params	['subject']	= $this->subject;
		}
		elseif ($this->tspace)
		{
			$params	['tspace']		= $this->tspace;
		}
			
		if($this->body)
		{
			$params	['body']	= $this->body;
		}
		elseif($this->bspace)
		{
			$params	['bspace']	= $this->bspace;
		}
		$sendInfo = $Oemail->addEmailQueue($params);
		$data = array(
			'account'=> $this->email,
			'type'=> $this->type,
			'action'=> $this->action,
			'status'=> $sendInfo[0]>0?1:0,
			'verifycode'=> $this->verfiycode,
			'create_time'=> TIMENOW,
		);
		$this->memberverifycode->verifycode_create($data);
		return $data;
	}

	private function makeVerifycode($length = 6)
	{
		$this->verfiycode = hg_set_verifycode($length = 6, $chars = '0123456789abcdefghijjklmnopqrstuvwxyzABCDEFGHIIJKLMNOPQRSTUVWXYZ');//获取随机数
	}

    private function makeToken($length = 16)
    {
        $token = hg_set_verifycode($length = 16, $chars = '0123456789abcdefghijjklmnopqrstuvwxyzABCDEFGHIIJKLMNOPQRSTUVWXYZ');//获取随机数
        return $token;
    }


    /**
     * 发送链接到邮箱
     */
    public function SendlinkEmail()
    {
        $template_name = $this->input['template_name'];
        $this->getEmail();
        $link_url = $this->input['link_url'];
        $identifierUserSystem = new identifierUserSystem();
        $identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
        if($email = trimall($this->input['email']))
        {
            $nick_name = '用户';
            $appicon = '';
            $appname = '找回密码';

            if(hg_check_email_format($email))
            {
                $condition = " AND platform_id='".$email."' AND mb.type='email' AND mb.identifier=".$identifier."";
                $leftjoin = " LEFT JOIN " . DB_PREFIX . "member_bind as mb ON m.member_id=mb.member_id ";
                $memberInfo = $this->Members->get_member_info($condition, $field = ' mb.* ',$leftjoin ,'',false);
                $nick_name = $memberInfo['nick_name'];
            }

            $this->type = 'resetpassword';

            $this->verify_email();

            $this->_expire_time = $this->settings['email_token_limit']['time_limit'] ? TIMENOW + $this->settings['email_token_limit']['time_limit'] : TIMENOW + 1000;

            $condition = " AND email='".$email."' AND status=0";
            $email_token_info = $this->email_token->show($condition,' ORDER BY id DESC ','limit 1');
            if(!$email_token_info)
            {
                $this->token = $this->makeToken(16);
            }
            else
            {
                $this->token = $email_token_info[0]['token'];
                //如果该验证码已经过期就重新生成验证码
                if($email_token_info[0]['expire_time'] < TIMENOW)
                {
                    $this->token = $this->makeToken(16);
                    $this->email_token->update($email,array('status' => 1));
                }
                else
                {
                    //如果没有过期，过期时间还是原来的
                    $this->_expire_time = $email_token_info[0]['expire_time'];
                }
            }

            $url = $link_url.'&email='.$email.'&token='.$this->token;

            //准备发送邮件
            $sub = $this->input['sub'];
            $preg = array();$replace = array();
            if(!empty($sub))
            {
                foreach($sub as $k=>$v)
                {
                    array_push($preg,"{$k}");
                    array_push($replace,"{$v}");
                }
            }
            if($replace)
            {
                foreach($replace as $k=>$v)
                {
                    if($v == '{membername}')
                    {
                        $replace[$k] = $nick_name;
                    }
                    if($v == '{link_url}')
                    {
                        $replace[$k] = $url;
                    }
                }
            }
            $htmlBody = file_get_contents(ROOT_DIR . 'email_template/'.$template_name.'.html');
            $content = str_replace($preg, $replace,$htmlBody);

            include (ROOT_PATH.'lib/class/email.class.php');
            $emailapi = new email();
            $param = array(
                'email' => $email,
                'content' => $content,
                'template_name' => $template_name,
                'from' => $this->input['from'], //不传使用默认域名
                'fromname' => $this->input['fromname']  //不传使用默认
            );
            $result = $emailapi->sendCloudMail($param);

            $data = array(
                'email'=> $this->email,
                'type'=> $this->type,
                'status'=> 0,
                'token'=> $this->token,
                'create_time'=> TIMENOW,
                'expire_time'=> TIMENOW + $this->settings['email_token_limit']['time_limit'],
            );
            $res = $this->email_token->create($data);

            $this->addItem($data);
            $this->output();
        }
    }


    /**
     *
     * 获取找回密码邮箱验证码接口...
     */
    public function getResetPasswordlinkMail()
    {
        $memberId = 0;
        $link_url = $this->input['link_url'];
        $identifierUserSystem = new identifierUserSystem();
        $identifier = $identifierUserSystem->setIdentifier((int)$this->input['identifier'])->checkIdentifier();//多用户系统
        if($memberName = trimall($this->input['member_name']))
        {
            $this->email = $memberName;
            $nick_name = '用户';
            if(hg_check_email_format($memberName))
            {
                $condition = " AND platform_id='".$memberName."' AND mb.type='email' AND mb.identifier=".$identifier."";
                $leftjoin = " LEFT JOIN " . DB_PREFIX . "member_bind as mb ON m.member_id=mb.member_id ";
                $memberInfo = $this->Members->get_member_info($condition, $field = ' mb.* ',$leftjoin ,'',false);
                $nick_name = $memberInfo['nick_name'];
            }

            $this->type = 'resetpassword';
            $this->appuniqueid = 'resetpassword_link';
            $this->tspace = array(
                hg_verify_mobile($memberName)?hg_hide_mobile($memberName):$memberName
            );

            $this->verify_email();

            $this->_expire_time = $this->settings['email_token_limit']['time_limit'] ? TIMENOW + $this->settings['email_token_limit']['time_limit'] : TIMENOW + 1000;

            $condition = " AND email='".$memberName."' AND status=0";
            $email_token_info = $this->email_token->show($condition,' ORDER BY id DESC ','limit 1');
            if(!$email_token_info)
            {
                $this->token = $this->makeToken(16);
            }
            else
            {
                $this->token = $email_token_info[0]['token'];
                //如果该验证码已经过期就重新生成验证码
                if($email_token_info[0]['expire_time'] < TIMENOW)
                {
                    $this->token = $this->makeToken(16);
                }
                else
                {
                    //如果没有过期，过期时间还是原来的
                    $this->_expire_time = $email_token_info[0]['expire_time'];
                }
            }

            $url = $link_url.'&email='.$memberName.'&token='.$this->token;
            $this->bspace = array($memberName,$nick_name,$url);
            $sendInfo = $this->generate_link_email();
            if($sendInfo){
                $output = array('email'=>$sendInfo['email']);
                $this->addItem($output);
            }
            $this->output();
        }
        else $this->errorOutput(NO_MEMBER_NAME);
    }

    private function verify_email()
    {
        //查询是否有在有效期的token
        if($this->settings['email_token_limit']['time_limit'])
        {
            $stime = TIMENOW - $this->settings['email_token_limit']['time_limit'];
            $condition = " AND create_time > " . $stime . " AND create_time < " . TIMENOW . " AND email = '".$this->email."'";
            $total = $this->email_token->count($condition);
            if ($total['total'] >= $this->settings['email_token_limit']['num_limit'])
            {
                $this->errorOutput(BEYOND_EMAIL_LIMIT_NUM);
            }
        }
    }

    /**
     * 生成邮箱验证码并发送接口
     * $mail 邮箱
     *
     * 返回
     * success
     */
    private function generate_link_email()
    {
        if (!$this->settings['App_email'])
        {
            $this->errorOutput('邮箱验证码发送失败!请联系管理员');
        }
        if (empty($this->email))
        {
            $this->errorOutput(NO_EMAIL);
        }
        else if (!hg_check_email_format($this->email))
        {
            $this->errorOutput(EMAIL_FORMAT_ERROR);
        }
        if(!$this->appuniqueid)
        {
            $this->errorOutput('发送配置标识不能为空');
        }

        include (ROOT_PATH.'lib/class/email.class.php');
        $Oemail = new email();
        $params = array(
            'to'=>$this->email,
            'appuniqueid'=> $this->appuniqueid,
        );
        if($this->subject)
        {
            $params	['subject']	= $this->subject;
        }
        elseif ($this->tspace)
        {
            $params	['tspace']		= $this->tspace;
        }

        if($this->body)
        {
            $params	['body']	= $this->body;
        }
        elseif($this->bspace)
        {
            $params	['bspace']	= $this->bspace;
        }
        $sendInfo = $Oemail->addEmailQueue($params);

        $data = array(
            'email'=> $this->email,
            'type'=> $this->type,
            'status'=> 0,
            'token'=> $this->token,
            'create_time'=> TIMENOW,
            'expire_time'=> $this->_expire_time,
        );

        $res = $this->email_token->create($data);

        return $data;
    }



	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

}

$out = new sendSmsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'generate_verifycode';
}
$out->$action();
?>