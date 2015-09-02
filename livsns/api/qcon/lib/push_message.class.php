<?php
//推送消息给某人的类
class pushMessage
{
	private $iospush;
	private $androidpush;
	private $member_mode;
	private $user_login_mode;
	public function __construct()
	{
		if(!class_exists('pushNotify'))
		{
			require_once(CUR_CONF_PATH . 'lib/push_notify.class.php');
		}
		
		if(!class_exists('jpush'))
		{
			require_once(CUR_CONF_PATH . 'lib/jpush.class.php');
		}
		
		if(!class_exists('member_mode'))
		{
			require_once(CUR_CONF_PATH . 'mode/member_mode.php');
		}
		
		$this->iospush = new pushNotify();
		$this->androidpush = new jpush();
		$this->member_mode = new member_mode();
	}
	
	//发消息给某人
	public function sendMessage($param = array())
	{
		if(!$param['uid'] || !$param['message'])
		{
			return false;
		}

		//查询出该用户的device信息
		$device_info = $this->member_mode->getDeviceInfoByUserId($param['uid']);
		if(!$device_info)
		{
			return false;
		}
		
		//判断用户设别是ios还是andriod来选取哪一种推送
		if($device_info['source'] == 1)//ios
		{
			$messageArr = array(
				'badge' 		=> 1,	
				'sound' 		=> 'default',	
				'text' 			=> $param['message'],
				'exchange_id'	=> $param['exchange_id'],
				'title'			=> $param['title'],
			);
			//连接苹果服务器
			if($this->iospush->connectToAPNS())
			{
				$ios_ret = $this->iospush->send($device_info['device_token'], $messageArr);//发消息
				$this->iospush->closeConnections();//关闭连接
			}
		}
		else if($device_info['source'] == 2)//android
		{
			//附加参数
			$n_extras = array(
				'exchange_id'	=> $param['exchange_id'],
			);
			$msg_content = json_encode(array('n_builder_id'=>0, 'n_title' => $param['title'], 'n_content'=>$param['message'],'n_extras' => $n_extras));   
			$android_ret = $this->androidpush->send(1,3,$device_info['device_token'],1,$msg_content);
		}
	}
}