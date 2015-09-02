<?php
class pm25core extends InitFrm
{
	protected $city;
	public function __construct($city)
	{
		parent::__construct();
		$this->city = $city;
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function get_city_stations()
	{
		$stations = array();
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'pm25_city_stations WHERE city_name="'.$this->city.'"';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$stations[$row['station_code']] = $row['station_name'];
		}
		return $stations;
	}
	public function update_city_stations($stations)
	{
		$sql = 'DELETE FROM ' . DB_PREFIX . 'pm25_city_stations WHERE city_name="'.$this->city.'"';
		$this->db->query($sql);
		if(is_array($stations['stations']) && $stations['stations'])
		{
			$sql = 'INSERT INTO ' . DB_PREFIX . 'pm25_city_stations(city_name, station_name, station_code) VALUES ';
			foreach($stations['stations'] as $sta)
			{
				$sql .= "('{$this->city}','{$sta['station_name']}', '{$sta['station_code']}'),";
			}
			$sql = trim($sql, ',');
			$this->db->query($sql);
		}
	}
	public function get_pm25_data($conditions = '')
	{
		$pmdata = array();
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'pm25_data WHERE area="'.$this->city.'" ' . $conditions . ' ORDER BY time_point DESC';
		$avg = $this->db->query_first($sql);
		if(!$avg)
		{
			return array();
		}
		$pmdata['avg'] = $avg;
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'pm25_station_data WHERE area="'.$this->city.'" AND time_point='.$avg['time_point'];
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$pmdata['stations'][$row['station_code']] = $row;
		}
		return $pmdata ? $pmdata : array();
	}
	public function update_pm25_data($avg, $data, $time = '')
	{
		//$time = strtotime(trim(str_replace(array('T','Z'), ' ', $time)));
		$sql = 'DELETE FROM ' . DB_PREFIX . 'pm25_station_data WHERE area="'.$this->city.'" AND time_point = "'.$time.'"';
		$this->db->query($sql);
		$sql = 'DELETE FROM ' . DB_PREFIX . 'pm25_data WHERE area="'.$this->city.'" AND time_point = "'.$time.'"';
		$this->db->query($sql);
		if(is_array($data) && $data)
		{
			$sql = 'INSERT INTO ' . DB_PREFIX . 'pm25_station_data(aqi, area, pm2_5,pm2_5_24h,position_name,primary_pollutant,quality,station_code,time_point) VALUES ';
			foreach($data as $pm25)
			{
				$sql .= "('{$pm25['aqi']}','{$pm25['area']}', '{$pm25['pm2_5']}', '{$pm25['pm2_5_24h']}', '{$pm25['position_name']}', '{$pm25['primary_pollutant']}', '{$pm25['quality']}', '{$pm25['station_code']}', '{$time}'),";
			}
			$sql = trim($sql, ',');
			$this->db->query($sql);
		}
		if($avg)
		{
			$sql = 'INSERT INTO ' . DB_PREFIX . 'pm25_data(aqi, area, pm2_5,pm2_5_24h,quality,time_point) VALUE("'.
			$avg['aqi'] . '","' . $avg['area'] . '","' . $avg['pm2_5'] . '","' . $avg['pm2_5_24h'] . '","' . $avg['quality'] . '","' . $time
			.'")';
			$this->db->query($sql);
		}
	}
	public function get_aqi_data($conditions = '')
	{
		$aqidata = array();
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'aqi_data WHERE area="'.$this->city.'" ' . $conditions . ' ORDER BY time_point DESC';
		$avg = $this->db->query_first($sql);
		if(!$avg)
		{
			return array();
		}
		$avg['update_time'] = date('Y/m/d H:i', $avg['time_point']);
		$aqidata['avg'] = $avg;
		/*
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'aqi_station_data WHERE area="'.$this->city.'" AND time_point='.$avg['time_point'];
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$row['update_time'] = date('Y/m/d H:i', $row['time_point']);
			$aqidata['stations'][$row['station_code']] = $row;
		}
		*/
		return $aqidata ? $aqidata : array();
	}
	public function update_aqi_data($avg, $data, $time = '')
	{
		$fileds = array(
		'aqi',
		'area',
		'co',
		'co_24h',
		'no2',
		'no2_24h',
		'o3',
		'o3_24h',
		'o3_8h',
		'o3_8h_24h',
		'pm10',
		'pm10_24h',
		'pm2_5',
		'pm2_5_24h',
		'position_name',
		'primary_pollutant',
		'quality',
		'so2',
		'so2_24h',
		'station_code',
		'time_point',
		);

		//$time = strtotime(trim(str_replace(array('T','Z'), ' ', $time)));
		$_data = array();
		foreach($data as $k=>$v)
		{
			foreach($fileds as $f)
			{
				$_data[$k][$f] = $v[$f];
			}
			$_data[$k]['time_point'] = $time;
		}
		$sql = 'DELETE FROM ' . DB_PREFIX . 'aqi_station_data WHERE area="'.$this->city.'" AND time_point = "'.$time.'"';
		$this->db->query($sql);
		$sql = 'DELETE FROM ' . DB_PREFIX . 'aqi_data WHERE area="'.$this->city.'" AND time_point = "'.$time.'"';
		$this->db->query($sql);

		if(is_array($data) && $data)
		{
			$sql = 'INSERT INTO ' . DB_PREFIX . 'aqi_station_data('.implode(',', $fileds).') VALUES ';
			foreach($_data as $pm25)
			{
				$sql .= '("'.implode('","', $pm25).'"),';
			}
			$sql = trim($sql, ',');
			$this->db->query($sql);
		}
		$_avg = array();

		unset($fileds['14']);
		unset($fileds['19']);

		foreach($fileds as $f)
		{
			$_avg[$f] = $avg[$f];
		}
		$_avg['time_point']= $time;
		//
		if($avg)
		{
			$sql = 'INSERT INTO ' . DB_PREFIX . 'aqi_data('.implode(',', $fileds).') VALUE("'.implode('","', $_avg).'")';
			$this->db->query($sql);
		}
	}
}