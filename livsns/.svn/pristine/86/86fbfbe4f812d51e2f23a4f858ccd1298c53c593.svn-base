<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','AutoTimeout');
define('SCRIPT_NAME', 'AutoTimeout');
class AutoTimeout extends cronBase
{

    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,
            'name' => '处理过期订单',
            'brief' => '处理过期订单',
            'space' => '30', //运行时间间隔，单位秒
            'is_use' => 1,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }

    public function show()
    {
        $sql = "UPDATE ".DB_PREFIX."orders SET trade_status = 'TRADE_CANCLED'
            WHERE trade_status = 'NOT_PAY' AND trade_expire_time <=" . TIMENOW;
        $this->db->query($sql);
    }
}

require_once ROOT_PATH . 'excute.php';

?>
