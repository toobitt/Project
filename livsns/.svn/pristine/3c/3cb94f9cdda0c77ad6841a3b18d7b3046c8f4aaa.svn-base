<?php
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_DIR.'global.php');
define('MOD_UNIQUEID','clear_win_info');//模块标识
class WinInfoClear extends cronBase
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
		if(!defined('CLEAR_WIN_INFO_TIME') || CLEAR_WIN_INFO_TIME <= 0)
		{
			return false;
		}
		
		$time = TIMENOW - (CLEAR_WIN_INFO_TIME * 24 * 3600);
		
		$sql = "DELETE FROM " .DB_PREFIX. "win_info WHERE  create_time < " . $time . " AND confirm = 0";
		
		if($this->settings['clear_un_win_info'])
		{
			$sql .= " OR create_time <" . $time . " AND prize_id = 0 ";
		}
		$this->db->query($sql);
		
		$this->addItem('success');
		$this->output();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,
			'name' => '清除未确认记录',
			'brief' => '默认清除未确认记录,可选择清除未中奖记录',
			'space' => '3600',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
}

$out = new WinInfoClear();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'run';
}
$out->$action();
?>