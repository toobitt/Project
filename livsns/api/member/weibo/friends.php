<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: friends.php 5369 2011-12-17 01:54:21Z develop_tong $
***************************************************************************/

require('global.php');
class getFriendsInfoApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();	
	}
	
	public function show()
	{	
		if(!$this->user['user_id'])
		{
			$this -> errorOutput(USENAME_NOLOGIN);  //用户未登录
		}
		
		/**
		 * 
		 * 用于分页请求，请求第1页page传0
		 * 每页默认是显示20条记录,最大请求不得超过200条
		 *  
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
								
		$sql = "SELECT r.fmember_id AS id , r.follow_time ,m.member_name as username , m.host, m.dir, m.filepath, m.filename, e.* 
				FROM " . DB_PREFIX . "member_relation AS r 
				LEFT JOIN " . DB_PREFIX . "member AS m ON r.fmember_id = m.id  
				LEFT JOIN " . DB_PREFIX . "member_extra AS e ON m.id = e.member_id ";
					
		/**
		 * 传递用户ID
		 */
		
		if($this->input['user_id'])
		{
			$user_id = $this->input['user_id'];                      //被关注ID							
			$condition = " WHERE r.member_id = " . $user_id . " 
						   ORDER BY r.follow_time DESC 
						   LIMIT " . $offset . "," . $count;			
			$sql = $sql . $condition;			
		}
		
		/**
		 * 传递用户呢称
		 */		
		if($this->input['screen_name'])
		{
			$screen_name = urldecode($this->input['screen_name']);   //用户呢称
			$condition = " WHERE m.nick_name = '" . $screen_name . "' 
					       ORDER BY r.follow_time DESC
					       LIMIT " . $offset . ',' . $count;			
			$sql = $sql . $condition;					
		}
		
		/**
		 * 如果什么参数都没有传递,默认返回该用户的粉丝
		 */
		if(!$this->input['user_id'] && !$this->input['screen_name'])
		{
			$condition = " WHERE m.member_id = " . $this->user['user_id'] . " 
					       ORDER BY r.follow_time DESC
						   LIMIT " . $offset . ',' . $count;
			$sql = $sql . $condition;							
		}
						
		$q = $this->db->query($sql);
		
		$this->setXmlNode('users_info' , 'user');
		if($this->db->num_rows($q) == 0)
		{
			$this->addItem(array());
		}
		else
		{
			while($row = $this->db->fetch_array($q))
			{
				$this->addItem($row);
			}	
		}		
		$this->output();		
	}	
}

$out = new getFriendsInfoApi();
$out->show();
?>