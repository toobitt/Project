<?php
define('MOD_UNIQUEID','cheapbuy');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/product_mode.php');
class product extends adminReadBase
{
	private $mode;
    public function __construct()
	{
	
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'create'	=>'创建',
		'update'	=>'修改',
		'delete'	=>'删除',
		'audit'		=>'审核',
		'_node'         => array(
			'name'=>'商品分类',
			'filename'=>'sort.php',
			'node_uniqueid'=>'sort',
		),
		);
		
		parent::__construct();
		$this->mode = new product_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY t1.order_id DESC,t1.id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		$size = '270x170/';
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				if($v['title'])
				{
					$v['title'] = hg_cutchars($v['title'],8);
				}
				if($v['start_time'] > TIMENOW)
				{
					$v['cheap_status'] = '未开始';
					$v['cheap_color'] = 0;
				}
				else if($v['start_time'] < TIMENOW && $v['end_time'] > TIMENOW)
				{
					$v['cheap_status'] = '进行中';
					$v['cheap_color'] = 1;
				}
				else if($v['end_time'] < TIMENOW)
				{
					$v['cheap_status'] = '已结束';
					$v['cheap_color'] = 2;
				}
				$v['time_now'] = TIMENOW;
				$v['indexpic_url'] = hg_material_link($v['host'], $v['dir'], $v['filepath'], $v['filename'],$size);
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
		/**************权限控制开始**************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND t1.user_id = '.$this->user['user_id'];//不允许查看他人数据
			}
			elseif ($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_org'])
			{
				$condition .= ' AND t1.org_id IN (' . $this->user['slave_org'] .')';//查看组织内的数据
			}
			if($authnode = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
			{
				$authnode_str = '';
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if ($authnode_str === '0')
				{
					$condition .= ' AND t1.sort_id IN(' . $authnode_str . ')';
				}
				if ($authnode_str)
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
						$authnode_str .= implode(',', $n) .',';
					}
					$authnode_str = in_array('0', $authnode) ? $authnode_str .'0' : trim($authnode_str,',');
					if(!$this->input['_id'])
					{
						$condition .= ' AND t1.sort_id IN(' . $authnode_str . ')';
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
							$condition .= ' AND t1.sort_id IN(' . implode(',', $auth_child_node_array) . ')';
						}
					}
				}
				
			}
		}
		/**************权限控制结束**************/
		if($this->input['id'])
		{
			$condition .= " AND t1.id IN (".($this->input['id']).")";
		}
		
		//查询应用分组
		if($this->input['k'])
		{
			$condition .= ' AND t1.title LIKE "%'.trim($this->input['k']).'%"';
		}
		
		//创建者
		if($this->input['user_name'])
		{
			$condition .= " AND t1.user_name = '".trim($this->input['user_name'])."'";
		}
		
		//节点
		if($_id = intval($this->input['_id']))
		{
			$sql = "select childs from " . DB_PREFIX . "sort where id = " . $_id;
			$ret =  $this->db->query_first($sql);
			$condition .=" AND t1.sort_id IN (" . $ret['childs'] . ")";
		}
		
		//运营单位
		if($this->input['company'])
		{
			$condition .= ' AND t1.company_id = '.intval($this->input['company']);		
		}
		
		//状态
		if (isset($this->input['status']) && $this->input['status'] != -1)
		{
			$condition .= ' AND t1.status = '.intval($this->input['status']); 
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
		return $condition;
	}
	
