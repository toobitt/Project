<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
define('MOD_UNIQUEID','member_group');//模块标识
define('SCRIPT_NAME', 'group');
require('./global.php');
define('TIMESTART',strtotime(date("Y-m-d"))+86400);
class group extends cronBase
{
	function __construct()
	{
		parent::__construct();
		$this->Members = new members();

	}
	function __destruct()
	{
		parent::__destruct();
	}
	function initcron()
	{
		$array = array(
			'mod_uniqueid' 	=> 'member_group',	 
			'name' 			=> '更新用户组',	 
			'brief' 		=> '更新拥有有效期的用户组',
			'space'			=> '86400',	//运行时间间隔，单位秒
			'is_use' 		=> 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	function show()
	{
		$sql='SELECT member_id,credits FROM '.DB_PREFIX.'member WHERE groupexpiry != 0 AND groupexpiry <='.TIMESTART;
		$query=$this->db->query($sql);
		while ($row=$this->db->fetch_array($query))
		{
			$newgroup=$this->Members->checkgroup_credits($row['credits']);
			if($newgroup)
			{
				$this->Members->updategroup_id($row['member_id'], $newgroup['gid'], $groupexpiry=0);
			}
		}
		$ret="更新了已经过期的用户组";
		$this->addItem($ret);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>