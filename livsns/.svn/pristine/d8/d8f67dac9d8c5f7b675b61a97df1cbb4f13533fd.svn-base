<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: show.php 1463 2010-12-31 02:28:14Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class showRelationApi extends BaseFrm
{	
	function __construct()
	{
		parent::__construct();		
	}
	
	function __destruct()
	{
		parent::__destruct();	
	}
	
	/**
	 * 功能：返回源用户与目标用户的关系
	 * 0: 目标用户已在源用户黑名单中
	 * 1：源用户和目标用户互相关注
	 * 2： 源用户关注了目标用户
	 * 3：目标用户关注了源用户
	 * 4：源用户和目标用户没有关系 
	 */
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
}

$out = new showRelationApi();
$out->show_relation();
?>