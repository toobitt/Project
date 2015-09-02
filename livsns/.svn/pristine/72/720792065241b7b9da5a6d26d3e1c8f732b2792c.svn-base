<?php
require ('./global.php');
define('MOD_UNIQUEID','gongjiao');
define('SCRIPT_NAME', 'Line');
class Line extends outerReadBase
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
		$line_no = $this->input['q'] ? $this->input['q']  : $this->input['routeid'] ;
		if (!$line_no)
		{
			$return = array(
				'error_message' => '请输入要查询的线路名称',
				'data' => $line,
			);
			echo json_encode($return);
			exit;
		}
		
		//$line_no = str_pad($q,4,'0',STR_PAD_LEFT);
		
		$sql = "SELECT * FROM " . DB_PREFIX . "line WHERE line_no = '{$line_no}'";
		$row = $this->db->query_first($sql);
		
		
		//hg_pre($row,0);
		$line = array();
		if (!$row)
		{
			$return = array(
				'error_message' => '没有该线路',
				'data' => $line,
			);
			echo json_encode($return);
			exit;
		}
		else
		{
			$error_message = '0';
		}
		
		$line = array(
			'id' => $row['line_no'],
			'name' => $row['line_name'],
			'gjgs' => '',
			'time' => TIMENOW,
			'kind' => '无',
			'price' =>'',
		);
		
		$buses = array();
		if($this->settings['bus_tab'])
		{
			
			//$sql = "SELECT tab_name FROM " . DB_PREFIX . "tab_record WHERE is_use = 1 WHERE tab_name = 't_bus'";
			
			$sql = "SELECT * FROM " . DB_PREFIX . "bus WHERE line_no='{$row['line_no']}'";
			$q = $this->db->query($sql);
			
			while ($r = $this->db->fetch_array($q))
			{
				if($r['runtime'])
				{
					$r['runtime'] =  date('H:i:s', strtotime($r['runtime']));
				}
				$buses[$r['line_direct']][$r['station_id']] = $r;
			}
		}
		else 
		{
			require_once CUR_CONF_PATH.'data/bus.php';
			
			if(!$row['sub_start_time'] && !$row['sub_stop_time'])
			{
				$buses = $this->bus($line_no);
			}
			else 
			{
				$buses1 = $buses2 = array();
				$buses1 = $this->bus($line_no);
				
				$buses2 = $this->bus($line_no,2);
				
				$buses1 = $buses1 ? $buses1 : array();
				$buses2 = $buses2 ? $buses2 : array();
				$buses = array_merge($buses1,$buses2);
				
			}
		}
		
		//hg_pre($buses,0);
		
		$sql = "SELECT t1.*, t2.* FROM " . DB_PREFIX . "line_station t1 
		  			LEFT JOIN " . DB_PREFIX . "station t2 
		  				ON t1.station_id=t2.station_id
		            WHERE t1.line_no='{$row['line_no']}'
		            ORDER BY t1.line_direct ASC, t1.station_no ASC";
		$q = $this->db->query($sql);
		
		$stations = array();
		$i = array(
			1 => 0,
			2 => 0
		);
		while ($r = $this->db->fetch_array($q))
		{
			############站点名称处理############
			$r['station_name'] = str_replace('-1', '', $r['station_name']);
			$r['station_name'] = str_replace('-2', '', $r['station_name']);
			#################################
			
			if ($r['line_direct'] == 1 && $i[$r['line_direct']] == 0)
			{
				$stationtypename = '主站上客站';
				$stationtypeid = '6';
			}
			elseif ($r['line_direct'] == 2 && $i[$r['line_direct']] == 0)
			{
				$stationtypename = '返回站';
				$stationtypeid = '12';
			}
			else
			{
				$stationtypename = '中间站';
				$stationtypeid = '3';
			}
			
			$t = array(
				'routeid' => $r['line_no'],
				'segmentid' => $r['station_no'],
				'stationid' => $r['station_id'],
				'stationtypeid' => $stationtypeid,
				'stationtypename' => $stationtypename,
				'stationseq' => $r['line_direct'],
				'stationno' => $r['station_no'],
				'stationname' => $r['station_name'],
				'gps' => $r['location_x'] . ',' . $r['location_y'],
				'bgps' => $r['baidu_x'] . ',' . $r['baidu_y'],
				'bus' => ''
			);
			if ($buses[$r['line_direct']][$r['station_id']])
			{
				$bus_tmp = array();
				$bus_tmp = $buses[$r['line_direct']][$r['station_id']];
				$t['bus'] = array(
					'bus_no' 	=> $bus_tmp['bus_no'] ? $bus_tmp['bus_no'] : '',
					'runtime' 	=> $bus_tmp['runtime'] ? $bus_tmp['runtime'] : '',
					'stop'		=> $bus_tmp['stop'] ? $bus_tmp['stop'] : '',
					'brief'		=> $bus_tmp['brief'] ? $bus_tmp['brief'] : '',
					'descr'		=> $bus_tmp['descr'] ? $bus_tmp['descr'] : '',
				);
			}
			$stations[$r['line_direct']][] = $t;
			$i[$r['line_direct']]++;
		}
		$l1 = $i[1] - 1;
		$stations[1][$l1]['stationtypeid'] = '3';
		$stations[1][$l1]['stationtypename'] = '返回站';
		$l2 = $i[2] - 1;
		$stations[2][$l2]['stationtypeid'] = '7';
		$stations[2][$l2]['stationtypename'] = '主站下客站';
		$line['time'] = hg_line_info($row);
		$return = array(
			'error_message' => $error_message,
			'data' => $line,
			'stations' => $stations,
			'row' => $row,
			'row1' => $row1,
			'buses' => $buses
		);
		$return = array(
			array('title' => '开往 ' . $stations[1][0]['stationname'],
			'starttime' => date('H:i', ($row['start_time'])) . '-'  . date('H:i', ($row['stop_time'])),
			'list' => $stations[1]
			),
		);
		
		if($stations[2][0]['stationname'])
		{
			$return[] = array('title' =>  '开往 ' . $stations[2][0]['stationname'],
			'starttime' => date('H:i', ($row['sub_start_time'])) . '-'  . date('H:i', ($row['sub_stop_time'])),
			'list' => $stations[2]
			);
		}
		echo json_encode($return);
		exit;
		
	}
	
	public function get_condition()
	{
		$condition = '';
		return $condition ;
	}
	
	public function bus($line_no,$line_direct=1)
	{
		$sql = "SELECT station_id,station_no FROM " . DB_PREFIX . "line_station WHERE line_no = '{$line_no}' AND line_direct = {$line_direct} ORDER BY station_no DESC LIMIT 1,1";
		$res = $this->db->query_first($sql); 
		
		$station_no = $res['station_no'];
		
		//echo $line_no;
		if(!$res['station_id'])
		{
			$return = array(
				'error_message' => '线路信息有误',
			);
			echo json_encode($return);
			exit;
		}
		$ret = array();
		$ret = line_bus($line_no,$res['station_id'],$line_direct);
		
		//hg_pre($ret);
		//echo $res['station_id'];
		if(!empty($ret))
		{
			
			$station = array();
			$sql = "SELECT t1.station_id,t1.station_no,t2.station_name FROM " . DB_PREFIX . "line_station t1 
						LEFT JOIN " . DB_PREFIX . "station t2 
							ON t1.station_id = t2.station_id
					WHERE t1.line_no = '{$line_no}' AND t1.line_direct = {$line_direct}";
			$q = $this->db->query($sql);
			
			while ($r = $this->db->fetch_array($q))
			{
				$station[$r['station_name'].'_'.$r['station_no']] = $r['station_id'];
			}
			$buses = array();
			//hg_pre($station,1);
			foreach ($ret as $v)
			{
				$station_no_tmp = '';
				$station_no_tmp = $station_no - $v['station_no'];
				$station_no_tmp -= 1;
				
				$station_id = '';
				
				$station_id = $station[$v['station'].'_'.$station_no_tmp];
				
				
				$info[] = $v['station'].'_'.$station_no_tmp;
				if(!$station_id)
				{
					continue;
				}
				$v['bus_no'] = '';
				$v['runtime'] = '';
				$v['station_id'] = $station_id;
				
				$stop = '';
				$stop = ($v['stop'] == 'no') ? '驶离' : '到达';
				
				$runtime = '';
				$runtime = $v['runtime'] ? '于' . $v['runtime'] : '';
				
				$v['descr'] = '车辆' . $v['bus_no'] . '已' . $runtime . $stop . $v['station'] . '，距离本站还有' . $v['station_no'];
				$v['brief'] = '车辆已' . $stop .  '本站';
				$buses[$line_direct][$station_id] = $v;
			}
			
			return $buses;
		}
	}
	public function detail()
	{
	}
}
include(ROOT_PATH . 'excute.php');
?>