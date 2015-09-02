<?php
define('SCRIPT_NAME', 'PushNotice');
define('MOD_UNIQUEID','push_notice');
require_once('./global.php');
class PushNotice extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
	}
	function show()
	{
		$order = ' ORDER BY id DESC';
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";

		$sql = 'SELECT * FROM '.DB_PREFIX.'push_notice  WHERE 1';
		$condition = $this->get_condition();		
		$sql = $sql . $condition . $order . $limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			switch (intval($row['receiver_type'])) 
			{
				case 1:
				    $row['receiver_type'] = 'IMEI';
					break;
				case 2:
					$row['receiver_type'] = '设备别名';
					break;
				case 3:
					$row['receiver_type'] = '设备标签';
					break;
				case 4:
					$row['receiver_type'] = '广播';
					break;
				default:
					break;
			}
			if($row['create_time'])
			{
				$row['create_time'] = date('Y-m-d H:i',$row['create_time']);
			}
			$this->addItem($row);
		}
		$this->output();
	}
	private function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND content LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		
		//创建者
		if($this->input['user_name'] || trim(($this->input['user_name']))== '0')
		{
			$condition .= " AND user_name = '".trim($this->input['user_name'])."'";
		}
		
		$id = intval($this->input['id']);
		if($id)
		{
			$condition .= ' AND id = '.$id;
		}
		if(isset($this->input['notice_state']) && $this->input['notice_state'] != '-1')
		{
			if($this->input['notice_state'] == 1)
			{
				$condition .= ' AND errcode = 0';
			}
			else 
			{
				$condition .= ' AND errcode != 0';
			}
		}
		if($this->input['app'] && $this->input['app'] != -1)
		{
			$condition .= ' AND app_id='.$this->input['app'];
		}
		return $condition;
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'push_notice  WHERE 1'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput('未找到应用id');
		}
		$condition = $this->get_condition();
		$sql = "SELECT * FROM ".DB_PREFIX."push_notice WHERE 1 ".$condition;
		
		$q = $this->db->query_first($sql);
		$this->addItem($q);
		$this->output();
	}
	
	function unknown()
	{
		$this->erroroutput(NO_METHOD);
	}
}
include(ROOT_PATH . 'excute.php');