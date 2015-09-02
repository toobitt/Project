<?php
define('MOD_UNIQUEID','ticket');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
require_once CUR_CONF_PATH.'lib/xcpw.class.php';
define('SCRIPT_NAME', 'xcpwApi');
class xcpwApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->xcpw = new xcpw();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '获取票外信息',	 
			'brief' => '获取票外信息',
			'space' => '60',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	function count()
	{
		
	}
	function detail()
	{
		
	}
	public function show()
	{	
		$sql = 'SELECT offset,locked FROM '.DB_PREFIX.'xcpw_queue where locked=1';
		$queue = $this->db->query_first($sql);
		//hg_pre($queue);exit();
		if($queue['locked'])
		{
			$offset = $queue['offset']?$queue['offset']:1;
			//$count = $this->input['count']?intval(urldecode($this->input['count'])):1;
			$res = $this->xcpw->getProduct($offset);
			if ($res)
			{
				$this->update_queue($offset+1);
				$this->addItem(true);				
			}else{
				$this->reset_queue();
				$this->addItem(QUEUE_HAS_FINISHED);
			}
		}
		
		$this->output();
	}
	public function reset_queue()
	{
		$this->db->query('UPDATE '.DB_PREFIX.'xcpw_queue SET offset = 0, locked=0');
	}
	public function update_queue($offset=0)
	{
		//锁定队列准备开始执行
		$this->db->query('UPDATE '.DB_PREFIX.'xcpw_queue SET offset = '.intval($offset));
	}	
}
include(ROOT_PATH . 'excute.php');
?>
