<?php
require ('global.php');
define('MOD_UNIQUEID','announcement');
define('SCRIPT_NAME', 'get_announcement');
require_once(CUR_CONF_PATH . 'lib/announcement_mode.php');
//外部调用接口获取公告
class get_announcement extends outerReadBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new announcement_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail(){}
	public function count(){}	
	
	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY a.order_id DESC,a.id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
		}
		else 
		{
			$this->addItem(array());
		}
		
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND a.id IN (".($this->input['id']).")";
		}
		
		if($this->input['carpark_id'])
		{
			$condition .= " AND a.carpark_id IN (".($this->input['carpark_id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  a.title  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		//根据类型筛选
		if($this->input['type_id'])
		{
			$condition .= ' AND a.type_id = ' . $this->input['type_id'];
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND a.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND a.create_time <= '".$end_time."'";
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
					$condition .= " AND  a.create_time > '".$yesterday."' AND a.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  a.create_time > '".$today."' AND a.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  a.create_time > '".$last_threeday."' AND a.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  a.create_time > '".$last_sevenday."' AND a.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		//查询已审核的公告
		$condition .= " AND a.status=2";
		return $condition;
	}
}
include(ROOT_PATH . 'excute.php');
?>