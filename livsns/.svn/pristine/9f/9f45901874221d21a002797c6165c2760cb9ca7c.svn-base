<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','adv');//模块标识
define('SCRIPT_NAME', 'update_adtime');
require_once(ROOT_DIR.'global.php');
require_once('../lib/functions.php');
class update_adtime extends cronBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function initcron()
	{
		$array = array(
			'mod_uniqueid' 	=> MOD_UNIQUEID,	 
			'name' 			=> '广告状态自动更新',	 
			'brief' 		=> '定期执行广告的上架下架',
			'space'			=> '3600',	//运行时间间隔，单位秒
			'is_use' 		=> 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	function show()
	{
		$report = array();
		//搜索过期广告 排除下架广告
		$expired_con = ' AND end_time<unix_timestamp() AND end_time!="" AND status!=6 AND status!=2';
		$sql = 'SELECT id FROM '.DB_PREFIX.'advcontent WHERE 1 '.$expired_con;
		$q = $this->db->query($sql);
		$ads = array();
		while($row = $this->db->fetch_array($q))
		{
			$ads[] = $row['id']; 
		}
		//过期广告
		if($ads)
		{
			//查询过期广告的其余时间段
			$sql = 'SELECT * FROM '.DB_PREFIX.'adtime WHERE adid IN('.implode(',', $ads).') AND end_time!="" ORDER BY start_time ASC';
			$q = $this->db->query($sql);
			$adtimes = array();
			while($row = $this->db->fetch_array($q))
			{
				$adtimes[$row['adid']]['start_time'][] = $row['start_time'];
				$adtimes[$row['adid']]['end_time'][] = $row['end_time'];
			}
			foreach($adtimes as $adid=>$array_time)
			{
				$start_time = $end_time = '';
				$publishTime = get_ad_publishTime($array_time['start_time'], $array_time['end_time']);	
				$start_time = $publishTime['start_time'] ? $publishTime['start_time'] : '';
				$end_time = $publishTime['end_time'] ? $publishTime['end_time'] : '';
				$status = get_ad_status($start_time, $end_time);
				$sql = 'UPDATE '.DB_PREFIX.'advcontent SET start_time = "'.$start_time.'", end_time = "'.$end_time.'", status = "'.$status.'" WHERE id = '.intval($adid);
				$this->db->query($sql);
				$report['下架'][] = $adid;
			}
		}
		//广告上架
		$ads2 = array();
		$sql2 = 'SELECT id FROM '.DB_PREFIX.'advcontent WHERE start_time <= unix_timestamp() AND status != 6 AND end_time>=unix_timestamp() AND end_time !=""';
		$q2 = $this->db->query($sql2);
		while($row = $this->db->fetch_array($q2))
		{
			$ads2[] = $row['id'];
		}
		//存在过期广告
		if($ads2)
		{
			$sql = 'UPDATE '.DB_PREFIX.'advcontent SET status = 1 WHERE id IN ('.implode(',', $ads2).')';
			$this->db->query($sql);
		}
		$this->addItem($report);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');