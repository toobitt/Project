<li class="list-item m2o-flex m2o-flex-center <?php if( $v['canplay'] ){ ?>canplay<?php } ?> <?php if( $v['current'] ){?>current<?php }else if($v['live']){?>live<?php }?>"  >
	<div class="live-time">
		<?php if( $v['current'] ){?>
		正在播放
		<?php }else if($v['live']){?>
		直播中
		<?php }else{?>
		<?php echo $v['time']?>
		<?php }?>
	</div>
	<div class="m2o-flex-one live-name"><?php echo $v['live_name']?></div>
	<?php if( $v['canplay'] ){?>
	<div class="live-flag"></div>
	<?php }?>
</li>