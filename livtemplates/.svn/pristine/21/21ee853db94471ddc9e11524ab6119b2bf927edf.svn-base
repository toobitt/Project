<ul class="form_ul" style="margin-bottom:50px;">
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">地图坐标转换：</span>
			<input type="text" value="{$settings['define']['BAIDU_CONVERT_DOMAIN']}" name='define[BAIDU_CONVERT_DOMAIN]' style="width:300px;">
			<font class="important" style="color:red">百度地图坐标转换地址</font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">地图默认地点：</span>
			<input type="text" value="{$settings['base']['areaname']}" name='base[areaname]' style="width:100px;">
			<font class="important" style="color:red">百度地图默认地点</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">安卓appid：</span>
			<input type="text" value="{$settings['base']['android_appid']}" name='base[android_appid]' style="width:100px;">
			<font class="important" style="color:red">标识安卓，获取附近公交时坐标转换</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">备份数据库名：</span>
			<input type="text" value="{$settings['base']['database_no_use']}" name='base[database_no_use]' style="width:100px;">
			<font class="important" style="color:red">公交备份数据库名</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">延时时间：</span>
			<input type="text" value="{$settings['base']['delay_time']}" name='base[delay_time]' style="width:100px;">
			<font class="important" style="color:red">实时公交延时时间，单位秒</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">实时公交表：</span>
			<span  class="title">是：</span>
			<input type="radio" value="1" name='base[bus_tab]' {if $settings['base']['bus_tab']}checked="checked"{/if}>
			<span  class="title">否：</span>
			<input type="radio" value="0" name='base[bus_tab]' {if !$settings['base']['bus_tab']}checked="checked"{/if}>
			<font class="important" style="color:red">为是时，实时公交信息从bus表中查询；为否查询实时公交接口</font>
		</div>
	</li>
</ul>