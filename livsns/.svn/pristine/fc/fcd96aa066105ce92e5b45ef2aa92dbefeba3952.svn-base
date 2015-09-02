<?php
/***************************************************************************
 * $Id: register.php 36891 2014-05-12 08:06:30Z youzhenghuan $
 ***************************************************************************/
define('MOD_UNIQUEID','member_invite');//模块标识
require('./global.php');
class invite_userApi extends appCommonFrm
{
	private $mMember;
	private $mSmsServer;
	public function __construct()
	{
		parent::__construct();

		require_once CUR_CONF_PATH . 'lib/sms_server.class.php';
		$this->mSmsServer = new smsServer();

		require_once CUR_CONF_PATH . 'lib/sms.log.class.php';
		$this->mSmslog = new smsLog();

		$this->invite = new invite();
		$this->Members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 产生邀请信息.目前仅支持手机邀请.
	 */
	public function generate_invite()
	{
		if($this->user['user_id'])
		{
			$member_id=intval($this->user['user_id']);
		}
		$re['status']=0;
		$re['msg']='您未登陆,请登陆后再次邀请!';
		if($member_id)
		{
			$select=array();
			$sendto='';
			if($mobile = intval($this->input['mobile']))
			{
				$checkBind = new check_Bind();
				$check_info = $checkBind->bind_to_memberid($mobile,'shouji',true);
				if(empty($check_info))
				{
					$sendto = $code = $mobile;
					if($code)
					{
						$condition['code']=$code;
					}
					$condition['type'] = $type = 1;
					$select=$this->invite->select($condition);
					$send_status=$this->send_sms();
					if($send_status)
					{
						$re['status']=$status=3;//发送成功
						$re['msg']='短信通知成功';
					}
					else {
						$re['status']=$status=4;//发送失败
						$re['msg']='短信通知失败,您可以主动通知好友或者重新尝试发送邀请!';
					}
				}
				else {
					$re['msg']='您邀请的好友已经是会员,无需邀请!';
				}
			}
			elseif($email = trim($this->input['email']))//暂时不支持email.
			{
				$check_info=$this->Members->get_member_id($email,true,false);
				if(empty($check_info))
				{
					$sendto = $code = $email;
					if($code)
					{
						$condition['code']=$code;
					}
					$condition['type'] = $type = 2;
					$select=$this->invite->select($condition);
				}
				else
				{
					$re['msg']='您邀请的好友已经是会员,无需邀请!';
				}
			}
			else
			{
				$code = strtolower(random(6));
				$condition['type'] = $type = 0;
				$status=1;//不发送
				$re['status']=$status=1;//未发送邀请
				$re['msg']='您的邀请码为:'.$code.',请复制邀请码并通过QQ,E-mail,论坛告知您的好友!';
			}
			if(empty($check_info))
			{
				$setarr = array(
				'member_id' => $member_id,
				'code' => $code,
				'type' => $type,
				'sendto'=>$sendto,
				'inviteip' => hg_getip(),
				'dateline' => TIMENOW,
				'status' => $status,//不发送为1,已发送则3
				'endtime' => ($this->settings['member_invite']['invite_endtime']?(TIMENOW+$this->settings['member_invite']['invite_endtime']*3600):0)
				);
				if(empty($select))
				{
					$ret=$this->invite->insert($setarr);
				}
				else
				{
					$ret=$this->invite->update($setarr,$condition);
				}
				$re['code']=$code;
			}
		}
		if($re&is_array($re))
		{
			foreach ($re as $k => $v)
			{
				$this->addItem_withkey($k, $v);
			}
		}
		$this->output();

	}
	/**
	 * 生成发送手机验证码
	 * $mobile 手机号
	 *
	 * 返回
	 * success
	 */
	private function send_sms()
	{
		if ($this->settings['closesms'])
		{
			$this->errorOutput($this->settings['error_text']['closesms']);
		}
		$mobile = trim($this->input['mobile']);
		if (!$mobile)
		{
			$this->errorOutput(MOBILE_NOT_NUMBER);
		}
		if($this->mSmslog->check_max_limits($mobile))
		{
			$this->errorOutput($this->settings['error_text']['sms_max_limits']);
		}
		//简单验证手机号格式
		if (!hg_verify_mobile($mobile))
		{
			$this->errorOutput(MOBILE_NUMBER_FORMAT_ERROR);
		}

		$condition  = " AND id = 2 AND status = 1 ORDER BY over DESC LIMIT 1";
		$sms_server = $this->mSmsServer->get_sms_server_info($condition);
		$sms_server = $sms_server[0];

		if (empty($sms_server))
		{
			$this->errorOutput(SMS_NOT);
		}

		$content = $sms_server['content'];

		if (strstr($content, '{&#036;c}'))
		{
			$content = str_replace('{&#036;c}', $this->user['user_name'], $content);
		}
		else if (strstr($content, '&#39;{&#036;c}&#39;'))
		{
			$content = str_replace('&#39;{&#036;c}&#39;', $this->user['user_name'], $content);
		}
		if($sms_server['charset'] != 'UTF-8')
		{
			$content = iconv('UTF-8', $sms_server['charset'], $content);
		}
		//替换相关变量
		$url = $sms_server['send_url'];

		if (strstr($url, '{&#036;mobile}'))
		{
			$url = str_replace('{&#036;mobile}', $mobile, $url);
		}

		if (strstr($url, '{&#036;content}'))
		{
			$url = str_replace('{&#036;content}', $content, $url);
		}

		if (!$sms_server['return_type'])
		{
			$type = 'json';
		}
		else
		{
			$type = $sms_server['return_type'];
		}
		$return = $this->mSmsServer->curl_get($url, $type);
		if ((isset($return['return']) && $return['return'])||$return['result'] == '01' || (isset($return['result']['err_code']) && $return['result']['err_code'] == '0'))
		{
			//纪录发送次数
			$this->mSmslog->replace($mobile);

			return true;
		}
		else
		{
			return false;
		}
	}


	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}
}

$out = new invite_userApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'generate_invite';
}
$out->$action();
?>