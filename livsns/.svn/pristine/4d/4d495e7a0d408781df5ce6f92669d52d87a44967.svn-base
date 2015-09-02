<?php
require_once ROOT_PATH . 'lib/class/curl.class.php';
class auditCore extends InitFrm
{
	public function __construct()
	{	
		parent::__construct();	
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($conditions = '', $orderby='', $limit='')
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'auditset WHERE is_open = 1 '.$conditions.$orderby.$limit;
		$query = $this->db->query($sql);
		$configs = array();
		while ($row = $this->db->fetch_array($query))
		{
			$configs[$row['id']] = $row;
		}
		return $configs;
		
	}
	
	public function forward($config)
	{
		if (empty($config))
		{
			return false;
		}
		$start_date = $config['start_time']; //开始日期
		$end_date = $config['end_time'] ? ($config['end_time']+24*60*60) : 0 ; //截至日期
		if ($start_date >= TIMENOW || ($end_date && $end_date<= TIMENOW))
		{
			return false;
		}
		
		$week_day = $config['week_day'] ? explode(',', $config['week_day']) : '';
		if (!$week_day || empty($week_day))
		{
			return false;
		}
		$week = date('w');
		$week = ($week == 0) ? 7 : $week ;  //星期日日期返回为0
		if (!in_array($week, $week_day))
		{
			return false;
		}
		
		$infor = unserialize($config['infor']);
		$settime = array();
		if ($infor && is_array($infor))
		{
			if ($infor['start_time'] && is_array($infor['start_time']))
			{
				foreach ($infor['start_time'] as $key=>$val)
				{
					$up_time = strtotime($val);
					if ($infor['end_time'][$key])
					{
						$down_time = strtotime($infor['end_time'][$key]);
						//说明时间设置是超过一天的,拆成2个时间段
						if ($down_time<$up_time)
						{
					
							$settime['start_time'][]	= '00:00';
							$settime['end_time'][] 		= $infor['end_time'][$key];
							$settime['type'][] 			= $infor['type'][$key];
							$settime['start_time'][]	= $infor['start_time'][$key];
							$settime['end_time'][]	 	= '23:59:59';
							$settime['type'][] 			= $infor['type'][$key];
						}
						else 
						{
							$settime['start_time'][]	= $infor['start_time'][$key];
							$settime['end_time'][]		= $infor['end_time'][$key];
							$settime['type'][] 			= $infor['type'][$key];
						}
					}
				}
			}
			if (empty($settime))
			{
				return false;
			}
			//审核状态已最后一个设置为准
			$start_time = '';
			$end_time = '';
			$status = '';
			foreach ($settime['start_time'] as $key=>$val)
			{
				$temp_start_time	= strtotime($val);
				$temp_end_time		= strtotime($settime['end_time'][$key]);
				if ($temp_start_time <= TIMENOW && TIMENOW <= $temp_end_time)
				{
					$start_time = $temp_start_time;
					$end_time 	= $temp_end_time;
					$status 	= $settime['type'][$key];
				}
			}
			if (!$start_time || !$end_time || !$status || !$config['filename'] || !$config['funcname'])
			{
				return false;
			}
			//echo date('Y-m-d H:i:s',$start_time).'_'.date('Y-m-d H:i:s',$end_time).'_'.$status; exit();
			if ($config['bundle'] && $config['bundle']!='-1')
			{
				if ($this->settings['App_'.$config['bundle']])
				{
					$temp = array(
						'a'			=> $config['funcname'],
						'start_time'=> $start_time,
						'end_time'	=> $end_time,
						'status'	=> $status,
					);
					$host = $this->settings['App_'.$config['bundle']]['host'];
					$dir = $this->settings['App_'.$config['bundle']]['dir'];
					$this->forward_curl($host, $dir, $config['filename'], $temp);
					return true;
				}
			}
			elseif ($config['host'] && $config['dir'])
			{
				$temp = array(
					'a'			=> $config['funcname'],
					'start_time'=> $start_time,
					'end_time'	=> $end_time,
					'status'	=> $status,
				);
				$this->forward_curl($config['host'], $config['dir'], $config['filename'], $temp);
				return true;
			}
		}
		return true;
	}
	
	private function forward_curl($host, $dir, $filename, $data)
	{
		//print_r($data);exit();
		switch ($data['status'])
		{
			case 1: $data['status_name'] = '未审核';break;
			case 2: $data['status_name'] = '已审核';break;
			case 3: $data['status_name'] = '被打回';break;
		}
		$this->curl = new curl($host, $dir.'admin/');
		$this->curl->initPostData();
		$this->curl->setSubmitType('post');
		foreach ($data as $key=>$val)
		{
			$this->curl->addRequestData($key, $val);
		}
		$ret = $this->curl->request($filename);
		return true;
	}
	
}