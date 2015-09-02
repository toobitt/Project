<ul class="form_ul">
	<li class="i">
	<div class="form_ul_div">
		<span  class="title">输出端口设置：</span>
		<input type="text" value="{$settings['define']['OUTPUT_PORT']}" name='define[OUTPUT_PORT]' style="width:200px;">
		<font class="important" style="color:red">不设置使用服务配置</font>
	</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">时移时间：</span>
			<input type="text" value="{$settings['base']['max_time_shift']}" name='base[max_time_shift]'style="width: 80px;">小时
			<font class="important" style="color:red">最大时移</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">直播回看时间：</span>
			<input type="text" value="{$settings['base']['live_time_shift']}" name='base[live_time_shift]'style="width: 80px;">分钟
			<font class="important" style="color:red">看直播时可以回看的时间</font>
		</div>
	</li>
	<!--
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">串联单播控实现：</span>
			<input type="checkbox" {if $settings[base][schedule_control_wowza][is_wowza]}checked=checked{/if} value="1" name='base[schedule_control_wowza][is_wowza]'>wowza
			<font class="important" style="color:red">实现方式，如果选择wowza则必须填写下方wowza配置，否则下面配置无效</font>
		</div>
	</li>
	-->
	{if $settings[base][schedule_control_wowza][is_wowza]}
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">wowza配置：</span>
			<input type="text" value="{$settings['base']['schedule_control_wowza']['host']}" name='base[schedule_control_wowza][host]' style="width: 180px;">
			<input type="text" value="{$settings['base']['schedule_control_wowza']['inputdir']}" name='base[schedule_control_wowza][inputdir]' style="width: 80px;">
			
			<font class="important" style="color:red">wowza api的主机和目录 用于串联单和播控的实现 如果留空则无法实现串联单和播控</font>
		</div>
	</li>
	{/if}
	{if $settings[base][sign_type]}
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">防盗链Token：</span>
			<input type="text" value="{$settings['define']['PRIVATE_TOKEN']}" name='define[PRIVATE_TOKEN]'style="width: 200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">防盗链有效期：</span>
			<input type="text" value="{$settings['base']['live_expire']}" name='base[live_expire]'style="width: 80px;">
			<font class="important" style="color:red">单位：秒</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">限制drm请求域：</span>
			<input type="text" value="{$settings['base']['limit_referer']}" name='base[limit_referer]'style="width: 300px;">
			<font class="important" style="color:red">用，号隔开每个域名</font>
		</div>
	</li>
	{/if}
</ul>