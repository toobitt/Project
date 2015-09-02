<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: destroy.php 3953 2011-05-23 05:07:43Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');
require(ROOT_DIR . '/lib/class/curl.class.php');

/**
 *  
 * 删除用户关注接口
 * 返回删除用户的信息
 */
class destroyApi extends BaseFrm
{
	var $mUserlib;

	function __construct()
	{
		parent::__construct();
		$this->curl = new curl();
		$this->mUserlib = new user();
	}

	function __destruct()
	{
		parent::__destruct();
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
		$userinfo = $this->mUserlib->verify_user(); //验证用户是否登录
		if(!$userinfo)
		{
			$this -> errorOutput(USENAME_NOLOGIN);  //用户未登录
		}
		
		$id = $userinfo['id'];                     //用户ID                 
		
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
			/**
	   		 *  配置是否同时取消关注该用户的频道
		   	 * @param $id 网台ID
			 * @return $ret 网台关注信息
	   		 */
			global $gGlobalConfig;
			if($gGlobalConfig['follow_set'] && !$this->input['have'])
			{
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('a', 'del_more');
				$this->curl->addRequestData('id', $this->input['user_id']);
				$this->curl->addRequestData('have',1);
				$ret = $this->curl->request('video/station_concern.php');
				$this->addItem($ret[0]);	
			}
			
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
				$sql = "SELECT 
						m.id  , m.email , m. username , m.username AS screen_name , 
					    m.location , m.birthday , m.qq , m.mobile , m.msn , m.join_time , 
						m.last_login , m.group_id , m.privacy  
						FROM " . DB_PREFIX . "member AS m 
						WHERE id IN($delete_friends)";				
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

$out = new destroyApi();
$out->destroy();
?>