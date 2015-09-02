<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_list_create.php 5132 2011-11-23 05:17:53Z develop_tong $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_record');//模块标识
class programRecordLogUpdateApi extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		if($this->mNeedCheckIn && !$this->prms['manage_log'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 添加收录
	 * Enter description here ...
	 */
	public function create()
	{
		$info = array(
			'record_id' => intval($this->input['id']),
			'channel_id' => intval($this->input['channel_id']),
			'title' => trim(rawurldecode($this->input['title'])),
			'start_time' => intval($this->input['start_time']),
			'week_day' => urldecode(trim($this->input['week_day'])),
			'toff' => intval($this->input['toff']),
			'item' => intval($this->input['item']),
			'columnid' => trim($this->input['columnid']),
			'column_name' => trim($this->input['column_name']),
			'state' => intval($this->input['state']),
			'text' => trim($this->input['text']),
			'auto' => 1,
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => trim($this->input['ip']),
		);
		
		$sql = "INSERT INTO " . DB_PREFIX . "program_record_log SET ";
		$space = '';
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space = ',';
		}
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$this->addItem($id);		
		$this->output();

	}
	
	public function update()
	{
		$info = array(
			'text' => urldecode(trim($this->input['text'])),
			'state' => intval($this->input['state']),
			'content_id' => intval($this->input['content_id']),
			'conid' => intval($this->input['conid']),
		);
		if(empty($info['content_id']))
		{
			$this->addItem(array('error' => '未传入录制ID'));		
			$this->output();
		}
		
		$sql = "SELECT conid FROM " . DB_PREFIX . "program_record WHERE id=" . $info['content_id'];
		$f = $this->db->query_first($sql);
		if($f && $f['conid'])
		{
			$sql = "SELECT log_id FROM " . DB_PREFIX . "program_queue WHERE id=" . $f['conid'];
			$sen = $this->db->query_first($sql);
			if($sen && $sen['log_id'])
			{
				$sql = "UPDATE " . DB_PREFIX . "program_record_log SET text='" . $info['text'] . "',state=" . $info['state'] . " WHERE id=" . $sen['log_id'];
				$this->db->query($sql);
				$this->addItem($sen['log_id']);		
				$this->output();
			}
			else
			{
				$this->addItem(array('error' => '日志不存在'));		
				$this->output();
			}
		}
		else
		{
			$this->addItem(array('error' => '录制无此对象'));		
			$this->output();
		}
	}
	
	
	//根据日志ID删除日志
	public function delete_log()
	{
		$id = $this->input['id'] ? $this->input['id'] : 0;
		$record_id = $this->input['record_id'] ? $this->input['record_id'] : 0;
		if(empty($id) || empty($record_id))
		{
			$this->errorOutput('日志ID不存在');
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE id=" . $record_id;
		$row = $this->db->query_first($sql);
			
		if($row['start_time'] <= TIMENOW && TIMENOW <= ($row['start_time']+$row['toff']))
		{
			if(defined('IS_WOZA') && IS_WOZA)
			{
				$this->errorOutput('录制已经开始，无法删除');//woza录制无法删除正在录制中的。。。。
			}
		}
		
		//删除录制日志
		$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE id=" . $id;
		$this->db->query($sql);
		
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
			$this->db->query($sql_update);
		}
		else
		{
			$sql_update = "UPDATE " . DB_PREFIX . "program_record SET is_record=2 WHERE id=" . $row['id'];
			$this->db->query($sql_update);
		}
		
		if($row['conid'] && $row['start_time'] > (TIMENOW+5))//只删除未开始录制的
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "program_queue WHERE id=" . $row['conid'];
			$tmp_first = $this->db->query_first($sql);
			if(!empty($tmp_first))
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
				$this->curl->addRequestData('id', $tmp_first['conid']);
				$record_xml = $this->curl->request('');
				$record_array = xml2Array($record_xml);
				$sql = "DELETE FROM " . DB_PREFIX . "program_queue WHERE record_id IN (" . $row['id'] . ")";
				$this->db->query($sql);
			}
		}
		
		$this->addItem(array('sucess' => 1));
		$this->output();
	}
	public function delete()
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

$out = new programRecordLogUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>