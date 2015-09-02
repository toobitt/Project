<?php
define('MOD_UNIQUEID','dataplant');
define('SCRIPT_NAME', 'dataPlant');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
require_once(CUR_CONF_PATH.'lib/dataPlant.class.php');
require_once(CUR_CONF_PATH.'core/data_migration.php');
class dataPlant extends cronBase
{
	public function __construct()
	{
		parent::__construct();
		$this->dp = new ClassDataPlant();
		$this->migration = new DataMigration();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '数据迁移',	 
			'brief' => '数据迁移',
			'space' => '300',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		//队列执行开始
		$sql = 'SELECT offset,locked FROM '.DB_PREFIX.'data_queue where locked=1';
		$queue = $this->db->query_first($sql);
		if($queue['locked'])
		{
			$offset = $queue['offset']?$queue['offset']:0;
			$count = $this->input['count']?intval($this->input['count']):1;
			$limit = " limit {$offset}, {$count}";
			$condition = '';
			$orderby = '';
			//获取配置
			$configs = $this->dp->show($condition, $orderby, $offset, $count);
			if (!empty($configs))
			{
				$this->update_queue($offset+$count);
				foreach ($configs as $config)
				{
					
					$this->migration->show($config);
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
		$this->db->query('UPDATE '.DB_PREFIX.'data_queue SET offset = 0, locked=0');
	}
	public function update_queue($offset=0)
	{
		//锁定队列准备开始执行
		$this->db->query('UPDATE '.DB_PREFIX.'data_queue SET offset = '.intval($offset));
	}
}
include(ROOT_PATH . 'excute.php');