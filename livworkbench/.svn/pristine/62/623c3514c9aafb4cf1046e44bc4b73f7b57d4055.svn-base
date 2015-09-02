<?php
function getIconName( $flag ){
	switch( $flag ){
		case 'article' : 
			echo '文章';
			break;
		case 'picture' : 
			echo '图片';
			break;
	}
};
function getUrl( $flag ){
	switch( $flag ){
		case 'article' : 
			echo 'href="collect_article.php"';
			break;
		case 'picture' : 
			echo 'href="collect_picture.php"';
			break;
	}
};

function selected( $aim,$flag ){
	if( $aim == $flag )
	echo 'selected';
}
require '../../lib/head/head.php';
?>
<link rel="stylesheet" type="text/css" href="../../lib/css/subnav.css" />

<!-- 收藏 二级导航  -->
<nav class="collect-nav">
	<ul class="column">
		<?php foreach ( $foot_icon['icons'] as $k => $v ){?>
		<li class="sub-item <?php selected($foot_icon['selected'],$v)?>"><a class="use-base-color" <?php getUrl($v)?>><?php getIconName($v)?>收藏</a></li>
		<?php }?>
	</ul>
</nav>
<!-- 二级导航 end -->