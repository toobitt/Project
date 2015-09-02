<?php
include_once (ROOT_PATH.'lib/class/curl.class.php');
class statistics extends InitFrm
{
	private $curl;
	public function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['App_app_plant']['host'], $this->settings['App_app_plant']['dir']);
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition)
	{
		
		$info = array();
		$info['total_app'] 				= $this->totalApp();
		$info['total_account']			= $this->totalAccount();
		$info['total_activation']		= $this->totalActivation();
		$info['total_app_week']			= $this->totalAppByWeek();
		$info['total_account_week']		= $this->totalAccountByWeek();
		$info['total_activation_week']	= $this->totalActivationByWeek();
		$info['statistical_figure'] 	= $this->statisticalFigure($condition['start_time'],$condition['end_time'],$condition['type']); 
		return $info;		
	}
	//统计数据
	public function statisticalFigure($start_time = '', $end_time = '', $type = 'day')
	{ 
		switch ($type)
		{
			case $type == 'hour'	: $data = $this->_hourData($start_time, $end_time);break;
			case $type == 'day'		: $data = $this->_dayData($start_time, $end_time);break;
			case $type == 'week'	: $data = $this->_weekData($start_time, $end_time);break;
			case $type == 'month'	: $data = $this->_monthData($start_time, $end_time);break;
			case $type == 'quarter'	: $data = $this->_quarterData($start_time, $end_time);break;
			case $type == 'year'	: $data = $this->_yearData($start_time, $end_time);break;
			default:return array();
		}
		return $data;
	}
	
	//采用24小时制
	private function _hourData($start_time = '', $end_time = '')
	{
		$start_time = $start_time ? strtotime($start_time) : '';
		$end_time = $end_time ? strtotime($end_time) : '';
		if (!$start_time && !$end_time)
		{
			$stime = strtotime(date('Y-m-d'));
			$etime = time();
		}elseif (!$start_time && $end_time) 
		{
			$stime = strtotime(date('Y-m-d', $end_time));
			$etime = $end_time;
		}elseif ($start_time && !$end_time)
		{
			$stime = $start_time;
			$etime = strtotime(date('Y-m-d',$start_time)) + 24 * 3600;	
		}else 
		{
			$stime = $start_time;
			$etime = time();	
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'statistics_hour 
				WHERE hour_time >='.$stime.' 
				AND hour_time<='.$etime;
		$query = $this->db->query($sql);
		$arr = array();
		while ($row = $this->db->fetch_array($query))
		{
			$hour 			= date('H', $row['hour_time']);
			$day			= date('d', $row['hour_time']);
			$month 			= date('m', $row['hour_time']);
			$year			= date('Y', $row['hour_time']);
			$row['hour']	= $hour;
			$row['day']		= $day;
			$row['month']	= $month;
			$row['year']	= $year;
			$arr[] 			= $row;
		}
		return $arr;
	}
	//查询某段日期
	private function _dayData($start_time, $end_time)
	{
		$start_time = $start_time ? strtotime($start_time) : '';
		$end_time = $end_time ? strtotime($end_time) : '';
		if (!$start_time && !$end_time)
		{
			$stime = strtotime(date('Y-m'));
			$etime = time();
		}elseif (!$start_time && $end_time) 
		{
			$stime = strtotime(date('Y-m', $end_time));
			$etime = $end_time;
		}elseif ($start_time && !$end_time)
		{
			$stime = $start_time;
			$etime = $stime + date('t',$start_time) * 3600 *24;	
		}else 
		{
			$stime = $start_time;
			$etime = $end_time;	
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'statistics_day 
				WHERE day_time >='.$stime.' 
				AND day_time<='.$etime;
		$query = $this->db->query($sql);
		$arr = array();
		while ($row = $this->db->fetch_array($query))
		{
			$hour			= date('H', $row['day_time']);
			$day			= date('d', $row['day_time']);
			$month 			= date('m', $row['day_time']);
			$year			= date('Y', $row['day_time']);
			$row['hour']	= $hour;
			$row['day']		= $day;
			$row['month']	= $month;
			$row['year']	= $year;
			$arr[] 			= $row;
		}
		return $arr;
	}
	private function _monthData($start_time, $end_time)
	{
		$start_time = $start_time ? strtotime($start_time) : '';
		$end_time = $end_time ? strtotime($end_time) : '';
		if (!$start_time && !$end_time)
		{
			$stime = mktime(0,0,0,1,1,date('Y'));
			$etime = time();
		}elseif (!$start_time && $end_time) 
		{
			$stime = strtotime(date('Y', $end_time));
			$etime = $end_time;
		}elseif ($start_time && !$end_time)
		{
			$stime = $start_time;
			$etime = mktime(0,0,0,1,1,date('Y',$stime)+1);	
		}else 
		{
			$stime = $start_time;
			$etime = $end_time;	
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'statistics_month
				WHERE month_time >='.$stime.' 
				AND month_time<='.$etime;
		$query = $this->db->query($sql);
		$arr = array();
		while ($row = $this->db->fetch_array($query))
		{
			$month 			= date('m', $row['month_time']);
			$year			= date('Y', $row['month_time']);
			$row['month']	= $month;
			$row['year']	= $year;
			$arr[] 			= $row;
		}
		return $arr;
	}
	
	
	
	
	//总APP数
	public function totalApp()
	{
		$this->curl = new curl($this->settings['App_app_plant']['host'],$this->settings['App_app_plant']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'count');
		$data = $this->curl->request('admin/app.php');
		return $data['total'];
	}
	//总账户数
	public function totalAccount()
	{
		return 0;
	}
	//总激活数
	public function totalActivation()
	{
		return 0;
	}
	//本周APP数
	public function totalAppByWeek()
	{
		$start_time = date('Y-m-d H:i:s',mktime(0,0,0,date('m'),date('d')-date('w')+1,date('Y')));
		$end_time	= date('Y-m-d H:i:s');
		$this->curl = new curl($this->settings['App_app_plant']['host'],$this->settings['App_app_plant']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'count');
		$this->curl->addRequestData('start_time', $start_time);
		$this->curl->addRequestData('end_time', $end_time);
		$data = $this->curl->request('admin/app.php');
		return $data['total'];
	}
	//本周账户数
	public function totalAccountByWeek()
	{
		return 0;
	}
	public function totalActivationByWeek()
	{
		return 0;
	}
	
	
	
	
	
	public function count($condition)
	{

	}	
	
	public function detail($id)
	{

	}
	
}
?>
