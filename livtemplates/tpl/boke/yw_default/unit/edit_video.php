<?php 
/* $Id: edit_video.php 87 2011-06-21 07:10:24Z repheal $ */
?>
<style type="text/css">
	#idVideoClose{cursor:pointer;float:right;}
	.lightbox{width:392px;height:auto;display:none;margin:0 auto;width:550px;padding:10px}
	.lightbox h3{background:#E5E5E5;padding:0px 15px 5px 15px;height:23px;line-height:23px;font-size:12px;font-weight:bold}
	.lightbox .text{padding:10px;margin:0;background:#fff;width:514px}
	.lightbox .text li{margin-bottom:7px;}
	.lightbox .text li .txt{height: 21px; min-width: 220px;}
	.lightbox .text li textarea{font-size:13px;}
	.lightbox .text li .type_out{float: left;width: 400px;margin-bottom: 9px;}
	.lightbox .text li .brief{vertical-align: top;}
	.lightbox_top{background:url(./res/img/Rounded.png) 0 -319px no-repeat;height:16px;font-size:0}
	.lightbox_middle{padding:0 8px;background:url(./res/img/zp_bg.png) repeat-y;width:auto}
	.lightbox_bottom{background:url(./res/img/Rounded.png) 0 -336px no-repeat;height:16px;font-size:0}
	.box{width:700px;height:auto;background:#FFFFFF;border:5px solid #ccc;display:none; margin:0;}
	.box dt{background:#f4f4f4;padding:5px;}
	.box dd{padding:20px; margin:0;}
	.box input{width:100px;height:30px;font-size:16px;margin-left:340px;}
</style>
<div id="idVideo" class="lightbox" style="top:10%;left:5%;">
	<div class="lightbox_top"></div>
	<div class="lightbox_middle">
	<h3><span id="idVideoClose">X</span>{$_lang['edit']}</h3>
	<div class="text">
	<ul>
		<li><span>标题：</span><input id="edit_title" type="text" name="video_title" value="" class="txt"/> </li>
		<li><span>标签：</span><input id="edit_tag" type="text" name="video_tag" value="" class="txt"/> </li>
		<li><span>图片：</span>
		<form target="Upfiler_iframe" id="form1" enctype="multipart/form-data" method="post" action="my_video.php?a=update_schematic">
        	<input type="file" name="files" id="files" onchange="edit_video_image();"/><img width="120px" height="90px" id="show_img" />
        	<input type="hidden" id="video_ids" name="video_id" value=""/>
        	<input type="hidden" id="schematic" name="schematic" value=""/>
		</form>
		<iframe height="1" frameborder="0" width="1" style="display: none;" src="about:blank" name="Upfiler_iframe" id="Upfiler_iframe" ></iframe>
</li>
		<li><span>版权：</span>
			 <input type="radio"  name="edit_copyright" value="0" checked="checked" />转载
			 <input type="radio"  name="edit_copyright" value="1"/>	原创  
		</li>
		<li>
			<span style="float:left;">分类：</span>
			<div class="type_out">
			<?php
			foreach($_settings['album'] as $k => $v)
			{
			?> 
				<input id="edit_sort_{$k}" type="radio"  name="edit_sort" value="{$v['id']}" checked="{$v['checked']}" />
				<label for="edit_sort_{$k}">{$v['name']}</label>
			<?php 		
			}					
			?>
			</div>
		</li>
		
		<li class="clear">
			<span class="brief">简介：</span>
			<textarea cols="60" rows="5" id="edit_brief" name="video_brief" ></textarea>
		</li>
		<li>
			<input id="edit_id" type="hidden" name="edit_id" value=""/>
		</li>
		<li><input type="button" value="提交" onclick="edit_bt();"/></li>
	</ul>
	</div>
	</div>
	<div class="lightbox_bottom"></div>
</div>