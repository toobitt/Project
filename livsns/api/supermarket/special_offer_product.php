<?php
define('MOD_UNIQUEID','special_offer_product');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/special_offer_product_mode.php');
class special_offer_product extends outerReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new special_offer_product_mode();
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
		//根据用户所传的排序方式进行排序
		if($this->input['order_by'])
		{
			if($this->input['sc'])
			{
				$orderby = '  ORDER BY so.' . $this->input['order_by'] . ' ASC' ;
			}
			else 
			{
				$orderby = '  ORDER BY so.' . $this->input['order_by'] . ' DESC ';
			}
		}
		else 
		{
			$orderby = '  ORDER BY so.order_id DESC,so.id DESC ';
		}
		
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(empty($ret))
		{
			$this->errorOutput(NO_DATA);
		}
		foreach($ret as $k => $v)
		{
			$this->addItem($v);
		}
		$this->output();
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
		
		//前台输出保证是已审核的数据
		$condition .= " AND so.status = 2 AND sa.end_time > " . (TIMENOW - 24 * 3600) . " AND sa.start_time < " . TIMENOW;
		
		if($this->input['activity_id'])
		{
			$condition .= " AND so.activity_id = '" .$this->input['activity_id']. "' ";
		}
		
		if($this->input['market_id'])
		{
			$condition .= " AND so.market_id = '" .$this->input['market_id']. "' ";
		}
		
		if($this->input['product_sort_id'])
		{
			$condition .= " AND so.product_sort_id = '" .$this->input['product_sort_id']. "' ";
		}
		
		if($this->input['is_recommend'])
		{
			$condition .= " AND so.is_recommend = 1 ";
		}

		if($this->input['id'])
		{
			$condition .= " AND so.id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  so.name  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if($this->input['stime'])
		{
			$start_time = strtotime(trim(($this->input['stime'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if($this->input['etime'])
		{
			$end_time = strtotime(trim(($this->input['etime'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		//权重
		if($this->input['start_weight'] && $this->input['start_weight'] != -1)
		{
			$condition .= " AND so.weight >=" . $this->input['start_weight'];
		}
		
		if($this->input['end_weight'] && $this->input['end_weight'] != -2)
		{
			$condition .= " AND so.weight <= " . $this->input['end_weight'];
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
					$condition .= " AND  so.create_time > '".$yesterday."' AND so.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  so.create_time > '".$today."' AND so.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  so.create_time > '".$last_threeday."' AND so.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  so.create_time > '".$last_sevenday."' AND so.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		//如果是最优惠需要屏蔽掉活动的分类
		if($this->input['order_by'] && $this->input['order_by'] == 'discount')
		{
			if(defined('NEED_DISABLE_SORT') && NEED_DISABLE_SORT)
			{
				$condition .= " AND so.product_sort_id != '" . NEED_DISABLE_SORT . "' ";
			}
		}
		
		//如果是新优惠需要屏蔽掉活动的分类
		if($this->input['order_by'] && $this->input['order_by'] == 'create_time' && !$this->input['is_recommend'])
		{
			if(defined('NEED_DISABLE_SORT') && NEED_DISABLE_SORT)
			{
				$condition .= " AND so.product_sort_id != '" . NEED_DISABLE_SORT . "' ";
			}
		}

		return $condition;
	}
	
	public function detail()
	{
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
}

$out = new special_offer_product();
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