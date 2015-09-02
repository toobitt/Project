<?php
require ('./global.php');
define('MOD_UNIQUEID','gongjiao');
define('SCRIPT_NAME', 'BaiduZb');
class BaiduZb extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function count(){}	
	
	public function show()
	{
		if($this->input['database'])
		{
			$this->update_line_station($this->input['database']);
		}
		else 
		{
			$arr = array(1,2);
			
			foreach ($arr as $v)
			{
				$this->update_line_station('dev_gongjiao' . $v);
			}
		}
	}
	
	private function update_line_station($database)
	{
		
		if(!$database)
		{
			return false;
		}
		//处理起始站点
		$sql = "SELECT t1.line_no,t1.line_direct, t2.station_name FROM {$database}." . DB_PREFIX . "line_station t1 
  				LEFT JOIN {$database}." . DB_PREFIX . "station t2 
	  				ON t1.station_id=t2.station_id
	            WHERE t1.line_direct =1 ORDER BY t1.station_no ASC";
            
		
		//echo $sql;
		$q = $this->db->query($sql);
		
		$line_station = array();
		while($r = $this->db->fetch_array($q))
		{
			$line_station[$r['line_no']][] = $r;
		}
		
		
		//hg_pre($line_station,0);
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
			if(!empty($tmp))
			{
				foreach ($tmp as $k => $v)
				{
					$sql = "UPDATE {$database}." . DB_PREFIX . "line SET start_station = '{$v['start_station']}', end_station = '{$v['end_station']}' WHERE line_no = '{$k}'";
    				$this->db->query($sql);
				}
			}
			
		}
			
		//坐标处理
		$sql = "SELECT station_id,location_x,location_y FROM {$database}." . DB_PREFIX . "station WHERE location_x !=0.0 AND location_y!=0.0 AND baidu_x = '' AND baidu_y = '' " . $limit;
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
    			$sql = "SELECT station_id,baidu_x,baidu_y FROM {$database}." . DB_PREFIX . "baidu_zb WHERE md5_key = '{$key}'";
    			
    			$res = $this->db->query_first($sql);
    			if($res['station_id'] && $res['baidu_x'] && $res['baidu_y'])
    			{
    				$sql = "UPDATE {$database}." . DB_PREFIX . "station SET baidu_x = '{$res['baidu_x']}',baidu_y = '{$res['baidu_y']}' WHERE station_id = {$res['station_id']}";
    				$this->db->query($sql);
    			}
    			else 
    			{
    				$sql = "DELETE FROM {$database}." . DB_PREFIX . "baidu_zb WHERE station_id = {$val['station_id']}";
    				$this->db->query($sql);
    				
    				$zb_tmp = array();
    				$zb_tmp = FromGpsToBaidu($val['location_x'].','.$val['location_y'], BAIDU_AK);
    				if(empty($zb_tmp))
	    			{
	    				continue;
	    			}
	    			
	    			$sql = "UPDATE {$database}." . DB_PREFIX . "station SET baidu_x = '{$zb_tmp['x']}',baidu_y = '{$zb_tmp['y']}' WHERE station_id = {$val['station_id']}";
    				$this->db->query($sql);
    				
	    			$sql = "INSERT INTO {$database}." . DB_PREFIX . "baidu_zb SET station_id = '{$val['station_id']}', baidu_x = '{$zb_tmp['x']}', baidu_y = '{$zb_tmp['y']}',md5_key='{$key}'";
	    			$this->db->query($sql);
    			}
    		}
    	}
	}
	
	public function get_condition()
	{
		$condition = '';
		return $condition ;
	}
	public function detail()
	{
	}
}
include(ROOT_PATH . 'excute.php');
?>