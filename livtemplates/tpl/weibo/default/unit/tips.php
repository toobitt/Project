<?php 
/* $Id: tips.php 572 2011-09-02 02:18:14Z develop_tong $ */
?>
<div id="tips" class="tipsbox" style="top:10%;left:5%;display:none;">
	<div class="tipsbox_top"></div>
	<div class="tipsbox_middle">
		<h3><span id="tipsClose">X</span>{$_lang['tips']}</h3>
		<div class="text">
		<img align="absmiddle" src="./res/img/error.gif" alt="" title="">
			<span id="tipscon"></span>
			<center>
				<input type="button" value="{$_lang['success']}" id="tipsCloses"/>
			</center>
		</div>
	</div>
	<div class="tipsbox_bottom"></div>
</div>