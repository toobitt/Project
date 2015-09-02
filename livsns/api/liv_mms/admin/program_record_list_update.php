<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_list_update.php 5186 2011-11-29 09:24:34Z repheal $
***************************************************************************/
require('global.php');
class programRecordUpdateApi extends BaseFrm
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
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$start_time = strtotime(trim(urldecode($this->input['start_time'])));
		$end_time = strtotime(trim(urldecode($this->input['end_time'])));
		$toff = $end_time - $start_time;
		
		$sql = "SELECT channel_id, start_time, toff FROM " . DB_PREFIX . "program_record_log WHERE channel_id =" . intval($this->input['channel_id']) . " AND start_time <". $start_time ." ORDER BY start_time DESC LIMIT 1";
		$s_time = $this->db->query_first($sql);
		$sql = "SELECT channel_id, start_time FROM " . DB_PREFIX . "program_record_log WHERE channel_id =" . intval($this->input['channel_id']) . " AND start_time >". $end_time ." ORDER BY start_time ASC LIMIT 1";
		$e_time = $this->db->query_first($sql);

		$pre_end_time = $s_time['start_time'] + $s_time['toff'];
		$next_start_time = $e_time['start_time'];
		
		/*if($pre_end_time > $start_time)
		{
			$this->errorOutput('此段时间内已经有节目被录播！');
		}
		if($end_time > $next_start_time)
		{
			$this->errorOutput('此段时间内已经有节目被录播');
		}*/
		if(is_array($this->input['week_day']) && $this->input['week_day'])
		{
			$week_day = $this->input['week_day'];
		}
		
		$info = array(
				'start_time' => $start_time,
				'toff' => $toff,
				'week_day' => serialize($week_day),
				'item' => intval($this->input['item']),
				'update_time' => TIMENOW
		);
		if(!$info['start_time'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sql = "UPDATE " . DB_PREFIX . "program_record_log SET ";
		$space = "";
		$sql_extra = "";
		foreach($info as $key => $value)
		{
			if($value)
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
		$ret['id'] = $this->db->insert_id();
		$this->setXmlNode('program_record', 'info');
		$this->addItem($ret);
		$this->output();
		
	}

	public function delete()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		else 
		{
			$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE id IN (" . $id . ")";
			$this->db->query($sql);
		}
		
		$ret['id'] = $id;
		
		$this->setXmlNode('program_record','info');
		$this->addItem($ret);
		$this->output();
	}

	public function record()
	{
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		global $gGlobalConfig;
		$this->curl = new curl($gGlobalConfig['vodapi']['host'], $gGlobalConfig['vodapi']['dir'], $gGlobalConfig['vodapi']['token']);

		if(!$this->input['id'])
		{
			$this->errorOutput('你录个鬼啊？！什么都没有');
		}
		$sql = "select * from " . DB_PREFIX . "program_record_log where id=".$this->input['id'];
		$row = $this->db->query_first($sql);

		$sql = "select id,code,name,save_time,stream_info_all from " . DB_PREFIX . "channel where 1 and id=" . $row['channel_id']; 
		$channel = $this->db->query_first($sql);

		
		$sql = "select other_info  from " . DB_PREFIX . "stream where 1 "; 
		$q = $this->db->query($sql);
		$stream = array();
		while($r = $this->db->fetch_array($q))
		{
			$arr = unserialize($r['other_info']);
			$stream[$arr[0]['name']] = $arr[0]['bitrate'];
		}

		$ret = array();
		$ret['channel_id'] = $row['channel_id'];
		$sql = "SELECT theme,subtopic FROM " . DB_PREFIX . "program WHERE id=" . $row['program_id'];
		$f = $this->db->query_first($sql);
		if(is_array($f))
		{
			$ret['program'] = $f['subtopic'] ? ($f['theme'].':'.$f['subtopic']) : $f['theme'];
		}
		else
		{
			$ret['program'] = '精彩节目';
		}
		$ret['starttime'] = $row['start_time'];
		$ret['endtime'] = $row['start_time']+$row['toff'];
		$up_name = unserialize($channel['stream_info_all']);
		$arr = array();
		foreach($up_name as $k => $v)
		{
			$arr[] = array('code'=>$v,'bit'=>$stream[$v]);
		}
		$code = max($arr);
		$ret['stream'] = hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $channel['code'], 'stream_name' => $code['code']), 'internal');
		$ret['save_time'] = $channel['save_time'];
		$ret['vod_sort_id'] = $row['item'];
		$ret['delay_time'] = $channel['live_delay'] * 60;
		if (($ret['endtime'] + $ret['delay_time']) > TIMENOW)
		{
			$this->errorOutput('超出延时！');
		}

		//file_put_contents('2.php',json_encode($ret));
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		
		foreach($ret as $key => $value)
		{
			$this->curl->addRequestData($key, $value);
		}
		$ret = $this->curl->request('record.php');
		//file_put_contents('1.php',json_encode($ret));
		if($ret['vodid'])
		{
			$sql = "update " . DB_PREFIX . "program_record_log SET vod_id='" . $ret['vodid'] . "',state=1 where channel_id=" .  $row['channel_id'] . " and (start_time+toff)<" . TIMENOW . " and vod_id='0' and state=0";
			$this->db->query($sql);
			$this->setXmlNode('program_record','info');
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('收录失败！');
		}
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