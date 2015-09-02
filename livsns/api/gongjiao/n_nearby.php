<?php
require ('./global.php');
define('MOD_UNIQUEID','gongjiao');
define('SCRIPT_NAME', 'NearBy');
class NearBy extends outerReadBase
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
		$lat = $this->input['lat'];
		$lng = $this->input['lng'];
		$stationid = $this->input['stationid'];
				
			
		if((!$lat || !$lng) && !$stationid)
		{
			$return = array(
			'error_message' => '请输入要查询的站点名称或者开启定位',
			);
			
			echo json_encode($return);
			exit;
		}
		
		
		/*if($this->input['appid'] == $this->settings['android_appid'])
		{
			$gps_res = FromBaiduToGpsXY($lng,$lat);
			
			if(!empty($gps_res))
			{
				$lng = $gps_res['GPS_x'];
				$lat = $gps_res['GPS_y'];
			}
		}*/
	
		$rad = $this->input['rad'];
		$rad = $rad ? ($rad/1000) : 1;//默认1千米范围
		
		
		if(!$stationid)
		{
			$condition = '';
			if ($lat && $lng && $rad)
			{
				$range = 180 / pi() * $rad / 6372.797; //rad单位km
				$lngR = $range / cos($lat * pi() / 180);
				$maxLat = $lat + $range;//最大纬度
				$minLat = $lat - $range;//最小纬度
				$maxLng = $lng + $lngR;//最大经度
				$minLng = $lng - $lngR;//最小经度
				if($rad <= 10)
				{
					if($this->input['appid'] == $this->settings['android_appid'])
					{
						$condition 	.= ' WHERE baidu_x >='.$minLng.' AND baidu_x <='.$maxLng.' AND baidu_y >='.$minLat.' AND baidu_y <= '.$maxLat;
					}
					else 
					{
						$condition 	.= ' WHERE location_x >='.$minLng.' AND location_x <='.$maxLng.' AND location_y >='.$minLat.' AND location_y <= '.$maxLat;
					}
				}
				else
				{
					$limit = ' limit 0,10';
				}
				$order_by = ' ORDER BY Lng, Lat ASC';
				
				if($this->input['appid'] == $this->settings['android_appid'])
				{
					$sql = "SELECT *,ABS(ABS(baidu_x)-ABS(".$lng.")) AS Lng, ABS(ABS(baidu_y)-ABS(".$lat.")) AS Lat FROM " . DB_PREFIX . "station " . $condition . $order_by . $limit;
				}
				else 
				{
					$sql = "SELECT *,ABS(ABS(location_x)-ABS(".$lng.")) AS Lng, ABS(ABS(location_y)-ABS(".$lat.")) AS Lat FROM " . DB_PREFIX . "station " . $condition . $order_by . $limit;
				}
			}
			else if($q)
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "station WHERE station_name LIKE '%$q%'";
			}
		}
		else
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "station WHERE station_id IN (" . $stationid . ")";
			$station_ids = $stationid;
		}
		
		
		//echo $sql;exit();
		
		$q = $this->db->query($sql);
			
		$station_id = array();
		$station = array();
		while ($r = $this->db->fetch_array($q))
		{
			############站点名称处理############
			$r['station_name'] = str_replace('-1', '', $r['station_name']);
			$r['station_name'] = str_replace('-2', '', $r['station_name']);
			#################################
			
			$station[$r['station_id']]['station_name'] = $r['station_name'];
			$station[$r['station_id']]['location_x'] = $r['location_x'];
			$station[$r['station_id']]['location_y'] = $r['location_y'];
					
			$zb = array();
			//站点表中没记录站点百度坐标，查找坐标缓存文件
			if($r['location_x'] && $r['location_y'] && !$r['baidu_x'] && !$r['baidu_y'])
			{
				if(file_exists('./data/'.$r['station_id'] . '.txt'))
				{
					$res = '';
					$res = file_get_contents('./data/'.$r['station_id'] . '.txt');
					if($res)
					{
						$zb = json_decode($res,1);
						
						$r['baidu_x'] = $zb['baidu_x'];
						$r['baidu_y'] = $zb['baidu_y'];
					}
				}
			}
			
			//缓存文件不存在，将gps坐标转换百度坐标
			if($r['location_x'] && $r['location_y'] && !$r['baidu_x'] && !$r['baidu_y'])
			{
				$zb_tmp = array();
				$zb_tmp = FromGpsToBaidu($r['location_x'].','.$r['location_y'], BAIDU_AK);
				if(!empty($zb_tmp))
				{
					$r['baidu_x'] = $zb_tmp['x'];
					$r['baidu_y'] = $zb_tmp['y'];
					
					$arr_tmp = array(
						'station_id'	=> $r['station_id'],
						'name'			=> $r['station_name'],
						'location_x'	=> $r['location_x'],
						'location_y'	=> $r['location_y'],
						'baidu_x'		=> $r['baidu_x'],
						'baidu_y'		=> $r['baidu_y'],
					);
					$file_name = '';
					$file_name = $r['station_id'] . '.txt';
					
					file_put_contents('./data/' . $file_name,json_encode($arr_tmp));
				}
				else
				{
					$r['baidu_x'] = '';
					$r['baidu_y'] = '';
				}
			}
			
			$station[$r['station_id']]['baidu_x'] = $r['baidu_x'];
			$station[$r['station_id']]['baidu_y'] = $r['baidu_y'];
			$station_id[] = $r['station_id'];
		}
		
		
		if (empty($station_id))
		{
			$return = array(
				'error_message' => '附近没有站点',
			);
			echo json_encode($return);
			exit;
		}
		
		$station_ids = '';
		$station_ids = implode(',', $station_id);
		
		$sql = "SELECT t1.*,t2.*,start_time,stop_time,sub_start_time,sub_stop_time FROM " . DB_PREFIX . "line_station t1 
					LEFT JOIN " . DB_PREFIX . "line t2
						ON t1.line_no = t2.line_no 
				WHERE t1.station_id IN (" . $station_ids . ") ORDER BY t2.line_no ASC";
		
		//echo $sql;
		$q = $this->db->query($sql);
		
		$line_info = array();
		while ($r = $this->db->fetch_array($q))
		{
			if($r['start_time'] && $r['stop_time'])
			{
				$r['start_time'] 		=  date('H:i', $r['start_time']);
				$r['stop_time'] 		=  date('H:i', $r['stop_time']);
			}
			
			if($r['sub_start_time'] && $r['sub_stop_time'])
			{
				$r['sub_start_time'] 	=  date('H:i', $r['sub_start_time']);
				$r['sub_stop_time'] 	=  date('H:i', $r['sub_stop_time']);
			}
			
			$arr = array();
			
			$arr['routeid'] 						= $r['line_no'];
			$arr['segmentid'] 						= $r['station_no'];
			$arr['stationseq'] 						= $r['line_direct'];
			$arr['station_id'] 						= $r['station_id'];
					
			$arr['stationname']						= $station[$r['station_id']]['station_name'];
			$arr['longitude']						= $station[$r['station_id']]['location_x'];
			$arr['latitude']						= $station[$r['station_id']]['location_y'];
			$arr['blongitude']						= $station[$r['station_id']]['baidu_x'];
			$arr['blatitude']						= $station[$r['station_id']]['baidu_y'];
			
			if($r['line_direct'] == 1)
			{
				if($r['start_time'] && $r['stop_time'])
				{
					$arr['starttime']	= $r['start_time'] . '-' . $r['stop_time'];
				}
				else 
				{
					$arr['starttime']	= '';
				}
			}
			else
			{
				if($r['sub_start_time'] && $r['sub_stop_time'])
				{
					$arr['starttime']	= $r['sub_start_time'] . '-' . $r['sub_stop_time'];
				}
				else 
				{
					$arr['starttime']	= '';
				}
			}
					
			$key = '';
			$key = $r['line_no'] . '_' . $r['line_direct'];
			
			
			if($r['line_direct'] == 2)
			{
				$start_tmp = '';
				$start_tmp = $r['end_station'];
				
				$arr['start_station'] = $start_tmp;
				$arr['end_station'] = $r['start_station'];
				
			}
			else 
			{
				$arr['start_station'] = $r['start_station'];
				$arr['end_station'] = $r['end_station'];
			}
			$line_info[$key]['routeid']			= $r['line_no'];
			$line_info[$key]['segmentid'] 		= $r['station_no'];
			$line_info[$key]['segmentname2'] 	= $r['line_name'];
			$line_info[$key]['station'][] 		= $arr;
		}
		
		//hg_pre($line_info,0);
		if($line_info)
		{
			$time = TIMENOW;
			$line_info_tmp = $line_info_tmp1 = array();
			//foreach($station as $key => $value)
			{
				foreach($line_info as $kk => $val)
				{
					foreach($val['station'] as $v)
					{
						//if($v['station_id'] != $key)
						{
							//continue;
						}
						
						$se_time = $start_time = $end_time = '';
						$se_time = explode('-',$v['starttime']);
						if($se_time)
						{
							$start_time = strtotime(date('H:i',strtotime($se_time['0'])));
							$end_time = strtotime(date('H:i',strtotime($se_time['1'])));
						}
						
						
						if($time >= $start_time && $time <= $end_time)
						{
							$line_info_tmp[$kk] = $val;
						}
						else
						{
							$line_info_tmp1[$kk] = $val;
						}
						break;
					}
				}
			}
		}
		
		//hg_pre($line_info_tmp);
		//hg_pre($line_info_tmp1,0);
		if(!empty($line_info_tmp) || !empty($line_info_tmp1))
		{
			$line_info_tmp = array_merge($line_info_tmp,$line_info_tmp1);
			
			//if($_INPUT['debug'])
			{
				$line_info = array();
				foreach($line_info_tmp as $v)
				{
					$line_info[] = $v;
				}
				
				echo json_encode($line_info);
				exit;
			}
			echo json_encode($line_info_tmp);
			exit;
		}
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