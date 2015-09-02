<?php
/***************************************************************************
* $Id: interactive_program.class.php 16724 2013-01-14 05:15:19Z lijiaying $
***************************************************************************/
class interactiveProgram extends InitFrm
{
	private $mLive;
	
	public function __construct()
	{
		parent::__construct();
		
		include_once ROOT_PATH . 'lib/class/curl.class.php';
		$this->mLive = new curl($this->settings['App_live']['host'],$this->settings['App_live']['dir']);
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($channel_id, $dates, $start_time='', $end_time='', $condition = '', $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "interactive_program ";
		$sql.= " WHERE channel_id=" . $channel_id . " AND dates = '" . $dates . "' " . $condition;
		if ($start_time && $end_time)
		{
			$sql.= " AND start_time >= " . $start_time . " AND start_time < " . $end_time;
		}
		$sql.= " ORDER BY start_time ASC ";
		$q = $this->db->query($sql);
		
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			$row['week_day']	= $row['week_day'] ? unserialize($row['week_day']) : array();
			
			$row['is_now'] = 0;
			if ($row['start_time'] < TIMENOW && ($row['start_time'] + $row['toff']) > TIMENOW)
			{
				$row['is_now'] = 1;
			}
			
			$row['start']		= date('H:i:s' , $row['start_time']);
			$row['end']		  	= date('H:i:s' , ($row['start_time'] + $row['toff']));
			$row['presenter_id']	= $row['presenter_id'] ? unserialize($row['presenter_id']) : array();
			$row['member_id']		= $row['member_id'] ? unserialize($row['member_id']) : array();
			$info[]   = $row;
		}
		return $info;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "interactive_program SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function update($data, $id)
	{
		$sql = "UPDATE " . DB_PREFIX . "interactive_program SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = " . $id;
		
		$this->db->query($sql);
		
		$data['id'] = $id;
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "interactive_program WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		
		$sql = "DELETE FROM " . DB_PREFIX . "interactive_program_relation WHERE program_id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function program_relation_delete($program_id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "interactive_program_relation WHERE program_id = " . $program_id;
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function program_relation_edit($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "interactive_program_relation SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	/**
	 * 获取节目单和主持人关系
	 * Enter description here ...
	 * @param unknown_type $channel_id
	 * @param unknown_type $presenter_statr
	 * @param unknown_type $presenter_ids
	 */
	public function get_program_presenter($channel_id, $dates)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_presenter ";
		$sql.= " WHERE channel_id = " . $channel_id . " AND dates = '" . $dates . "' ";
		
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		return $return;
	}
	
	public function program_presenter_add($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "program_presenter SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		if ($this->db->query($sql))
		{
			return $data;
		}
		return false;
	}
	
	public function program_presenter_delete($channel_id, $start_time)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "program_presenter ";
		$sql .= " WHERE channel_id = " . $channel_id . " AND start_time = " . $start_time;
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function get_program_by_id($id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program ";
		$sql.= " WHERE id = " . $id;
		
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function program_add($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "program SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function program_edit($id, $data)
	{
		$sql = "UPDATE " . DB_PREFIX . "program SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= " WHERE id = " . $id;
		$this->db->query($sql);
		
		$data['id'] = $id;
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function presenter_add($program_id, $presenter_id)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "presenter SET program_id = " . $program_id . ", presenter_id = " . $presenter_id;
		$this->db->query($sql);
	}

	public function presenter_delete($program_id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "presenter ";
		$sql .= " WHERE program_id = " . $program_id;
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	/**
	 * 根据频道id搜索节目单
	 * Enter description here ...
	 * @param unknown_type $channel_id
	 * @param unknown_type $date
	 */
	function get_program_by_channel_id($channel_id, $dates = '')
	{
		if (!$this->mLive)
		{
			return array();
		}
		
		$this->mLive->setSubmitType('post');
		$this->mLive->initPostData();
		$this->mLive->setReturnFormat('json');
		$this->mLive->addRequestData('a', 'show');
		$this->mLive->addRequestData('channel_id', $channel_id);
		$this->mLive->addRequestData('dates', $dates);
		$ret = $this->mLive->request('program.php');
		return $ret;
	}
	
	/**
	 * 获取auth中角色信息
	 * Enter description here ...
	 * @param unknown_type $offset
	 * @param unknown_type $count
	 */
	function get_admin($admin_role, $offset = '', $count = '')
	{
		include_once ROOT_PATH . 'lib/class/curl.class.php';
		$this->mAuth = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		if (!$this->mAuth)
		{
			return array();
		}
		
		$this->mAuth->setSubmitType('post');
		$this->mAuth->initPostData();
		$this->mAuth->setReturnFormat('json');
		$this->mAuth->addRequestData('a', 'show');
		$this->mAuth->addRequestData('offset', $offset);
		$this->mAuth->addRequestData('count', $count);
		$this->mAuth->addRequestData('admin_role', $admin_role);
		$ret = $this->mAuth->request('admin/admin.php');
		return $ret;
	}
	
	/**
	 * 获取节目单,开始时间,结束时间
	 * Enter description here ...
	 * @param unknown_type $channel_id
	 * @param unknown_type $dates
	 * @param unknown_type $start_end
	 */
	public function get_program_start_end($channel_id, $dates, $start_end)
	{
		$ret_program = $this->get_program_by_channel_id($channel_id, $dates);
		
		if (!empty($ret_program))
		{
			$start_time = $end_time = $zhibo = '';
			$program = array();
			$theme = '';
			foreach ($ret_program AS $k=>$v)
			{
				if ($v['zhi_play'])
				{
					$start_time = $v['start_time'];
					$end_time = $start_time + $v['toff'];
					$theme = $v['theme'];
					$zhibo = 1;
				}
				$v['norm_start'] = date('H:i:s', $v['start_time']);
				$v['norm_end'] = date('H:i:s', $v['start_time'] + $v['toff']);
				$program[] = $v;
			}
		}
		
		if ($start_end)
		{
			$start2end = explode(',', $start_end);
			$start_time = strtotime($dates .' '.$start2end[0]);
			$end_time = strtotime($dates .' '.$start2end[1]);
		}
		else if(!$zhibo)
		{
			$start_time = $ret_program[0]['start_time'];
			$end_time = $start_time + $ret_program[0]['toff'];
			$start_end = date('H:i:s', $start_time) . ',' . date('H:i:s', $end_time);
		}
		else 
		{
			$start_end = date('H:i:s', $start_time) . ',' . date('H:i:s', $end_time);
		}
		
		$return = array(
			'program'	 => $program,
			'start_end'	 => $start_end,
			'start_time' => $start_time,
			'end_time' 	 => $end_time,
			'theme'		 => $theme,
		);
		return $return;
	}
	
	public function get_presenter_by_program_id($channel_id, $dates)
	{
		$sql = "SELECT t1.*, t2.start_time, t2.toff FROM " .DB_PREFIX . "presenter t1 ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "program t2 ON t1.program_id = t2.id";
		$sql.= " WHERE t2.channel_id = " . $channel_id . " AND t2.dates = '" . $dates . "'";
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[$row['presenter_id']][] = $row;
		}
		return $return;
	}
	
	public function get_presenter_info($channel_id, $start_time, $presenter_id = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program t1";
		$sql.= " LEFT JOIN " . DB_PREFIX . "presenter t2 ON t1.id = t2.program_id ";
		$sql.= " WHERE channel_id = " . $channel_id . " AND start_time = " . $start_time;
		if ($presenter_id)
		{
			$sql .= " AND t2.presenter_id = " . $presenter_id;
		}
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		return $return;
	}
	
	/**
	 * 节目环节审核
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public function audit($id, $type)
	{
		$sql = "SELECT " . $type . " FROM " . DB_PREFIX . "interactive_program WHERE id = " . $id;
		$info = $this->db->query_first($sql);

		$status = $info[$type];
		
		$new_status = 0; //操作失败
		
		if (!$status)	//已审核
		{
			$sql = "UPDATE " . DB_PREFIX . "interactive_program SET ".$type." = 1 WHERE id = " . $id;
			$this->db->query($sql);
			
			$new_status = 1;
		}
		else			//待审核
		{
			$sql = "UPDATE " . DB_PREFIX . "interactive_program SET ".$type." = 0 WHERE id = " . $id;
			$this->db->query($sql);

			$new_status = 2;
		}

		return $new_status;
	}
	
	public function mutex_audit($id, $program_id)
	{
		$sql = "UPDATE " . DB_PREFIX . "interactive_program SET status = 0 WHERE program_id = " . $program_id . " AND id NOT IN (" . $id . ")";
		$this->db->query($sql);
		
		$sql = "UPDATE " . DB_PREFIX . "interactive_program SET status = 1 WHERE id = " . $id;
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
}
?>