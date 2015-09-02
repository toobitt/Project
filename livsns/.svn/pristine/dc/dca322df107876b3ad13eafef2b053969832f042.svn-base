<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/19
 * Time: 上午10:50
 */
require 'global.php';
define('MOD_UNIQUEID', 'hogepay');
define('WITHOUT_DB', 1);
class testApi extends InitFrm
{
    function __construct()
    {
        parent::__construct();
    }

    function __destruct() {
        parent::__destruct();
    }

    function show() {
        include_once (CUR_CONF_PATH . 'lib/pay/hg_pay.class.php');
        include_once (CUR_CONF_PATH . 'lib/hg_order.class.php');

        $config = array();
        $hgPayFactory = hgPayFactory::get_instance($config);
        $pay_driver = $hgPayFactory->get_driver('unionpay');

        $order = new HgOrder();
        $order->desc = '我是来测试退货的';
        $order->trade_create_time = '20141120174303';//date('YmdHis', TIMENOW);
        $order->trade_expire_time = date('YmdHis', TIMENOW + 3600);
        $order->trade_num = '20141120174303'; //date('YmdHis', TIMENOW);
        $order->total_fee = '2000';
        $data = $pay_driver->getPayParam( $order);
//        $order->pay_trade_num = '20141120174303';
//        $data = $pay_driver->query($order);

        var_dump($data);

    }

    function count() {}

    function detail() {}
}

$out = new testApi();
$action = $_GET['a'];
if(!method_exists($out,$action))
{
    $action = 'show';
}
$out->$action();