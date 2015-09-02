<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','workload_statistics');
require_once(CUR_CONF_PATH . 'lib/appset_mode.php');
require_once(CUR_CONF_PATH . 'core/statistics.class.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class getWork extends cronBase
{
    public function __construct()
    {
    	parent::__construct();
    	$this->appset = new appset_mode();
    	$this->statistics = new statistics();
    }
    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,  
            'name' => '获取统计数据',    
            'brief' => '获取统计数据',
            'space' => '20', //运行时间间隔，单位秒
            'is_use' => 1,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }
    
    public function get_work()
    {
    	//队列执行开始
		$sql = 'SELECT offset,locked FROM '.DB_PREFIX.'queue where locked=1';
		$queue = $this->db->query_first($sql);
		if($queue['locked'])
		{	
			$offset = $queue['offset']?$queue['offset']:0;
			$count = $this->input['count']?intval(urldecode($this->input['count'])):1;
			$limit = " limit {$offset}, {$count}";
			$condition = ' AND state = 1';
			$appinfo = $this->appset->show($condition , '',$limit);
			$appinfo = $appinfo[0];
			if($appinfo)
			{
				$this->update_queue($offset+$count);
				$static_date = $this->input['static_date'] ? $this->input['static_date'] : date('Y-m-d 00:00:00',strtotime('-1 day'));
				$date = strtotime(date('Y-m-d',strtotime($static_date)));
				$sql = 'SELECT flag FROM '.DB_PREFIX.'workload_log where date = '.$date.' AND app like "'.$appinfo['app_uniqueid'].'"';
				$result = $this->db->query_first($sql);
				if($result['flag'])
				{
					$this->addItem(APP_HAS_STATISTIC);
				}
				else
				{
					$this->db->query('INSERT INTO '.DB_PREFIX.'workload_log SET date = '.$date.', app="'.$appinfo['app_uniqueid'].'", flag=1');
					$wokload = $this->statistics->get_workloads($appinfo, $static_date);
					$this->addItem($wokload);
				}
			}
			else
			{
				$this->reset_queue();
				$this->addItem(QUEUE_HAS_FINISHED);
			}
		}
		else 
		{
			$this->addItem(QUEUE_HAS_NO_START);
		}
		$this->output();
    }

    public function reset_queue()
	{
		$this->db->query('UPDATE '.DB_PREFIX.'queue SET offset = 0, locked=0');
	}
	
	public function update_queue($offset=0)
	{
		//锁定队列准备开始执行
		$this->db->query('UPDATE '.DB_PREFIX.'queue SET offset = '.intval($offset));
	}
    
}

$out = new getWork();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'get_work';
}
$out->$action();

?>
