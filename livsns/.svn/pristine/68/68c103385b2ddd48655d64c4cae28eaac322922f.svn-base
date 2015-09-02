<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_update.php 4728 2011-10-12 10:38:02Z lijiaying $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_record');//模块标识
class programRecordUpdateApi extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function update()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
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
				include_once (ROOT_PATH . 'lib/class/curl.class.php');
				$this->curl = new curl($this->settings['mms']['record_server']['host'], $this->settings['mms']['record_server']['dir']);
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
		
		if(!defined('IS_WOZA') || !IS_WOZA)
		{
			if($f['conid'] && TIMENOW > $f['start_time'] && TIMENOW < ($f['start_time']+$f['toff']))
			{				
				$sql = "SELECT id,log_id,conid FROM " . DB_PREFIX . "program_queue WHERE id=" . $f['conid'];
				$sen = $this->db->query_first($sql);
				if($sen)
				{
					include_once (ROOT_PATH . 'lib/class/curl.class.php');
					$this->curl = new curl($this->settings['mms']['record_server']['host'], $this->settings['mms']['record_server']['dir']);
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
		}
				
		$sql = "SELECT * FROM  " . DB_PREFIX . "program_record WHERE id=" . $id;
		$fo = $this->db->query_first($sql);
		if($fo['program_id'] || $fo['plan_id'])
		{
			$this->update_from_program($id);
		}
		else
		{
			if(!$this->input['channel_id'] || !$this->input['start_time'])
			{
				$this->errorOutput(OBJECT_NULL);
			}
			$this->update_from_self($id);			
		}
		
		$this->setXmlNode('program_record', 'info');
		$this->addLogs('update' , $fo , $info);
		$this->addItem($ret);
		$this->output();
	}
	
	
	private function update_from_program($id)
	{
		$columnid = urldecode($this->input['column_id']) ? urldecode($this->input['column_id']) : '';
		if($this->mNeedCheckIn && !$this->prms['publish'] && $columnid)
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$column_name = urldecode($this->input['column_name']) ? urldecode($this->input['column_name']) : '';
		
		
		$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		$info = array(
			'columnid' => $columnid,
			'column_name' => $column_name,
			'update_time' => TIMENOW,
			'is_mark' => $this->input['is_mark'],
			'force_codec' => $this->input['force_codec'] ? 1 : 0,
			'audit_auto' => $this->input['audit_auto'],
		);
		
		$is_record = 0;//判断录制状态
		$is_out = 0;//判断是否超时
		if($f['week_day'])
		{
			$is_record = 0;
			if($f['start_time'] <= TIMENOW)//大于当前时间
			{
				$week_now = date('N',TIMENOW);
				$week_num = unserialize($f['week_day']);
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
				$start_time =  strtotime($dates . ' ' . date('H:i:s',$f['start_time']));
				$info['start_time'] = $start_time;
			}
		}
		else
		{//单天
			if($f['start_time'] > TIMENOW)//大于当前时间
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
		
		$info['is_out'] = $is_out;
		$info['is_record'] = $is_record;
		$info['conid'] = 0;
		
		$sql = "UPDATE " . DB_PREFIX . "program_record SET ";
		$space = "";
		$sql_extra = "";
		foreach($info as $key => $value)
		{
			if(isset($value))
			{
				$sql_extra .= $space . $key . "=" . "'" . $value . "'";
				$space = ",";
			}
		}
		if($sql_extra)
		{
			$sql .= $sql_extra . " WHERE id=" . $id;
			$this->db->query($sql);
		}
		return true;
	}
	
	private function update_from_self($id)
	{
		$columnid = urldecode($this->input['column_id']) ? urldecode($this->input['column_id']) : '';
		if($this->mNeedCheckIn && !$this->prms['publish'] && $columnid)
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$column_name = urldecode($this->input['column_name']) ? urldecode($this->input['column_name']) : '';

		$start_time = strtotime(trim(urldecode($this->input['dates'])) . ' ' . trim(urldecode($this->input['start_time'])));
		$end_time = strtotime(trim(urldecode($this->input['dates'])) . ' ' . trim(urldecode($this->input['end_time'])));

		$toff = $end_time - $start_time;
		if($toff < 0)
		{
			$toff = $toff + 24*3600;
		}

		if(empty($toff))
		{
			$this->errorOutput('录制时长不能为零！');
		}
		
		$is_record = 0;//判断录制状态
		$is_out = 0;//判断是否超时
		if(is_array($this->input['week_day']) && $this->input['week_day'])
		{
			$is_record = 0;
			if($start_time <= TIMENOW)//大于当前时间
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
		
		$info = array(
			'channel_id' => intval($this->input['channel_id']),
			'start_time' => $start_time,
			'title' => $this->input['title'] ? trim($this->input['title']) : '',
			'toff' => $toff,
			'week_day' => $week_day ? $week_day : '',
			'item' => intval($this->input['item']),
			'columnid' => $columnid,
			'column_name' => $column_name,
			'update_time' => TIMENOW,
			'is_mark' => $this->input['is_mark'],
			'force_codec' => $this->input['force_codec'] ? 1 : 0,
			'audit_auto' => $this->input['audit_auto'],
			'is_out' => $is_out,
			'is_record' => $is_record,
			'conid' => 0,
		);
		
		$sql = "UPDATE " . DB_PREFIX . "program_record SET ";
		$space = "";
		$sql_extra = "";
		foreach($info as $key => $value)
		{
			if(isset($value))
			{
				$sql_extra .= $space . $key . "=" . "'" . $value . "'";
				$space = ",";
			}
		}
		if($sql_extra)
		{
			$sql .= $sql_extra . " WHERE id=" . $id;
			$this->db->query($sql);
		}
		$this->insert_relation($this->input['channel_id'],$id,$start_time,$toff,serialize($week_day));
		return true;
	}
	
	private function insert_relation($channel_id,$record_id,$start_time,$toff,$week_day)
	{
		$start = date("H:i:s",$start_time);
		$end = date("H:i:s",$start_time+$toff);

		$sql = "DELETE FROM " . DB_PREFIX . "program_record_relation WHERE record_id=" . $record_id ;
		$this->db->query($sql);
		if(!$week_day)
		{
			$week_num = date('N',$start_time);

			$sql = "INSERT INTO " . DB_PREFIX . "program_record_relation SET record_id=" . $record_id . " ,channel_id=" . $channel_id . ", start_time='" . $start . "', end_time='" . $end . "', week_num=" . $week_num . ",num=0";
			$this->db->query($sql);
		}
		else
		{
			$week_day = unserialize($week_day);
			foreach($week_day as $k => $v)
			{
				$sql = "INSERT INTO " . DB_PREFIX . "program_record_relation SET record_id=" . $record_id . " ,channel_id=" . $channel_id . ", start_time='" . $start . "', end_time='" . $end . "', week_num=" . $v . ",num=1";
				$this->db->query($sql);
			}
		}
	}

	public function delete()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		else 
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE id IN (" . $id . ")";
			$q = $this->db->query($sql);
			include_once (ROOT_PATH . 'lib/class/curl.class.php');
			$this->curl = new curl($this->settings['mms']['record_server']['host'], $this->settings['mms']['record_server']['dir']);
			$id_success = array();
			$delete_id = $space = "";
			$ret = array();
			while($row = $this->db->fetch_array($q))
			{
				if(!$row['program_id'] && !$row['plan_id'])
				{
					if($row['conid'] && $row['start_time'] > (TIMENOW+5)) //未进行录制，可以删除 5秒的冗余
					{
						$sql = "SELECT * FROM " . DB_PREFIX . "program_queue WHERE id=" . $row['conid'];
						$tmp_first = $this->db->query_first($sql);
						if(!empty($tmp_first))
						{
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
	
	public function delete_all()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		else 
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE id IN (" . $id . ")";
			$q = $this->db->query($sql);
			include_once (ROOT_PATH . 'lib/class/curl.class.php');
			$this->curl = new curl($this->settings['mms']['record_server']['host'], $this->settings['mms']['record_server']['dir']);
			$id_success = array();
			$ret = array();
			while($row = $this->db->fetch_array($q))
			{
				if($row['conid'] && $row['start_time'] > (TIMENOW+5)) //未进行录制，可以删除 5秒的冗余
				{
					$sql = "SELECT * FROM " . DB_PREFIX . "program_queue WHERE id=" . $row['conid'];
					$tmp_first = $this->db->query_first($sql);
					if(!empty($tmp_first))
					{
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
				$ret[] = $row;
			}
			$sql = "DELETE FROM " . DB_PREFIX . "program_record WHERE id IN (" . $id . ")";
			$this->db->query($sql);
			$sql = "DELETE FROM " . DB_PREFIX . "program_record_relation WHERE record_id IN (" . $id . ")";
			$this->db->query($sql);
			$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE record_id IN (" . $id . ")";
			$this->db->query($sql);
			$sql = "DELETE FROM " . DB_PREFIX . "program_queue WHERE record_id IN (" . $id . ")";
			$this->db->query($sql);
			
			$this->addLogs('delete' , $ret , '');
		}
		
		$re = array();
		$re['id'] = $id;

		$this->setXmlNode('program_record','info');
		$this->addItem($re);
		$this->output();
	}
	
	public function create()
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
}

$out = new programRecordUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'update';
}
$out->$action();
?>