<?php
require('./global.php');
define('MOD_UNIQUEID', 'wbcircle');
class wbCircle extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	public function index(){}
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
		$count = $this->input['count'] ? intval($this->input['count']) : 20 ;	
		$data_limit = ' LIMIT ' . $offset . ',' . $count;	
		$sql  = "SELECT * FROM " . DB_PREFIX . "circle  WHERE 1 " . $condition . $data_limit;
		$info = $this->db->query($sql);	
		$ret = array();
		while ( $row = $this->db->fetch_array($info) )
		{
			$row['state'] = $row['status'];
			switch($row['status'])
			{
				case 0:
					$row['status'] = '未审核';
					break;
				case 1:
					$row['status'] = '已审核';
					break;
				case 2:
					$row['status'] = '已打回';
					break;
			}
			$row['log'] = json_decode($row['log'],1);
			$row['log'] = $row['log'][0];
			$row['create_time'] = date('Y-m-d H:i');
			$row['update_time'] = date('Y-m-d H:i');
			$row['brief'] = $row['description'];
			unset($row['description']);
			//节点
			$row['is_last'] = 1;
			$row['fid'] = $row['id'];
            $row['depath'] = 1;
			$row['input_k'] = "_id";
			$ret[] = $row;
		}	
		foreach($ret as $k => $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
		
	
	public function detail()
	{
		if($this->input['id'])
		{
			$data_limit = ' AND id=' . intval($this->input['id']);
		}
		else
		{
			$data_limit = ' LIMIT 1';
		}	
		
		$sql = "SELECT * FROM " . DB_PREFIX ."circle  WHERE 1 " . $data_limit;
		$ret = $this->db->query_first($sql);		
		if($ret)
		{
			$ret['log_img'] = json_decode($ret['log'],1);
			$ret['log_img'] = $ret['log_img'][0];
			$ret['brief'] = $ret['description'];
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('查询失败');
		}
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "circle WHERE 1 " . $condition;
		$f = $this->db->query_first($sql);
		echo json_encode($f);	
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= " AND name LIKE '%".$this->input['k']."%'";
		}
		//根据排序
		$condition .=" ORDER BY order_id  ";
		//查询排序方式(升序或降序,默认为降序)
		$condition .= $this->input['descasc'] ? $this->input['descasc'] : ' DESC ';
		return $condition ;
	}
	
}

$out = new wbCircle();
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