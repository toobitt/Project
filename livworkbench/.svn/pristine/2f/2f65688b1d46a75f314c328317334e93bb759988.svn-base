<?php
require '../../lib/head/head.php';
$title = '公交';
$icons = array(
	left => array(
		0 => 'modules'
	),
);
require '../../lib/unit/nav.php';
$subnav = array(
	0 => array(
		name => '附近',
		url => '../../tpl/bus/geolocation_failed.php'
	),
	1 => array(
		name => '线路',
		url => '../../tpl/bus/route.php'
	),
	2 => array(
		name => '站点',
		url => '../../tpl/bus/station.php'
	),
	3 => array(
		name => '换乘'
	),
	4 => array(
		name => '收藏',
		url => '../../tpl/bus/collect_list.php'
	),
);
require '../../lib/unit/subnav.php';
?>
<link rel="stylesheet" href="../../lib/css/bus.css" type="text/css" />
<style>
.common-input-box{border:none;}
</style>
<div class="bus-inner">
	<div class="search-area">
		<div class="common-input-box">
			<div class="input-item m2o-flex m2o-flex-center">
				<span class="icon icon-route"></span>
				<input class="input m2o-flex-one" placeholder="输入线路名" value=""/>
				<span class="handle-btn clear"></span>
			</div>
			<div class="fuzzy-matching">
				<ul>
					<li _val="1">1路<a class="more"></a></li>
					<li _val="10">10路<a class="more"></a></li>
					<li _val="11">11路<a class="more"></a></li>
					<li _val="15">15路<a class="more"></a></li>
					<li _val="宜家">宜家<a class="more"></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="vertical-btns">
		<a class="common-btn">查询</a>
	</div>
	<div class="query-area">
		<span class="title">最近查询</span>
		<a class="clear-btn"></a>
		<div class="recent-query">
			<span>11</span>
			<span>211</span>
			<span>111</span>
		</div>
	</div>
</div>
<script>
$(function(){
	var input = document.querySelector('input');
	input.addEventListener('keyup',function(){
		$('.css').remove();
		var css = '.fuzzy-matching li[_val^="'+ $('input').val() +'"]{display:block;}';
		$('<style class="css"></style>').text(css).appendTo('body');
	});
});
</script>