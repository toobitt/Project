<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 5128 2011-11-23 02:53:21Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','channel_change_plan');
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
class channelChgPlan extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function show()
	{
		//最多提前10秒取出切播计划
		$sql = "SELECT ccp.id, ccp.channel_id, ccp.stream_uri, ccp.type, ccp.channel2_id, (ccp.change_time + ccp.toff) AS resume_time, c.chg_id FROM " . DB_PREFIX . "channel_chg_plan ccp 
				LEFT JOIN " . DB_PREFIX . "channel c
					ON ccp.channel_id = c.id
				WHERE c.id > 0 AND ccp.is_exec=0 AND ccp.change_time <= " . (TIMENOW - 10);
		$q = $this->db->query($sql);
		include(CUR_CONF_PATH . 'lib/channel_change.class.php');
		$channel_changes = new ChannelChange();
		$chgids = array();
		while ($row = $this->db->fetch_array($q))
		{
			$ret_name = $channel_changes->channel_emergency_change($row['chg_id'], 'file', $row['stream_uri']);
			$chgids[] = $row['id'];
			if ($row['type'] == 3 || $row['type'] == 1)
			{
				$chg_type = array(3 => 'stream', 1 => 'file');
				$sql = "UPDATE " . DB_PREFIX . "channel SET chg2_stream_id={$row['channel2_id']}, chg_type='{$chg_type[$row['type']]}' WHERE id=" . $row['channel_id'];
				$this->db->query($sql);
			}
			$sql = 'REPLACE INTO ' . DB_PREFIX . "channel_change (channel_id, resume_time) VALUES ({$row['channel_id']}, {$row['resume_time']})";
			$this->db->query($sql);
			$this->addItem($row);
		}
		if ($chgids)
		{
			$sql = 'UPDATE ' . DB_PREFIX . 'channel_chg_plan SET is_exec=1 WHERE id IN (' . implode(',', $chgids) . ')';
			$this->db->query($sql);
		}
		$sql = "SELECT cc.channel_id, c.chg_id FROM " . DB_PREFIX . "channel_change cc 
				LEFT JOIN " . DB_PREFIX . "channel c
					ON cc.channel_id = c.id
				WHERE c.id > 0 AND cc.resume_time <= " . (TIMENOW - 5);
		$q = $this->db->query($sql);
		$chgids = array();
		while ($row = $this->db->fetch_array($q))
		{
			$ret_name = $channel_changes->channel_resume($row['chg_id']);
			$chgids[] = $row['channel_id'];
			if ($row['type'] == 3 || $row['type'] == 1)
			{
				$sql = "UPDATE " . DB_PREFIX . "channel SET chg2_stream_id=0 WHERE id=" . $row['channel_id'];
				$this->db->query($sql);
			}
			$this->addItem($row);
		}
		if ($chgids)
		{
			$sql = 'UPDATE ' . DB_PREFIX . 'channel_change SET is_exec=1 WHERE channel_id IN (' . implode(',', $chgids) . ')';
			$this->db->query($sql);
		}
		$this->output();
	}

	public function count()
	{
		
	}
	public function detail()
	{
		
	}

}

$out = new channelChgPlan();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>