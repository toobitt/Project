<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: topic_update.php 6173 2012-03-23 06:46:16Z repheal $
***************************************************************************/
require('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class topicUpdateApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function create()
	{
		$dates = $this->input['dates'] ? urldecode($this->input['dates']) : date("Y-m-d");
		$is_null = array(
			array(
			'value' => urldecode($this->input['name']),	
			'tips' => '话题名称不为空！',	
			),	
			array(
			'value' => $this->input['channel_id'],	
			'tips' => '请选择所属频道！',	
			),	
			array(
			'value' => $this->input['start_time'],	
			'tips' => '请传入开始时间！',	
			),	
			array(
			'value' => $this->input['end_time'],	
			'tips' => '请传入结束时间！',	
			),	
			array(
			'value' =>  strtotime($dates . " " . urldecode($this->input['start_time'])) >= strtotime($dates . " " . urldecode($this->input['end_time'])) ? 0 : 1,	
			'tips' => '结束时间必须大于开始时间',	
			),	
		);
		$this->is_empty($is_null);

		$info = array(
			'name' => urldecode($this->input['name']),	
			'channel_id' => $this->input['channel_id'],	
			'dates' => $dates,
			'start_time' => strtotime($dates . " " . urldecode($this->input['start_time'])),
			'end_time' => strtotime($dates . " " . urldecode($this->input['end_time'])),
			'create_time' => TIMENOW,
			'ip' => hg_getip(),
		);
		$sql = "INSERT INTO " . DB_PREFIX . "topic SET ";
		$space = '';
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space = ',';
		}
		$this->db->query($sql);
		$info['id'] = $this->db->insert_id();
		$this->addItem($info);
		$this->output();
	}
	
	function update()
	{
	//	file_put_contents('1.php',json_encode($this->input));
		$dates = $this->input['dates'] ? urldecode($this->input['dates']) : date("Y-m-d");
		$is_null = array(	
			array(
			'value' => $this->input['channel_id'],	
			'tips' => '请选择所属频道！',	
			),
			array(
			'value' => $this->input['dates'],	
			'tips' => '请选择日期！',	
			),
		);
		$this->is_empty($is_null);
		$arr = array(
			'new'=>$this->input['new'],
			'checke'=>$this->input['checke'],
			'start_time'=>$this->input['start_time'],
			'name'=>$this->input['name'],
			'end_time'=>$this->input['end_time'],
		);
		foreach($arr as $key => $value)
		{
			if(empty($value))
			{
				unset($arr[$key]);
			}
		}
		$ids = $spa = '';
		foreach($arr['start_time'] as $key => $value)
		{
			$pid = $key;
			if($arr['checke'][$pid])
			{
				$info = array(
						'id' => $pid,
						'color' => urldecode($value),
						'start_time' => strtotime(urldecode($dates." ".$arr['start_time'][$key])),
						'name' => urldecode($arr['name'][$key]),
						'end_time' => strtotime(urldecode($dates." ".$arr['end_time'][$key])),
						'new' => urldecode($arr['new'][$key]),
					);

				if($info['new'])
				{
					$creates = array(
						'channel_id' => $this->input['channel_id'],
						'start_time' => $info['start_time'],
						'end_time' => $info['end_time'],
						'name' => $info['name'],
						'dates' => $dates,
						'create_time' => TIMENOW,
						'ip' => hg_getip(),
					);
					$sql = "INSERT INTO " . DB_PREFIX . "topic SET ";
					$space = "";
					foreach($creates as $k => $v)
					{
						$sql .= $space . $k . "=" . "'" . $v . "'";
						$space = ",";
					}
					$this->db->query($sql);
					$info['id'] = $this->db->insert_id();
					$pid = $info['id'];
				}
				else
				{
					$sql = "UPDATE " . DB_PREFIX . "topic SET start_time=" . $info['start_time'] . ",name='" . $info['name'] . "',end_time=" . $info['end_time'] . ",dates='" . $dates . "' where id=" . $info['id'];
					
					//file_put_contents('2.php',$sql);
					$this->db->query($sql);
				}
			}
			$ids .= $spa . $pid;
			$spa = ',';
		}
		
		$sql = "DELETE FROM " . DB_PREFIX . "topic WHERE channel_id = " . $this->input['channel_id'] . " AND id NOT IN(" . $ids . ") and dates='" . $dates . "'";
		$this->db->query($sql);
		$info = $this->get_topic($dates);
		$this->addItem($info);
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

	private function is_empty($array)
	{
		foreach($array as $k => $v)
		{
			if(empty($v['value']))
			{
				$this->errorOutput($v['tips']);
			}
		}
	}

	private function get_topic($dates)
	{
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
		return $topic;
	}

	function unknow()
	{
		$this->errorOutput('该方法不存在！');
	}
}
$out = new topicUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>