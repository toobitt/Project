<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/reporter.class.php';
define('MOD_UNIQUEID','reporter_lib');//模块标识
class reporterApi extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
		$this->reporter = new reporter();
		$this->mPrmsMethods= array(
								'show'=>array(
										'name' => '查看',
										'node' => false,
										),
								'create'=>array(
										'name'=>'创建',
										'node'=>false,
										),
								'update'=>array(
										'name'=>'更新',
										'node'=>false,
										),
								'delete'=>array(
										'name'=>'删除',
										'node'=>false,
										),
								'sort'=>array(
										'name'=>'排序',
										'node'=>false,
										),
								);
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function index()
	{
	
	}
	
	public function show()
	{
		/**************权限控制开始**************/
		$this->verify_content_prms();
		/**************权限控制结束**************/
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$orderby = ' ORDER BY order_id  DESC';
		$condition = $this->get_condition();
		$data = $this->reporter->show($condition,$orderby,$offset,$count);
		if (!empty($data))
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= ".$end_time;
		}
		if($this->input['show_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['show_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > ".$yesterday." AND create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > ".$today." AND create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > ".$last_threeday." AND create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	public function count()
	{
		$ret = $this->reporter->count($this->get_condition());
		echo json_encode($ret);
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->reporter->detail($id);
		$this->addItem($data);
		$this->output();
	}
	
	public function getAuthRole()
	{
		$ret = array();
		$data = $this->reporter->getAuthRole();
		if (!empty($data) && is_array($data))
		{
			foreach ($data as $key=>$val)
			{
				$ret[$val['id']] = $val['name'];
			}
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function show_opration()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->reporter->detail($id);
		$this->addItem($data);
		$this->output();
	}
}
$ouput= new reporterApi();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();