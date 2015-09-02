<?php 
class check extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function update_time($data,$condition)
	{
		$sql = 'UPDATE '.DB_PREFIX.'online SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE 1 ';
		foreach ($condition as $k=>$v)
		{
			$sql .= ' AND '.$k .'="'.$v.'"';
		}
		$this->db->query($sql);
		return true;
		
	}
	public function check_online($time)
	{
		//删除登陆时间超过30分钟的用户
		$sql = ' DELETE  FROM '.DB_PREFIX.'online WHERE ('.$time.'-login_time) >1800 ';
		$this->db->query($sql);
		return true;
	}
	public function clear_online($interviewId)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'online WHERE interview_id = '.$interviewId;
		$this->db->query($sql);
		return TRUE;
	}

}




