<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: session.php 2693 2011-03-11 01:28:05Z repheal $
***************************************************************************/
class Session
{
	var $db;
	var $user = array();
	var $input = array();
	var $settings = array();
	var $sessionid = 0;
	var $badsessionid = 0;

    
	function __construct()
	{
		$this->__init();
	}

	public function __init()
	{
		global $gDB, $_INPUT, $gConfigs; 
		$this->db = &$gDB;
		$this->input = &$this->input;
		$this->settings = &$gConfigs;
	}	
	
	public function LoadSession($user = '', $pass = '', $sessionid = '')
	{
		$this->user = $this->set_up_guest();
		$user = $user ? $user : hg_get_cookie('user');		
		$pass = $pass ? $pass : hg_get_cookie('pass');
				
		if ($user && $pass)
		{
			$this->load_user();
		}
		return $this->user;
	}

	private function load_user()
	{
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$user = new user();
		$uinfo = $user->verify_credentials();
		$this->user = $uinfo;
	}

	private function unload_user()
	{
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