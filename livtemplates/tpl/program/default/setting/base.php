
<ul class="form_ul">
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
	{/if}
<li></li>
</ul>