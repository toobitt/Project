<?php
require_once './global.php';
include_once(ROOT_PATH . 'lib/class/mark.class.php');
class markApi extends adminBase
{
	
	public function __construct()
	{
		parent::__construct();
		$this->mark = new mark();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->mark);
	}
	
	/**
	 * 获取所有状态的小组信息
	 */
	public function show()
	{	
		$data = array(
			'user_id' => 0,
			'offset' => '',
			'count' => '',
		);
		$data['offset'] = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$data['count'] = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$mark = $this->mark->show_mark_to_kind($data);
		$ret = $mark['data'];
//		print_r($mark);
//		exit();
		if(!empty($ret) && is_array($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);	
			}			
		}
		$this->output();
	}	
	
	/**
	 * 获取所有状态的小组数量
	 */
	public function count()
	{
		$data = array(
			'user_id' => 0,
			'offset' => '',
			'count' => -1,
		);
		$mark = $this->mark->show_mark_to_kind($data);
		$return['total'] = $mark['total'];
		echo json_encode($return);
	}
	
	public function create()
	{
		$data = array(
			'user_id' => 0,
			'kind_name' => $this->input['kind_name'],
			'mark_name' => $this->input['mark_name'],
		);
		$this->mark->create_mark_to_kind($data);
		$this->addItem('true');
		$this->output();	
	}
	
	public function delete()
	{
		$mark_id = urldecode($this->input['id']);
		$data = array(
			'user_id' => 0,
			'id' => $mark_id,
		);
		$this->mark->delete_mark_to_kind($data);
		$this->addItem($mark_id);
		$this->output();
	}
	
	
}

$out = new markApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();