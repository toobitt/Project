<?php
require ('./global.php');
define('MOD_UNIQUEID','gongjiao');
define('SCRIPT_NAME', 'LineBus');
class LineBus extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function count(){}	
	
	public function show()
	{
		$line_no 		= $this->input['routeid'] ;
		$line_direct 	= $this->input['stationseq'] == 2 ? 2 :1;
		$station_no 	= $this->input['segmentid'];
		
		
		if (!$line_no || !$station_no)
		{
			$return = array(
				'message' => '信息不全',
				'result' => '',
			);
			echo json_encode($return);
			exit;
		}
		
		//$line_no = str_pad($line_no,4,'0',STR_PAD_LEFT);
	
		$sql = "SELECT * FROM  " . DB_PREFIX . "line_station t1
					LEFT JOIN " . DB_PREFIX . "station t2
						ON t2.station_id=t1.station_id
					WHERE t1.line_no='{$line_no}' AND t1.line_direct={$line_direct} AND t1.station_no = {$station_no}";
		
		//echo $sql;
		$r = $this->db->query_first($sql);
		
		############站点名称处理############
		$r['station_name'] = str_replace('-1', '', $r['station_name']);
		$r['station_name'] = str_replace('-2', '', $r['station_name']);
		#################################
		
		//hg_pre($r,0);
		$curlocation = array($r['location_x'], $r['location_y'], $r['station_name']);
		
		$station_id = '';
		$station_id = $r['station_id'];
		
		if (!$station_id)
		{
			$return = array(
				'message' => '站点已经不存在',
				'result' => '',
			);
			echo json_encode($return);
			exit;
		}
		
		$buses = array();
		
		$line_bus_tab = $this->settings['bus_tab'];
		if($line_bus_tab)
		{
			$sql = "SELECT t1.*, t2.station_no, t3.station_name,t3.location_x st_location_x, t3.location_y st_location_y, runtime FROM " . DB_PREFIX . "bus t1 
					LEFT JOIN " . DB_PREFIX . "line_station t2
						ON t2.station_id=t1.station_id
					LEFT JOIN " . DB_PREFIX . "station t3
						ON t3.station_id=t1.station_id
					WHERE t1.line_no='{$line_no}' AND t1.line_direct=$line_direct AND t2.station_no < $station_no ORDER BY t2.station_no DESC";
			
			//echo $sql;
			$query = $this->db->query($sql);
			
			$delay_time = intval($this->settings['delay_time']);//单位秒
			//$delay_time = 0;
			while ($r = $this->db->fetch_array($query))
			{
				############站点名称处理############
				$r['station_name'] = str_replace('-1', '', $r['station_name']);
				$r['station_name'] = str_replace('-2', '', $r['station_name']);
				#################################
				if($r['runtime'])
				{
					$r['runtime'] =  date('H:i:s', strtotime($r['runtime']));
				}
				
				
				if ($curlocation[0])
				{
					$r['distance'] = GetDistance($r['location_x'], $r['location_y'], $curlocation[0], $curlocation[1]);
				}
				else
				{
					$r['distance'] = 'unknown';
				}
				
				$num = $station_no-$r['station_no'];
				if ($num == 0)
				{
					$r['distance'] = '-' . $r['distance'];
				}
				
				$near_des = '最近一班车距离本站' . $num . '站';
				if($r['distance'] != 'unknown')
				{
					if($delay_time && $r['bus_v'])
					{
						if(strpos($r['distance'],'公里'))
						{
							$r['distance'] = floatval($r['distance']);
							$r['distance'] -= $r['bus_v'] * $delay_time / 3600;
							$r['distance'] = round($r['distance'],2) . '公里';
						}
						else if(strpos($r['distance'], '米'))
						{
							$r['distance'] = floatval($r['distance']);
							$r['distance'] -= $r['bus_v'] * $delay_time / 3600 * 1000;
							$r['distance'] = round($r['distance']) . '米';
						}
						
					}
					$near_des .= ' ' . $r['distance'];
				}
				
				$t = array(
					'stationname' => $r['station_name'],
					'actdatetime' => $r['runtime'],
					'stationnum' => $num,
					'busselfid' => $r['bus_no'],
					'productid' => $r['bus_id'],
					'lastBus' => 0,
					'distance' => $r['distance'],
					'curstation' => $curlocation[2],
					'near_des'	=> $near_des,
					);
				$buses[] = $t;
			}
		}
		else 
		{
			require_once CUR_CONF_PATH.'data/bus.php';
			
			$ret = array();
			//echo $line_no . '---' . $station_id . '----' . $line_direct;
			$ret = line_bus($line_no,$station_id,$line_direct);
			
			
			//hg_pre($ret,0);
			if(!empty($ret))
			{
				if($ret['leave_time'])
				{
					$leave_time  = $ret['leave_time'];
				}
				else 
				{
					foreach ($ret as $val)
					{
						$stop = '';
						$stop = ($val['stop'] == 'no') ? '驶离' : '到达';
						
						$runtime = '';
						$runtime = $val['runtime'] ? '于' . $val['runtime'] : '';
						
						$val['descr'] = '车辆' . $val['bus_no'] . '已' . $runtime . $stop . $val['station'] . '，距离本站还有' . $val['station_no'] . '站';
						$val['brief'] = '车辆已' . $stop .  '本站';
				
						$t = array(
							'stationname' 	=> $val['station'],
							'actdatetime' 	=> $val['runtime'] ? $val['runtime'] : '',
							'stationnum' 	=> $val['station_no'],
							'busselfid' 	=> $val['bus_no'],
							'productid' 	=> $val['bus_id'],
							'lastBus' 		=> 0,
							//'distance' 	=> abs($val['distance']),//单位公里，精确到0.1
							'curstation' 	=> $curlocation[2],
							'stop'			=> $val['stop'] == 'no' ? 1 : 0,
							'brief'			=> $val['brief'],
							'descr'			=> $val['descr'],
							'near_des'		=> '最近一班车距离本站还有' . $val['station_no'] . '站',
						);
						$buses[] = $t;
					}
				}
			}
		}
		
		if ($buses)
		{
			$return = array(
				'message' => "",
				'result' => $buses
			);
		}
		else
		{
			
			$sql = "SELECT start_time,stop_time,sub_start_time,sub_stop_time FROM " . DB_PREFIX . "line WHERE line_no = '{$line_no}'";
			$r = $this->db->query_first($sql);
			
			if($line_direct == 1)
			{
				$start_time = strtotime(date('H:i',$r['start_time']));
				$end_time = strtotime(date('H:i',$r['stop_time']));
			}
			else if($line_direct == 2)
			{
				$start_time = strtotime(date('H:i',($r['sub_start_time'])));
				$end_time = strtotime(date('H:i',($r['sub_stop_time'])));
			}
			
			$time = TIMENOW;
			
			if($leave_time)
			{
				$message = '最近一班将于' . $leave_time . '从首站发出';
			}
			elseif($time >= $start_time && $time <= $end_time)
			{
				$message = '无发车信息';
			}
			else
			{
				$message = '还未运行';
			}
			$return = array(
				'message' => $message,
				'result' => ''
			);
			//print_r($return);
		}
		echo json_encode($return);
		exit;
		
	}
	
	public function get_condition()
	{
		$condition = '';
		return $condition ;
	}
	
	
	public function detail()
	{
	}
}
include(ROOT_PATH . 'excute.php');
?>