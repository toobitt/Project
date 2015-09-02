<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: topic.php 6173 2012-03-23 06:46:16Z repheal $
***************************************************************************/
require('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class topicApi extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	function show()
	{

		//所有频道信息
		$sql = "select id,logo,name from ". DB_PREFIX . "channel ORDER BY order_id DESC";
		$ch = $this->db->query($sql);
		$channel_info = array();
		while($row = $this->db->fetch_array($ch))
		{
			if(empty($channel_info))
			{
				$channel_info['default'] = $row;
			}
			$imgss = hg_get_images($row['logo'], UPLOAD_URL . CHANNEL_IMG_DIR, $this->settings['channel_img_size']);
			foreach($imgss as $key => $value)
			{
				$row[$key] = $value;
			}
			$channel_info[$row['id']] = $row;
		}
		$this->addItem_withkey('channel_info', $channel_info);
		$dates = $this->input['dates'] ? urldecode($this->input['dates']) : date("Y-m-d");
		$this->addItem_withkey('date', $dates);
		if(!$this->input['channel_id'])
		{
			$sql = "SELECT id FROM " . DB_PREFIX . "channel WHERE 1 ORDER BY order_id DESC LIMIT 1";
			$f = $this->db->query_first($sql);
			if(!empty($f))
			{
				$this->input['channel_id'] = $f['id'];
			}
			else
			{
				$this->errorOutput('未传入频道ID');
			}
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "topic WHERE channel_id=" . $this->input['channel_id'] . " and dates='" . $dates . "' ORDER BY start_time ASC ";
		$q = $this->db->query($sql);
		$topic = array();
		$last_end = '';
		$start = strtotime($dates." 00:00:00");
		$end = strtotime($dates." 23:59:59");
		$com_time = 0;
		while($row = $this->db->fetch_array($q))
		{
			if(!$com_time && $row['start_time'] > $start)//头
			{
				$topic[] = $this->getInfo($start,$row['start_time'],$dates);
			}

			if($com_time && $com_time != $row['start_time'])//中
			{				
				$topic[] = $this->getInfo($com_time,$row['start_time'],$dates); 
			}

			$com_time = $row['end_time'];
			$row['start'] = date("H:i:s",$row['start_time']);
			$row['end'] = date("H:i:s",$row['end_time']);
			$topic[] = $row;
		}
		
		if($com_time && $com_time < $end)//中
		{		
			$topic[] = $this->getInfo($com_time,$end,$dates);
		}

		if(empty($topic))
		{
			$topic[] = $this->getInfo($start,strtotime($dates." 08:00:00"),$dates,1);
			$topic[] = $this->getInfo(strtotime($dates." 08:00:00"),$end,$dates);
		}

		$this->addItem_withkey('topic', $topic);
		$this->output();
	}

	function getInfo($start,$end,$dates,$type=0)
	{
		//$type 存在表示space color 存在，否 null存在
		$info = array(
			'id' => hg_rand_num(10),
			'name' => '新话题',
			'dates' => $dates,
			'start' => date('H:i:s',$start),
			'end' => date('H:i:s',$end),
			'channel_id' => $this->input['channel_id'],
		);
		if($type)
		{
			$info['color'] = '#DF6564,#FEF2F2';
			$info['space'] = 1;
		}
		else
		{
			$info['null'] = 1;
		}
		return $info;
	}

	function get_condition()
	{
		$cond = '';

	}

	public function count()
	{
		
	}
	public function detail()
	{
		
	}
	public function index()
	{
		
	}
	
}
$out = new topicApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>