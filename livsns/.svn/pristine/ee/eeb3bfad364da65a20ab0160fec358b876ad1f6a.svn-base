<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_list.php 7586 2012-07-05 09:40:56Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_record');//模块标识
class programRecordLogApi extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function index()
	{
		
	}

	function show()
	{
		if($this->mNeedCheckIn && !$this->prms['manage_log'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$state = array(0=>'等待录制', 1=>'录制成功', 2=>'录制失败');
		$content_id = 0;
		$condition = $this->get_condition();
		if($this->input['record_id'])
		{
			$content_id = intval($this->input['record_id']);
			$condition .= " AND a.record_id=" . $content_id;
		}
		
		$sql = "SELECT id,stream_state FROM " . DB_PREFIX . "channel WHERE 1";
		$q = $this->db->query($sql);
		$channel = array();
		while($row = $this->db->fetch_array($q))
		{
			$channel[$row['id']] = $row['stream_state'];
		}
		$cond_data = array();
		$cond_data['offset'] = $this->input['offset'] ? $this->input['offset'] : 0;
		$cond_data['count'] = $this->input['count'] ? intval($this->input['count']) : 20;
		
		$data_limit = " LIMIT " . $cond_data['offset'] . "," . $cond_data['count'];

		$sql = "SELECT a.*,q.conid FROM  " . DB_PREFIX . "program_record_log a LEFT JOIN " . DB_PREFIX . "program_queue q ON a.id=q.log_id WHERE 1 " . $condition . " ORDER BY id DESC " . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		if(!empty($info))
		{
			include_once(ROOT_PATH . 'lib/class/curl.class.php');
			$obj_curl = new curl($this->settings['mms']['record_server']['host'], $this->settings['mms']['record_server']['dir']);
			
			foreach($info as $k => $v)
			{
				$ret = array();
				$ret[$k]['id'] = $v['id'];
				$ret[$k]['record_id'] = $v['record_id'];
				$ret[$k]['dates'] = date('Y-m-d',$v['start_time']);
				$pre_title = date('Y-m-d H:i:s',$v['start_time']) . '~~~' . date('Y-m-d H:i:s',($v['start_time']+$v['toff'])) . '--《' . $v['title'] . '》--------';
				switch(intval($v['state']))
				{
					case 0://录制等待录制
						$ret[$k]['state'] = 0;
						$ret[$k]['operation'] = $v['text'];
						
						$obj_curl->setSubmitType('get');
						$obj_curl->initPostData();
						$obj_curl->addRequestData('action', 'SELECT');
						$obj_curl->addRequestData('id', $v['conid']);
						$record_xml = $obj_curl->request('');
						$record_array = xml2Array($record_xml);
		        		if(!empty($record_array) && $record_array['result'])//表示任务存在
		        		{
		        			if($record_array['record']['status'] == 'running')
		        			{
		        				$ret[$k]['has_completed'] = ($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW) - $v['start_time'];
		        				$ret[$k]['operation'] = '录制中';//.
		        			}
		        			if($record_array['record']['status'] == 'waiting')
		        			{
		        				if(($v['start_time']+$v['toff'])<=($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW))
		        				{
			        				$ret[$k]['operation'] = '录制超时';
					        		$ret[$k]['state'] = 2;
					        		$ret[$k]['has_completed'] = -1;
		        				}
		        				else
		        				{
			        				$ret[$k]['operation'] = '等待录制';//.$record_array['record']['startTime']
			        				$ret[$k]['has_completed'] = -1;
		        				}
		        			}
		        		}
		        		else
		        		{
			        		$ret[$k]['operation'] = '录制超时';
							$ret[$k]['state'] = 2;
							$ret[$k]['has_completed'] = -1;
			        	}
			        	
					break;
					case 1://录制成功
						$ret[$k]['state'] = 1;
						$ret[$k]['operation'] = $v['text'] ? $v['text'] : '录制超时';
			        	$ret[$k]['has_completed'] = ($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW) - $v['start_time'];
					break;
					case 2://录制失败
						$ret[$k]['state'] = 2;
						$ret[$k]['operation'] = $v['text'] ? $v['text'] : '录制超时';
			        	$ret[$k]['has_completed'] = ($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW) - $v['start_time'];
					break;
				}
									
				if(!$channel[$v['channel_id']])
				{
					$ret[$k]['operation'] = '频道关闭';
					$ret[$k]['state'] = 2;
				}
				
				//$ret[$k]['state'] = intval($v['state']);
				$ret[$k]['auto'] = 1;
				$ret[$k]['toff'] = $v['toff'];
				$ret[$k]['isError'] = $ret[$k]['state']==2 ? 1 : 0;//$v['up_data'];
				$ret[$k]['operation'] = $pre_title . $ret[$k]['operation'];
				//hg_pre($ret[$k]);
				$this->addItem($ret[$k]);
			}
		}
		
		$this->output();
	}
	
	/**
	 * 获取单条信息
	 */
	public function detail()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$condition = " ORDER BY id DESC LIMIT 1";
		}
		else 
		{
			$condition = " WHERE id IN(" . $id . ")";
		}
		$sql ="SELECT * FROM " . DB_PREFIX . "program_record_log " . $condition;
		$row = $this->db->query_first($sql);
		$this->setXmlNode('program_record', 'info');
		
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['end_time'] = date('Y-m-d H:i:s' , ($row['start_time'] + $row['toff']));
			$row['start_time'] = date('Y-m-d H:i:s' , $row['start_time']);
			$row['week_day'] = $row['week_day'] ? unserialize($row['week_day']) : array();
		
			$this->addItem($row);
			$this->output();
		}
		else 
		{
			$this->errorOutput('录播节目不存在');
		}
	}

	/**
	 * Enter description here ...
	 */
	public function count()
	{
		//暂时这样处理
		$condition = $this->get_condition();
		if($this->input['record_id'])
		{
			$condition .= " AND record_id=" . intval($this->input['record_id']);
		}		
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "program_record_log WHERE 1" . $condition;
		$f = $this->db->query_first($sql);
		echo json_encode($f);
	}
	
	/**
	 * 获取条件
	 */
	private function get_condition()
	{
		$date_condit = "";
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$date_condit .= " AND  a.create_time > " . $yesterday . " AND a.create_time < " . $today;
					break;
				case 3://今天的数据
					$date_condit .= " AND  a.create_time > " . $today . " AND a.create_time < " . $tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$date_condit .= " AND  a.create_time > " . $last_threeday . " AND a.create_time < " . $tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$date_condit .= " AND  a.create_time > " . $last_sevenday . " AND a.create_time < " . $tomorrow;
					break;
				case 'other'://所有时间段
					$start = urldecode($this->input['start_time']) ? strtotime(urldecode($this->input['start_time'])) : 0;
					if($start)
					{
						$date_condit .= " AND a.create_time > '" . $start . "'";
					}
					$end = urldecode($this->input['end_time']) ? strtotime(urldecode($this->input['end_time'])) : 0;
					if($end)
					{
						$date_condit .= " AND a.create_time < '" . $end . "'";
					}
					break;
				default://所有时间段
					break;
			}
		}
		if($this->input['channel_id'] > 0)
		{
			$date_condit .= " AND a.channel_id=".intval($this->input['channel_id']);
		}
		return $date_condit;
	}
}

$out = new programRecordLogApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>