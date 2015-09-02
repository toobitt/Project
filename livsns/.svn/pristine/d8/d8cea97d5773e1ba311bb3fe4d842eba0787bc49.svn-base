<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','gather_plan_forward');//模块标识
require_once CUR_CONF_PATH.'lib/gather_plan.class.php';
require_once CUR_CONF_PATH.'core/forward.core.php';
class gatherPlanForwardApi extends cronBase
{
	public function __construct()
	{
		parent::__construct();
		$this->plan = new gather_plan();
		$this->forward = new gatherForward();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '转发数据',	 
			'brief' => '转发数据',
			'space' => '20',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		//检测数否有数据
		$plan = $this->plan->check_plan();
		if (empty($plan))
		{
			echo '无转发数据';
			exit();
		}

		$offset = $this->input['offset'] ? intval($this->input['offset']): 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 10;
		$orderby = ' ORDER BY create_time ASC ';
		$condition = '';
		$gather = $this->plan->show($condition,$orderby,$offset,$count);
		if (empty($gather))
		{
			echo '无转发数据';
			exit();
		}
		$temp = array();
		$ret = array();
		$plan_ids = array();
		foreach ($gather as $key=>$val)
		{
			$plan_ids[] = $val['id'];
			$temp[$val['set_id']][] = $val['cid']; 
		}
		foreach ($temp as $key=>$val)
		{
			$ids = implode(',', $val);
			$tmp = $this->forward->forward($ids, $key);
			if ($tmp && is_array($tmp))
			{
				$ret[] = $tmp;
			}
		}
		//数据整理
		$arr = array();
		if (!empty($ret))
		{
			foreach ($ret as $val)
			{
				foreach ($val as $kk=>$vv)
				{
					foreach ($vv as $kkk=>$vvv)
					{
						$arr[$kk][$kkk] = $vvv;
					}
				}
				
			}
		}
		//更新数据库发布记录
		if (!empty($arr))
		{
			foreach ($arr as $key=>$val)
			{
				$this->plan->update_set_url($val, $key);
			}			
		}
		//清空已转发的队列
		if (!empty($plan_ids))
		{
			$plan_ids = implode(',', $plan_ids);
			$this->plan->delete_plan($plan_ids);
		}
		echo "转发成功";exit();		
	}
	
}

$out = new gatherPlanForwardApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>