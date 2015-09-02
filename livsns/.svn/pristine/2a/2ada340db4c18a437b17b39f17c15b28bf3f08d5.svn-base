<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','workload_operation');
require_once(CUR_CONF_PATH . 'core/statistics.class.php');
class getLogOperation extends cronBase
{
    public function __construct()
    {
    	parent::__construct();
    	$this->statistics = new statistics();
    }
    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,  
            'name' => '获取日志操作数据',    
            'brief' => '获取日志操作数据',
            'space' => '86400', //运行时间间隔，单位秒
            'is_use' => 1,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }
    
    public function get_work()
    {
    	$static_date = $this->input['static_date'];
		$wokload = $this->statistics->get_log_operation($static_date);
		$this->addItem($wokload);
		$this->output();
    }
}

$out = new getLogOperation();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'get_work';
}
$out->$action();

?>
