<?php
/***************************************************************************
* $Id: interactive_member_auto.php 14744 2012-11-29 09:23:36Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','interactive_auto');
require('global.php');
class interactiveMemberAutoApi extends BaseFrm
{
	private $mInteractive;
	private $mInteractiveMember;
	private $mShare;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/interactive.class.php';
		$this->mInteractive = new interactive();
		
		require_once CUR_CONF_PATH . 'lib/interactive_member.class.php';
		$this->mInteractiveMember = new interactiveMember();
		
		require_once ROOT_PATH . 'lib/class/share.class.php';
		$this->mShare = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function get_interactive_member()
	{
		$date		= date('Y-m-d', TIMENOW);
		$condition  = " AND status = 1 ";
		$condition .= " AND imr.week_num=" . date('N',TIMENOW);
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count	 	= $this->input['count'] ? intval($this->input['count']) : 50;
		
		$sql = "SELECT im.*, imr.*, im.channel_id AS channel_id FROM " . DB_PREFIX . "interactive_member im ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "interactive_member_relation imr ON im.id=imr.m_id ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		
		$q = $this->db->query($sql);
		
		$memebr_info = array();
		
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			$row['week_day']	= $row['week_day'] ? unserialize($row['week_day']) : array();	
			$row['channel_id']	= $row['channel_id'] ? unserialize($row['channel_id']) : array();	
			
			$row['start_time']  = strtotime($date . ' ' . $row['start_time']);
			$row['end_time']    = $row['start_time'] + $row['toff'];
			
			$row['start'] 		= date('Y-m-d H:i:s', $row['start_time']);
			$row['end'] 		= date('Y-m-d H:i:s', $row['end_time']);
			
			$memebr_info[$row['id']] = $row;
		}
	//	hg_pre($memebr_info);
		
		if (!empty($memebr_info))
		{
			foreach ($memebr_info AS $member)
			{
				$data = array(
					'm_id'			=> $member['id'],
					'channel_id' 	=> $member['channel_id'] ? serialize($member['channel_id']) : '',
					'member_id' 	=> $member['member_id'],
					'member_name' 	=> $member['member_name'],
					'nick_name' 	=> $member['nick_name'],
					'plat_id'		=> $member['plat_id'],
					'plat_type' 	=> $member['plat_type'],
					'plat_name' 	=> $member['plat_name'],
					'plat_token' 	=> $member['plat_token'],
					'plat_since_id' => $member['plat_since_id'],
					'start_time' 	=> $member['start_time'],
					'end_time'		=> $member['end_time'],
				);
		
				$this->mInteractiveMember->member_queue_add($data);
			}
		}
		
		$this->addItem($memebr_info);
		$this->output();
	}
	
}

$out = new interactiveMemberAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'get_interactive_member';
}
$out->$action();
?>