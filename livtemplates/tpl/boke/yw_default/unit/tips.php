<?php 
/* $Id: tips.php 87 2011-06-21 07:10:24Z repheal $ */
?>
<style type="text/css">
	#tipsClose{cursor:pointer;float:right;}
	.tipsbox{width:300px;height:auto;display:none;margin:0 auto;}
	.tipsbox h3{background:#E5E5E5;padding:0px 15px 5px 15px;height:23px;line-height:23px;font-size:12px;font-weight:bold}
	.tipsbox .text{padding:10px;margin:0;background:#fff;}
	.tipsbox_top{background:url(./res/img/Rounded.png) 0 -213px no-repeat;height:16px;font-size:0}
	.tipsbox_middle{padding:0 8px;background:url(./res/img/tip_bg.png) repeat-y;width:281px;}
	.tipsbox_bottom{background:url(./res/img/Rounded.png) 0 -230px no-repeat;height:16px;font-size:0}
</style>
<div id="tips" class="tipsbox" style="top:10%;left:5%;display:none;">
	<div class="tipsbox_top"></div>
	<div class="tipsbox_middle">
		<h3><span id="tipsClose">X</span>{$_lang['tips']}</h3>
		<div class="text">
		<img align="absmiddle" src="./res/img/error.gif" alt="" title="">
			<span id="tipscon"></span>
			<center>
				<input type="button" value="{$_lang['ok']}" id="tipsCloses"/>
			</center>
		</div>
	</div>
	<div class="tipsbox_bottom"></div>
</div>