<?php
require_once ('./global.php');
define('MOD_UNIQUEID','weibo');
class getWeibo extends outerReadBase
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
		$sql = "SELECT w.*, u.avatar,u.uid AS uuid FROM " .DB_PREFIX ."weibo_circle wc
				LEFT JOIN " . DB_PREFIX ."weibo w
					ON wc.weibo_id = w.id
				LEFT JOIN " . DB_PREFIX ."user u
					ON wc.uid = u.id 
				WHERE 1  AND w.status = 1 " . $condition . $data_limit;			
		$info = $this->db->query($sql);	
		$ret = array();
		while ( $row = $this->db->fetch_array($info) ) 
		{			
			$row['create_time']  = date('Y-m-d H:i',$row['create_time']);
			unset($row['weibo_info']);
			$row['img'] = unserialize($row['img']);
			$row['video'] = unserialize($row['video']);
			$row['music'] = unserialize($row['music']);
			$row['source_info'] = unserialize($row['source_info']);
			$row['avatar'] = unserialize($row['avatar']);
			$row['picsize'] = unserialize($row['picsize']);
			if(!empty($row['source_info']))
			{
				$row['source_info']['create_time'] = date('Y-m-d H:i',$row['source_info']['create_time']);
			}
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
		if(!empty($this->input['circleid']))
		{
			$condition .= " AND wc.circle_id = ".intval($this->input['circleid']);
		}
		//根据时间
		$condition .=" ORDER BY wc.create_time  ";		
		//查询排序方式(升序或降序,默认为降序)
		$condition .= $this->input['descasc'] ? $this->input['descasc'] : ' DESC ';
		return $condition ;
	}
}
$out = new getWeibo();
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