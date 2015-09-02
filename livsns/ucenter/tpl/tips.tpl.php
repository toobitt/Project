<?php 
/* $Id: tips.tpl.php 1734 2011-01-13 05:58:57Z repheal $ */
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