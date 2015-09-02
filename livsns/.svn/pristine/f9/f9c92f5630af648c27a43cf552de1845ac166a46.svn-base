<?php
define('MOD_UNIQUEID','project_list');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/project_list_mode.php');
class project_list extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new project_list_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		/*********权限验证开始*********/
		$this->verify_content_prms(array('_action'=>'project'));
		/*********权限验证结束*********/
		if(!$this->input['cinema_id'])
		{
			$this->errorOutput(NOCINEMAID);
		}
		if(!$this->input['_id']) //给个默认的影片
		{
			$sql = "SELECT movie_id FROM " .DB_PREFIX. "project WHERE cinema_id = " .$this->input['cinema_id']. " GROUP BY movie_name";
			$r = $this->db->query_first($sql);
			$this->input['_id'] = $r['movie_id'];
		}
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = ' ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		$re = array(
			'dates' => $ret,
			'movie_id' => $this->input['_id'],
			'status' => $ret[0]['status'],
		);
		/*分页
		 * $r = array('list'=>$re,'page'=>array(
				'current_page' => '1',
				'page_num' => '20',
				'total' => "89",
				'total_num' => "89",
				'total_page' => '5',));
		 * */
		/*
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
		*/
		$this->addItem($re);
		$this->output();
	}
	
	public function node_show()
	{
		$cinema_id = $this->input['cinema_id'];
		$sql = "SELECT movie_id,movie_name FROM " .DB_PREFIX. "project WHERE cinema_id = " .$cinema_id. " GROUP BY movie_name";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$row = array(
				'id' => $row['movie_id'],
				'name' => $row['movie_name'],
				'fid' => '0',
				'childs' => '',
				'parents' => '1',
				'depath' => '1',
				'is_last' => '1',
			);
			$this->addItem($row);
		}
		$this->output();
	}
	
	public function count()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		if(!$this->input['_id']) //给个默认的影片
		{
			$sql = "SELECT movie_id FROM " .DB_PREFIX. "project WHERE cinema_id = " .$this->input['cinema_id']. " GROUP BY movie_name";
			$r = $this->db->query_first($sql);
			$this->input['_id'] = $r['movie_id'];
		}
		$condition = $this->get_condition();
		//$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->count($condition);
		echo json_encode($ret);
	}
	
	public function get_condition()
	{
		$condition = '';
		/***** 权限 *****/
		$this->verify_content_prms(array('_action'=>'project'));
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND org_id IN (' . $this->user['slave_org'] .')';
			}
		}
		/***** 权限 *****/
		if($this->input['_id'])
		{
			$condition .= " AND movie_id IN (".($this->input['_id']).")";
		}
		if($this->input['cinema_id'])
		{
			$condition .= " AND cinema_id IN (".($this->input['cinema_id']).")";
		}
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  name  LIKE "%'.trim(($this->input['k'])).'%"';
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
		
		//权重
		if($this->input['start_weight'] && $this->input['start_weight'] != -1)
		{
			$condition .= " AND weight >=" . $this->input['start_weight'];
		}
		
		if($this->input['end_weight'] && $this->input['end_weight'] != -2)
		{
			$condition .= " AND weight <= " . $this->input['end_weight'];
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

		return $condition;
	}
	
	public function detail()
	{
		$cinema_id = $this->input['cinema_id'];
		$movie_id = $this->input['movie_id'];
		$create_time = $this->input['create_time'];
		if(!$cinema_id)
		{
			$this->errorOutput(NOCINEMAID);
		}
		if(!$movie_id)
		{
			$this->errorOutput(NOMOVIEID);
		}
		if(!$create_time)
		{
			$this->errorOutput(NODATES);
		}
		$ret = $this->mode->detail($cinema_id, $movie_id, $create_time);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
	}
}

$out = new project_list();
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