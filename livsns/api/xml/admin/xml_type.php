<?php
require('global.php');
define('MOD_UNIQUEID','transcode_config');
class xml_type extends adminReadBase
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
	
	public function show()
	{
		$sql = "SELECT * FROM " .DB_PREFIX. "xml_type";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$row['id'] = '_'.$row['id'];
			$row['is_group'] = '1';
			$out[] = $row;
		}
		$sql = "SELECT id,title,is_open,user_name,create_time FROM " .DB_PREFIX. "xml WHERE type_id = 0";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$row['is_group'] = '0';
			$out[] = $row;
		}
		$this->addItem($out);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."xml_type WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	public function get_condition()
	{
		$condition = "";
		if($this->input['id'])
		{
			$condition .= " AND id = '".intval($this->input['id'])."'";
		}
		
		if($this->input['k'] || urldecode($this->input['k'])== '0')
		{
			$condition .= ' AND  name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
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
	
	/*
	 * 获取分类下模板数据
	 */
	public function show_xml()
	{
		$type_id = $this->input['id'];
		$sql = "SELECT * FROM " .DB_PREFIX. "xml WHERE type_id = " .$type_id;
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$this->addItem($row);
		}
		$this->output();
	}
	
	public function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."xml_type  WHERE id = '".intval($this->input['id'])."'"; 
		$return = $this->db->query_first($sql);
		$this->addItem($return);
		$this->output();
	}
	
}

$out = new xml_type();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>