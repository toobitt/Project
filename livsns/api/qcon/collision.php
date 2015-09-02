<?php
/*
 * 嘉宾头像碰撞接口
 * 当两位嘉宾交换名片之后前端脚本不断请求该接口查看有没有新的交换的嘉宾，有的话就将二者头像碰撞
 **/
define('MOD_UNIQUEID','collision');
define('SCRIPT_NAME', 'collision');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
class collision extends outerReadBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new member_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function count(){}
	public function detail(){}
	
	public function show()
	{
		//查询交换名片的基准时间
		$create_time = $this->input['create_time'];
		$cond = array('create_time' => $create_time);
		
		//建立长连接
		set_time_limit(0);
		header("Connection: Keep-Alive");
		header("Proxy-Connection: Keep-Alive");
		for ($i = 0, $timeout = 20; $i < $timeout; $i++)
		{
			$member = $this->mode->get_need_collision($cond);
			if ($member)
			{
				echo json_encode($member);
				ob_flush();
				flush();
				exit(0);
			}
			sleep(1);
		}
		$this->addItem(array('return' => 'refresh'));
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');