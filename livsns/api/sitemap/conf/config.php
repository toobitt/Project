<?php

$gDBconfig = array(
    'host' => 'db.dev.hogesoft.com',
    'user' => 'root',
    'pass' => 'hogesoft',
    'database' => 'dev_sitemap',
    'charset' => 'utf8',
    'pconncet' => '0',
);
define('APP_UNIQUEID','sitemap');//应用标识
define('DB_PREFIX','liv_');//定义数据库表前缀

$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

$gGlobalConfig['status_config'] = array(
    0 => '未审核',
    1 => '已审核',
    2 => '已打回',
);

//网址类型
$gGlobalConfig['url_suffix'] = array(
    0   => '*.html',
    1   => '*.htm',
    2   => '*.php',
    3   => '*.jsp',
    4   => '*.aspx',
    5   => '*.asp',
    6   => '*.cgi',
    7   => '*.shmtl',
    8   => '*.shtm',
    9   => '*.net',
    10  => '*.tpl',
);

//Sitemap格式
$gGlobalConfig['sitemap_format'] = array(
    0 => 'Sitemap.xml',
    1 => 'Sitemap.xml.gz',
    2 => 'Sitemap.html',
    3 => 'Sitemap.txt',
);

$gGlobalConfig['ping_settings'] = array(
    0 => '百度蜘蛛',
    1 => '谷歌蜘蛛',
    2 => '雅虎蜘蛛',
);

define('INITED_APP', true);
?>