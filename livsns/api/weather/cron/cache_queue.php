<?php
define('MOD_UNIQUEID','cacheQueue');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('SCRIPT_NAME', 'cacheQueue');
class cacheQueue extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '缓存城市数据队列',	 
			'brief' => '缓存城市数据队列',
			'space' => '10800',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		//单队列
		$sql = 'SELECT locked FROM '.DB_PREFIX.'cache_queue';
		$queue = $this->db->query_first($sql);
		if($queue['locked'])
		{
			$this->errorOutput('QUEUE_LOCKED');
		}
		$this->push_into_queue();
		$this->addItem('QUEUE_START');
		$this->output();
	}
	public function push_into_queue($offset=0)
	{
		//锁定队列准备开始执行
		$this->db->query('REPLACE INTO '.DB_PREFIX.'cache_queue value(0,1)');
	}
}
include(ROOT_PATH . 'excute.php');