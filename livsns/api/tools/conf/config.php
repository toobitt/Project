<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 17931 2013-02-26 01:34:49Z lijiaying $
***************************************************************************/
if(file_exists(DATA_DIR."db"))
{
	$db_switch = json_decode(file_get_contents(DATA_DIR."db"),1);
	if(!defined('DB_SWITCH') || !DB_SWITCH)
	{
		$db_name = $db_switch['used'];
	}
	else
	{
		$db_name = $db_switch['import'];
	}
}
else
{
	$db_name = 'dev_tools';
}

$gDBconfig = array(
	'host'     => 'db.dev.hogesoft.com',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => $db_name,
	'charset'  => 'utf8',
	'pconncet' => 0,
);

define('APP_UNIQUEID','tools');//应用标识

define('DB_PREFIX','liv_');//定义数据库表前缀

define('DATAURL','http://localhost/livsns/api/tools/data/');

$gGlobalConfig['field_type'] = array(
		'default' => '无',
		'int' => 'INT',
		'tinyint' => 'TINYINT',
		'varchar' => 'VARCHAR',
		'text' => 'TEXT',
);


$gGlobalConfig['field_index'] = array(
		'default' => '无',
		'primary' => '主键',
		//'foreign' => '外键',
		'index' => '索引',
);

$gGlobalConfig['field_index_value'] = array(
		'default' => '',
		'primary' => 'PRIMARY KEY',
		'index' => 'KEY',
		'foreign' => '',
);
$gGlobalConfig['xls_order'] = array(
1 => 'A',
2 => 'B',
3 => 'C',
4 => 'D',
5 => 'E',
6 => 'F',
7 => 'G',
8 => 'H',
9 => 'I',
10 => 'J',
11 => 'K',
12 => 'L',
13 => 'M',
14 => 'N',
15 => 'O',
16 => 'P',
17 => 'Q',
18 => 'R',
19 => 'S',
20 => 'T',
21 => 'U',
22 => 'V',
23 => 'W',
24 => 'X',
25 => 'Y',
26 => 'Z',
);
$gGlobalConfig['car_type'] = array(
'01' => '大型汽车号牌',
'02' => '小型汽车号牌',
'03' => '使馆汽车号牌',
'04' => '领馆汽车号牌',
'05' => '境外汽车号牌',
'06' => '外籍汽车号牌',
'07' => '两、三轮摩托车号牌',
'08' => '轻便摩托车号牌',
'09' => '使馆摩托车号牌',
'10' => '领馆摩托车号牌',
'11' => '境外摩托车号牌',
'12' => '外籍摩托车号牌',
'13' => '农用运输车号牌',
'14' => '拖拉机号牌',
'15' => '挂车号牌',
'16' => '教练汽车号牌',
'17' => '教练摩托车号牌',
'18' => '试验汽车号牌',
'19' => '试验摩托车号牌',
'20' => '临时入境汽车号牌',
'21' => '临时入境摩托车号牌',
'22' => '临时行驶车号牌',
'23' => '警用汽车号牌',
'24' => '警用摩托号牌',
);

$gGlobalConfig['provice_shortname'] = array(
'京',
'沪',
'港',
'吉',
'鲁',
'冀',
'湘',
'青',
'苏',
'浙',
'粤',
'台',
'甘',
'川',
'黑',
'蒙',
'新',
'津',
'渝',
'澳',
'辽',
'豫',
'鄂',
'晋',
'皖',
'赣',
'闽',
'琼',
'陕',
'云',
'贵',
'藏',
'宁',
'桂'
);


define('INITED_APP', true);


?>