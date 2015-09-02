<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
*@public function create
*@private function verify_timeline|array_group|set_chg_uris
*
* $Id: channel_chg_plan_create.php 
***************************************************************************/
define('MOD_UNIQUEID','old_live');
require('global.php');
class channelMmsChgPlanUpdateApi extends adminUpdateBase
{
	private $mChannelChgPlan;
	private $mLivemms;
	private $mServerConfig;
	private $mLive;
	function __construct()
	{
		parent::__construct();
		if($this->mNeedCheckIn && !$this->prms['series_connection'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		require_once CUR_CONF_PATH . 'lib/channel_chg_plan.class.php';
		$this->mChannelChgPlan = new channelChgPlan();
		
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
		
		$this->mLive = $this->settings['wowza']['live_output_server'];
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function edit()
	{
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('请选择频道');
		}
		
		$date = urldecode($this->input['chg_date']);
		if ($date < date('Y-m-d'))
		{
			$this->errorOutput('此日期已过，无法设置串联单');
		}
		
		$sql = "SELECT id, is_live, live_delay, main_stream_name, server_id FROM " . DB_PREFIX . "channel WHERE id=" . $channel_id;
		$channel_info = $this->db->query_first($sql);
		if (!$channel_info['is_live'] || empty($channel_info))
		{
			$this->errorOutput('该频道不支持播控或频道不存在');
		}
		
		$chg_plan_ids 		= $this->input['ids'];
		$start_time 		= $this->input['start_times'];
		$end_time 			= $this->input['end_times'];
		$type 				= $this->input['type'];
		$channel2_id 		= $this->input['channel2_ids'];
		$channel2_name 		= $this->input['channel2_name'];
		$program_start_time = $this->input['program_start_time'];
		$epg_id 			= $this->input['epg_id'];
		$source_id 			= $this->input['source_id'];
		$hidden_temp 		= $this->input['hidden_temp'];
		
		//服务器配置
		$server_id = $channel_info['server_id'];
		if ($server_id)
		{
			$server_info 	= $this->mServerConfig->get_server_config_by_id($server_id);
		}
		
		if ($server_info['core_in_host'])
		{
			if ($server_info['is_dvr_output'])
			{
				$host = $server_info['dvr_in_host'] . ':' . $server_info['dvr_in_port'];
			}
			else 
			{
				$host = $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			}
			
			$apidir_output	= $server_info['output_dir'];
		}
		else 
		{
			if ($this->settings['dvr_output_server'])
			{
				$host 			= $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['dvr_output_server']['port'];
				$apidir_output	= $this->settings['wowza']['dvr_output_server']['output_dir'];
			}
			else
			{
				$host 			= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
				$apidir_output	= $this->settings['wowza']['core_input_server']['output_dir'];
			}
		}
		
		if ($this->mLive)
		{
			if ($server_info['is_live_output'])
			{
				$host 			= $server_info['live_in_host'] . ':' . $server_info['live_in_port'];
				$apidir_output	= $server_info['output_dir'];
			}
			else 
			{
				$host 			= $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['live_output_server']['port'];
				$apidir_output 	= $this->settings['wowza']['live_output_server']['output_dir'];
			}
		}
		
		$ret_ntpTime = $this->mLivemms->outputNtpTime($host, $apidir_output);
		
		if (!$ret_ntpTime['result'])
		{
			$this->errorOutput('获取媒体服务器端时间出错');
		}
		
		$ntpTime   = $ret_ntpTime['ntp']['utc'];
		$ntpYmdhis = ceil($ntpTime/1000);

		if ($start_time)
		{
			for ($j = 0; $j < count($start_time); $j++)
			{
				if ($date == date('Y-m-d') && strtotime(date('Y-m-d') . ' ' . $start_time[$j]) < $ntpYmdhis && !$chg_plan_ids[$j])
				{
					$this->errorOutput('此刻不能添加串联单');
				}
				
				if (!$start_time[$j])
				{
					$this->errorOutput('开始时间不能为空');
				}
				
				if (!$end_time[$j])
				{
					$this->errorOutput('结束时间不能为空');
				}
				
				if (!$channel2_id[$j])
				{
					$this->errorOutput('请选择频道或者备播文件或者时移节目');
				}
				
				if (!$channel2_name[$j])
				{
					$this->errorOutput('请选择频道或者备播文件或者时移节目');
				}
				
				if ($type[$i] == 3)
				{
					if (!$program_start_time[$i])
					{
						$this->errorOutput('时移时间不能为空');
					}
				}
			}
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_stream WHERE channel_id = " . $channel_id;
		$q = $this->db->query($sql);
		$channel_stream = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel_stream[] = $row;
		}
		
		if (empty($channel_stream))
		{
			$this->errorOutput('该频道不存在流信息');
		}
		
		if (!$this->verify_timeline($start_time, $end_time, $date))
		{
			$this->errorOutput('时间设置存在重复');
		}
		
		$add_input = array(
			'chg_plan_ids'			=> $this->input['ids'],
			'start_time'			=> $this->input['start_times'],
			'end_time'				=> $this->input['end_times'],
			'type'					=> $this->input['type'],
			'channel2_id'			=> $this->input['channel2_ids'],
			'channel2_name'			=> $this->input['channel2_name'],
			'program_start_time'	=> $this->input['program_start_time'],
			'epg_id'				=> $this->input['epg_id'],
			'source_id'				=> $this->input['source_id'],
			'hidden_temp'			=> $this->input['hidden_temp'],
		);
		
		$info = $this->mChannelChgPlan->edit($channel_id, $date, $add_input, $channel_stream, $server_info, $this->user);
		
		switch ($info)
		{
			case -22 :
				$this->errorOutput('串联单地址出错');
				break;
			case -23 :
				$this->errorOutput('媒体服务器端连接失败');
				break;
			case -24 :
				$this->errorOutput('媒体服务器端更新失败');
				break;
			case -25 :
				$this->errorOutput('媒体服务器端添加失败');
				break;
			default :
				break;
		}
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 检验复制是否是同一天
	 * @name check_copy
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道ID
	 * @param $dates string 日期
	 * @param $id string 要复制到的日期
	 * @param $show_id string 被复制的日期
	 * @return $tip array 日期数组
	 */
	public function check_copy()
	{
		$channel_id = $this->input['channel_id'];
		if(!$channel_id)
		{
			$this->errorOutput("未传入频道ID");
		}
		
		$dates = urldecode($this->input['dates']);//源日期
		if(!$dates)
		{
			$this->errorOutput("未传入更新日期");
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "channel_chg_plan WHERE channel_id=" . $channel_id . " and dates='" . $dates . "'";
		$f = $this->db->query_first($sql);
		
		$tip = array('ret'=>1,'id'=>$this->input['id'],'show_id'=>$this->input['show_id']);
		if(!$f['id'])
		{
			$tip = array('ret'=>0,'id'=>$this->input['id'],'show_id'=>$this->input['show_id']);
		}
		$this->addItem($tip);
		$this->output();
	}

	/**
	 * 复制串联单
	 * @name copy_day
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道ID
	 * @param $dates string 日期
	 * @param $copy_dates string 要复制的日期
	 * @return $tip array 复制成功后返回 1
	 */
	public function copy_day()
	{
		$channel_id = $this->input['channel_id'];
		if(!$channel_id)
		{
			$this->errorOutput("未传入频道ID");
		}

		$sql = "SELECT id, server_id FROM " . DB_PREFIX . "channel WHERE id = " . $channel_id;
		$channel_info = $this->db->query_first($sql);
		
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		//服务器配置
		$server_id = $channel_info['server_id'];
		if ($server_id)
		{
			$server_info 	= $this->mServerConfig->get_server_config_by_id($server_id);
		}
		
		if ($server_info['core_in_host'])
		{
			$host 	= $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			$apidir = $server_info['input_dir'];
		}
		else 
		{
			$host 	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$apidir	= $this->settings['wowza']['core_input_server']['input_dir'];
		}
		
		$dates = urldecode($this->input['dates']);//源日期
		if(!$dates)
		{
			$this->errorOutput("未传入更新日期");
		}

		$copy_dates = urldecode($this->input['copy_dates']);
		if(!$copy_dates)
		{
			$this->errorOutput("未传入要复制的日期");
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_chg_plan WHERE channel_id=" . $channel_id . " AND dates='" . $dates . "'";
		$q = $this->db->query($sql);
		$chg_info = array();
		while($row = $this->db->fetch_array($q))
		{
			$chg_info[] = $row;
		}
		
		$diff = strtotime($copy_dates) - strtotime($dates); 
		
		$sql = "SELECT epg_id FROM " . DB_PREFIX . "channel_chg_plan WHERE channel_id=" . $channel_id . " AND dates='" . $copy_dates . "'";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if ($row['epg_id'])
			{
				$ret_epg_delete = $this->mLivemms->inputScheduleOperate($host, $apidir, 'delete', $row['epg_id']);
			}
		}
		
		$sql = "DELETE FROM  " . DB_PREFIX . "channel_chg_plan WHERE channel_id=" . $channel_id ." AND dates='" . $copy_dates . "'";
		$this->db->query($sql);
		
		if ($chg_info)
		{
			foreach ($chg_info AS $k => $v)
			{
				$start = $v['change_time'] + $diff;
				$end = $v['change_time'] + $v['toff'] + $diff;
				if($v['program_start_time'])
				{
					$program_start_time = $v['program_start_time'] + $diff;
				}
				else 
				{
					$program_start_time = 0;
				}
				
				$sourceId = '';
				if ($v['type'] == 2)
				{
					$ret_list = $this->mLivemms->inputFileListInsert($host, $apidir, $v['fileid']);

					if (!$ret_list['result'])
					{
						continue;
					}
					
					$sourceId = $ret_list['list']['id'];
				}
				else if ($v['type'] == 3)
				{
					$sourceId = 0;
					$v['fileid'] = 0;
				}
				else 
				{
					$sourceId = $v['source_id'];
				}
				
				if ($v['type'] != 3)
				{
					$epg_insert = $this->mLivemms->inputScheduleInsert($host, $apidir, $v['out_stream_id'], $sourceId, $v['source_type'], $start, $v['toff']);
					
					if (!$epg_insert['result'])
					{
						continue;
					}
				}
				
				$epg_id = $epg_insert['schedule']['id'];
				
				if ($v['type'] == 3)
				{
					 $epg_id = $program_start_time . '000';
				}
				
				$info = array(
				    'channel_id' 		 => $v['channel_id'],
				    'channel2_id' 		 => $v['channel2_id'],
				    'channel2_name' 	 => $v['channel2_name'],
					'out_stream_id'		 => $v['out_stream_id'],
					'source_id' 		 => $sourceId,
					'source_type' 		 => $v['source_type'],
				    'epg_id' 			 => $epg_id,
				    'change_time' 		 => $start,
				    'program_start_time' => $program_start_time,
				    'dates'				 => $copy_dates,
				    'toff'				 => $v['toff'],
				    'file_toff'			 => $v['file_toff'],
				    'fileid'			 => $v['fileid'],
				    'type'				 => $v['type'],
				    'create_time'		 => TIMENOW,
				    'update_time'		 => TIMENOW,
				    'ip'				 => hg_getip(),
				    'admin_name'		 => $this->user['user_name'],
				    'admin_id'			 => $this->user['user_id'],
				    'stream_uri'		 => $v['stream_uri'] ? $v['stream_uri'] : '',
	  			);
	  			
				$createsql = "INSERT INTO " . DB_PREFIX . "channel_chg_plan SET ";
				$space = "";
				foreach($info as $key => $value)
				{
					$createsql .= $space . $key . "=" . "'" . $value . "'";
					$space = ",";
				}
				$this->db->query($createsql);
			}
		}
		$tip = array('ret'=>1);
		$this->addItem($tip);
		$this->output();

	}
	
	/**
	 * 删除串联单
	 * @name delete
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 串联单ID
	 * @return $ret int 被删除串联单ID
	 */
	public function delete()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('未传入串联单ID');
		}
		
		$sql = "SELECT channel_id, epg_id FROM " . DB_PREFIX . "channel_chg_plan WHERE id=" . $id;
		$epg = $this->db->query_first($sql);
		if (empty($epg))
		{
			$this->errorOutput('该串联单不存在或已被删除');
		}
		
		$sql = "SELECT id, server_id FROM " . DB_PREFIX . "channel WHERE id = " .$epg['channel_id'];
		$channel_info = $this->db->query_first($sql);
		
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		//服务器配置
		$server_id = $channel_info['server_id'];
		if ($server_id)
		{
			$server_info 	= $this->mServerConfig->get_server_config_by_id($server_id);
		}
		
		if ($server_info['core_in_host'])
		{
			$host 	= $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			$apidir = $server_info['input_dir'];
		}
		else 
		{
			$host 	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$apidir	= $this->settings['wowza']['core_input_server']['input_dir'];
		}
		
		if (strlen($epg['epg_id']) < 13)
		{
			if ($epg['epg_id'])
			{
				$ret_plan = $this->mLivemms->inputScheduleOperate($host, $apidir, 'delete', $epg['epg_id']);
			}
	
			if (!$ret_plan['result'])
			{
				$this->errorOutput('媒体服务器端删除失败');
			}
		}
		
		
		$sql = "DELETE FROM " . DB_PREFIX . "channel_chg_plan WHERE id=" .$id;
		$this->db->query($sql);

		$this->addItem($id);
		$this->output();
	}

	/**
	 * 检测某天节目单是否存在
	 * $channel_id int 频道id
	 * $dates string 日期
	 * Enter description here ...
	 */
	function check_program()
	{
		$channel_id = intval($this->input['channel_id']);
		$dates 		= trim(urldecode($this->input['dates']));
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		if (!$dates)
		{
			$this->errorOutput('日期不能为空');
		}
		
		//查询该频道的串联单
		$sql = "SELECT id FROM " . DB_PREFIX . "program WHERE channel_id = " . $channel_id . " AND dates = '" . $dates . "'";
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		
		$ret = array(
			'channel_id' => $channel_id,
			'dates' 	 => $dates,
		);
		
		$ret['result'] = 0;
		
		if (!empty($return))
		{
			$ret['result'] = 1;
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 复制一天的串联单到节目单中
	 * $channel_id int 频道id
	 * $dates string 日期
	 * Enter description here ...
	 */
	function chg2program()
	{
		$channel_id = intval($this->input['channel_id']);
		$dates 		= trim(urldecode($this->input['dates']));
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		if (!$dates)
		{
			$this->errorOutput('日期不能为空');
		}
		
		//查询该频道的串联单
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_chg_plan WHERE channel_id = " . $channel_id . " AND dates = '" . $dates . "'";
		$q = $this->db->query($sql);
		
		$chg_plan = array();
		while ($row = $this->db->fetch_array($q))
		{
			$chg_plan[] = $row;
		}

		if (empty($chg_plan))
		{
			$this->errorOutput('您选择的 ' . $dates . ' 没有串联单,请重新选择日期！');
		}
		
		//删除存在的节目单
		$sql = "DELETE FROM " . DB_PREFIX . "program WHERE channel_id = " . $channel_id . " AND dates = '" . $dates . "'";
		$this->db->query($sql);
		
		$ret = array(
			'dates' 	 => $dates,
			'channel_id' => $channel_id,
			'result'	 => 0,
		);
		
		//添加串联单到节目单
		foreach ($chg_plan AS $k => $v)
		{
			$creates = array(
				'channel_id'	 => $channel_id,
				'start_time'	 => $v['change_time'],
				'toff'			 => $v['toff'],
				'theme'			 => $v['channel2_name'],
				'type_id'		 => 1,
				'weeks'			 => date("W", $v['change_time']),
				'dates'			 => $dates,
				'create_time'	 => TIMENOW,
				'update_time'	 => TIMENOW,
				'ip'			 => hg_getip(),
				'is_show'		 => 1,
			);
			$sql = "INSERT INTO " . DB_PREFIX . "program SET ";
			$space = "";
			foreach($creates AS $k => $v)
			{
				$sql .= $space . $k . "=" . "'" . $v . "'";
				$space = ",";
			}
			
			$this->db->query($sql);
			
			$ret['result'] = 1;
		}
		
		$this->addItem($ret);
		$this->output();
	}
		
	/**
	 * 时间验证
	 * @name verify_timeline
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $starttime array 开始时间
	 * @param $endtime array 结束时间
	 * @return true or false 
	 */
	private function verify_timeline($starttime, $endtime , $date)
	{
		$return = $this->array_group($starttime, $endtime , $date);
		for ($i = 1, $c = count($return); $i < $c; $i++)
		{
			if (($return[$i] - $return[$i - 1]) < 0)
			{
				return false;
			}
		}
		return true;
	}
	
	/**
	 * 开始时间和结束时间组成新数组
	 * @name array_group
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $arr1 array 开始时间
	 * @param $arr2 array 结束时间
	 * @param $temp array 日期
	 * @return $array array 时间线
	 */
	private function array_group($arr1, $arr2, $temp)
	{
		$num = count($arr1);
		$array = array();
		$i = 0;
		$j = 0;
		while($j < $num)
		{
		   $array[$i] = strtotime(urldecode($temp. ' ' .$arr1[$j]));
		   $array[$i+1] = strtotime(urldecode($temp. ' ' .$arr2[$j]));
		   $i= $i + 2;
		   $j++;
		}
		return $array;
	}
	
	public function create()
	{
		
	}
	
	public function update()
	{
		
	}
	
	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
	
	function unknow()
	{
		$this->errorOutput('未定义的空方法');
	}
	
}
$out = new channelMmsChgPlanUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>