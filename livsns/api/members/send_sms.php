<?php
/***************************************************************************
 * $Id: send_sms.php 46660 2015-07-16 08:59:52Z tandx $
 ***************************************************************************/
define('MOD_UNIQUEID','send_sms');//模块标识
require('./global.php');
class sendSmsApi extends appCommonFrm
{
	private $mSmsServer;
    private $app_sms_count;
    private $applant;
	public function __construct()
	{
		parent::__construct();

		require_once CUR_CONF_PATH . 'lib/sms_server.class.php';
		$this->mSmsServer = new smsServer();

		require_once CUR_CONF_PATH . 'lib/sms.log.class.php';
		$this->mSmslog = new smsLog();
		$this->Members = new members();
        require_once CUR_CONF_PATH . 'lib/app_sms_count_mode.php';
        $this->app_sms_count = new app_sms_count_mode();

        require_once ROOT_PATH . 'lib/class/applant.class.php';
        $this->applant = new applant();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 
 	* 重置密码发送验证码接口 ...
 	*/
	public function rePasswordSendSms()
	{
		$memberId = 0;
		if($memberName = trimall($this->input['member_name']))
		{
			if(hg_check_email_format($memberName))
			{
				$this->errorOutput('请填写正确的用户名');
			}
			if(hg_verify_mobile($memberName))
			{
				$memberId = $this->Members->get_member_id($memberName,false,false,'shouji');
				if($memberId)
				{
					$isMobile  = 1;
					$platform_id = $memberName;
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
			if(!$isMobile)
			{
				if($mobile = trimall($this->input['mobile']))
				{
					$checkBind = new check_Bind();
					$platform_id = $checkBind->check_Bind($memberId,'shouji');
					if($platform_id&&$platform_id!=$mobile)
					{
						$this->errorOutput('对不起，您填写的手机号不正确，请重新输入！');
					}elseif(empty($platform_id)) {
						$this->errorOutput('对不起，您需找回的帐号未绑定手机号!');
					}
				}
				else {
					$this->errorOutput('请输入正确的手机号，并获取验证码!');
				}
			}
			$this->send_sms();
		}
		else $this->errorOutput(NO_MEMBER_NAME);

	}
	
	/**
	 * 
	 * 注册手机验证码接口...
	 */
	public function regSendSms()
	{
		$this->send_sms();	
	}
	
	
	/**
	 * 
	 * 绑定手机验证码接口...
	 */
	public function bindSendSms()
	{
		$this->send_sms();	
	}
	/**
	 * 生成发送手机验证码(未采用任何验证)，历史版本方法，等待私有化
	 * $mobile 手机号
	 *
	 * 返回
	 * success
	 */
	public function send_sms()
	{
        $app_id = intval($this->input['app_id']);
        $appName = $this->input['app_name'];
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

		/*******************************增加可以指定短信配置进行发短信*************************************/
		if($this->input['m_server_id'])
		{
			$condition  = " AND status = 1 AND id = '" .$this->input['m_server_id']. "' ";
		}
		else
		{
			$condition  = " AND status = 1 ORDER BY over DESC LIMIT 1";
		}
		/*******************************增加可以指定短信配置进行发短信*************************************/

        //限制应用的发送短信条数
        if($app_id)
        {
            $app_sms_record = $this->app_sms_count->detail($app_id);
            if($app_sms_record)
            {
                //判断时间是否是上个月  如果是,把total清零
                $last_sms_time = $app_sms_record['last_send_time'];
                if(date('Y-m',TIMENOW) > date('Y-m',$last_sms_time))
                {
                    $this->app_sms_count->update($app_id,array('total' => 0,'last_send_time' => TIMENOW));
                    //重新获取计数
                    $app_sms_record = $this->app_sms_count->detail($app_id);
                }

                $balance = $app_sms_record['total'];
                $limit_count = MAX_SENDSMS_COUNT_LIMITS + $app_sms_record['recharge'];
                if($balance >= $limit_count)
                {
                    $this->errorOutput(SMS_BALANCE_NOT_ENOUGH);
                }
            }
        }


		$sms_server = $this->mSmsServer->get_sms_server_info($condition);
		$sms_server = $sms_server[0];

		if (empty($sms_server))
		{
			$this->errorOutput(SMS_NOT);
		}

		$verifycode_length  = $sms_server['verifycode_length'];
		$verifycode_content = $sms_server['verifycode_content'];
		$content 			= $sms_server['content'];

		if($verifycode = $this->db->query_first("SELECT * FROM " .DB_PREFIX . "mobile_verifycode WHERE mobile='".$mobile."' AND create_time >= ".intval(TIMENOW-VERIFYCODE_EXPIRED_TIME)." ORDER BY create_time DESC"))
		{
			$verifycode = $verifycode['verifycode'];
		}
		else
		{
			$verifycode = hg_set_verifycode($verifycode_length, $verifycode_content);
		}
		if (!$verifycode)
		{
			$this->errorOutput(VERIFY_MAKE_FAILED);
		}

		if (strstr($content, '{&#036;c}'))
		{
			$content = str_replace('{&#036;c}', $verifycode, $content);
		}
		else if (strstr($content, '&#39;{&#036;c}&#39;'))
		{
			$content = str_replace('&#39;{&#036;c}&#39;', $verifycode, $content);
		}
        //为应用名称处理短信发送接口
        if(strstr($content, '{&#036;app}'))
        {
            if (strstr($content, '{&#036;app}'))
            {
                $content = str_replace('{&#036;app}', $appName, $content);
            }
            else if (strstr($content, '&#39;{&#036;app}&#39;'))
            {
                $content = str_replace('&#39;{&#036;app}&#39;', $appName, $content);
            }
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
		/*
			$data = array(
			'mobile'		=> $mobile,
			'verifycode'	=> $verifycode,
			'create_time'	=> TIMENOW,
			);
			if ($this->settings['closesms'])
			{
			$ret = $this->mSmsServer->mobile_verifycode_create($data);
			$this->addItem($data);
			$this->output();
			}
			*/
		if (!$sms_server['return_type'])
		{
			$type = 'json';
		}
		else
		{
			$type = $sms_server['return_type'];
		}
		$return = $this->mSmsServer->curl_get($url, $type);

		if ((isset($return['return']) && $return['return']) || $return['result'] == '01' || (isset($return['result']['err_code']) && $return['result']['err_code'] == '0'))
		{
			//入手机验证码库
			$data = array(
				'mobile'		=> $mobile,
				'verifycode'	=> $verifycode,
				'create_time'	=> TIMENOW,
			);
				
			$ret = $this->mSmsServer->mobile_verifycode_create($data);
				
			//纪录发送记录和次数
			$this->mSmslog->replace($mobile);
            //记录app发送的次数和最后的时间
            if($app_id)
            {
                $this->record_app_count($app_id);
            }
				
			if (!$ret)
			{
				$this->errorOutput(VERIFY_ADD_FAILED);
			}
				
			$this->addItem('success');
			$this->output();
		}
		else
		{
			$this->errorOutput(VERIFY_SEND_FAILED);
		}
	}

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

    /**
     * 记录应用发送的条数
     */
    public function record_app_count($app_id)
    {
        if(!$app_id)
        {
            return false;
        }
        $app_info = $this->applant->getUserInfoByAppId($app_id);
        if(empty($app_info))
        {
            $this->errorOutput(NO_APP);
        }
        $user_id = $app_info['user_id'];
        $user_name = $app_info['user_name'];
        $info = $this->app_sms_count->detail($app_id);
        if($info)
        {
            if($info['total'] >= MAX_SENDSMS_COUNT_LIMITS)
            {
                $new_total = $info['recharge'] - 1;
                if($new_total <= 0)
                {
                    $new_total = 0;
                }
                $update_date = array(
                    'recharge' => $new_total,
                    'last_send_time' => TIMENOW,
                );
            }
            else
            {
                $new_total = $info['total'] + 1;
                if($new_total >= MAX_SENDSMS_COUNT_LIMITS)
                {
                    $new_total = MAX_SENDSMS_COUNT_LIMITS;
                }
                $update_date = array(
                    'total' => $new_total,
                    'last_send_time' => TIMENOW,
                );
            }

            $this->app_sms_count->update($app_id,$update_date);
        }
        else
        {
            $create_date = array(
                'app_id' => $app_id,
                'total'  => 1,
                'last_send_time' => TIMENOW,
                'user_id' => $user_id,
                'user_name' => $user_name,
            );
            $this->app_sms_count->create($create_date);
        }
    }

}

$out = new sendSmsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'send_sms';
}
$out->$action();
?>