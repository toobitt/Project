<?php
class adfunc extends InitFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function get_ad_status_by_id($ids = array())
	{
		if(!$ids)
		{
			return;
		}
		$return = array();
		$ids = is_array($ids) && $ids ? implode(',', $ids) : $ids; 
		$sql = 'SELECT * FROM '.DB_PREFIX.'adtime WHERE adid IN('.$ids.') ORDER BY start_time ASC';
		$q = $this->db->query($sql);
		$adtimes = array();
		while($row = $this->db->fetch_array($q))
		{
			$adtimes[$row['adid']]['start_time'][] = $row['start_time'];
			$adtimes[$row['adid']]['end_time'][] = $row['end_time'];
		}
		foreach($adtimes as $adid=>$array_time)
		{
			$start_time = $end_time = '';
			//默认状态为过期
			$publishTime = get_ad_publishTime($array_time['start_time'], $array_time['end_time']);
			$start_time = $publishTime['start_time'] ? $publishTime['start_time'] : '';
			$end_time = $publishTime['end_time'] ? $publishTime['end_time'] : '';
			$return[$adid] = intval(get_ad_status($start_time, $end_time));
		}
		return $return;
	}
	//多段时间投放
	function insert_ad_time($start = array(), $end = array(), $adid = 0)
	{
		if(!$adid)
		{
			return;
		}
		if($start[0] && $end[0])
		{
			$sql = 'INSERT INTO '.DB_PREFIX.'adtime(adid, start_time, end_time) values';
			foreach($start as $k=>$v)
			{
				if($end[$k] <= $v)
				{
					return false;
				}
				$sql .= '('.$adid.','.strtotime($v).','.strtotime($end[$k]).'),';
			}
			$this->db->query(trim($sql, ','));
		}
		else if(!$start[0] && $end[0])
		{
			$endtime = $end[0] ? strtotime($end[0]) : '';
			if($endtime < TIMENOW)
			{
				return false;
			}
			//开始时间是当前时间
			$this->db->query('INSERT INTO '.DB_PREFIX.'adtime(adid, start_time, end_time) values('.$adid.','.TIMENOW.','.$endtime.')');
		}
		return true;
	}
}
?>