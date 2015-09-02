<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user.php 3953 2011-05-23 05:07:43Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
include './uclient/client.php';
require_once("./qq/comm/utils.php");
class qqlogin extends uiBaseFrm
{	
	private $mUser;
	function __construct()
	{		
		parent::__construct();
		$this->load_lang('user');
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->mUser = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" 
        . $_SESSION['access_token'];
		$str  = get_url_contents($graph_url);
		if (strpos($str, "callback") !== false)
		{
			$lpos = strpos($str, "(");
			$rpos = strrpos($str, ")");
			$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
		}

		$user = json_decode($str);
		if (isset($user->error))
		{
			echo "<h3>error:</h3>" . $user->error;
			echo "<h3>msg  :</h3>" . $user->error_description;
			exit;
		}

		//debug
		//echo("Hello " . $user->openid);

		//set openid to session
		$_SESSION["openid"] = $user->openid;

		$get_user_info = "https://graph.qq.com/user/get_user_info?"
        . "access_token=" . $_SESSION['access_token']
        . "&oauth_consumer_key=" . $_SESSION["appid"]
        . "&openid=" . $_SESSION["openid"]
        . "&format=json";
		$info = get_url_contents($get_user_info);
		$arr = json_decode($info, true);
		$arr['openid'] = $_SESSION["openid"];
		$arr['avatar'] = str_replace('100','',$arr['figureurl_2']);
/*		$arr = array(
			'nickname' => 'Yang',
			'avatar' => str_replace('100','','http://qzapp.qlogo.cn/qzapp/206523/A2D8AE5184F0453A7F5502463CE6DD0C/100'),
			'openid' => 'A2D8AE5184F0453A7F5502463CE6DD0C',
		);*/
			
		$member = $this->mUser->qq_login($arr['nickname'], $arr['openid'], $arr['avatar']);
		$member['large_avatar']= $member['avatar'].'30';
		$member['middle_avatar']= $member['avatar'].'50';
		$member['small_avatar'] = $member['avatar'].'100';
		$timestamp = TIMENOW;
		// uid 大于0 登录成功，-1 ： 用户不存在,或者被删除   -2：密码错误  其他：未定义
		if($member['id'] > 0) 
		{
			//同步登录
			$ucsynlogin =  uc_user_synlogin($member['id']);
			//print_r($ucsynlogin);exit;
			hg_set_cookie('user', urldecode($member['username']), $timestamp+ 31536000);
			hg_set_cookie('pass',$member['password'], $timestamp+ 31536000);
			hg_set_cookie('member_id',$member['id'], $timestamp+ 31536000);
		//	hg_pre($_COOKIE);exit;
			$this->Redirect($this->lang['loginsucess'], '', 2, 0, $ucsynlogin);
		}
		else
		{
			$this->ReportError($this->lang['nameerror']);
		}
	}

	
	/**
	 * @brief 跳转到QQ登录页面.请求需经过URL编码，编码时请遵循 RFC 1738
	 *
	 * @param $appid
	 * @param $appkey
	 * @param $callback
	 *
	 * @return 返回字符串格式为：oauth_token=xxx&openid=xxx&oauth_signature=xxx&timestamp=xxx&oauth_vericode=xxx
	 */
	function redirect_to_login()
	{
		$appid = $_SESSION["appid"];
		$appkey = $_SESSION["appkey"];
		$callback =  $_SESSION["callback"] . "&referto=" . $this->input['referto'];

		$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		$login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=" . $_SESSION["appid"] . "&redirect_uri=" . urlencode($callback) . "&state=" . $_SESSION['state'] . "&scope=" . $_SESSION["scope"];
//		header("Location:$login_url");
		echo "<script>document.location.href='" . $login_url . "';</script>";
	}

	function callback()
	{
		$token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
				. "client_id=" . $_SESSION["appid"]. "&redirect_uri=" . urlencode($_SESSION["callback"])
				. "&client_secret=" . $_SESSION["appkey"]. "&code=" . $_REQUEST["code"];

		$response = get_url_contents($token_url);
		if (strpos($response, "callback") !== false)
		{
			$lpos = strpos($response, "(");
			$rpos = strrpos($response, ")");
			$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
			$msg = json_decode($response);
			if (isset($msg->error))
			{
				echo "<h3>error:</h3>" . $msg->error;
				echo "<h3>msg  :</h3>" . $msg->error_description;
				exit;
			}
		}

		$params = array();
		parse_str($response, $params);

		//debug
		//print_r($params);

		//set access token to session
		$_SESSION["access_token"] = $params["access_token"];

		$this->show();
	}
}
$out = new qqlogin();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'redirect_to_login';
}
$out->$action();
?>