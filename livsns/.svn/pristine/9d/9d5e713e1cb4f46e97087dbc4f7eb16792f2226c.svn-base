<?php
define('MOD_UNIQUEID','schedules');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/schedules_mode.php');
class schedules extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new schedules_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		if(!$this->input['hospital_id'])
		{
			return false;
		}
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		$offset = intval(($pp - 1)*$count);	
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$condition = $this->get_condition();
		$orderby = '  ORDER BY t1.id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		$data = array();
		
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "yuyue t1 WHERE 1 " . $condition;
		$cond_res = $this->db->query_first($sql);
		 $total_num = $cond_res['total'];//总的记录数
		 
		 $data['count'] = $total_num ? $total_num : 0;
		//总页数
		if(intval($total_num%$count) == 0)
		{
			$data['total_page']    = intval($total_num/$count);
		}
		else 
		{
			$data['total_page']    = intval($total_num/$count) + 1;
		}
		$data['total_num'] 		= $total_num;//总的记录数
		$data['page_num'] 		= $count;//每页显示的个数
		$data['current_page']  	= $pp;//当前页码
		
		
		$data['data'] = $ret;
		if(!empty($data))
		{
			$this->addItem($data);
			$this->output();
		}
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
		
		if($this->input['department_id'])
		{
			$sql = "SELECT department_id FROM " . DB_PREFIX . "departments WHERE fid = " . $this->input['department_id'];
			$q = $this->db->query($sql);
			
			$depart_id = array();
			while ($r = $this->db->fetch_array($q))
			{
				$depart_id[] = $r['department_id'];
			}
			
			if(!empty($depart_id))
			{
				$depart_ids = implode(',', $depart_id);
			}
			else 
			{
				$depart_ids = $this->input['department_id'];
			}
			$condition .= ' AND t1.department_id IN (' . $depart_ids . ')';
		}
		
		if($this->input['hospital_id'])
		{
			$condition .= ' AND t1.hospital_id = ' . $this->input['hospital_id'];
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  t1.name  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND t1.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND t1.create_time <= '".$end_time."'";
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

$out = new schedules();
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