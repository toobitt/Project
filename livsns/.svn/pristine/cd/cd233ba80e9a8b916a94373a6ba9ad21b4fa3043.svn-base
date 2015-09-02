<?php
define('MOD_UNIQUEID','statistics_hour');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
class statisticsHour extends cronBase
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
	
	public function statisticsByHour()
	{
		//获取数据库最后一次记录
		$sql = 'SELECT hour_time, create_time FROM '.DB_PREFIX.'statistics_hour ORDER BY id DESC LIMIT 0,1';
		$info = $this->db->query_first($sql);
		$lastTime = $info['hour_time'];
		//$lastTime = strtotime('2014-5-1 00:00:00');//此处设定起始日期
		//如果不存在记录。则说明此时是第一次记录
		$start_time = 0;
		$end_time	= 0;
		$time_hour	= strtotime(date('Y-m-d')) + date('H')*3600;
		if ($time_hour <= $lastTime)
		{
			exit('无新记录');
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
		//查询账户数
		$account = $this->_getAccount($start_time, $end_time);
		//查询APP数
		$appNumber = $this->_getAppNumber($start_time, $end_time);
		//激活数
		$activation = $this->_getActivation($start_time, $end_time); 
		
//		if (!$lastTime)
//		{
//			$start_time = $time_hour-3600;
//			$end_time 	= $time_hour;
//		}
//		else 
//		{			
//			if ($time_hour > $lastTime)
//			{
//				$start_time = $lastTime;
//				$end_time 	= $lastTime + 3600;
//			}
//			else
//			{
//				exit('无新记录');
//			} 
//		}		
		$data = array();
		while (time() > $end_time)
		{
//			$data = $this->_getData($start_time, $end_time);
			//查询账户数
			$account = $this->_getAccount($start_time, $end_time);
			//查询APP数
			$appNumber = $this->_getAppNumber($start_time, $end_time);
			//激活数
			$activation = $this->_getActivation($start_time, $end_time); 
			$data[] = array(
				'account'		=> $account,
				'appnumber'		=> $appNumber,
				'activation'	=> $activation,
				'hour'			=> $end_time,
			);
			$start_time += 3600;
			$end_time += 3600;  
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
		exit('录入成功');
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
		$this->curl->addRequestData('a', 'count');
		$this->curl->addRequestData('start_time', date('Y-m-d H:i:s',$start_time));
		$this->curl->addRequestData('end_time', date('Y-m-d H:i:s',$end_time));
		$data = $this->curl->request('admin/app.php');
		return $data['total'];
	}
	
	private function _getAccount($start_time, $end_time)
	{
		$this->curl = new curl($this->settings['App_company']['host'],$this->settings['App_company']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'count');
		$this->curl->addRequestData('start_time', date('Y-m-d H:i:s',$start_time));
		$this->curl->addRequestData('end_time', date('Y-m-d H:i:s',$end_time));
		$data = $this->curl->request('admin/company.php');
		return $data['total'];
	} 
}
$out = new statisticsHour();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'statisticsByHour';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 

?>
