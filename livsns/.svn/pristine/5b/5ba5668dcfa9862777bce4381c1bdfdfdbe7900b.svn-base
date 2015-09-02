<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id: config.php 15986 2013-03-21 14:28:00 jeffrey $
 ***************************************************************************/

$gGlobalConfig['is_back'] =  '0';//配送状态是否可逆向修改

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_payments',
'charset'  => 'utf8',
'pconnect' => '',
);


/**
 * 状态码说明
 * 1-20    支付相关
 * 21-30   订单状态
 * 81-90   库存状态
 * 91-110  配送相关
 * 111-120 评论
 */

$gGlobalConfig['order_status'] = array(
'pay_status' => array(1 => '待支付', 2 => '支付完成',3=>'支付失败订单过期',4=>""), 
'is_cancel' => array(21 => "取消", 22 => "未取消"), 
'is_completed' => array(23 => "完成", 24 => "未完成"), 
'is_comment' => array(111 => "已评", 112 => "未评"), 
'store_status' => array(81 => '有', 82 => '无'), 
'order_status' => array(21 => '未支付', 22 => '取消订单', 23 => "订单完成", 24 => "订单过期",25=>'支付完成' ), );
/**
 * 21 => '尚未完成未支付', 22 => '取消订单', 23 => "订单完成", 24 => "订单过期",25=>'尚未完成已支付完成'
 * 
 * 22 23 24 为订单完成 22 24 为异常 25 没有异常
 */

$gGlobalConfig['alipay_type'] = 'alipaywap';


/**
 * sms 相关配置
 */
$gGlobalConfig['sms']['vendor'] = '3tong';     //第三方服务商
$gGlobalConfig['sms']['is_open'] = 1;
$gGlobalConfig['sms']['api_url'] = "http://3tong.net/http/sms/Submit";
$gGlobalConfig['sms']['username'] =  '5895115';
$gGlobalConfig['sms']['password'] =  md5("5895115");
/**
 * sms 内容主体配置
 */
$gGlobalConfig['sms']['header'] = '您已成功购买，';
$gGlobalConfig['sms']['needsmsbody'] = 1;//是否需要短信body部分
$gGlobalConfig['sms']['footer'] = "请凭短信至徐州音乐厅售票处提前换取入场门票。";
/**
 * sms 内容主体配置 end
 */

 
/**
 * 支付方式
 * 1.money
 * 2.credits
 * 3.money+credits
 */
 
$gGlobalConfig['paymethod'] = array(
1,2,3
);
  
 

$gGlobalConfig['bill_config'] = array(
'bill_type'=>array(1=>'纸质',2=>'电子'),
'bill_header_type'=>array(1=>'个人',2=>'公司'),
'bill_content_type'=>array(1=>'商品列表')
);

//配送流程
$gGlobalConfig['trace_step'] = array(
                            0 => '',
                            1 => "确认订单", 
                            2 => "打印票据", 
                            3 => "打包", 
                            4 => "出库", 
                            5 => "货运中", 
                            6 => "到达配送站", 
                            7 => "指定配送人员", 
                            8 => "配送", 
                            9 => "签收", 
                            10 => "完成");

/**
 * 搜索
 */
//时间搜索
$gGlobalConfig['search']['date_search'] = array(
            1 => '所有时间段', 
            2 => '昨天', 
            3 => '今天', 
            4 => '最近3天', 
            5 => '最近7天', 
            'other' => '自定义时间', 
);

$gGlobalConfig['search']['trace_step'] =array(
							-1 =>'所有配送状态',
                            1 => "确认订单", 
                            2 => "打印票据", 
                            3 => "打包", 
                            4 => "出库", 
                            5 => "货运中", 
                            6 => "到达配送站", 
                            7 => "指定配送人员", 
                            8 => "配送", 
                            9 => "签收", 
                            10 => "完成");
 
$gGlobalConfig['search']['order_status'] = array(
            -1 =>'所有支付状态',
            1  => "待支付",
            2 => "支付完成",
            3 =>"支付失败订单过期"

);
$gGlobalConfig['creditLogTitle'] = array(//积分支付日志标题
            'jf_mall' =>'积分商城兑换',
            'ticket' =>'票务支付',
);

/**
 * 搜索 end
 */

define('APP_UNIQUEID', 'payments'); //应用标识
define('DB_PREFIX','liv_');        //定义数据库表前缀
define('UNKNOW', '未知错误');         //未知错误
define('OBJECT_NULL', '参数缺损');
define('INITED_APP', true);

$gGlobalConfig['max_time_shift'] =  '168';
?>