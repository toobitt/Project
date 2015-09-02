<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
*@public function show
*
* $Id: change_plan_auto.php
***************************************************************************/
define('MOD_UNIQUEID','change_plan_auto');
require('global.php');
class changePlanAutoApi extends cronBase
{
	/**
	 * 构造函数
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include livemms.class.php
	 */
	private $mLivemms;
	private $mServerConfig;
	public function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
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
	 */
	public function show()
	{	
		$tomorrow_date = date("Y-m-d",TIMENOW+3600*24);
		$week = date("N",TIMENOW+3600*24);
		$sql = "SELECT id, server_id FROM " . DB_PREFIX . "channel WHERE is_live = 1 ";
		$q = $this->db->query($sql);
		
		$channel_id = $server_id =  array();
		while($row = $this->db->fetch_array($q))
		{
			$server_id[]  = $row['server_id'];
			$channel_id[] = $row['id'];
		}
		
		if (empty($channel_id))
		{
			$this->errorOutput('频道信息不存在');
		}
		//服务器配置
		if (!empty($server_id))
		{
			$server_id	   	= implode(',', @array_unique($server_id));
			$server_infos   = $this->mServerConfig->get_server_config($server_id);
		}
		
		$channel_id = implode(',',$channel_id);	
		
 		$channelStreamInfo = $this->channelStreams($channel_id);
		
		$sql = "SELECT t1.*, t2.* FROM " . DB_PREFIX . "change_plan t1 ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "change_plan_relation t2 ON t2.plan_id=t1.id ";
		$sql.= " WHERE 1 AND t1.channel_id IN (" . $channel_id . ") AND t2.week_num=" . $week;
		$q = $this->db->query($sql);
		$sql_extra = $space = '';
		while($r = $this->db->fetch_array($q))
		{
			//服务器配置
			if ($server_infos[$r['server_id']]['core_in_host'])
			{
				$host	= $server_infos[$r['server_id']]['core_in_host'] . ':' . $server_infos[$r['server_id']]['core_in_port'];
				$apidir = $server_infos[$r['server_id']]['input_dir'];
			}
			else 
			{
				$host 	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
				$apidir	= $this->settings['wowza']['core_input_server']['input_dir'];
			}
			
			$week_days 	 = $r['week_days'];
			$week_d 	 = date('N', TIMENOW);
			$week 		 = date('W',$r['program_start_time']);
			$this_week 	 = date('W',TIMENOW);
			$offset_week = ($this_week - $week)*24*3600*7;
			
			$program_start_time = '';
			if ($r['program_start_time'])
			{
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
			}
		
			$start = strtotime($tomorrow_date . ' ' . date('H:i:s', $r['start_time']));
			$toff  = $r['toff'];

			if (empty($channelStreamInfo[$r['channel_id']]))
			{
				continue;
			}
			
			$streamId = '';
			foreach ($channelStreamInfo[$r['channel_id']] AS $k => $v)
			{
				if ($v['is_main'])
				{
					$streamId = $v['chg_stream_id'];
				}
			}
			
			if (!$streamId)
			{
				continue;
			}
			
			$outputId = $streamId;
			
			if ($r['type'] == 2)
			{
				$ret_list = $this->mLivemms->inputFileListInsert($host, $apidir, $r['source_id']);

				if (!$ret_list['result'])
				{
					continue;
				}
				
				$sourceId = $ret_list['list']['id'];
			}
			else 
			{
				$sourceId = $r['source_id'];
			}
			$sourceType = $r['source_type'];
			
			if ($r['type'] == 3)
			{
				$epg_id = $r['program_start_time'] . '000';
				$fileid = $r['source_id'];
			}
			elseif ($r['type'] == 2)
			{
				$fileid = $r['source_id'];
			}
			else 
			{
				$epg = $this->mLivemms->inputScheduleInsert($host, $apidir, $outputId, $sourceId, $sourceType, $start, $toff);

				if (!$epg['result'])
				{
					continue;
				}
				
				$epg_id = $epg['schedule']['id'];
			}
			
			$change_time = strtotime($tomorrow_date .' '. date('H:i:s', $r['start_time']));
			
			//数据入库
			$data = array(
				'out_stream_id'		 => $outputId,
				'source_id' 		 => $sourceId,
				'source_type' 		 => $sourceType,
				'channel_id' 		 => $r['channel_id'],
				'channel2_id' 		 => $r['channel2_id'],
				'channel2_name' 	 => $r['channel2_name'],
				'change_time' 		 => $change_time,
				'toff' 				 => $r['toff'],
				'file_toff' 		 => $r['file_toff'],
				'fileid' 		 	 => $fileid ? $fileid : 0,
				'type' 				 => $r['type'],
				'program_start_time' => strtotime($program_start_time),
				'epg_id' 			 => $epg_id,
				'stream_uri' 	 	 => $r['stream_uri'],
				'dates' 			 => $tomorrow_date,
				'create_time' 		 => TIMENOW,
				'update_time' 		 => TIMENOW,
				'admin_name' 		 => $this->user['user_name'],
				'admin_id' 			 => $this->user['user_id'],
				'ip'		 		 => hg_getip(),
			);

			$createsql = "INSERT INTO " . DB_PREFIX . "channel_chg_plan SET ";
			$space = "";
			foreach($data AS $key => $value)
			{
				$createsql .= $space . $key . "=" . "'" . $value . "'";
				$space = ",";
			}
				
			$this->db->query($createsql);
			$data['id']	 = $this->db->insert_id();
			
			if ($data['id'] && $data['type'] == 3)
			{
				$url = $r['stream_uri'] . '?dvr&starttime=' . $r['program_start_time'] . '000' . '&duration=' . $toff . '000';
				$callback = $this->settings['App_live']['protocol'].$this->settings['App_live']['host'].'/'.$this->settings['App_live']['dir'].'admin/callback.php?a=record_callback&appid=' . intval($this->input['appid']) . '&appkey=' . urldecode($this->input['appkey']);
				$record_info = array(
					'id' 			=> $r['prefix'] . $data['id'],
					'uploadFile' 	=> 0,
					'access_token'  => $this->user['token'],
					'channel_id'  	=> $data['channel2_id'],
				//	'starttime'  	=> $program_start[$i],
				//	'toff'  		=> $toff,
				);
				if ($server_infos[$r['server_id']]['record_host'])
				{
					$host	= $server_infos[$r['server_id']]['record_host'] . ':' . $server_infos[$r['server_id']]['record_port'];
					$apidir = $server_infos[$r['server_id']]['record_dir'];
				}
				else 
				{
					$host 	= $this->settings['wowza']['record_server']['host'];
					$apidir	= $this->settings['wowza']['record_server']['dir'];
				}
				
				$ret_record = $this->mLivemms->recordInsert($host, $apidir, $record_info, urlencode($url), urlencode($callback));
				if ($ret_record['result'])
				{
					$sql = "UPDATE " . DB_PREFIX . "channel_chg_plan SET record_flag = 1 WHERE id = " . $data['id'];
					$this->db->query($sql);
				}
			}
			$this->addItem($data);
		}
		$this->output();
	}
	
	public function channelStreams($channel_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN(" . $channel_id .") ORDER BY id ASC";
		$q = $this->db->query($sql);
		$return = array();
		while ($r = $this->db->fetch_array($q))
		{
			$return[$r['channel_id']][] = $r;
		}
		return $return;
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