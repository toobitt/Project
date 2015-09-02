<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: video_update.php 7586 2013-04-19 09:40:56Z yaojian $
***************************************************************************/
define('MOD_UNIQUEID', 'getActivateStatistics');  //模块标识
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/activate_mode.php');
class getActivateStatistics extends outerReadBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new activate_mode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$app_id = $this->input['app_id'];
		if(!$app_id)
		{
			$this->errorOutput(NO_APP_ID);
		}
		
		/**********************************************按照天输出激活量与活跃数****************************************************/
		$stime = strtotime($this->input['s_date']);
		$etime = strtotime($this->input['e_date']);
		//必须要传开始日期
		if(!$stime && !$etime)
		{
			$stime = TIMENOW - 6 * 24 * 3600;
			$etime = TIMENOW;
		}
		else if(!$stime)
		{
			$stime = $etime - 6 * 24 * 3600;
		}
		else if(!$etime)
		{
			$etime = TIMENOW;
		}

		//开始时间不能大于结束时间
		if($stime >= $etime)
		{
			$this->errorOutput(START_CAN_NOT_OVER_END);
		}
		
		//将传进来的时间分解成具体的天
		$s_date = date('Y-m-d',$stime);
		$e_date = date('Y-m-d',$etime);
		//通过条件查询统计激活量
		$condition = " AND create_time >= '" .strtotime($s_date). "' AND create_time < '" .(strtotime($e_date) + 24 * 3600). "' AND app_id = '" .$app_id. "' ";
		$activate_data = $this->mode->getActivateNum($condition);
		//查询活跃量
		$condition = " AND l.create_time >= '" .strtotime($s_date). "' AND l.create_time < '" .(strtotime($e_date) + 24 * 3600). "' AND d.app_id = '" .$app_id. "' ";
		$live_data = $this->mode->getLivenessNums($condition);
		//整理数据
		$c_date = $s_date;
		//最后输出的数据
		$out_data = array();
		$_n = 0;
		$_max = 0;
		while (strtotime($c_date) <= strtotime($e_date))
		{
			//整理激活量
			if(!isset($activate_data[$c_date]))
			{
				$out_data['byday'][$_n]['total_activates'] = 0;
			}
			else 
			{
				$out_data['byday'][$_n]['total_activates'] = $activate_data[$c_date];
			}
			
			//整理活跃数
			if(!isset($live_data[$c_date]))
			{
				$out_data['byday'][$_n]['total_liveness'] = 0;
			}
			else 
			{
				$out_data['byday'][$_n]['total_liveness'] = $live_data[$c_date];
			}
			
			//统计最大的值
			if($max < $activate_data[$c_date])
			{
				$max = $activate_data[$c_date];
			}
			
			if($max < $live_data[$c_date])
			{
				$max = $live_data[$c_date];
			}

			$out_data['byday'][$_n]['y'] = date('Y',strtotime($c_date));
			$out_data['byday'][$_n]['m'] = date('m',strtotime($c_date));
			$out_data['byday'][$_n]['d'] = date('d',strtotime($c_date));
			$c_date = date('Y-m-d',strtotime($c_date) + 24 * 3600);
			$_n++;
		}
		
		/**********************************************按照天输出激活量与活跃数****************************************************/
		
		
		/**********************************************输出激活总量****************************************************/
		$out_data['total_activates'] = $this->getTotalActivateNums($app_id);//总激活量
		$out_data['total_activates_month'] = $this->getTotalActivateNums($app_id,1);//30天内总激活量
		$out_data['total_activates_day'] = $this->getTotalActivateNums($app_id,2);//当日的激活量
		$out_data['total_activates_section'] = $this->getTotalActivateNumsBySection($app_id,strtotime($s_date),strtotime($e_date) + 24 * 3600);//该时间区间内的激活总量
		/**********************************************输出激活总量****************************************************/
		
		/**********************************************输出活跃总量*************************************************/
		$out_data['total_liveness_day'] = $this->getTotalLivnessByCurrentDay($app_id);//当日活跃总量
		$out_data['total_liveness_section'] = $this->getTotalLivenessNumsBySection($app_id,strtotime($s_date),strtotime($e_date) + 24 * 3600);//该时间区间内的活跃总量
		/**********************************************输出活跃总量*************************************************/
		
		/**********************************************输出统计的刻度**************************************************/
		$out_data['graduation'] = round((($max*3/2)/6),1);
		/**********************************************输出统计的刻度**************************************************/
		
		$this->addItem($out_data);
		$this->output();
	}
	
	//获取总的激活量（支持按月、按周、按日）
	private function getTotalActivateNums($app_id,$type = 0)
	{
		$condition = '';
		$etime = strtotime(date('Y-m-d',TIMENOW)) + 24 * 3600;//明天的凌晨00:00:00
		switch ($type)
		{
			case 1://月统计(今天向前推30天包括今天)
					$stime = $etime - 30 * 24 * 3600;
					break;
			case 2:
					//当日统计（今天）
					$stime = $etime - 24 * 3600;
					break;
		}
		
		if($stime)
		{
			$condition .= " AND create_time >= '" .$stime. "' AND create_time < '" .$etime. "' ";
		}

		$condition .= " AND app_id = '" .$app_id. "' ";
		$ret = $this->mode->getTotalActivateNums($condition);
		if($ret && $ret['total_activates'])
		{
			return intval($ret['total_activates']);
		}
		else 
		{
			return 0;
		}
	}
	
	//按照指定的时间区间获取总的激活量
	private function getTotalActivateNumsBySection($app_id,$stime,$etime)
	{
		$condition = " AND create_time >= '" .$stime. "' AND create_time < '" .$etime. "' AND app_id = '" .$app_id. "' ";
		$ret = $this->mode->getTotalActivateNums($condition);
		if($ret && $ret['total_activates'])
		{
			return intval($ret['total_activates']);
		}
		else 
		{
			return 0;
		}
	}

	//当日活跃数
	private function getTotalLivnessByCurrentDay($app_id)
	{
		$ret = $this->mode->getTotalLivnessByCurrentDay($app_id);
		if($ret && $ret['total_liveness'])
		{
			return intval($ret['total_liveness']);
		}
		else 
		{
			return 0;
		}
	}
	
	//获取总的活跃数
	private function getTotalLivenessNums($app_id)
	{
		$ret = $this->mode->getTotalLivenessNums($app_id);
		if($ret && $ret['total_liveness'])
		{
			return intval($ret['total_liveness']);
		}
		else 
		{
			return 0;
		}
	}
	
	//按照指定的时间区间获取总的活跃数(同设备号只算一次)
	private function getTotalLivenessNumsBySection($app_id,$stime,$etime)
	{
		$condition = " AND l.create_time >= '" .$stime. "' AND l.create_time < '" .$etime. "' AND d.app_id = '" .$app_id. "' ";
		$ret = $this->mode->getTotalLivenessNumsBySection($condition);
		if($ret)
		{
			return intval($ret['total_liveness']);
		}
		else 
		{
			return 0;
		}
	}
	
	//获取应用安装排名
	public function getInstallRank()
	{
	    $orderNum = $this->input['order_num'];
	    if(!$orderNum)
	    {
	        $orderNum = 20;//取多少位的排名
	    }
	    
	    $ret = $this->mode->getInstallRank($orderNum);
	    if($ret)
	    {
	        foreach ($ret AS $k => $v)
	        {
	            $this->addItem($v);
	        }
	        $this->output();
	    }
	}
	
	/**
	 * 获取应用IOS的使用数目
	 */
	public function getIosStatistics()
	{
		$app_id = intval($this->input['app_id']); 
		$appInfo = $this->mode->getIosNums($app_id);
		
		$testNums = "";
		$testArray = array();
		$publishArray = array();
		$publishNums = "";
		if($appInfo)
		{
			foreach ($appInfo as $k => $v)
			{
				if($v['debug'] == 1)
				{
					$publishArray[] = $v;
				}
				else
				{
					$testArray[] = $v;
				}
			}
		}
		$result = array();
		if($publishArray)
		{
			$publishNums = count($publishArray);
		}
		$result['publishNums'] = $publishNums;
		if($testArray)
		{
			$testNums = count($testArray);	
		}
		$result['testNums'] = $testNums;
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 获取一定时间内 有过活跃的app的总数
	 */
	public function getLivenessAppByTime()
	{	
		$times = trim($this->input['times']);
		$data = $this->mode->getLivenessAppInTimes($times);
		if($data)
		{
			$this->addItem($data);
		}
		$this->output();
	}
	
	/**
	 * (non-PHPdoc)获取每天活跃度最高的10个应用
	 * @see outerReadBase::detail()
	 */
	public function getTopTenAppId()
	{
		$info = $this->mode->getTopTen();
		if($info)
		{
			$this->addItem($info);
			
		}
		$this->output();
	}
	
	/**
	 * 获取一段时间内，应用的覆盖数目(每个设备只算1次)
	 */
	public function getCoverNums()
	{
		$start_time = intval($this->input['start_time']);
		$end_time = intval($this->input['end_time']);
		$info = $this->mode->getCoverNums($start_time,$end_time);
		if($info)
		{
			$this->addItem($info);
				
		}
		$this->output();
	}
	
	public function getStart()
	{
		$day = trim($this->input['day']);
		$info = $this->mode->getStart($day);
		if($info)
		{
			$this->addItem($info);
		
		}
		$this->output();
	}
	
	/**
	 * 获取指定时间安卓与iOS的激活量
	 */
	public function getTopTenDown()
	{
		$time_type = intval($this->input['time_type']);
		//1总，2自定义
		switch ($time_type)
		{
			case 1:
				$start_time = 0;
				$end_time = 0;
				break;
			case 2:
				$start_time = strtotime(trim($this->input['start_date']));
				$end_time = strtotime(trim($this->input['end_date']));
				break;
			case 3://昨日
				$start_time = strtotime(date('Y-m-d',TIMENOW))-3600*24;
				$end_time = strtotime(date('Y-m-d',TIMENOW));
				break;
			case 4://上周
				$week = date('w',TIMENOW);
				if($week == 0)
				{
					$end_time = strtotime(date('Y-m-d',TIMENOW))-6*3600*24;
				}
				else
				{
					$end_time = strtotime(date('Y-m-d',TIMENOW))-($week-1)*3600*24;
				}
				$start_time = $end_time - 7*3600*24;
				break;
			case 5://上月
				$back_month_date = date('Y-m-d',strtotime("-1 month"));
				$arr = $this->getthemonth($back_month_date);
				$start_time = strtotime($arr[0]);
				$end_time = strtotime($arr[1])+3600*24;
				break;
		}
		$ios_down_info = $this->mode->getTopDown($start_time , $end_time,IOS_SOURCE);
		$and_down_info = $this->mode->getTopDown($start_time , $end_time,ANDROID_SOURCE);
		$ret = array(
			'android' => $and_down_info,
			'ios'	  => $ios_down_info,
		);
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 获取app活跃数排名
	 * ios
	 * and
	 */
	public function getAppActivateRank()
	{
		$time_type = intval($this->input['time_type']);
		//1总，2自定义
		switch ($time_type)
		{
			case 1://总的
				$start_time = 0;
				$end_time = 0;
				break;
			case 2://自定义日期
				$start_time = strtotime(trim($this->input['start_date']));
				$end_time = strtotime(trim($this->input['end_date']));
				break;
			case 3://昨日
				$start_time = strtotime(date('Y-m-d',TIMENOW))-3600*24;
				$end_time = strtotime(date('Y-m-d',TIMENOW));
				break;
			case 4://上周
				$week = date('w',TIMENOW);
				if($week == 0)
				{
					$end_time = strtotime(date('Y-m-d',TIMENOW))-6*3600*24;
				}
				else
				{
					$end_time = strtotime(date('Y-m-d',TIMENOW))-($week-1)*3600*24;
				}
				$start_time = $end_time - 7*3600*24;
				break;
			case 5://上月
				$back_month_date = date('Y-m-d',strtotime("-1 month"));
				$arr = $this->getthemonth($back_month_date);
				$start_time = strtotime($arr[0]);
				$end_time = strtotime($arr[1])+3600*24;
				break;
		}
		$ios_activate_info = $this->mode->getAppActivateRank($start_time , $end_time, IOS_SOURCE);
		$and_activate_info = $this->mode->getAppActivateRank($start_time , $end_time, ANDROID_SOURCE);
		$ret = array(
				'android' => $and_activate_info,
				'ios'	  => $ios_activate_info,
		);
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 获取这个月的最后一天和最后一天
	 * @param unknown $date
	 * @return multitype:string
	 */
	public function getthemonth($date)
	{
		$firstday = date('Y-m-01', strtotime($date));
		$lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
		return array($firstday, $lastday);
	}
	
	/**
	 * 获取所有的激活量和活跃数
	 */
	public function getAllActivateAndDown()
	{
		$time_type = intval($this->input['time_type']);
		//1总，2自定义
		switch ($time_type)
		{
			case 1:
				$start_time = 0;
				$end_time = 0;
				break;
			case 2:
				$start_time = strtotime(trim($this->input['start_date']));
				$end_time = strtotime(trim($this->input['end_date']));
				break;
			case 3:
				$start_time = strtotime(date('Y-m-d',TIMENOW))-3600*24;
				$end_time = strtotime(date('Y-m-d',TIMENOW));
				break;
			case 4:
				$week = date('w',TIMENOW);
				if($week == 0)
				{
					$end_time = strtotime(date('Y-m-d',TIMENOW))-6*3600*24;
				}
				else
				{
					$end_time = strtotime(date('Y-m-d',TIMENOW))-($week-1)*3600*24;
				}
				$start_time = $end_time - 7*3600*24;
				break;
			case 5:
				$back_month_date = date('Y-m-d',strtotime("-1 month"));
				$arr = $this->getthemonth($back_month_date);
				$start_time = strtotime($arr[0]);
				$end_time = strtotime($arr[1])+3600*24;
				break;
		}
		$all_avtivate = $this->mode->getAllActivateAndDown($start_time , $end_time);
		$this->addItem($all_avtivate);
		$this->output();
	}
	
	public function getTodayDeviceInfo()
	{
		$time = intval($this->input['zero_time']);
		$info = $this->mode->getTodayDeviceInfo($time);
		$this->addItem($info);
		$this->output();
	}
	
	public function getSinkInfo()
	{
		$start_time = intval($this->input['start_time']);//对应日期的开始时间
		$end_time = intval($this->input['end_time']);//对应日期的结束
		$six_start_time = intval($this->input['six_start_time']);//对应APP创建那天的start_time
		$six_end_time = intval($this->input['six_end_time']);//对应app创建那天的结束时间
		$source = intval($this->input['source']);
		$ids = trim($this->input['ids']);
		$info = array();
		$ids_arr = explode(',', $ids);
		if($ids_arr && is_array($ids_arr))
		{
			foreach ($ids_arr as $k => $v)
			{
				//判断此APP的对应source 是否满足条件
				//：自创建起6个月内，前3个月用户量达到100后，日活跃用户均小于5的APP数
				//先判断前3个月用户量是否到100，
				$first_three_months_end_time = strtotime('+3 months',$six_end_time)+3600*24;
					
				$first_true = $this->mode->validateFirstThreeMonths($six_start_time,$first_three_months_end_time,$v,$source);
				if($first_true && $first_true['total'] > FIRST_THREE_MONTH)
				{
					//如果到100，在判断后3个月的活跃度
					$next_true = $this->mode->valiedateNextThreeMothns($first_three_months_end_time,$start_time,$v,$source);
					if($next_true && $next_true['total'])
					{
						//计算平均数
						$days = ($start_time-$first_three_months_end_time)/(3600*24);
						if($next_true['total']/$days > NEXT_THREE_MONTH_AVERAGE)
						{
							$info[] = $v;
						}
					}
				}
			}
		}
		$this->addItem($info);
		$this->output();	
	}
	
	public function getAllActivateInfo()
	{
		$start_time = intval($this->input['start_time']);
		$end_time = intval($this->input['end_time']);
		$info = $this->mode->getAllActivateInfo($start_time,$end_time);
		$this->addItem($info);
		$this->output();	
	}
	
	public function getTodayActivateInfo()
	{
		$start_time = intval($this->input['start_time']);
		$info = $this->mode->getTodayActivateInfo($start_time);
		$this->addItem($info);
		$this->output();
	}
	
	public function change()
	{
		
	}
	
	public function getPerishInfo()
	{
		$source = intval($this->input['source']);
		$start_time = intval($this->input['start_time']);
		$end_time = intval($this->input['end_time']);
		$ids = trim($this->input['ids']);
		$ids_arr = explode(',', $ids);
		$info = array();
		if($ids_arr && is_array($ids_arr))
		{
			foreach ($ids_arr as $k => $v)
			{
				//用户量未达到100
				$id = intval($v);
				$ret = $this->mode->validateFirstThreeMonths($start_time,$end_time,$id,$source);
				if($ret['total'] > PERISH_NUM)
				{
					$info[] = $v;
				}
			}
		}
		$this->addItem($info);
		$this->output();
	}
	
	public function detail(){}
    public function count(){}
}

$out = new getActivateStatistics();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>