<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: authority.php 17947 2013-02-26 02:57:46Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');

/**
 * 
 * 设置权限
 */

class setAuthorityApi extends appCommonFrm
{
	var $mUserlib;
	
	function __construct()
	{
		parent::__construct();
						
		$this->mUserlib = new user();
	}
	
	function __destruct()
	{
		parent::__destruct();	
	}
	
	public function set()
	{
		$userinfo = $this->mUserlib->verify_user(); //验证用户是否登录
				
		if(!$userinfo)
		{
			$this -> errorOutput(USENAME_NOLOGIN);   //用户未登录
		}
		
		//默认
		$authority = '10111100000000000000';
		
		/**
		 * 每位权限设置
		 */
		
		//真实姓名
		if($this->input['true_name'])
		{
			$authority[0] = $this->input['true_name'];	
		}
		
		//生日
		if($this->input['birthday'])
		{
			$authority[1] = $this->input['birthday'];	
		}
		
		//QQ
		if($this->input['qq'])
		{
			$authority[2] = $this->input['qq'];	
		}
		
		//mobile
		if($this->input['mobile'])
		{
			$authority[3] = $this->input['mobile'];			
		}
		
		//msn
		if($this->input['msn'])
		{
			$authority[4] = $this->input['msn'];			
		}
		
		//mobile
		if($this->input['mobile'])
		{
			$authority[5] = $this->input['mobile'];	
		}

		//访问个人页面
		if($this->input['visit_user_info'])
		{
			$authority[14] = $this->input['visit_user_info'];	
		}
		
		//发送私信
		if($this->input['sent_message'])
		{
			$authority[15] = $this->input['sent_message'];	
		}
				
		//地理位置信息
		if($this->input['location'])
		{
			$authority[16] = $this->input['location'];	
		}
		
		//是否允许别人通过真实姓名搜索到我
		if($this->input['search_true_name'])
		{
			$authority[17] = $this->input['search_true_name'];	
		}
		
		//设置谁可以评论我的点滴
		if($this->input['comment'])
		{
			$authority[18] = $this->input['comment'];	
		}
		
		//加关注
		if($this->input['follow'])
		{
			$authority[19] = $this->input['follow'];	
		}

		$sql = "UPDATE " . DB_PREFIX . "member SET privacy = '" . $authority . "' WHERE id = " . $userinfo['id'];
		
		$this->db->query($sql);
	}		
}

$out = new setAuthorityApi();
$out->set();

?>