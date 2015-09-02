<?php
define('MOD_UNIQUEID','supermarket');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/supermarket_mode.php');
class supermarket extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		$this->mPrmsMethods = array(
			'show'		=> '查看',
			'update'	=> '更新',
			'manger' 	=> '超市子模块的管理',
			'audit'		=> '审核',
			'_node'=>array(
					'name'=>'超市名称',
					'filename'=>'market_name.php',
					'node_uniqueid'=>'market_name',
				),
		);
		parent::__construct();
		$this->mode = new supermarket_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		//权限
		$this->verify_content_prms(array('_action'=>'show'));
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY sm.order_id DESC,sm.id DESC ';
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
		
		/******************************权限*************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//节点权限判断
			if($authnode = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
			{
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if($authnode_str)
				{
					$condition .= ' AND sm.id IN (' . $authnode_str . ')'; 
				}
			}
			else 
			{
				$this->errorOutput('您没有查看该超市的权限');
			}
		}
		/******************************权限*************************/
		
		if($this->input['id'])
		{
			$condition .= " AND sm.id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  sm.market_name  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND sm.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND sm.create_time <= '".$end_time."'";
		}
		
		//商超状态
		if($this->input['status'] > 0)
		{
			$condition .= " AND sm.status = '". $this->input['status'] ."'";
		}
			
		//权重
		if($this->input['start_weight'] && $this->input['start_weight'] != -1)
		{
			$condition .= " AND sm.weight >=" . $this->input['start_weight'];
		}
		
		if($this->input['end_weight'] && $this->input['end_weight'] != -2)
		{
			$condition .= " AND sm.weight <= " . $this->input['end_weight'];
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
					$condition .= " AND  sm.create_time > '".$yesterday."' AND sm.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  sm.create_time > '".$today."' AND sm.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  sm.create_time > '".$last_threeday."' AND sm.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  sm.create_time > '".$last_sevenday."' AND sm.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}
	
	public function detail()
	{
		if($this->input['id'])
		{
			/******************************权限*************************/
			if($this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				$this->verify_content_prms(array('_action'=>'show'));
				$node = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'];
				if(!in_array($this->input['id'],$node))
				{
					$this->errorOutput('没有权限编辑此超市');
				}
			}
			/******************************权限*************************/
			
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	
	/*****************************************扩展操作***********************************************/
	//获取超市信息（主要用于append）
	public function get_market_info()
	{
		if($this->input['market_id'])
		{
			$ret = $this->mode->detail($this->input['market_id']);
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	/*****************************************扩展操作***********************************************/
}

$out = new supermarket();
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