<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID','share');
require_once(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH."lib/functions.php");
class followApi extends adminBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/public.class.php');
		include(CUR_CONF_PATH . 'lib/share.class.php');
		$this->pub = new publicapi();
		$this->obj = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 根据系统id,分享平台id  
	 * @name share
	 * @access public
	 * @author 
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return 
	 */
	public function follow()
	{
		$appid = intval($this->input['appid']);
		$platid = intval($this->input['id']);
		$plat_type = intval($this->input['plat_type']);
		$uid = urldecode($this->input['uid']);
		$name = urldecode($this->input['name']);
		$del_follow = $this->input['del_follow'];
		$token = urldecode($this->input['access_plat_token']);
		//先判断token有没有过期
		if($token)
		{
			$checktoken = $this->pub->share_check_token($token);
		}
		else
		{
			$checktoken['msg'] = false;
		}
		if($checktoken['msg'] !== 'new' && $checktoken['msg'])
		{
			include_once(CUR_CONF_PATH . 'lib/'.$this->settings['share_plat'][$checktoken['data']['type']]['name'].'_oauth.php');
			$action = $this->settings['share_plat'][$checktoken['data']['type']]['name'].'_follow';
			$ret = $this->$action($checktoken,$uid,$name,$del_follow);
			if(!empty($ret['error']))
			{
				$this->errorOutput($ret['error']);
			}
			else
			{
				$this->addItem($ret);
				$this->output();
			}
		}
		else
		{
			include(CUR_CONF_PATH.'lib/oauthlogin.class.php');
			$oauthlogin = new oauthlogin();
			if($appid && $platid)
			{
				$ret = $oauthlogin->oauthlogin($appid,$platid,$token);
			}
			else
			{
				if($platid || !$plat_type)
				{
					$this->errorOutput('NO_APPID_PLATID');
				}
				else
				{
					$plat = $this->obj->get_plat_by_type($appid,$plat_type);
					if(empty($plat))
					{
						$this->errorOutput('NO_ANY_PLAT');
					}
					$ret = $oauthlogin->oauthlogin($appid,$plat['id'],$token);
				}
			}
			$ret['error'] = 1;
			$this->addItem($ret);
			$this->output();			
		}
	}
	
	public function sinaweibo_follow($checktoken,$uid,$name,$del_follow)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		$url = $del_follow?$this->settings['share_plat'][$checktoken['data']['type']]['del_followurl']:$this->settings['share_plat'][$checktoken['data']['type']]['followurl'];
		$result = $c->follow_by_id($url,$uid);
		if(empty($result['created_at']))
		{
			$ret['error'] = empty($result['error'])?'empty':$result['error'];
		}
		else
		{
			$ret['msg'] = 'ok';
		}
		return $ret;
	}
	
	public function txweibo_follow($checktoken,$uid,$name,$del_follow)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		$url = $del_follow?$this->settings['share_plat'][$checktoken['data']['type']]['del_followurl']:$this->settings['share_plat'][$checktoken['data']['type']]['followurl'];
		$result = $c->txfollow_by_id($url,$uid,$name,$checktoken['data']['openid']);
		if(!empty($result['errcode']))
		{
			$ret['error'] = empty($result['msg'])?'empty':$result['msg'];
		}
		else
		{
			$ret['msg'] = 'ok';
		}
//		print_r($result);exit;
		return $ret;
	}
	
	public function other_follow($checktoken,$uid,$name,$del_follow)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		$url = $del_follow?$checktoken['data']['platdata']['del_followurl']:$checktoken['data']['platdata']['followurl'];
		$result = $c->follow_by_id($url,$uid);
		if(empty($result['created_at']))
		{
			$ret['error'] = empty($result['error'])?'empty':$result['error'];
		}
		else
		{
			$ret['msg'] = 'ok';
		}
		return $ret;
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright 	ho	gesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new followApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'follow';
}
$out->$action();
?>
