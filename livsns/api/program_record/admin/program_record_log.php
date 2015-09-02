<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_list.php 7586 2012-07-05 09:40:56Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_record_log');//模块标识
class programRecordLogApi extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/program_record_log.class.php');
		$this->obj = new programRecordLog();
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
		$nodes = array(
			'_action' => 'show',
		);
		$nodes = array();
		if(intval($this->input['channel_id']) > 0)
		{
			$nodes['nodes'][intval($this->input['channel_id'])] = intval($this->input['channel_id']);
		}
		$this->verify_content_prms($nodes);
		$state = array(0=>'等待录制', 1=>'录制成功', 2=>'录制失败');
		$content_id = 0;
		$condition = $this->get_condition();
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if($this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$condition .= " AND channel_id IN (" . implode(',',$this->user['prms']['app_prms'][APP_UNIQUEID]['nodes']) . ")";
			}
			else
			{
				$condition .= " AND channel_id IN (-1)";
			}
		}
		if($this->input['record_id'])
		{
			$content_id = intval($this->input['record_id']);
			$condition .= " AND a.record_id=" . $content_id;
		}
		
		$cond_data = array();
		$cond_data['offset'] = $this->input['offset'] ? $this->input['offset'] : 0;
		$cond_data['count'] = $this->input['count'] ? intval($this->input['count']) : 20;
		
		$data_limit = " LIMIT " . $cond_data['offset'] . "," . $cond_data['count'];

		$data = $this->obj->show($condition,$data_limit);
		
		if(!empty($data))
		{
			foreach($data as $k => $v)
			{
				$this->addItem($v);	
			}
			$this->output();			
		}
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
		
		$row = $this->obj->detail($condition);
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
	}

	/**
	 * Enter description here ...
	 */
	public function count()
	{
		//暂时这样处理
		$condition = $this->get_condition();
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if($this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$condition .= " AND channel_id IN (" . implode(',',$this->user['prms']['app_prms'][APP_UNIQUEID]['nodes']) . ")";
			}
			else
			{
				$condition .= " AND channel_id IN (-1)";
			}
		}
		if($this->input['record_id'])
		{
			$condition .= " AND record_id=" . intval($this->input['record_id']);
		}
		$f = $this->obj->count($condition);
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
					$date_condit .= " AND  create_time > " . $yesterday . " AND create_time < " . $today;
					break;
				case 3://今天的数据
					$date_condit .= " AND  create_time > " . $today . " AND create_time < " . $tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$date_condit .= " AND  create_time > " . $last_threeday . " AND create_time < " . $tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$date_condit .= " AND  create_time > " . $last_sevenday . " AND create_time < " . $tomorrow;
					break;
				case 'other'://所有时间段
					$start = urldecode($this->input['start_time']) ? strtotime(urldecode($this->input['start_time'])) : 0;
					if($start)
					{
						$date_condit .= " AND create_time > '" . $start . "'";
					}
					$end = urldecode($this->input['end_time']) ? strtotime(urldecode($this->input['end_time'])) : 0;
					if($end)
					{
						$date_condit .= " AND create_time < '" . $end . "'";
					}
					break;
				default://所有时间段
					break;
			}
		}
		if($this->input['channel_id'] > 0)
		{
			$date_condit .= " AND channel_id=".intval($this->input['channel_id']);
		}
		return $date_condit;
	}
	function show_program_record_status()
	{
		$id = intval($this->input['id']);
		$condition = ' AND a.id = '.$id;
		$data_limit = '';
		$sql = "SELECT a.*,q.conid FROM  " . DB_PREFIX . "program_record_log a LEFT JOIN " . DB_PREFIX . "program_queue q ON a.id=q.log_id WHERE 1 " . $condition . " ORDER BY id DESC " . $data_limit;
		$info = $this->db->query_first($sql);
		//print_r($info);exit;
		if(!empty($info))
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 AND state=1";
			$q = $this->db->query($sql);
			$server_config = array();
			while($row = $this->db->fetch_array($q))
			{
				$server_config[$row['id']] = $row;
			}
			if($info['record_id'])
			{
				$sql = "SELECT id,server_id FROM " . DB_PREFIX . "program_record WHERE id IN(" . $info['record_id'] . ")";
				$q = $this->db->query($sql);
				$record_server = array();
				while($row = $this->db->fetch_array($q))
				{
					$record_server[$row['id']] = $row['server_id'];
				}
			}
			include_once(ROOT_PATH . 'lib/class/curl.class.php');
//			foreach($info as $k => $v)
//			{
			if(empty($server_config[$record_server[$info['record_id']]]))
			{
				$obj_curl = new curl($this->settings['mms']['record_server']['host'], $this->settings['mms']['record_server']['dir']);
			}
			else
			{
				$obj_curl = new curl($server_config[$record_server[$info['record_id']]]['host'] . ':' . $server_config[$record_server[$info['record_id']]]['port'], $server_config[$record_server[$info['record_id']]]['dir']);
			}		
			$ret = array();
			$ret['id'] = $info['id'];
			$ret['channel_id'] = $info['channel_id'];
			$ret['record_id'] = $info['record_id'];
			$ret['dates'] = date('Y-m-d',$info['start_time']);
			//$pre_title = date('Y-m-d H:i:s',$info['start_time']) . '~~~' . date('Y-m-d H:i:s',($info['start_time']+$info['toff'])) . '--《' . $info['title'] . '》--------';
			switch(intval($info['state']))
			{
				case 0://录制等待录制
					$ret['state'] = 0;
					$ret['operation'] = $info['text'];
					
					$obj_curl->setSubmitType('get');
					$obj_curl->initPostData();
					$obj_curl->addRequestData('action', 'SELECT');
					$obj_curl->addRequestData('id', $info['conid']);
					$record_xml = $obj_curl->request('');
					$record_array = xml2Array($record_xml);
	        		if(!empty($record_array) && $record_array['result'])//表示任务存在
	        		{
	        			if($record_array['record']['status'] == 'running')
	        			{
	        				if(($info['start_time']+$info['toff'])<=($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW))
	        				{
		        				$ret['operation'] = '录制超时';
				        		$ret['state'] = 2;
				        		$ret['has_completed'] = -1;
	        				}
	        				else 
	        				{
		        				$ret['has_completed'] = ($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW) - $info['start_time'];
	        					$ret['operation'] = '录制中';//.
	        				}
	        			}
	        			if($record_array['record']['status'] == 'waiting')
	        			{
	        				if(($info['start_time']+$info['toff'])<=($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW))
	        				{
		        				$ret['operation'] = '录制超时';
				        		$ret['state'] = 2;
				        		$ret['has_completed'] = -1;
	        				}
	        				else
	        				{
		        				$ret['operation'] = '等待录制';//.$record_array['record']['startTime']
		        				$ret['has_completed'] = -1;
	        				}
	        			}
	        		}
	        		else
	        		{
		        		$ret['operation'] = '录制超时';
						$ret['state'] = 2;
						$ret['has_completed'] = -1;
		        	}
		        	
				break;
				case 1://录制成功
					$ret['state'] = 1;
					$ret['operation'] = $info['text'] ? $info['text'] : '录制超时';
		        	$ret['has_completed'] = ($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW) - $info['start_time'];
				break;
				case 2://录制失败
					$ret['state'] = 2;
					$ret['operation'] = $info['text'] ? $info['text'] : '录制超时';
		        	$ret['has_completed'] = ($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW) - $info['start_time'];
				break;
			}
			$ret['auto'] = 1;
			$ret['toff'] = $info['toff'];
			$ret['isError'] = $ret['state']==2 ? 1 : 0;//$info['up_data'];
			$ret['operation'] = $ret['operation'];
//			}
		}
		$this->addItem($ret);
		$this->output();
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