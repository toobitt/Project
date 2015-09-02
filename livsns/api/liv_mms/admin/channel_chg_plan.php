<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
*@public function show|count|detail|type_source
*@private function get_condition|getInfo|getPlan|verify_plan
*
* $Id: channel_chg_plan.php 
***************************************************************************/
require('global.php');
class ChannelChgPlanApi extends BaseFrm
{
	private $mChannel;
	private $mBackup;
	function __construct()
	{
		parent::__construct();
		
		require_once ROOT_PATH.'lib/class/curl.class.php';
		$this->mBackup = new curl($this->settings['liv_mms_api']['host'],$this->settings['liv_mms_api']['dir']);
		
		
		require_once CUR_CONF_PATH . 'lib/channel.class.php';
		$this->mChannel = new channels();
		
	}

	function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 串联单显示
	 * @name show
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $condition 检索条件
	 * @param $channel_id int 频道ID
	 * @param $dates string 格式化日期(Y-m-d)
	 * @return $channel_name string 频道名称
	 * @return $channel_id int 频道ID
	 * @return $dates_api string 格式化日期(Y-m-d)
	 * @return $uri string 频道输出流地址
	 * @return $change array 某天串联单信息内容
	 */
	public function show()
	{
		$condition = $this->get_condition();
		$channel_id = intval($this->input['channel_id']);
		if(!$channel_id)
		{
			$this->errorOutput('未传入频道ID');
		}
		$dates = $this->input['dates'] ? urldecode($this->input['dates']) : date('Y-m-d');
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_chg_plan ";
		$sql .= " WHERE channel_id=" . $channel_id . " AND dates='". $dates ."'" . $condition . " ORDER BY change_time ASC ";
		$q = $this->db->query($sql);
		
		$sql = "SELECT * FROM " . DB_PREFIX . "channel c LEFT JOIN " . DB_PREFIX . "channel_stream s ON c.id=s.channel_id WHERE c.id=" . $channel_id;
		$channel_info = $this->db->query_first($sql);
		
		$uri = hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $channel_info['code'], 'stream_name' => $channel_info['out_stream_name']));
		$this->addItem_withkey('channel_name', $channel_info['name']);
		$this->addItem_withkey('channel_id', $channel_id);
		$this->addItem_withkey('dates_api', $dates);
		$this->addItem_withkey('uri', $uri);
		
		$change = array();
		$start = strtotime($dates." 00:00:00");
		$end = strtotime($dates." 23:59:59");
		$com_time = 0;
		
		$today = date('Y-m-d',TIMENOW);
		$last_week_day = date('Y-m-d', (strtotime($today) + ((7-date('N',strtotime($today)))*86400)));
		$change_plan = $this->getPlan($channel_id, $dates);
		if($dates >= $today && $dates <= $last_week_day)
		{
			$change_plan = $this->getPlan($channel_id, $dates);
		}
		else 
		{
			$change_plan = array();
		}
		while($row = $this->db->fetch_array($q))
		{
			if(!$com_time && $row['change_time'] > $start)//头
			{
				$plan = $this->verify_plan($change_plan,$start,$row['change_time']);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$change[] = $v;
					}
				}
				else
				{
					$change[] = $this->getInfo($start,$row['change_time'],$dates);
				}
			}

			if($com_time && $com_time != $row['change_time'])//中
			{				
				$plan = $this->verify_plan($change_plan,$com_time,$row['change_time']);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$change[] = $v;
					}
				}
				else
				{
					$change[] = $this->getInfo($com_time,$row['change_time'],$dates); 
				}
			}
			
			if($row['change_time'] < TIMENOW)
			{
				$row['plan_status'] = 1;
			}
			else
			{
				$row['plan_status'] = 0;
			}
			$row['end_time'] = date('H:i:s',($row['change_time']+$row['toff']));
			$row['start_time'] = date('H:i:s',$row['change_time']);
			$row['e_time'] = date('H:i:s',($row['program_start_time']+$row['toff']));
			$row['s_time'] = date('m-d H:i:s',$row['program_start_time']);
			if($row['program_start_time'])
			{
				$row['program_end_time'] = date('Y-m-d H:i:s',($row['program_start_time']+$row['toff']));
				$row['program_start_time'] = date('Y-m-d H:i:s',$row['program_start_time']);
			}
			
			//文件
			$sql = "select * from " . DB_PREFIX . "backup WHERE id=" . $row['channel2_id'];
			$file_name = $this->db->query_first($sql);
			$row['file_name'] = $file_name['title'];
			if($file_name['toff'])
			{
				if(intval($file_name['toff']/1000/60))
				{
					$row['file_toff'] = intval($file_name['toff']/1000/60) . "'" . intval(($file_name['toff']/1000/60-intval($file_name['toff']/1000/60))*60) .'"' ;
				}
				else 
				{
					$row['file_toff'] = intval($file_name['toff']/1000) . '"' ;
				}
			}
			$row['start'] = date("H:i:s",$row['change_time']);
			$row['end'] = date("H:i:s",$row['change_time']+$row['toff']);
		
			$com_time = $row['change_time']+$row['toff'];
			$change[] = $row;
		}
	
		if($com_time && $com_time < $end)//中
		{			
			$plan = $this->verify_plan($change_plan,$com_time,$end);
			if($plan)
			{
				foreach($plan as $k => $v)
				{
					$change[] = $v;
				}
			}
			else
			{
			//	$change[] = $this->getInfo($com_time,$end,$dates);
			}
		}
		if(empty($change))
		{
			$change = array();
			$start = strtotime($dates." 00:00:00");
			$end = strtotime($dates." 23:59:59");
			$com_time = 0;
			foreach($change_plan as $k => $v)
			{
				if(!$com_time && $v['change_time'] > $start)//头
				{
					$change[] = $this->getInfo($start,$v['change_time'],$dates);
				}

				if($com_time && $com_time != $v['change_time'])//中
				{
					$change[] = $this->getInfo($com_time,$v['change_time'],$dates); 
				}
				$v['start'] = date("H:i",$v['change_time']);
				$v['end'] = date("H:i",$v['change_time']+$v['toff']);
				
				$com_time = $v['change_time']+$v['toff'];
				$change[] = $v;
			}
		}
		
		$this->addItem($change);
	//	hg_pre($change);
		$this->output();	
	}

	/**
	 * 填补空白数据
	 * @name getInfo
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $start int 开始时间
	 * @param $end int 结束时间
	 * @param $dates string 格式化日期(Y-m-d)
	 * @return $info array 空白数据
	 */
	private function getInfo($start,$end,$dates)
	{
		$info = array(
				'start_time' => $start,	
				'empty_toff' =>  $end-$start,
				'dates' => $dates,
				'start' => date("H:i:s",$start),	
				'end' => date("H:i:s",$end),
			);
		return $info;
	}
	
	/**
	 * 获取该频道串联单计划信息
	 * @name getPlan
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道ID
	 * @param $dates string 格式化日期(Y-m-d)
	 * @return $change_plan array 该频道串联单计划信息内容
	 */
	private function getPlan($channel_id,$dates)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "change_plan p LEFT JOIN " . DB_PREFIX . "change_plan_relation r ON r.plan_id=p.id WHERE 1 and p.channel_id=" . $channel_id . " AND r.week_num=" . date("N",strtotime($dates)) . " ORDER BY p.start_time ASC";
		$q = $this->db->query($sql);
		$change_plan = array();
		while($r = $this->db->fetch_array($q))
		{
			$week_days = $r['week_days'];
			$week_d = date('N', strtotime($dates));
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
			$change_plan[] = array(
					'id' => hg_rand_num(12),	
					'channel_id' => $r['channel_id'],
					'change_time' => strtotime($dates . " " . date("H:i:s",$r['start_time'])),	
					'toff' =>  $r['toff'],
					'stream_uri' => $r['stream_uri'],
					'channel2_id' => $r['channel2_id'],
					'channel2_name' => $r['channel2_name'],
					'program_end_time' => $r['program_start_time'] ? date('Y-m-d H:i:s',(strtotime($program_start_time)+$r['toff'])) : '',
					'program_start_time' => $r['program_start_time'] ? $program_start_time : '',
					'week_days' => $r['week_days'],
					'type' => $r['type'],	
					'dates' => $dates,
					'create_time' => TIMENOW,	
					'update_time' => TIMENOW,	
					'ip' => hg_getip(),	
					'end_time' => date("H:i:s",($r['start_time'] + $r['toff'])),
					'start_time' => date("H:i:s", $r['start_time']),
					'e_time' => $r['program_start_time'] ? date('H:i:s',(strtotime($program_start_time)+$r['toff'])) : '',
					's_time' => $r['program_start_time'] ? date('m-d H:i:s',strtotime($program_start_time)) : '',
					'is_plan' => 1,
				);
		}
		return $change_plan;
	}
		
	/**
	 * 合并串联单和串联单计划信息
	 * @name verify_plan
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $plan array 串联单计划内容
	 * @param $start_time int 开始时间
	 * @param $end_time int 结束时间
	 * @return $change array 合并后信息内容
	 */
	private function verify_plan($plan,$start_time,$end_time)
	{
		$change_plan = array();
		if(!empty($plan))
		{
			foreach($plan as $k => $v)
			{
				if($v['change_time'] >= $start_time && ($v['change_time']+$v['toff'])<= $end_time)
				{
					$change_plan[] = $v;
				}
			}
			if(empty($change_plan))
			{
				return false;
			}
			$change = array();
			$start = $start_time;
			$end = $end_time;
			$dates = date("Y-m-d",$start_time);
			$com_time = 0;
			foreach($change_plan as $k => $v)
			{
				if(!$com_time && $v['change_time'] > $start)//头
				{
					$change[] = $this->getInfo($start,$v['change_time'],$dates);
				}

				if($com_time && $com_time != $v['change_time'])//中
				{
					$change[] = $this->getInfo($com_time,$v['change_time'],$dates); 
				}
				$v['start'] = date("H:i",$v['change_time']);
				$v['end'] = date("H:i",$v['change_time']+$v['toff']);
		
				$com_time = $v['change_time']+$v['toff'];
				$change[] = $v;
			}
			if($com_time && $com_time < $end)//中		暂时这样
			{
				$change[] = $this->getInfo($com_time,$end,$dates);
			}
			if(empty($change_plan))
			{
				return false;
			}
			return $change;
		}
		else
		{
			return false;
		}
	}
		
	/**
	 * 串联单来源类型
	 * @name type_source
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道ID
	 * @param $type tinyint 来源类型 (1-直播 2-文件 3-时移)
	 * @param $channel2_id int 来源类型ID
	 * @param $program_start_time string 时移开始时间 (Y-m-d H:i:s)
	 * @param $program_end_time string 时移结束时间  (Y-m-d H:i:s)
	 * @param $channel2_name string 来源名称
	 * @return $change string json串， 串联单来源类型信息
	 */
	public function type_source()
	{
		$channel_id = intval($this->input['channel_id']);
		$type = intval($this->input['type']);
		$channel2_id = intval($this->input['channel2_id']);
		$program_start_time = $this->input['program_start_time'];
		$program_end_time = $this->input['program_end_time'];
		$channel2_name = $this->input['channel2_name'];
		$audio_only = intval($this->input['audio_only']);
		
		$type_source = array(
			'channel_id' => $channel_id,
			'type' => $type,
			'channel2_id' => $channel2_id,
			'program_start_time' => urldecode($program_start_time),
			'program_end_time' => urldecode($program_end_time),
			'channel2_name' => urldecode($channel2_name),
			'audio_only' => $audio_only,
		);
		
		$channel_condition = '';
		$get_channel_info = $this->mChannel->channelsInfo($channel_condition,0,20);
		
		$get_backup_info = $this->get_backup_info(0, $this->settings['channelChgPlan2BackupCount']);
		
		$total = $this->get_backup_count();

		$data = array(
			'total' => $total['total'],
			'count' => ceil($total['total']/$this->settings['channelChgPlan2BackupCount']),
			'type_source' => $type_source,
			'get_backup_info' => $get_backup_info,
			'get_channel_info' => $get_channel_info,
		);

		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 获取备播文件信息
	 * Enter description here ...
	 * @param unknown_type $offset
	 * @param unknown_type $count
	 */
	function get_backup_info($offset, $count)
	{
		$this->mBackup->setSubmitType('post');
		$this->mBackup->initPostData();
		$this->mBackup->addRequestData('a','show');
		$this->mBackup->addRequestData('offset',$offset);
		$this->mBackup->addRequestData('count',$count);
		$return = $this->mBackup->request('live_backup.php');
		return $return;
	}
	
	/**
	 * 备播文件总数
	 * Enter description here ...
	 */
	function get_backup_count()
	{
		$this->mBackup->setSubmitType('post');
		$this->mBackup->initPostData();
		$this->mBackup->addRequestData('a','count');
		$return = $this->mBackup->request('live_backup.php');
		return $return;
	}
	
	/**
	 * 分页调用接口
	 * Enter description here ...
	 */
	function backupPage()
	{
		$offset = intval($this->input['offset']);
		$count = intval($this->input['counts']);
		$info = $this->get_backup_info($offset, $count);
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 取单条信息
	 * @name detail
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 串联单ID
	 * @return $row array 单条串联单信息
	 */
	function detail()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id IN(' . $id .')';
		}			
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_chg_plan " . $condition;		
		$row = $this->db->query_first($sql);
		$this->setXmlNode('channel_chg_plan' , 'info');
		
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['channel_id'] = intval($this->input['channel_id']);
			$this->addItem($row);
			$this->output();
		}
		else
		{
			$this->errorOutput('串联单不存在');	
		} 	
	}
	
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $ret string 总数，json串
	 */
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "channel_chg_plan AS v WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$ret = $this->db->query_first($sql);

		//暂时这样处理
		echo json_encode($ret);
	}
	
			
	/**
	 * 检索条件
	 * @name get_condition
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' and program_name like \'%'.urldecode($this->input['k']).'%\'';
		}
		if($this->input['id'])
		{
			$condition .= ' AND id IN('.trim(urldecode($this->input['id'])).')';
		}
		return $condition;
	}
	
}

$out = new ChannelChgPlanApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>