<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
define('MOD_UNIQUEID','baidu_rebuild');
set_time_limit(0);
class BaiduZb extends cronBase
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
            'name' => '重建百度坐标',
            'brief' => '重建百度坐标',
            'space' => '5',//运行时间间隔，单位秒
            'is_use' => 1,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }

    
    
    public function run()
    {
    	$limit = ' LIMIT 0,200';
    	$sql = "SELECT station_id,location_x,location_y FROM " . DB_PREFIX . "station WHERE location_x !=0.0 AND location_y!=0.0 AND baidu_x = '' AND baidu_y = '' " . $limit;
    	$q = $this->db->query($sql);
    	while ($r = $this->db->fetch_array($q))
    	{
    		$key = md5($r['station_id'] . $r['location_x'] . $r['location_y']);
    		$baidu_zb[$key] = $r;
    	}
    	//hg_pre($baidu_zb,0);
    	if(!empty($baidu_zb))
    	{
    		foreach ($baidu_zb as $key => $val)
    		{
    			$res = '';
    			$sql = "SELECT station_id,baidu_x,baidu_y FROM " . DB_PREFIX . "baidu_zb WHERE md5_key = '{$key}'";
    			
    			$res = $this->db->query_first($sql);
    			if($res['station_id'] && $res['baidu_x'] && $res['baidu_y'])
    			{
    				$sql = "UPDATE " . DB_PREFIX . "station SET baidu_x = '{$res['baidu_x']}',baidu_y = '{$res['baidu_y']}' WHERE station_id = {$res['station_id']}";
    				$this->db->query($sql);
    			}
    			else 
    			{
    				$sql = "DELETE FROM " . DB_PREFIX . "baidu_zb WHERE station_id = {$val['station_id']}";
    				$this->db->query($sql);
    				
    				$zb_tmp = array();
    				$zb_tmp = FromGpsToBaidu($val['location_x'].','.$val['location_y'], BAIDU_AK);
    				if(empty($zb_tmp))
	    			{
	    				continue;
	    			}
	    			
	    			$sql = "UPDATE " . DB_PREFIX . "station SET baidu_x = '{$zb_tmp['x']}',baidu_y = '{$zb_tmp['y']}' WHERE station_id = {$val['station_id']}";
    				$this->db->query($sql);
    				
	    			$sql = "INSERT INTO " . DB_PREFIX . "baidu_zb SET station_id = '{$val['station_id']}', baidu_x = '{$zb_tmp['x']}', baidu_y = '{$zb_tmp['y']}',md5_key='{$key}'";
	    			$this->db->query($sql);
    			}
    		}
    	}
    }
}

$out = new BaiduZb();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'run';
}
$out->$action();

?>               