<ul class="form_ul">
    <!--
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">图片域名设置</span>
			<input type="text" value="{$settings['define']['IMG_URL']}" name='define[IMG_URL]' style="width:200px;">
			<font class="important" style="color:red">与附件中图片域名配置一致</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">临时URL设置</span>
			<input type="text" value="{$settings['define']['TEMP_URL']}" name='define[TEMP_URL]' style="width:200px;">
			<font class="important" style="color:red">当设置此url后，内容的链接将为此链接，设置格式http://tmp.{$_settings['domain']}/?id=%s</font>
		</div>
	</li>
        -->
        <li class="i">
		<div class="form_ul_div">
			<span  class="title">打开审核功能：</span>
			<input type="text" value="{$settings['base']['is_need_audit']}" name='base[is_need_audit]'style="width: 80px;">
			<font class="important" style="color:red">当设置此参数，内容发布后需要审核才会到前台页</font>
		</div>
	</li>
</ul>