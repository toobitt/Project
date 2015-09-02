<?php
define('MOD_UNIQUEID','refreshAudit');
define('SCRIPT_NAME', 'refreshAudit');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
require_once CUR_CONF_PATH.'core/audit.core.php';
class refreshAudit extends cronBase
{
	public function __construct()
	{
		parent::__construct();
		$this->core = new auditCore();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '审核请求队列',	 
			'brief' => '审核请求队列',
			'space' => '10',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
		
	public function show()
	{
		//队列执行开始
		$sql = 'SELECT offset,locked FROM '.DB_PREFIX.'audit_queue where locked=1';
		$queue = $this->db->query_first($sql);
		if($queue['locked'])
		{
			$offset = $queue['offset'] ? $queue['offset'] : 0;
			$count = $this->input['count'] ? intval($this->input['count']) : 3;
			$limit = " limit {$offset}, {$count}";
			$condition = '';
			$order = ' ORDER BY order_id DESC ';
			$configs = $this->core->show($condition, $order, $limit);
			if(!empty($configs))
			{
				$this->update_queue($offset+$count);
				foreach ($configs as $config)
				{
					$this->core->forward($config);
					$this->addItem(true);
				}
			}
			else
			{
				$this->reset_queue();
				$this->addItem(QUEUE_HAS_FINISHED);
			}
		}
		$this->output();
	}
	public function reset_queue()
	{
		$this->db->query('UPDATE '.DB_PREFIX.'audit_queue SET offset = 0, locked=0');
	}
	public function update_queue($offset=0)
	{
		//锁定队列准备开始执行
		$this->db->query('UPDATE '.DB_PREFIX.'audit_queue SET offset = '.intval($offset));
	}
}
include(ROOT_PATH . 'excute.php');