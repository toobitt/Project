<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/26
 * Time: 下午2:15
 */
require_once ('./global.php');
define('MOD_UNIQUEID', 'hogeorder');
define('SCRIPT_NAME', 'CouponApi');
class CouponApi extends outerReadBase {
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
        $sql = "SELECT pay_type FROM ".DB_PREFIX."pay_config WHERE status = 1";
        $q = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q))
        {
            $row = $this->settings['pay_type'][$row['pay_type']];
            $ret[] = $row;
        }

        foreach ((array)$ret as $k => $v)
        {
            $this->addItem($v);
        }
        $this->output();
    }

    public function detail(){}
    public function count(){}
}

require_once (ROOT_PATH . 'excute.php');