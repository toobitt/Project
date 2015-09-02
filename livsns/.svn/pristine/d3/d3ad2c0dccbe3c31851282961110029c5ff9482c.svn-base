<?php
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require_once(CUR_CONF_PATH.'global.php');
define('MOD_UNIQUEID','lottery_filter');//模块标识
class LotteryFilter extends cronBase
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
			'name' => '计划任务缓存有效抽奖',
			'brief' => '计划任务缓存有效抽奖活动',
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
		
		$sql = "DELETE FROM " . DB_PREFIX . "lottery_filter ";
		$this->db->query($sql);
		
		
		$week_now = date('w',TIMENOW);
		$hour_now = date('His',TIMENOW);
		$day_now = date('d',TIMENOW);
		
		/*$sql = "SELECT * FROM " . DB_PREFIX . "lottery WHERE 
					status = 1 AND 
					time_limit = 1 AND 
					start_time != 0 AND 
					end_time != 0 AND 
					start_time <=" . TIMENOW . " AND 
					end_time > " .TIMENOW . " 
					OR (time_limit = 0 AND 
					status = 1) 	
				ORDER BY order_id DESC";*/
		
		$sql = "SELECT * FROM " . DB_PREFIX . "lottery WHERE status = 1 ORDER BY order_id DESC";
		$q = $this->db->query($sql);
		
		while ($r = $this->db->fetch_array($q))
		{
			if($r['time_limit'] && ($hour_now > $r['end_hour'] || $hour_now < $r['start_hour']))
			{
				//continue;
			}
			
			if($r['cycle_type'] && $r['cycle_value'])
			{
				//$r['cycle_value'] = explode(',', $r['cycle_value']);
				
				
				if($r['cycle_type'] == 1 && !in_array($week_now, $r['cycle_value']))
				{
					//continue;
				}
				else if($r['cycle_type'] == 2 && !in_array($day_now, $r['cycle_value']))
				{
					//continue;
				}
			}
			
			if($r['feedback'])
			{
				$r['feedback'] = unserialize($r['feedback']);
			}
			
			$r['start_times'] = $r['start_time'];
			$r['end_times'] = $r['end_time'];
			$lottery_id[] = $r['id'];
			$lottery_info[$r['id']] = $r;
		}
		if(empty($lottery_info))
		{
			return false;
		}
		
		$lottery_ids = implode(',', $lottery_id);
		
		//获取图片信息
		$sql = 'SELECT id,host,dir,filepath,filename FROM '.DB_PREFIX."materials  WHERE cid IN ({$lottery_ids})";
		$q = $this->db->query($sql);
		
		$indexpic = $pic_info = array();
		while($row = $this->db->fetch_array($q))
		{			
			if(!$row['cid'])
			{
				$indexpic[$row['id']] = $row;
				continue;
			}
			$pic_info[$row['cid']][$row['id']] = $row;
		}
		
		
		//查询奖项
		/*$sql = 'SELECT p.*,m.host,m.dir,m.filepath,m.filename FROM '.DB_PREFIX."prize p  
				LEFT JOIN " . DB_PREFIX . "materials m 
					ON p.indexpic_id = m.id 
				WHERE p.lottery_id IN ({$lottery_ids})";
		$q = $this->db->query($sql);
		
		
		$prize = array();
		while($row = $this->db->fetch_array($q))
		{			
			$prize[$row['lottery_id']][] = $row;
		}*/
		
		
		//hg_pre($lottery_info);
		include_once CUR_CONF_PATH . 'lib/win_info_mode.php';
		$obj = new win_info_mode();
		
		//循环符合条件活动,整理信息
		foreach ($lottery_info as $key => $val)
		{
			if($pic_info[$key])
			{
				$val['pic'] = $pic_info[$key];
			}
			else 
			{
				$val['pic'] = array();
			}
			
			if($indexpic[$val['indexpic_id']])
			{
				$val['indexpic'] = $indexpic[$val['indexpic_id']];
			}
			else 
			{
				$val['indexpic'] = array();
			}
			
			//查询活动中奖记录
			$sql = "SELECT w.*,p.name,p.type,p.prize FROM " . DB_PREFIX . "win_info w
					LEFT JOIN " . DB_PREFIX . "prize p 
						ON w.prize_id = p.id 
					WHERE w.lottery_id = " . $key . " 
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
			
			if(!empty($member_id))
			{
				$member_info = $obj->get_memberInfo($member_id);
			}
			
			$win_info = array();
			if(!empty($info) && $member_info)
			{
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
			}
			$val['win_info'] = $win_info;
			
			//活动奖品可以通过mamcache写入缓存
			if($prize[$key])
			{
				//$val['prize'] = $prize[$key];
			}
			else 
			{
				//$val['prize'] = array();
			}
			
			$sql = "INSERT INTO " . DB_PREFIX . "lottery_filter SET id=" . $val['id'] . ",sort_id = " . $val['sort_id'] . ",order_id = " . $val['order_id'] . ",content='" . serialize($val) . "'";
			$this->db->query($sql);
			//$data[] = $val;
		}
		//hg_pre($data,0);
		return true;
	}
}

$out = new LotteryFilter();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'run';
}
$out->$action();
?>