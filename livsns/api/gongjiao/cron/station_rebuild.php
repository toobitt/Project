<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
define('MOD_UNIQUEID','station_rebuild');
class StationRebuild extends cronBase
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
            'name' => '重建起始站点',
            'brief' => '重建起始站点',
            'space' => '5',//运行时间间隔，单位秒
            'is_use' => 1,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }

    
    
    public function run()
    {
    	$sql = "SELECT t1.line_no,t1.line_direct, t2.station_name FROM " . DB_PREFIX . "line_station t1 
  				LEFT JOIN " . DB_PREFIX . "station t2 
	  				ON t1.station_id=t2.station_id
	            WHERE t1.line_direct =1 AND t1.line_no NOT IN (SELECT line_no FROM " . DB_PREFIX . "station_qs)
	            ORDER BY t1.station_no ASC";
            
		$q = $this->db->query($sql);
		
		$line_station = array();
		while($r = $this->db->fetch_array($q))
		{
			$line_station[$r['line_no']][] = $r;
		}
		
		
		//print_r($line_station);
		if(!empty($line_station))
		{
			$tmp = array();
			foreach($line_station as $key => $val)
			{
				if(empty($val))
				{
					continue;
				}
				
				$len = '';
				$len = count($val);
				
				$val[0]['station_name'] = str_replace('-1', '', $val[0]['station_name']);
				$val[0]['station_name'] = str_replace('-2', '', $val[0]['station_name']);
				$tmp[$key]['start_station'] = $val[0]['station_name'];
				if($len > 1)
				{
					$val[$len-1]['station_name'] = str_replace('-1', '', $val[$len-1]['station_name']);
					$val[$len-1]['station_name'] = str_replace('-2', '', $val[$len-1]['station_name']);
					$tmp[$key]['end_station'] 	= $val[$len-1]['station_name'];
				}
			}
			//hg_pre($tmp,0);
			if(!empty($tmp))
			{
				foreach ($tmp as $key => $val)
				{
					$sql = "REPLACE INTO " . DB_PREFIX . "station_qs SET start_station = '{$val['start_station']}', end_station = '{$val['end_station']}', line_no = '{$key}'";
    				$this->db->query($sql);
				}
			}
		}
    }
}

$out = new StationRebuild();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'run';
}
$out->$action();

?>               