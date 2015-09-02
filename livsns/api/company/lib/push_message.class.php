<?php
//推送消息给某人的类
class pushMessage
{
	private $iospush;
	private $androidpush;
	
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
		
		$this->iospush = new pushNotify();
		$this->androidpush = new jpush();
	}
	
	//发消息给某人
	public function sendMessage($params)
	{
		if(!$params['deviceToken'] || !$params['message'] || !$params['type'])
		{
			return false;
		}
		$type = strtolower($params['type']);
		//判断用户设别是ios还是andriod来选取哪一种推送
		if ($type == 'ios')
		{
			$messageArr = array(
				'badge' 		=> 1,	
				'sound' 		=> 'default',	
				'text' 			=> $params['message'],
				'exchange_id'	=> $params['exchange_id'],
				'title'			=> $params['title'],
			);
			//连接苹果服务器
			if($this->iospush->connectToAPNS())
			{
				$this->iospush->send($params['deviceToken'], $messageArr);//发消息
				$this->iospush->closeConnections();//关闭连接
			}
		}
		else if ($type == 'android')
		{
			//附加参数
			$n_extras = array(
				'exchange_id'	=> $param['exchange_id'],
			);
			$messageArr = array(
			    'n_builder_id' => 0,
			    'n_title' => $params['title'],
			    'n_content' => $params['message'],
			    'n_extras' => $n_extras
			);
			$msg_content = json_encode($messageArr);   
			$this->androidpush->send(1, 3, $params['deviceToken'], 1, $msg_content);
		}
	}
}