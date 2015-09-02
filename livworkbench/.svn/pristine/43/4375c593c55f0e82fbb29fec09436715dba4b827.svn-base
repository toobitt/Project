<link rel="stylesheet" type="text/css" href="../../lib/css/foot.css" />
<!-- 选项卡式 -->
<!--
$foot_icon = array(
	selected = 'home', //被选中的 icon
	icons => array(
		0 => 'home',
		1 => 'more',
		2 => 'pic',
		3 => 'topics',	//专题
		4 => 'video'
	)
);
-->
<?php
function getIconName( $flag ){
	switch( $flag ){
		case 'home' : 
			echo '首页';
			break;
		case 'more' : 
			echo '更多';
			break;
		case 'pic' : 
			echo '图集';
			break;
		case 'topics' : 
			echo '专题';
			break;
		case 'video' : 
			echo '视频';
			break;
	}
};
function getUrl( $flag ){
	switch( $flag ){
		case 'home' : 
			echo 'href="index.php"';
			break;
		case 'more' : 
			echo 'href="more.php"';
			break;
		case 'pic' : 
			echo 'href="tuji.php"';
			break;
		case 'topics' : 
			echo 'href="special.php"';
			break;
		case 'video' : 
			echo '视频';
			break;
	}
};

function selected( $aim,$flag ){
	if( $aim == $flag )
	echo 'selected';
}

?>
<script src="../../lib/js/tab_foot.js" type="text/javascript"></script>

<footer class="m2o-tab-foot m2o-flex">
	<?php foreach ( $foot_icon['icons'] as $k => $v ){?>
	<div class="m2o-flex-one item">
		<a class="foot-icon <?php echo $v?> <?php selected($foot_icon['selected'],$v)?>" <?php getUrl($v)?>><?php getIconName($v)?></a>
	</div>	
	<?php }?>
</footer>
