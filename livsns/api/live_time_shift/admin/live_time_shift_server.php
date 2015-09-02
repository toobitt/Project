<?php
require('global.php');
require_once(CUR_CONF_PATH . 'lib/server.class.php');
define('MOD_UNIQUEID','time_shift_server');
class live_time_shift_server extends adminReadBase
{
	private $obj;
	public function __construct()
	{
		parent::__construct();
		$this->obj = new server();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->obj->show($condition,$orderby,$data_limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count($condition);
		echo json_encode($info);
	}
	
	public function detail()
	{
		$id = intval($this->input['id'] ? $this->input['id'] : 0);
		$condition = '';
		if($id)
		{
			$condition = " AND id=" . $id;
		}
		$ret = $this->obj->detail($condition);
		if(!empty($ret))
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	
	private function get_condition()
	{
		$condition = '';
		return $condition;
	}
}

$out = new live_time_shift_server();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>