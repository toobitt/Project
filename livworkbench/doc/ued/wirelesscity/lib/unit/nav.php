<?php 
function getSrc( $flag ){
	switch ($flag){
		case 'back' : 
			echo 'javascript:history.go(-1)';
			break;
		case 'comment' : 
			echo '../../tpl/news/comment.php';
			break;
	}
}
?>
<header class="m2o-header">
	<div class="m2o-nav">
		<?php if ( $icons['left'] ) {?>
		<aside class="m2o-nav-left">
			<?php foreach ( $icons['left'] as $k => $v ){?>
				<a class="m2o-nav-icon <?php echo $v?>"  href="<?php getSrc($v) ?>"></a>
			<?php }?>
		</aside>
		<?php }?>
		<?php if ( $icons['right'] ) {?>
		<aside class="m2o-nav-right">
			<?php foreach ( $icons['right'] as $k => $v ){?>
				<a class="m2o-nav-icon <?php echo $v?>"  href="<?php getSrc($v) ?>"></a>
			<?php }?>
		</aside>
		<?php }?>
		<h1 class="m2o-nav-title"><?php echo $title ?></h1>
	</div>
</header>
