<?php
define('MOD_UNIQUEID','statistics');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
define('START_TIME','');
class ClassStatistics extends cronBase
{
	private $curl;
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '每小时APP统计',	 
			'brief' => '每小时进行一次APP统计',
			'space' => '3600',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function statistics()
	{
		//获取数据库最后一次记录
		$sql = 'SELECT hour_time, create_time FROM '.DB_PREFIX.'statistics_hour ORDER BY id DESC LIMIT 0,1';
		$info = $this->db->query_first($sql);
		$lastTimeHour = $info['hour_time'];
		$timeHour	= strtotime(date('Y-m-d')) + date('H')*3600;
		$forward_hour = mktime((date('H',$timeHour)-1),0,0,date('m',$timeHour),date('d',$timeHour),date('Y',$timeHour));
		//echo $lastTimeHour.'___'.$forward_hour;exit();
		if ($lastTimeHour < $forward_hour)
		{
			$this->statisticsByHour();
		}
		//获取数据库最后一次记录
		$sql = 'SELECT day_time, create_time FROM '.DB_PREFIX.'statistics_day ORDER BY id DESC LIMIT 0,1';
		$info = $this->db->query_first($sql);
		$lastTimeDay = $info['day_time'];
		$timeDay	= strtotime(date('Y-m-d'));
		$forward_day = mktime(0,0,0,date('m',$timeDay),(date('d',$timeDay)-1),date('Y',$timeDay));
		//echo $lastTimeDay.'___'.$forward_day;exit();
		if ($lastTimeDay < $forward_day)
		{
			$this->statisticsByDay();
		}
		//获取数据库最后一次记录
		$sql = 'SELECT month_time, create_time FROM '.DB_PREFIX.'statistics_month ORDER BY id DESC LIMIT 0,1';
		$info = $this->db->query_first($sql);
		$lastTimeMonth = $info['month_time'];
		$timeMonth	= strtotime(date('Y-m'));
		$forward_month = mktime(0,0,0,(date('m',$timeMonth)-1),1,date('Y',$timeMonth));
		//echo $lastTimeMonth.'___'.$forward_month;exit();
		if ($lastTimeMonth < $forward_month)
		{
			$this->statisticsByMonth();
		}
		
	}
	
	
	
	public function statisticsByHour()
	{
		//获取数据库最后一次记录
		$sql = 'SELECT hour_time, create_time FROM '.DB_PREFIX.'statistics_hour ORDER BY id DESC LIMIT 0,1';
		$info = $this->db->query_first($sql);
		$lastTime = $info['hour_time'];
		if (defined('START_TIME') && START_TIME)
		{
			$lastTime = strtotime(START_TIME);
		}
		//echo date('Y-m-d H:i:s',$lastTime);exit();
		//$lastTime = strtotime('2014-5-1 00:00:00');//此处设定起始日期
		//如果不存在记录。则说明此时是第一次记录
		$start_time = 0;
		$end_time	= 0;
		$time_hour	= strtotime(date('Y-m-d')) + date('H')*3600;
		if ($time_hour <= $lastTime)
		{
			//exit('无新记录');
			return false;
		}
		//不设置则默认取前一个小时数据
		if (!$lastTime)
		{
			$start_time = $time_hour-3600;
			$end_time 	= $time_hour;
		}
		else 
		{			
			$start_time = $lastTime;
			$end_time 	= $time_hour;
		}
		//echo $start_time.'____'.$end_time;exit();
		//查询账户数
		$account = $this->_getAccount($start_time, $end_time);
		//查询APP数
		$appNumber = $this->_getAppNumber($start_time, $end_time);
		//激活数
		$activation = $this->_getActivation($start_time, $end_time); 
		$data = array();
		if (!empty($appNumber))
		{
			foreach ($appNumber as $key=>$val)
			{
				
				$data[$key]['appnumber'] 	= $val['appnumber'];
				$data[$key]['hour']			= $val['hour'];
				$data[$key]['account']		= 0;
				$data[$key]['activation']	= 0;
			}
		}
		if ($data && is_array($data) && !empty($data))
		{
			$sql = 'INSERT INTO '.DB_PREFIX.'statistics_hour (id, total_account, total_app, total_activation, create_time, hour_time) VALUES ';
			foreach ($data as $val)
			{
				$sql .= '("", '.$val['account'].', '.$val['appnumber'].', '.$val['activation'].','.time().','.$val['hour'].') ,';
			}
			$sql = rtrim($sql, ',');
			$this->db->query($sql);
		}
		return true;
	}
	public function _getActivation($start_time, $end_time)
	{
		return 0;
	}
	
