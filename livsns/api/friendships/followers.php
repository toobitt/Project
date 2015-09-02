<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: followers.php 5369 2011-12-17 01:54:21Z develop_tong $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');
require(ROOT_DIR . '/lib/class/curl.class.php');

/**
 * 
 * 获取粉丝信息接口
 */
class getFollowsInfoApi extends BaseFrm
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
			
		/**
		 * 
		 * 用于分页请求，请求第1页page传1 
		 */
		$page = $this->input['page'] ? $this->input['page'] : 0;
		
		$page = intval($page);
				
		if(!$this->input['count'])
		{
			$this->input['count'] =  20;
		}
		elseif ($this->input['count'] > 200)
		{
			$this->input['count'] = 200;
		}
		
		$count = intval($this->input['count']);
		
		$offset = $page * $count;
		
		$sql = "SELECT r.member_id AS id , r.follow_time , m.username ,m.avatar , e.* 
				FROM " . DB_PREFIX . "member_relation AS r 
				LEFT JOIN " . DB_PREFIX . "member AS m ON r.member_id = m.id 
				LEFT JOIN " . DB_PREFIX . "member_extra AS e ON m.id = e.member_id"; 
				
		/**
		 * 传递用户ID
		 */
		if($this->input['user_id'])
		{
			$user_id = $this->input['user_id'];            //被关注ID
			$condition = " WHERE r.fmember_id = " . $user_id . " 
						   ORDER BY r.follow_time DESC 
						   LIMIT " . $offset . ', ' . $count;			
			$sql = $sql . $condition;								
		}

		/**
		 * 传递用户呢称
		 */
		if($this->input['screen_name'])
		{
			$screen_name = $this->input['screen_name'];   //用户呢称			
			$condition = " LEFT JOIN " . DB_PREFIX . "member
						   ON r.fmember_id = id 
					       WHERE m.username = '" . $screen_name . "'
					       ORDER BY r.follow_time DESC  
					       LIMIT " . $offset . ', ' . $count;
			$sql = $sql . $condition;
		}
					
		
		/**
		 * 如果什么参数都没有传递,默认返回该用户的粉丝
		 */
		if(!$this->input['user_id'] && !$this->input['screen_name'])
		{
			$condition = " WHERE r.fmember_id = " . $userinfo['id'] . " 
						   ORDER BY r.follow_time DESC 
						   LIMIT " . $offset . ', ' . $count;

			$sql = $sql . $condition;
							
		}
		
		$q = $this->db->query($sql);
		
		
		
		//关注时间
		$follow_time = array();
		
		$this->setXmlNode('users_info' , 'user');
		if($this->db->num_rows($q) == 0)
		{
			$this -> errorOutput(NOFOLLOWERS);     //没有粉丝
		}
		else
		{
			while($row = $this->db->fetch_array($q))
			{
				//$ids .= $row['member_id'] . ',';
				if(strlen($row['avatar']) > 32)//qq同步的用户头像
				{
					$row['large_avatar']= hg_avatar($row['id'],"100",$row['avatar'],0);
					$row['middle_avatar']= hg_avatar($row['id'],"50",$row['avatar'],0);
					$row['small_avatar'] = hg_avatar($row['id'],"10",$row['avatar'],0);
				}
				else 
				{
					$row['large_avatar']= hg_avatar($row['id'],"larger",$row['avatar']);
					$row['middle_avatar']= hg_avatar($row['id'],"middle",$row['avatar']);
					$row['small_avatar'] = hg_avatar($row['id'],"small",$row['avatar']);
				}
				$this->addItem($row);
			}
			
			//$ids = substr($ids , 0 , -1);			
		}
		
		/*$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('user_id', $ids);
		$user_info = $this->curl->request('users/show.php');
				*/
		
				
		/*foreach ($user_info AS $item)
		{
			$this->addItem($item);
		}*/			
		
		$this->output();						
	}	
}

$out = new getFollowsInfoApi();
$out->show();
?>