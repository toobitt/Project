<!-- 
$icons = array(
	//左侧图标，不需要的不用传
	left => array(
		//用天气信息
		weather => array(
			icon => 'sunny',
			city => '南京',
			max => '22',
			min => '18'
		),
		//用图标
		icons => array(
			0 => 'drawer',
			1 => 'back',
			...
		)
	),
	//右侧图标，直接循环
	right => array(
		0 => 'avatar'
		...
	)
);
//	图标名
//		avatar:小人 	back:返回 	collect:收藏 
//		comment:评论 down:下载 	share:分享 
//		drawer:三条杠	delete:删除	sure:勾
 -->
<?php 
function getSrc( $flag ){
	switch ($flag){
		case 'back' : 
			echo 'javascript:history.go(-1)';
			break;
		case 'avatar':
			echo '../../tpl/member/login_personal.php';
			break;
		case 'comment' :
			echo '../../tpl/comment/comment.php';
			break;
		case 'collect' :
			echo '../../tpl/collect/collect_article.php';
			break;
		case 'drawer' : 
			echo 'javascript:toggleLeftnav()';
			break;
		case 'drawer_avatar' : 
			echo 'javascript:toggleRightnav()';
			break;
	}
}

?>
<header class="m2o-header">
	<div class="m2o-nav">
		<aside class="m2o-nav-left">
			<?php if ($icons['left']['weather']) { 
				$weather = $icons['left']['weather'];
				?>
				<a href="../../tpl/weather/weather.php" class="weather-box">
					<div class="weather-item">
						<span class="weather-icon <?php echo $weather['icon']?>"></span>
						<span class="weather-city"><?php echo $weather['city']?></span>
					</div>
					<div class="weather-item weather-temp">
						<?php echo $weather['max']?><span class="icon-du">°</span>/
						<?php echo $weather['min']?><span class="icon-du">°</span>
					</div>
				</a>
			<?php }if($icons['left']['icons']){
				
					 foreach ( $icons['left']['icons'] as $k => $v ){ ?>
						<a class="m2o-nav-icon <?php echo $v?>" href="<?php getSrc($v) ?>"></a>
					<?php }
					}?>
		</aside>
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

<script>
function toggleLeftnav(){
	$('.drawer-box').toggleClass('open-left');
}
function toggleRightnav(){
	$('.drawer-box').toggleClass('open-right');
}
</script>
