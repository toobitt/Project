<?php 
$title = '风格列表';
require 'lib/head/head.php';
require 'lib/unit/nav.php';

$list = array(
	0 => array(
		title => '选项卡风格',
		url => 'tpl/tab'
	),
	1 => array(
		title => '抽屉式风格(iconleft)',
		url => 'tpl/drawer_iconleft'
	),
	2 => array(
		title => '抽屉式风格(icontop)',
		url => 'tpl/drawer_icontop'
	),
	3 => array(
		title => '抽屉式风格3',
		url => 'tpl/drawer_block'
	),
	4 => array(
		title => 'metro风格',
		url => 'tpl/magazine'
	)
);
?>
<style>
.index-list{border-top:1px solid #d9d9d9;}
.index-list li{height:3em;line-height:3em;text-align:center;border:1px solid #d9d9d9;border-top:0;font-size:1.4em;cursor:pointer;}
.index-list li a{color:#000;}
</style>
<link rel="stylesheet" href="lib/css/reset.css" type="text/css" />
<link rel="stylesheet" href="lib/css/common.css" type="text/css" />
<div class="main-wrap">
	<ul class="index-list">
		<?php foreach( $list as $k => $v ){?>
		<li><a href="<?php echo $v['url']?>"><?php echo $v['title']?></a></li>
		<?php }?>
	</ul>
</div>
</body>
</html>