<?php
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','scenic_survey');//模块标识
class scenicSpotsApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/scenic_spots.class.php');
		$this->obj = new scenicSpots();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function  show()
	{	
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$ret = $this->obj->show($condition,$limit);	
		$this->addItem($ret);	
		$this->output();		
	}

	function detail()
	{	
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'scenic_spots WHERE id = '.$this->input['id'];
		$r = $this->db->query_first($sql);
		//$sq = "select name from " . DB_PREFIX . "scenic_sort where id = ".$r['sort_id'];
		//$sort_name = $this->db->query_first($sq);
		//$r['sort_name'] = $sort_name['name'];
		$sql_ = 'SELECT introduce
				 FROM '.DB_PREFIX.'scenic_spots_introduce  WHERE scenic_spots_id = '.$this->input['id'];
		$q = $this->db->query_first($sql_);
		$r['introduce'] = $q['introduce'];
		$ret[] = $r;
		$this->addItem($ret);
		$this->output();
	}

	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{	
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'templates WHERE 1 '.$this->get_condition();
		$templates_total = $this->db->query_first($sql);
		echo json_encode($templates_total);	
	}
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{		
		$condition = '';
		//查询应用分组
		if($this->input['k'])
		{
			$condition .= ' AND title LIKE "%'.trim($this->input['k']).'%"';
		}
		if (isset($this->input['status']) && $this->input['status'] != -1)
		{
			
			$condition .= ' AND status = '.$this->input['status'] ; 
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim($this->input['start_time']));
			$condition .= " AND create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim($this->input['end_time']));
			$condition .= " AND create_time <= ".$end_time;
		}
		if($this->input['create_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['create_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > ".$yesterday." AND create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > ".$today." AND create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > ".$last_threeday." AND  create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	//获取专题分类名称
	public function get_sort()	
	{	
		$sql = "select id,name from " . DB_PREFIX . "scenic_sort where 1";	
		$q = $this->db->query($sql);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$ret[$r['id']] = $r['name'];
		}
		$this->addItem($ret);
		$this->output();
	}	
	

	//获取城市
	public function get_city_name()
	{	
		$ret = CITY_NAME;
		$this->addItem($ret);
		$this->output();
	}
	
	//获取地图
	public function get_map()
	{	
		$city_name = CITY_NAME;
		$address = $this->input['address'];
		if($address)
		{
			$area_name = $address;
		}
		else
		{
			$area_name = $city_name;
		}
		$this->addItem($area_name);
		$this->output();
	}
	
	public function get_province()
	{	
		$sql = "SELECT * FROM ". DB_PREFIX . "province where 1";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$ret[$r['id']] = $r['name'];
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_city()
	{	
		$province_id = intval($this->input['province']);
		$filename = CUR_CONF_PATH."cache/city.txt";
		if($ret = unserialize(file_get_contents($filename)))
		{	
			foreach($ret as $k=>$v)
			{	
				if($province_id == $v['province_id'])
				{
					$re[$v['id']] = $v['city'];
				}
				
			}
			$this->addItem($re);
			$this->output();
		}
		else 
		{
			$sql = "SELECT * FROM ". DB_PREFIX . "city where 1 ";
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				$city[] = $r;
			}
			file_put_contents($filename,serialize($city));
		}
	}
	
	public function get_area()
	{	
		$city_id = intval($this->input['city']);
		$filename = CUR_CONF_PATH."cache/area.txt";
		if($ret = unserialize(file_get_contents($filename)))
		{	
			foreach($ret as $k=>$v)
			{	
				if($city_id == $v['city_id'])
				{
					$re[$v['id']] = $v['area'];
				}
				
			}
			$this->addItem($re);
			$this->output();
		}
		else 
		{
			$sql = "SELECT * FROM ". DB_PREFIX . "area where 1 ";
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				$area[] = $r;
			}
			file_put_contents($filename,serialize($area));
		}
	}
}

$out = new scenicSpotsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
