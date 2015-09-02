<?php
define('MOD_UNIQUEID','tv_interact');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/tv_interact_mode.php');
class tv_interact extends adminReadBase
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
			'name'=>'互动分类',
			'filename'=>'tv_interact_node.php',
			'node_uniqueid'=>'tv_interact_node',
		),
		);
		parent::__construct();
		$this->mode = new tv_interact_mode();
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
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		$size = '40x30/';
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				if($v['indexpic'])
				{
					$v['indexpic'] = hg_material_link($v['indexpic']['host'], $v['indexpic']['dir'], $v['indexpic']['filepath'], $v['indexpic']['filename'],$size);
				}
				if($v['start_time'] && $v['end_time'])
				{
					$v['activ_time'] = date('H:i',$v['start_time']) . '-' . date('H:i',$v['end_time']);
				}
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
				if ($authnode_str && $authnode_str!=-1)
				{
					$authnode_str = intval($this->input['_id']) ? $authnode_str .',' . $this->input['_id'] : $authnode_str;
					$sql = 'SELECT id,childs FROM '.DB_PREFIX.'tv_interact_node WHERE id IN('.$authnode_str.')';
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
					//显示无分类
					$authnode_str = $authnode_str . '0';
					
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
			$condition .= ' AND t1.name LIKE "%'.trim($this->input['k']).'%"';
		}
		
		//创建者
		if($this->input['user_name'])
		{
			$condition .= " AND t1.user_name LIKE '%".trim($this->input['user_name'])."%' ";
		}
		
		//节点
		if($_id = intval($this->input['_id']))
		{
			$sql = "select childs from " . DB_PREFIX . "tv_interact_node where id = " . $_id;
			$ret =  $this->db->query_first($sql);
			$condition .=" AND t1.sort_id IN (" . $ret['childs'] . ")";
		}
		
		//审核状态
		if (isset($this->input['status']) && $this->input['status'] != -1)
		{
			$condition .= ' AND t1.status = '.intval($this->input['status']); 
		}
		
		//活动状态
		if (isset($this->input['activ_status']) && $this->input['activ_status'] != -1)
		{
			$activ_status = intval($this->input['activ_status']);
			if($activ_status == 1)
			{
				$condition .= ' AND t1.start_time > '.TIMENOW; 
			}
			else if($activ_status == 2)
			{
				$condition .= ' AND t1.start_time <= ' . TIMENOW . " AND t1.end_time >= " . TIMENOW;
			}
			else if($activ_status == 3)
			{
				$condition .= ' AND t1.end_time < ' . TIMENOW;
			}
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
		
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('id不存在');
		}
		
		$ret = $this->mode->detail($id);
		if($ret)
		{
			if($ret['indexpic'])
			{
				$size = '178x178/';
				$ret['indexpic'] = hg_material_link($ret['indexpic']['host'], $ret['indexpic']['dir'], $ret['indexpic']['filepath'], $ret['indexpic']['filename'],$size);
				
			}
			if($ret['un_start_icon'])
			{
				$size = '82x82/';
				$ret['un_start_icon'] = hg_material_link($ret['un_start_icon']['host'], $ret['un_start_icon']['dir'], $ret['un_start_icon']['filepath'], $ret['un_start_icon']['filename'],$size);
				
			}
			if($ret['sense_icon'])
			{
				$size = '82x82/';
				$ret['sense_icon'] = hg_material_link($ret['sense_icon']['host'], $ret['sense_icon']['dir'], $ret['sense_icon']['filepath'], $ret['sense_icon']['filename'],$size);
				
			}
			if($ret['un_win_icon'])
			{
				$size = '82x82/';
				$ret['un_win_icon'] = hg_material_link($ret['un_win_icon']['host'], $ret['un_win_icon']['dir'], $ret['un_win_icon']['filepath'], $ret['un_win_icon']['filename'],$size);
				
			}
			if($ret['points_icon'])
			{
				$size = '82x82/';
				$ret['points_icon'] = hg_material_link($ret['points_icon']['host'], $ret['points_icon']['dir'], $ret['points_icon']['filepath'], $ret['points_icon']['filename'],$size);
				
			}
			$this->addItem($ret);
			$this->output();
		}
		
	}
	
	public function show_win_info_more()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('活动id不存在');
		}
		
		
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['count'] ? intval($this->input['count']) : 12;
		$offset = intval(($pp - 1)*$count);	
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$orderby = '  ORDER BY red_bag DESC,create_time  ASC ';
		
		$sql = "SELECT * FROM " . DB_PREFIX . "win_info WHERE tv_interact_id = " . $id . $orderby . $limit;
		$q = $this->db->query($sql);
		
		$member_id = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['create_time']	= date('Y-m-d H:i',$r['create_time']);
			$info[] 			= $r;
			$member_id[] 		= $r['member_id'];
		}
		if(!empty($member_id))
		{

			include_once ROOT_DIR . 'lib/class/members.class.php';
			
			$obj = new members();
			$member_id 			= implode(',', $member_id);
			$member_info	 	= array();
			$member_info_tmp 	= array();
			$member_info_tmp 	= $obj->get_member_info($member_id);
			if(!empty($member_info_tmp))
			{
				$size = '82x62/';
				foreach ($member_info_tmp as $val)
				{
					$member_info[$val['member_id']]['member_name'] 	= $val['member_name'];
					if(!empty($val['avatar']))
					{
						$member_info[$val['member_id']]['avatar']	= hg_material_link($val['avatar']['host'], $val['avatar']['dir'], $val['avatar']['filepath'], $val['avatar']['filename'],$size);
					}
					else 
					{
						$member_info[$val['member_id']]['avatar']	= array();
					}
					$member_info[$val['member_id']]['phone_num']	= $val['mobile'];
				}
			}
		}
		if(!empty($info))
		{
			foreach ($info as $val)
			{
				foreach ($val as $k => $v)
				{
					if($k == 'member_id' && $member_info[$v])
					{
						$val['member_name'] 	= $member_info[$v]['member_name'];
						$val['phone_num']	 	= $member_info[$v]['phone_num'];
						$val['avatar']	 		= $member_info[$v]['avatar'];
					}
				}
				$ret[] = $val;
			}
		}
		
		//分页信息
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'win_info WHERE 1 AND tv_interact_id = ' . $id;
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
		$return['total_num'] 		= $total_num;//总的记录数
		$return['page_num'] 		= $count;//每页显示的个数
		$return['current_page']  	= $pp;//当前页码
		
		$data['info'] 		= $ret;
		$data['page_info'] 	= $return;
		
		$this->addItem($data);
		$this->output();
	}
	
	public function show_win_info()
	{
		$this->verify_content_prms(array('_action'=>'show'));
		
		$id = intval($this->input['id']);
		
		if(!$id)
		{
			$this->errorOutput('id不存在');
		}
		$sql = "SELECT name FROM " . DB_PREFIX . "tv_interact WHERE id = " . $id;
		$res = $this->db->query_first($sql);
		
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'win_info WHERE 1 AND tv_interact_id = ' . $id;
		$re = $this->db->query_first($sql);
        $total_num = $re['total'];//总的记录数
        
		$name = $res['name'];
		$data['name'] 		= $name;
		$data['id'] 		= $id;
		$data['total']		= $total_num;
		
		$this->addItem($data);
		$this->output();
	}
}

$out = new tv_interact();
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