<?php
require_once './global.php';
define('MOD_UNIQUEID','service_ggfw');//模块标识
require_once CUR_CONF_PATH.'lib/service_ggfw.class.php';
class serviceGGFWApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->service = new ClassServiceGGFW();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 
	 * @Description 公示列表  公共服务
	 * @author Kin
	 * @date 2013-10-26 上午11:03:46 
	 * @see outerReadBase::show()
	 */
	public function show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 10;
		$res = array();
		$condition = $this->get_condition();
		$res = $this->service->show($condition, $offset, $count);
		if (!empty($res))
		{
			foreach ($res as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = array();
		if ($this->input['title'])
		{
			$condition['title'] = trim($this->input['title']);
		}
		if ($this->input['tel'])
		{
			$condition['tel'] = trim($this->input['tel']);
		}
		if ($this->input['area'])
		{
			$condition['area'] = trim($this->input['area']);
		}
		if ($this->input['address'])
		{
			$condition['address'] = trim($this->input['address']);
		}
		if ($this->input['start_time'])
		{
			$condition['start_time'] = trim($this->input['start_time']);
		}
		if ($this->input['end_time'])
		{
			$condition['end_time'] = trim($this->input['end_time']);
		}
		return $condition;
	}
	
	public function count()
	{
		$total = $this->service->count($this->get_condition());
		$ret['total'] = $total;
		echo json_encode($ret);
	}
	
	public function detail()
	{
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->service->detail($id);
		//hg_pre($data);exit();
		$this->addItem($data);
		$this->output();
	}
}
$ouput = new serviceGGFWApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>