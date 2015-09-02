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

        $data = array (
            'orderTime' => '20141120145525',
            'settleDate' => '1120',
            'orderNumber' => 'YB1098777782113519',
            'exchangeRate' => '0',
            'signature' => 'b63ffb2a963a2d10232f7008769afb92',
            'settleCurrency' => '156',
            'signMethod' => 'MD5',
            'transType' => '01',
            'respCode' => '00',
            'charset' => 'UTF-8',
            'sysReserved' => '{traceTime=1120145525&acqCode=00215800&traceNumber=009978}',
            'version' => '1.0.0',
            'settleAmount' => '1000',
            'transStatus' => '00',
            'merId' => '880000000002996',
            'qn' => '201411201455250099787',
        );

        $cancle_data = array (
            'orderTime' => '20141120172921',
            'settleDate' => '1120',
            'orderNumber' => '20141120172921',
            'exchangeRate' => '0',
            'signature' => '8b6c94a155ee96e756bb9a5dd25297ce',
            'settleCurrency' => '156',
            'signMethod' => 'MD5',
            'transType' => '31',
            'respCode' => '00',
            'charset' => 'UTF-8',
            'sysReserved' => '{traceTime=1120172921&acqCode=00215800&traceNumber=008859}',
            'version' => '1.0.0',
            'settleAmount' => '1000',
            'transStatus' => '00',
            'merId' => '880000000002996',
            'qn' => '201411201729210088597',
        );

        $refued_data = array (
            'orderTime' => '20141120174303',
            'settleDate' => '1120',
            'orderNumber' => '20141120174303',
            'exchangeRate' => '0',
            'signature' => '9f293b265deb908164275d6359e861f4',
            'settleCurrency' => '156',
            'signMethod' => 'MD5',
            'transType' => '04',
            'respCode' => '00',
            'charset' => 'UTF-8',
            'sysReserved' => '{traceTime=1120174303&acqCode=00215800&traceNumber=010740}',
            'version' => '1.0.0',
            'settleAmount' => '2000',
            'transStatus' => '00',
            'merId' => '880000000002996',
            'qn' => '201411201743030107407',
        );

        $reqString = $pay_driver->createLinkString($refued_data);

        echo $reqString;exit;

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