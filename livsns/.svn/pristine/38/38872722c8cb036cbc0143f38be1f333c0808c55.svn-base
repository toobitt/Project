<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_auto.php 6444 2012-04-18 05:18:47Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_record');//模块标识
class programRecordRecoverApi extends adminReadBase
{
	private $mLive;
	private $live;
	function __construct()
	{
		parent::__construct();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		//$this->curl = new curl($this->settings['App_program_record']['host'], $this->settings['App_program_record']['dir'] . 'admin/');
		$this->curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		
		$this->mLive = $this->settings['mms']['live_stream_server'];
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function index(){}
	function count(){}
	
	function show()
	{
		//$id = '126,106,100,127,217,111,134,139,119';
		$id = $this->input['id'];
		$condition = '';
		if($id)
		{
			$condition .= ' pr.id IN(' . $this->input['id'] . ')';
			$day = intval($this->input['day']);
		}
		else
		{
			$condition .= "is_record=1 and (pr.start_time) > " . strtotime(date('Y-m-d',TIMENOW)) . " and (pr.start_time+pr.toff) < " . TIMENOW;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "program_record pr WHERE " . $condition;
		$record_info = $channel_info = array();
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$channel_info[$row['channel_id']] = $row['channel_id'];
			$record_info[] = $row;
		}
		if(!empty($channel_info))
		{
			$channel_ids = implode(',',$channel_info);
			include_once(ROOT_PATH . 'lib/class/live.class.php');
			$newLive = new live();
			$channel_tmp = $newLive->getChannelById($channel_ids);
			foreach($channel_tmp as $k => $v)
			{
				$channel[$v['id']] = $v;
			}
		}
	
		
		include_once(ROOT_PATH . 'lib/class/program.class.php');
		$program_plan = new program();
		$info = array();
		foreach($record_info as $k => $row)
		{
			$channel_tmp = $channel[$row['channel_id']];
			$row['name'] = $channel_tmp['name'];
			$row['save_time'] = $channel_tmp['save_time'];
			$row['record_time'] = $channel_tmp['record_time'];
		
			$program = $row['title'] ? $row['title'] : trim($program_plan->get_program_plan($row['channel_id'],$row['starttime'],$row['endtime']));
			if($id)
			{
				$row['start_time'] = $row['start_time'] - $day*86400;
			}
			$start_time = date('YmdHis',($row['start_time']+$row['record_time'] - 1));
			/*
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
				'title' => base64_encode(json_encode(trim($program ? $program : '精彩节目'))),
				'name' => $row['name'],
				'channel_id' => $row['channel_id'],
				'file_path' => substr($start_time,0,4) . '/' . substr($start_time,4,2). '/' . substr($start_time,6,2). '/' . substr($start_time,8,2). '/' . substr($start_time,10,2). '/' . substr($start_time,12,2) . '/' . $row['id'] . '.mp4',
				'extend' => base64_encode(json_encode(array('user_id' => $row['user_id'],'user_name' => $row['user_name'],'org_id' => $row['org_id'],'force_codec'=>$row['force_codec']))),
				'update_state' => 1,
			);
			*/
			
			$data = array(
			   'id'					=> $row['id'],
			   'is_time_shift'      => $row['time_shift'] ? 1 : 0,
			   'title' 				=> $program ? $program : '精彩节目',
			   'filepath' 			=> substr($start_time,0,4) . '/' . substr($start_time,4,2). '/' . substr($start_time,6,2). '/' . substr($start_time,8,2). '/' . substr($start_time,10,2). '/' . substr($start_time,12,2) . '/' . $row['id'] . '.flv',
			   'source' 			=> $row['name'],
			   'is_mark' 			=> $row['is_mark'],
			   'vod_sort_id' 		=> $row['item'],
			   'audit_auto' 		=> $row['audit_auto'] ? 2 : 0,
			   'column_id' 			=> $row['columnid'],
			   'channel_id' 		=> $row['channel_id'],
			   'vod_leixing' 		=> 3,
			   'start' 				=> '0',
			   'end' 				=> $row['toff']*1000,
			   'starttime' 			=> $start_time,
			   'create_time'		=> -1,
			   'appid' 				=> $this->input['appid'],
			   'appkey' 			=> $this->input['appkey'],
			   'user_id' 			=> $row['user_id'],
			   'user_name' 			=> $row['user_name'],
			   'org_id'			 	=> $row['org_id'],
			   'force_codec'		=> $row['force_codec'],
		   );
		   
		   hg_pre($data);exit;
		   
			$info[] = $data;
			if($this->input['debug'])
			{
				hg_pre($data);exit;
			}
			foreach ($data AS $k => $v)
			{
				$curl->addRequestData($k,$v);
			}
			$ret = $curl->request('create.php');
	
		/*	
			$this->curl->setSubmitType('post');
			$this->curl->initPostData();
			$this->curl->addRequestData('a','callBack');
			$this->curl->addRequestData('appkey',$this->input['appkey']);
			$this->curl->addRequestData('appid',$this->input['appid']);
			$this->curl->addRequestData('data',json_encode($data));
			$this->curl->addRequestData('html',1);
			$ret = $this->curl->request('record_callback.php');
			hg_pre($ret);
*/
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