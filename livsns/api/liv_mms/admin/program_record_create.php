<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_create.php 4728 2011-10-12 10:38:02Z lijiaying $
***************************************************************************/
require('global.php');
class programRecordCreateApi extends BaseFrm
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
/*		if($start_time>$end_time)
		{
			$this->errorOutput('时间设置不正确！');
		}*/
		$channel_id = intval($this->input['channel_id']);
		$sql = "select id,save_time,live_delay from " . DB_PREFIX . "channel where id=".$channel_id; 

		$channel = $this->db->query_first($sql);
		if(!$channel['id'])
		{
			$this->errorOutput('该频道已经不存在！');
		}

		$start_time = strtotime(trim(urldecode($this->input['dates'])) . ' ' . trim(urldecode($this->input['start_time'])));
		$end_time = strtotime(trim(urldecode($this->input['dates'])) . ' ' . trim(urldecode($this->input['end_time'])));

		$is_out = 0;
		if($end_time < TIMENOW)
		{
			$is_out = 1;
		}

		$toff = $end_time - $start_time;
		if($toff < 0)
		{
			$toff = $toff + 24*3600;
		}
		if(is_array($this->input['week_day']) && $this->input['week_day'])
		{
			$week_now = date('N',TIMENOW);
			$week_num = $this->input['week_day'];
			if(in_array($week_now,$week_num))
			{
				$dates = date('Y-m-d',TIMENOW);
			}
			else
			{
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
			}
			$start_time =  strtotime($dates . ' ' . trim(urldecode($this->input['start_time'])));
			$end_time =  strtotime($dates . ' ' . trim(urldecode($this->input['end_time'])));

			$week_day = serialize($this->input['week_day']);
		}

		$sql = "SELECT channel_id, start_time, toff FROM " . DB_PREFIX . "program_record WHERE channel_id =" . $channel_id . " AND " . $start_time . "=start_time and " . $end_time . "=(start_time+toff) AND week_day='" . $week_day . "'";
		$f = $this->db->query_first($sql);
		if($f['channel_id'])
		{
			$this->errorOutput('此段时间已经有节目被录制！');
		}
		$columnid = (is_array($this->input['columnid']) && !empty($this->input['columnid'])) ? implode(',',$this->input['columnid']) : 0;

		$info = array(
				'channel_id' => $channel_id,
				'start_time' => $start_time,
				'title' => urldecode($this->input['title']) ? urldecode($this->input['title']) : '',
				'toff' => $toff,
				'week_day' => ($week_day ? $week_day : ''),
				'item' => intval($this->input['item']),
				'columnid' => $columnid,
				'create_time' => TIMENOW,
				'update_time' => TIMENOW,
				'is_mark' => $this->input['is_mark'],
				'ip' => hg_getip(),
				'force_codec' => $this->input['force_codec'] ? 1 : 0,
				'is_record' => $is_out,
				'is_out' => $is_out,
		);
		if(!$info['channel_id'] || !$info['start_time'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$createsql = "INSERT INTO " . DB_PREFIX . "program_record SET ";
		$space = "";
		foreach($info as $key => $value)
		{
			$createsql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($createsql);
		$ret = array();
		$ret['id'] = $this->db->insert_id();

		$this->insert_relation($channel_id,$ret['id'],$start_time,$toff,$week_day);
		
		$this->setXmlNode('program_record', 'info');
		$this->addItem($ret);
		$this->output();
	}

	private function insert_relation($channel_id,$record_id,$start_time,$toff,$week_day)
	{
		$start = date("H:i:s",$start_time);
		$end = date("H:i:s",$start_time+$toff);

		$sql = "DELETE FROM " . DB_PREFIX . "program_record_relation WHERE record_id=" . $record_id;
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
	

}

$out = new programRecordCreateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>