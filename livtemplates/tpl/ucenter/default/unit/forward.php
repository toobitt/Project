<?php 
/* $Id: forward.php 396 2011-07-28 00:52:08Z zhoujiafei $ */
?>
<div id="idBox" class="lightbox" style="top:10%;left:5%;display:none;">
	<div class="lightbox_top"></div>
	<div class="lightbox_middle">
	<h3><span id="idBoxClose">X</span>{$_lang['forward_mine']}</h3>
	<div class="text">
	<textarea onkeydown='countCharF();' onkeyup='countCharF();' name="statusF" id="statusF" rows="8" cols="50">
</textarea>		
		<input type="hidden" value="" id="temporary" />
	<div class="countsF">
	<div class="face" id="facelist"><img alt="" src="<?php echo RESOURCE_DIR;?>img/smiles/17.gif"><a href="javascript:void(0);">表情</a></div>
	您还可以输入<span id="counterF" class="counterF">140</span>字<input type="button" value="" id="ModForward" /></div>
	<div id="faceF"  class="face_content" style="left: 70px;top: 160px;position: absolute; display: none; visibility: visible;"></div>
	</div>
	<div class="rounded-bottom clear"><span id="title"></span><img id="avatar" src="" align="middle"/></div>
	</div>
	<div class="lightbox_bottom"></div>
	<input id="source" value="点滴" type="hidden"/>
</div>