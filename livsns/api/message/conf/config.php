<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/

$gDBconfig = array('host' => 'db.dev.hogesoft.com',
'user' => 'root',
'pass' => 'hogesoft',
'database' => 'dev_message',
'charset' => 'utf8',
'pconncet' => '0',
);
 
define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','message');//应用标识
define('MESSAGE_SET_CACHE_DIR', CUR_CONF_PATH . 'cache/');//评论设置生成缓存路径

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
//评论敏感词过滤规则
$gGlobalConfig['message_colation'] = array(
  1 => "禁止入库", 
  2 => "入库未审核",
  3 => "过滤敏感词"
 );
 
 //评论类型
$gGlobalConfig['comment_type'] = array(
  0 => "发布库", 
  1 => "栏目",
  //2 => "应用"
 );
 
//评论节点类型
$gGlobalConfig['node_change'] = array(
  64 => "按分类查看",
  101 => "按栏目查看"
 );
 //评论检索年份
$gGlobalConfig['comment_year'] = array(
  0 => "本年发布内容评论",
  1 => "去年发布内容评论"
 );
 
//后台添加评论默认状态
$gGlobalConfig['default_state'] = 0;
 
//评论的状态
$gGlobalConfig['message_status'] = array(
  0=>"全部状态",
  1 => "待审核",
  2 => "已审核",
  3 => '打回',
);
$gGlobalConfig['message_form_set'] = array(
	'display' => array(
		'state'=>array(
			'title' => '评论功能',
			'names' => 'state',
			'type' => 'radio',
			'value1' => '1',
			'value2' => '0',
			'def_val' => '0',
		),
		'allow_reply'=>array(
			'title' => "评论回复",
			'names' => "allow_reply",
			'type' => "radio",
			'value1' => '1',
			'value2' => '0',
			'def_val' => '0',
		),
		'vote'=>array(
			'title' => "评论投票",
			'names' => "vote",
			'type' => "radio",
			'value1' => '1',
			'value2' => '0',
			'def_val' => '0',
		),
		'allow_quoted'=>array(
			'title' => "引用评论",
			'names' => "allow_quoted",
			'type' => "radio",
			'value1' => '1',
			'value2' => '0',
			'def_val' => '0',
		),
		'display'=>array(
			'title' => "审核显示",
			'names' => "display",
			'type' => "radio",
			'value1' => '1',
			'value2' => '0',
			'def_val' => '0',
		),
		'display_order'=>array(
			'title' => "显示顺序",
			'names' => "display_order",
			'type' => "radio",
			'value1' => '1',
			'value2' => '0',
			'def_val' => '1',
		),
	),
	"rule" => array(
		'is_login' => array(
			'title' => "登录/匿名",
			'names' => "is_login",
			'type' => "radio",
			'value1' => '1',
			'value2' => '0',
			'def_val' => '0',
		),
		'is_credits' => array(
			'title' => "未审核积分",
			'names' => "is_credits",
			'type' => "radio",
			'value1' => '1',
			'value2' => '0',
			'def_val' => '0',
		),
		'is_credits_extra' => array(
			'title' => "审核积分",
			'names' => "is_credits_extra",
			'type' => "radio",
			'value1' => '1',
			'value2' => '0',
			'def_val' => '0',
		),
		'verify_mode'=>array(
			'title' => "验证码",
			'names' => "verify_mode",
			'type' => "radio",
			'value1' => '1',
			'value2' => '0',
			'def_val' => '0',
		),
		'reply_way'=>array(
			'title' => "回复方式",
			'names' => "reply_way",
			'type' => "radio",
			'value1' => '1',
			'value2' => '0',
			'def_val' => '0',
			),
		'colation'=>array(
			'title' => "敏感词过滤",
			'names' => "colation",
			'type' => "radio",
			'value1' => '1',
			'value2' => '0',
			'def_val' => '0',
		),
		'rate'=>array(
			'title' => "评论频率",
			'names' => "rate",
			'type' => "text",
			'def_val' => '10',
		),
		'max_word'=>array(
			'title' => "最大字数",
			'names' => "max_word",
			'type' => "text",
			'def_val' => '200',
		),
		'same_user_same_record' => array(
			'title' => "积分规则",
			'names' => "same_user_same_record",
			'type' => "text",
			'def_val' => '2',
		),
	)
 );
//留言信箱配置
$gGlobalConfig['message_mailbox_type'] = array(
  1 => "合作洽谈", 
  2 => "建议意见",
  3 => "投诉处理",
  4 => "疑难提问",
  5 => "bug提交",
  6 => "添加分类"
 );
define('INITED_APP', true);
?>