<li class="list-item m2o-flex m2o-flex-center <?php echo $v['type']?>">
	<span class="list-pic">
		<img src="http://instasrc.com/80x50"/>
	</span>
	<div class="info m2o-flex-one">
		<h3 class="channel-name"><?php echo $v['channelname']?></h3>
		<p><?php echo $flag?>：<span class="live-name"><?php echo $v['live']?></span></p>
	</div>
	<a class="live-flag" href="<?php echo $v['href']?>"></a>
</li>