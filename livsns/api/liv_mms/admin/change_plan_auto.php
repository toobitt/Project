<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
*@public function show
*
* $Id: change_plan_auto.php
***************************************************************************/
require('global.php');
class changePlanAutoApi extends BaseFrm
{
	/**
	 * 构造函数
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include curl.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['vodapi']['host'], $this->settings['vodapi']['dir'], $this->settings['vodapi']['token']);
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 计划任务执行
	 * @name show
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include tvie_api.php
	 */
	public function show()
	{
		if($this->settings['tvie']['open'])
		{
			include(CUR_CONF_PATH . 'lib/tvie_api.php');
			$tvie_api = new TVie_api($this->settings['tvie']['up_stream_server']);
		}	
		$tomorrow_date = date("Y-m-d",TIMENOW+3600*24);
		$week = date("N",TIMENOW+3600*24);
		$sql = "SELECT * FROM " . DB_PREFIX . "channel WHERE 1 ";
		$q = $this->db->query($sql);
		$all_channel_id = $chg_ids =  array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['is_live'] && $r['save_time'] >= 24)
			{
				$all_channel_id[$r['id']] = $r['id'];
				$chg_ids[$r['id']] = $r['chg_id'];
			}
		}
 
		if(!empty($all_channel))
		{
			$channel_id = implode(',',$all_channel_id);	
			$sql = "SELECT * FROM " . DB_PREFIX . "change_plan p left join " . DB_PREFIX . "change_plan_relation r on r.plan_id=p.id where 1 and p.channel_id IN (" . $channel_id . ") and r.week_num=" . $week;
			$q = $this->db->query($sql);
			$sql_extra = $space = '';
			while($r = $this->db->fetch_array($q))
			{
				$week_days = $r['week_days'];
				$week_d = date('N', TIMENOW);
				$week = date('W',$r['program_start_time']);
				$this_week = date('W',TIMENOW);
				$offset_week = ($this_week - $week)*24*3600*7;
				if($week_days == $week_d)
				{
					$program_start_time = date('Y-m-d H:i:s', ($r['program_start_time'] + $offset_week));
				}
				else if($week_days > $week_d)
				{
					$program_start_time = date('Y-m-d H:i:s', ($r['program_start_time'] - (86400*($week_days-$week_d)) + $offset_week));
				}
				else if($week_days < $week_d)
				{
					$program_start_time = date('Y-m-d H:i:s', ($r['program_start_time'] + (86400*($week_d-$week_days)) + $offset_week));
				}
				if($r['program_start_time'])
				{	
					$stream_uri = substr($r['stream_uri'], 0, -27) . strtotime($program_start_time) . '000,' . (strtotime($program_start_time) + $r['toff']) . '000';
				}
			
				$start = strtotime($tomorrow_date . ' ' . date('H:i:s', $r['start_time']));
				$end = strtotime($tomorrow_date . ' ' . date('H:i:s', ($r['start_time'] + $r['toff'])));
				if($tvie_api)
				{
					$epg = $tvie_api->create_channel_epg($chg_ids[$r['channel_id']], $start, $end, $stream_uri, '播放' . $r['channel2_name'] . '的节目');
				}
				$epg_id = $epg['result']['id'];
				$sql_extra .= $space."(" . $r['channel_id'] . "," . $r['channel2_id'] . ",'" . $r['channel2_name'] . "'," . $epg_id . ",'" . $stream_uri ."',". strtotime($tomorrow_date .' '. date('H:i:s', $r['start_time'])) . ",". strtotime($program_start_time) . ",'" . $tomorrow_date . "',". $r['toff'] . "," . $r['type'] . "," . TIMENOW . "," .TIMENOW . ",'" . hg_getip() . "','" . $r['admin_name'] . "'," . $r['admin_id'] . ")";
				$space = ",";
			}
			$sql = "INSERT INTO " . DB_PREFIX . "channel_chg_plan(channel_id,channel2_id,channel2_name,epg_id,stream_uri,change_time,program_start_time,dates,toff,type,create_time,update_time,ip,admin_name,admin_id) values" . $sql_extra;
			$this->db->query($sql);
		}
	}
}

$out = new changePlanAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>