<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','staff');//模块标识
require_once CUR_CONF_PATH.'lib/staff.class.php';
class staffApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->staff = new staff();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function index()
	{
	
	}
	public function show()
	{
		if($this->mNeedCheckIn && !$this->prms['show'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$orderby = ' ORDER BY order_id  DESC';
		$condition = $this->get_condition();
		$data = $this->staff->show($condition,$orderby,$offset,$count);
		if (!empty($data))
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	public function count()
	{
		$ret = $this->staff->count($this->get_condition());
		echo json_encode($ret);
	}
	public function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND s.name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		//分类列表
		if ($this->input['_id'])
		{
			$condition .= ' AND s.department_id = '.$this->input['_id'] ; 
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND s.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND s.create_time <= ".$end_time;
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
					$condition .= " AND  s.create_time > ".$yesterday." AND s.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  s.create_time > ".$today." AND s.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  s.create_time > ".$last_threeday." AND s. create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  s.create_time > ".$last_sevenday." AND s.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->staff->detail($id);
		$this->addItem($data);
		$this->output();
	}

	
	
	//内容详细页面
	public function show_opration()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->staff->detail($id);
		$this->addItem($data);
		$this->output();
	}
	
	//获取所有的部门信息
	public function departments()
	{
		$data = $this->staff->departments();
		$this->addItem($data);
		$this->output();
	}

}

$ouput= new staffApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}else{
	$action = $_INPUT['a'];
}
$ouput->$action();