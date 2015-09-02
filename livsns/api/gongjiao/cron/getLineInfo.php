<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
require_once(ROOT_PATH . "lib/class/curl.class.php");
define('MOD_UNIQUEID','line_info');
set_time_limit(0);
class LineInfo extends cronBase
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
            'name' => '导入线路信息',
            'brief' => '导入线路信息',
            'space' => '30',//运行时间间隔，单位秒
            'is_use' => 0,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }

    public function run()
    {
    	//获取线路
    	$url = 'http://122.227.235.243:8841/bus/search/line.xml?sync=true&query=%%&debug=false&count=124&page=1';
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
		$line = $tmp['line'];
		//导入线路
		foreach((array)$line as $key => $val)
		{
			$data = array(); $sql = '';
			$data = array(
				'line_no'		=> $val['id'],
				'line_name' 		=> $val['name'],
				'rlen' 			=> $val['lat'],
				'Rlen1' 			=> $val['lon'],
				'Rlen2'			=> 1,
				'rtime'			=> 1,
				'start_time'		=> 1,
				'stop_time'		=> 1,
				'sub_start_time'	=> 1,
				'sub_stop_time'	=> 1,
			);
			$sql = "INSERT INTO " .DB_PREFIX. "t_line SET ";
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

$out = new LineInfo();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'run';
}
$out->$action();

?>               