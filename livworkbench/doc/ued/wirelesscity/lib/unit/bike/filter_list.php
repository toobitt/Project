<li class="area-item m2o-flex m2o-flex-end">
	<div class="info m2o-flex-one">
		<h3 class="name"><a href="<?php echo $v['href']?>?address=<?php echo $v['areaname']?>"><?php echo $v['areaname']?></a></h3>
		<div class="address"><span><?php echo $v['address']?></span><?php echo $flag?></div>
	</div>
	<div class="detail">
		<div class="num">共<?php echo $v['websitenum']?>个站点</div>
		<div class="distance"><?php echo $v['distance']?></div>
	</div>
</li>