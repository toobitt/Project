<?php
require('global.php');
define(MOD_UNIQUEID,'ranking');
define(SCRIPT_NAME,'Ranking');
class Ranking extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? $this->input['count'] : 20;
		$data_limit = ' LIMIT ' . $offset .' , ' . $count;
		$sql = "SELECT * FROM ".DB_PREFIX."ranking_sort WHERE 1 " . $condition . $data_limit;
		$q = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['start_time']  = date('Y-m-d H:i:s',$row['start_time']);
			$row['create_time'] = date('Y-m-d H:i:s',$row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s',$row['update_time']);
			$row['status'] = $this->settings['status'][$row['status']];
			$ret[] = $row;
		}
		if($ret && is_array($ret))
		{
			foreach($ret as $key => $value)
			{
				$this->addItem($value);
			}
		}
		$this->output();
	} 
	
	function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."ranking_sort WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
        echo json_encode($info);exit;
	}
	
	function detail()
	{
		$id = intval($this->input['id']);
		if($id)
		{
			$data_limit = " AND id = " . $id;
		}
		else
		{
			$data_limit = " LIMIT 1 ";
		}
		$sql = "SELECT * FROM ".DB_PREFIX."ranking_sort  WHERE 1 " . $data_limit;
		$info = $this->db->query_first($sql);
		if($info)
		{
			$info['start_time'] = $info['start_time'] ?  date('Y-m-d H:i:s',$info['start_time']) : '';
			if($info['column_id'])
			{
				include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
				$this->publish_column = new publishconfig();
				$columnName = $this->publish_column->get_columnname_by_ids('id,name',$info['column_id']);
				$info['column_name'] = $columnName[$info['column_id']];		
			}
			if($info['type'])
			{
				$info['type'] = explode(',', $info['type']);
			}
			$this->addItem($info);
			$this->output();
		}
		else
		{
			$this->errorOutput("NOCONTENT");
		}
	}
	
	function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= " AND title LIKE '%" . trim($this->input['k']) . "%' ";
		}
        //查询发布的时间
        if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));
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
		if($this->input['start_time'] && $this->input['end_time'] && $this->input['start_time'] == $this->input['end_time'])
		{
			$start_time = strtotime($this->input['start_time']);
			$end_time = $start_time + 24 * 3600;
			$condition .= " AND create_time >= ".$start_time." AND create_time <= " . $end_time;
		}
		else
		{
			//查询创建的起始时间
			if($this->input['start_time'])
			{
				$condition .= " AND create_time >= " . strtotime($this->input['start_time']);
			}
			
			//查询创建的结束时间
			if($this->input['end_time'])
			{
				$condition .= " AND create_time <= " . strtotime($this->input['end_time']);	
			}				
		}			
		return $condition;
	}
	
	function get_contentType()
	{
		include_once(ROOT_PATH.'lib/class/publishcontent.class.php');
		$this->publishcontent = new publishcontent();	
		$content_type = $this->publishcontent->get_all_content_type();
		$this->addItem($content_type);
		$this->output();
	}
}
require_once(ROOT_PATH . 'excute.php');
?>