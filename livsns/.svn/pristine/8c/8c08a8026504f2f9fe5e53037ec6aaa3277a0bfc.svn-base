<?php
require './global.php';
require_once (CUR_CONF_PATH . 'core/Core.class.php');
require_once (CUR_CONF_PATH . 'lib/sms.class.php');
if (!isset($_REQUEST['out_trade_no'])) {
    return false;
}

if (!$_REQUEST['out_trade_no']) {
    
    return false;
}

$out_trade_no = $_REQUEST['out_trade_no'];

$db = new Core();

$query = "SELECT * FROM " . DB_PREFIX . "order 
                  WHERE 
                  pay_status=1 
                  and order_id='" . $_REQUEST['out_trade_no'] . "'";
$query = "SELECT * FROM " . DB_PREFIX . "order 
                  WHERE 
                  order_id='" . $_REQUEST['out_trade_no'] . "'";
$result = $db -> query($query,'');
if (!$result) {
    return false;
}

/**
 * 1.money 2.credits 3.money+credits
 */
 
$paymethod = $result[0]['paymethod'];

$payprocess = $result[0]['payprocess'];

$payprocess |= 2;

if($payprocess==$paymethod)
{
    $params['order_status'] = 25; //支付完成
    $params['pay_status'] = 2;    //支付完成
    $params['integral_status']= (int)$_REQUEST['integral_status']; //冻结积分
}
$params['payprocess'] = $payprocess;
$re = $db -> update('order', $params, " where order_id='" . $_REQUEST['out_trade_no'] . "'");
$order_id = $_REQUEST['out_trade_no'];
if($payprocess==$paymethod)
{
    $sms = new sms($order_id);
    $sms -> sendsms();
}
?>