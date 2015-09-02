<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: bind.php 1710 2011-01-11 05:54:06Z chengqing $
***************************************************************************/
session_start();
define('ROOT_DIR', '../');
define('SCRIPTNAME', 'bind');
require('./global.php');

/**
 * oauth 授权
 */

class oauth extends uiBaseFrm
{
	private $curl;
	private $oauth; 
	
	function __construct()
	{
		parent::__construct();
		
		$this->check_login();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		include_once (ROOT_PATH . 'lib/class/weibooauth.class.php');
		$this->curl = new curl();		
		$this->oauth = new WeiboOAuth( WB_AKEY , WB_SKEY );
		$this->load_lang('bind');
			
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{	
		
		//获取绑定信息
		$bind_info = $this->get_bind_info();
										
		if(!$bind_info)
		{
			$is_bind = false;		
		}
		else
		{
			$is_bind = true;
			$bind_info = $bind_info[0];
		}
				
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'bind.js');
		$gScriptName = SCRIPTNAME;
		$this->page_title =  $this->lang['pageTitle'];
		$this->tpl->addVar('bind_info', $bind_info);
		$this->tpl->addVar('is_bind', $is_bind);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('bind');
	}
	
	/**
	 * 设置绑定其他微博
	 */
	public function bind()
	{		
		if($this->input['is_bind'])
		{
			$this->set_bind_info();
		}
		else
		{			
			$dir = substr($_SERVER['SCRIPT_NAME'] , 0 , (strrpos($_SERVER['SCRIPT_NAME'] , '/')+1));
			
			$callback = 'http://' . $_SERVER['SERVER_NAME'] . $dir . 'callback.php';
			
			//应用向点滴平台发起请求,获得一个临时的oauth_token,和oauth_token_secret
			$keys = $this->oauth->getRequestToken();
			
			//应用将用户转向到点滴授权页面,同时带上这个token和一个回调页面地址
			$aurl = $this->oauth->getAuthorizeURL( $keys['oauth_token'] ,false , $callback);
					
			$_SESSION['keys'] = $keys;
			header("Location:$aurl");
		} 	
	}
	
	/**
	 * 更新绑定信息
	 */
	public function set_bind_info()
	{
		$type = $this->input['type'];
		$is_bind = $this->input['is_bind'];			
		$state = $this->input['state'];
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('func', 'set');
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('state', $state);
		$this->curl->addRequestData('is_bind', $is_bind);
		return $this->curl->request('oauth/oauth.php');
	}
	
	
	/**
	 * 获取绑定信息
	 */
	public function get_bind_info()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('func', 'get_bind_info');		
		return $this->curl->request('oauth/oauth.php');
	}

	
	/**
	 * 获取是否绑定
	 */
	public function get_bind_state()
	{
		$type = $this->input['type'];
		
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('func', 'is_bind');
		$this->curl->addRequestData('type', $type);	
		$bind_info = $this->curl->request('oauth/oauth.php');
		
		if($bind_info == 0)
		{
			echo 0;
		}
		else
		{
			$bind_info = $bind_info[0];
			$bind_info = $bind_info['is_bind'] . ',' . $bind_info['state'];
			echo $bind_info;
		}
	}
	
	
	/**
	 * 解除绑定
	 */
	public function destroy()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('func', 'destroy');
		$this->curl->request('oauth/oauth.php');	
	}
	
	/**
	 * 添加绑定
	 */
	public function add_bind()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('func', 'add_bind');
		$this->curl->request('oauth/oauth.php');
	}
	
	
	/**
	 * 同步点滴
	 */
	public function syn()
	{
		$state = $this->input['state'];
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('func', 'syn');
		$this->curl->addRequestData('state', $state);
		$this->curl->request('oauth/oauth.php');	
	}
	
}

$out = new oauth();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>