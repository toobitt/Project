<?php
define('MOD_UNIQUEID','member_sign');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_sign.class.php';
class member_sign extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->sign = new sign();
		$this->Members=new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	public function show()
	{
		$this->verify_setting_prms();
		$condition=$this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$info 	= $this->sign->show($condition,$offset,$count);

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}

		$this->output();
	}

	public function detail()
	{
		$id = intval($this->input['id']);
		if(empty($id))
		{
			return false;
		}
		$info = $this->sign->detail($id);
		$this->addItem($info);
		$this->output();
	}
	/**
	 * 获取签到统计 ...
	 */
	public function get_sign_count()
	{
		$info=$this->sign->get_sign_count();
		$this->addItem($info);
		$this->output();
	}
	public function count()
	{
		$condition = '';
		$sql = "SELECT COUNT(member_id) AS total FROM " . DB_PREFIX . "sign WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$get_member_info = $this->Members->get_member_info('AND m.member_name LIKE \'%'.$this->input['k'].'%\'','member_id','','member_id',true);
			if($get_member_info)
			{
				$condition .= ' AND member_id IN(' . trim(implode(',', array_keys($get_member_info))) . ')';
			}
			else 
			{
				$condition .= ' AND member_id = 0';
			}
		}
	if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND time <= ".$end_time;
		}
		if($this->input['time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  time > ".$yesterday." AND time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  time > ".$today." AND time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  time > ".$last_threeday." AND time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND time > ".$last_sevenday." AND time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}

}

$out = new member_sign();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>