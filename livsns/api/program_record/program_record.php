<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program.php 6082 2012-03-13 03:16:40Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . "global.php");
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','program_record');
class programRecordApi extends outerReadBase
{
	private $obj;
	function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/program_record.class.php');
		$this->obj = new programRecord();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	function show()
	{
		
	}

	public function count()
	{
		
	}

	public function detail()
	{
		
	}
	
	public function getRecordByChannel()
	{
		$channel_id = trim($this->input['channel_id']) ? trim($this->input['channel_id']) : 0;
		if(empty($channel_id))
			$this->errorOutput('请传入频道ID');
		$dates = trim($this->input['dates']) ? trim($this->input['dates']) : '';
		$condition = '';
		if(!empty($dates))
		{
			$condition .= " AND r.channel_id=" .  intval($channel_id) . " AND r.week_num=" . date('N',strtotime($dates));
		}
		else
		{
			$condition .= " AND r.channel_id IN (" . $channel_id . ")";			
		}
		
		$sql = "SELECT p.id,p.title,p.toff,p.week_day,r.start_time,r.end_time,r.channel_id,r.week_num,p.item FROM " . DB_PREFIX . "program_record_relation r LEFT JOIN " . DB_PREFIX . "program_record p ON p.id = r.record_id WHERE 1" . $condition;
		$q = $this->db->query($sql);

		$record = array();
		while($r = $this->db->fetch_array($q))
		{
			$this->addItem($r);
		}
		$this->output();
	}
	
	public function getRecordById()
	{
		$record_id = trim($this->input['record_id']) ? trim($this->input['record_id']) : 0;
		if(empty($record_id))
		{
			$this->errorOutput('请传入录制ID');
		}
		$sql = "SELECT p.id,p.title,p.toff,p.week_day,r.start_time,r.end_time,r.channel_id,r.week_num,p.item FROM " . DB_PREFIX . "program_record_relation r LEFT JOIN " . DB_PREFIX . "program_record p ON p.id = r.record_id WHERE 1 AND r.record_id IN(" . $record_id . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$this->addItem($row);
		}
		$this->output();
	}
	
	public function getRecordByProgramId()
	{
		$program_id = trim($this->input['program_id']) ? trim($this->input['program_id']) : 0;
		if(empty($program_id))
		{
			$this->errorOutput('请传入节目ID');
		}
		$sql = "SELECT p.id,p.title,p.toff,p.week_day,r.start_time,r.end_time,r.channel_id,r.week_num,p.item FROM " . DB_PREFIX . "program_record_relation r LEFT JOIN " . DB_PREFIX . "program_record p ON p.id = r.record_id WHERE 1 AND r.program_id =" . $program_id;
		$ret = $this->db->query_first($sql);
		$this->addItem($ret);
		$this->output();
	}

	public function getRecordByPlanId()
	{
		$plan_id = trim($this->input['plan_id']) ? trim($this->input['plan_id']) : 0;
		if(empty($plan_id))
		{
			$this->errorOutput('请传入节目计划ID');
		}
		$sql = "SELECT p.id,p.title,p.toff,p.week_day,r.start_time,r.end_time,r.channel_id,r.week_num,p.item FROM " . DB_PREFIX . "program_record_relation r LEFT JOIN " . DB_PREFIX . "program_record p ON p.id = r.record_id WHERE 1 AND r.plan_id=" . $plan_id;
		$ret = $this->db->query_first($sql);
		$this->addItem($ret);
		$this->output();
	}
	
	public function getRecord()
	{
		$condition = '';
		if(intval($this->input['plan_id']))
		{
			$condition .= ' AND p.plan_id=' . intval($this->input['plan_id']);
		}
		
		if(intval($this->input['program_id']))
		{
			$condition .= ' AND p.program_id=' . intval($this->input['program_id']);
		}
		
		$sql = "SELECT p.id,p.title,p.toff,p.week_day,r.start_time,r.end_time,r.channel_id,r.week_num,p.item FROM " . DB_PREFIX . "program_record_relation r LEFT JOIN " . DB_PREFIX . "program_record p ON p.id = r.record_id WHERE 1 " . $condition;
		$f = $this->db->query_first($sql);
		if(!empty($f))
		{
			$this->addItem($f);
			$this->output();
		}
	}
	
}

$out = new programRecordApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>