<?php
	$partner		= $WIDpartner;			//合作身份者ID，以2088开头的16位纯数字
	$key   			= $WIDkey;			//安全检验码，以数字和字母组成的32位字符
	$seller_email	= $WIDseller_email;			//签约支付宝账号或卖家支付宝帐户

	$subject		= $WIDsubject;			//产品名称
	$out_trade_no	= $WIDout_trade_no;			//请与贵网站订单系统中的唯一订单号匹配
	$total_fee		= $WIDtotal_fee;			//订单总金额
	$out_user		= $WIDuid;			//商户系统中用户唯一标识、例如UID、NickName

	$notify_url		= $WIDnotify_url;			
	//服务端获取通知地址，用户交易完成异步返回地址
	$call_back_url	= $WIDcallback_url;			
	//用户交易完成同步返回地址
	$merchant_url	= $WIDmerchant_url;			
	//用户付款中途退出返回地址
	
	//以下参数为支付宝默认参数，禁止修改其参数值
	$Service_Create				= "alipay.wap.trade.create.direct";
	$Service_authAndExecute		= "alipay.wap.auth.authAndExecute";
	$format						= "xml";
	$sec_id						= "MD5";
	$_input_charset				= "utf-8";
	$v							= "2.0";
	
	
	$_SESSION['partner'] = $partner;
	$_SESSION['key'] = $key;
	$_SESSION['sec_id'] = $sec_id;
	$_SESSION['_input_charset'] = $_input_charset;
	

?>