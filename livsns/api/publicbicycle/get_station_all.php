<?php
define('MOD_UNIQUEID','bicycle_station');
require_once ('./global.php');
define('MOD_UNIQUEID','bicycle_station');
define('SCRIPT_NAME', 'stationAll');
class stationAll extends outerReadBase
{
		
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail(){}
	public function count(){}
	public function show()
	{
		$sql = "SELECT t1.id, t1.name, t1.company_id, t1.station_id, t1.totalnum, t1.currentnum, t1.baidu_latitude, t1.baidu_longitude, t2.host, t2.dir, t2.filepath, t2.filename FROM " . DB_PREFIX . "station t1 
			LEFT JOIN " .DB_PREFIX . "material t2 
				ON t1.material_id=t2.id 
			WHERE t1.state = 1 "; 
		
		$con = $this->get_condition();
		$sql .= $con;
		
		$q = $this->db->query($sql);
		
		while ($ret = $this->db->fetch_array($q))
		{
			//可停车位
			if($ret['totalnum'] && $ret['totalnum']>=$ret['currentnum'])
			{
				$ret['park_num'] = $ret['totalnum'] - $ret['currentnum'];
				unset($ret['totalnum']);
			}
			else 
			{
				$ret['park_num'] = 0;
			}
			
			if($ret['host'] && $ret['dir'] && $ret['filepath'] && $ret['filename'])
			{
				//索引图
				$ret['station_icon'] = array(
						'host'		=>	$ret['host'],
						'dir'		=>	$ret['dir'],
						'filepath'	=>	$ret['filepath'],
						'filename'	=>	$ret['filename'],
				);		
			}
			else 
			{
				$ret['station_icon'] = array();
			}
			unset($ret['host'],$ret['dir'],$ret['filepath'],$ret['filename']);
			
			//zuobiao
			if ($ret['baidu_latitude'] != '')
			{
				$ret['baidu_latitude'] = $ret['baidu_latitude'];
			}
			if ($ret['baidu_longitude'] != '')
			{
				$ret['baidu_longitude'] = $ret['baidu_longitude'];
			}
			
			$this->addItem($ret);
		}
		
		$this->output();
	}	
	
	private function get_condition()
	{
		if($this->input['jd'] || $this->input['wd'])
		{
			//gps坐标转百度坐标
			$baidu_zuobiao = GpsToBaidu($this->input['jd'],$this->input['wd']);
			$this->input['baidu_longitude'] = $baidu_zuobiao['x'];
			$this->input['baidu_latitude'] = $baidu_zuobiao['y'];
		}
		
		
		$distance = $this->input['distance'];
		if ($this->input['baidu_longitude'] && $this->input['baidu_latitude'] && $distance)
		{
			$baidu_longitude = $this->input['baidu_longitude'];
			$baidu_latitude = $this->input['baidu_latitude'];
			
			$range = 180 / pi() * $distance / 6372.797; //里面的 $distance 就代表搜索 $distance 之内，单位km
			$lngR = $range / cos($baidu_latitude * pi() / 180);
			//echo $range;exit()
			$maxLat = $baidu_latitude + $range;//最大纬度
			$minLat = $baidu_latitude - $range;//最小纬度
			$maxLng = $baidu_longitude + $lngR;//最大经度
			$minLng = $baidu_longitude - $lngR;//最小经度
			
			$condition 	.= ' AND t1.baidu_longitude >='.$minLng.' AND t1.baidu_longitude <='.$maxLng
			.' AND t1.baidu_latitude >='.$minLat.' AND t1.baidu_latitude <= '.$maxLat
			.' AND t1.baidu_latitude != "" AND t1.baidu_longitude != ""';
		}
		return $condition;
	}
}
include(ROOT_PATH . 'excute.php');
?>