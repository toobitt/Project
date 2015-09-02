<?php

class get_user extends BaseFrm{

    public function __construct()
	{
		parent::__construct();
		include_once(CUR_CONF_PATH . 'lib/share.class.php');
		$this->obj = new share();
		include_once(CUR_CONF_PATH . 'lib/public.class.php');
		$this->pub = new publicapi();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show_user($platdata,$access_token,$uid='',$name='',$openid = '',$url = '')
	{
		include_once(CUR_CONF_PATH . 'lib/'.$this->settings['share_plat'][$platdata['type']]['name'].'_oauth.php');
		//根据uid，name，access_token获取用户头像，名称
		$c = new ClientV2( $platdata['akey'],$platdata['skey'],$platdata['response_type'],$access_token['access_token'] );
		//如果是腾讯，则单独处理
		switch($platdata['type'])
		{
			case 2:  $plattype = 'other';break;
			case 3:  $plattype = 'tx';break;
			case 6:  $plattype = 'tx';break;
			default: $plattype = 1;
		}
		
		if($plattype == 'other')
		{
			$show_action = $this->settings['share_plat'][$platdata['type']]['name'].'_show_user';
			$userdata = $c->$show_action($url?$url:$this->settings['share_plat'][$platdata['type']]['userurl'],$uid,$name,$platdata['skey']);
		}
		else
		{
			$userdata = $c->show_user_by_id($url?$url:$this->settings['share_plat'][$platdata['type']]['userurl'] , $uid , $name , $openid , $plattype);		
		}
		return $userdata;
	}
	
	public function sinaweibo_show_user($platdata, $uid , $name , $openid , $plattype)
	{
		$c = new ClientV2( $platdata['akey'],$platdata['skey'],$platdata['oauthurl'],$platdata['accessurl'],$platdata['callback'],$platdata['userurl'],$platdata['response_type'],$platdata['access_token']['access_token'] );
		$result = $c->show_user_by_id($this->settings['share_plat'][$platdata['type']]['userurl'] , $uid , $name , $openid , $plattype);
		return $result;
	}
	
	public function get_tx_open_id($platdata,$access_token)
	{
		//根据uid，name，access_token获取用户头像，名称
		$c = new ClientV2( $platdata['akey'],$platdata['skey'],$platdata['response_type'],$access_token['access_token'] );
		$userdata = $c->get_tx_open_id($this->settings['share_plat'][$platdata['type']]['openidurl']);		
		return $userdata;
	}
}
?>