<?php 
/* $Id: status_pub.php 388 2011-07-26 05:32:48Z lijiaying $ */
?>
<div id="Box" class="lightbox" style="top:10%;left:5%;display:none;">
	<div class="lightbox_top"></div>
	<div class="lightbox_middle">
		<h3><span id="BoxClose">X</span>{$_lang['status_title']}</h3>
		<div class="text">
			<textarea onkeydown='countChar();' onkeyup='countChar();' name="status" id="status" rows="8" cols="50"></textarea>		
			<div class="countsS">
			<div class="face" id="facelistS"><img alt="" src="{code} echo RESOURCE_DIR;{/code}img/smiles/17.gif"><a href="javascript:void(0);">表情</a></div>
			您还可以输入<span id="counter" class="counterS">140</span>字<input type="button" value="" id="Released" onclick="pubUserStatus();" class="{$_lang['inputclass']}" />
				<div id="faceS" class="face_content" style="text-align: left;left: 70px;top: 160px;position: absolute; display: none; visibility: visible;"></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="lightbox_bottom"></div>
</div>
