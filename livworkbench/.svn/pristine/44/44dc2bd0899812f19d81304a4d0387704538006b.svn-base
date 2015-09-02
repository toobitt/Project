<?php 
require '../../lib/head/head.php';
$title = '新闻';
$icons = array(
	left => array(
		0 => 'modules'
	),
);
require '../../lib/unit/nav.php';
$subnav = array(
	0 => array(
		name => '头条'
	),
	1 => array(
		name => '无锡'
	),
	2 => array(
		name => '微闻'
	),
	3 => array(
		name => '天下'
	),
	4 => array(
		name => '生活'
	),
	5 => array(
		name => '房产'
	),
	6 => array(
		name => '娱乐'
	),
);
require '../../lib/unit/subnav.php';
require '../../lib/unit/slide.php';
$list = array(
	0 => array(
		'title' => '习近平:以壮士断腕勇气反腐到底',
		'href' => '../../tpl/news/detail.php?type=video&src=http://www.w3school.com.cn/i/movie.mp4',
		'type' => 'video'
	),
	1 => array(
		'title' => '2014年春运今日启幕 预计客运量超36亿人次',
		'href' => '../../tpl/news/detail.php'
	),
	2 => array(
		'title' => '微信要革淘宝搜索广告的命 阿里高估值有压力',
		'href' => '../../tpl/news/detail.php'
	),
	3 => array(
		'title' => '外媒称中国2014年或选某个大老虎作为反腐突破口',
		'href' => '../../tpl/news/detail.php'
	),
	4 => array(
		'title' => '“春节四大晚会”3台确定停办 央视春晚成独苗',
		'href' => '../../tpl/news/detail.php'
	),
	5 => array(
		'title' => '美前防长：韩国曾欲大规模空袭朝鲜被中美阻止',
		'href' => '../../tpl/news/detail.php'
	)
);
?>
<style>
.list-item{height:70px;padding:0 10px;}
.list-item.video .list-pic{position:relative;}
.list-item.video .list-pic:after{position:absolute;content:'';background:url(../../lib/images/live/live-icon-play-l.png)no-repeat center;width:100%;height:100%;top:0;}
.list-item .title{line-height:24px;margin-right:15px;position:relative;}
.list-item .flag{position:absolute;bottom:4px;right:0;font-size:12px;text-align:center;height:16px;line-height:16px;width:32px;color:#fff;background:#33b5e5;}
</style>
<div class="main-wrap">
<?php foreach ($list as $k=> $v){
	require '../../lib/unit/list_news_title.php';
	}
?>
</div>