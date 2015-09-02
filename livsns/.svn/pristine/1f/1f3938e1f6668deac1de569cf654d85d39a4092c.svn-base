<?php
define('SCRIPT_NAME', 'version');
require_once('./global.php');
class version extends adminReadBase
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
		$sql = 'SELECT v.*,a.name AS app_name FROM '.DB_PREFIX.'version v LEFT JOIN ' .DB_PREFIX. 'apps a ON a.id = v.app_id  WHERE 1 '.$condition .' ORDER BY v.create_time DESC ' . $limit;
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
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'version v WHERE 1 '.$this->get_condition();
		$total = $this->db->query_first($sql);
		echo json_encode($total);	
	}
	
	public function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = "SELECT * FROM " .DB_PREFIX. "version WHERE id = '" .intval($this->input['id']). "'";
		$ret = $this->db->query_first($sql);
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= ' AND v.id = '.intval($this->input['id']);
		}
		
		if($this->input['app_id'] && intval($this->input['app_id'])!= -1)
		{
			$condition .= ' AND v.app_id = '.intval($this->input['app_id']);
		}
		
		if($this->input['k'] || trim(urldecode($this->input['k']))== '0')
		{
			$condition .= ' AND  v.version_name  LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}

		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND v.create_time >= '".$start_time."'";
		}

		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND v.create_time <= '".$end_time."'";
		}

		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  v.create_time > ".$yesterday." AND v.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  v.create_time > ".$today." AND v.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  v.create_time > ".$last_threeday." AND v.create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  v.create_time > ".$last_sevenday." AND v.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}
	
	//对比两个版本的sql
	public function diffsql()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ids = explode(',',$this->input['id']);
		$num = count($ids);
		if($num != 2)
		{
			$this->errorOutput('只能在2个版本之间进行对比');
		}
		
		$sql = "SELECT * FROM " .DB_PREFIX. "version WHERE id IN (" .$this->input['id']. ") ORDER BY create_time ASC ";
		$q = $this->db->query($sql);
		$version = array();
		while($r = $this->db->fetch_array($q))
		{
			$version[] = $r;
		}
		
		if($version[0]['app_id'] != $version[1]['app_id'])
		{
			$this->errorOutput('版本比对只能在相同应用之间进行');
		}
		//再查询这两个版本之间的差异
		$sql = "SELECT * FROM " .DB_PREFIX. "version_diff WHERE app_id = {$version[0]['app_id']} AND version_id = '" .$version[0]['id']. "_" .$version[1]['id'] . "'";
		$diff = $this->db->query_first($sql);
		if($diff['id'])
		{
			$version[] = $diff;
		}
		$this->addItem($version);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');