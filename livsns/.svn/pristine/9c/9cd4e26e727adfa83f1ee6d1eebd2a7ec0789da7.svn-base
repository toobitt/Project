<?php
require_once(dirname(__FILE__) . '/avos/AV.php');
require_once(dirname(__FILE__) . '/avos/AVObject.php');
require_once(dirname(__FILE__) . '/avos/AVQuery.php');
require_once(dirname(__FILE__) . '/avos/AVUser.php');
require_once(dirname(__FILE__) . '/avos/AVFile.php');
require_once(dirname(__FILE__) . '/avos/AVPush.php');
require_once(dirname(__FILE__) . '/avos/AVGeoPoint.php');
require_once(dirname(__FILE__) . '/avos/AVACL.php');
require_once(dirname(__FILE__) . '/avos/AVCloud.php');
class pushMessage
{
	const AVOS_URL 	= 'https://cn.avoscloud.com/1/';
	private $_avpush;
	private $_msg;//保存所要推送的消息
	public function __construct($config = array())
	{
		$this->init($config);
	}
	
	//初始化数据
	public function init($config = array())
	{
		$this->_avpush = new AVPush();
		if($config)
		{
			$this->_avpush->app_info = array(
				'access_id' 	=> $config['app_id'],
				'access_key' 	=> $config['app_key'],
				'secret_key' 	=> $config['master_key'],
				'avos_url' 		=> self::AVOS_URL,
			);
			
			//此处ios用到
			if($config['device_type'] == 'ios')
			{
				$this->_avpush->alert = $config['msg']['push_content'];
				$this->_avpush->badge = 'Increment';
				$this->_avpush->sound = 'default';
				$this->_avpush->push_extend = $config['msg']['push_extend'];
			}
			else 
			{
				//加入消息体
				if($config['msg'] && is_array($config['msg']))
				{
					foreach ($config['msg'] AS $k => $v)
					{
						$this->_avpush->{$k} = $v;
					}
				}
			}

			//订阅频道
			$this->_avpush->channels =  array($config['device_type']);
		}
	}
	
	//开始推送
	public function push()
	{
		$ret = $this->_avpush->send();
		$ret = object_array($ret);

		if($ret['objectId'])
		{
			$ret['errcode'] = 0;
			$ret['msg_id'] = $ret['objectId'];
			$ret['errmsg']	= '发送成功';
		}
		else 
		{
			$ret['errcode'] = 1;
			$ret['errmsg']	= '发送失败';
		}
		return $ret;
	}
}
