<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/12/2
 * Time: 下午5:54
 */
require_once('global.php');
define(SCRIPT_NAME, 'OrderApi');
define('MOD_UNIQUEID','hogepay_order');
class OrderApi extends adminReadBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function index(){}

    public function show()
    {
        $stat_list = $this->settings['trade_status'];
        foreach ((array)$stat_list as $k => $v)
        {
            $row = array('title' => $v, 'is_last' => 1,'id' => $k, 'name' => $v, 'depath' => 1);
            $this->addItem($row);
        }
        $this->output();
    }

    public function detail(){}

    public function count(){}
}

require_once ROOT_PATH . 'excute.php';