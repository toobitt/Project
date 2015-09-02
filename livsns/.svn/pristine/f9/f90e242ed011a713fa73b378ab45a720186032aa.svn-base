<?php
/*
 * 功能：付完款后跳转的页面（页面跳转同步通知页面） 版本：2.0 日期：2011-09-01 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */
session_start();
define('ROOT_DIR', '../../../../');
define('CUR_CONF_PATH', './../../');
require_once(ROOT_DIR.'global.php');
require_once ("../../lib/paydata.class.php");
require_once ("class/alipay_notify.php");

// 构造通知函数信息
$alipay = new alipay_notify ( $_SESSION['partner'], $_SESSION['key'], $_SESSION['sec_id'], $_SESSION['_input_charset'] );
// 计算得出通知验证结果
$verify_result = $alipay->return_verify ();

//实例化类
$out = new paydataClass();
$data = array();
if ($verify_result) {
	                   
	$mydingdan = $_GET ['out_trade_no'];   // 外部交易号
	$myresult = $_GET ['result'];          // 订单状态，是否成功
	$mytrade_no = $_GET ['trade_no'];      // 交易号

	if ($_GET ['result'] == 'success') {
		//入库操作
		$data['out_trade_no'] = $mydingdan;
		$data['trade_no'] = $mytrade_no;
		$data['trade_status'] = $myresult;
		$data['create_time'] = TIMENOW;
		$data['update_time'] = TIMENOW;
		$out->create($data);
		
		
		//重新生成配置文件
		$partner = $_SESSION['partner'];
		$key = $_SESSION['key'];
		$sec_id = $_SESSION['sec_id'];
		$_input_charset = $_SESSION['_input_charset'];
		
		$sing_arr = "<?php";
		$sing_arr .= "\r\n";
		$sing_arr .= "$"."partner = '$partner';  ";
		$sing_arr .= "\r\n";
		$sing_arr .= "$"."key = '$key';  ";
		$sing_arr .= "\r\n";
		$sing_arr .= "$"."sec_id = '$sec_id';  ";
		$sing_arr .= "\r\n";
		$sing_arr .= "$"."_input_charset = '$_input_charset';  ";
		$sing_arr .= "\r\n";
		$sing_arr .= "?>";
		
		file_put_contents("config/".$mytrade_no."_cache_code.php",$sing_arr);
		
		if (!file_exists("config/".$mytrade_no."_cache_code.php")) 
		{
			$data_back = array();
			$data_back['mytrade_no'] = $mytrade_no;
			$data_back['partner'] = $partner;
			$data_back['key'] = $key;
			$data_back['sec_id'] = $sec_id;
			$data_back['_input_charset'] = $_input_charset;
			$out->create_back($data_back);
		}
	}
}
else {
	echo "fail";
}

?>