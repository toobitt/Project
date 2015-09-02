<?php
define('NEED_CHECKIN', true);
define('MOD_UNIQUEID','carpark');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/carpark_mode.php');
class carpark extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mPrmsMethods = array(
			'show'		=> '查看',
			'create'	=> '新增',
		    'update'	=> '更新',
		    'delete'	=> '删除',
			'audit'		=> '审核',
			'_node'=>array(
					'name'=>'停车场地区',
					'filename'=>'carpark_district.php',
					'node_uniqueid'=>'carpark_district',
				),
		);
		$this->mode = new carpark_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		#######权限#######
		$this->verify_content_prms();
		#######权限#######
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY c.order_id DESC,c.id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = '';
		####增加权限控制 用于显示####
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND c.user_id = '.$this->user['user_id'];
			}
			else
			{
				//组织以内
				if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
				{
					$condition .= ' AND c.org_id IN('.$this->user['slave_org'].')';
				}
			}
			if($authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if($authnode_str === '0')
				{
					$condition .= ' AND c.district_id IN(' . $authnode_str . ')';
				}
				if($authnode_str)
				{
					$authnode_str = intval($this->input['_id']) ? $authnode_str .',' . $this->input['_id'] : $authnode_str;
					$sql = 'SELECT id,childs FROM '.DB_PREFIX.'carpark_district WHERE id IN('.$authnode_str.')';
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
						$condition .= ' AND c.district_id IN(' . $authnode_str . ')';
					}
					else
					{
						$authnode_array = explode(',', $authnode_str);
						if(!in_array($this->input['_id'], $authnode_array))
						{
							//
							if(!$auth_child_node_array = array_intersect($node_father_array, $authnode_array))
							{
								$this->errorOutput(NO_PRIVILEGE);
							}
							//$this->errorOutput(var_export($auth_child_node_array,1));
							$condition .= ' AND c.district_id IN(' . implode(',', $auth_child_node_array) . ')';
						}
					}
				}
			}
		}
		if($this->input['_id'])
		{
			$sql = "SELECT childs FROM " . DB_PREFIX	. "carpark_district WHERE id = " . intval($this->input['_id']);
			$ret =  $this->db->query_first($sql);
			$condition .=" AND  c.district_id in (" . $ret['childs'] . ")";
		}
		####增加权限控制 用于显示####
		if($this->input['id'])
		{
			$condition .= " AND c.id IN (".($this->input['id']).")";
		}

		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  c.name  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND c.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND c.create_time <= '".$end_time."'";
		}
		
		//权重
		if($this->input['start_weight'] && $this->input['start_weight'] != -1)
		{
			$condition .= " AND c.weight >=" . $this->input['start_weight'];
		}
		
		if($this->input['end_weight'] && $this->input['end_weight'] != -2)
		{
			$condition .= " AND c.weight <= " . $this->input['end_weight'];
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
					$condition .= " AND  c.create_time > '".$yesterday."' AND c.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  c.create_time > '".$today."' AND c.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  c.create_time > '".$last_threeday."' AND c.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  c.create_time > '".$last_sevenday."' AND c.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}
	
	public function detail()
	{
		#######权限#######
		$this->verify_content_prms(array('_action'=>'show'));
		#######权限#######
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
	
	/***************************************以下是一些扩展功能********************************************************/
	
	public function get_all_carpark()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "carpark ";
		$q = $this->db->query($sql);
		$all_carpark = array();
		while($r = $this->db->fetch_array($q))
		{
			$all_carpark[] = $r;
		}
		$this->addItem($all_carpark);
		$this->output();
	}
	
	//显示省（form）append用到
	public function show_province()
	{
		$ret = $this->mode->show_province();
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	
	//显示市（form）append用到  联动
	public function show_city()
	{
		if(!$this->input['province_id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->show_city($this->input['province_id']);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	
	//显示区（form）append用到  联动
	public function show_area()
	{
		if(!$this->input['city_id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->show_area($this->input['city_id']);
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
		$address = $address['result']['formatted_address'];
		$this->addItem(array('address' => $address));
		$this->output();
	}
	
	//为了产生一个服务时间的小模板
	public function create_server_time_list()
	{
		$ret = array(
			'key' => $this->input['key'],
			'type' => $this->input['type'],
			'name' => $this->input['name'],
		);
		$this->addItem($ret);
		$this->output();
	}
	
	//为了产生一个收费标准的小模板
	public function create_fees_list()
	{
		$ret = array(
			'key' => $this->input['key'],
			'type' => $this->input['type'],
			'name' => $this->input['name'],
		);
		$this->addItem($ret);
		$this->output();
	}
}

$out = new carpark();
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