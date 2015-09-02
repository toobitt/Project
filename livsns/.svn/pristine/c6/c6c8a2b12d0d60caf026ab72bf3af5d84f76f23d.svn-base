<?php
require('global.php');
define('MOD_UNIQUEID','publicbicycle');//模块标识
define('SCRIPT_NAME', 'bicycleStation');
class bicycleStation extends adminReadBase
{
	public function __construct()
	{
		$this->mPrmsMethods = array(
			'manage'=>'管理',
			'_node'=>array(
				'name'=>'区域',
				'node_uniqueid'=>'station_node',
			),
		);
		
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/station.class.php');
		$this->obj = new station();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index()
	{
	}
	
	function  show()
	{	
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage'));
		
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;					
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		
		$info = $this->obj->show($condition . $data_limit);
		if($info && is_array($info))
		{
			foreach($info as $k => $v)
			{
				$this->addItem($v);
			}			
		}
		$this->output();	
	}

	function detail()
	{	
		$this->verify_content_prms(array('_action'=>'manage'));
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$info = $this->obj->detail($id);
		
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{	
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'station t1 WHERE 1'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 */
	private function get_condition()
	{		
		$condition = '';
		//权限判断
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//不允许查看他们数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND t1.user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND t1.org_id IN ('.$this->user['slave_org'].')';
			}
			
			//节点权限判断
			$authnode = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'];
			if($authnode)
			{
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				$authnode_str = intval($this->input['_id']) ? $authnode_str .',' . $this->input['_id'] : $authnode_str;
				
				$sql = 'SELECT id,childs FROM '.DB_PREFIX.'region WHERE id IN('.$authnode_str.')';
				$query = $this->db->query($sql);
				
				$authnode_array = array();
				while($row = $this->db->fetch_array($query))
				{
					$authnode_array[$row['id']]= explode(',', $row['childs']);
				}
				$authnode_str = '';
				foreach ($authnode_array as $node_id=>$n)
				{
					if($node_id == intval($this->input['_id']))
					{
						$node_father_array = $n;
						if(!in_array(intval($this->input['_id']), $authnode))
						{
							continue;
						}
					}
					$authnode_str .= implode(',', $n) . ',';
				}
				$authnode_str = true ? $authnode_str . '0' : trim($authnode_str,',');
				if(!$this->input['_id'])
				{
					$condition .= ' AND t1.region_id IN(' . $authnode_str . ')';
				}
				else
				{
					$authnode_array = explode(',', $authnode_str);
					if(!in_array($this->input['_id'], $authnode_array))
					{
						if(!$auth_child_node_array = array_intersect($node_father_array, $authnode_array))
						{
							$this->errorOutput(NO_PRIVILEGE);
						}
						$condition .= ' AND t1.region_id IN(' . implode(',', $auth_child_node_array) . ')';
					}
				}
			}
		}
		
		//查询应用分组
		if($this->input['k'])
		{
			$condition .= ' AND t1.name LIKE "%'.trim($this->input['k']).'%"';
		}
		
		//创建者
		if($this->input['user_name'])
		{
			$condition .= " AND t1.user_name = '".trim($this->input['user_name'])."'";
		}
		
		//节点
		if($_id = intval($this->input['_id']))
		{
			$sql = "select childs from " . DB_PREFIX . "region where id = " . $_id;
			$ret =  $this->db->query_first($sql);
			$condition .=" AND t1.region_id IN (" . $ret['childs'] . ")";
		}
		
		//运营单位
		if(isset($this->input['company']) && $this->input['company'] != -1)
		{
			$condition .= ' AND t1.company_id = '.intval($this->input['company']);		
		}
		
		//状态
		if (isset($this->input['state']) && $this->input['state'] != -1)
		{
			$condition .= ' AND t1.state = '.intval($this->input['state']); 
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim($this->input['start_time']));
			$condition .= " AND t1.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim($this->input['end_time']));
			$condition .= " AND t1.create_time <= ".$end_time;
		}
		
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  t1.create_time > ".$yesterday." AND t1.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  t1.create_time > ".$today." AND t1.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  t1.create_time > ".$last_threeday." AND  t1.create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND t1.create_time > ".$last_sevenday." AND t1.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		$condition .=" ORDER BY t1.order_id DESC ";
		
		return $condition;
	}
	
	//获取运营单位名称
	public function get_company()	
	{	
		$sql = "SELECT id,name FROM " . DB_PREFIX . "company WHERE 1 ORDER BY order_id DESC";	
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
		$sql = "SELECT * FROM ". DB_PREFIX . "province";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_city()
	{
		if(!$this->input['province_id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->obj->get_city($this->input['province_id']);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function get_area()
	{
		if(!$this->input['city_id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->obj->get_area($this->input['city_id']);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	
	//根据地图坐标获取地址信息
	public function get_address_by_xy()
	{
		if(!$this->input['location'])
		{
			$this->errorOutput(NO_DATA);
		}
		$url = BAIDU_GEOCODER_DOMAIN . 'ak='  . BAIDU_AK. '&location=' .$this->input['location']. '&output=json';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($ch);
		curl_close($ch);//关闭
		$address = json_decode($response,1);
		$address_arr = $address['result']['addressComponent'];
		if($address_arr)
		{
			$street = $address_arr['district'].$address_arr['street'].$address_arr['street_number'];
		}
		$this->addItem(array('address' => $street));
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');

?>
