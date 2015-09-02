<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: search.php 17947 2013-02-26 02:57:46Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');
require(ROOT_DIR . '/lib/class/status.class.php');

/**
 * 获取搜索到的用户信息接口
 */

class searchUserInfoApi extends appCommonFrm
{
	var $mUserlib;
	var $mStatus;
	
	function __construct()
	{
		parent::__construct();						
		$this->mUserlib = new user();
		$this->mStatus = new status();
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
			$this -> errorOutput(USENAME_NOLOGIN);   //用户未登录
		}
		
		/**
		 * 
		 * 用于分页请求，请求第1页page传0 
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

		$screen_name = trim(urldecode($this->input['screen_name']));
		
		$sql = "SELECT 
				COUNT(*) AS total_nums
				FROM ".DB_PREFIX."member
				WHERE (username LIKE '%$screen_name%' OR truename LIKE '%$screen_name%') AND (substring(privacy , -3 , 1) = 0)";
		
		$r = $this->db->query_first($sql);

		$total_nums = $r['total_nums'];
		
		$sql = "SELECT 
				id,
				username,
				truename,
				avatar,
				join_time,
				last_login,
				group_id,
				privacy,
				e.*
				FROM ".DB_PREFIX."member m
				LEFT JOIN ".DB_PREFIX."member_extra e
				ON m.id = e.member_id
				WHERE (username LIKE '%$screen_name%' OR truename LIKE '%$screen_name%') AND (substring(privacy , -3 , 1) = 0) order by id desc
				LIMIT " . $offset . ',' . $count ;
		$q = $this->db->query($sql);
		
		$blog_ids = '';
		
		$user_info = array();
		while($row = $this->db->fetch_array($q))
		{
			$blog_ids .= $row['last_status_id'] . ',';
			
			if(strlen($row['avatar']) > 32)//qq同步的用户头像
			{
				$row['large_avatar']= hg_avatar($row['id'],"100",$row['avatar'],0);
				$row['middle_avatar']= hg_avatar($row['id'],"50",$row['avatar'],0);
				$row['small_avatar'] = hg_avatar($row['id'],"10",$row['avatar'],0);
			}
			else 
			{
				$row['larger_avatar']= hg_avatar($row['id'],"larger",$row['avatar']);
				$row['middle_avatar']= hg_avatar($row['id'],"middle",$row['avatar']);
				$row['small_avatar'] = hg_avatar($row['id'],"small",$row['avatar']);
			}
			$user_info[] = $row;			
		}
				
		if(empty($user_info))
		{
			$this -> errorOutput(USERNOTEXIST);    //用户不存在	
		}
		else
		{
			$blog_ids = substr($blog_ids , 0 , strlen($blog_ids)-1);
		
			$content = $this->mStatus->show($blog_ids);

			$info = array();
			$len = count($user_info);

			for($i = 0 ; $i < $len ; $i++)
			{
				if ($content[$i])
				{
					$user_info[$i]['text'] = $content[$i]['text'];
				}
			}
			
			$user_info['total'] = $total_nums;
			$this->setXmlNode('users_info' , 'user');		
			foreach ($user_info AS $item)
			{
				$this->addItem($item);
			}						
			$this->output();			
		}
	}	
}

$out = new searchUserInfoApi();
$out->show();
?>