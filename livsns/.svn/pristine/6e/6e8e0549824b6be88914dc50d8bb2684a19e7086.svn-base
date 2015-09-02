<?php
/**
 *获取停车场对外输出接口
 */
require ('./global.php');
require_once(CUR_CONF_PATH . 'lib/carpark_mode.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
define('MOD_UNIQUEID','get_carpark');
define('SCRIPT_NAME', 'get_carpark');
class get_carpark extends outerReadBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new carpark_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function count(){}	
	
	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		//$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$count = $this->input['count'];
		$condition = $this->get_condition();
		$orderby = '  ORDER BY c.order_id DESC,c.id DESC ';
		if($count)
		{
			$limit = ' LIMIT ' . $offset . ' , ' . $count;
		}
		else 
		{
			$limit = '';
		}
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				//传过来的是GPS坐标
				if($this->input['jd'] || $this->input['wd'])
				{
					//计算距离
					if($v['GPS_x'] && $v['GPS_y'])
					{
						$v['distance'] = GetDistance($v['GPS_y'],$v['GPS_x'],$this->input['wd'], $this->input['jd']);
						$v['distance_format'] = distance_change_unit($v['distance']);
					}
					else
					{
						$v['distance'] = '距离不详';
					}
				}
				$this->addItem($v);
			}
		}
		else
		{
			$this->addItem(array());
		}
		$this->output();
	}
	
	public function detail()
	{
		if($this->input['id'])
		{
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	
	public function get_condition()
	{
		$condition = '';
		//站点名称
		if($this->input['name'])
		{
			$condition .= ' AND c.name LIKE "%'.trim($this->input['name']).'%"';
		}
		
		if($this->input['id'])
		{
			$condition .=" AND c.id =" . intval($this->input['id']);
		}
		
		//根据区域查询
		if($this->input['district_id'])
		{
			$condition .=" AND c.district_id =" . intval($this->input['district_id']);
		}
		
		//根据分类id查询
		if($this->input['type_id'])
		{
			$condition .= " AND c.type_id = " . intval($this->input['type_id']);
		}
		
		//根据坐标来查询（算出最近的停车场即在一定距离范围内）注：传过来的是GPS坐标，库里面存的是百度坐标，所以要转换
		if($this->input['wd'] || $this->input['jd'])
		{
			$distance = $this->input['distance'] ? intval($this->input['distance']) : 10;
			$jwd = hg_jwd_square(intval($this->input['wd']),intval($this->input['jd']),$distance);
			$condition .=" AND c.GPS_y  >=" . $jwd['wd']['min'] ." AND c.GPS_y  <= " . $jwd['wd']['max'];
			$condition .=" AND c.GPS_x >=" . $jwd['jd']['min'] ." AND c.GPS_x <= " . $jwd['jd']['max'];
		}
		
		$condition .= " AND c.status = 2";
		return $condition ;
	}
	
	
	//查询类型
	public function get_type()
	{
		
		$sql = "SELECT id,name FROM " . DB_PREFIX . "carpark_type ORDER BY order_id DESC,id DESC ";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$this->addItem($r);
		}
		
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>