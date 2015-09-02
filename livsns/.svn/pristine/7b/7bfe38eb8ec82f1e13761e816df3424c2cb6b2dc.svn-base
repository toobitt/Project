<?php
/***************************************************************************
 * $Id: member.php 46809 2015-07-24 10:13:22Z tandx $
 ***************************************************************************/
define('MOD_UNIQUEID','members');//模块标识
require('./global.php');
require CUR_CONF_PATH . 'lib/member.class.php';
require ROOT_PATH . 'lib/class/curl.class.php';

class sendNotify extends outerReadBase
{
	private $mMember;
	public function __construct()
	{
		parent::__construct();

		$this->mMember = new member();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 发送消息到用户最后一次登录的设备
	 * Enter description here ...
	 */
	public function send_notify()
	{
		$member_id = intval($this->input['member_id']);
		$message = trim($this->input['message']);
		$content_id = intval($this->input['content_id']);
		$module = trim($this->input['module']);
		if (!$member_id)
		{
			$this->errorOutput('NO_MEMBER');
		}		
		if (!$message)
		{
			$this->errorOutput('NO_MESSAGE');
		}
   		$field='member_id, last_login_device';
		$condition = ' AND member_id=' . $member_id;
		$info = $this->mMember->get_member_info($condition,$field,$leftjoin);
		if(!$info)
		{
			$this->errorOutput('NO_MEMBER');
		}
		$last_login_device = $info[0]['last_login_device'];
		if (!$last_login_device)
		{
			$this->errorOutput('NO_DEVICE_LOGIN');
		}
		if ($this->settings['App_push_platform']) //avos
		{
		}
		else
		{
			$device_len = strlen($last_login_device);
			if ($device_len > 60)
			{
				if ($this->settings['App_mobile'])
				{
							$curl = new curl($this->settings['App_mobile']['host'], $this->settings['App_mobile']['dir']);
							$curl->initPostData();
							$curl->addRequestData('device_token', $last_login_device);
							$curl->addRequestData('message', $message);
							$curl->addRequestData('module', $module);
							$curl->addRequestData('content_id', $content_id);
							$ret = $curl->request('send_notify.php');
							if($ret)
							{
								$this->addItem_withkey('result', 1);
							}
							else
							{
								$this->addItem_withkey('result', 0);
							}
				}
			}
			else if ($device_len > 20)
			{
					$curl = new curl($this->settings['App_members']['host'], $this->settings['App_members']['dir']);
					$curl->initPostData();
					$curl->addRequestData('device_token', $last_login_device);
					$curl->addRequestData('message', $message);
					$curl->addRequestData('module', $module);
					$curl->addRequestData('content_id', $content_id);
					$ret = $curl->request('jpush.php');
					if($ret['result'])
					{
						$this->addItem_withkey('result', 1);
					}
					else
					{
						$this->addItem_withkey('result', 0);
					}
			}
		}
		$this->output();
    }

	public function show()
	{
	}

	public function detail()
	{
	}

	public function count()
	{
	}

	
}

$out = new sendNotify();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'send_notify';
}
$out->$action();
?>