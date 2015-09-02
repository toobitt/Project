<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: blocking.php 4016 2011-05-30 06:32:19Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');

class getblockInfoApi extends BaseFrm
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
	
	public function show()
	{

		$userinfo = $this->mUserlib->verify_user();   //验证用户是否登录
				
		if(!$userinfo)
		{
			$this -> errorOutput(USENAME_NOLOGIN);    //用户未登录
		}
		

      	$id = intval($this->input['user_id']);      		  //当前用户ID

		$id = intval($this->input['user_id']);        //当前用户ID

		
		/**
		 * 
		 * 用于分页请求，请求第1页cursor传1，在返回的结果中会得到next_cursor字段，表示下一页的cursor。
		 * next_cursor为0表示已经到记录末尾。 
		 */
		$start_page = $this->input['cursor'] ? $this->input['cursor'] : 1;		
		$start = ($start_page - 1) * BATCH_FETCH_LIMIT;
		
		/**
		 * 查看当前登录用户黑名单信息
		 */
		$sql = "SELECT 
				m.id  ,m.avatar, m.email , m. username , m.username AS screen_name , 
				m.location , m.birthday , m.qq , m.mobile , m.msn , m.join_time , 
				m.last_login , m.group_id , m.privacy   
				FROM " . DB_PREFIX . "member_block AS b 
				LEFT JOIN " . DB_PREFIX . "member m 
				ON b.bmemberid = m.id WHERE b.member_id = " . $id . " LIMIT " . $start . ", " . BATCH_FETCH_LIMIT;
		$q = $this->db->query($sql);
		
		$this->setXmlNode('users_info' , 'user');

		if($this->db->num_rows($q) == 0)
		{
			$this -> errorOutput(NOBLACKLIST);      //用户没有黑名单	
		}
		else
		{
			while($row = $this->db->fetch_array($q))
			{
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
		} 
						
		$this->output();		
	}	
}
$out = new getblockInfoApi();
$out->show();
?>