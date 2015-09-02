<?php
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();



if(!$verify_result) {
    file_put_contents("./cache/alipay".date("Y-m-d").".txt", var_export($_REQUEST,1)."\r\n",FILE_APPEND);
    
    //验证成功
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//商户订单号
	$out_trade_no = $_REQUEST['out_trade_no'];

	//支付宝交易号
	$trade_no = $_REQUEST['trade_no'];

	//交易状态
	$trade_status = $_REQUEST['trade_status'];


    if($_REQUEST['trade_status'] == 'TRADE_FINISHED') {
        
        $db = new Core();
        
        $query = "SELECT * FROM ".DB_PREFIX."order 
                  WHERE 
                  pay_status=1 
                  and order_id='".$_REQUEST['out_trade_no']."'"
                 ;
        $result = $db->query($query,'');
        
        if(!$result)
        {
            return false;
        }
        
        $paydatalog = new Paydatalog();
        $paydatalog->create($_REQUEST);
        
        $paymethod = $result[0]['paymethod'];

        $payprocess = $result[0]['payprocess'];
        
        $payprocess |= 1;
        
        if($payprocess==$paymethod)
        {
            $params['trade_no'] = $_REQUEST['trade_no'];
            $params['order_status'] = 25;//支付完成
            $params['pay_status'] = 2;
            $params['payprocess'] = $payprocess;
            $re = $db -> update('order', $params, " where order_id='" . $_REQUEST['out_trade_no'] . "'");
            $order_id = $_REQUEST['out_trade_no'];
            $sms = new sms($order_id);
            $sms -> sendsms();
        }
        
        
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
        
    }

	echo "success";		//请不要修改或删除
	
}
else {
    //验证失败
    echo "fail";
}
?>