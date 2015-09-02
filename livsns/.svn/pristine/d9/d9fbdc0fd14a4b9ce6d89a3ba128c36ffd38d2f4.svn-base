<?php
define('MOD_UNIQUEID','road');
require ('./global.php');
class getRoad extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function detail(){}
	public function count(){}	
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ',' . $count;
        if($this->input['area'])
        {
        	
           /* $sql  = "SELECT r.*,g.title AS sort_name,g.log AS icon, g.color,r.id as roadid ,r.name as roadname FROM ".DB_PREFIX."road_area ra LEFT JOIN " . DB_PREFIX ."road r ON ra.rid=r.id " .
			"LEFT JOIN ".DB_PREFIX."group g " .
			"ON r.group_id = g.id " .
			"WHERE r.state = 1 ". $condition . $data_limit;
			*/
			     $sql = "SELECT r.*,g.title AS sort_name,g.color,g.log AS icon, a.id as areaid ,a.name as areaname" .
                " FROM " . DB_PREFIX . "road r " .
                " LEFT JOIN " . DB_PREFIX . "road_area ra " .
                " ON r.id = ra.rid " .
                " LEFT JOIN " . DB_PREFIX . "area a " .
                " ON ra.aid = a.id " .
                " LEFT JOIN " . DB_PREFIX . "group g " .
                " ON r.group_id = g.id " .
                " WHERE 1  ". $condition . $data_limit;
        }
		else if(!$this->input['expire'])
		{
			$sql  = "SELECT r.*,g.title AS sort_name,g.log AS icon, g.color FROM " . DB_PREFIX ."road r " .
					"LEFT JOIN ".DB_PREFIX."group g " .
							"ON r.group_id = g.id " .
					"WHERE r.state = 1 ". $condition . $data_limit;	
		}
		else
		{
			$sql  = "SELECT r.*,g.title AS sort_name,g.log AS icon, g.color FROM " . DB_PREFIX ."road r " .
					"LEFT JOIN ".DB_PREFIX."group g " .
							"ON r.group_id = g.id " .
					"WHERE r.state = 1 AND r.create_time + r.effect_time*60 >=".TIMENOW . $condition . $data_limit;			
		}
		
		
		$info = $this->db->query($sql);	
		$ret = array();
		$data = array();
		$today = strtotime(date("Y-m-d"));
		$retarea = array();
		while (($row = $this->db->fetch_array($info))!=false) 
		{	
			if($row['create_time']>$today)
			{
				$row['datetime'] = date('H:i',$row['create_time']);	
			}
			else
			{
				$row['datetime'] = date('m-d',$row['create_time']);	
			}
			//$row['create_time'] = date('Y-m-d H:i',$row['create_time']);	
			$row['pic'] = json_decode($row['pic'], true);
			if (!$row['pic']['filename'])
			{
				$row['pic'] = array();
			}
			$row['picsize'] = json_decode($row['picsize'],1);
			$row['icon'] = json_decode($row['icon'],1);
            if($row['areaid'])
            {
            	$retarea[$row['id']][] = $row['areaname'];
            	$retareaid[$row['id']][] = $row['areaid'];
            }
            $row['road_area']    = $retarea[$row['id']];
            $row['road_area_id'] = $retareaid[$row['id']];
            if(empty($row['road_area']))
            {
            	 $row['road_area']    = '';
            	 $row['road_area_id'] = '';
            }
            unset($row['areaid']);
			unset($row['areaname']);
			$ret[$row['id']] = $row;	
		}
		
		if($ret && is_array($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['sort_id'])
		{
			$condition .=" AND r.group_id =" . intval($this->input['sort_id']);
		}
		if($this->input['wd'] || $this->input['jd'])
		{
			$distance = $this->input['distance'] ? intval($this->input['distance']) : 10;	
			$jwd = hg_jwd_square(intval($this->input['wd']),intval($this->input['jd']),$distance);
			$condition .=" AND r.baidu_latitude >=" . $jwd['wd']['min'] ." AND r.baidu_latitude <= " . $jwd['wd']['max'];
			$condition .=" AND r.baidu_longitude >=" . $jwd['jd']['min'] ." AND r.baidu_longitude <= " . $jwd['jd']['max'];
		}
		
		if ($this->settings['jwd']['wd'])
		{
			$condition .= " AND r.baidu_latitude >=" . $this->settings['jwd']['wd']['min'] ." AND r.baidu_latitude <= " . $this->settings['jwd']['wd']['max'];
		}
		if ($this->settings['jwd']['jd'])
		{
			$condition .= " AND r.baidu_longitude >=" . $this->settings['jwd']['jd']['min'] ." AND r.baidu_longitude <= " . $this->settings['jwd']['jd']['max'];
		}
		//2013.07.12 scala
        if ($this->input['is_hot'])
        {
            $condition .= " AND r.is_hot=1 ";
        }
        if ($this->input['area'])
        {
	        $condition .= " AND ra.aid in (".$this->input['area'].") ";
        }
        //2013.07.12 scala end
		//查询排序方式(升序或降序,默认为降序)
		$hgupdown .= $this->input['descasc'] ? $this->input['descasc'] : ' DESC ';
		//根据时间，order_id 和 istop字段排序，istop字段优先级高 create_time<orderid
		$condition .=" ORDER BY (r.create_time + r.effect_time * 60) ". $hgupdown . ",r.orderid " . $hgupdown;
		return $condition ;
	}
}
$out = new getRoad();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>