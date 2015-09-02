<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_update.php 4728 2011-10-12 10:38:02Z lijiaying $
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
		
		$start_time = strtotime(trim(urldecode($this->input['dates'])) . ' ' . trim(urldecode($this->input['start_time'])));
		$end_time = strtotime(trim(urldecode($this->input['dates'])) . ' ' . trim(urldecode($this->input['end_time'])));
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
				$start_time =  strtotime($dates . ' ' . trim(urldecode($this->input['start_time'])));
				$end_time =  strtotime($dates . ' ' . trim(urldecode($this->input['end_time'])));
			}

			$week_day = $this->input['week_day'];
		}
		
		$sql = "SELECT channel_id, start_time, toff FROM " . DB_PREFIX . "program_record WHERE channel_id =" . intval($this->input['channel_id']) . " AND start_time <". $start_time ." ORDER BY start_time DESC LIMIT 1";
		$s_time = $this->db->query_first($sql);
		$sql = "SELECT channel_id, start_time FROM " . DB_PREFIX . "program_record WHERE channel_id =" . intval($this->input['channel_id']) . " AND start_time >". $end_time ." ORDER BY start_time ASC LIMIT 1";
		$e_time = $this->db->query_first($sql);

		$pre_end_time = $s_time['start_time'] + $s_time['toff'];
		$next_start_time = $e_time['start_time'];		
		
		$is_out = 0;
		if($end_time < TIMENOW)
		{
			$is_out = 1;
		}

		$columnid = (is_array($this->input['columnid']) && !empty($this->input['columnid'])) ? implode(',',$this->input['columnid']) : 0;
		
		$info = array(
				'channel_id' => $this->input['channel_id'],
				'start_time' => $start_time,
				'title' => urldecode($this->input['title']) ? urldecode($this->input['title']) : '',
				'toff' => $toff,
				'is_record' => 0,
				'week_day' => serialize($week_day),
				'item' => intval($this->input['item']),
				'columnid' => $columnid,
				'update_time' => TIMENOW,
				'is_mark' => $this->input['is_mark'],
				'force_codec' => $this->input['force_codec'] ? 1 : 0,
				'is_record' => $is_out,
				'is_out' => $is_out,
		);
		if(!$info['start_time'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
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
		$this->setXmlNode('program_record', 'info');
		$this->addItem($ret);
		$this->output();
		
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
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		else 
		{
			$sql = "DELETE FROM " . DB_PREFIX . "program_record WHERE id IN (" . $id . ")";
			$this->db->query($sql);
			$sql = "DELETE FROM " . DB_PREFIX . "program_record_relation WHERE record_id IN (" . $id . ")";
			$this->db->query();
		}
		
		$ret['id'] = $id;
		
		$this->setXmlNode('program_record','info');
		$this->addItem($ret);
		$this->output();
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