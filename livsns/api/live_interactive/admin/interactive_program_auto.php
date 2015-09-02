<?php
/***************************************************************************
* $Id: interactive_program_auto.php 16549 2013-01-09 08:39:36Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','interactive_program_auto');
require('global.php');
class interactiveProgramAutoApi extends BaseFrm
{
	private $mInteractiveMember;
	public function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/interactive_member.class.php';
		$this->mInteractiveMember = new interactiveMember();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function get_interactive_program()
	{
		$sql = "SELECT t1.*, t2.member_id, t2.member_id AS member_id, t3.member_id AS t3_member_id, t3.member_name, t3.nick_name , t3.plat_id, t3.plat_type, t3.plat_name, t3.plat_token, t3.plat_since_id FROM " . DB_PREFIX . "program t1 ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "weibo_member t2 ON t1.id = t2.program_id ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "interactive_member t3 ON t3.id = t2.member_id ";
		$sql.= " WHERE t1.dates = '" . date('Y-m-d') . "' ";
		$sql.= " AND t1.start_time <= " . TIMENOW . " AND t1.start_time + t1.toff >= " . TIMENOW;
		$sql.= " AND t3.status = 1 AND t3.plat_expired_time >= " . TIMENOW;
		
		$q = $this->db->query($sql);
		$program_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['plat_since_id']	= $row['plat_since_id'] ? unserialize($row['plat_since_id']) : array();
			$program_info[] = $row;
		}
		
		if (empty($program_info))
		{
			$this->errorOutput('当前时间未设置信息或已被删除');
		}
		
		if ($this->input['debug'])
		{
			hg_pre($program_info);
		}
		
		foreach ($program_info AS $program)
		{
			if ($program['member_id'])
			{
				$data = array(
					'program_id'	=> $program['id'],
					'channel_id' 	=> $program['channel_id'],
					'member_id' 	=> $program['t3_member_id'],
					'member_name' 	=> $program['member_name'],
					'nick_name' 	=> $program['nick_name'],
					'plat_id'		=> $program['plat_id'],
					'plat_type' 	=> $program['plat_type'],
					'plat_name' 	=> $program['plat_name'],
					'plat_token' 	=> $program['plat_token'],
					'plat_since_id' => $program['plat_since_id'][$program['channel_id']] ? $program['plat_since_id'][$program['channel_id']] : 0,//$program['plat_since_id'],
					'start_time' 	=> $program['start_time'],
					'end_time'		=> $program['start_time'] + $program['toff'],
					'create_time'	=> TIMENOW,
				);
		
				$this->mInteractiveMember->member_queue_add($data);
			}
		}
		
		$this->addItem('success');
		$this->output();
	}
}

$out = new interactiveProgramAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'get_interactive_program';
}
$out->$action();
?>