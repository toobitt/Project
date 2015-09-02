<?php
$title = $_GET['address'];
function getIconName( $flag ){
	switch( $flag ){
		case 'nearest' : 
			echo '离我最近';
			break;
		case 'filter' : 
			echo '区域筛选';
			break;
		case 'website' : 
			echo '站点公告';
			break;
	}
};
function getUrl( $flag){
	Global $title;
	switch( $flag ){
		case 'nearest' : 
			if( $title ){
				echo 'area_nearest.php?address='.$title;
			}else{
				echo 'nearest_list.php';
			}
			break;
		case 'filter' : 
			echo 'filter_list.php';
			break;
		case 'website' : 
			if( $title ){
				echo 'area_website.php?address='.$title;
			}else{
				echo 'website_list.php';
			}
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