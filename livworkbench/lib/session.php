<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: session.php 1618 2012-09-25 06:40:17Z zhuld $
***************************************************************************/
session_start();
class Session
{
	private $db;
	private $user = array();
	private $input = array();
	private $settings = array();
	private $sessionid = 0;
	private $badsessionid = 0;

    
	function __construct()
	{
		global $gGlobalConfig;
		$this->settings = $gGlobalConfig;
		$this->__init();
	}

	public function __init()
	{
	}	
	//参数主要解决swf上传跳转问题
	public function LoadSession($access_token = '')
	{
		$this->user = $this->set_up_guest();
		$this->load_user($access_token, $id, $pass);
		return $this->user;
	}

	private function load_user($access_token = '')
	{
		if ($_SESSION['livmcp_userinfo'])
		{
			$this->user = $_SESSION['livmcp_userinfo'];
		}
		else
		{
			if ($access_token && $this->settings['App_auth'])
			{
				include_once(ROOT_PATH . 'lib/class/curl.class.php');
				$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
				$curl->addRequestData('access_token', $access_token);
				$curl->addRequestData('a', 'get_user_info');
				$ret = $curl->request('get_access_token.php');
				if ($ret[0])
				{
					$user = $ret[0];
					$user['id'] = $user['user_id'];
					$this->user = $_SESSION['livmcp_userinfo'] = $user;
				}
			}
		}
	}

	private function unload_user()
	{
		$_SESSION['livmcp_userinfo'] = array();
	}

	private function set_up_guest($userid = 0)
	{
		$user_name = $user_name ? $user_name : '游客';
		return $this->user = array(
			'id' => 0,	
			'username' => $user_name,	
			'password' => $password,	
		);
	}
}
?>