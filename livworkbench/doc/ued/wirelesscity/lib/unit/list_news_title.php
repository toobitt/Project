<a href="<?php echo $v['href']?>">
<div class="list-item m2o-flex m2o-flex-center <?php echo $v['type']?>">
	<div class="info m2o-flex-one">
		<h3 class="title"><?php echo $v['title']?>
		<?php if( $v['type']=='video' ){?>
			<span class="flag">视频</span>
		<?php }?>
		</h3>
	</div>
	<span class="list-pic">
		<img src="http://instasrc.com/80x50">
	</span>
</div>
</a>