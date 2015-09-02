<ul class="form_ul" style="margin-bottom:50px;">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">默认城市：</span>
			<input type="text" value="{$settings['define']['CITY_NAME']}" name='define[CITY_NAME]' style="width:200px;">
			<font class="important" style="color:red">添加运营单位中地图默认显示城市</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">百度密钥：</span>
			<input type="text" value="{$settings['define']['BAIDU_AK']}" name='define[BAIDU_AK]' style="width:300px;">
			<font class="important" style="color:red">转换坐标时使用</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">谷歌转百度地址：</span>
			<input type="text" value="{$settings['define']['BAIDU_CONVERT_DOMAIN_GOOGLE_TO_BAIDU']}" name='define[BAIDU_CONVERT_DOMAIN_GOOGLE_TO_BAIDU]' style="width:350px;">
			<font class="important" style="color:red">from=3to=5谷歌转百度</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">GPS转百度地址：</span>
			<input type="text" value="{$settings['define']['BAIDU_CONVERT_DOMAIN_GPS_TO_BAIDU']}" name='define[BAIDU_CONVERT_DOMAIN_GPS_TO_BAIDU]' style="width:350px;">
			<font class="important" style="color:red">from=2to=5gps转百度</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">百度坐标转地址：</span>
			<input type="text" value="{$settings['define']['BAIDU_GEOCODER_DOMAIN']}" name='define[BAIDU_GEOCODER_DOMAIN]' style="width:350px;">
			<font class="important" style="color:red">坐标转地址</font>
		</div>
	</li>
</ul>