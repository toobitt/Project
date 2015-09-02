<?php
	$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_feedback',
'charset'  => 'utf8',
'pconnect' => '',
);
define('APP_UNIQUEID','feedback');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀
define('INITED_APP', true);
define('PUBLISH_SET_ID', 783);
define('ADD_CREDIT_NUM', 1);//每个表单会员增加几次积分
define('UPLOAD_MATERIAL_SIZE', 5);//限制上传素材大小5M
define('UPLOAD_MEDIA_SIZE', 5);//限制上传素材大小5M
define('FB_DOMAIN', 'http://10.0.1.40/livsns/api/feedback/data/');//限制上传素材大小5M
define('PROVINCE_ID', 320000);//默认省份
define('CITY_ID', 320100);//默认城市
define('AUDIT_ADD_CRIDET', 1);//1-审核过加积分，0-审核前加积分
define('CORE_DIR', CUR_CONF_PATH.'core/');//定义模板目录
define('DATA_DIR', CUR_CONF_PATH.'data/');//定义h5缓存目录
define('NO_DEVICE_TIPS', '客户端版本太低，请先升级');//无设备号提示
define('APPID', '98');
define('APPKEY', 'nepUOH76IqirMNOwvxITEMnAA39O7bPl');
define('PASSWORD', '888888');
define('NEED_CHECK_DEVICE', 0 );
define('TEMPLATE_NAME', 'Baoming');    //使用模板
define('ADVANCED',0);   //高级编辑
define('RESULT_CACHE_TIME',3600);//统计结果缓存时间

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

//时间搜索
$gGlobalConfig['db_bind'] = array(
 	'host'	=> 'db.dev.hogesoft.com',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'dev_member_bind',
);


//时、分、秒
$gGlobalConfig['hour'] = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
$gGlobalConfig['minit'] = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59');
$gGlobalConfig['second'] = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59');

//常用组件类型配置
$gGlobalConfig['form_type'] = array(
  1 => array('type'=>'1' , 'tpl'=>'one-text-tpl' , 'title'=>'单行文本框') ,
  2 => array('type'=>'2' , 'tpl'=>'multiline-text-tpl' , 'title'=>'多行文本框'),
  3 => array('type'=>'3' , 'tpl'=>'more-choice-tpl' , 'title'=>'单选/多选'),
  4 => array('type'=>'4' , 'tpl'=>'select-tpl' , 'title'=>'下拉选择'),
  5 => array('type'=>'5' , 'tpl'=>'upload-tpl' , 'title'=>'上传附件'),
  6 => array('type'=>'6' , 'tpl'=>'divide-box-tpl' , 'title'=>'分割线'),
);

//固定组件类型配置
$gGlobalConfig['fixed_type'] = array(
  1 => array('tpl'=>'one-text-tpl' , 'title'=>'姓名') ,
  2 => array('tpl'=>'one-text-tpl' , 'title'=>'邮箱'),
  3 => array('tpl'=>'one-text-tpl' , 'title'=>'电话'),
  4 => array('tpl'=>'address-box-tpl' , 'title'=>'地址'),
  5 => array('tpl'=>'date-box-tpl' , 'title'=>'日期'),
  6 => array('tpl'=>'time-box-tpl' , 'title'=>'时间'),
);
//固定组件包含的元素配置
$gGlobalConfig['element'] = array(
  1 => array('name'=>'时','form_type' => 4,'fixed_id'=>6),
  2 => array('name'=>'分','form_type' => 4,'fixed_id'=>6),
  3 => array('name'=>'秒','form_type' => 4,'fixed_id'=>6),
  8 => array('name'=>'省份','form_type' => 4,'fixed_id'=>4),
  9 => array('name'=>'城市','form_type' => 4,'fixed_id'=>4),
  10 => array('name'=>'区县','form_type' => 4,'fixed_id'=>4),
  11 => array('name'=>'请填写详细地址','form_type' => 1,'fixed_id'=>4),
  );
  
$gGlobalConfig['status'] = array(
 0 => '待审核',
 1 => '已审核',
 2 => '已打回',
);
$gGlobalConfig['process'] = array(
 0 => '待处理',
 1 => '已处理',
 2 => '未通过',
 );
 
$gGlobalConfig['standard'] = array( 1 => 'input', 2 => 'textarea', 3 => 'choose' , 4=> 'select' ,5=>'file' , 6=>'split',7=>'radio',8=>'checkbox');
$gGlobalConfig['fixed'] = array( 1 => 'name', 2 => 'email', 3 => 'tel' , 4=> 'address' ,5=>'date' , 6=>'time');

//视频类型配置
$gGlobalConfig['video_type'] = array(
	'allow_type' => '.wmv,.avi,.dat,.asf,.rm,.rmvb,.ram,.mpg,.mpeg,.3gp,.mov,.mp4,.m4v,.dvix,.dv,.dat,.mkv,.flv,.vob,.ram,.qt,.divx,.cpk,.fli,.flc,.mod,.m4a,.f4v,.3ga,.caf,.mp3,.vob,.aac,.amr,.ts'
);
$gGlobalConfig['used_search_condition'] =  array (
);

$gGlobalConfig['source'] = array (
	0 => '59',
);
$gGlobalConfig['des'] = array (
	0 => '630',
);

?>



