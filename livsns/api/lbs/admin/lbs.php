<?php
define('MOD_UNIQUEID','lbs');//模块标识
require_once('./global.php');
require_once(CUR_CONF_PATH.'lib/lbs.class.php');
require_once(CUR_CONF_PATH.'core/lbs.core.php');
class LBS extends adminReadBase
{
	public function __construct()
	{
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'create'	=>'创建',
		'update'	=>'修改',
		'delete'	=>'删除',
		'audit'		=>'审核',
		'_node'         => array(
			'name'=>'lbs分类',
			'filename'=>'lbs_node.php',
			'node_uniqueid'=>'lbs_node',
		),
		);
		parent::__construct();
		$this->lbs = new ClassLBS();
		$this->lbs_field = new lbs_field();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index()
	{
		
	}
	public function show()
	{
		/*********权限验证开始*********/
		$this->verify_content_prms();		
		/*********权限验证结束*********/
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 10;
		$orderby = ' ORDER BY lbs.order_id DESC';
		$res = $this->lbs->show($this->get_condition(),$orderby,$offset,$count);
		if (!empty($res))
		{
			foreach ($res as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	public function get_field()
	{
		if(!$this->input['sortid'])
		{
		 $this->errorOutput('请传分类id');
		}
		$data=$this->lbs_field->handle($this->input['sortid'],$this->input['id']);
		$this->addItem($data);
		$this->output();
	}
	
	
	public function province()
	{
		$data = $this->lbs_field->province();
		$this->addItem($data);
		$this->output();
	}
	
	public function city()
	{
		$province_id = intval($this->input['id']);
		if (!$province_id)
		{
			$this->errorOutput(PROVINCE_ID);
		}		
		$data = $this->lbs_field->city($province_id);
		$this->addItem($data);
		$this->output();
	}
	
	public function area()
	{
		$city_id = intval($this->input['id']);
		if(!$city_id)
		{
			$this->errorOutput(CITY_ID);
		}
		$data = $this->lbs_field->area($city_id);
		$this->addItem($data);
		$this->output();
	}
	
	public function get_condition()
	{		
		$condition = '';		
		/**************权限控制开始**************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{			
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND lbs.user_id = '.$this->user['user_id'];//不允许查看他人数据
			}
			elseif ($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_org'])
			{	
				$condition .= ' AND lbs.org_id IN (' . $this->user['slave_org'] .')';//查看组织内的数据
			}
			if($authnode=$this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
			{
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if($authnode_str)
				{
					$sql = 'SELECT id,childs FROM '.DB_PREFIX.'sort WHERE id IN('.$authnode_str.')';
					$query = $this->db->query($sql);
					$authnode_array = array();
					while($row = $this->db->fetch_array($query))
					{
						$authnode_array[$row['id']]= explode(',', $row['childs']);
					}
					//算出所有允许的节点
					$auth_nodes = array();
					foreach($authnode_array AS $k => $v)
					{
						$auth_nodes = array_merge($auth_nodes,$v);
					}
					
					//如果没有_id就查询出所有权限所允许的节点下的视频包括其后代元素
					if(!$this->input['_id'])
					{
						$condition .= " AND lbs.sort_id IN (".implode(',', $auth_nodes).",0)";
					}
					else if(in_array($this->input['_id'],$auth_nodes))
					{
						if(isset($authnode_array[$this->input['_id']]) && $authnode_array[$this->input['_id']])
						{
							$condition .= " AND lbs.sort_id IN (".implode(',', $authnode_array[$this->input['_id']]).")";
						}
						else 
						{
							$sql = "SELECT id,childs FROM ".DB_PREFIX."sort WHERE id = '" .$this->input['_id']. "'";
							$childs_nodes = $this->db->query_first($sql);
							$condition .= " AND lbs.sort_id IN (".$childs_nodes['childs'].")";
						}
					}
					else 
					{
						$this->errorOutput(NO_PRIVILEGE);
					}
				}
			}
		}
		else 
		{
			if($this->input['_id'])
			{
				$sql = " SELECT childs,fid FROM ".DB_PREFIX."sort WHERE  id = '".$this->input['_id']."'";
				$arr = $this->db->query_first($sql);
				if($arr)
				{
					$condition .= " AND lbs.sort_id IN (".$arr['childs'].")";
				}
			}
		}
		/**************权限控制结束**************/
		if($this->input['k'])
		{
			$condition .= ' AND lbs.title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if ($this->input['id'])
		{
			$condition .= ' AND lbs.id = '.intval($this->input['id']);
		}
		if (isset($this->input['sort_id']))
		{
			$condition .= ' AND lbs.sort_id = '.intval($this->input['sort_id']);
		}
		if (isset($this->input['status']) && $this->input['status'] != -1)
		{
			$condition .= ' AND lbs.status = '.intval($this->input['status']);
		}
		if (intval($this->input['province_id']))
		{
			$condition .= ' AND lbs.province_id = '.intval($this->input['province_id']);
		}
		if (intval($this->input['city_id']))
		{
			$condition .= ' AND lbs.city_id = '.intval($this->input['city_id']);
		}
		if (intval($this->input['area_id']))
		{
			$condition .= ' AND lbs.area_id = '.intval($this->input['area_id']);
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND lbs.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND lbs.create_time <= ".$end_time;
		}
		if($this->input['lbs_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['lbs_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  lbs.create_time > ".$yesterday." AND lbs.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  lbs.create_time > ".$today." AND lbs.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  lbs.create_time > ".$last_threeday." AND lbs.create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND lbs.create_time > ".$last_sevenday." AND lbs.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	public function count()
	{
		$ret = $this->lbs->count($this->get_condition());
		echo json_encode($ret);
	}
	
	public function detail()
	{
		/*********权限验证开始*********/
		$this->verify_content_prms(array('_action'=>'show'));
		/*********权限验证结束*********/
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->lbs->detail($id);
		if ($data && !empty($data) && $data['province_id'] && $data['city_id'])
		{
			$data['now_city_data'] = $this->lbs_field->city($data['province_id']);
			$data['now_area_data'] = $this->lbs_field->area($data['city_id']);
		}
		$this->addItem($data);
		$this->output();
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
	
	
	public function append_sort()
	{
		
		$sql = 'SELECT id,name FROM ' . DB_PREFIX . "sort";
		$q = $this->db->query($sql);
		
		while ($r = $this->db->fetch_array($q))
		{
			$data[$r['id']] = $r['name'];
		}
		
		$this->addItem($data);
		
		$this->output();
	}
	
	public function formexcel()
	{
		$data_prms['_action'] = 'create';
		$this->verify_content_prms($data_prms);
	}
}
$ouput = new LBS();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>