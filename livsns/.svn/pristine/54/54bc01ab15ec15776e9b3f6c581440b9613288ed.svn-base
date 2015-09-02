<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: change_plan.class.php 
***************************************************************************/
define('MOD_UNIQUEID','change_plan_m');//模块标识
class changePlan extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->mDates = date('2012-01-01');
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show($condition)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "change_plan_relation r left join " . DB_PREFIX . "change_plan p on p.id=r.plan_id WHERE 1 " . $condition . " ORDER BY start_time ASC";
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['start'] = strtotime(date("H:i:s",$row['start_time']));
			$row['end'] = strtotime(date("H:i:s",$row['start_time']+$row['toff']));
			$info[] = $row;
		}
		return $info;
	}

	function create()
	{
		$dates = $this->mDates;
		$uri = $this->set_chg_uris(trim($this->input['channel2_ids']),trim($this->input['type']));
		$toff = strtotime($dates . " " . urldecode($this->input['plan_end_time'])) - strtotime($dates . " " . urldecode($this->input['plan_start_time']));
		if($this->input['program_start_time'])
		{
			$program_start_time = strtotime(urldecode($this->input['program_start_time']));
			$uri .= $program_start_time . '000,' . ($program_start_time + $toff) . '000';
		}
		$today = date('N', TIMENOW);
		$week_days = $this->input['week_d'];
		if($week_days <= $today)
		{
			$week_days = $today;
		}
		$info = array(
			'channel_id' => trim($this->input['channel_id']),
			'type' => trim($this->input['type']),
			'stream_uri' => $uri,
			'channel2_id' => trim($this->input['channel2_ids']),
			'channel2_name' => urldecode($this->input['channel2_name']),
			'start_time' => strtotime($dates . " " . urldecode($this->input['plan_start_time'])),
			'toff' => $toff,
			'program_start_time' => $program_start_time ? $program_start_time : 0,
			'week_days' => $week_days,
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
		);
		$sql_extra = $space = "";
		foreach($info as $key => $value)
		{
			if($value)
			{
				$sql_extra .= $space . $key . "='" . $value . "'";
				$space = ",";
			}
		}
		if($sql_extra && $this->input['week_day'])
		{
			$sql = "INSERT INTO " . DB_PREFIX . "change_plan SET ".$sql_extra;
			$this->db->query($sql);
			$info['id'] = $this->db->insert_id();
			if($info['id'])
			{
				$week_num = $this->input['week_day'];
				if(!empty($week_num))
				{
					$sql_extra = $space = '';
					foreach($week_num as $k => $v)
					{
						$sql_extra .= $space . '(' . $info['id'] . ',' . $v . ')';
						$space = ',';
					}
					$sql = "INSERT INTO " . DB_PREFIX . "change_plan_relation(plan_id,week_num) value".$sql_extra;
					$this->db->query($sql);
				}
			}
			return $info;
		}
		return false;
	}

	function update()
	{	
		if($this->input['id'])
		{
			$sql = "SELECT week_days,program_start_time FROM " . DB_PREFIX . "change_plan WHERE id=" . intval($this->input['id']);
			$change_plan = $this->db->query_first($sql);
		}
		$dates = $this->mDates;
		$uri = $this->set_chg_uris(trim($this->input['channel2_ids']),trim($this->input['type']));
		$toff = strtotime($dates . " " . urldecode($this->input['plan_end_time'])) - strtotime($dates . " " . urldecode($this->input['plan_start_time']));
		if(trim($this->input['program_start_time']))
		{
			$program_start_time = strtotime(urldecode($this->input['program_start_time']));
			$uri .= $program_start_time . '000,' . ($program_start_time + $toff) . '000';
		}
		else
		{
			$program_start_time = '00';
		}
		if(!$change_plan['program_start_time'] && $program_start_time)
		{
			$today = date('N', TIMENOW);
			$week_days = $this->input['week_d'];
			if($week_days <= $today)
			{
				$week_days = $today;
			}
			$info = array(
				'channel_id' => trim($this->input['channel_id']),
				'type' => trim($this->input['type']),
				'stream_uri' => $uri,
				'channel2_id' => trim($this->input['channel2_ids']),
				'channel2_name' => urldecode($this->input['channel2_name']),
				'start_time' => strtotime($dates . " " . urldecode($this->input['plan_start_time'])),
				'toff' => $toff,
				'week_days' => $week_days,
				'program_start_time' => $program_start_time,
				'update_time' => TIMENOW,
				'ip' => hg_getip(),
			);
		}
		else
		{
			$info = array(
				'channel_id' => trim($this->input['channel_id']),
				'type' => trim($this->input['type']),
				'stream_uri' => $uri,
				'channel2_id' => trim($this->input['channel2_ids']),
				'channel2_name' => urldecode($this->input['channel2_name']),
				'start_time' => strtotime($dates . " " . urldecode($this->input['plan_start_time'])),
				'toff' => $toff,
				'program_start_time' => $program_start_time,
				'update_time' => TIMENOW,
				'ip' => hg_getip(),
			);
		}
		$sql_extra = $space = "";
		foreach($info as $key => $value)
		{
			if($value)
			{
				$sql_extra .= $space . $key . "='" . $value . "'";
				$space = ",";
			}
		}
		if($sql_extra && $this->input['week_day'])
		{
			$sql = "UPDATE " . DB_PREFIX . "change_plan SET " . $sql_extra . " WHERE 1 AND id=" . $this->input['id'];
			$this->db->query($sql);
			$info['id'] =  $this->input['id'];
			if($info['id'])
			{
				$sql = "DELETE FROM " . DB_PREFIX . "change_plan_relation where plan_id=" . $info['id'];
				$this->db->query($sql);
				$week_num = $this->input['week_day'];
				if(is_array($week_num))
				{
					$sql_extra = $space = '';
					foreach($week_num as $k => $v)
					{
						$sql_extra .= $space . '(' . $info['id'] . ',' . $v . ')';
						$space = ',';
					}
					$sql = "INSERT INTO " . DB_PREFIX . "change_plan_relation(plan_id,week_num) value".$sql_extra;
					$this->db->query($sql);
				}
			}
			return $info;
		}
		return false;		
	}
	

	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "change_plan_relation r left join " . DB_PREFIX . "change_plan p on p.id=r.plan_id WHERE 1 ";
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);
		return $r;
	}

	public function delete()
	{
		$id = trim($this->input['id']);
		
		$sql = "select * from " . DB_PREFIX . "change_plan where id = " . $id;
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['channel2_name'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['change_plan'] = $row;
		}
		$sql = "select * from " . DB_PREFIX . "change_plan_relation where id = " . $id;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['id']]['content']['change_plan_relation'][] = $row;
		}
		if($data2)
		{
			//放入回收站
			foreach($data2 as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
				
			}	
			//放入回收站结束
		}
		
		if($res['sucess'])
		{
			$sql = "select * from " . DB_PREFIX . "change_plan WHERE id=" . $id;
			$f = $this->db->query_first($sql);
			if($f['id'])
			{
				$sql = "DELETE FROM " . DB_PREFIX . "change_plan WHERE id=".$id;
				$r = $this->db->query($sql);
				$sql = "DELETE FROM " . DB_PREFIX . "change_plan_relation WHERE plan_id=".$id;
				$r = $this->db->query($sql);
			}
			return $f['channel_id'];
		}
		else 
		{
			return false;
		}
	}

	public function verify($start,$end,$channel_id,$id = 0)
	{
		$start = strtotime($this->mDates . " " . $start);
		$end = strtotime($this->mDates . " " . $end);
		
		if($start >= $end)
		{
			return false;
		}
		$sql = "select *,FROM_UNIXTIME(start_time, '%Y-%m-%d %H:%i:%S') as start_time,FROM_UNIXTIME((start_time+toff), '%Y-%m-%d %H:%i:%S') as plan_end_time from " . DB_PREFIX . "change_plan where channel_id=" . $channel_id." and start_time >" . $start . " and (start_time) <" . $end ." or (start_time+toff) >" . $start . " and (start_time+toff) <" . $end . " or start_time=" . $start . " and (start_time+toff)=" . $end;
		$q = $this->db->query($sql);
		$id_array = array();
		while($r = $this->db->fetch_array($q))
		{
			$id_array[] = $r['id'];
		}
		$id_array = array_unique($id_array);
		$ids = implode(',',$id_array);

		if(!$ids)
		{
			return false;
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "change_plan_relation WHERE plan_id IN(" . $ids . ")";
		$q = $this->db->query($sql);
		$week_array = array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['plan_id'] != $id)
			{
				$week_array[] = $r['week_num'];
			}
		}
		return $week_array;
	}

	
	/**
	 * 获取单条信息
	 */
	public function detail($condition)
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$condition .= " ORDER BY start_time ASC LIMIT 1";
		}
		else 
		{
			$condition .= " AND id =" . $id ;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "change_plan p WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		if($info['id'])
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "change_plan_relation WHERE plan_id=" . $id ." ORDER BY week_num ASC";
			$q = $this->db->query($sql);
			$week_num = array();
			while($r = $this->db->fetch_array($q))
			{
				$week_num[$r['week_num']] = $r['week_num'];
			}
			$info['week_day'] = $week_num;
			$info['week_d'] = $this->input['week_d'];
		}
		return $info;
	}
	/**
	 * 
	 * 设置提交数据的切播地址
	 * @param unknown_type $channel2_id
	 * @param unknown_type $type 1 - 直播， 2 -文件 ， 3 - 时移
	 */
	public function set_chg_uris($channel2_id, $type)
	{
		if ($type == 2) //取文件流地址
		{
			$sql = "select * from " . DB_PREFIX . "backup WHERE id=" . $channel2_id;
			$backup_info = $this->db->query_first($sql);
			if($backup_info['vodinfo_id'])
			{
				$file_stream_uri = $this->settings['vod_url'] . $backup_info['filepath'] . $backup_info['newname'];
			}
			else 
			{
				$file_stream_uri = UPLOAD_BACKUP_MMS_URL . $backup_info['newname'];
			}
			return $file_stream_uri;
		}
		else	//	取频道流地址
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "channel WHERE id=" . $channel2_id;
			$channel_info = $this->db->query_first($sql);
			$stream_uri = hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $channel_info['code'], 'stream_name' => $channel_info['main_stream_name']), 'live', 'tvie://');
			return $stream_uri;
		}
	}
}

?>