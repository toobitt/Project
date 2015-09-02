<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
date_default_timezone_set('PRC');
define('MOD_UNIQUEID', 'pay_order');
require './global.php';
require_once (CUR_CONF_PATH . 'core/Core.class.php');
require_once (CUR_CONF_PATH . 'conf/config.php');
require_once (CUR_CONF_PATH . 'lib/Paydatalog.class.php');
if(!isset($gGlobalConfig['alipay_type']))
{
    exit('没有设置支付宝的支付支付方式');
}

$alipath = CUR_CONF_PATH ."alipay/".$gGlobalConfig['alipay_type']."/";

require_once (CUR_CONF_PATH . 'lib/sms.class.php');

require_once($alipath."alipay.config.php");

require_once($alipath."lib/alipay_notify.class.php");

require_once($alipath."notify_url.php");
 
?>