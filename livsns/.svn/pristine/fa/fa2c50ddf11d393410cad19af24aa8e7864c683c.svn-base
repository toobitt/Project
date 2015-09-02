<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: move.php 1638 2011-01-08 09:20:24Z chengqing $
***************************************************************************/

/**
 *  
 * 删除用户关注接口
 * 返回删除用户的信息
 */
require('global.php');
class showApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show_relation()
	{		
		$source_id = intval($this->input['source_id']);   //源用户UID
				
		$target_id = intval($this->input['target_id']);   //目标用户UID
		
		$sql = "SELECT * FROM " . DB_PREFIX . "member_block WHERE member_id = " . $source_id . " AND bmemberid = " . $target_id;
		
		$r = $this->db->query($sql);
		
		if($this->db->num_rows($r) == 1)
		{
			echo 0;
			return;
		}

		if($source_id == $target_id)
		{
			echo 4;
		}
		else
		{
			$sql = "SELECT count(*) as r1 
					FROM " . DB_PREFIX . "member_relation 
					WHERE member_id = " . $source_id . " AND fmember_id = " . $target_id;			
			$r1 = $this->db->query_first($sql);
			
			$sql = "SELECT count(*) as r2 
					FROM " . DB_PREFIX . "member_relation 
					WHERE fmember_id = " . $source_id . " AND member_id = " . $target_id;
			$r2 = $this->db->query_first($sql);
			
			if($r1['r1'] == 1 && $r2['r2'] == 1)   
			{
				echo  1;
			}
			
			if($r1['r1'] == 1 && $r2['r2'] == 0)
			{
				echo  2;
			}

			if($r1['r1'] == 0 && $r2['r2'] == 1)
			{
				echo  3;
			}
			
			if($r1['r1'] == 0 && $r2['r2'] == 0)
			{
				echo  4;
			}			
		} 		
	}
	
	/**
	 * 获取会员
	 */
	public function getVip()
	{
		$page = $this->input['page'] ? intval($this->input['page']) : 0;
				
		if(!$this->input['count'])
		{
			$this->input['count'] =  6;
		}
		$user_id = $this->input['user_id'] ? $this->input['user_id'] : ($this->user['user_id'] ? $this->user['user_id'] : 0);
		if($user_id)
		{
			$con = ' AND id NOT IN(' . $user_id . ')';
		}
		$count = intval($this->input['count']);
		
		$totle = $this->input['total'] ? intval($this->input['total']) : 0;
				
		if($page >= $totle)
		{
			$page = 0;
		}
		
		$offset = $page * $count;
				
		$sql = "SELECT COUNT(*) AS nums FROM " . DB_PREFIX . "member AS m LEFT JOIN " . DB_PREFIX . "member_extra AS e ON m.id = e.member_id WHERE 1 " . $con;
		$r = $this->db->query_first($sql);
		
		$total = $r['nums'];
			
		$conditon  = " LIMIT " . $offset . ' , ' . $count;
		
		$sql = "SELECT m.id , m.member_name as username, m.host, m.dir, m.filepath, m.filename, e.* FROM " . DB_PREFIX . "member AS m LEFT JOIN " . DB_PREFIX . "member_extra AS e ON m.id = e.member_id WHERE 1 " . $con . " ORDER BY create_time DESC " . $conditon;
		
		$q = $this->db->query($sql);
		
		$this->setXmlNode('vip_info' , 'vip');
		
		while($row = $this->db->fetch_array($q))
		{
			$this->addItem($row);	
		}
		
		$this->addItem($total);
		
		$this->output();		
	}
}

$out = new showApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show_relation';
}
$out->$action();
?>