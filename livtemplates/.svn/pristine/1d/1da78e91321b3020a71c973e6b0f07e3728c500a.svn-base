<script type="text/javascript">
	function mibaoCallback(img)
	{
		if(img)
		{
			$('#download_all').show();
			var url = "settings.php?a=download_card&img="+img+"&app_uniqueid=auth";
			window.location.href=url;
		}
	}

	function download_all_mibao()
	{
		var url = "settings.php?a=download_all_mibao&app_uniqueid=auth";
		window.location.href=url;
	}
</script>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div" id="info_box">
			<span  class="title">是否开启密保：</span>
			<input type="checkbox" value="1"  name='base[mibao][open]' {if $settings['base']['mibao']['open']} checked {/if} />
			<input class="setting_button" type="button" value="打包下载所有密保卡,并为未绑定密保的用户绑定密保" style="width:328px;display:none;" id="download_all" onclick="download_all_mibao();" />
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">Token周期：</span>
			<input type="text" value="{$settings['define']['TOKEN_EXPIRED']}" name='define[TOKEN_EXPIRED]' style="width:200px;">
			<font class="important" style="color:red">建议周期设置不要超过一个星期，默认一天，单位秒</font>
		</div>
	</li>
</ul>