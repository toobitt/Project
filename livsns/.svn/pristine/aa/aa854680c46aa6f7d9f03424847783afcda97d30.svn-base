<?php
define('MOD_UNIQUEID','survey');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/survey_mode.php');
class survey extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'manage'	=>'管理',
		'audit'		=>'状态',
		'_node'=>array(
			'name'=>'问卷分类',
			'filename'=>'survey_node.php',
			'node_uniqueid'=>'survey_node',
			),
		);
		parent::__construct();
		$this->mode = new survey_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$this->verify_content_prms();
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		//分页信息
		if($this->input['cite'])
		{
			$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'survey WHERE 1 '.$condition;
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
			$pp = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页
			if($pp > $return['total_page'])
			{
				$pp = $return['total_page'];
			}
			$return['current_page']  = $pp;//当前页码
			$offset = intval(($pp - 1)*$count) > 0 ? intval(($pp - 1)*$count) : 0;
			//$return['offset']  = $offset;
			//$ret['info'] = $res;
			$ret['page_info'] = $return;
		}
		else
		{
			$offset = $this->input['offset'] ? $this->input['offset'] : 0;	
		}		
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		if($this->input['cite'])
		{
			$ret['info'] = $this->mode->show($condition,$orderby,$limit);
		}
		else
		{
			$ret = $this->mode->show($condition,$orderby,$limit);
			
		}
		if(!empty($ret))
		{
			$this->addItem($ret);
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
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		####增加权限控制 用于显示####
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else
			{
				//组织以内
				if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
				{
					$condition .= ' AND org_id IN('.$this->user['slave_org'].')';
				}
			}
			if($authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if($authnode_str === '0')
				{
					$condition .= ' AND node_id IN(' . $authnode_str . ')';
				}
				if($authnode_str)
				{
					$authnode_str = intval($this->input['_id']) ? $authnode_str .',' . $this->input['_id'] : $authnode_str;
					$sql = 'SELECT id,childs FROM ' . DB_PREFIX . 'survey_node WHERE id IN('.$authnode_str.')';
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
						$condition .= ' AND node_id IN(' . $authnode_str . ')';
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
							$condition .= ' AND node_id IN(' . implode(',', $auth_child_node_array) . ')';
						}
					}
				}
			}
		}
		if($this->input['_id'])
		{
			$sql = "SELECT childs FROM " . DB_PREFIX . "survey_node WHERE id in ( " . trim($this->input['_id']).")";
			$ret =  $this->db->query_first($sql);
			$condition .=" AND  node_id in (" . $ret['childs'] . ")";
		}

		####增加权限控制 用于显示####

		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  title  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if(isset($this->input['state']) && $this->input['state'] && urldecode($this->input['state'])!= -1)
		{
			$condition .= " AND status = '".urldecode($this->input['state'])."'";
		}
		else if(urldecode($this->input['state']) == '0')
		{
			$condition .= " AND status = 0 ";
		}
		
		if($this->input['node_id'])
		{
			$sql = "SELECT childs FROM " . DB_PREFIX . "survey_node WHERE id in ( " . trim($this->input['node_id']).")";
			$ret =  $this->db->query_first($sql);
			$condition .=" AND  node_id in (" . $ret['childs'] . ")";
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
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
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		if($this->input['enddate']) //查询过期的
		{
			$condition .= ' AND  end_time < '.TIMENOW .' AND end_time != 0 ';
		}
		if($this->input['no_enddate']) //查询过期的
		{
			$condition .= ' AND  ( end_time > '.TIMENOW .' OR end_time = 0 )';
		}
		return $condition;
	}
	
	public function detail()
	{
		$this->verify_content_prms(array('_action'=>'show'));
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->detail($this->input['id']);
		if(!$ret)
		{
			$this->errorOutput(NODATA);
		}
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 得到统计结果
	 * Enter description here ...
	 */
	public function show_result()
	{
		$id = $this->input['id']; //问卷id
		if(!$id)
		{
			$this->errorOutput("没有问卷id");
		}
		$ret = $this->mode->get_result($id);
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 得到其他的答案以及对应用户信息
	 * Enter description here ...
	 */
	public function show_other_result()
	{
		$pp = $this->input['page'] ? $this->input['page'] : 0;	
		$count = $this->input['page_num'] ? intval($this->input['page_num']) : 20;
		$problem_id = $this->input['problem_id']; //问题id
		if(!$problem_id)
		{
			$this->errorOutput("没有问题id");
		}
		$sql = "SELECT count(*) as total FROM " .DB_PREFIX. "result r LEFT JOIN " .DB_PREFIX. "record_person p ON r.person_id=p.id WHERE problem_id=" .$problem_id. " AND answer != ''";
		$tt = $this->db->query_first($sql);
	    $total_num = $tt['total'];//总的记录数
		if(intval($total_num%$count) == 0)
		{
			$re['total_page']    = intval($total_num/$count);
		}
		else 
		{
			$re['total_page']    = intval($total_num/$count) + 1;
		}
		$re['total_num'] = $total_num;//总的记录数
		$re['page_num'] = $count;//每页显示的个数
		$pp = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页
		if($pp > $re['total_page'])
		{
			$pp = $re['total_page'];
		}
		$re['current_page']  = $pp;//当前页码
		$offset = intval(($pp - 1)*$count) > 0 ? intval(($pp - 1)*$count) : 0;
		
		$limit = 'LIMIT '.$offset.','.$count;
		$ret = $this->mode->get_other_result($problem_id,$limit);
		$return['info'] = $ret;
		$return['page_info'] = $re;
		$this->addItem($return);
		$this->output();
	}
	
	public function show_tags()
	{
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id in( 0, '.intval($this->user['user_id']).')';
			}
		}
		$sql = "SELECT * FROM " . DB_PREFIX . 'tags WHERE 1 '.$condition.' ORDER BY order_id DESC, id DESC';
		$query = $this->db->query($sql);
		$tag = $this->db->fetch_all($sql);
		if(is_array($tag))
		{
			foreach ($tag as $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	/**
	 * 获取验证码种类
	 */
	public function get_verify_type()
	{
		include_once(ROOT_PATH . 'lib/class/verifycode.class.php');
		$this->verifycode = new verifycode();
		$ret = $this->verifycode->get_verify_type();
		$this->addItem($ret);
		$this->output();	
	}
	
	public function get_survey_info()
	{
		$this->verify_content_prms(array('_action'=>'show'));
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->get_survey_info($id);
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if(!$this->user['prms']['default_setting']['show_other_data'] && $ret['user_id'] != $this->user['user_id'])
            {
            	$this->errorOutput(NO_PRIVILEGE);
            }
        }
		if(!$ret)
		{
			$this->errorOutput(NODATA);
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_survey_problem()
	{
		$this->verify_content_prms(array('_action'=>'show'));
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$sql = "SELECT template_id FROM ". DB_PREFIX . "survey WHERE id =".$id;
		$query = $this->db->query_first($sql);
		$ret = $this->mode->get_child_problem($id);
		if(!$ret)
		{
			$this->addItem(array());
		}else 
		{
			foreach ($ret as $r)
			{
				$this->addItem($r);
			}
		}
		$this->output();
	}
	
	/**
	 * 得到统计结果
	 * Enter description here ...
	 */
	public function get_survey_result()
	{
		$id = $this->input['id']; //问卷id
		if(!$id)
		{
			$this->errorOutput("没有问卷id");
		}
		$ret = $this->mode->get_survey_result($id);
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_one_problem()
	{
		$id = $this->input['id']; //问卷组件id
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->get_one_problem($id);
		$this->addItem($ret);
		$this->output();
	}
	
}

$out = new survey();
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