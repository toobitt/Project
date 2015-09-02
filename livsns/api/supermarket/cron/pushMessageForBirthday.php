<?php
/*
 * 计划任务执行的强制转码
 */
require('global.php');
define('MOD_UNIQUEID','market_message_birthday');//模块标识
require_once(CUR_CONF_PATH . 'lib/market_member_mode.php');
require_once(CUR_CONF_PATH . 'lib/market_message_mode.php');

class pushMessage extends cronBase
{
	private $mode;
	private $message_mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new market_member_mode();
		$this->message_mode = new market_message_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function run()
	{
		//算出当前时间的月份与天
		$cur_month = intval(date('m',TIMENOW));
		$cur_day = intval(date('d',TIMENOW));
		$condition = " AND month = '" . $cur_month . "' AND day = '" . $cur_day . "' ";
		//遍历所有生日是今天的用户
		$members = $this->mode->show($condition);
		if($members)
		{
			foreach($members AS $k => $v)
			{
				//先要查看该用户在其所在的超市里面是否已经有生日的消息了，如果已经有了就不要推消息了
				if($this->message_mode->isHasBirthdayMessage($v['market_id'],$v['id']))
				{
					continue;
				}
				$this->sendMessage($v);
			}
		}
		$this->addItem('success');
		$this->output();
	}
	
	private function sendMessage($member = array())
	{
		$data = array(
			'title' 			=> $member['name'] . $this->settings['birthday_message']['title'],
			'market_id' 		=> $member['market_id'],
			'member_id' 		=> $member['id'],
			'is_birthday' 		=> 1,//标识这是生日消息
			'content' 			=> $this->settings['birthday_message']['content'],
			'scope' 			=> 3,//指定用户
			'status' 			=> 2,
			'expire_time' 		=> strtotime(date('Y-m-d',TIMENOW + 24 * 3600)),//过期时间设置为第二天的凌晨0点
			'user_id' 			=> $this->user['user_id'],
			'user_name' 		=> $this->user['user_name'],	
			'org_id' 			=> $this->user['org_id'],	
			'update_user_id' 	=> $this->user['user_id'],	
			'update_user_name' 	=> $this->user['user_name'],	
			'ip' 				=> hg_getip(),	
			'create_time' 		=> TIMENOW,	
			'update_time' 		=> TIMENOW,
		);
		$this->message_mode->create($data);
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '推送生日消息',	 
			'brief' => '检测今天是不是会员的生日，是的话推送生日消息',
			'space' => '10800',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
}

$out = new pushMessage();
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