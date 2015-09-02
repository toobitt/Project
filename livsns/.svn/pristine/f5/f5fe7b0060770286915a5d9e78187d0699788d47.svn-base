<?php
require ('./global.php');
define('MOD_UNIQUEID','gongjiao');
define('SCRIPT_NAME', 'station');
class station extends outerReadBase
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
		$k = trim($this->input['k']);
		if(!$k)
		{
			$return = array(
				'error_message' => '请输入要查询的站点名称或者开启定位',
			);
			echo json_encode($return);
			exit;
		}
		
		$stationids = array();
		$uniquestations = array();
		$stationnameid = array();
		$stations = array();
		
		$sql = "SELECT * FROM " .DB_PREFIX. "station WHERE station_name LIKE '%" .$k. "%'";
		$query = $this->db->query($sql);
		while ($r = $this->db->fetch_array($query))
		{
			############站点名称处理############
			$r['station_name'] = str_replace('-1', '', $r['station_name']);
			$r['station_name'] = str_replace('-2', '', $r['station_name']);
			#################################
			
			$stationids[$r['station_id']] = $r;
			$stationnameid[$r['station_name']][] = $r['station_id'];
			$uniquestations[$r['station_name']] = $r['station_name'];
			
			$t = array(
				'stationid' 	=> $r['station_id'],
				'stationname' 	=> $r['station_name'],
				'longitude' 	=> $r['location_x'],
				'latitude' 		=> $r['location_y'],
				'blongitude' 	=> $r['baidu_x'],
				'blatitude'	 	=> $r['baidu_y'],
			);
			$stations[$r['station_id']] = $t;
		}
		//hg_pre($stationids);exit;
		if (!$uniquestations)
		{
			$return = array(
				'error_message' => '查询的站点不存在',
				'data' 			=> $stationids,
			);
			echo json_encode($return);exit;
		}
		if (count($uniquestations) == 1  || $uniquestations[$k])
		{
			if($uniquestations[$k])
			{
				$q = $uniquestations[$k];
			}
			else 
			{
				$tmp = array_keys($uniquestations);
				$q = $tmp[0];
			}
			
			
			$arr[] = array(
				'stationid' 	=> implode(',',$stationnameid[$q]),
				'stationname' 	=> $q,
				'longitude'		=> $stations[$stationnameid[$q]['0']]['longitude'],
				'latitude'		=> $stations[$stationnameid[$q]['0']]['latitude'],
				'blongitude'	=> $stations[$stationnameid[$q]['0']]['blongitude'],
				'blatitude'		=> $stations[$stationnameid[$q]['0']]['blatitude'],
				'px'			=> $stations[$stationnameid[$q]['0']]['blatitude'],
			);
			echo json_encode($arr);exit();
		}
		else
		{
			if(!empty($stationnameid))
			{
				foreach($stationnameid as $key => $val)
				{
					$arr[] = array(
						'stationid' 	=> implode(',',$val),
						'stationname' 	=> $key,
						'longitude'		=> $stations[$val[0]]['longitude'],
						'latitude'		=> $stations[$val[0]]['latitude'],
						'blongitude'	=> $stations[$val[0]]['blongitude'],
						'blatitude'		=> $stations[$val[0]]['blatitude'],
						'px'			=> $stations[$val[0]]['blatitude'],
					);
				}
			}
		
			echo json_encode($arr);exit;
		}
		$return = array(
			'error_message' => $error_message,
			'data' 			=> $line,
			'stations' 		=> $stations,
			'row' 			=> $row,
		);
		echo json_encode($return);exit;
	}
	
	public function get_condition()
	{
		
	}
	
	
	public function detail()
	{
		
	}
}
include(ROOT_PATH . 'excute.php');
?>