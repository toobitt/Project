<?php

define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
define('CUR_M2O_PATH', '../lib/m2o/');
require_once(ROOT_PATH . 'global.php');
define('MOD_UNIQUEID', 'mkpublish_clean'); //模块标识
require(CUR_CONF_PATH . 'lib/functions.php');

class mkpublish_clean extends cronBase
{

    /**
     * 构造函数
     * @author repheal
     * @category hogesoft
     * @copyright hogesoft
     * @include news.class.php
     */
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
            'name' => '清楚错误日志',
            'brief' => '清楚错误日志',
            'space' => '3600', //运行时间间隔，单位秒
            'is_use' => 1, //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }
    
    public function show()
    {
        $pass_time = mktime(0,0,0,date("m"),date("d")-7,date("Y"));
        $sql = "DELETE FROM ".DB_PREFIX."mklog WHERE create_time<$pass_time";
        $this->db->query($sql);
    }

    

}

$out    = new mkpublish_clean();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>