<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
define('MOD_UNIQUEID','member_medal');//模块标识
define('SCRIPT_NAME', 'medal_cron');
require('./global.php');
require CUR_CONF_PATH . 'lib/member_medal.class.php';
class medal_cron extends cronBase
{
	function __construct()
	{
		parent::__construct();

	}
	function __destruct()
	{
		parent::__destruct();
	}
	function initcron()
	{
		$array = array(
			'mod_uniqueid' 	=> 'member_medal',	 
			'name' 			=> '更新用户勋章',	 
			'brief' 		=> '清除过期会员勋章',
			'space'			=> '86400',	//运行时间间隔，单位秒
			'is_use' 		=> 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	function show()
	{
		$membersql = new membersql();
		$member_medal = new medal();
		$medalnews  = array();
		$sql='SELECT * FROM '.DB_PREFIX.'medallog WHERE status=1 AND expiration>0 AND expiration<'.TIMENOW;
		$query=$this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$medalnews[] = $row;
		}
		foreach($medalnews as $medalnew) 
		{
			$membersql->update('medallog',array('status' => 0), array('id'=>$medalnew['id']));
			$membersql->delete('member_medal', array('member_id'=>$medalnew['member_id'],'medalid'=>$medalnew['medalid']));
			$member_medal->update_used_num(array($medalnew['medalid']),'-');
		}
		$ret="更新了已经过期会员勋章";
		$this->addItem($ret);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>