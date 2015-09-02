<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_auto.php 6444 2012-04-18 05:18:47Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_record');//模块标识
class programRecordRecoverApi extends cronBase
{
	private $mLive;
	private $live;
	function __construct()
	{
		parent::__construct();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$this->live = new live();
		
		$this->mLive = $this->settings['mms']['live_stream_server'];
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function show()
	{
		//$id = '126,106,100,127,217,111,134,139,119';
		$id = $this->input['id'];
		$condition = '';
		if($id)
		{
			$condition .= ' pr.id IN(' . $this->input['id'] . ')';
			$day = $this->input['day'] ? intval($this->input['day']) : 1;
		}
		else
		{
			$condition .= "is_record=1 and (pr.start_time) > " . strtotime(date('Y-m-d',TIMENOW)) . " and (pr.start_time+pr.toff) < " . TIMENOW;
		}
		$sql = "SELECT c.code, c.name,c.save_time,c.main_stream_name,c.stream_state,c.record_time,c.stream_id, pr.*,vs.sort_name
					FROM " . DB_PREFIX . "program_record pr 
					LEFT JOIN " . DB_PREFIX . "channel c 
						ON pr.channel_id=c.id 
					LEFT JOIN " . DB_PREFIX . "vod_sort vs 
						ON pr.item=vs.id
				WHERE " . $condition;
		$q = $this->db->query($sql);
//		echo "<pre>";
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$program = $row['title'] ? $row['title'] : trim($this->program_plan($row['channel_id'],$row['starttime'],$row['endtime']));
			if($id)
			{
				$row['start_time'] = $row['start_time'] - $day*86400;
			}
			$start_time = date('YmdHis',($row['start_time']+$row['record_time'] - 1));
			$data = array(
				'start_time' => $start_time,
				'id' =>  $row['id'],
				'duration' => $row['toff'],
				'exit_status' => 1,
				'save_time' => $row['save_time'],
				'source' => $row['channel_id'],
				'is_allow' => $row['is_mark'],
				'vod_sort_id' => $row['item'],
				'week_flag' => $row['week_day'] ? 1 : 0,
				'column_id' => $row['columnid'],
				'audit_auto' => $row['audit_auto'] ? 2 : 0,
				'title' =>  (trim($program ? $program : '精彩节目')),
				'name' => ($row['name']),
				'channel_id' => $row['channel_id'],
				'file_path' => substr($start_time,0,4) . '/' . substr($start_time,4,2). '/' . substr($start_time,6,2). '/' . substr($start_time,8,2). '/' . substr($start_time,10,2). '/' . substr($start_time,12,2) . '/' . $row['id'] . '.mp4',
			);

			//hg_pre($data);exit;
			$info[] = $data;
			$this->curl->setSubmitType('post');
			$this->curl->initPostData();
			$this->curl->addRequestData('a','callBack');
			$this->curl->addRequestData('appkey',$this->input['appkey']);
			$this->curl->addRequestData('appid',$this->input['appid']);
			$this->curl->addRequestData('data',json_encode($data));
			$ret = $this->curl->request('record_callback_bak.php');
			print_r($ret);
		}
		//hg_pre($info);
	}

	public function detail()
	{
		$date = $this->input['date'] ? $this->input['date'] : '';
		if($date)
		{
			$sql = "select * from " . DB_PREFIX . "program_record_log where start_time < " . (strtotime($date)+86399) . " and start_time > " . strtotime($date) . " and state=0";
			$q = $this->db->query($sql);

			$info = array();
			$log_id = $record_id = $space ='';
			while($row = $this->db->fetch_array($q))
			{
				//$info[] = $row;
				$log_id .= $space . $row['id'];
				$record_id .= $space . $row['record_id'];
				$space = ',';
			}
			echo $log_id . '<br/>';
			echo $record_id . '<br/>';
		}
	}

	public function update_log()
	{
		$log_id = $this->input['log_id'] ? $this->input['log_id'] : '';
		if($log_id)
		{
			$sql = "update " . DB_PREFIX . "program_record_log set state=1,text='录制成功' where id in(" . $log_id . ")";
			$this->db->query($sql);
		}
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
	
	public function tips($str,$pub_time)
	{
		echo $str . "----------------------------" . date("Y-m-d H:i:s",$pub_time) . '<br/>';
	}
}

$out = new programRecordRecoverApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>