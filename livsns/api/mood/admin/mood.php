<?php
define('MOD_UNIQUEID','mood');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/mood_mode.php');
class mood extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'manage'	=>'管理',
		'audit'		=>'状态',
		'_node'=>array(
			'name'=>'心情栏目',
			'filename'=>'column_node.php',
			'node_uniqueid'=>'mood_node',
			),
		);

		parent::__construct();
		$this->mode = new mood_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		#######权限#######
		$this->verify_content_prms();//权限判断
		#######权限#######
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,id DESC ';
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
	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function result()
	{
		#######权限#######
		$this->verify_content_prms(array('_action'=>'show'));
		#######权限#######
		$id = intval($this->input['id']);
	    if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$con = $this->mode->detail($id);   //取出该内容的详细数据
		if(!$con)
		{
			$this->errorOutput('没有该内容');
		}
		$condition = " AND list_id = " . $id;
		$mood_result = $this->mode->get_mood_result($condition, $con['mood_style']);
		if($mood_result)
		{
			$con['total_count'] = $mood_result['total_count'];
			$con['result'] = $mood_result['result'];
		}
		$this->addItem($con);
		$this->output();
		
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  title  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if($this->input['mood_style'])
		{
			$condition .= " AND  mood_style IN (".($this->input['mood_style']).")";
		}

		//按站点栏目查询
		if($this->input['_id'])
		{
			$condition .= ' AND column_id IN ('.$this->input['_id'].')'; 
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

		return $condition;
	}
}

$out = new mood();
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