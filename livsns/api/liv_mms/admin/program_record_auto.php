<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_auto.php 6444 2012-04-18 05:18:47Z repheal $
***************************************************************************/
require('global.php');
class programRecordAutoApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		global $gGlobalConfig;
		$this->curl = new curl($gGlobalConfig['vodapi']['host'], $gGlobalConfig['vodapi']['dir'], $gGlobalConfig['vodapi']['token']);
	}

	function __destruct()
	{
		parent::__destruct();
	}

	private function getPlan($channel_id,$dates)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan p LEFT JOIN " . DB_PREFIX . "program_plan_relation r ON r.plan_id=p.id WHERE 1 and p.channel_id=" . $channel_id . " AND r.week_num=" . date("N",strtotime($dates)) . " ORDER BY p.start_time ASC";
		$q = $this->db->query($sql);
		$program_plan = array();
		while($r = $this->db->fetch_array($q))
		{
			$program_plan[] = array(
					'id' => hg_rand_num(10),	
					'channel_id' => $r['channel_id'],
					'start_time' => strtotime($dates . " " . date("H:i:s",$r['start_time'])),	
					'toff' =>  $r['toff'],	
					'theme' => $r['program_name'],	
					'subtopic' => '',	
					'type_id' => 1,	
					'dates' => $dates,	
					'weeks' => date('W',strtotime($dates . " " . date("H:i:s",$r['start_time']))),	
					'describes' => '',	
					'create_time' => TIMENOW,	
					'update_time' => TIMENOW,	
					'ip' => hg_getip(),	
					'is_show' => 1,	
					'start' => date("H:i",strtotime($dates . " " . date("H:i:s",$r['start_time']))),	
					'end' => date("H:i",strtotime($dates . " " . date("H:i:s",$r['start_time'])) + $r['toff']),	
					'is_plan' => 1,
				);
		}
		return $program_plan;
	}

	private function program_plan($channel_id,$start,$end)
	{
		if(!$channel_id || !$start || !$end)
		{
			return false;
		}
		$dates = date('Y-m-d',$start);
		$start_time = strtotime($dates . ' 00:00:00');
		$end_time = strtotime($dates . ' 23:59:59');
		
		$program_plan = $this->getPlan($channel_id,$dates);
		$sql = "SELECT * FROM " . DB_PREFIX . "program WHERE channel_id=" . $channel_id . " and dates='" . $dates . "' ORDER BY start_time ASC";
		$q = $this->db->query($sql);
		$program = array();
		$com_time = 0;//取节目的最大时间和最小时间
		while($r = $this->db->fetch_array($q))
		{
			if(!$com_time && $r['start_time'] > $start_time)//头
			{
				$plan = $this->verify_plan($program_plan,$start_time,$r['start_time']);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$program[] = $v;
					}
				}
			}
			if($com_time && $com_time != $r['start_time'])//中
			{
				$plan = $this->verify_plan($program_plan,$com_time,$r['start_time']);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$program[] = $v;
					}
				}
			}
			$r['start'] = date('H:i:s',$r['start_time']);
			$r['end'] = date('H:i:s',$r['start_time']+$r['toff']);
			$com_time = $r['start_time']+$r['toff'];
			$program[] = $r;
		}

		if($com_time && $com_time < $end_time)//尾
		{
			$plan = $this->verify_plan($program_plan,$com_time,$end_time);
			if($plan)
			{
				foreach($plan as $k => $v)
				{
					$program[] = $v;
				}
			}
		}else
		{
			$plan = $this->verify_plan($program_plan,$com_time,$end_time);
			if($plan)
			{
				foreach($plan as $k => $v)
				{
					$program[] = $v;
				}
			}
		}
		if(!empty($program))
		{
			$str = $space = '';
			foreach($program as $key => $value)
			{
				if($value['start_time'] == $start && ($value['start_time']+$value['toff']) == $end)
				{
					$str =  $value['theme'];
					break;
				}
				else
				{
					if($value['start_time'] >= $start && $value['start_time'] < $end)
					{
						$str .= $space . $value['theme'];
						$space = ',';
					}
					if($value['start_time'] < $start)
					{
						if(($value['start_time']+$value['toff']) > $start)
						{
							$str .= $space . $value['theme'];
							$space = ',';
						}
					}				
				}
			}
			return $str;
		}
		else
		{
			return false;
		}
	}

	private function verify_plan($plan,$start_time,$end_time)
	{
		$program_plan = array();
		if(!empty($plan))
		{
			foreach($plan as $k => $v)
			{
				if($v['start_time'] >= $start_time && ($v['start_time']+$v['toff']) <= $end_time)
				{
					$program_plan[] = $v;
				}
			}
			return $program_plan;
		}
		else
		{
			return false;
		}
	}

	/**
	 * 显示录播节目单
	 */
	function show()
	{
		$sql = "SELECT c.id, c.code, c.name,c.save_time,c.main_stream_name,c.stream_state,c.record_time,c.stream_id, pr.*,vs.sort_name
					FROM " . DB_PREFIX . "program_record pr 
					LEFT JOIN " . DB_PREFIX . "channel c 
						ON pr.channel_id=c.id 
					LEFT JOIN " . DB_PREFIX . "vod_sort vs 
						ON pr.item=vs.id
				WHERE pr.is_record=0 AND (pr.start_time+pr.toff) < " . (TIMENOW - 40); //延迟30秒开始录制
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{	
			$return = array();
			if(!$row['channel_id'])
			{
				continue;
			}
			$ret = array();
			$ret['channel_id'] = $row['channel_id'];

			$ret['starttime'] = $row['start_time']+$row['record_time'];
			$ret['endtime'] = $ret['starttime'] + $row['toff'];

			$ret['program'] = $row['title'] ? $row['title'] : $this->program_plan($ret['channel_id'],$ret['starttime'],$ret['endtime']);

			$row['program_name'] = $ret['program'] = $ret['program'] ? $ret['program'] : '精彩节目';
			$ret['stream'] = hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel'=>$row['code'],'stream_name'=>$row['main_stream_name']),'internal');
			$ret['save_time'] = $row['save_time'];
			$ret['vod_sort_id'] = $row['item'];
			$ret['delay_time'] = $row['live_delay'] * 60;
			$ret['source'] = $row['channel_id'];
			$ret['is_allow'] = $row['is_mark'];
			$ret['force_codec'] = $row['force_codec'];
			if ($ret['endtime'] + $ret['delay_time'] > TIMENOW)
			{
				continue;
			}
			$sql = "select id, ch_name,other_info,s_status from " . DB_PREFIX . "stream where id=" . $row['stream_id'];
			$stream = $this->db->query_first($sql);
			if(!$row['stream_state'])
			{
				$ret = array('errortext' => '视频流未开启！');

			}elseif(!$stream['s_status'])
			{
				$ret = array('errortext' => '视频上游流未开启！');
			}
			else{		
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				
				foreach($ret as $key => $value)
				{
					$this->curl->addRequestData($key, $value);
				}
				if ($row['columnid'])
				{
					$this->curl->addRequestData('status', '2');
				}
				$ret = $this->curl->request('record.php');				
			}
			
			$sql_update = "UPDATE " . DB_PREFIX . "program_record SET is_record=1 WHERE id=" . $row['id'];
			$state = 2;
			$error = $ret['errortext'];
			$week_day = unserialize($row['week_day']);
			if (is_array($week_day) && $week_day)
			{
				$week_now = date('N',$row['start_time']);
				$new_arr = array_flip($week_day);
				if(count($week_day) > ($new_arr[$week_now]+1))
				{
					$ks = $new_arr[$week_now] + 1;
				}
				else
				{
					$ks = 0;
				}
				$week_day = array_flip($new_arr);
				$next_week = ($week_day[$ks] - $week_now)>0?($week_day[$ks] - $week_now):($week_day[$ks] - $week_now + 7);
				$start_time = $row['start_time']+($next_week*86400);
				$sql_update = "UPDATE " . DB_PREFIX . "program_record SET is_record=0,start_time=" . $start_time . " WHERE id=" . $row['id'];			
			}
			if(!empty($ret['vodid']))
			{
				$state = 1;
				$return = array(
					'conid' => $ret['id'],	
					'admin_id' => $ret['admin_id'],
					'admin_name' => $ret['admin_name'],
					'columnid' => $row['columnid'],
					'status' => $ret['status'] == 2 ? 1 : 0,//转码状态(0=>"正在转码中"，1=>"转码完成"，2=>"审核通过",3=>"审核不过")
				);
			}
			$this->db->query($sql_update);	
			$log = array(
				'record_id' => $row['id'],
				'channel_id' => $row['channel_id'],
				'channel_name' => $row['name'],
				'program_name' => $row['program_name'],
				'start_time' => $row['start_time'],
				'vod_id' => $ret['id'],
				'toff' => $row['toff'],
				'item' => $row['item'],
				'sortname' => $row['sort_name'],
				'create_time' => TIMENOW,
				'update_time' => TIMENOW,
				'ip' => hg_getip(),
				'state' => $state,
				'error' => $error,
				'auto' => 1,
			);
			$createsql = "INSERT INTO " . DB_PREFIX . "program_record_log SET ";
			$space = "";
			foreach($log as $key => $value)
			{
				$createsql .= $space . $key . "=" . "'" . $value . "'";
				$space = ",";
			}
			$this->db->query($createsql);
			if(!empty($return))
			{
				$this->addItem($return);
			}
		}
		$this->output();
	}	
}

$out = new programRecordAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>