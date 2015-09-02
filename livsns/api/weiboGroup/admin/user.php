<?php
require('./global.php');
define('MOD_UNIQUEID', 'wbuser');
class wbUser extends adminReadBase
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
        $condition .= ' ORDER BY id DESC ';
		$data_limit = ' LIMIT ' . $offset . ',' . $count;		
		$sql  = "SELECT * FROM " . DB_PREFIX . "user
				WHERE 1 " . $condition . $data_limit;
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
			$row['create_time'] = date('Y-m-d H:i',$row['create_time']);
			$row['update_time'] = date('Y-m-d H:i',$row['update_time']);
			$row['circle_id'] = unserialize($row['circle_id']);
			if($row['circle_id'])
			{
				$row['circle_name'] = implode(',',$row['circle_id']);
			}
			$row['avatar'] = unserialize($row['avatar']);
			$row['avatar'] = $row['avatar']['host'] . $row['avatar']['dir'] . $row['avatar']['filepath'] . $row['avatar']['filename'];
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
		$sql = "SELECT *,group_name gname,name uname FROM " . DB_PREFIX ."user
				WHERE 1 " . $data_limit;
		$ret = $this->db->query_first($sql);		
		if($ret)
		{
			$ret['circle_id'] = unserialize($ret['circle_id']);
			if($ret['circle_id'])
			{
				$ret['circle_id'] = array_keys($ret['circle_id']);
			}
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
		$sql = "SELECT count(*) AS total FROM " . DB_PREFIX . "user WHERE 1 " . $condition;
		$f = $this->db->query_first($sql);
		echo json_encode($f);	
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['key'])
		{
			$condition .= " AND name LIKE '%".$this->input['key']."%'";
		}
		return $condition ;
	}
	
	
}

$out = new wbUser();
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