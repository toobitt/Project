<?php 
/* $Id: status_pub.php 5351 2011-12-06 05:49:24Z lixuguang $ */
?>
<div id="Box" class="lightbox" style="top:10%;left:5%;display:none;">
	<div class="lightbox_top"></div>
	<div class="lightbox_middle">
		<h3><span id="BoxClose">X</span>{$_lang['status_title']}</h3>
		<div class="text">
			<textarea onkeydown='countChar();' onkeyup='countChar();' name="status" id="status" rows="5" cols="55"></textarea>		
			<div class="countsS">
			<div class="face" id="facelist"><img alt="" src="<?php echo RESOURCE_DIR;?>img/smiles/17.gif"><a href="javascript:void(0);">表情</a></div>
			您还可以输入<span id="counter" class="counterS">140</span>字<input type="button" value="" id="Released" onclick="pubUserStatus();" class="{$_lang['inputclass']}" />
				<div id="faceF" class="face_content" style="margin-left: 20px; margin-top: -15px;position: absolute; display: none; visibility: visible;"></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="lightbox_bottom"></div>
</div>
