<?php
/**
 * 计划任务执行清楚n天之前的收录日志
 */
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_DIR.'global.php');
define('MOD_UNIQUEID','program_record_clear_log');//模块标识
class program_record_clear_log extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function run()
	{
		//如果没有设置清楚多少天之前的日志则不执行
		if(!defined('CLEAR_LOG_BEFORE_TIME') || CLEAR_LOG_BEFORE_TIME <= 0)
		{
			$this->errorOutput(CLEAR_LOG_BEFORE_TIME_NOT_SETTING);
		}
		
		//算出CLEAR_LOG_BEFORE_TIME天之前的临界时间点
		$time = TIMENOW - (CLEAR_LOG_BEFORE_TIME * 24 * 3600);
		
		//清楚CLEAR_LOG_BEFORE_TIME之前的日志
		$sql = "DELETE FROM " .DB_PREFIX. "program_record_log WHERE create_time <" . $time;
		$this->db->query($sql);
		
		$this->addItem('success');
		$this->output();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '清除收录日志',	 
			'brief' => '清除收录日志',
			'space' => '3600',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
}

$out = new program_record_clear_log();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'run';
}
$out->$action();
?>