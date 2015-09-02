<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
require_once(ROOT_PATH . "lib/class/curl.class.php");
define('MOD_UNIQUEID','station');
set_time_limit(0);
class Station extends cronBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,
            'name' => '导入站点',
            'brief' => '导入站点',
            'space' => '30',//运行时间间隔，单位秒
            'is_use' => 0,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }

    public function run()
    {
    	//获取站点
    	$url = 'http://122.227.235.243:8841/bus/search/station.xml?sync=true&query=%%&debug=false&count=718&page=1';
		$str = '';//'query=702';
		$url .= $str;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);  
		curl_setopt($ch, CURLOPT_USERPWD, 'test:test'); 
		$response  = curl_exec($ch);
		curl_close($ch);//关闭
		$tmp = $this->xml2Array($response);
		$station = $tmp['station'];
		//导入站点
		foreach((array)$station as $key => $val)
		{
			$data = array(); $sql = '';
			$data = array(
				'station_id'		=> $val['id'],
				'station_name' 	=> $val['name'],
				'location_y' 	=> $val['lat'],
				'location_x' 	=> $val['lon'],
				'company_id'		=> 1,
			);
			$sql = "INSERT INTO " .DB_PREFIX. "t_station SET ";
			foreach($data as $k => $v)
			{
				$sql .= "{$k}='{$v}',";
			}
			$sql = trim($sql,',');
			$this->db->query($sql);
		}
		
		$this->addItem('success');
		$this->output();
    }
    
    
	public function xml2Array($xml) 
	{
		//$this->normalizeSimpleXML(simplexml_load_string(mb_convert_encoding($xml,'UTF-8')), $result);
		$this->normalizeSimpleXML(simplexml_load_string($xml), $result);
		return $result;
	}
	
	public function normalizeSimpleXML($obj, &$result) 
	{
		$data = $obj;
		if (is_object($data)) 
		{
			$data = get_object_vars($data);
		}
		if (is_array($data)) 
		{
			foreach ($data as $key => $value) 
			{
				$res = null;
				$this->normalizeSimpleXML($value, $res);
				if (($key == '@attributes') && ($key)) 
				{
					$result = $res;
				}
				else 
				{
					$result[$key] = $res;
				}
			}
		}
		else
		{
			$result = $data;
		}
	}
}

$out = new Station();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'run';
}
$out->$action();

?>               