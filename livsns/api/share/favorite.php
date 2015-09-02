<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require_once(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','share');
class favoriteApi extends adminBase
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
	public function favorite()
	{
		$appid = intval($this->input['appid']);
		$platid = intval($this->input['id']);
		$plat_type = intval($this->input['plat_type']);
		$weibo_id = urldecode($this->input['weibo_id']);
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
			$action = $this->settings['share_plat'][$checktoken['data']['type']]['name'].'_favorite';
			$ret = $this->$action($checktoken,$weibo_id);
			if(!empty($ret['error']))
			{
				$this->errorOutput($ret['error']);
			}
			else
			{
				$this->addItem($ret);
				$this->output();
			}
			$this->addItem($ret);
			$this->output();
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
	
	public function sinaweibo_favorite($checktoken,$weibo_id)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		$result = $c->favorite($this->settings['share_plat'][$checktoken['data']['type']]['favorite_addurl'],$weibo_id,'',true);
		
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
	
	public function txweibo_favorite($checktoken,$weibo_id)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		$result = $c->favorite($this->settings['share_plat'][$checktoken['data']['type']]['favorite_addurl'],$weibo_id,$checktoken['data']['openid'],'tx');
		if(!empty($result['errcode']))
		{
			$ret['error'] = empty($result['msg'])?'empty':$result['msg'];
		}
		else
		{
			$ret['msg'] = 'ok';
		}
		return $ret;
	}
	
	public function other_favorite($checktoken,$weibo_id)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		$result = $c->favorite($checktoken['data']['platdata']['favorite_addurl'],$weibo_id,'',true);
		
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

$out = new favoriteApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'favorite';
}
$out->$action();
?>
