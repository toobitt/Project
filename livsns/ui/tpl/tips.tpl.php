<?php 
/* $Id: tips.tpl.php 3518 2011-04-10 20:22:24Z develop_tong $ */
?>
<div id="tips" class="tipsbox" style="top:10%;left:5%;display:none;">
	<div class="tipsbox_top"></div>
	<div class="tipsbox_middle">
		<h3><span id="tipsClose">X</span><?php echo $this->lang['tips']?></h3>
		<div class="text">
		<img align="absmiddle" src="./res/img/error.gif" alt="" title="">
			<span id="tipscon"></span>
			<center>
				<input type="button" value="<?php echo $this->lang['success']?>" id="tipsCloses"/>
			</center>
		</div>
	</div>
	<div class="tipsbox_bottom"></div>
</div>
<div id="Mymap" style="display:none;position:absolute;top:10%;left:10%;"><div style="position:relative;width:560px;"><span style="display:block;position:absolute;right:36px;top:10px;cursor:pointer;"><img width="14" height="14" id="mapClose" src="http://www.hoolo.tv/res/cspd/images/close.gif"></span><iframe src="http://www.hoolo.tv/topic/smallmap/" style="width:560px;height:430px"></iframe></div></div>