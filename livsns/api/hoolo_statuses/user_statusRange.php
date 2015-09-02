 <?php
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php'); 
class statusRangeApi_All extends BaseFrm
{
	var $pp; 
	function __construct()
	{
		parent::__construct();
		 
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function get_topx()
	{
    	 
//		$this->input['begin_time']='2011-01-01';
//		$this->input['end_time']='2011-05-01';
//		$this->input['range_type']='1';
		$begin_time = $this->input['begin_time'] ? strtotime(urldecode($this->input['begin_time'])) : strtotime(date('Y-m-d' , time() . ' 00:00:00'));
		$end_time = $this->input['end_time'] ? strtotime(urldecode($this->input['end_time']) . ' 23:59:59') : strtotime(date('Y-m-d' , time() . ' 23:59:59'));
		$range_type = intval($this->input['range_type']);
		
		$limit = $this->pp;
		$sql = 'select sum(1) as total_num ,s.member_id as user_id from liv_status s where s.create_at between ' . $begin_time . ' and ' . $end_time . ' group by s.member_id order by total_num desc limit 0 , ' . $limit;
		
		$users_top = array();
		$qid = $this->db->query($sql);
		$i = 1;
		while(false != ($r = $this->db->fetch_array($qid)))
		{
			$users_top[$i] = $r;
			$i++;
		}
		
		if($users_top)
		{
			$this->setXmlNode('UserStatusesCounts','UserStatusCount');
			$this->addItem($users_top);
			$this->output();
		}
	}
	
	//处理返回结果条数
	private function init_page()
	{
		$range_type = intval($this->input['range_type']);
		$this->pp = ($range_type > 0) ? (($range_type == 1) ? 10 : (($range_type == 2) ? 50 : 100) ) : 10;
	}
}

$statusRangeApi_All = new statusRangeApi_All();
$statusRangeApi_All->get_topx();