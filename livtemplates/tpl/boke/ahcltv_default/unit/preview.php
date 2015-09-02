<?php 
/* $Id: preview.php 87 2011-06-21 07:10:24Z repheal $ */
?>

<style type="text/css">
	#VideoClose{cursor:pointer;float:right;}
	.videobox{width:392px;height:auto;display:none;margin:0 auto;width:550px;padding:10px}
	.videobox h3{background:#E5E5E5;padding:0px 15px 5px 15px;height:23px;line-height:23px;font-size:12px;font-weight:bold;text-align: left;}
	.videobox .text{padding:10px;margin:0;background:#fff;width:514px}
	.videobox .text li{margin-bottom:7px;}
	.videobox_top{background:url(./res/img/Rounded.png) 0 -319px no-repeat;height:16px;font-size:0}
	.videobox_middle{padding:0 8px;background:url(./res/img/zp_bg.png) repeat-y;width:auto;text-align:center;}
	.videobox_bottom{background:url(./res/img/Rounded.png) 0 -336px no-repeat;height:16px;font-size:0}

</style>

<div id="previewVideo" class="videobox" style="top:10%;left:5%;">
	<div class="videobox_top"></div>
	<div class="videobox_middle">
		<h3><span id="VideoClose">X</span>{$_lang['preview']}</h3>
		
		<div id="tvie_flash_players"> </div>
		
	</div>
	<div class="videobox_bottom"></div>
</div>


