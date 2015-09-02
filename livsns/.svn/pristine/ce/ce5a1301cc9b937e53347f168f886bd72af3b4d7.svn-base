<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program.php 6082 2012-03-13 03:16:40Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . "global.php");
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','program_record');
class programRecordUpdateApi extends outerUpdateBase
{
	function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/program_record.class.php');
		$this->obj = new programRecord();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	function create()
	{
		$channel_id = intval($this->input['channel_id'] ? $this->input['channel_id'] : 0);
		if(empty($channel_id))
		{
			$this->errorOutput('请传入频道ID');
		}
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannelById($channel_id, -1);
		$channel = $channel[0];
		if(!$channel['id'])
		{
			$this->errorOutput('该频道已经不存在！');
		}
		 
		$start_time = strtotime(trim(urldecode($this->input['dates'])) . ' ' . trim(urldecode($this->input['start_time'])));
		$end_time = strtotime(trim(urldecode($this->input['dates'])) . ' ' . trim(urldecode($this->input['end_time'])));

		
		$toff = $end_time - $start_time;
		if($toff < 0)
		{
			$toff = $toff + 24*3600;
		}		
		$is_record = 0;//判断录制状态
		$is_out = 0;//判断是否超时
		if(is_array($this->input['week_day']) && $this->input['week_day'])
		{
			$is_record = 0;
			if($start_time > (TIMENOW+5))//大于当前时间
			{
				$week_now = date('N',TIMENOW);
				$week_num = $this->input['week_day'];
				if(in_array($week_now,$week_num))
				{
					$dates = date('Y-m-d',TIMENOW);
				}
				$i = 0;
				$next_day = $next_week_day = 0;
				foreach($week_num as $k => $v)
				{
					if(!$i && $v > $week_now)
					{
						$next_day = $v;
						$i = 1;
					}
					if(!$k)
					{
						$next_week_day = $v;
					}
				}
				if(!$next_day)
				{
					$next_day = $next_week_day;
				}
				$next_week = ($next_day - $week_now)>0?($next_day - $week_now):($next_day - $week_now + 7);
				$dates = date('Y-m-d', (TIMENOW + $next_week*86400));
				$start_time =  strtotime($dates . ' ' . trim(urldecode($this->input['start_time'])));
				$end_time =  strtotime($dates . ' ' . trim(urldecode($this->input['end_time'])));
			}
			$week_day = serialize($this->input['week_day']);
		}
		else
		{//单天
			if($start_time > TIMENOW)//大于当前时间
			{
				$is_record = 0;
				$is_out = 0;
			}
			else
			{
				$is_record = 2;
				$is_out = 1;
			}			
		}

		
		$sql = "SELECT channel_id, start_time, toff FROM " . DB_PREFIX . "program_record WHERE channel_id =" . $channel_id . " AND " . $start_time . "=start_time and " . $end_time . "=(start_time+toff) AND week_day='" . $week_day . "'";
		$f = $this->db->query_first($sql);
		if($f['channel_id'])
		{
			$this->errorOutput('此段时间已经有节目被录制！');
		}
		$columnid = $this->input['column_id'] ? $this->input['column_id'] : '';
		$column_name = $this->input['column_name'] ? $this->input['column_name'] : '';
		
		$info = array(
				'channel_id' => $channel_id,
				'start_time' => $start_time,
				'title' => $this->input['title'] ? $this->input['title'] : '',
				'toff' => $toff,
				'week_day' => ($week_day ? $week_day : ''),
				'item' => intval($this->input['item']),
				'columnid' => $columnid,
				'column_name' => $column_name,
				'create_time' => TIMENOW,
				'update_time' => TIMENOW,
				'is_mark' => $this->input['is_mark'],
				'ip' => hg_getip(),
				'force_codec' => $this->input['force_codec'] ? 1 : 0,
				'is_record' => $is_record,
				'audit_auto' => $this->input['audit_auto'],
				'is_out' => $is_out,
				'user_id' => $this->user['user_id'],
				'user_name' => $this->user['user_name'],
				'org_id' => $this->user['org_id'],
				'appid' => $this->user['appid'],
				'appname' => $this->user['display_name'],
		);
		if(!$info['channel_id'] || !$info['start_time'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		if(empty($toff))
		{
			$this->errorOutput('录制时长不能为零！');
		}
		$server = $this->_get_server();
		if(empty($server))
		{
			$this->errorOutput('录制服务不存在or有误～');
		}
		$info['server_id'] = $server['id'];
		$ret = $this->obj->create($info);
		$info['id'] = $ret['id'];
		
		$this->setXmlNode('program_record', 'info');
		$this->addItem($ret);
		$this->output();
	}
	
	private function _get_server($offset = 0)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 AND state=1 LIMIT " . $offset . ",1";
		$f = $this->db->query_first($sql);
		if(!$f)
		{
			return false;
		}
		$f['isSuccess'] = $this->_checkServer($f['host'] . ':' . $f['port']);
		if(!$f['isSuccess'])
		{
			return $this->_get_server($offset+1);
		}
		else
		{
			return $f;
		}	
	}
	
	private function _checkServer($url)
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_exec($ch);
		$head_info = curl_getinfo($ch);
        curl_close($ch);
		if ($head_info['http_code'] != 200)
		{
			return false;
		}
		return true;
	}

