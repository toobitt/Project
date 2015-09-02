<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">JPUSH_APP_KEY：</span>
			<input type="text" value="{$settings['define']['JPUSH_APP_KEY']}" name='define[JPUSH_APP_KEY]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>

	<li class="i">
		<div class="form_ul_div">
			<span  class="title">MASTER_SECRET：</span>
			<input type="text" value="{$settings['define']['MASTER_SECRET']}" name='define[MASTER_SECRET]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>

	<li class="i">
		<div class="form_ul_div">
			<span  class="title">附近的范围：</span>
			<input type="text" value="{$settings['define']['MEETING_DISTANCE']}" name='define[MEETING_DISTANCE]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">签到开始时间：</span>
			<input type="text" value="{$settings['define']['SIGN_STIME']}" name='define[SIGN_STIME]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">签到结束时间：</span>
			<input type="text" value="{$settings['define']['SIGN_ETIME']}" name='define[SIGN_ETIME]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>

	<li class="i">
		<div class="form_ul_div">
			<span  class="title">直播时间：</span>
			<input type="text" value="{$settings['define']['LIVE_STIME']}" name='define[LIVE_STIME]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">是否验证GPS：</span>
			<input type="text" value="{$settings['define']['IS_VERIFY_GPS']}" name='define[IS_VERIFY_GPS]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">是否是发布版本：</span>
			<input type="text" value="{$settings['define']['IS_APP_PUBLISHED']}" name='define[IS_APP_PUBLISHED]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">ios推送证书：</span>
			<input type="text" value="{$settings['define']['IOS_PEMS']}" name='define[IOS_PEMS]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">直播流：</span>
			<input type="text" value="{$settings['base']['live_stream']['stream_url']}" 	name='base[live_stream][stream_url]' style="width:400px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>

	<li class="i">
		<div class="form_ul_div">
			<span  class="title">地点的经纬度：</span>
			经度：
			<input type="text" value="{$settings['base']['meeting_pos']['x']}" 	name='base[meeting_pos][x]' style="width:200px;">
			纬度：
			<input type="text" value="{$settings['base']['meeting_pos']['y']}" 	name='base[meeting_pos][y]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>

</ul>