	private function _getAppNumber($start_time, $end_time)
	{
		$this->curl = new curl($this->settings['App_app_plant']['host'],$this->settings['App_app_plant']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('count', '-1');
		$this->curl->addRequestData('start_time', date('Y-m-d H:i:s',$start_time));
		$this->curl->addRequestData('end_time', date('Y-m-d H:i:s',$end_time));
		$data = $this->curl->request('admin/app.php');
		$data = $data[0];
			//	hg_pre($data);exit();
		
		$arr = array();
		if (is_array($data['info']) && !empty($data['info']))
		{
			
			for ($i = $start_time ; $i< $end_time ; $i = $i+3600)
			{
				$num = 0;
				foreach ($data['info'] as $val)
				{
					if ($val['create_time'] >= $i && $val['create_time'] < $i+3600)
					{
						$num += 1;
					}
				}
				//$arr[] = $num;
				$arr[] = array(
					'hour'		=> $i,
					'appnumber'	=> $num,
				);	
			}
		}
		return $arr;
	}
	
	private function _getAccount($start_time, $end_time)
	{
		return 0;
//		$this->curl = new curl($this->settings['App_company']['host'],$this->settings['App_company']['dir']);
//		$this->curl->setSubmitType('post');
//		$this->curl->setReturnFormat('json');
//		$this->curl->initPostData();
//		$this->curl->addRequestData('a', 'show');
//		$this->curl->addRequestData('start_time', date('Y-m-d H:i:s',$start_time));
//		$this->curl->addRequestData('end_time', date('Y-m-d H:i:s',$end_time));
//		$data = $this->curl->request('admin/company.php');
//		//hg_pre($data);exit();
//		return $data['total'];
	}

	
	public function statisticsByDay()
	{
		//获取数据库最后一次记录
		$sql = 'SELECT day_time, create_time FROM '.DB_PREFIX.'statistics_day ORDER BY id DESC LIMIT 0,1';
		$info = $this->db->query_first($sql);
		$lastTime = $info['day_time'];
		//$lastTime = strtotime('2014-5-1 00:00:00');//此处设定起始日期
		//如果不存在记录。则说明此时是第一次记录
		$start_time = 0;
		$end_time	= 0;
		$time_day	= strtotime(date('Y-m-d'));
		$forward_time = mktime(0,0,0,date('m',$time_day), (date('d',$time_day)-1), date('Y',$time_day));
		if ($forward_time <= $lastTime)
		{
			return false;
		}
		//不设置则默认取前一天数据
		if (!$lastTime)
		{
			//查询小时的记录表，查看小时表是否也无记录
			$sql = 'SELECT hour_time FROM '.DB_PREFIX.'statistics_hour ORDER BY id ASC LIMIT 0,1';
			$res = $this->db->query_first($sql);
			$firstTime	= $res['hour_time'];
			if ($firstTime)
			{
				$start_time	= $firstTime;
				$end_time	= $time_day;
			}
			else 
			{
				$start_time = $time_day-86400;
				$end_time 	= $time_day;
			}
			
		}
		else 
		{			
			$start_time = $lastTime;
			$end_time 	= $time_day;
		}
		$data = $this->_getDayData($start_time, $end_time);
		if (!empty($data))
		{
			$sql = 'INSERT INTO '.DB_PREFIX.'statistics_day (id, total_account, total_app, total_activation, create_time, day_time) VALUES ';
			foreach ($data as $val)
			{
				if(($val['day'] - time()) >= 86400)
				{
			         $sql .= '("", '.$val['account'].', '.$val['appnumber'].', '.$val['activation'].','.time().','.$val['day'].') ,';
				}
			}
			$sql = rtrim($sql, ',');
			$this->db->query($sql);
		}
		return true;
	}
	//获取数据
	private function _getDayData($start_time, $end_time)
	{
		$stime = strtotime(date('Y-m-d', $start_time));
		$etime = $end_time;
		$sql = 'SELECT * FROM '.DB_PREFIX.'statistics_hour 
				WHERE hour_time >=' . $stime. ' AND hour_time < '.$etime;		
		$query = $this->db->query($sql);
		$data = array();
		while ($row = $this->db->fetch_array($query))
		{
			$data[] = $row;
		}
		if (empty($data))
		{
			return $data;
		}
		$arr = array();
		//时间段切割
		for ($i = $stime; $i< $etime; $i+=86400)
		{
			$account 	= 0;
			$appnumber	= 0;
			$activation	= 0;
			$day = $i;
			foreach ($data as $val)
			{
				if ($val['hour_time']>=$i && $val['hour_time']<$i+86400)
				{
					$account 	+= $val['total_account'];
					$appnumber	+= $val['total_app'];
					$activation	+= $val['total_activation'];
				}
			}
			$arr[] = array(
						'account'		=> $account,
						'appnumber'		=> $appnumber,
						'activation'	=> $activation,
						'day'			=> $day,
			);				
		}			
		return $arr;
	}
	
	public function statisticsByMonth()
	{
		//获取数据库最后一次记录
		$sql = 'SELECT month_time, create_time FROM '.DB_PREFIX.'statistics_month ORDER BY id DESC LIMIT 0,1';
		$info = $this->db->query_first($sql);
		$lastTime = $info['month_time'];
		//$lastTime = strtotime('2014-5-1 00:00:00');//此处设定起始日期
		//如果不存在记录。则说明此时是第一次记录
		$start_time = 0;
		$end_time	= 0;
		$time_month	= strtotime(date('Y-m'));
		$forward_month = mktime(0,0,0,(date('m',$time_month)-1),1,date('Y',$time_month));
		//echo $lastTime.'___'.$forward_month;exit();
		if ($forward_month <= $lastTime )
		{
			return false;
		}
		//不设置则默认取前一天数据
		if (!$lastTime)
		{
			//查询小时的记录表，查看小时表是否也无记录
			$sql = 'SELECT * FROM '.DB_PREFIX.'statistics_hour ORDER BY id ASC LIMIT 0,1';
			$res = $this->db->query_first($sql);
			$firstTime	= $res['hour_time'];
			if ($firstTime)
			{
				$start_time	= $firstTime;
				$end_time	= $time_month;
			}
			else 
			{
				$start_time = date('Y-m-01',strtotime(date('Y').'-'.(date('m')-1).'-01'));
				$end_time 	= $time_month;
			}
			
		}
		else 
		{			
			$start_time = $lastTime;
			$end_time 	= $time_month;
		}
		$data = $this->_getMonthData($start_time, $end_time);
		if (!empty($data))
		{
			$sql = 'INSERT INTO '.DB_PREFIX.'statistics_month (id, total_account, total_app, total_activation, create_time, month_time) VALUES ';
			foreach ($data as $val)
			{
				$sql .= '("", '.$val['account'].', '.$val['appnumber'].', '.$val['activation'].','.time().','.$val['month'].') ,';
			}
			$sql = rtrim($sql, ',');
			$this->db->query($sql);
		}
		return true;
	}
	
	//获取数据
	private function _getMonthData($start_time, $end_time)
	{		
		$stime = strtotime(date('Y-m',$start_time));
		$etime = $end_time;
		if ($stime >= $etime)
		{
			return array();
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'statistics_day 
				WHERE day_time >=' . $stime. ' AND day_time < '.$etime;		
		$query = $this->db->query($sql);
		$data = array();
		while ($row = $this->db->fetch_array($query))
		{
			$data[] = $row;
		}
		if (empty($data))
		{
			return $data;
		}
		//hg_pre($data);exit();
		$arr = array();
		while ($stime < $etime)
		{
			$overTime = strtotime(date('Y-m-t 24:00:00',$stime));
			//echo $stime.'___'.$overTime;exit();
			$account 	= 0;
			$appnumber	= 0;
			$activation	= 0;
			$month		= strtotime(date('Y-m',$stime));
			foreach ($data as $val)
			{
			
				if ($val['day_time'] >= $stime && $val['day_time'] < $overTime)
				{
					$account 	+= $val['total_account'];
					$appnumber	+= $val['total_app'];
					$activation	+= $val['total_activation']; 
				}
			}
			$stime = $overTime;
			$arr[] = array(
						'account'		=> $account,
						'appnumber'		=> $appnumber,
						'activation'	=> $activation,
						'month'			=> $month,
			);
		}
		//hg_pre($arr);exit();	
		return $arr;
	}
}
$out = new ClassStatistics();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'statistics';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 

?>
