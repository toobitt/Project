<?php
/***************************************************************************
* $Id: channel_chg_plan_auto.php 19886 2013-04-08 02:01:25Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','old_live');
require('global.php');
class channelMmsChgPlanAutoApi extends cronBase
{
	private $mLivemms;
	function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 时移串联单计划任务执行
	 * Enter description here ...
	 */
	function record_schedule_edit()
	{
		$dates		= $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d');
		$condition  = " AND t1.dates = '" . $dates . "' ";
		$condition .= " AND t1.type = 3 AND t1.record_flag = 0 ";
		$orderby	= " ORDER BY t1.change_time ASC ";
		
		$sql = "SELECT t1.*, 
					 t2.server_id,
					 t3.record_host, t3.record_port, t3.record_dir
				FROM " . DB_PREFIX . "channel_chg_plan t1 ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "channel t2 ON t2.id = t1.channel2_id ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "server_config t3 ON t3.id = t2.server_id ";
		$sql.= " WHERE 1 " . $condition . $orderby;
		$q = $this->db->query($sql);
		
		$channel_chg_plan = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel_chg_plan[] = $row;
		}
	//	hg_pre($channel_chg_plan);
	//	exit;
		if (!empty($channel_chg_plan))
		{
			foreach ($channel_chg_plan AS $v)
			{
				if ($v['stream_uri'] && $v['program_start_time'])
				{
					$url = $v['stream_uri'] . '?dvr&starttime=' . $v['program_start_time'] . '000&duration=' . $v['toff'] . '000';
					$callback = $this->settings['App_live']['protocol'].$this->settings['App_live']['host'].'/'.$this->settings['App_live']['dir'].'admin/callback.php?a=record_callback&appid=' . intval($this->input['appid']) . '&appkey=' . urldecode($this->input['appkey']);
				
					$record_info = array(
						'id' 			=> $this->settings['wowza']['record']['prefix'] . $v['id'],
						'uploadFile' 	=> 0,
						'access_token'  => $this->user['token'],
						'channel_id'  	=> $v['channel2_id'],
					//	'starttime'  	=> $program_start[$i],
					//	'toff'  		=> $toff,
					);
					
					if ($v['record_host'])
					{
						$host	= $v['record_host'] . ':' . $v['record_port'];
						$apidir	= $v['record_dir'];
					}
					else 
					{
						$host 	= $this->settings['wowza']['record_server']['host'];
						$apidir	= $this->settings['wowza']['record_server']['dir'];
					}

					$ret_record = $this->mLivemms->recordInsert($host, $apidir, $record_info, urlencode($url), urlencode($callback));

					if ($ret_record['result'])
					{
						$sql = "UPDATE " . DB_PREFIX . "channel_chg_plan SET record_flag = 1 WHERE id = " . $v['id'];
						$this->db->query($sql);
					}
				}
			}
		}
		
		$this->addItem($channel_chg_plan);
		$this->output();
	}
}
$out = new channelMmsChgPlanAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'record_schedule_edit';
}
$out->$action();
?>