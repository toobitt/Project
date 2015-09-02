<?php
require_once ('./global.php');
define('MOD_UNIQUEID','weibo');
class getCircle extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail(){}
	public function count(){}
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ',' . $count;
		$sql  = "SELECT * FROM " . DB_PREFIX ."circle  WHERE 1 AND status = 1 " . $condition . $data_limit;
		$info = $this->db->query($sql);	
		$ret = array();
		while ($row = $this->db->fetch_array($info) ) 
		{
			$row['create_time']  = date('Y-m-d H:i',$row['create_time']);
			$row['update_time']  = date('Y-m-d H:i',$row['update_time']);
			$row['log'] = json_decode($row['log'],1);
			$ret[] = $row;
		}
		foreach($ret as $k => $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		$limit_id = $this->input['limit_id'];
		if ($limit_id)
		{
			$condition .= ' AND id IN (' . $limit_id . ')';
		}
		//根据时间
		$condition .=" ORDER BY order_id DESC, create_time  ";
		//查询排序方式(升序或降序,默认为降序)
		$condition .= $this->input['descasc'] ? $this->input['descasc'] : ' DESC ';
		return $condition ;
	}
}
$out = new getCircle();
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