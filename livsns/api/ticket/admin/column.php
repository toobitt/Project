<?php
define('MOD_UNIQUEID','ticket_column');//模块标识
define('SCRIPT_NAME', 'TicketColumn');
require('global.php');
class TicketColumn extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function  show()
	{	
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		
		$order_by = ' ORDER BY order_id DESC ';
		
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'column WHERE 1 ' . $condition . $order_by . $limit;
		
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			if($r['create_time'])
			{
				$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			}
			else 
			{
				$r['create_time'] = '';
			}
			$this->addItem($r);
		}
		$this->output();
	}

	public function detail()
	{	
		$id = $this->input['id'];
		$sql = 'SELECT * FROM '.DB_PREFIX.'column WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function count()
	{	
		$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'column WHERE 1 '.$this->get_condition();
		$templates_total = $this->db->query_first($sql);
		echo json_encode($templates_total);	
	}
	
	public function get_condition()
	{		
		$condition = '';
		$condition .= ' AND column_id !="" ';
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  title  LIKE "%'.trim(($this->input['k'])).'%"';
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
	
	
	public function index()
	{	
	}
}
include(ROOT_PATH . 'excute.php');
?>
