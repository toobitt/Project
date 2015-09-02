<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: callback.php 1710 2011-01-11 05:54:06Z chengqing $
***************************************************************************/
session_start();
define('ROOT_DIR', '../');
require('./global.php');


/**
 * 用户确认授权后回调的页面
 */

class callBack extends uiBaseFrm
{
	private $curl;
	private $oauth; 
	
	function __construct()
	{
		parent::__construct();
		
		$this->check_login();
		include_once (ROOT_PATH . 'lib/class/weibooauth.class.php');		
		$this->oauth = new WeiboOAuth(WB_AKEY , WB_SKEY ,$_SESSION['keys']['oauth_token'] , $_SESSION['keys']['oauth_token_secret']);			
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$last_key = $this->oauth->getAccessToken( $this->input['oauth_verifier'] ) ;
				
		if($last_key['error'])
		{
			$this->ReportError('对不起,绑定失败!');	
		}
		else
		{
			$uid = $last_key['user_id'];
						
			/**
			 * 检测多账号绑定新浪同一账号
			 */
			$result = $this->check_repeat_bind($uid);
			
			if($result == 0)
			{
				$this->ReportError('对不起,你已经绑定过了!');
			}
									
			$last_key = serialize($last_key);
						
			/**
		 	  * 录入配置信息
		      */
		    $this->update($last_key , $uid);
		    
		    $dir = substr($_SERVER['SCRIPT_NAME'] , 0 , (strrpos($_SERVER['SCRIPT_NAME'] , '/')+1));
		    $returnUrl = 'http://' . $_SERVER['SERVER_NAME'] . $dir . 'bind.php';
		    header("Location:$returnUrl");		    
		} 					
	}
	
	/**
	 * 保存授权后的最后key值
	 */
	public function update($last_key , $uid)
	{
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl();		
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('func', 'update');
		$this->curl->addRequestData('key', $last_key);
		$this->curl->addRequestData('uid', $uid);
		$this->curl->addRequestData('type', 1);
		$this->curl->addRequestData('state', 1);
		$this->curl->addRequestData('is_bind', 1);			
		$this->curl->request('oauth/oauth.php');
	}

	/**
	 *  检测多账号绑定新浪同一账号
	 */
	public function check_repeat_bind($uid)
	{
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl();		
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('func', 'check');
		$this->curl->addRequestData('type', 1);
		$this->curl->addRequestData('uid', $uid);
		$result = $this->curl->request('oauth/oauth.php');					
		return 	intval($result);
	}
	
}

$out = new callBack();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