	public function detail()
	{
		$this->verify_content_prms(array('_action'=>'show'));
		
		if($this->input['id'])
		{
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				$ret['live_start_time'] = date('Y-m-d H:i',$ret['live_start_time']);
				$ret['live_end_time']	= date('Y-m-d H:i',$ret['live_end_time']);
				$size = '162x162/';
				$ret['indexpic_url'] = hg_material_link($ret['host'], $ret['dir'], $ret['filepath'], $ret['filename'],$size);
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	
	//获取机构名称
	public function get_company()	
	{	
		$sql = "SELECT id,name FROM " . DB_PREFIX . "company WHERE 1 ORDER BY order_id DESC ";	
		$q = $this->db->query($sql);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$ret[$r['id']] = $r['name'];
		}
		$this->addItem($ret);
		$this->output();
	}	
	
	//获取购买分类
	public function get_buy_type()	
	{	
		$sql = "SELECT id,name FROM " . DB_PREFIX . "buy_type WHERE 1 ORDER BY order_id DESC ";	
		$q = $this->db->query($sql);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$ret[$r['id']] = $r['name'];
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_video_url()
	{
		$vid = $this->input['vid'];
		if(!$vid)
		{
			return false;
		}
		$res = $this->mode->get_video($vid);
		
		$data['video_url'] = $res[0][$vid]['video_url'];
		
		$this->addItem($data);
		
		$this->output();
	}
	
	//获取订单
	public function show_order()
	{
		$this->verify_content_prms(array('_action'=>'show'));
		
		$product_id = intval($this->input['product_id']);
		
		if(!$product_id)
		{
			return false;
		}
		
		if(!$this->input['get_more_order'])
		{
			
			$sql = "SELECT p.id,p.title,p.sort_id,p.company_id,p.type_id,p.fare,m.host,m.dir,m.filepath,m.filename,c.name as company_name,s.name as sort_name FROM ".DB_PREFIX."product p 
				LEFT JOIN ".DB_PREFIX."materials m 
					ON p.indexpic_id = m.id 
				LEFT JOIN ".DB_PREFIX."company c 
					ON p.company_id = c.id 
				LEFT JOIN ".DB_PREFIX."sort s 
					ON p.sort_id = s.id
			WHERE p.id = ".$product_id;
	        $product_info = $this->db->query_first($sql);
	        	
	        if($product_info)
	        {
	       	 	$size = '160x160/';
	        	$product_info['type_name'] 		= $this->settings['buy_type'][$product_info['type_id']];
	        	$product_info['indexpic_url']	= hg_material_link($product_info['host'], $product_info['dir'], $product_info['filepath'], $product_info['filename'],$size);
	        	$data['product_info'] 			= $product_info;
	        }
        }
        else
        {
	        $sql = "SELECT fare FROM ".DB_PREFIX."product WHERE id = ".$product_id;
	        $product_info = $this->db->query_first($sql);
        }


		$cond = '';
		$cond = $this->get_condition_order();
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		$offset = intval(($pp - 1)*$count);	
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$orderby = ' ORDER BY create_time  DESC';
		
		$sql = "SELECT * FROM " . DB_PREFIX . "order WHERE 1 " . $cond . $orderby . $limit;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['order_sum']		= $r['order_sum'] + $product_info['fare'];
			$r['create_time'] 	= date('Y-m-d H:i',$r['create_time']);
			$r['user_name'] 	= $r['user_name'] ? $r['user_name'] : '匿名用户'; 
			$r['fare']			= $product_info['fare'];
			
			switch ($r['status'])
			{
				case  1: $r['audit'] = '已审核';break;
				case  2: $r['audit'] = '已打回';break;
				default: $r['audit'] = '待审核';
			}
			$res[] = $r;
		}
        
        //分页信息
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'order WHERE 1 '.$cond;
		$re = $this->db->query_first($sql);
        $total_num = $re['total'];//总的记录数
		//总页数
		if(intval($total_num%$count) == 0)
		{
			$return['total_page']    = intval($total_num/$count);
		}
		else 
		{
			$return['total_page']    = intval($total_num/$count) + 1;
		}
		$return['total_num'] = $total_num;//总的记录数
		$return['page_num'] = $count;//每页显示的个数
		$return['current_page']  = $pp;//当前页码
		
		$data['info'] = $res;
		$data['page_info'] = $return;
		
		
		$this->addItem($data);
		$this->output();
	}
	
	public function get_condition_order()
	{
		if($this->input['product_id'])
		{
			$condition .= " AND product_id = ".$this->input['product_id'];
		}

		//状态
		if (isset($this->input['status']) && $this->input['status'] != -1)
		{
			$condition .= ' AND status = '.intval($this->input['status']); 
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

	
	public function get_more_order()
	{
		$this->input['get_more_order'] = 1;
		$this->show_order();
	}
	
	public function create_execl()
	{
		$this->verify_content_prms(array('_action'=>'audit'));
		
		$product_id = intval($this->input['id']);
		if(!$product_id)
		{
			return false;
		}
		
		$sql = "SELECT title,fare FROM ".DB_PREFIX."product WHERE id = ".$product_id;
		$res = $this->db->query_first($sql);
		
		$fare = $res['fare'];
		
		$sql = "SELECT * FROM ".DB_PREFIX."order";
		$q = $this->db->query($sql);
		
		while($r = $this->db->fetch_array($q))
		{
			$r['order_sum']		= $r['order_sum'] + $fare;
			$r['create_time'] 	= date('Y/m/d',$r['create_time']);
			$r['user_name'] 	= $r['user_name'] ? $r['user_name'] : '匿名用户'; 
			$r['fare']			= $fare;
			switch ($r['status'])
			{
				case  1: $r['audit'] = '已审核';break;
				case  2: $r['audit'] = '已打回';break;
				default: $r['audit'] = '待审核';
			}
			unset($r['status'],$r['update_time'],$r['order_id'],$r['user_id']);
			$info[] = $r;
		}
		
		$date = date('Y/m/d',TIMENOW);
		header("Content-type:application/vnd.ms-excel;charset=UTF-8");
		header("Content-Disposition:filename=".$res['title']."订单信息_".$date.".xls");
		
		if($this->settings['execl_set'])
		{
			foreach($this->settings['execl_set'] as $k => $v)
			{
				$v= iconv('utf-8', "gb2312", $v);
				if($k != 11)
				{
					echo $v . "\t";
				}
				else
				{
					echo $v . "\t\n";
				}
			}
		}
		if($info)
		{
			foreach($info as $key => $val)
			{
				$len = count($val);
				$i = 0;
				foreach($val as $k => $v)
				{
					$v= iconv('utf-8', "gb2312", $v);
					$i = $i + 1;
					if($i != $len)
					{
						echo $v . "\t";
					}
					else
					{
						echo $v . "\t\n";
					}
				}
			}
		}
	}
	
	/**
	 * 获取频道列表
	 * Enter description here ...
	 */
	public function get_channel()
	{
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$data = $newLive->getChannel();
		if($data)
		{
			foreach ($data as $k => $v)
			{
				$arr[$v['id']] = $v['name'];
			}
		}
		$this->addItem($arr);
		$this->output();
	}
	
	
	public function get_program()
	{
		$channel_ids = $this->input['id'];
		$res = $this->mode->get_live($channel_ids);
		hg_pre($res,0);
	}
	
}

$out = new product();
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