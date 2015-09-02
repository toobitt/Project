<?php
$gDBconfig = array(
    'host'     => 'db.dev.hogesoft.com',
    'user'     => 'root',
    'pass'     => 'hogesoft',
    'database' => 'dev_hogeorder',
    'charset'  => 'utf8',
    'pconncet' => 0,
);

define('DB_PREFIX','liv_');     //定义数据库表前缀
define('APP_UNIQUEID','hogeorder');


$gGlobalConfig['aa'] = array(

) ;

$gGlobalConfig['pay_type'] = array(
    'alipay' => array(
        'uniqueid' => 'alipay',
        'title'    => '支付宝即时到账交易',
        'brief'    => '中国领先的在线支付平台，致力于为互联网用户和企业提供安全、便捷、专业的在线支付服务。',
    ),
    'weixin' => array(
        'uniqueid' => 'weixin',
        'title'    => '微信支付',
        'brief'    => '微支付，支付就这么简单。',
    ),
    'unionpay' => array(
        'uniqueid' => 'unionpay',
        'title'    => '银联支付',
        'brief'    => '安全、便捷、高效在线支付服务。',
    ),
);

$gGlobalConfig['trade_status'] = array(
    'NOT_PAY'  => '未付款',
    'HAS_PAY'  => '已付款',
    'HAS_DELIVER' => '已发货',
    'TRADE_SUCCESS ' => '交易成功',
    'TRADE_CANCLED'  => '交易取消',
    'TRADE_EXCEPTION' => '交易异常',
);

$gGlobalConfig['trade_flow_status'] = array(
    'TRADE_WAIT'        => '等待付款',
    'TRADE_SUCCESS'    => '交易成功',
    'TRADE_FINISHED'    => '交易成功,不可退款',
    'TRADE_EXCEPTION'   => '交易异常',
);

$gGlobalConfig['cache_type_config'] = array(
    'file'   => array(
        'type' => 'file',
        'cache_type' => 'array',
        'cache_path' => CACHE_DIR,
        'expire'  => 3600,
    ),
);


define('INITED_APP', true);

?>