<?php
require_once './global.php';
define('MOD_UNIQUEID','service_bmzx');//模块标识
require_once CUR_CONF_PATH.'lib/service_bmzx.class.php';
class serviceBMZXApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->service = new ClassServiceBMZX();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 
	 * @Description 便民资讯
	 * @author Kin
	 * @date 2013-10-26 上午09:48:03 
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
		return $condition;
	}
	
	public function count()
	{
		$total = $this->service->count($this->get_condition());
		$ret['total'] = $total;
		echo json_encode($ret);
	}
	/**
	 * 
	 * @Description 便民资讯详情
	 * @author Kin
	 * @date 2013-10-26 上午09:48:30 
	 * @see outerReadBase::detail()
	 */
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
$ouput = new serviceBMZXApi();
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