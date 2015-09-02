<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: member.php 11734 2012-09-22 08:45:04Z develop_tong $
***************************************************************************/
require('global.php');
class friendshipsUpdateApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		if (!$this->input['self_id'])
		{
			if(!$this->user['user_id'])
			{
				$this -> errorOutput(USENAME_NOLOGIN);  //用户未登录
			}
			/**
			 * 验证用户是否被设置为设置为黑名单
			 */
			$id = $this->user['user_id'];      				//粉丝ID
		}
		else
		{
			$id = $this->input['self_id'];      				//粉丝ID
		}
		
		$ids = $this->input['user_id'];        		//将要关注者ID
				
		$ids = explode(',' , $ids);
		$block = array();
		  
        $sql = "SELECT member_id FROM " . DB_PREFIX . "member_block WHERE bmemberid = " . $id;
        $q = $this->db->query($sql);
		
	  	while($row = $this->db->fetch_array($q))
	  	{
	   		$block[] = $row['member_id'];
	  	}
	  	
		$follow = array();
		$follow = array_diff($ids , $block);

		if(empty($follow))
		{
			$this->errorOutput(UNFOLLOW);  		//无法关注用户
		}
		
		$ids = implode(',' , $follow);
		
		$sql = "SELECT * FROM " . DB_PREFIX . "member WHERE id IN(" . $ids . ")";  
		$q = $this->db->query($sql);  
		while($row = $this->db->fetch_array($q))
		{
			if($ret && is_array($ret))
			{
				foreach($ret as $k => $v)
				{
					$row['is'] = $v[$row['id']]['is'];
					$row['sid'] = $v[$row['id']]['id'];
					$row['cid'] = $v[$row['id']]['cid'];
				}
			}
			
			if($row['id'] == $id)
			{
				continue;
			}
			$info[$row['id']] = $row; 
		}
		
	 	$success_follow = array();
	 	$this->setXmlNode('users' , 'user');
	 	
  		foreach($info as $v)
  		{
  			$time = time();
  			$privacy = $v['privacy'];
  			
  			/**
  			 * 权限设置
  			 * 第20位表示是否可以加关注
  			 * 0：任何人可以加关注(默认)
  			 * 1：加关注需审核
  			 * 2：任何人不可以加关注
  			 */
  			$add_friend = intval($privacy[19]);

  			$notify_id = array(); 
   			
  			//加关注不需通过审核
  			if($add_friend == 0)
   			{   				
   				$success_follow[] = $v['id'];
   				
   				$sql = "SELECT * FROM " . DB_PREFIX . "member_extra WHERE member_id = " . $id;
				$f = $this->db->query_first($sql);
				if(empty($f))
				{
					$sql = "INSERT INTO " . DB_PREFIX . "member_extra(member_id) values(" . $id . ")";
					$this->db->query($sql);
				}
   				
   				$sql = "INSERT IGNORE INTO " . DB_PREFIX . "member_relation VALUES($id , {$v['id']} , $time)";
   				$this->db->query($sql);
   				
   				/**
   				 * 插入通知的ID
   				 */
   				
   				$notify_id[] = $v['id']; 
   				  				  				
				$sql = "SELECT count(*) as friend_nums FROM " . DB_PREFIX . "member_relation WHERE member_id = " . $id;
				$r = $this->db->query_first($sql);
				$friend_nums = $r['friend_nums'];
				
				$sql = "SELECT count(*) as follow_nums FROM " . DB_PREFIX . "member_relation WHERE fmember_id = " . $v['id'];
				$r = $this->db->query_first($sql);
				$follow_nums = $r['follow_nums'];
   				
   				$sql = "UPDATE " . DB_PREFIX . "member_extra SET attention_count = " . $friend_nums . " WHERE member_id = " . $id;
				$this->db->query($sql);
					
					
				$sql = "UPDATE " . DB_PREFIX . "member_extra SET followers_count = " . $follow_nums . " WHERE member_id = " . $v['id'];										
				$this->db->query($sql);   					
   				
  				$succrss_follow_info[] = $v;
  				$this->addItem($v);				
   			}
   			
   			//加关注需通过审核
   			if($add_friend == 1)
   			{
    			$sql = "INSERT IGNORE INTO " . DB_PREFIX . "member_relation_verify values($id , {$v['id']} , $time)";
    			$this->db->query($sql); 
   			}

   			//任何人无法加我关注
   			if($add_friend == 2)
   			{
   				//不做任何操作	
   			}
  		}
  		
  		
  		/**
   		 *  插入关注通知
   		 
   		
   		$notifyIds = implode(',' , $notify_id);
   		$content = array('title' => '新粉丝' , 'page_link' => SNS_UCENTER . 'fans.php');  		
   		$type = 1;
   		
   		$this->curl->setSubmitType('post');
		$this->curl->addRequestData('a', 'send');
		$this->curl->addRequestData('user_id', $notifyIds);
		$this->curl->addRequestData('content', serialize($content));
		$this->curl->addRequestData('type', $type);
		$this->curl->request('users/notify.php');*/
				
		$queue = array();
		foreach($success_follow as $v)
		{
			$queue[] = '1' . ',' . $id . ',' . $v;		
		}
		
		/**
		 * 加入队列
		 */		
		$this->ConnectQueue();		
		foreach($queue as $v)
		{
			$this->queue->set(FOLLOW_QUEUE , $v);
		}
  		
		/**
		 * 返回用户信息 XML格式
		 */
	  	$this->output();		
	}
	
	/**
	 * 
	 * 删除用户关注
	 */
	public function destroy()
	{
		/*
		 * 验证用户是否登录
		 */
		if(!$this->user['user_id'])
		{
			$this -> errorOutput(USENAME_NOLOGIN);  //用户未登录
		}
		
		$id = $this->user['user_id'];  	
		/**
		 * 当接收的是关注的ID(支持多ID)
		 */
		if($this->input['user_id'])
		{
			$ids  = explode(',' , $this->input['user_id']);
						
			if(count($ids) > BATCH_FETCH_LIMIT)
			{
				$this -> errorOutput(OUTLIMIT);  //输出超出删除数目	
			}
					
			$this->setXmlNode('users' , 'user');
			
			$sql = "SELECT fmember_id FROM " . DB_PREFIX . "member_relation WHERE member_id = " . $id;			
			$q = $this->db->query($sql);
			
			$friends = array();
			while($row = $this->db->fetch_array($q))
			{
				$all_friends[] = $row['fmember_id']; 	
			}
			
			$delete_friends = array_intersect($ids , $all_friends);
			//处理关注的用户
			if(!empty($delete_friends))
			{
				/**
				 * 取出取消关注的用户信息
				 */
				$delete_friends = implode(',' , $delete_friends);
				$sql = "SELECT * FROM " . DB_PREFIX . "member WHERE id IN($delete_friends)";
									
				$q = $this->db->query($sql);
				while($row = $this->db->fetch_array($q))
				{
					$this->addItem($row);
				}
				
				/**
				 * 删除关注用户关系
				 */
				$sql = "DELETE FROM " . DB_PREFIX . "member_relation WHERE fmember_id IN($delete_friends) AND member_id = " . $id;				
				$this->db->query($sql);
				
				/**
				 * 更新扩展表中的用户粉丝和关注数目
				 */
				
				$sql = "UPDATE " . DB_PREFIX . "member_extra SET attention_count = attention_count - 1 WHERE member_id = " . $id;
				$this->db->query($sql);
				
				$sql = "UPDATE " . DB_PREFIX . "member_extra SET followers_count = followers_count - 1 WHERE member_id IN($delete_friends)";
				$this->db->query($sql);
								
				$delete_friends = explode(',' ,$delete_friends);
				
								
				/**
				 * 删除用户关系进入队列
				 */
				$this->ConnectQueue();
				foreach($delete_friends as $v)
				{
					$delete_relation = '0 ,' . $id . ',' . $v;
					$this->queue->set(FOLLOW_QUEUE , $delete_relation);
				}	
			}
			
			$verify = array();
						
		    $verify = array_diff($ids , $delete_friends);
		    
		    //处理等待审核的关注用户
		    if(!empty($verify))
		    {
		    	/**
				 * 取出待审核删除用户信息
				 */
		    	$delete_verify_friends = implode(',' , $verify);		    	
	    	    $sql = "SELECT 
			    	    m.id  , m.email , m. username , m.username AS screen_name , 
					    m.location , m.birthday , m.qq , m.mobile , m.msn , m.join_time , 
						m.last_login , m.group_id , m.privacy  
						FROM " . DB_PREFIX . "member AS m
						WHERE id IN($delete_verify_friends)";				
				$q = $this->db->query($sql);
				
				while($row = $this->db->fetch_array($q))
				{
					$this->addItem($row);	
				}
				
				/**
				 * 删除待审核关注用户关系
				 */
				$sql = "DELETE FROM " . DB_PREFIX . "member_relation_verify WHERE fmember_id IN($delete_verify_friends)";				
				$this->db->query($sql);		    	
		    }		   
		}
		
		/**
		 * 当接收的是关注名(不支持多用户名)
		 */
		if($this->input['screen_name'])
		{
			/**
			 * 取出删除用户信息
			 */
			$sql = "SELECT 
					m.id  , m.email , m. username , m.username AS screen_name , 
			    	m.location , m.birthday , m.qq , m.mobile , m.msn , m.join_time , 
					m.last_login , m.group_id , m.privacy  FROM " . DB_PREFIX . "member AS m
					WHERE username = '" . $this->input['screen_name'] . "'";
			$delete_user = $this->db->query_first($sql);

			$this->setXmlNode('users' , 'user');
			$this->addItem($delete_user);
			
			/**
			 * 删除关注用户关系
		     */
			$sql = "DELETE FROM " . DB_PREFIX . "member_relation WHERE member_id = " . $id . " AND fmember_id = " . $delete_user['id'];
			
			/**
			 * 更新扩展表中的用户粉丝和关注数目
			 */
			
			$sql = "UPDATE " . DB_PREFIX . "member_extra SET attention_count = attention_count - 1 WHERE member_id = " . $id;
			$this->db->query($sql);
			
			$sql = "UPDATE " . DB_PREFIX . "member_extra SET followers_count = followers_count - 1 WHERE member_id IN($delete_friends)";
			$this->db->query($sql);
			
			if($this->db->query($sql))
			{
				/**
				 * 删除关系进队列
				 */
				$this->ConnectQueue();
				$delete_relation = '0 ,' . $id . ',' . $delete_user['id'];
				$this->queue->set(FOLLOW_QUEUE , $delete_relation);				
			}
			else
			{
				$sql = $sql = "DELETE FROM " . DB_PREFIX . "member_relation_verify WHERE member_id = " . $id . " AND fmember_id = " . $delete_user['id'];
				$this->db->query($sql);
			}			
		}
		
		 /**
		 * 返回用户信息 XML格式
		 */
	  	$this->output();				
	}

}

$out = new friendshipsUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>