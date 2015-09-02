<?php
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class videoRangetApi extends adminBase
{ 
	function __construct()
	{
		parent::__construct();  
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function get_vtopx()
	{
		global $rangeNum; 
		$begin_time = $this->input['begin_time'] ? strtotime(urldecode($this->input['begin_time'])) : strtotime(date('Y-m-d' , time() . ' 00:00:00'));
		$end_time = $this->input['end_time'] ? strtotime(urldecode($this->input['end_time']) . '23:59:59') : strtotime(date('Y-m-d' , time() . ' 23:59:59'));
		$range_type = intval($this->input['range_type']);
		
		$limit = $rangeNum[$range_type];
		$sql = 'SELECT sum(  CASE WHEN v.state =1 AND v. is_show =2 THEN 1  ELSE 0  END ) AS total_num, v.user_id,m.username as user_name
				FROM ' . DB_PREFIX . 'video v left join ' . DB_PREFIX . 'user m on v.user_id = m.id
				WHERE 1 
				And create_time between ' . $begin_time . ' and ' . $end_time . '
				GROUP BY user_id
				ORDER BY total_num DESC 
				LIMIT 0 , ' . $limit;
		
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
			$this->setXmlNode('UserVideosCounts','UserVideoCount');
			$this->addItem($users_top);
			$this->output();
		}
	}
}
$videoRangetApi = new videoRangetApi();
$videoRangetApi->get_vtopx();