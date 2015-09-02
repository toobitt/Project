<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">验证码过期时间：</span>
			<input type="number" min="0" value="{$settings['base']['verify_code_valid']}" 	name='base[verify_code_valid]' 	style="width:40px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">背景图片宽：</span>
			<input type="number" min="0" max="255" value="{$settings['base']['width']}" 	name='base[width]' 	style="width:40px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">背景图片高：</span>
			<input type="number" min="0" max="60" value="{$settings['base']['height']}" 	name='base[height]' 	style="width:40px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>