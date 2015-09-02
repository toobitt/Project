<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: destroy.php 677 2010-12-16 05:59:45Z develop_tong $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class destroy_attention_count extends appCommonFrm
{
	private $mUser;
	function __construct()
	{ 
		parent::__construct();
			
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->mUser = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	* 减少一个点滴数
	*/
	public function destroy_attention_count() 
	{	
		//$this->input['id'] = 461;
		//$userinfo['id'] =36 ;
		//验证用户是否登录
		require_once(ROOT_DIR.'lib/user/user.class.php');
		$this->user = new user();
		
		$userinfo = $this->user->verify_credentials(); 
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		if($this->input['id'])
		{
			$userinfo['id'] = $this->input['id'];
		}
		//用户的点滴数目减一
		$sql = "UPDATE ".DB_PREFIX."member_extra SET 
			status_count = (status_count - 1)
			WHERE member_id = ".$userinfo['id'];
		
		$row = $this->db->query($sql);
		if(!$row)
		{
			$this -> errorOutput(COUNT_FALES);
		}
		//返回信息
		if($row)
		{
			$this->setXmlNode('userinfo','status_count');
			$this->addItem('status_count_ture');
			return $this->output();
		}
	}
}
$out = new destroy_attention_count();
$out->destroy_attention_count();
?>