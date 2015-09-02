<?php
define('MOD_UNIQUEID','busmanage');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/bus_manage.class.php');
class bus_manage extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->busmanage = new busmanage();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}


	public function show()
	{
		if(!BUS_ON_OFF)
		{
			$this->errorOutput('客运管理已关闭,请开启此功能');
		}
		$condition=$this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$orderby = '  ORDER BY departDate,departTime ASC  ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->busmanage->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}

	public function detail()
	{
		if(!BUS_ON_OFF)
		{
			$this->errorOutput('客运管理已关闭,请开启此功能');
		}
		
		if($this->input['id'])
		{
			$condition=$this->get_condition();
			$ret = $this->busmanage->detail($condition);
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
	public function fromexcel()//excel导入使用
	{
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->busmanage->count($condition);
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
			$condition .= ' AND  departStation  LIKE "%'.trim(urldecode($this->input['station'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}

		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  arriveStation  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		$condition .= ' AND type = 0';

		return $condition;
	}


}

$out = new bus_manage();
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
