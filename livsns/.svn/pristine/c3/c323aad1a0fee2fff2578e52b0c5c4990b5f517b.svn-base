<?php
define('MOD_UNIQUEID','lbs');//模块标识
require_once('./global.php');
require_once(CUR_CONF_PATH.'lib/lbs.class.php') ;
require_once(CUR_CONF_PATH.'core/lbs.core.php') ;
class LBSApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->lbs = new ClassLBS();
		$this->lbs_field = new lbs_field();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 20;

		//如果百度坐标存在的话，就转换为GPS坐标
		if ($this->input['local_GPS_longitude'] && $this->input['local_GPS_latitude'])
		{
			$local_GPS_longitude = $this->input['local_GPS_longitude'];
			$local_GPS_latitude = $this->input['local_GPS_latitude'];
		}
		elseif ($this->input['local_baidu_longitude'] && $this->input['local_baidu_latitude'])
		{
			$local_baidu_longitude = $this->input['local_baidu_longitude'];
			$local_baidu_latitude = $this->input['local_baidu_latitude'];
			$gps = $this->lbs->FromBaiduToGpsXY($local_baidu_longitude, $local_baidu_latitude);
			$local_GPS_longitude = $gps['GPS_x'];
			$local_GPS_latitude = $gps['GPS_y'];
		}
		//默认1km
		$distance = $this->input['distance'];
		$orderby = ' ORDER BY order_id  DESC';
		$condition = $this->get_condition();
		$need_brief = intval($this->input['need_brief']);
		$res = $this->lbs->show($condition, $orderby, $offset, $count, $local_GPS_longitude, $local_GPS_latitude, $distance,$need_brief);
		if (!empty($res))
		{
			$need_extend = intval($this->input['need_extend']);
			foreach ($res as $key=>$val)
			{
				$field = '';
				if($need_extend && $val['sort_id'])
				{
					$field = $this->lbs_field->handle($val['sort_id'],$val['id']);
					if ($field)
					{
						$val['field'] = $field;
					}
					else 
					{
						$val['field'] = array();
					}
				}
				else 
				{
					$val['field'] = array();
				}
				$this->addItem($val);
			}
		}
		else 
		{
			$this->addItem(array());
		}
		$this->output();
	}

	public function get_field()
	{
		if(!$this->input['sort_id'])
		{
		 $this->errorOutput('请传分类id');
		}
		$data=$this->lbs_field->handle($this->input['sort_id'],$this->input['id']);
		foreach ($data as $val)
		{
			$this->addItem($val);
		}
		$this->output();
	}

	public function province()
	{
		$data=$this->lbs_field->province();
		foreach ($data as $val)
		{
			$this->addItem($val);
		}
		$this->output();
	}

	public function city()
	{
		$province_id = intval($this->input['id']);
		if (!$province_id)
		{
			//$this->errorOutput(PROVINCE_ID);
		}
		$data=$this->lbs_field->city($province_id);
		foreach ($data as $val)
		{
			$this->addItem($val);
		}
		$this->output();
	}

	public function area()
	{
		$city_id = intval($this->input['id']);
		if(!$city_id)
		{
			//$this->errorOutput(CITY_ID);
		}
		$data=$this->lbs_field->area($city_id);
		foreach ($data as $val)
		{
			$this->addItem($val);
		}
		$this->output();
	}

	public function get_condition()
	{
		$condition = ' AND lbs.status = 1';

		//如果百度坐标存在的话，就转换为GPS坐标
		if ($this->input['local_GPS_longitude'] && $this->input['local_GPS_latitude'])
		{
			$local_GPS_longitude = $this->input['local_GPS_longitude'];
			$local_GPS_latitude = $this->input['local_GPS_latitude'];
		}
		elseif ($this->input['local_baidu_longitude'] && $this->input['local_baidu_latitude'])
		{
			$local_baidu_longitude = $this->input['local_baidu_longitude'];
			$local_baidu_latitude = $this->input['local_baidu_latitude'];
			$gps = $this->lbs->FromBaiduToGpsXY($local_baidu_longitude, $local_baidu_latitude);
			$local_GPS_longitude = $gps['GPS_x'];
			$local_GPS_latitude = $gps['GPS_y'];
		}
		//默认1km
		$distance = $this->input['distance'];
		
		
		$distance_id = intval($this->input['distance_id']);
		if($distance_id && $this->settings['lbs_distance'][$distance_id])
		{
			if($distance_id == 1)
			{
				$distance = 0;
			}
			else 
			{
				$distance = $this->settings['lbs_distance'][$distance_id]/1000;
			}
			
		}
		if ($local_GPS_longitude && $local_GPS_latitude && $distance)
		{
			$range = 180 / pi() * $distance / 6372.797; //里面的 $distance 就代表搜索 $distance 之内，单位km
			$lngR = $range / cos($local_GPS_latitude * pi() / 180);
			//echo $range;exit()
			$maxLat = $local_GPS_latitude + $range;//最大纬度
			$minLat = $local_GPS_latitude - $range;//最小纬度
			$maxLng = $local_GPS_longitude + $lngR;//最大经度
			$minLng = $local_GPS_longitude - $lngR;//最小经度
			$condition 	.= ' AND lbs.GPS_longitude >='.$minLng.' AND lbs.GPS_longitude <='.$maxLng
			.' AND lbs.GPS_latitude >='.$minLat.' AND lbs.GPS_latitude <= '.$maxLat
			.' AND lbs.GPS_longitude != 0.00000000000000 AND lbs.GPS_latitude != 0.00000000000000 ';
		}
		if($this->input['k'])
		{
			$condition .= ' AND lbs.title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if ($this->input['id'])
		{
			$condition .= ' AND lbs.id = '.intval($this->input['id']);
		}
		if ($this->input['_id'])
		{
			$condition .= ' AND lbs.sort_id = '.$this->input['_id'] ;
		}
		if (isset($this->input['sort_id']))
		{
			$condition .= ' AND lbs.sort_id = '.intval($this->input['sort_id']);
		}
		
		if(intval($this->input['sort_ids']))
		{
			$sql = "SELECT childs FROM " . DB_PREFIX . "sort WHERE id = " . $this->input['sort_ids'];
			$childs = $this->db->query_first($sql);
			
			if($childs['childs'])
			{
				$condition .= ' AND lbs.sort_id IN (' . $childs['childs'] . ')';
			}
			else 
			{
				$condition .= ' AND lbs.sort_id = '.intval($this->input['sort_ids']);
			}
		}
		if (isset($this->input['status']) && $this->input['status'] != -1)
		{
			$condition .= ' AND lbs.status = '.intval($this->input['status']);
		}
		if (intval($this->input['province_id']))
		{
			$condition .= ' AND lbs.province_id = '.intval($this->input['province_id']);
		}
		if (intval($this->input['city_id']))
		{
			$condition .= ' AND lbs.city_id = '.intval($this->input['city_id']);
		}
		if (intval($this->input['area_id']))
		{
			$condition .= ' AND lbs.area_id = '.intval($this->input['area_id']);
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND lbs.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND lbs.create_time <= ".$end_time;
		}
		return $condition;
	}

	public function count()
	{

		$ret = $this->lbs->count($this->get_condition());
		echo json_encode($ret);
	}

	public function detail()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		//如果百度坐标存在的话，就转换为GPS坐标
		if ($this->input['local_GPS_longitude'] && $this->input['local_GPS_latitude'])
		{
			$local_GPS_longitude = $this->input['local_GPS_longitude'];
			$local_GPS_latitude = $this->input['local_GPS_latitude'];
		}
		elseif ($this->input['local_baidu_longitude'] && $this->input['local_baidu_latitude'])
		{
			$local_baidu_longitude = $this->input['local_baidu_longitude'];
			$local_baidu_latitude = $this->input['local_baidu_latitude'];
			$gps = $this->lbs->FromBaiduToGpsXY($local_baidu_longitude, $local_baidu_latitude);
			$local_GPS_longitude = $gps['GPS_x'];
			$local_GPS_latitude = $gps['GPS_y'];
		}
		$data = $this->lbs->detail($id, $local_GPS_longitude, $local_GPS_latitude);
		if($data['sort_id'])
		{
			$data['field']=$this->lbs_field->handle($data['sort_id'],$this->input['id']);
		}
		
		$this->addItem($data);
		$this->output();
	}
}
$ouput = new LBSApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>