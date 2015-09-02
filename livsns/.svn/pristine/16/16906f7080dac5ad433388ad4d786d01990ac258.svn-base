<?php
class publicapi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		include_once(CUR_CONF_PATH . 'lib/share.class.php');
		$this->obj = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function share_check_token($token,$appid='',$platid='')
	{
		$tokendata = $this->obj->get_token_by_token($token);
		if(empty($tokendata))
		{
			$result['msg'] = 'new';
			return $result;
		}
		if(empty($tokendata['access_token']))
		{
			return false;
		}
		$access_token = json_decode($tokendata['access_token']);
		if(empty($access_token->expires_in))
		{
			return false;
		}
		$maxtime = $tokendata['token_addtime']+$access_token->expires_in;
		if($maxtime < TIMENOW)
		{
			return false;
		}
		else
		{
			$result['msg'] = true;
			$result['data'] = $tokendata;
			return $result;
		}
	}
	
}


?>
