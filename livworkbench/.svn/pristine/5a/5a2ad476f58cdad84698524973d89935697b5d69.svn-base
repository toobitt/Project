<?php
$title = $_GET['address'];
$name = $_GET['name'];
function getIconName( $flag ){
	switch( $flag ){
		case 'general' : 
			echo '概括';
			break;
		case 'notice' : 
			echo '公告';
			break;
	}
};
function getUrl( $flag){
	Global $title, $name;
	switch( $flag ){
		case 'general' : 
			echo 'website_general.php?address='.$title.'&name='.$name;
			break;
		case 'notice' : 
			echo 'website_noticelist.php?address='.$title.'&name='.$name;
			break;
	}
};
function selected( $aim,$flag ){
	if( $aim == $flag )
	echo 'selected';
}
?>
<nav class="subnav m2o-flex">
	<?php foreach ( $bike_icon['icons'] as $k => $v ){?>
	<a class="item m2o-flex-one <?php selected($bike_icon['selected'],$v)?>" href="<?php getUrl($v)?>"><?php getIconName($v)?></a>
	<?php }?>
</nav>