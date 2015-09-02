<?php
require_once ("alipay_config.php");
require_once ("class/alipay_service.php");

// 构造要请求的参数数组，无需改动
$pms1 = array (
		"req_data" => '<direct_trade_create_req><subject>' . $subject . '</subject><out_trade_no>' . $out_trade_no . '</out_trade_no><total_fee>' . $total_fee . "</total_fee><seller_account_name>" . $seller_email . "</seller_account_name><notify_url>" . $notify_url . "</notify_url><out_user>" . $_GET ["out_user"] . "</out_user><merchant_url>" . $merchant_url . "</merchant_url>" . "<call_back_url>" . $call_back_url . "</call_back_url></direct_trade_create_req>",
		"service" => $Service_Create,
		"sec_id" => $sec_id,
		"partner" => $partner,
		"req_id" => date ( "Ymdhms" ),
		"format" => $format,
		"v" => $v 
);

// 构造请求函数
$alipay = new alipay_service ();

// 调用alipay_wap_trade_create_direct接口，并返回token返回参数
$token = $alipay->alipay_wap_trade_create_direct ( $pms1, $key, $sec_id );


// 构造要请求的参数数组，无需改动
$pms2 = array (
		"req_data" => "<auth_and_execute_req><request_token>" . $token . "</request_token></auth_and_execute_req>",
		"service" => $Service_authAndExecute,
		"sec_id" => $sec_id,
		"partner" => $partner,
		"call_back_url" => $call_back_url,
		"format" => $format,
		"v" => $v 
);

// 调用alipay_Wap_Auth_AuthAndExecute接口方法，并重定向页面
$ruselt_url = $alipay->alipay_Wap_Auth_AuthAndExecute ( $pms2, $key );
?>
