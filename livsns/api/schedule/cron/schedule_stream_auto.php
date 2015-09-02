<?php
/***************************************************************************
* $Id: schedule_stream_auto.php 31969 2013-11-26 11:09:11Z tong $
***************************************************************************/
define('MOD_UNIQUEID','schedule_stream_auto');
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR.'global.php');
require(CUR_CONF_PATH."lib/functions.php");
class scheduleStreamAutoApi extends cronBase
{
	private $mLivemms;
	private $mLive;
	private $mSchedule;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '删除串联单已过期的备播信号',	 
			'brief' => '删除已过期的串联单临时创建的备播信号',
			'space' => '86400',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	/**
	 * 删除已过期的串联单临时创建的备播信号
	 * Enter description here ...
	 */
	public function schedule_stream_auto()
	{
		$dates = date('Y-m-d');
		
		$sql  = "SELECT id, channel_id, change2_id, change2_name, input_id FROM " . DB_PREFIX . "schedule ";
		$sql .= " WHERE (start_time+toff) < " . time();
		$sql .= " AND type = 1  AND input_id > 0 ";
		
		$q = $this->db->query($sql);
		
		$server_info = $this->settings['server_info'];
		$host 		 = $server_info['host'];
		$input_dir 	 = $server_info['input_dir'];
		while ($row = $this->db->fetch_array($q))
		{
			$input_data = array(
				'action'	=> 'delete',
				'id'		=> $row['input_id'],
			);
			
			$ret_delete = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
			if ($ret_delete['result'])
			{
				$sql = 'UPDATE ' . DB_PREFIX . "schedule SET input_id=0 WHERE id=" . $row['id'];
				$this->db->query($sql);
			}
			$this->addItem($ret_delete);
		}
		
		$this->output();
	}
}

$out = new scheduleStreamAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'schedule_stream_auto';
}
$out->$action();
?>