<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','line_station');
set_time_limit(0);
class LineStation extends cronBase
{
	private $url = '';
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
            'name' => '站点线路关联关系',
            'brief' => '导入站点线路关联关系',
            'space' => '30',//运行时间间隔，单位秒
            'is_use' => 0,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }

    
    
    public function _curl()
    {
    	//获取线路
    	$url = $this->url;
    	
    	
    	if(!$url)
    	{
    		return false;
    	}
		$str = '';//'query=702';
		$url .= $str;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);  
		curl_setopt($ch, CURLOPT_USERPWD, 'test:test'); 
		$response  = curl_exec($ch);
		curl_close($ch);//关闭
		$res = $this->xml2Array($response);
		
		return $res;
    }
    
    public function run()
    {
    	$this->url = 'http://122.227.235.243:8841/bus/search/line.xml?sync=true&query=%%&debug=false&count=124&page=1';
    	$ret = $this->_curl();
    	
    	$line = array();
    	$line = $ret['line'];
    	
		if(!empty($line))
		{
			$line_info = array();
			foreach ($line as $key => $val)
			{
				$this->url = '';
				if(!$val['id'])
				{
					continue;
				}
				
				$this->url = 'http://122.227.235.243:8841/bus/line.xml?id=' . $val['id'];
				
				$ret = '';
				$ret = $this->_curl();
				
				if($ret)
				{
					$line_info[$ret['line']['id']] = $ret['stations']['station'];
				}
			}
			//hg_pre($line_info,0);
			if(!empty($line_info))
			{
				foreach ($line_info as $key => $val)
				{
					
					if(empty($val))
					{
						continue;
					}
					
					$line_no = '';
					if(substr_count($key,'1000'))
					{
						$line_no = substr($key, 4);
						$line_direct = 2;
					}
					else 
					{
						$line_no = $key;
						$line_direct = 1;
					}
					//echo $line_no;
					//exit();
					if(!$line_no)
					{
						continue;
					}
					
					
					foreach ($val as $k => $v)
					{
						$data = array(
							'line_no'		=> $line_no,
							'station_no' 	=> $k + 1,
							'station_id' 	=> $v['id'],
							'line_direct' 	=> $line_direct,
						);
						
						$sql = "INSERT INTO " .DB_PREFIX. "t_line_station SET ";
						foreach($data as $k => $v)
						{
							$sql .= "{$k}='{$v}',";
						}
						$sql = trim($sql,',');
						$this->db->query($sql);
					}
				}
			}
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

$out = new LineStation();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'run';
}
$out->$action();

?>               