<?php
define('MOD_UNIQUEID','member_medal');//模块标识
require('./global.php');
class member_medal extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->Members=new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	public function show()
	{
		$this->verify_setting_prms();
		$condition = $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$sql = "SELECT * FROM " . DB_PREFIX . "medallog WHERE 1";
		$sql .= " AND type=2" . $condition;
		$sql .= " ORDER BY dateline ASC".$limit;
		$q = $this->db->query($sql);
		$return = array();
		$medal_id=array();
		$member_id=array();
		while ($row = $this->db->fetch_array($q))
		{
			if ($row['dateline'])
			{
				$row['dateline']=date('Y-m-d H:i',$row['dateline']);
			}
			if ($row['expiration'])
			{
				$row['expiration']=date('Y-m-d H:i',$row['expiration']);
			}
			else
			{
				$row['expiration']='永久有效';
			}
			$return[] = $row;
			$medal_id[]=$row['medalid'];
			$member_id[]=$row['member_id'];
		}
		$medal_info = $this->Members->get_medal(@array_unique($medal_id),'id,name');
		$member_name= $this->Members->get_member_name(@array_unique($member_id));
		if (!empty($return)&&is_array($return))
		{
			foreach ($return AS $v)
			{
				$v['member_name']=$member_name[$v['member_id']];
				$v['medal_name']=$medal_info[$v['medalid']]['name'];
				$this->addItem($v);
			}
		}

		$this->output();
	}

	public function detail()
	{
	}

	public function count()
	{
		$condition=$this->get_condition();
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "medallog WHERE 1 AND type=2 ".$condition;
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}
	
	public function get_medal()
	{
		$medal = $this->Members->get_medal('','*',1,false,true,' ORDER BY order_id DESC');
		if(is_array($medal))
		{
			foreach ($medal as $v)
			{
				$this->addItem($v);
			}
		}
		else {
			$this->addItem($medal);
		}
		$this->output();
	}
	
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$member_info=$this->Members->get_member_id(trim($this->input['k']),true,true);
			if(is_array($member_info))
			{
				$member_id=array();
				foreach ($member_info as $v)
				{
					$member_id[]=$v;
				}
				if($member_id&&is_array($member_id))
				{
					$member_id=trim(implode(',', $member_id));
					$condition=' AND member_id IN( '.$member_id.')';
				}
			}
			else {
				$condition=' AND member_id = 0 ';
			}
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND dateline >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND dateline <= ".$end_time;
		}
		if($this->input['dateline'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['dateline']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  dateline > ".$yesterday." AND dateline < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  dateline > ".$today." AND dateline < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  dateline > ".$last_threeday." AND dateline < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND dateline > ".$last_sevenday." AND dateline < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		
		if ($this->input['medalid']&&$this->input['medalid'] != -1)
		{
			$condition .= " AND medalid = " . intval($this->input['medalid']);
		}
		
		return $condition;
	}


}

$out = new member_medal();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>