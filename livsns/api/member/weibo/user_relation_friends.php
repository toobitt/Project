<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user_relation_friends.php 776 2010-12-17 05:47:15Z yuna $
***************************************************************************/
require('global.php');
class getRelationFriendsApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
						
		$this->mUserlib = new user();
	}
	
	function __destruct()
	{
		parent::__destruct();	
	}
	
	public function get_relation()
	{
		$user_id = $this->input['user_id'];      			//接收当前用户ID	
		$relation = urldecode($this->input['ids']);         //接收需要判断用户关系的IDS
		
		$ids = explode(',' , $relation);
		
		$sql = "SELECT member_id FROM " . DB_PREFIX . "member_relation WHERE fmember_id = " . $user_id ;
		
		$q = $this->db->query($sql);
		
		$followers = array();
		while($row = $this->db->fetch_array($q))
		{
			$followers[] = $row['member_id'];	
		}
		
		$followers_ids = array_intersect($followers , $ids);
		
		$relation = array();
		foreach($ids as $k => $v)
		{
			if(in_array($v , $followers_ids))
			{
				$relation[$v] = 1;	
			}
			else
			{
				$relation[$v] = 0;	
			} 	
		}
		
		foreach ($relation AS $item)
		{
			$this->addItem($item);
		}
		
		$this->output();
	}
}

$out = new getRelationFriendsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'get_relation';
}
$out->$action();
?>