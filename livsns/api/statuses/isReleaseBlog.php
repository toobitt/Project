<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: isReleaseBlog.php 17941 2013-02-26 02:20:49Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
include(ROOT_PATH . '/lib/user/user.class.php');

/**
 * 
 * 是否在规定的时间内发布了指定的微博数目
 * @author chengqing
 *
 */
class isReleaseBlog extends appCommonFrm
{
	private $mUserlib;
	
	function __construct()
	{
		parent::__construct();
		$this->mUserlib = new user();	
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 
	 * 支持用户ID和用户名
	 */
	public function is_release_blog()
	{
		$userinfo = $this->mUserlib->verify_credentials(); //验证用户是否登录
		if(!$userinfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);  //用户未登录
		}
		
		$condition = '';
		
		if($this->input['start_time'])
		{
			$time  = strtotime($this->input['start_time']);
			$condition .= ' AND create_at > ' . $time;	
		}
		else
		{
			$time  = strtotime('2011-4-11');
			$condition .= ' AND create_at > ' . $time;
		}
		
		if($this->input['end_time'])
		{
			$time  = strtotime($this->input['end_time']);
			$condition .= ' AND create_at < ' . $time;	
		}
		else
		{
			$time  = strtotime('2011-6-30');
			$condition .= ' AND create_at < ' . $time;
		}
		
		$count = $this->input['count'] ? $this->input['count'] : 20;
		
		if(intval($this->input['user_id']))
		{
			$user_id = $this->input['user_id'];
			$sql = 'SELECT COUNT(*) AS nums FROM ' . DB_PREFIX . 'status WHERE member_id = ' . $userinfo['id'];
			$sql = $sql . $condition;			
			$r = $this->db->query_first($sql);	
		}
		
		if($this->input['user_name'])
		{
			$user_name = urldecode($this->input['user_name']);			
			$user_info = $this->mUserlib->getUserByName($user_name);
			$user_id = $user_info['id'];
			$sql = 'SELECT COUNT(*) AS nums FROM ' . DB_PREFIX . 'status WHERE member_id = ' . $user_info[0]['id'];
			$sql = $sql . $condition;		
			$r = $this->db->query_first($sql);				
		}
		
		if(!empty($r))
		{
			if($r['nums'] > $count)
			{
				$this->addItem(true);
			}
			else
			{
				$this->addItem(false);
			} 	
		}
		else
		{
			$this->addItem(false);
		}
		
		$this->output();
	}
}

$out = new isReleaseBlog();
$out->is_release_blog();