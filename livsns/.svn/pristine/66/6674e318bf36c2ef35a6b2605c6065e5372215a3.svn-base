<?php
require ('./global.php');
require_once CUR_CONF_PATH.'lib/functions.php';
define('MOD_UNIQUEID','push_platform');
define('SCRIPT_NAME', 'PushPlatform');
class PushPlatform extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		
		//如果应用在推送平台添加，需要传入应用id，然后根据应用id查询应用的密钥
		$app_push_id = intval($this->input['app_id']);
		if($app_push_id)
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . "app_info WHERE id = " . $app_push_id;
			$appinfo = $this->db->query_first($sql);
		}
			
		
		if(!$appinfo['access_id'] || !$appinfo['access_key'])
		{
			$this->errorOutput('缺少应用配置');
		}
			
			
		$content = trim($this->input['content']);
		if(!$content)
		{
			$this->errorOutput('缺少通知内容');
		}

		//platform_type：1信鸽,2极光，3AVOS
		$platform_type = $appinfo['platform_type'];
		
		//极光可以不写标题，不写标题，默认显示应用包名称
		$title = trim($this->input['title']);
		if(!$title && $platform_type =1)
		{
			$this->errorOutput('请输入通知标题');
		}
		
		//通知离线时间
		$expire_time = intval($this->input['expire_time']);
		
		//发送时间
		$send_time = $this->input['send_time'];
		
		//扩展字段
		if($this->input['extras'])
		{
			$extras = $this->input['extras'];
		}
		
		$ios = $ios_dev = '';
		if($this->input['ios'] == 1)
		{
			$ios_dev = 1;
		}
		else if($this->input['ios'] == 2)
		{
			$ios = 1;
		}
		
		$android_sys 	= $this->input['android'];
		$winphone		= $this->input['winphone'];
		
		if(!$ios && !$ios_dev && !$android_sys && !$winphone)
		{
			$this->errorOutput('请选择客户端设备系统');
		}
		
		if($platform_type == 1)
		{
			$accessId 	= $appinfo['access_id'];
			$secretKey	= $appinfo['access_key'];
			//$accessId = 2100033914;
			//$secretKey = '755776a7f60242ed472848ddf6b08197';
			
			if(!$accessId || !$secretKey)
			{
				$this->errorOutput('请传入应用信息');
			}
			
			
			include_once CUR_CONF_PATH.'lib/XingeApp.php';
			
			$push = new XingeApp($accessId, $secretKey);
			
			$mess = new Message();
			$mess->setTitle($title);
			$mess->setContent($content);
			
			//0是通知，1是消息
			$mess_type = intval($this->input['mess_type']);
			if($mess_type)
			{
				$mess->setType(Message::TYPE_MESSAGE);
			}
			else 
			{
				$mess->setType(Message::TYPE_NOTIFICATION);
			
				$style = new Style(0);
				#含义：样式编号0，响铃，震动，可从通知栏清除，不影响先前通知
				$style = new Style(0,1,1,1,0);
				$mess->setStyle($style);
				
				#接收消息时间范围
				$acceptTime1 = new TimeInterval(0, 0, 23, 59);
				$mess->addAcceptTime($acceptTime1);
				
				$action = new ClickAction();
				
				$action_type 	= intval($this->input['action_type']);//点击通知操作
				$action_url 	= $this->input['action_url'];//打开的url地址
				
				if(!$action_type)
				{
					#打开activity或app本身
					$action->setActionType(ClickAction::TYPE_ACTIVITY);
				}
				else if($action_type == 1 && $action_url)
				{
					//打开链接
					$action->setActionType(ClickAction::TYPE_URL);
					$action->setUrl($action_url);
					
					#打开url需要用户确认
					$action_comfirm = intval($this->input['action_comfirm']);
					$action->setComfirmOnUrl($action_comfirm);
				}
				else if($action_type == 2)
				{
					$intent = $this->input['intent'];
					$action->setActionType(ClickAction::TYPE_INTENT);
					$action->setIntent($intent);
				}
				
				$mess->setAction($action);
				
				#自定义内容
				if($extras)
				{
					$mess->setCustom($extras);
				}
			}
			
			//设备标识
			$device_token = trim($this->input['device_token']);
			//推送帐号
			//$account = trim($this->input['account']);
			
			//向ios推送时启用，1生产，2开发
			$environment	= intval($this->input['environment']);
			
			//设备类型
			$device_type	= intval($this->input['device_type']);
			$device_sys 	= '';
			switch ($device_type)
			{
				case 0:
					$device_sys = XingeApp::DEVICE_ALL;
					break;
				case 1:
					$device_sys = XingeApp::DEVICE_BROWSER;
					break;
				case 2:
					$device_sys = XingeApp::DEVICE_PC;
					break;
				case 3:
					$device_sys = XingeApp::DEVICE_ANDROID;
					break;
				case 4:
					$device_sys = XingeApp::DEVICE_IOS;
					break;
				case 5:
					$device_sys = XingeApp::DEVICE_WINPHONE;
					break;
				default:
					$device_sys = XingeApp::DEVICE_ALL;
			}
			
			
			if($device_token)//推送给单台设备
			{
				$ret = $push->PushSingleDevice($device_token, $mess, $environment);
			}
			//else if ($account)//发送给单个帐号
			//{
				//$ret = $push->PushSingleAccount($device_sys, $account, $mess, $environment);
			//}
			else //发送给所有设备
			{
				$ret = $push->PushAllDevices($device_sys, $mess, $environment);
			}
		}
		else if($platform_type == 2)//极光推送
		{
			if(!$appinfo['access_id'] || !$appinfo['access_key'])
			{
				$this->errorOutput('缺少应用配置');
			}
			
			include_once CUR_CONF_PATH . 'lib/jpush/JPushClient.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/Audience.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/Message.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/notification/Notification.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/notification/IOSNotification.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/notification/AndroidNotification.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/notification/WinphoneNotification.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/Options.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/Platform.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/PushPayload.php';
		    
    		$client = new JPushClient($app_key, $master_secret);
    		
    		$platform = new Platform();
    		$audience = new Audience();
    		$message = new Message();
    		$options = new Options();

    		$notification = new Notification();
    		$ios = new IOSNotification();
    		$android = new AndroidNotification();
    		$winphone = new WinphoneNotification();

		    //set platform params
		    $platform->ios = true;
		    $platform->winphone = true;
		    
		    
		    //set options params设置选项
		    $options->sendno = 10;
		    $options->apns_production = true;
		    $options->time_to_live = 60;
    
		    //set notification params
		    $ios->alert = $content;
		    $ios->sound = "default";
		    $ios->badge = 1;
		    //$ios->extras = $extras;
		    $ios->content_available = null;
		
		    $android->alert = $content;
		    $android->title = $title;
		    $android->builder_id = 1;
		    //$android->extras = $extras;
		
		    $winphone->alert = $content;
		    $winphone->title = $title;
		    $winphone->_open_page = "/friends.xaml";
		    //$winphone->extras = $extras;
		
		    $notification->alert = $content;
		    $notification->android = $android;
		    $notification->ios = $ios;
		    $notification->winphone = $winphone;
		    
		    
		    //发送广播通知
		    $payload = new PushPayload();
		    $payload->notification = $notification;
		    //设置接受设备系统
		    //$payload->platform = $platform;
		    $payload->options = $options;
		    
    		$ret = $client->sendPush($payload);
		}
		elseif ($platform_type == 3)
		{
			if(!$appinfo['access_id'] || !$appinfo['access_key'] || !$appinfo['secret_key'])
			{
				$this->errorOutput('缺少应用配置');
			}
			
			if(!$appinfo['channel'])
			{
				$this->errorOutput('订阅频道不存在');
			}
			
			include_once CUR_CONF_PATH . 'lib/avos/AV.php';
			include_once CUR_CONF_PATH . 'lib/avos/AVObject.php';
			include_once CUR_CONF_PATH . 'lib/avos/AVQuery.php';
			include_once CUR_CONF_PATH . 'lib/avos/AVUser.php';
			include_once CUR_CONF_PATH . 'lib/avos/AVFile.php';
			include_once CUR_CONF_PATH . 'lib/avos/AVPush.php';
			include_once CUR_CONF_PATH . 'lib/avos/AVGeoPoint.php';
			include_once CUR_CONF_PATH . 'lib/avos/AVACL.php';
			include_once CUR_CONF_PATH . 'lib/avos/AVCloud.php';
			
			$obj =new AVPush;
			
			
			$app_info['access_id'] 		= $appinfo['access_id'];
			$app_info['access_key']		= $appinfo['access_key'];
			$app_info['secret_key']		= $appinfo['secret_key'];
			$app_info['avos_url']		= 'https://cn.avoscloud.com/1/';
			
			//应用信息
			$obj->app_info = $app_info;
			
			//设置标题，不设置默认显示应用名称
			$obj->title = $title;
			
			//通知内容
			$obj->alert = $content;
			
			//$this->input['link_module'] = 'news';
			//$this->input['content_id'] = 1;
			if($this->input['link_module'])
			{
				$obj->module_id = $this->input['link_module'];
			}
			
			if($this->input['content_id'])
			{
				$obj->id = $this->input['content_id'];
			}
			//$push->channels = array('123456');
			
			if($appinfo['channel'])
			{
				$obj->channels = explode(',', $appinfo['channel']);
			}

			if($android_sys)
			{
				$obj->action = 'com.avos.UPDATE_STATUS';
			}
			
			//声音
			$sound = $this->input['sound'];
			if($sound)
			{
				$obj->sound = $sound;
			}
			
			//指定设备
			$platform_id = trim($this->input['platform_id']);
			if($platform_id)
			{
				$obj->where = array(
					'installationId' => $platform_id,
				);
			}
			//发送时间
			if($send_time)
			{
				$send_time = date('c',strtotime($send_time));
				$send_time = explode('+', $send_time);
				$send_time = $send_time[0] . '.000Z';
				//file_put_contents('4.txt', $send_time);
				$obj->push_time = $send_time;
			}
			//设置离线时间
			if($expire_time)
			{
				if($send_time)
				{
					$obj->expiration_time_interval = $expire_time;
				}
				else 
				{
					$expire_time += TIMENOW;
					$expire_time = date("c",$expire_time);
					$expire_time = explode('+', $expire_time);
					$expire_time = $expire_time[0] . '.000Z';
					$obj->expiration_time = $expire_time;
					//$obj->expiration_time = '2014-07-22T17:24:13.145Z';
				}
			}
			
			//通知推送
			$return = $obj->send();
			$return = object_array($return);
			
			if($return['objectId'])
			{
				$ret['errcode'] = 0;
				$ret['msg_id'] = $return['objectId'];
				$ret['errmsg']	= '发送成功';
			}
			else 
			{
				$ret['errcode'] = 1;
				$ret['errmsg']	= '发送失败';
			}
			//file_put_contents('1.txt', var_export($return,1));
			//hg_pre($return);
		}
		
		if(!$send_time)
		{
			$send_time = TIMENOW;
		}
		else 
		{
			$send_time = strtotime($send_time);
		}
		$data = array(
			'app_id' 				=> $app_id,
			//'sendno' 				=> $sendno,
			'title' 				=> $title,
			'content' 				=> $content,
			'msg_id'				=> $ret['msg_id'],
			'errcode' 				=> $ret['errcode'],
			'errmsg' 				=> $ret['errmsg'],
			'ios' 				    => $ios,
			'ios_dev'				=> $ios_dev,
			'android' 				=> $android_sys,
			'winphone' 				=> $winphone,
			'create_time'			=> TIMENOW,
            'update_time'			=> TIMENOW,
			//'org_id'				=> $this->user['org_id'],
			'user_id'				=> $this->user['user_id'],
			'user_name'				=> $this->user['user_name'],
            'appid'				 	=> $this->user['appid'],
			'appname'				=> $this->user['display_name'],
			'ip'					=> hg_getip(),
			'platform_type'			=> $platform_type,
			'send_time'				=> $send_time,
			'expire_time'			=> $expire_time,
		);
		
		$sql = 'INSERT INTO '.DB_PREFIX.'notice SET ';
		foreach($data as $k=>$v)
		{
			$sql .= "{$k} = '".$v."',";
		}
		$this->db->query(rtrim($sql, ','));
		
		$data['id'] = $this->db->insert_id();
		
		$this->addItem($data);
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
	
		return $condition ;
	}
	
	public function detail(){}
	public function count(){}	
}
include(ROOT_PATH . 'excute.php');
?>