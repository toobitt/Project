<?php
define('MOD_UNIQUEID','market_member_queue');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/market_member_mode.php');
require_once(CUR_CONF_PATH . 'lib/PHPExcel.class.php');
require_once(CUR_CONF_PATH . 'lib/IdCard.class.php');
ini_set('max_execution_time', 3600);
ini_set('memory_limit', '1024M');
class market_member_queue extends cronBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new market_member_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function run()
	{
		$taskPath = DATA_DIR . 'excel/task.k';
		if(!file_exists($taskPath))
		{
			$this->errorOutput('任务文件不存在');
		}
		
		$task = file_get_contents($taskPath);
		$task = unserialize($task);
		if(!$task)
		{
			$this->errorOutput('没有任务要执行');
		}
		
		//执行任务
		foreach ($task AS $_k => $_v)
		{
			$market_id = $_v['market_id'];
			if(!file_exists($_v['filename']))
			{
				continue;//文件不存在就直接执行下一个
			}
			
			$userdata = file_get_contents($_v['filename']);
			$arr = json_decode($userdata,1);
			foreach($arr AS $k => $v)
			{
				//验证卡号
				if(!$v[0])
				{
					continue;
				}
				else if($this->mode->isExistsMember(" card_number = '" .$v[0]. "' AND market_id = '" .$market_id. "' "))
				{
					continue;
				}
				
				//名称
				if(!$v[1])
				{
					continue;
				}
				
				//验证生日
				if(!$v[2])
				{
					continue;
				}
				
				//验证手机号
				if(!$v[3])
				{
					continue;
				}
				
				//根据身份证号得到出生日期以及年龄并且保存起来
				$idCardInfo = new IdCard();
				$birthday = date('Y-m-d',strtotime($v[2]));
				$age = $idCardInfo->getAge($birthday);
				$month = intval(date('m',strtotime($birthday)));
				$day   = intval(date('d',strtotime($birthday)));
				$constellation_id = $idCardInfo->getConstellation($birthday);
	
				$data = array(
						'card_number' 		=> $v[0],
						'name' 				=> $v[1],
						'phone_number' 		=> $v[3],
						'age' 				=> $age,
						'month' 			=> $month,
						'day' 				=> $day,
						'birthday'			=> $birthday,
						'constellation_id' 	=> $constellation_id,
						'market_id' 		=> $market_id,
						'user_id' 			=> $this->user['user_id'],
						'user_name' 		=> $this->user['user_name'],
						'update_user_id' 	=> $this->user['user_id'],
						'update_user_name' 	=> $this->user['user_name'],
						'create_time' 		=> TIMENOW,
						'update_time' 		=> TIMENOW,
						'ip' 				=> hg_getip(),
				);
				$this->mode->create($data);
			}
			
			//每导完一条任务就删掉这条数据
			unlink($_v['filename']);
		}
		
		//全部导完之后,删除锁文件
		unlink($taskPath);
		$this->addItem('success');
		$this->output();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '执行会员数据导入的队列(不用的时候请关掉)',	 
			'brief' => '执行会员数据导入的队列(不用的时候请关掉)',
			'space' => '3600',//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	protected function verifyToken(){}
}

$out = new market_member_queue();
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