<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: destroy.php 776 2010-12-17 05:47:15Z yuna $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');

class destroyApi extends BaseFrm
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
		
		$id = $userinfo['id'];      				//当前用户ID

		/**
		 * 当接收的是取消的黑名单ID(支持多ID)
		 */
		if($this->input['user_id'])
		{
			echo $ids = $this->input['user_id'];
	
			if(count(implode(',' , $ids)) > BATCH_FETCH_LIMIT)
			{
				$this -> errorOutput(OUTLIMIT); //输出超出删除黑名单数目	
			}				
			
			/**
			 * 删除黑名单
			 */

			echo $sql = "DELETE FROM " . DB_PREFIX . "member_block WHERE bmemberid IN($ids) AND member_id = " . $id;
			
			$this->db->query($sql);

			/**
			 * 获取删除黑名单的信息
			 */
			
			$sql = "SELECT 
					m.id  , m.email , m. username , m.username AS screen_name , 
					m.location , m.birthday , m.qq , m.mobile , m.msn , m.join_time , 
				    m.last_login , m.group_id , m.privacy  
				    FROM " . DB_PREFIX . "member AS m WHERE id IN($ids)";
			
			$this->setXmlNode('users' , 'user');
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$this->addItem($row);	
			}
		}

		/**
		 * 当接收的是用户昵称
		 */
		if($this->input['screen_name'])
		{
			$sql  = "SELECT 
					 m.id  , m.email , m. username , m.username AS screen_name , 
					 m.location , m.birthday , m.qq , m.mobile , m.msn , m.join_time , 
				     m.last_login , m.group_id , m.privacy FROM " . DB_PREFIX . "member AS m 
				     WHERE username = '" . $this->input['screen_name'] . "'";

			$data = $this->db->query_first($sql);
		
			/**
			 * 删除黑名单用户
			 */
			$sql  = "DELETE FROM " . DB_PREFIX . "member_block WHERE member_id = " . $id . " AND bmemberid = " . $data['id'];
			$this->db->query($sql);
			
			$this->setXmlNode('users' , 'user');
			$this->addItem($data);			
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