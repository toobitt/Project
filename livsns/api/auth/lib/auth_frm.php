<?php
class Auth_frm extends adminBase
{	
	function __construct()
	{
		parent::__construct();
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//$this->errorOutput(NO_PRIVILEGE);
		}
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
		
	}
}