	public function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "select * from " . DB_PREFIX . "program_record where id=" . $id;
		$f = $this->db->query_first($sql);
		
		
		if($f['conid'] && $f['start_time'] > (TIMENOW+5)) //更新的时候，假如当前的conid计划，未开始录制，删除，正在录制不进行任何调整 5秒的冗余
		{
			$sql = "SELECT id,log_id,conid FROM " . DB_PREFIX . "program_queue WHERE id=" . $f['conid'];
			$sen = $this->db->query_first($sql);
			if($sen)
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 AND state=1 AND id=" . intval($this->input['server_id']);
				$fServer = $this->db->query_first($sql);
				include_once (ROOT_PATH . 'lib/class/curl.class.php');
				if($fServer)
				{
					$this->curl = new curl($fServer['host'] . ':' . $fServer['port'] , $fServer['dir']);
				}
				else
				{
					$this->errorOutput('录制服务地址不存在！');
				}
				$this->curl->mPostContentType('string');
				$this->curl->setSubmitType('get');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				if(!defined('IS_WOZA') || !IS_WOZA)
				{
					$this->curl->addRequestData('action', 'DELETE');
				}
				else
				{
					$this->curl->addRequestData('action', 'delete');				
				}
				$this->curl->addRequestData('id', $sen['conid']);
				$record_xml = $this->curl->request('');
				$record_array = xml2Array($record_xml);
				if($record_array['result'])
				{
					$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE id IN (" . $sen['log_id'] . ")";
					$this->db->query($sql);
					$sql = "DELETE FROM " . DB_PREFIX . "program_queue WHERE id=" . $sen['id'];
					$this->db->query($sql);
				}
			}					
		}
		
		if($f['conid'] && TIMENOW > $f['start_time'] && TIMENOW < ($f['start_time']+$f['toff']))
		{				
			$sql = "SELECT id,log_id,conid FROM " . DB_PREFIX . "program_queue WHERE id=" . $f['conid'];
			$sen = $this->db->query_first($sql);
			if($sen)
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 AND state=1 AND id=" . intval($this->input['server_id']);
				$fServer = $this->db->query_first($sql);
				include_once (ROOT_PATH . 'lib/class/curl.class.php');
				if($fServer)
				{
					$this->curl = new curl($fServer['host'] . ':' . $fServer['port'] , $fServer['dir']);
				}
				else
				{
					$this->errorOutput('录制服务地址不存在！');
				}
				$this->curl->mPostContentType('string');
				$this->curl->setSubmitType('get');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				if(!defined('IS_WOZA') || !IS_WOZA)
				{
					$this->curl->addRequestData('action', 'DELETE');
				}
				else
				{
					$this->curl->addRequestData('action', 'delete');				
				}
				$this->curl->addRequestData('id', $sen['conid']);
				$record_xml = $this->curl->request('');
				$record_array = xml2Array($record_xml);
				if($record_array['result'])
				{
					$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE id IN (" . $sen['log_id'] . ")";
					$this->db->query($sql);
					$sql = "DELETE FROM " . DB_PREFIX . "program_queue WHERE id=" . $sen['id'];
					$this->db->query($sql);
				}
			}
		}	
				
		$sql = "SELECT * FROM  " . DB_PREFIX . "program_record WHERE id=" . $id;
		$fo = $this->db->query_first($sql);
		
		if($fo['program_id'] || $fo['plan_id'])
		{
			$data = array(
				'column_id' => urldecode($this->input['column_id']) ? urldecode($this->input['column_id']) : '',
				'column_name' => urldecode($this->input['column_name']) ? urldecode($this->input['column_name']) : '',
				'is_mark' => $this->input['is_mark'],
				'force_codec' => $this->input['force_codec'] ? 1 : 0,
				'audit_auto' => $this->input['audit_auto'],
				'server_id' => intval($this->input['server_id']),
				'program_id' => intval($this->input['program_id']) ? intval($this->input['program_id']) : 0,
				'plan_id' => intval($this->input['plan_id']) ? intval($this->input['plan_id']) : 0,
			);
			$this->obj->update_from_program($id,$data);
		}
		else
		{
			if(!$this->input['channel_id'] || !$this->input['start_time'])
			{
				$this->errorOutput(OBJECT_NULL);
			}
			
			$data = array(
				'column_id' => urldecode($this->input['column_id']) ? urldecode($this->input['column_id']) : '',
				'column_name' => urldecode($this->input['column_name']) ? urldecode($this->input['column_name']) : '',
				'is_mark' => $this->input['is_mark'],
				'force_codec' => $this->input['force_codec'] ? 1 : 0,
				'audit_auto' => $this->input['audit_auto'],
				'start_time' => strtotime(trim(urldecode($this->input['dates'])) . ' ' . trim(urldecode($this->input['start_time']))),
				'end_time' => strtotime(trim(urldecode($this->input['dates'])) . ' ' . trim(urldecode($this->input['end_time']))),
				'week_day' => $this->input['week_day'],
				'channel_id' => intval($this->input['channel_id']),
				'title' => $this->input['title'] ? trim($this->input['title']) : '',
				'item' => intval($this->input['item']),
				'server_id' => intval($this->input['server_id']),
				'program_id' => intval($this->input['program_id']) ? intval($this->input['program_id']) : 0,
				'plan_id' => intval($this->input['plan_id']) ? intval($this->input['plan_id']) : 0,
			);
			$this->obj->update_from_self($id,$data);			
		}
		
		$this->setXmlNode('program_record', 'info');
		$this->addLogs('update' , $fo , $info);
		$this->addItem($ret);
		$this->output();
	}


	//来自节目单和来自计划的不能删除
	public function delete()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		else 
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 AND state=1";
			$q = $this->db->query($sql);
			$server_config = array();
			while($row = $this->db->fetch_array($q))
			{
				$server_config[$row['id']] = $row;
			}
			
			$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE id IN (" . $id . ")";
			$q = $this->db->query($sql);
			include_once (ROOT_PATH . 'lib/class/curl.class.php');
			$id_success = array();
			$delete_id = $space = "";
			$ret = array();
			while($row = $this->db->fetch_array($q))
			{
				if($row['conid'] && $row['start_time'] > (TIMENOW+5)) //未进行录制，可以删除 5秒的冗余
				{
					$sql = "SELECT * FROM " . DB_PREFIX . "program_queue WHERE id=" . $row['conid'];
					$tmp_first = $this->db->query_first($sql);
					if(!empty($tmp_first))
					{
						$this->curl = new curl($server_config[$row['server_id']]['host'] . ':' . $server_config[$row['server_id']]['port'], $server_config[$row['server_id']]['dir']);
						$this->curl->mPostContentType('string');
						$this->curl->setSubmitType('get');
						$this->curl->setReturnFormat('json');
						$this->curl->initPostData();
						if(!defined('IS_WOZA') || !IS_WOZA)
						{
							$this->curl->addRequestData('action', 'DELETE');
						}
						else
						{
							$this->curl->addRequestData('action', 'delete');				
						}
						$this->curl->addRequestData('id', $tmp_first['conid']);
						$record_xml = $this->curl->request('');
						$record_array = xml2Array($record_xml);
						if($record_array['result'])
						{
							$id_success[] = $row['id'];
						}
					}
				}
				$delete_id .= $space . $row['id'];
				$space = ',';
				$ret[] = $row;
			}
			
			if($delete_id)
			{		
				$sql = "DELETE FROM " . DB_PREFIX . "program_record WHERE id IN (" . $delete_id . ")";
				$this->db->query($sql);
				$sql = "DELETE FROM " . DB_PREFIX . "program_record_relation WHERE record_id IN (" . $delete_id . ")";
				$this->db->query($sql);
				$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE record_id IN (" . $delete_id . ")";
				$this->db->query($sql);
				$sql = "DELETE FROM " . DB_PREFIX . "program_queue WHERE record_id IN (" . $delete_id . ")";
				$this->db->query($sql);
			}
			$this->addLogs('delete' , $ret , '');
		}
		
		$re = array();
		$id_array = explode(',',$id);
		$delid_array = explode(',',$delete_id);
		if(count($id_array) == count($delid_array))
		{
			$re['info'] = '';
		}
		else
		{
			$re['info'] = '来自于节目单或者节目单计划的录制内容无法删除！';
		}
		
		$re['id'] = $delete_id;
		
		$this->setXmlNode('program_record','info');
		$this->addItem($re);
		$this->output();
	}
	
	public function check_record()
	{
		$channel_id = intval($this->input['channel_id'] ? $this->input['channel_id'] : 0);
		$start_time = intval($this->input['start_time']) ? intval($this->input['start_time']) : 0;
		$end_time = intval($this->input['end_time']) ? intval($this->input['end_time']) : 0;
		$ret = $this->obj->check_record($channel_id,$start_time,$end_time);
		$this->addItem($ret);
		$this->output();
	}
	
	public function update_record_state()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('未传入录制ID');
		}
		$this->obj->update_record_state($id);
			
		$this->addItem(array('id' => $id));
		$this->output();
	}
	
	public function update_program()
	{
		$record_id = trim($this->input['record_id'] ? $this->input['record_id'] : 0);
		$program_id = intval($this->input['program_id'] ? $this->input['program_id'] : 0);
		if(!empty($record_id))
		{
			if(!empty($program_id))
			{
				$sql = "UPDATE " . DB_PREFIX . "program_record SET program_id=" . $program_id . " WHERE id=" . $record_id;
			}
			else
			{
				$sql = "UPDATE " . DB_PREFIX . "program_record SET program_id=0 WHERE id IN(" . $record_id . ")";
			}
			$this->db->query($sql);
			$this->addItem(array('record_id' => $record_id));
			$this->output();
		}		
	}
	
	public function update_plan()
	{
		$record_id = trim($this->input['record_id'] ? $this->input['record_id'] : 0);
		$plan_id = intval($this->input['plan_id'] ? $this->input['plan_id'] : 0);
		if(!empty($record_id))
		{
			if(!empty($plan_id))
			{
				$sql = "UPDATE " . DB_PREFIX . "program_record SET plan_id=" . $plan_id . " WHERE id=" . $record_id;
			}
			else
			{
				$sql = "UPDATE " . DB_PREFIX . "program_record SET plan_id=0 WHERE id IN(" . $record_id . ")";
			}
			$this->db->query($sql);
			$this->addItem(array('record_id' => $record_id));
			$this->output();
		}
	}
}

$out = new programRecordUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>