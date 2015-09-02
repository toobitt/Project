<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_plan_auto.php 5429 2011-12-22 10:02:35Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','program_plan_auto');
require('global.php');
class programPlanAutoApi extends cronBase
{
	function __construct()
	{
		parent::__construct();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['App_mediaserver']['host'], $$this->settings['App_mediaserver']['dir'] . 'admin/');
	}

	function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 显示录播节目单
	 */
	function show()
	{
		//$time_now = round(microtime(true)*1000); //当前时间的毫秒级

	
		$tomorrow_date = date("Y-m-d",TIMENOW+3600*24);
		$week = date("N",TIMENOW+3600*24);
		$sql = "SELECT id FROM " . DB_PREFIX . "channel WHERE 1 ";
		$q = $this->db->query($sql);
		$all_channel = array();
		while($r = $this->db->fetch_array($q))
		{
			$all_channel[$r['id']] = $r['id'];
		}

		$sql = "SELECT c.id FROM " . DB_PREFIX . "channel c LEFT JOIN " . DB_PREFIX . "program p ON p.channel_id=c.id WHERE p.dates='" . $tomorrow_date . "' GROUP BY c.id ORDER BY c.id";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			if($all_channel[$r['id']])
			{
				unset($all_channel[$r['id']]);
			}
		}

		if(!empty($all_channel))
		{
			$channel = implode(',',$all_channel);		
			$sql = "SELECT * FROM " . DB_PREFIX . "program_plan p left join " . DB_PREFIX . "program_plan_relation r on r.plan_id=p.id where 1 and p.channel_id IN (" . $channel . ") and r.week_num=" . $week;
			$q = $this->db->query($sql);
			$program = array();
			$sql_extra = $space = '';
			while($r = $this->db->fetch_array($q))
			{
				$sql_extra .= $space."(" . $r['channel_id'] . "," . strtotime($tomorrow_date . " " . date("H:i:s",$r['start_time'])) . "," . $r['toff'] . ",'" . $r['program_name'] . "',1," . date("W",strtotime($tomorrow_date . " " . date("H:i:s",$r['start_time']))) . ",'" . $tomorrow_date . "'," . TIMENOW . "," . TIMENOW . ",'" . hg_getip() . "',1)";
				$space = ",";
			}
			$sql = "INSERT INTO " . DB_PREFIX . "program(channel_id,start_time,toff,theme,type_id,weeks,dates,create_time,update_time,ip,is_show) values" . $sql_extra;
			$q = $this->db->query($sql);
		}
	}
}

$out = new programPlanAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>