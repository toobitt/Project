<?php 
/* $Id: list.php 18206 2013-03-20 02:07:46Z yizhongyue $ */
?>
<ul class="form_ul">
	<li class="i">
		<div class = "form_ul_div">
			<span class="title">素材访问域名：</span>
			<input type="text" value="{$settings['define']['DATA_URL']}" name='define[DATA_URL]' style="width:200px;">
			<font class="important" style="color:red">域名需指向data目录</font>		
		</div>
	</li>
        <li class="i">
		<div class = "form_ul_div">
			<span class="title">显示“阅读全文”链接：</span>
			<input type="text" value="{$settings['base']['need_show_all_pages']}" name='base[need_show_all_pages]' style="width:200px;">
			<font class="important" style="color:red">内容正文页是否需要“阅读全文”链接</font>		
		</div>
	</li>
</ul>