<?php
define('SCRIPT_NAME', 'PushNoticeUpdate');
define('MOD_UNIQUEID','push_platform');
require_once('./global.php');
require_once(CUR_CONF_PATH."lib/functions.php");
class PushNoticeUpdate extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function sort()
	{
	}
	function audit()
	{
	}
	function publish()
	{
	}
	
	//发送通知，不覆盖
	function create()
	{
		$this->push_notice('create');
	}
	
	
	//重新发送，覆盖上一条
	function update()
	{
		$this->push_notice('update');
	}
	
	
	
	//推送通知
	private function push_notice($type)
	{
		$id = intval($this->input['id']);
		if($type == 'update' && !$id)
		{
			$this->errorOutput('id不存在');
		}
		
		$app_id = intval($this->input['app_push_id']);
		if(!$app_id)
		{
			$this->errorOutput('请选择应用');
		}
		
		/**************权限控制开始**************/
		//节点权限
		if($app_id && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$nodes['nodes'][$app_id] = $app_id;
		}
		$nodes['_action'] = 'notice_manage';
		
		$this->verify_content_prms($nodes);
		/**************权限控制结束**************/	
		
		$content = trim($this->input['content']);
		if(!$content)
		{
			$this->errorOutput('请输入通知内容');
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
		
		
		
		//根据应用id查询注册应用信息
		$sql = 'SELECT * FROM ' . DB_PREFIX . "app_info WHERE id = " . $app_id;
		$appinfo = array();
		$appinfo = $this->db->query_first($sql);
		if(empty($appinfo))
		{
			$this->errorOutput('注册应用信息不存在');
		}
		

		//扩展字段
		if($this->input['extras'])
		{
			$extras = $this->input['extras'];
		}
		
		//通知离线时间
		$expire_time = intval($this->input['expire_time']);
		
		//发送时间
		$send_time = $this->input['send_time'];
		
		//platform_type：1信鸽,2极光，3AVOS
		$platform_type = $appinfo['platform_type'];
		
		
		//极光可以不写标题，不写标题，默认显示应用包名称
		$title = trim($this->input['title']);
		if(!$title && $platform_type == 1)
		{
			$this->errorOutput('请输入通知标题');
		}
		
		if($platform_type == 1)
		{
			$accessId 	= $appinfo['access_id'];
			$secretKey	= $appinfo['secret_key'];
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
				
				//发送时间
				if($send_time)
				{
					$send_time .= ':00';
					$mess->setSendTime($send_time);
				}
				
				//离线时间
				if($expire_time)
				{
					$mess->setExpireTime($expireTime);
				}
				$action = new ClickAction();
				
				$action_type 	= intval($this->input['action_type']);//点击通知操作
				$action_url 	= $this->input['action_url'];//打开的url地址
				$intent 		= $this->input['intent'];
				
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
				else if($action_type == 2 && $intent)
				{
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
			$environment = 0;
			
			//设备类型
			$device_sys 	= '';
			if(($ios || $ios_dev) && $android_sys && $winphone)
			{
				$device_sys = XingeApp::DEVICE_ALL;
				//向ios推送时启用，1生产，2开发
				$environment	= $ios_dev ? 2 : 1;
			}
			else if($ios || $ios_dev)
			{
				$device_sys = XingeApp::DEVICE_IOS;
				//向ios推送时启用，1生产，2开发
				$environment	= $ios_dev ? 2 : 1;
			}
			else if($android_sys)
			{
				$device_sys = XingeApp::DEVICE_ANDROID;
			}
			else if($winphone)
			{
				$device_sys = XingeApp::DEVICE_WINPHONE;
			}
			
			
			
			$ret = $push->PushAllDevices($device_sys, $mess, $environment);
			if(!$ret['ret_code'])
			{
				$ret['errcode'] = 0;
				$ret['msg_id'] = $ret['result']['push_id'];
				$ret['errmsg']	= '发送成功';
			}
			else 
			{
				$ret['errcode'] = $ret['ret_code'];
				$errmsg = $this->XingeErrorMsg($ret['ret_code']);
				$ret['errmsg']	= $errmsg['errmsg'];
			}
		}
		else if($platform_type == 2)//极光推送
		{
			include_once CUR_CONF_PATH . 'lib/jpush/JPushClient.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/Audience.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/Message.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/notification/Notification.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/Options.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/Platform.php';
		    include_once CUR_CONF_PATH . 'lib/jpush/model/PushPayload.php';
		    
		    $app_key 		= $appinfo['access_key'];
		    $master_secret	= $appinfo['secret_key'];
		    //$master_secret = 'b10501ff90b68130097458bc';
    		//$app_key='2193f24f5975b39235afdc9a';
    
    		$client = new JPushClient($app_key, $master_secret);
    		
    		$platform = new Platform();
    		$audience = new Audience();
    		$message = new Message();
    		$options = new Options();

    		$notification = new Notification();
    		
    		$extras = array();
    		$link_module = trim($this->input['link_module']);
    		if($link_module)
    		{
    			$extras = array(
    				$link_module => '',
    			);
    			
    			if($this->input['module_id'])
    			{
    				$extras[$link_module] = $this->input['module_id'];
    			}
    		}
    		
		    //set platform params
		    if($ios || $ios_dev)
		    {
		    	include_once CUR_CONF_PATH . 'lib/jpush/model/notification/IOSNotification.php';
		    	$ios_obj = new IOSNotification();
		    	$platform->ios = true;
		    	//set notification params
			    $ios_obj->alert = $content;
			    $ios_obj->sound = "default";
			    $ios_obj->badge = 1;
			    //$ios->extras = $extras;
			    //静默推送
			    $ios_obj->content_available = null;
			    $notification->ios = $ios_obj;
			    
			    $options->apns_production = $ios_dev ? false : true;
		    }
		    if($android_sys)
		    {
		    	include_once CUR_CONF_PATH . 'lib/jpush/model/notification/AndroidNotification.php';
		    	$android = new AndroidNotification();
		    	$platform->android = true;
		    	$android->alert = $content;
			    $android->title = $title;
			    $android->builder_id = 1;
			    //$android->extras = $extras;
			    $notification->android = $android;
		    }
		    if($winphone)
		    {
		    	include_once CUR_CONF_PATH . 'lib/jpush/model/notification/WinphoneNotification.php';
		    	$winphone_obj = new WinphoneNotification();
		    	$platform->winphone = true;
		    	$winphone_obj->alert = $content;
			    $winphone_obj->title = $title;
			    $winphone_obj->_open_page = "/friends.xaml";
			    //$winphone->extras = $extras;
			    $notification->winphone = $winphone_obj;
		    }
		    
		    
		    //set options params设置选项
		    //$options->sendno = 10;
		    $options->time_to_live = $expire_time;
    
		    $notification->alert = $content;
		    
		    //发送广播通知
		    $payload = new PushPayload();
		    $payload->notification = $notification;
		    //设置接受设备系统
		    //$payload->platform = $platform;
		    $payload->options = $options;
		    
    		$ret = $client->sendPush($payload);
    		$ret = json_decode($ret,1);
    		if($ret['error'])
    		{
    			$ret['errcode'] = $ret['reeor']['code'];
    			$errmsg = $this->JPushErrorMsg($ret['errcode']);
    			$ret['errmsg']	= $errmsg['errmsg'];
    		}
    		else 
    		{
    			$ret['errcode'] = 0;
				$ret['msg_id'] = $ret['msg_id'];
				$ret['errmsg']	= '发送成功';
    		}
		}	
		else if($platform_type == 3)
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
			$app_info['action']			= $appinfo['action'];
			$app_info['avos_url']		= 'https://cn.avoscloud.com/1/';
			
			//应用信息
			$obj->app_info = $app_info;
			##############################################
			if($ios || $ios_dev)
			{
				$ios_info = array(
					'alert' 	=> $content,
				);
				
				//声音
				$sound = $this->input['sound'];
				if($sound)
				{
					$ios_info['sound'] = $sound;
				}
				else 
				{
					$ios_info['sound'] = 'UILocalNotificationDefaultSoundName';
				}
				
				if($this->input['badge'])
				{
					$ios_info['badge'] = $this->input['badge'];
				}
				else 
				{
					$ios_info['badge'] = 'Increment';
				}
				
				if($this->input['link_module'])
				{
					$ios_info['module_id'] = $this->input['link_module'];
				}
				
				if($this->input['content_id'])
				{
					$ios_info['id'] = $this->input['content_id'];
				}
				
				if($ios_dev)
				{
					$obj->prod = 'dev';
				}
				
				$obj->ios = $ios_info;
			}
			
			if($android_sys)
			{
				$and_info = array();
				if($this->settings['push_type'])
				{
					$and_info['push_content'] = $content;
					$and_info['push_title'] = $title;
				}
				else 
				{
					$and_info['alert'] = $content;
					$and_info['title'] = $title;
				}
				
				if($this->input['link_module'])
				{
					$and_info['module_id'] = $this->input['link_module'];
				}
				
				if($this->input['content_id'])
				{
					$and_info['id'] = $this->input['content_id'];
				}
				
				
				if($app_info['action'])
				{
					$and_info['action'] = $app_info['action'];
				}
				else 
				{
					$and_info['action'] = 'com.avos.UPDATE_STATUS';
				}
				
				
				if($appinfo['packagename'])
				{
					$and_info['packagename'] = $appinfo['packagename'];
				}
				
				if($this->settings['push_app_id'])
				{
					$and_info['push_app_id'] = $this->settings['push_app_id'];
				}
				
				$obj->android = $and_info;
			}
			
			
			if($appinfo['channel'])
			{
				$obj->channels = explode(',', $appinfo['channel']);
			}
			
			//指定设备
			$intall_id = trim($this->input['intall_id']);
			if($platform_id)
			{
				$obj->where = array(
					'installationId' => $intall_id,
				);
			}
			##############################################
			
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
			//hg_pre($return);
		}
		
		if(!$send_time)
		{
			$send_time = TIMENOW;
		}
		else 
		{
			$send_time = strtotime($this->input['send_time']);
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
			'org_id'				=> $this->user['org_id'],
			'user_id'				=> $this->user['user_id'],
			'user_name'				=> $this->user['user_name'],
            'appid'				 	=> $this->user['appid'],
			'appname'				=> $this->user['display_name'],
			'ip'					=> hg_getip(),
			'platform_type'			=> $platform_type,
			'send_time'				=> $send_time,
			'expire_time'			=> $expire_time,
			'intall_id'				=> $intall_id,
		);
		//file_put_contents('3.txt', var_export($ret,1));
		if($type == 'create')
		{
			$sql = 'INSERT INTO '.DB_PREFIX.'notice SET ';
			foreach($data as $k=>$v)
			{
				$sql .= "{$k} = '".$v."',";
			}
			$this->db->query(rtrim($sql, ','));
			
			
			$data['id'] = $this->db->insert_id();
		}
		else if($type == 'update')
		{
			$sql = 'UPDATE '.DB_PREFIX.'notice SET ';
			foreach($data as $k=>$v)
			{
				$sql .= "{$k} = '".$v."',";
			}
			$sql = rtrim($sql,',');
			$sql .= ' WHERE id='.$id;
			
			$this->db->query($sql);
			$data['id'] = $id;
		}
	
		$this->addItem($data);
		$this->output();
	}

	function  delete()
	{
		$ids = trim(urldecode($this->input['id']));
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		
		$sql = "SELECT * FROM " . DB_PREFIX ."notice WHERE id IN(" . $ids .")";
		$q = $this->db->query($sql);
		$msg_id = array();
		while($row = $this->db->fetch_array($q))
		{
			if($row['platform_type'] == 3 && $row['msg_id'] && $row['send_time'] > TIMENOW && $row['app_id'])
			{
				$msg_id[$row['app_id']][] = $row['msg_id'];
				$app_id[] = $row['app_id'];
			}
			$app_id[] = $row['app_id'];
			$sorts[] = $row['app_id'];
			$advInfor[$row['id']] = $row;
		}
		
		
		/**************权限控制开始**************/
		//节点验证
		if($sorts && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$sorts = array_filter($sorts);
			if (!empty($sorts))
			{
				$nodes['nodes'][$app_id] = implode(',', $sorts);
				
				if (!empty($nodes))
				{
					$nodes['_action'] = 'notice_manage';
					$this->verify_content_prms($nodes);
				}
			}
			
		}
		//能否修改他人数据
		if (!empty($advInfor) && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			foreach ($advInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'notice_manage'));
			}
		}
		/**************权限控制结束**************/
		
		/**************avos同步删除未发送出去消息***************/
		if(!empty($msg_id))
		{
			include_once CUR_CONF_PATH . 'lib/avos/AV.php';
			$obj = new AVRestClient();
			$sql = "SELECT * FROM " . DB_PREFIX . "app_info WHERE id IN (" . implode(',', $app_id) . ")";
			$query = $this->db->query($sql);
			
			while ($r = $this->db->fetch_array($query))
			{
				$app_info[$r['id']] = $r;
			}
			
			$args = array('method' => 'DELETE');
			foreach ($app_info as $appid => $appinfo)
			{
				if(!$msg_id[$appid])
				{
					continue;
				}
				
				foreach ($msg_id[$appid] as $msgid)
				{
					$appinfo['avos_url'] = 'https://leancloud.cn/1.1/classes/_Notification/'.$msgid;
					$obj->request($args, $appinfo);
				}
			}
		}
		
		
		$sql = 'DELETE FROM '.DB_PREFIX.'notice WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	private function XingeErrorMsg($errcode)
	{
		switch (intval($errcode)) 
		{
			case 0:
			    $res_arr['errmsg'] = '成功';
				break;
			case -1:
			    $res_arr['errmsg'] = '参数错误';
				break;
			case -2:
			    $res_arr['errmsg'] = '请求时间戳不在有效期内';
				break;
			case -3:
				$res_arr['errmsg'] = 'sign校验无效，检查access id和secret key';
				break;
			case 20:
				$res_arr['errmsg'] = '鉴权错误';
				break;
			case 40:
				$res_arr['errmsg'] = '推送的token没有在信鸽中注册';
				break;
			case 48:
				$res_arr['errmsg'] = '推送的账号没有在信鸽中注册';
				break;
			case 73:
				$res_arr['errmsg'] = '消息字符数超限';
				break;
			case 76:
				$res_arr['errmsg'] = '接口调用太频繁，稍后再试';
				break;
			default:
				break;
		}
		return $res_arr;	
	}
	
	private function JPushErrorMsg($errcode)
	{
		switch (intval($errcode)) 
		{
			case 1000:
			    $res_arr['errmsg'] = '系统内部错误';
				break;
			case 1001:
			    $res_arr['errmsg'] = '只支持 HTTP Post 方法';
				break;
			case 1002:
			    $res_arr['errmsg'] = '缺少了必须的参数';
				break;
			case 1003:
				$res_arr['errmsg'] = '参数值不合法';
				break;
			case 1004:
				$res_arr['errmsg'] = '验证失败';
				break;
			case 1005:
				$res_arr['errmsg'] = '消息体太大';
				break;
			case 1006:
				$res_arr['errmsg'] = 'app_key 参数非法';
				break;
			case 1011:
				$res_arr['errmsg'] = '没有满足条件的推送目标';
				break;
			case 1020:
				$res_arr['errmsg'] = '只支持 HTTPS 请求';
				break;
			default:
				break;
		}
		return $res_arr;	
	}
	function unknown()
	{
		$this->erroroutput(NO_METHOD);
	}
}
include(ROOT_PATH . 'excute.php');