<?php
define('MOD_UNIQUEID','ctrip_city');
define('SCRIPT_NAME', 'ctrip');
define('ROOT_DIR', '../../');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
require_once(CUR_CONF_PATH . 'lib/buffer.class.php');
class ctrip extends coreFrm
{
	private $buffer;
    public function __construct()
	{
		parent::__construct();
		$this->buffer = new buffercore();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'airports_city WHERE 1 ';
		$orderby = ' ORDER BY flag DESC';
		$query = $this->db->query($sql . $orderby);
		while($row = $this->db->fetch_array($query))
		{
			$cities[] = $row;
		}
		if(!$cities)
		{
			$cities = $this->analog();
		}
		if(!$cities)
		{
			$this->errorOutput(GET_AIRPORTS_CITY_ERROR);
		}
		$output['Data'][0] = array(
			'city_id',
			'city_name',
			'city_name_en',
			'city_name_jp',
			'city_code',
			'air_port_code',
			'air_port_name',
			'first_letter',
			'flag',
		);
		foreach($cities as $city)
		{
			$output['Data'][] = array(
				$city['city_id'],
				$city['city_name'],
				$city['city_name_en'],
				$city['city_name_jp'],
				$city['city_code'],
				$city['air_port_code'],
				$city['air_port_name'],
				$city['first_letter'],
				$city['flag'],
				);
		}
		echo json_encode($output);
		exit();
	}
	
	/**
	 * 模拟城市接口
	 * Enter description here ...
	 */
	private function analog()
	{
		if(!defined('CLOUND_AIRPORTS_CITY_API') || !CLOUND_AIRPORTS_CITY_API)
		{
			return '';
		}
		
		$city_json = curlRequest(CLOUND_AIRPORTS_CITY_API);
		if(!$city_json)
		{
			$city_json = curlRequest(CLOUND_AIRPORTS_CITY_API);
		}
		$city_data = json_decode($city_json, true);
		if(is_array($city_data['cities']))
		{
			foreach($city_data['cities'] as $key=>$val)
			{
				$cities[] = array(
				'city_id'=>strval($val['id']),
				'city_name'=>strval($val['name']),
				'city_name_en'=>strval($val['py']),
				'city_name_jp'=>strval($val['jp']),
				'city_code'=>strval($val['code']),
				'air_port_code'=>strval($val['portCode']),
				'air_port_name'=>strval($val['portName']),
				'first_letter'=>strval($val['initial']),
				'flag'=>strval($val['flag']),
				);
			}
		}

		if($cities)
		{
			$fields = array('city_id', 'city_name','city_name_en', 'city_name_jp', 'city_code', 'air_port_code','air_port_name','first_letter','flag');
			$sql = 'REPLACE INTO ' . DB_PREFIX . 'airports_city('.implode(',', $fields).') values';
			foreach($cities as $key=>$val)
			{
				$sql .= '('.'""'.', 
				"'.addslashes($val['city_name']).'",
				"'.addslashes($val['city_name_en']).'",
				"'.addslashes($val['city_name_jp']).'",
				"'.addslashes($val['city_code']).'",
				"'.addslashes($val['air_port_code']).'",
				"'.addslashes($val['air_port_name']).'",
				"'.addslashes($val['first_letter']).'",
				"'.addslashes($val['flag']).'"
				),';
			}
			$sql = trim($sql, ',');
			$this->db->query($sql);
		}
		return $cities;
		
	}
}
include(ROOT_PATH . 'excute.php');
?>