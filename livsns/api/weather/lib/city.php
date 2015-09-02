<?php
/**
 * 过滤城市名称，有省，市进行过滤
 */
class city extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 
	 * 城市过滤
	 * @param string $name 城市名称
	 */
	public function filter($name)
	{
		if (!$name)
		{
			return false;
		}	
		if (is_array($this->settings['city']))
		{
			foreach ($this->settings['city'] as $val)
			{
				if (strstr($name, $val))
				{
					$name = str_replace($val, '', $name);
				}					
			}
		}
		return $name;
	}
	function show($conditions = '', $orderby='', $limit='')
	{
		if (!$orderby)
		{
			$orderby = ' ORDER BY ci.id ASC ';
		}
		$sql = 'SELECT c.*,s.inner_func,ci.en_name FROM '.DB_PREFIX.'weather_city_source c 
				LEFT JOIN '.DB_PREFIX.'weather_source s ON s.id = c.source_id 
				LEFT JOIN '.DB_PREFIX.'weather_city ci ON ci.id = c.id 
				WHERE 1 '.$conditions.$orderby.$limit;
		$q = $this->db->query($sql);
		$weather_cities = array();
		while($row = $this->db->fetch_array($q))
		{
			$weather_cities[$row['id']] = $row;
		}
		return $weather_cities;
	}
	public function cityShow($conditions = '',$limit= '')
	{
		$sql = 'SELECT id,name FROM '.DB_PREFIX.'weather_city WHERE 1'.$conditions;
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[$row['id']]= array('id'=>$row['id'],'name'=>$row['name']);
		}
		return $k;
	}
}