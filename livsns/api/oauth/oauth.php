<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: oauth.php 3778 2011-04-22 09:19:00Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');
require(ROOT_DIR . '/lib/class/credit.class.php');

class oauthApi extends BaseFrm
{
	private $userinfo;
	private $mCredit;
	
	function __construct()
	{
		parent::__construct();
		$this->mUserlib = new user();
		$this->mCredit = new credit();
		
		$userinfo = $this->mUserlib->verify_user();  //验证用户是否登录				
		if(!$userinfo)
		{
			$this -> errorOutput(USENAME_NOLOGIN);   //用户未登录
		}
		else
		{
			$this->userinfo = $userinfo;
		} 						
	}
	
	function __destruct()
	{
		parent::__destruct();	
	}
	
	/**
	 * 设置绑定信息
	 */
	public function set_bind_info()
	{		
		$type = $this->input['type'];
		$state = $this->input['state'];
		$time = time();	
		$sql = "UPDATE "  . DB_PREFIX . "member_config SET type = " . $type . ", state = " . $state . ", time =  " . $time . " WHERE user_id = " . $this->userinfo['id'];		
		$this->db->query($sql);
	}
	
	/**
	 * 保存key值
	 */
	public function update()
	{		
		$type = $this->input['type'];
		$state = $this->input['state'];
		$is_bind = $this->input['is_bind'];
		$key = urldecode($this->input['key']);
		$uid = urldecode($this->input['uid']);
		$time = time();
		
		$sql = "INSERT INTO "  . DB_PREFIX . "member_config SET is_bind = " . $is_bind . ", last_key = '" . $key . "' , uid= " . $uid . ", type = " . $type . ", state = " . $state . ", time =  " . $time . ", user_id = " . $this->userinfo['id'];
		$this->db->query($sql);		
	}
	
	
	/**
	 * 获取绑定信息
	 */
	public function get_bind_info()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "member_config WHERE user_id = " . $this->userinfo['id'];		
		$r = $this->db->query_first($sql);
		
		if(empty($r))
		{
			echo 0;
		}
		else
		{
			$this->setXmlNode('bind_info' , 'user');		
			$this->addItem($r);
			$this->output();
		} 				
	}
	
	public function is_bind()
	{		
		$type = $this->input['type'];
		
		$sql = "SELECT * FROM " . DB_PREFIX . "member_config WHERE user_id = " . $this->userinfo['id'] . " AND type = " . $type;		
		$r = $this->db->query_first($sql);
		
		if(empty($r))
		{
			echo 0;
		}
		else
		{
			$this->setXmlNode('bind_info' , 'user');		
			$this->addItem($r);
			$this->output();
		} 		
	}
	
	/**
	 * 解除绑定
	 */
	public function destroy()
	{
		$sql = "DELETE FROM " . DB_PREFIX . "member_config WHERE user_id = " . $this->userinfo['id'];
		$this->db->query($sql);
	}
	
	/**
	 * 添加绑定
	 */
	public function add_bind()
	{
		/**
		 * 添加绑定积分
		 */
		$this->mCredit->add_credit_log(BIND_STATUS);
				
		$sql = "UPDATE " . DB_PREFIX . "member_config SET is_bind=1 WHERE user_id = " . $this->userinfo['id'];
		$this->db->query($sql);
	}
	
	
	/**
	 * 同步点滴
	 */
	public function syn()
	{
		$state = $this->input['state'];
		$sql = "UPDATE " . DB_PREFIX . "member_config SET state= " . $state . "  WHERE user_id = " . $this->userinfo['id'];
		$this->db->query($sql);	
	}
	
	/**
	 * 检测多账号重复绑定
	 */
	public function check()
	{
		$type = $this->input['type'];
		$uid = urldecode($this->input['uid']);
		$sql = "SELECT uid FROM " . DB_PREFIX . "member_config WHERE uid='" . $uid . "' AND type=1";
		
		$q = $this->db->query($sql);
		$nums = $this->db->num_rows($q);
		
		if($nums > 0)
		{
			echo 0;
		}
		else
		{
			echo 1;
		}
	}
	
	
	/**
	 * 
	 * 检测这条点滴是否同步
	 */
	/*public function check_syn_status()
	{
		$status_id = intval($this->input['status_id']);
		$type = intval($this->input['type']);
		$sql = "SELECT * FROM " . DB_PREFIX . "member_syn_relation WHERE status_id = " . $status_id . " AND type = " . $type;
		$r = $this->db->query_first($sql);
		if(count($r)  == 0)
		{
			echo 0;
		}
		else
		{
			echo json_encode($r);
		}
	}*/
	
		
	public function show()
	{
		$fun_name = $this->input['func'];
		$this->$fun_name();				
	}	
}

$out = new oauthApi();
$out->show();
?>