<?php
/*
 * 关注一个用户
 * 
 */
 
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID','share_plat_type');
require_once(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH."lib/functions.php"); 

class friendships_create_api extends adminBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/share.class.php');
		$this->obj = new share();
		include_once(CUR_CONF_PATH . 'lib/public.class.php');
		$this->pub = new publicapi();
		include_once(CUR_CONF_PATH . 'lib/get_user.class.php');
		$this->get_user = new get_user();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$appid = intval($this->input['appid']);
		$platid = intval($this->input['id']);
		$token = urldecode($this->input['access_plat_token']);
		$uid = $this->input['uid'];
		$name= $this->input['name'];
		
		//先判断token有没有过期
		if($token) {
			$checktoken = $this->pub->share_check_token($token);
		}
		else {
			$checktoken['msg'] = false;
		}
		
		
		if($checktoken['msg'] !== 'new' && $checktoken['msg'])
		{
			$platdata = $this->obj->get_by_app_plat($checktoken['data']['appid'],$checktoken['data']['platId']);
			include_once(CUR_CONF_PATH . 'lib/'.$this->settings['share_plat'][$checktoken['data']['type']]['name'].'_oauth.php');
			$action = $this->settings['share_plat'][$platdata['type']]['name'].'_create';
			$ret = $this->$action($checktoken, $uid, $name);
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
				$this->errorOutput('NO_APPID_PLATID');
			}
			$ret['error'] = 1;
			$this->addItem($ret);
			$this->output();			
		}
				
	}	
	
	
	public function sinaweibo_create($checktoken, $uid, $name)
	{
		if($checktoken)
		{
			$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
			if ($this->settings['share_plat'][$checktoken['data']['type']]['friendships_create']) {
				$url = $this->settings['share_plat'][$checktoken['data']['type']]['friendships_create'];
			} else {
				$url = 'https://api.weibo.com/2/friendships/create';
			}
			$result = $c->follow_by_id($url, $uid, $name);
			$ret = array();
			if(!empty($result['id']))
			{
				$ret = array(
					'id'			=> $result['id'],
				    'screen_name'	=> $result['screen_name'],
					'name'			=> $result['name'],
					'location'		=> $result['location'],
					'description'	=> $result['description'],
					'url'			=> $result['url'], 	 
					'profile_image_url'	=> $result['profile_image_url'],
				);
			}
			else
			{
				$ret['error'] = empty($result['error'])?"empty":$result['error'];
			}
			return $ret;
		}
		else
		{
			return "NO_TOKEN_DATA";
		}
	}
	
	public function txweibo_create($checktoken,$uid,$name) {
		if($checktoken)
		{	
			$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
			if ($this->settings['share_plat'][$checktoken['data']['type']]['friendships_create']) {
				$url = $this->settings['share_plat'][$checktoken['data']['type']]['friendships_create'];
			} else {
				$url = 'https://open.t.qq.com/api/friends/add';
			}
			$result = $c->txfollow_by_id($url, $uid, $name, $checktoken['data']['access_token']['openid']);
			$ret = array();
			if(!empty($result['openid']))
			{
				$ret = array(
					'id'			=> $result['openid'],
				    'screen_name'	=> $result['name'],
					'name'			=> $result['name'],	 
				);
			}
			else
			{
				$ret['error'] = empty($result['error'])?"empty":$result['error'];
			}
			return $ret;			
		}
		else
		{
			return "NO_TOKEN_DATA";
		}				
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

$out = new friendships_create_api();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();	 
?>
