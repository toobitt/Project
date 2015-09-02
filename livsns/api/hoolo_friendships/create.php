<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: create.php 3953 2011-05-23 05:07:43Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');
require(ROOT_DIR . '/lib/class/curl.class.php');

class createApi extends BaseFrm
{
	var $mUserlib;

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
	/**
	* 增加用户关注
	*
	*/
	public function create()
	{
		if (!$this->input['self_id'])
		{
			$userinfo = $this->mUserlib->verify_user(); //验证用户是否登录
			if(!$userinfo)
			{
				$this -> errorOutput(USENAME_NOLOGIN);  //用户未登录
			}
			/**
			 * 验证用户是否被设置为设置为黑名单
			 */
			
			$id = $userinfo['id'];      				//粉丝ID
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
			$this -> errorOutput(UNFOLLOW);  		//无法关注用户
		}
		
		$ids = implode(',' , $follow);  
		
		/**
   		 *  配置是否同时关注该用户的频道
   		 */
		global $gGlobalConfig;
		if($gGlobalConfig['follow_set'] && !$this->input['have'])
		{
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', 'create_more');
			$this->curl->addRequestData('uids', $ids);
			$this->curl->addRequestData('have',1);
			$ret = $this->curl->request('video/station_concern.php');
		}
		
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
   		 */
   		
   		$notifyIds = implode(',' , $notify_id);
   		$content = array('title' => '新粉丝' , 'page_link' => SNS_UCENTER . 'fans.php');  		
   		$type = 1;
   		
   		$this->curl->setSubmitType('post');
		$this->curl->addRequestData('a', 'send');
		$this->curl->addRequestData('user_id', $notifyIds);
		$this->curl->addRequestData('content', serialize($content));
		$this->curl->addRequestData('type', $type);
		$this->curl->request('users/notify.php');
				
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
}
$out = new createApi();
$out->create();
?>