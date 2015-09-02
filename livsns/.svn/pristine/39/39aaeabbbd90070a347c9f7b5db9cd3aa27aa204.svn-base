<?php
require('global.php');
define('MOD_UNIQUEID','auto_copy_daydata');//模块标识
require_once(CUR_CONF_PATH . 'lib/bus_types.class.php');
class auto_copy_day extends cronBase
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

	public function run($max_date = 0,$is_black = true)
	{
		$max_time= strtotime(date('Y-m-d',strtotime('+'.abs(intval(MAX_SEARCH_TIME_RANGE)).' DAY')));//最大复制时间
		$run_time=strtotime(date('Y-m-d'));
		if(empty($max_date))
		{
			$sql="SELECT MAX(departDate) as max_date FROM ".DB_PREFIX."bus_query WHERE type = 0";
			$max_date_arr=$this->db->query_first($sql);
			$max_date = $max_date_arr['max_date'];
			if(empty($max_date))
			{
				$this->errorOutput(NO_DATA);
			}
		}
				
		if($max_date<$max_time)//复制数据
		{
			$max_date +=24*3600;
			$condition=$this->get_condition($max_date);
			$this->bustypes->copy($condition);			
			$this->run($max_date,false);
		}
		$condition = '';
		$link='AND';		
		if ($max_date>$max_time&&$is_black)//回退数据
		{
			$condition .= " AND departDate > ".$max_time;
			$link='OR';
		}
		$condition .= " {$link} departDate < ".$run_time. " AND type = 0";
		if($condition)
		{
			$this->bustypes->delete($condition);
		}
		$this->addItem('success');
		$this->output();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' 	=> MOD_UNIQUEID,	 
			'name' 			=> '自动复制客运及历史数据删除',	 
			'brief' 		=> '自动复制客运以及历史数据删除',
			'space' 		=> '86400',//运行时间间隔，单位秒
			'is_use' 		=> 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	public function get_condition($run_time)
	{
		$condition='';
		$condition .= " AND type = 0 AND departDate = ".($run_time-24*60*60);
		return $condition;
	}
}
$out = new auto_copy_day();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'run';
}
else
{
	$action = $_INPUT['a'];
}
$out->$action();
?>