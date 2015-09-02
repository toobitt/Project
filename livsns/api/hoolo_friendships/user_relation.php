<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user_relation.php 776 2010-12-17 05:47:15Z yuna $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');

class getRelationApi extends BaseFrm
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
	
	public function get_relation()
	{
		$user_id = $this->input['user_id'];      			//接收当前用户ID
		
		$relation = urldecode($this->input['ids']);         //接收需要判断用户关系的IDS

		$ids = explode(',' , $relation);
				
		$sql = "SELECT fmember_id FROM " . DB_PREFIX . "member_relation WHERE member_id = " . $user_id ;
		
		$q = $this->db->query($sql);
		
		$friends = array();
		while($row = $this->db->fetch_array($q))
		{
			$friends[] = $row['fmember_id'];	
		}
		
		$friends_ids = array_intersect($friends , $ids);
		
		$relation = array();
		foreach($ids as $k => $v)
		{
			if(in_array($v , $friends_ids))
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

$out = new getRelationApi();
$out->get_relation();
?>