<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_list_create.php 5132 2011-11-23 05:17:53Z develop_tong $
***************************************************************************/
require('global.php');
class programRecordListCreateApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
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
		$start_time = strtotime(trim(urldecode($this->input['start_time'])));
		$end_time = strtotime(trim(urldecode($this->input['end_time'])));
		if($start_time>$end_time)
		{
			$this->errorOutput('时间设置不正确！');
		}
		$channel_id = intval($this->input['channel_id']);
		$sql = "select id,name,save_time,live_delay from " . DB_PREFIX . "channel where id=".$channel_id; 

		$channel = $this->db->query_first($sql);
		if(!$channel_id)
		{
			$this->errorOutput('该频道已经不存在！');
		}
		
		if($start_time < (TIMENOW-(($channel['save_time']*3600)-($channel['live_delay']*60))))
		{
			$this->errorOutput('此条录制已超过回看时间！');
		}

		if($end_time > TIMENOW)
		{
			$this->errorOutput('自动录制节目的结束时间必须小于当前时间！');
		}

		$toff = $end_time - $start_time;
/*
		$sql = "SELECT channel_id, start_time, toff FROM " . DB_PREFIX . "program_record_log WHERE channel_id =" . $channel_id . " AND (start_time+toff) < " . TIMENOW;
		$f = $this->db->query_first($sql);
		if($f['channel_id'])
		{
			$this->errorOutput('此段时间已经有节目被录制！');
		}
*/
		//所属栏目
		$sql = "select sort_name from " . DB_PREFIX . "vod_sort WHERE id=" . intval($this->input['item']);
		$sen = $this->db->query_first($sql);

		if(!$sen['sort_name'])
		{
			$this->errorOutput('所属栏目已经不存在！');
		}

		$info = array(
				'channel_id' => $channel_id,
				'channel_name' => $channel['name'],
				'program_name' => '精彩节目',
				'start_time' => $start_time,
				'toff' => $toff,
				'item' => intval($this->input['item']),
				'sortname' => $sen['sort_name'],
				'create_time' => TIMENOW,
				'update_time' => TIMENOW,
				'ip' => hg_getip(),
		);
		if(!$info['channel_id'] || !$info['start_time'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$createsql = "INSERT INTO " . DB_PREFIX . "program_record_log SET ";
		$space = "";
		foreach($info as $key => $value)
		{
			$createsql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($createsql);
		$ret = array();
		$ret['id'] = $this->db->insert_id();
		
		$this->setXmlNode('program_record', 'info');
		$this->addItem($ret);
		$this->output();
	}
	

}

$out = new programRecordListCreateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>