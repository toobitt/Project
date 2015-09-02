<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_service12345',
'charset'  => 'utf8',
'pconnect' => '',
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','service12345');//应用标识
define('BAIDU_CONVERT_DOMAIN','http://api.map.baidu.com/ag/coord/convert?from=0&to=4');
define('WEB_URL','http://61.132.118.100/xz12345WebService/12345publish2web.asmx?wsdl');
define('INITED_APP', true);

$gGlobalConfig['service_type'] = array(
  1 => '便民服务',
  2 => '公共服务',
);
$gGlobalConfig['service_sex'] = array(
  0 => '先生',
  1 => '女士',
);
$gGlobalConfig['service_area'] = array(
	 0 => '',
	10 => '新沂市',
	15 => '邳州市',
	20 => '鼓楼区',
	25 => '云龙区',
	30 => '开发区',
	35 => '贾汪区',
	40 => '泉山区',
	45 => '丰县',
	50 => '沛县',
	55 => '铜山区',
	60 => '睢宁县',
	65 => '新城区',
);
$gGlobalConfig['service_case_status'] = array(
	 0 => '未知',
	10 => '标记作废',
	15 => '草稿',
	20 => '已提交任务单',
	25 => '已派任务单',
	30 => '已答复',
	40 => '已答复待回访',
	50 => '回访客户不满意，要求再处理',
	80 => '办结',
);
$gGlobalConfig['service_case_type'] = array(
	 0 => '未知',
	10 => '咨询',
	15 => '查找服务',
	20 => '生活服务',
	30 => '政府求助',
	40 => '投诉',
	50 => '建议、表扬',
	60 => '特别服务',
	70 => '市民卡',
	80 => '美食网',
	90 => '其他',
	95 => '出租车调度',
	100 => '订票业务',
	110 => '公共自行车',
	120 => '预约挂号',
);
?>