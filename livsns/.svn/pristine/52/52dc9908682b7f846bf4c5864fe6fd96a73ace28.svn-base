<?php
define('MOD_UNIQUEID','gather');//模块标识
require_once ('./global.php');
require_once (CUR_CONF_PATH . 'lib/gather.class.php');
require_once (ROOT_PATH . 'lib/class/material.class.php');
class gatherApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'create'	=>'创建',
		'update'	=>'修改',
		'delete'	=>'删除',
		'audit'		=>'审核',
		'_node'         => array(
			'name'=>'采集分类',
			'filename'=>'gather_node.php',
			'node_uniqueid'=>'gather_node',
		),
		);
		$this->gather = new gather();
		$this->material = new material();			
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	public function show()
	{
		/*********权限验证开始*********/
		$this->verify_content_prms();
		/*********权限验证结束*********/
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20 ;
		$condition = $this->get_condition();
		$orderby = ' ORDER BY g.order_id DESC ';
		$gather = $this->gather->show($condition,$orderby,$offset,$count);
		if($gather && is_array($gather))
		{
			foreach($gather as $val)
			{
				$this->addItem($val);
			}			
		}
		$this->output();				
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->gather->count($condition);
		echo json_encode($info);
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
		$data = $this->gather->detail($id);
		$this->addItem($data);
		$this->output();
	}
		
	private function get_condition()
	{
		$condition = '';
		/**************权限控制开始**************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE && !$this->user['prms']['app_prms'][APP_UNIQUEID]['is_complete'])
		{
			//$condition .= ' AND g.status = 1 ';
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND g.user_id = '.$this->user['user_id'];//不允许查看他人数据
			}
			elseif ($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_org'])
			{
				$condition .= ' AND g.org_id IN (' . $this->user['slave_org'] .')';//查看组织内的数据
			}
			if($authnode = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
			{
				$authnode[] = 0;
				$authnode_str = '';
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if ($authnode_str === '0')
				{
					$condition .= ' AND g.sort_id IN(' . $authnode_str . ')';
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
						$condition .= ' AND g.sort_id IN(' . $authnode_str . ')';
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
							$condition .= ' AND g.sort_id IN(' . implode(',', $auth_child_node_array) . ')';
						}
					}
				}
				
			}
		}
		/**************权限控制结束**************/
		if ($this->input['user_id'])
		{
			$condition .=' AND  g.user_id ='.intval($this->input['user_id']);
		}
		if ($this->input['format_date'])
		{
			$stime = strtotime($this->input['format_date']);//开始时间
			$etime = $stime + 24*60*60;//结束时间
			$condition .= ' AND  g.create_time >='.$stime.' AND g.create_time <='.$etime;
		}
		if ($this->input['sort_id'])
		{
			$condition .=' AND  g.sort_id ='.intval($this->input['sort_id']);
		}
		if ($this->input['_id'])
		{
			$condition .=' AND  g.sort_id ='.intval($this->input['_id']);
		}
		if ($this->input['key'])
		{
			$condition .= ' AND g.title LIKE "%'.trim(urldecode($this->input['key'])).'%"';
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND g.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND g.create_time <= ".$end_time;
		}
        //查询发布的时间
        if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  g.create_time > '".$yesterday."' AND g.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  g.create_time > '".$today."' AND g.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  g.create_time > '".$last_threeday."' AND g.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND g.create_time > '".$last_sevenday."' AND g.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;	
	}
	//ajax分页
	public function show_order()
	{
		$condition = '';
		$condition = $this->get_condition();
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['page_num'] ? intval($this->input['page_num']) : 20;
		$offset = intval(($pp - 1)*$count);			
		$orderby = ' ORDER BY g.order_id  DESC';
		$res = $this->gather->show($condition,$orderby,$offset,$count);
        //分页信息
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'gather g WHERE 1 '.$condition;
		$ret = $this->db->query_first($sql);
        $total_num = $ret['total'];//总的记录数
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
	
	//输出权限，判断是否为编辑
	public function personal_auth()
	{
		$auth = false;
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$prms = $this->user['prms']['app_prms'][APP_UNIQUEID]['is_complete'];
			$auth = $prms ? true : false;
		}
		else
		{
			$auth = true;
		} 
		$this->addItem($auth);
		$this->output();
	}
	
	
}

$out = new gatherApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
