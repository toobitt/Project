<?php
define('SCRIPT_NAME', 'appstore');
require_once('./global.php');
class appstore extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index()
	{
		
	}
	
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
		$sql = 'SELECT * FROM '.DB_PREFIX.'apps WHERE 1 '.$condition.' ORDER BY id DESC '.$limit;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			$this->addItem($r);
		}
		$this->output();
	}
	
	public function count()
	{
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'apps WHERE 1 '.$this->get_condition();
		$total = $this->db->query_first($sql);
		echo json_encode($total);	
	}
	
	public function detail()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'apps WHERE id = '.intval($this->input['id']);
		$row = $this->db->query_first($sql);
		$this->addItem($row);
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= ' AND id = '.intval($this->input['id']);
		}
		
		if($this->input['k'] || trim(urldecode($this->input['k']))== '0')
		{
			$condition .= ' AND  name  LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}

		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}

		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}

		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > ".$yesterday." AND create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > ".$today." AND create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > ".$last_threeday." AND create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}
	
	//开放app
	public function open_app()
	{
		$sql = "SELECT * FROM " .DB_PREFIX. "apps";
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ret[$r['id']] = $r;
		}
		$this->addItem($ret);
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');
