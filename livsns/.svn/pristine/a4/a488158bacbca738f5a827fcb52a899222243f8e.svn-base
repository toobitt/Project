<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require_once(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH."lib/functions.php");
define(MOD_UNIQUEID, 'share_search_user)');
class searchuserApi extends adminBase
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
	public function search_user()
	{
		$appid = intval($this->input['appid']);
		$platid = intval($this->input['id']);
		$keyword = urldecode($this->input['keyword']);
		$token = urldecode($this->input['access_plat_token']);
		$plat_type = urldecode($this->input['plat_type']);
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
			$action = $this->settings['share_plat'][$checktoken['data']['type']]['name'].'_search_user';
			$ret = $this->$action($checktoken,$keyword);
			$result['info'] = $ret;
			$result['platId'] = $checktoken['data']['platId'];
			$result['plat_type'] = $checktoken['data']['type'];
			$result['plat_type_name'] = $this->settings['share_plat'][$checktoken['data']['type']]['name_ch'];
			$this->addItem($result);
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
	
	public function sinaweibo_search_user($checktoken,$keyword)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		$result = $c->search_user($this->settings['share_plat'][$checktoken['data']['type']]['search_userurl'],$keyword,'',true);
		$ret = array();
		if(!empty($result[0]['screen_name']))
		{
			foreach($result as $v)
			{
				$ret[]['name'] = $v['screen_name'];
			}
		}
		return $ret;
	}
	
	public function txweibo_search_user($checktoken,$keyword)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		if ($this->settings['share_plat'][$checktoken['data']['type']]['search_userbytag']) {
			$url = $this->settings['share_plat'][$checktoken['data']['type']]['search_userbytag'];
		}
		else {
			$url = "https://open.t.qq.com/api/search/userbytag";
		}
		//$result = $c->search_user($this->settings['share_plat'][$checktoken['data']['type']]['search_userurl'],$keyword,$checktoken['data']['access_token']['openid'],'tx');
		$result = $c->search_userbytag($url,$keyword,$checktoken['data']['access_token']['openid'],'tx');
		$ret = array();
		if(!empty($result['data']['info']))
		{
			foreach($result['data']['info'] as $v)
			{
				$ret[]['name'] = $v['name'];
			}
		}
		return $ret;
	}
	
	public function other_search_user($checktoken,$keyword)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		$result = $c->search_user($checktoken['data']['platdata']['search_userurl'],$keyword,'',true);
		$ret = array();
		if(!empty($result[0]['screen_name']))
		{
			foreach($result as $v)
			{
				$ret[]['name'] = $v['screen_name'];
			}
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

$out = new searchuserApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'search_user';
}
$out->$action();
?>
