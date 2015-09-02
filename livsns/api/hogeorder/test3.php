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
        $order->trade_create_time = date('YmdHis', TIMENOW);
        $order->trade_num = date('YmdHis', TIMENOW);
        $order->total_fee = '1000';
        $order->pay_trade_num = '201411201711470102157';
        $data = $pay_driver->cancle($order);

        var_dump($data);

    }

    function show2() {
        include_once (CUR_CONF_PATH . 'lib/pay/hg_pay.class.php');
        include_once (CUR_CONF_PATH . 'lib/hg_order.class.php');

        $config = array();
        $hgPayFactory = hgPayFactory::get_instance($config);
        $pay_driver = $hgPayFactory->get_driver('unionpay');

        $order = new HgOrder();
        $order->trade_create_time = date('YmdHis', TIMENOW);
        $order->trade_num = date('YmdHis', TIMENOW);
        $order->total_fee = '2000';
        $order->pay_trade_qn = '201411201738400106687';
        $data = $pay_driver->refund($order);

        var_dump($data);

    }

    function getToken()
    {
        include_once (CUR_CONF_PATH . 'lib/pay/hg_pay.class.php');
        include_once (CUR_CONF_PATH . 'lib/hg_order.class.php');

        $config = array();
        $hgPayFactory = hgPayFactory::get_instance($config);
        $pay_driver = $hgPayFactory->get_driver('weixin');

        $ret = $pay_driver->getToken();

        var_dump($ret);
    }

    function count() {}

    function detail() {}
}

$out = new testApi();
$action = $_GET['a'];
if(!method_exists($out,$action))
{
    $action = 'getToken';
}
$out->$action();