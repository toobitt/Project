<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: create.php 776 2010-12-17 05:47:15Z yuna $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');

class createApi extends BaseFrm
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
	
	public function create()
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
		 * 当接收的是添加黑名单ID(支持多ID)
		 */
		if($this->input['user_id'])
		{
			$bolck = array();
			$ids = $this->input['user_id'];
			if(is_array($ids))
			{
				$bolck = $ids;	
				if(count($bolck) > BATCH_FETCH_LIMIT)
				{
					$this -> errorOutput(OUTLIMIT); //输出超出天加黑名单数目	
				}				
			}
			else
			{
				$block[] = $ids; 	
			}
			
			$time = time();
			foreach($block as $v)
			{
				$data[] = "($id , $v , $time)";
			}
						
			$insert_data = implode(',' , $data);			
			$sql = "INSERT INTO " . DB_PREFIX . "member_block VALUES" . $insert_data;		
			$this->db->query($sql);
			
			$ids  = implode(',' , $block);
			
			$sql = "SELECT 
					m.id  , m.email , m. username , m.username AS screen_name , 
					m.location , m.birthday , m.qq , m.mobile , m.msn , m.join_time , 
				    m.last_login , m.group_id , m.privacy  
				    FROM " . DB_PREFIX . "member AS m 
				    WHERE id IN($ids)";
			
			$q = $this->db->query($sql);
			$this->setXmlNode('users' , 'user');
			while($row = $this->db->fetch_array($q))
			{
				$this->addItem($row);
			}			
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