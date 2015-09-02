<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH.'lib/ticket.class.php';
define('MOD_UNIQUEID','ticket');//模块标识
class ticketApi extends adminReadBase
{
	function __construct()
	{
		$this->mPrmsMethods = array(
	
		'manage'			=>'管理',
		'perform_manage'	=>'场次管理',
		'_node'=>array(
			'name'=>'票务类型',
			'filename'=>'ticket_node.php',
			'node_uniqueid'=>'ticket_node',
			),
		);
		parent::__construct();
		$this->ticket = new ticket(); 
		/*
		$this->mNodes = array(
			'ticket_node'	=> '记者列表',
		);
		$this->mPrmsMethods['sale_state'] = array(
										'name' => '售票状态',
										'node' => true,
									);
		*/
	}
	
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	
	function index()
	{
	
	}
	
	
	public function show()
	{
		/*********权限验证开始*********/
		$this->verify_content_prms(array('_action'=>'manage'));
		/*********权限验证结束*********/
		$offset = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
		$count = $this->input['count'] ? intval(urldecode($this->input['count'])) : 10;
		$orderby = ' ORDER BY s.create_time DESC,s.order_id DESC ';
		$condition = $this->get_condition();
		$data = $this->ticket->show($condition,$orderby,$offset,$count);
		if (!empty($data))
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	
	public function count()
	{
		$ret = $this->ticket->count($this->get_condition());
		echo json_encode($ret);
	}
	
	
	public function get_condition()
	{
		$condition = '';
		
		/**************权限控制开始**************/	
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND s.user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_org'])//查看组织内
			{
				$condition .= ' AND s.org_id IN (' . $this->user['slave_org'] .')';
			}
			if($authnode = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
			{
				$authnode_str = '';
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if ($authnode_str === '0')
				{
					$condition .= ' AND s.sort_id IN(' . $authnode_str . ')';
				}
				if($authnode_str)
				{
					$authnode_str = intval($this->input['_id']) ? $authnode_str .',' . $this->input['_id'] : $authnode_str;
					$sql = 'SELECT id,childs FROM '.DB_PREFIX.'sort WHERE id IN('.$authnode_str.')';
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
						$condition .= ' AND s.sort_id IN(' . $authnode_str . ')';
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
							$condition .= ' AND s.sort_id IN(' . implode(',', $auth_child_node_array) . ')';
						}
					}
				}
			}
		}

		/**************权限控制结束**************/
		
		if($this->input['k'])
		{
			$condition .= ' AND s.title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		//分类列表
		if ($this->input['sort_id'] && intval($this->input['sort_id'])!= -1)
		{
			$condition .= ' AND s.sort_id = '.$this->input['sort_id'] ; 
		}
		if ($this->input['_id'])
		{
			$id = intval($this->input['_id']);
			$sql = 'SELECT childs FROM '.DB_PREFIX.'sort WHERE id = '.$id;
			$ret  = $this->db->query_first($sql);
			$ids = $ret['childs'];
			if ($ids)
			{
				$condition .= ' AND s.sort_id IN ('.$ids.')';
			}	
			//$condition .= ' AND s.sort_id = '.$this->input['_id'] ; 
		}
		//查询权重
		if($this->input['start_weight'] && $this->input['start_weight'] != -1)
		{
			$condition .=" AND s.weight >= " . $this->input['start_weight'];
		}
		if($this->input['end_weight'] && $this->input['end_weight'] != -1)
		{
			$condition .=" AND s.weight <= " . $this->input['end_weight'];
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND s.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND s.create_time <= ".$end_time;
		}
		if($this->input['show_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['show_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  s.create_time > ".$yesterday." AND s.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  s.create_time > ".$today." AND s.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  s.create_time > ".$last_threeday." AND s.create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND s.create_time > ".$last_sevenday." AND s.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	
	public function detail()
	{
		$this->verify_content_prms(array('_action'=>'manage'));
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->ticket->detail($id);
		if($data['seat_map'])
		{
			$url = $data['seat_map'];
			$size = '100x75/';
			$data['seat_map'] = hg_material_link($url['host'], $url['dir'], $url['filepath'], $url['filename'],$size);
		}
		$this->addItem($data);
		$this->output();
	}
	
	
	public function all_sort()
	{
		$res = $this->ticket->all_sort();
		$this->addItem($res);
		$this->output();
	}
	
	
	public function	show_opration()
	{
		$this->verify_content_prms(array('_action'=>'manage'));
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$id = intval($this->input['id']);
		$data = $this->ticket->show_opration($id);
		$this->addItem($data);
		$this->output();
	}
	
	//获取场馆信息
	public function get_venue()
	{
		$sql = "SELECT id,venue_name FROM " . DB_PREFIX . "venue ORDER BY order_id DESC";
		$q = $this->db->query($sql);
		$data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$data[$r['id']] = $r['venue_name'];
		}
		
		if(!empty($data))
		{
			foreach ($data as $k => $v)
			{
				$this->addItem_withkey($k, $v);
			}
		}
		$this->output();
	}
	
	//获取明星信息
	public function get_stars()
	{
		$sql = "SELECT id,name FROM " . DB_PREFIX . "star WHERE 1 ";
		
		if($this->input['name'])
		{
			$cond = ' AND name LIKE "%'.trim(urldecode($this->input['name'])).'%"';
			$sql .= $cond;
		}
		else 
		{
			$order_by = " ORDER BY order_id DESC";
			$sql .= $order_by;
		}
		$q = $this->db->query($sql);
		$data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$data[$r['id']] = $r['name'];
		}
		if(!empty($data))
		{
			$this->addItem($data);
		}
		$this->output();
	}
	
	//获取栏目信息
	public function get_column()
	{	
		
		$id = intval($this->input['id']);
		
		if($id)
		{
			$sql = "SELECT column_id FROM " . DB_PREFIX . "show WHERE id = " . $id;
			$res = $this->db->query_first($sql);
		}
		$sql_ = "SELECT column_id as id,title FROM " . DB_PREFIX . "column WHERE 1 AND column_id !='' ORDER BY order_id DESC";	
		$q_ = $this->db->query($sql_);
		$data = array();
		while($r = $this->db->fetch_array($q_))
		{
			$data['column'][] = $r;
		}
		/*if(!empty($data))
		{
			foreach ($data as $k => $v)
			{
				$this->addItem_withkey($k, $v);
			}
		}*/
		
		if($res['column_id'])
		{
			$data['column_id_selected'] = @implode(',',array_keys(unserialize($res['column_id'])));
		}
		else 
		{
			$data['column_id_selected'] = '';
		}
		$this->addItem($data);
		$this->output();
	}
}
$ouput= new ticketApi();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();