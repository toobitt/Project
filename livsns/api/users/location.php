<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: location.php 17947 2013-02-26 02:57:46Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class locationApi extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->user = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}	
	
	/**
	* 插入会员地理信息
	* @return array  该会员的地理信息
	*/	
	public function create()
	{
		$userinfo = $this->user->verify_credentials();
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$memberid = $userinfo['id']; 
		$group_id = $this->input['group_id'];
		if(!$group_id)
		{
			$this->errorOutput(INPUT_ERROR); 
		}
		$frequency = '频度';	
		$gname = urldecode($this->input['group_name']); 
		$glat = urldecode($this->input['glat']);
		$glng= urldecode($this->input['glng']);
		$query = $this->db->query_first("SELECT * FROM " . DB_PREFIX . "member_location WHERE member_id = " . $memberid);
		if(!empty($query))
		{
			$sql = "UPDATE ".DB_PREFIX."member_location 
					SET group_id='" . $group_id . "',
					frequency = frequency + 1,
					group_name = '". $gname ."',
					lat = '" . $glat . "',
					lng = '" . $glng . "'
					WHERE member_id = " . intval($memberid); 
		} 
	 	else
		{
			$sql = "INSERT INTO ".DB_PREFIX."member_location(
						member_id,
						group_name,
						frequency,
						lat,
						lng,
						group_id 
					)
					VALUES(
						" . $memberid . ",
						'" . $gname . "',
						0,
						'" . $glat . "',
						'" . $glng . "',
						'" . $group_id . "'
					)
					";
		} 
 		 
	  	$queryid = $this->db->query($sql); 
		$insert_id = $this->db->insert_id();
		$this->setXmlNode('Locations','Location');		
		$locationinfo = array(
				'id' => $insert_id, 
		);
		$this->addItem($locationinfo);  
		$this->output();
		 
		 
		 
	}
	
	//获取用户的默认位置
	public function getLocation()
	{  

		$userinfo = $this->user->verify_credentials();
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$user_id = intval($user_id) > 0 ? intval($user_id) : $userinfo['id'];
		if (!$user_id)
		{
			$this->errorOutput(INPUT_ERROR);//返回0x4100代码
		}
		$this->setXmlNode('Locations','Location');		
		$userlocation = array();
		$userlocation = $this->db->query_first("SELECT lat,lng FROM " . DB_PREFIX . "member_location WHERE member_id = " . $user_id);
	 	$this->addItem($userlocation);
		$this->output();
		 
	}
	
	//删除用户标注的位置
	public function delLocation()
	{
		$userinfo = $this->user->verify_credentials();
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$user_id = intval($this->input['user_id']);
		
		if (!$user_id)
		{
			$this->errorOutput(INPUT_ERROR);//返回0x4100代码
		}
		
		$location = $this->input['location'];
		
		$sql_str = 'SELECT id FROM ' . DB_PREFIX . 'member_location WHERE member_id = ' .$user_id .' AND location = "' . $location . '"';
		$first = $this->db->query_first($sql_str);
		
		if(!$first)
		{
			$this->errorOutput(OBJECT_NULL);//该条记录不存在
		} 
		else
		{
			$sql = 'DELETE FROM ' . DB_PREFIX . 'member_location WHERE member_id = ' . $user_id .'  AND location = "' . $location . '"';
			$this->db->query($sql);
			
			$this->addItem($first);
			$this->output();
		}
			
	}
}
$out = new locationApi(); 
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	 $action = 'unknow';
}
$out->$action();
?>