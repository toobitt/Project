<?php 
/* $Id: index.tpl.php 1639 2011-01-08 10:05:09Z chengqing $ */
?>
<div id="Box" class="lightbox" style="top:10%;left:5%;display:none;">
	<div class="lightbox_top"></div>
	<div class="lightbox_middle">
		<h3><span id="BoxClose">X</span><?php echo $this->lang['status_title']?></h3>
		<div class="text">
			<textarea onkeydown='countChar();' onkeyup='countChar();' name="status" id="status" rows="8" cols="50">
		</textarea>		
			<div class="countsS">您还可以输入<span id="counter" class="counterS">140</span>字<input type="button" value="" id="Released" onclick="pubUserStatus();" class="<?php echo $this->lang['inputclass'];?>" /></div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="lightbox_bottom"></div>
</div>
