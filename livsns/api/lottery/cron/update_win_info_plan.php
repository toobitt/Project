<?php
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require_once(CUR_CONF_PATH.'global.php');
define('MOD_UNIQUEID','update_win_info');//模块标识
class WinInfoUpdate extends cronBase
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
			'name' => '计划任务缓存抽奖中奖信息',
			'brief' => '计划任务缓存抽奖中奖信息',
			'space' => '60',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function run()
	{
		if(!$this->settings['lottery_filter'])
		{
			return false;
		}
		
		$sql = "SELECT id FROM " . DB_PREFIX . "lottery_filter ";
		$q = $this->db->query($sql);
		
		while ($r = $this->db->fetch_array($q))
		{
			$lottery_id[] = $r['id'];
		}
		
		if(empty($lottery_id))
		{
			return false;
		}
		
		include_once CUR_CONF_PATH . 'lib/win_info_mode.php';
		$obj = new win_info_mode();
		
		//循环符合条件活动,整理信息
		foreach ($lottery_id as $key => $val)
		{
			//查询活动中奖记录
			$sql = "SELECT w.*,p.name,p.type,p.prize FROM " . DB_PREFIX . "win_info w
					LEFT JOIN " . DB_PREFIX . "prize p 
						ON w.prize_id = p.id 
					WHERE w.lottery_id = " . $val . " 
						AND w.prize_id != '' AND w.status=1 AND w.confirm=1 
					ORDER BY w.create_time DESC LIMIT 0,2";
			
			$q = $this->db->query($sql);
			$info = array();
			$member_id = array();
			while ($r = $this->db->fetch_array($q))
			{
				$r['create_time']	= hg_tran_time_tv($r['create_time']);
				$info[] 			= $r;
				$member_id[] 		= $r['member_id'];
			}
			
			if(empty($info))
			{
				continue;
			}
			
			if(!empty($member_id))
			{
				$member_info = $obj->get_memberInfo($member_id);
			}
			
			$win_info = array();
			foreach ($info as $value)
			{
				foreach ($value as $k => $v)
				{
					if($k == 'member_id' && $member_info[$v])
					{
						$value['member_name'] 	= $member_info[$v]['member_name'];
						$value['phone_num']	 	= $member_info[$v]['phone_num'];
						$value['avatar']	 	= $member_info[$v]['avatar'];
					}
				}
				$win_info[] = $value;
			}
			
			
			if(!empty($win_info))
			{
				$win_info = serialize($win_info);
				$sql = "UPDATE " . DB_PREFIX . "lottery_filter SET win_info = '" . $win_info . "' WHERE id = " . $val;
				$this->db->query($sql);
			}
		}
		return true;
	}
}

$out = new WinInfoUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'run';
}
$out->$action();
?>