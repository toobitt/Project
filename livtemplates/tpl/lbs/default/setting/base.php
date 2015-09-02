<style type="text/css">
.form_ul_div input{vertical-align:middle;}
</style>
<ul class="form_ul" style="margin-bottom:50px;text-align:left;" >
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">默认城市：</span>
			<input type="text" value="{$settings['base']['areaname']}" name='base[areaname]' style="width:200px;">
			<font class="important" style="color:red">地图默认显示城市</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">缓存时间：</span>
			<input type="text" value="{$settings['base']['cache_time']}" name='base[cache_time]' style="width:200px;">分钟
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">偏移count：</span>
			<input type="text" value="{$settings['base']['data_count']}" name='base[data_count]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">百度地图密钥：</span>
			<input type="text" value="{$settings['define']['BAIDU_AK']}" name='define[BAIDU_AK]' style="width:300px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">自行车分类：</span>
			<input type="text" value="{$settings['base']['bicycle_sort_id']}" name='base[bicycle_sort_id]' style="width:60px;">
			<font class="important" style="color:red">自行车分类id</font>
		</div>
	</li>
	 <!-- 
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">坐标转换地址：</span>
			<input type="text" value="{$settings['define']['BAIDU_CONVERT_DOMAIN']}" name='define[BAIDU_CONVERT_DOMAIN]' style="width:400px;">
			<font class="important" style="color:red">百度地图坐标转换地址</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">坐标转化地址：</span>
			<input type="text" value="{$settings['define']['BAIDU_GEOCODER_DOMAIN']}" name='define[BAIDU_GEOCODER_DOMAIN]' style="width:400px;">
			<font class="important" style="color:red">百度地图坐标获取对应地址接口地址</font>
		</div>
	</li>
	 -->
</ul>