<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_list_create.php 5132 2011-11-23 05:17:53Z develop_tong $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . "global.php");
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','program_record_log');
class programRecordLogUpdateApi extends outerUpdateBase
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
		$id = $this->obj->create($info);
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
		$ret = $this->obj->update($info);
		$this->addItem($ret);		
		$this->output();
	}
	
	public function delete_log()
	{
		$id = $this->input['id'] ? $this->input['id'] : 0;
		$record_id = $this->input['record_id'] ? $this->input['record_id'] : 0;
		if(empty($id) || empty($record_id))
		{
			$this->errorOutput('日志ID不存在');
		}
		$ret = $this->obj->delete_log($record_id,$id);
		if($ret)
		{
			$this->addItem(array('sucess' => 1));
			$this->output();
		}
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