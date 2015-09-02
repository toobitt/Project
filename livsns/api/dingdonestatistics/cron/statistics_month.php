<?php
define('MOD_UNIQUEID','statistics_month');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
class statisticsMonth extends cronBase
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
			'name' => '每月APP统计',	 
			'brief' => '每月进行一次APP统计',
			'space' => '2592000',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function statisticsByMonth()
	{
		//获取数据库最后一次记录
		$sql = 'SELECT day_time, create_time FROM '.DB_PREFIX.'statistics_day ORDER BY id DESC LIMIT 0,1';
		$info = $this->db->query_first($sql);
		$lastTime = $info['day_time'];
		//$lastTime = strtotime('2014-5-1 00:00:00');//此处设定起始日期
		//如果不存在记录。则说明此时是第一次记录
		$start_time = 0;
		$end_time	= 0;
		$time_day	= strtotime(date('Y-m'));
		if ($time_day <= $lastTime)
		{
			exit('无新记录');
		}
		//不设置则默认取前一天数据
		if (!$lastTime)
		{
			$start_time = $time_day-86400;
			$end_time 	= $time_day;
		}
		else 
		{			
			$start_time = $lastTime;
			$end_time 	= $time_day;
		}
		$data = $this->_getData($start_time, $end_time);
		if (!empty($data))
		{
			$sql = 'INSERT INTO '.DB_PREFIX.'statistics_day (id, total_account, total_app, total_activation, create_time, day_time) VALUES ';
			foreach ($data as $val)
			{
				$sql .= '("", '.$val['account'].', '.$val['appnumber'].', '.$val['activation'].','.time().','.$val['day'].') ,';
			}
			$sql = rtrim($sql, ',');
			$this->db->query($sql);
		}
		exit('录入成功');
	}
	//获取数据
	private function _getData($start_time, $end_time)
	{
		$stime = ($start_time%86400) != 57600 ? strtotime(date('Y-m-d', $start_time)) + 86400 : $start_time;
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
		for ($i = $stime; $i<= $etime; $i+=86400)
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
}
$out = new statisticsMonth();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'statisticsByMonth';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 

?>
