<?php
define('MOD_UNIQUEID','push_message');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/pushMessage.php');
require_once(CUR_CONF_PATH . 'lib/app.class.php');
require_once(CUR_CONF_PATH . 'lib/push_msg_mode.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');

class push_message extends outerUpdateBase
{
	private $api;
	private $push_msg_mode;
    public function __construct()
	{
		parent::__construct();
		$this->api = new app();
		$this->push_msg_mode = new push_msg_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

    public function update(){}
    public function delete(){}
	public function create(){}
	
	//推送消息
	public function push()
	{
		$user_id = $this->user['user_id'];
		if(!$user_id)
		{
			$this->errorOutput(NO_LOGIN);
		}
		
		$msg = $this->input['msg'];
		if(!$msg)
		{
			$this->errorOutput(MSG_CAN_NOT_EMPTY);
		}

		//获取应用信息
		$appInfo = $this->api->getAppInfoByUserId($user_id);
		if(!$appInfo)
		{
			$this->errorOutput(APP_NOT_EXISTS);
		}

		//构造消息体
		$msg_body = array(
			'push_title' 	=> $appInfo['name'],
			'push_content' 	=> $msg,
			'action'		=> 'com.dingdone.UPDATE_STATUS',
			'push_app_id'	=> $appInfo['id'],//推送给哪个应用的id
		);
		
		//根据不同的打开方式构造不同的消息体
		$open_mode = intval($this->input['open_mode']);
		//打开模块的方式
		if($open_mode == 1)
		{
			if(!$this->input['module_id'])
			{
				$this->errorOutput(NO_MODULE_ID);
			}
			$msg_body['push_extend'] = $this->input['module_id'] . '#';
		}
		elseif ($open_mode == 2)//打开内容的方式
		{
			if(!$this->input['module_id'])
			{
				$this->errorOutput(NO_MODULE_ID);
			}
			
			if(!$this->input['content_id'])
			{
				$this->errorOutput(NO_CONTENT_ID);
			}
			
			if(!$this->input['app_uniqueid'])
			{
				$this->errorOutput(NO_MODULE_MARK);
			}
			$msg_body['push_extend'] = $this->input['app_uniqueid'] . '#' . $this->input['content_id'] . '#' . $this->input['module_id'];
		}
		elseif ($open_mode == 3)//打开链接的方式
		{
			if(!$this->input['push_url'])
			{
				$this->errorOutput(NO_PUSH_URL);
			}
			$msg_body['push_extend'] = $this->input['push_url'];
		}
		else 
		{
			$this->errorOutput(NO_SELECT_OPEN_MODE);//未选择打开方式
		}
		
		//根据用户的user_id获取用户的推送接口配置
		$pushApi = $this->getPushApiConfig($user_id);
		if(!$pushApi)
		{
			$this->errorOutput(THIS_USER_NOT_PUSH_API);
		}
		
		//终端类型
		$device_type = strtolower($this->input['device_type']);
		if(!$device_type)
		{
			$this->errorOutput(NO_SELECT_DEVICE_TYPE);
		}
		else
		{
			$deviceTypeArr = explode(',', $device_type);
			foreach($deviceTypeArr AS $k => $v)
			{
				if(!in_array($v, array('ios','android')))
				{
					$this->errorOutput(DEVICE_TYPE_ERR);
				}
			}
		}
		
		//分别针对ios与android发送
		foreach ($deviceTypeArr AS $k => $_device_type)
		{
			$_push = new pushMessage(array(
				'app_id' 		=> $pushApi['app_id'],
				'app_key' 		=> $pushApi['app_key'],
				'master_key' 	=> $pushApi['master_key'],
				'device_type'	=> $_device_type,
				'msg'			=> $msg_body,
			));
			//推送
			$ret = $_push->push();
		}

		//保存推送的消息
		$this->push_msg_mode->create(array(
			'app_id'		=> $appInfo['id'],
			'user_id' 		=> $user_id,
			'user_name' 	=> $this->user['user_name'],
			'device_type' 	=> $device_type,
			'title' 		=> $appInfo['name'],
			'msg' 			=> $msg,
			'status' 		=> $ret['errcode']?2:1,//1:成功 2：失败
			'open_mode'		=> $open_mode,//记录打开模式
			'create_time' 	=> TIMENOW,
		));
		
		$this->addItem($ret);
		$this->output();
	}
	
	//根据用户的user_id获取用户的推送接口配置
	public function getPushApiConfig($user_id = '')
	{
		if(!$user_id)
		{
			return false;
		}
		
		$curl = new curl($this->settings['App_company']['host'], $this->settings['App_company']['dir']);
        $curl->setSubmitType('get');
        $curl->initPostData();
        $curl->addRequestData('a','getPushApiConfig');
        $curl->addRequestData('user_id',$user_id);
        $ret  = $curl->request('user.php');
		if($ret && isset($ret[0]))
        {
        	return $ret[0];
        }
        else 
        {
        	return array();
        }
	}
}

$out = new push_message();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'push';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>