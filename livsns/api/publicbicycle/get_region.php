<?php
define('MOD_UNIQUEID','region');
require_once ('./global.php');
require_once CUR_CONF_PATH.'lib/functions.php';
define('SCRIPT_NAME', 'getRegion');
class getRegion extends outerReadBase
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
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ',' . $count;
		
		//ios传入gps坐标，转百度坐标
		if($this->input['jd'] || $this->input['wd'])
		{
			//gps坐标转百度坐标
			$baidu_zuobiao = GpsToBaidu($this->input['jd'],$this->input['wd']);
			$this->input['baidu_longitude'] = $baidu_zuobiao['x'];
			$this->input['baidu_latitude'] = $baidu_zuobiao['y'];
		}
		//如果传入经纬度，筛选区域内最近站点
		if($this->input['baidu_longitude'] || $this->input['baidu_latitude'])
		{
			//查询所有站点，以区域分组
			$sql  = "SELECT name,baidu_longitude,baidu_latitude,region_id FROM " . DB_PREFIX ."station  WHERE state = 1 ";
			$q = $this->db->query($sql);	
			while ($r = $this->db->fetch_array($q))
			{
				if($r['region_id'])
				{
					$data[$r['region_id']][] = $r;
				}
			}
			
			//计算区域里站点与传入的经纬度坐标之间距离
			if($data)
			{
				foreach ($data as $rid => $val)
				{
					foreach ($val as $k => $v)
					{
						if($v['baidu_longitude'] && $v['baidu_latitude'])
						{
							if($this->input['baidu_longitude'] && $this->input['baidu_latitude'])
							{
								$v['distance'] = GetDistance($v['baidu_latitude'], $v['baidu_longitude'], $this->input['baidu_latitude'], $this->input['baidu_longitude'], 1);
							}
							$arr[$rid][] = $v;
						}
					}
				}
			}
			//排出区域中离坐标最近站点
			if($arr)
			{
				foreach ($arr as $rid => $val)
				{
					$len = count($val);
					for ($i=1;$i<$len;$i++)
					{
						for ($j=$len-1;$j>=$i;$j--)
						{
							if($val[$j]['distance']<$val[$j-1]['distance'])
							{
								$x = $val[$j];
								$val[$j] = $val[$j-1];
								$val[$j-1] = $x;
							}
						}
					}
					$distance[$rid] = $val;
				}
			}
		}
		
		$city_name = $this->input['city_name'] ? $this->input['city_name'] : '无锡';
		$sql = "SELECT id FROM ".DB_PREFIX."region WHERE name = '" . $city_name ."'";
		$res = $this->db->query_first($sql);
		$fid = $res['id'];
		
		$condition .= ' AND fid = '.$fid;
		$condition .= ' ORDER BY order_id ASC ';
		$sql  = "SELECT id,name,station_num FROM " . DB_PREFIX ."region  WHERE 1 " . $condition . $data_limit;
		$q = $this->db->query($sql);	
		
		while ($row = $this->db->fetch_array($q)) 
		{
			if(isset($distance[$row['id']]))
			{
				$row['station'] = $distance[$row['id']][0]['name'];
				$row['distance'] = $distance[$row['id']][0]['distance'];
				
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
			$info[]= $row;
			$this->addItem($row);
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		return $condition ;
	}
}
include(ROOT_PATH . 'excute.php');
?>