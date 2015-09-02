<?php
require('global.php');
define('MOD_UNIQUEID','access');
class accessApi extends adminReadBase
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
	public function detail(){}
	
	public function show()
	{
		if($this->mNeedCheckIn && !$this->prms['show'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "app WHERE father = 0"; 
		$app = $this->db->fetch_all($sql);
		$sql = "SELECT * FROM " . DB_PREFIX . "app WHERE  father != 0";
		$module = $this->db->fetch_all($sql);
		$condition = $this->get_condition();
		$sql = "SELECT create_time FROM " . DB_PREFIX ."nums where 1 " . $condition;
		$info = $this->db->query_first($sql);
		$time = date('Ym',$info['create_time']);
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? $this->input['count'] : 20;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql = "SELECT * FROM " . DB_PREFIX . "record_" .$time . " where 1 " . $condition . $data_limit;
		$info = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($info))
		{
			foreach($app as $k => $v)
			{
				if($v['bundle'] == $row['app_bundle'])
				{
					$row['app_bundle'] = $v['name'];
				}
			}
			foreach($module as $k => $v)
			{
				if($v['bundle'] == $row['module_bundle'])
				{
					$row['module_bundle'] = $v['name'];
				}
			}
			switch ($row['type'])
			{
				case 1:
					$row['access_type'] = '评论';
					break;
				case 2:
					$row['access_type'] = '分享';
					break;
				case 3:
					$row['access_type'] = '下载';
					break;
				default:
					$row['access_type'] = "点击";
			}
			$row['access_time'] = date('Y-m-d H:i:s', $row['access_time']);
			$ret[] = $row;
		}
		foreach ($ret as $k => $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT create_time FROM " . DB_PREFIX ."nums where 1 " . $condition;
		$info = $this->db->query_first($sql);
		$time = date('Ym',$info['create_time']);
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "record_" . $time ." where 1 " . $condition;
		$ret = $this->db->query_first($sql);
		echo json_encode($ret);
	}
	
	private function get_condition()
	{
		$condition = '';
		if($this->input['app_bundle'])
		{
			$condition .= " AND app_bundle = '" . urldecode($this->input['app_bundle']) . "'";
		}
		if($this->input['mod_bundle'])
		{
			$condition .= " AND module_bundle = '" .urldecode($this->input['mod_bundle']). "'";
		}
		if($this->input['cid'])
		{
			$condition .= " AND cid = " . intval($this->input['cid']);
		}
		return $condition;
	}
}

$out = new accessApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>