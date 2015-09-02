<?php
require ('./global.php');
require_once CUR_CONF_PATH.'lib/functions.php';
define('MOD_UNIQUEID','bicycle_station');
define('SCRIPT_NAME', 'getBicycleStation');
class getBicycleStation extends outerReadBase
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
		//ios传入gps坐标，转百度坐标
		if($this->input['jd'] || $this->input['wd'])
		{
			//gps坐标转百度坐标
			$baidu_zuobiao = GpsToBaidu($this->input['jd'],$this->input['wd']);
			$this->input['baidu_longitude'] = $baidu_zuobiao['x'];
			$this->input['baidu_latitude'] = $baidu_zuobiao['y'];
		}
		$cond = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$limit = ' LIMIT ' . $offset . ',' . $count;
		
		$sql = "SELECT t1.id,t1.name,t1.company_id,t1.currentnum,t1.totalnum,t1.station_id,t1.address,t1.stationx, t1.stationy, t1.baidu_latitude, t1.baidu_longitude, t3.station_icon FROM " . DB_PREFIX . "station t1 
				LEFT JOIN " . DB_PREFIX . "company t3 
					ON t1.company_id = t3.id 
				WHERE 1 " . $cond; 
		$q = $this->db->query($sql);
		
		while ($row = $this->db->fetch_array($q)) 
		{	
			if($row['baidu_longitude'] && $row['baidu_latitude'])
			{
				
				if($this->input['baidu_longitude'] || $this->input['baidu_latitude'])
				{
					//计算距离
					$row['distance'] = GetDistance($row['baidu_latitude'], $row['baidu_longitude'], $this->input['baidu_latitude'], $this->input['baidu_longitude'], 1);
				}
			}
			
			$row['station_icon'] = unserialize($row['station_icon']);
			if (!$row['station_icon']['filename'])
			{
				$row['station_icon'] = array();
			}
			
			if($row['totalnum'] && $row['totalnum']>=$row['currentnum'])
			{
				$row['park_num'] = $row['totalnum'] - $row['currentnum'];
			}
			else 
			{
				$row['park_num'] = 0;
			}
			$row['currentnum'] = intval($row['currentnum']);
			unset($row['totalnum']);
			
			$info[] = $row;
		}
		if($info)
		{
			//按距离由近到远排序
			$len = count($info);
			for ($i=1;$i<$len;$i++)
			{
				for ($j=$len-1;$j>=$i;$j--)
				{
					if($info[$j]['distance'] < $info[$j-1]['distance'])
					{
						$x = $info[$j-1];
						$info[$j-1] = $info[$j];
						$info[$j] = $x;
					}
				}
			}
			
			//根据偏移量返回结果
			if($offset)
			{
				$station_num = 0;
				foreach ($info as $k => $v)
				{
					if($station_num == $count)
					{
						break;
					}
					if($k >= $offset)
					{
						$station_num = $station_num + 1;
						$info_limit[] = $v;
					}
				}
			}
			else 
			{
				foreach ($info as $k => $v)
				{
					if($station_num == $count)
					{
						break;
					}
					
					$station_num = $station_num + 1;
					$info_limit[] = $v;
				}
			}
			$info = array();
			$info = $info_limit;
			//单位换算
			foreach ($info as $k => $row)
			{
				if($row['distance'])
				{
					if($row['distance'] > 1000)
					{
						$row['distance'] /= 1000;
						$row['distance'] .= 'km'; 
					}
					else 
					{
						$row['distance'] .= 'm';
					}
				}
				else
				{
					$row['distance'] .= '距离不详';
				}
				$this->addItem($row);
			}
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		//站点名称
		if($this->input['name'])
		{
			$condition .= ' AND t1.name LIKE "%'.trim($this->input['name']).'%"';
		}
		//运营单位
		if(isset($this->input['company']) && $this->input['company'] != -1)
		{
			$condition .= ' AND t1.company_id = '.intval($this->input['company']);		
		}
		//根据区域查询
		if($this->input['region_id'])
		{
			$condition .=" AND t1.region_id =" . intval($this->input['region_id']);
		}
		//限定最近范围
		/*if($this->input['baidu_longitude'] || $this->input['baidu_latitude'])
		{
			$distance = $this->input['distance'] ? intval($this->input['distance']) : 100;
			
			$jwd = hg_jwd_square(intval($this->input['baidu_latitude']),intval($this->input['baidu_longitude']),$distance);
			$condition .=" AND t1.baidu_longitude >=" . $jwd['jd']['min'] ." AND t1.baidu_longitude <= " . $jwd['jd']['max'];
			$condition .=" AND t1.baidu_latitude >=" . $jwd['wd']['min'] ." AND t1.baidu_latitude  <= " . $jwd['wd']['max'];
		}*/
		
		//限定在无锡范围内
		/*if ($this->settings['jwd']['wd'])
		{
			$condition .= " AND t1.stationy >=" . $this->settings['jwd']['wd']['min'] ." AND t1.stationy <= " . $this->settings['jwd']['wd']['max'];
		}
		if ($this->settings['jwd']['jd'])
		{
			$condition .= " AND t1.stationx >=" . $this->settings['jwd']['jd']['min'] ." AND t1.stationx <= " . $this->settings['jwd']['jd']['max'];
		}*/
		
		//查询排序方式(升序或降序,默认为降序)
		$hgupdown .= $this->input['descasc'] ? $this->input['descasc'] : ' DESC ';
		
		//create_time<orderid
		//$condition .=" ORDER BY t1.create_time ". $hgupdown . ",t1.order_id " . $hgupdown;
		
		$condition .= " AND t1.state = 1";
		return $condition ;
	}
}
include(ROOT_PATH . 'excute.php');
?>