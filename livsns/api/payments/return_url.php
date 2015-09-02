<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */
define('MOD_UNIQUEID', 'pay_order');
require './global.php';
require_once (CUR_CONF_PATH . 'core/Core.class.php');
require_once (CUR_CONF_PATH . 'conf/config.php');

if(!isset($gGlobalConfig['alipay_type']))
{
    exit('没有设置支付宝的支付支付方式');
}

require_once (CUR_CONF_PATH . 'lib/Paydatalog.php');

$alipath = CUR_CONF_PATH ."alipay/".$gGlobalConfig['alipay_type']."/";

require_once($alipath."alipay.config.php");

require_once($alipath."lib/alipay_notify.class.php");

require_once($alipath."return_url.php");

?>
