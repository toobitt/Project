<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: verify.php 776 2010-12-17 05:47:15Z yuna $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');

/**
 * 
 * 审核用户
 * 审核成功返回true 失败返回false
 *
 */
class verifyApi extends BaseFrm
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
	
	public function verify()
	{
		/*
		 * 验证用户是否登录
		 */
		$userinfo = $this->mUserlib->verify_user(); //验证用户是否登录
		if(!$userinfo)
		{
			$this -> errorOutput(USENAME_NOLOGIN);  //用户未登录
		}
		
		$id = $userinfo['id'];      				//被关注者ID	
		
		/**
		 * 当接收的是关注ID(支持多ID)
		 * 
		 */
		if($this->input['user_id'])
		{
			$ids = $this->input['user_id'];		
			if(is_array($ids))
			{
				$ids  = implode(',' , $ids);	
				if(count($this->input['user_id']) > BATCH_FETCH_LIMIT)
				{
					$this -> errorOutput(OUTLIMIT); //输出超出审核数目	
				}				
			}

			/**
			 * 删除审核关系数据
			 */
			$sql = "DELETE FROM " . DB_PREFIX . "member_relation_verify WHERE member_id IN($ids) AND fmember_id = " . $id;			
			$this->db->query($sql);
			
			/**
			 * 更新关注数据
			 */
			$this->ConnectQueue();
			if(!is_array($ids))
			{
				$time = time();
				$sql = "INSERT INTO " . DB_PREFIX . "member_relation VALUES($ids , $id , $time)";
				$this->db->query($sql);
				$add_relation = '1 ,' . $ids . ',' . $id;
				$this->queue->set(FOLLOW_QUEUE , $add_relation);	
			}
			else
			{
				$time = time();
				foreach($this->input['user_id'] as $v)
				{
					$sql = "INSERT INTO " . DB_PREFIX . "member_relation VALUES($v , $id , $time)";
					$this->db->query($sql);
					$add_relation = '1 ,' . $v . ',' . $id;
					$this->queue->set(FOLLOW_QUEUE , $add_relation);
				}				
			}			
		}
		
		/**
		 * 当接收的是关注名(不支持多用户名)
		 */
		if($this->input['screen_name'])
		{
			$sql = "SELECT id FROM " . DB_PREFIX . "member WHERE username = '" . $this->input['screen_name'] . "'";		
			$data = $this->db->query_first($sql);
			
			/**
			 * 删除审核关系数据
			 */
			$sql = "DELETE FROM " . DB_PREFIX . "member_relation_verify WHERE member_id = " . $data['id'] . " AND fmember_id = " . $id;
			$this->db->query($sql);
			
			/**
			 * 更新关注数据
			 */
			$time = time();
			$sql = "INSERT INTO " . DB_PREFIX . "member_relation VALUES({$data['id']} , $id , $time)";
			$this->db->query($sql);
			
			/**
			 * 更新后的关注关系进入队列
			 */
			$this->ConnectQueue();
			$add_relation = '1 ,' . $data['id'] . ',' . $id;
			$this->queue->set(FOLLOW_QUEUE , $add_relation);
		}
	}	
}

$out = new verifyApi();
$out->verify();
?>