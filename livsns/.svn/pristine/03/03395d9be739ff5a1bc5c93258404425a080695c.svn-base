<?php
define('MOD_UNIQUEID','bus_types');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/bus_types.class.php');
class trip_types extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->bustypes = new bustypes();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		if(!BUS_ON_OFF)
		{
			$this->errorOutput('客运管理已关闭,请开启此功能');
		}
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$orderby = '  ORDER BY departDate ASC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->bustypes->show($orderby,$limit,$condition);

		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}
	public function detail(){}
	/*
	public function showdetail()
	{ 	
		$condition=$this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$orderby = '  ORDER BY id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->bustypes->showdetail($condition,$orderby,$limit);
		if(!empty($ret))
		{
			$this->addItem($ret);
			$this->output();
		}
	}

	public function detail()
	{
		if($this->input['id'])
		{
			$condition=$this->get_condition();
			$ret = $this->bustypes->detail($condition);
			if(!empty($ret))
			{
				foreach($ret as $k => $v)
				{
					$this->addItem($v);
				}
				$this->output();
			}
		}
	}
	*/
	public function fromexcel()//excel导入使用
	{
		if(!BUS_ON_OFF)
		{
			$this->errorOutput('客运管理已关闭,请开启此功能');
		}
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->bustypes->count($condition);
		echo json_encode($info);
	}

	public function get_condition()
	{
		$condition = '';
		if($this->input['date'])
		{
			$condition .= " AND departDate IN (".(strtotime($this->input['date'])).")";
		}
		if($this->input['station'])
		{
			$condition .= ' AND  departStation  LIKE "%'.trim(($this->input['station'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}

		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  departStation  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		$condition .= ' AND type  = 0';

		return $condition;
	}
	public function index(){}

	
}

$out = new trip_types();
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