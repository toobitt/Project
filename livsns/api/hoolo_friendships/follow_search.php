<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: follow_search.php 5369 2011-12-17 01:54:21Z develop_tong $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');
require(ROOT_DIR . '/lib/class/curl.class.php');

/**
 * 
 * 搜索关注信息接口
 */
class searchFriendsInfoApi extends BaseFrm
{
	private $mUserlib;
	private $curl;
	
	function __construct()
	{
		parent::__construct();						
		$this->mUserlib = new user();
		$this->curl = new curl(); 
	}
	
	function __destruct()
	{
		parent::__destruct();	
	}
	
	public function show()
	{
		$userinfo = $this->mUserlib->verify_user(); //验证用户是否登录
				
		if(!$userinfo)
		{
			$this -> errorOutput(USENAME_NOLOGIN);  //用户未登录
		}
		
		$id = $userinfo['id'];
		
		/**
		 * 
		 * 用于分页请求，请求第1页page传1 
		 */
		$page = $this->input['page'] ? $this->input['page'] : 0;
		
		$page = intval($page);
				
		if(!$this->input['count'])
		{
			$this->input['count'] =  10;
		}
		elseif ($this->input['count'] > 200)
		{
			$this->input['count'] = 200;
		}
		
		$count = intval($this->input['count']);
		
		$offset = $page * $count;
		
		$screen_name = trim(urldecode($this->input['screen_name']));     //用户昵称
		
		$sql = "SELECT count(*) as total_nums 
				FROM " . DB_PREFIX . "member_relation as r 
				LEFT JOIN " . DB_PREFIX . "member as m ON r.member_id = m.id
				LEFT JOIN " . DB_PREFIX . "member_extra as e ON r.member_id = e.member_id 
				WHERE r.fmember_id = " . $id . " AND username LIKE  '%" . $screen_name . "%' ";
		
		$r = $this->db->query_first($sql);
		
		$total_nums = $r['total_nums'];
		
		$sql = "SELECT 
				id,
				username,
				avatar,
				follow_time,
				join_time,
				last_login,
				group_id,
				privacy,
				last_activity,
				followers_count,
				attention_count
				FROM " . DB_PREFIX . "member_relation as r 
				LEFT JOIN " . DB_PREFIX . "member as m ON r.member_id = m.id
				LEFT JOIN " . DB_PREFIX . "member_extra as e ON r.member_id = e.member_id 
				WHERE r.fmember_id = " . $id . " AND username LIKE  '%" . $screen_name . "%' ORDER BY follow_time DESC 
				LIMIT " . $offset . ',' . $count;
		$q = $this->db->query($sql);
		
		$follow = array();
		while($row = $this->db->fetch_array($q))
		{
			$follow[$row['id']] = $row;
			
			if(strlen($row['avatar']) > 32)//qq同步的用户头像
			{
				$follow[$row['id']]['larger_avatar']= hg_avatar($row['id'],"100",$row['avatar'],0);
				$follow[$row['id']]['middle_avatar']= hg_avatar($row['id'],"50",$row['avatar'],0);
				$follow[$row['id']]['small_avatar'] = hg_avatar($row['id'],"10",$row['avatar'],0);
			}
			else 
			{
				$follow[$row['id']]['larger_avatar']= hg_avatar($row['id'],"larger",$row['avatar']);
				$follow[$row['id']]['middle_avatar']= hg_avatar($row['id'],"middle",$row['avatar']);
				$follow[$row['id']]['small_avatar'] = hg_avatar($row['id'],"small",$row['avatar']); 	
			}
		}
				
		if(empty($follow))
		{
			$this -> errorOutput(NORESULT);    				//搜索结果不存在
		}
		else
		{			
			$total = count($follow);
			
			$follow['total'] = $total_nums;
													
			$this->setXmlNode('users_info' , 'user');		
			foreach ($follow AS $item)
			{
				$this->addItem($item);
			}			
			return $this->output();			
		}	
	}
}

$out = new searchFriendsInfoApi();
$out->show();

?>