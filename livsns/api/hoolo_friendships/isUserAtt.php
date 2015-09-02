<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: isUserAtt.php 3516 2011-04-10 16:30:05Z develop_tong $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');

/**
 * 
 * 关注包括指定用户在内的指定数量的用户
 * @author chengqing
 *
 */

class isUserAtt extends BaseFrm
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
		
	public function is_user_att()
	{
		$userinfo = $this->mUserlib->verify_user(); //验证用户是否登录
		if(!$userinfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);  //用户未登录
		}
		
		$search_user = urldecode(trim($this->input['user_name']));
		
		$sql = 'SELECT r.fmember_id AS id , r.follow_time , m.username , m.avatar , e.* 
				FROM ' . DB_PREFIX . 'member_relation AS r 
				LEFT JOIN ' . DB_PREFIX . 'member AS m ON r.fmember_id = m.id 
				LEFT JOIN ' . DB_PREFIX . 'member_extra AS e ON m.id = e.member_id 
				WHERE r.member_id = ' . $userinfo['id']  . ' 
				AND m.username = "' . $search_user . '"';
					
		$r = $this->db->query_first($sql);
					
		$this->setXmlNode('users_info' , 'user');	

		if(!$r['id'])
		{
			$this->addItem(false);  	//没有关注该用户
			$this->output();	
		}
		

		$count = $this->input['count'] ? $this->input['count'] : 20;
		
				
		$sql = "SELECT count(*) AS total
				FROM " . DB_PREFIX . "member_relation AS r 
				LEFT JOIN " . DB_PREFIX . "member AS m ON r.fmember_id = m.id  
				LEFT JOIN " . DB_PREFIX . "member_extra AS e ON m.id = e.member_id ";
		
		$condition = " WHERE r.member_id = " . $userinfo['id'] ;			
		$sql = $sql . $condition;				
		$q = $this->db->query_first($sql);
		if ($q['total'] >= $this->input['count'])
		{
			$this->addItem(true);  	//没有关注该用户
		}
		else
		{
			$this->addItem(false);  	//没有关注该用户
		}
	
		$this->output();
	}	
}

$out = new isUserAtt();
$out->is_user_att();