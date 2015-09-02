<?php
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require_once(CUR_CONF_PATH.'global.php');
define('MOD_UNIQUEID','stock_lock');//模块标识
class StockLock extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,
			'name' => '计划任务同步会员积分',
			'brief' => '计划任务同步会员积分',
			'space' => '60',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function run()
	{
		
		$lock_time = intval($this->settings['lock_time']) ? intval($this->settings['lock_time']) : 120;
		
		
		$cond = TIMENOW - $lock_time;
		$res = array();
		$sql = "SELECT * FROM " . DB_PREFIX . "stock_lock WHERE status = 0 AND create_time < " . $cond . "  ORDER BY create_time ASC LIMIT 0,10";
		$q = $this->db->query($sql);
		
		while ($res = $this->db->fetch_array($q))
		{
			if(!$res['prize_id'] || !$res['send_no'])
			{
				continue;
			}
			$win_info = '';
			$sql = "SELECT prize_id,lottery_id FROM " . DB_PREFIX . "win_info WHERE sendno = '{$res['send_no']}' AND confirm = 1";
			$win_info = $this->db->query_first($sql);
			
			
			//当中奖信息确认中奖,删除记录
			if($win_info)
			{
				//删除库存记录
				$sql = "UPDATE " . DB_PREFIX . "stock_lock SET status=1 WHERE send_no = '" . $res['send_no'] . "'";
				$this->db->query($sql);
				continue;
			}
			
			
			//查询中奖记录中奖品个数
			$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "win_info WHERE prize_id = {$res['prize_id']}";
			$win_total = $this->db->query_first($sql);
			$win_total = $win_total['total'] ? $win_total['total'] : 0;
			
			//查询奖品中出个数
			$sql = "SELECT prize_win FROM " . DB_PREFIX . "prize WHERE id = {$res['prize_id']}";
			$prize_win = $this->db->query_first($sql);
			$prize_win = $prize_win['prize_win'] ? $prize_win['prize_win'] : 0;
			
			if($win_total == $prize_win)
			{
				//删除库存记录
				$sql = "UPDATE " . DB_PREFIX . "stock_lock SET status=1 WHERE send_no = '" . $res['send_no'] . "'";
				$this->db->query($sql);
				continue;
			}
			//实际中奖数大于等于奖品中出数
			if($win_total > $prize_win)
			{
				$sql = "UPDATE " . DB_PREFIX . "prize SET prize_win = {$win_total} WHERE id = " . $res['prize_id'];
				$this->db->query($sql);
				
				//删除库存记录
				$sql = "UPDATE " . DB_PREFIX . "stock_lock SET status=1 WHERE send_no = '" . $res['send_no'] . "'";
				$this->db->query($sql);
				continue;
			}
			
			//更新库存
			$sql = "UPDATE " . DB_PREFIX . "prize SET prize_win = prize_win - 1 WHERE id = " . $res['prize_id'] . " AND prize_win > 0";
			$this->db->query($sql);
			
			
			$sql = "UPDATE " . DB_PREFIX . "stock_lock SET status=1 WHERE send_no = '" . $res['send_no'] . "'";
			$this->db->query($sql);
			
			
			//删除中奖记录
			$sql = "DELETE FROM " . DB_PREFIX . "win_info WHERE sendno = '" . $res['send_no'] . "'";
			$this->db->query($sql);
			
		}
	}
}

$out = new StockLock();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'run';
}
$out->$action();
?>