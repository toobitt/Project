<?php
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require_once(CUR_CONF_PATH.'global.php');
define('MOD_UNIQUEID','sync_credits');//模块标识
class SyncCredits extends cronBase
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
		if($this->settings['sync_switch'] || !$this->settings['App_members'])
		{
			return false;
		}
		
		//查询中奖信息中失败记录
		/*$sql = "SELECT member_id,prize_name,lottery_id FROM " . DB_PREFIX . "win_info WHERE lottery_id = 10 AND prize_type=1 AND prize_name!=''";
		$q = $this->db->query($sql);
		
		while ($r = $this->db->fetch_array($q))
		{
			//同步失败,记录失败记录
			$sync_fail = array(
				'user_id'		=> $r['member_id'],
				'credits'		=> $r['prize_name'],
				'lottery_id'	=> $r['lottery_id'],
				'create_time'	=> TIMENOW,
				'update_time'	=> TIMENOW,
			);
			
			$sql = '';
			$sql = " INSERT INTO " . DB_PREFIX . "sync_fail SET ";
			foreach ($sync_fail AS $k => $v)
			{
				$sql .= " {$k} = '{$v}',";
			}
			$sql = trim($sql,',');
			
			$this->db->query($sql);
		}
		exit();*/
		
		
		$res = array();
		$sql = "SELECT * FROM " . DB_PREFIX . "sync_fail WHERE status = 0 ORDER BY update_time ASC LIMIT 0,1";
		$res = $this->db->query_first($sql);
		
		
		if(empty($res) || !$res['credits'] || !$res['user_id'])
		{
			return false;
		}
		
		include_once ROOT_PATH . 'lib/class/members.class.php';
		$mem_obj = new members();
		//同步会员积分
		$credit_type = $mem_obj->get_trans_credits_type();
		$res1 = '';
		if($credit_type['db_field'])
		{
			$ac = 'add_' . $credit_type['db_field'];
			$res1 = $mem_obj->$ac($res['user_id'],$res['credits'],$res['lottery_id'],APP_UNIQUEID,MOD_UNIQUEID,'update_win_info','抽奖加积分');
		}
		
		$up_data = array(
				'update_time'	=> TIMENOW,
		);
		if($res1)
		{
			$up_data['status'] = 1;
			
			//状态置为已发放
			if($res['wininfo_id'])
			{
				$sql = "UPDATE " . DB_PREFIX . "win_info SET provide_status = 1 WHERE id = {$res['wininfo_id']}";
				$this->db->query($sql);
			}
		}
		
		
		$sql = "UPDATE " . DB_PREFIX . "sync_fail SET ";
		
		foreach ($up_data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		
		$sql .= " WHERE id = '"  .$res['id']. "'";
		
		$this->db->query($sql);
		
	}
}

$out = new SyncCredits();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'run';
}
$out->$action();
?>