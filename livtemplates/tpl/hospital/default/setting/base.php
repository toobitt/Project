<ul class="form_ul" style="margin-bottom:50px;">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">医院token：</span>
			<input type="text" value="{$settings['base']['hospital_token']}" name='base[hospital_token]' style="width:300px;">
			<font class="important" style="color:red">请求医院接口token</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">医院接口url：</span>
			<input type="text" value="{$settings['base']['hospital_url']}" name='base[hospital_url]' style="width:300px;">
			<!-- <font class="important" style="color:red">医院数据接口地址</font> -->
			
			<span  class="title">开：</span>
			<input type="radio" value="1" name='base[hospital_switch]' {if $settings['base']['hospital_switch']}checked="checked"{/if}>
			<span  class="title">关：</span>
			<input type="radio" value="0" name='base[hospital_switch]' {if !$settings['base']['hospital_switch']}checked="checked"{/if}>
			<font class="important" style="color:red">医院数据更新或者初始化开关</font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">科室接口url：</span>
			<input type="text" value="{$settings['base']['departments_url']}" name='base[departments_url]' style="width:300px;">
			<!-- <font class="important" style="color:red">科室数据接口地址</font> -->
			
			<span  class="title">开：</span>
			<input type="radio" value="1" name='base[departments_switch]' {if $settings['base']['departments_switch']}checked="checked"{/if}>
			<span  class="title">关：</span>
			<input type="radio" value="0" name='base[departments_switch]' {if !$settings['base']['departments_switch']}checked="checked"{/if}>
			<font class="important" style="color:red">科室数据更新或者初始化开关</font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">医生接口url：</span>
			<input type="text" value="{$settings['base']['doctor_url']}" name='base[doctor_url]' style="width:300px;">
			<!-- <font class="important" style="color:red">医生数据接口地址</font> -->
			
			<span  class="title">开：</span>
			<input type="radio" value="1" name='base[doctor_switch]' {if $settings['base']['doctor_switch']}checked="checked"{/if}>
			<span  class="title">关：</span>
			<input type="radio" value="0" name='base[doctor_switch]' {if !$settings['base']['doctor_switch']}checked="checked"{/if}>
			<font class="important" style="color:red">医生数据更新或者初始化开关</font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">预约号接口url：</span>
			<input type="text" value="{$settings['base']['schedules_url']}" name='base[schedules_url]' style="width:300px;">
			
			<span  class="title">开：</span>
			<input type="radio" value="1" name='base[schedules_switch]' {if $settings['base']['schedules_switch']}checked="checked"{/if}>
			<span  class="title">关：</span>
			<input type="radio" value="0" name='base[schedules_switch]' {if !$settings['base']['schedules_switch']}checked="checked"{/if}>
			<font class="important" style="color:red">预约号数据更新或者初始化开关</font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">预约接口url：</span>
			<input type="text" value="{$settings['base']['yuyue_url']}" name='base[yuyue_url]' style="width:300px;">
			<font class="important" style="color:red">预约接口地址</font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">取消预约接口：</span>
			<input type="text" value="{$settings['base']['cancel_yuyue']}" name='base[cancel_yuyue]' style="width:300px;">
			<font class="important" style="color:red">取消预约接口地址</font>
		</div>
	</li>
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
</ul>