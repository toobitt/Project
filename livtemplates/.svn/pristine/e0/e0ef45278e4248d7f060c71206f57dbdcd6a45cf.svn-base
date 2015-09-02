<ul class="form_ul" style="margin-bottom:50px;">
	<li class="i">
		<div class="form_ul_div">
			<span class="title">&nbsp;&nbsp;&nbsp;域名：</span>
			<input type="text" value="{$settings['define']['LOTTERY_DOMAIN']}" name='define[LOTTERY_DOMAIN]' style="width:240px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">城市名称：</span>
			<input type="text" value="{$settings['base']['areaname']}" name='base[areaname]' style="width:80px;">
			<font class="important" style="color:red">地图默认城市名称</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">清除时间：</span>
			<input type="text" value="{$settings['define']['CLEAR_WIN_INFO_TIME']}" name='define[CLEAR_WIN_INFO_TIME]' style="width:80px;">天
			<font class="important" style="color:red">清除未确认记录</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">清除未中奖：</span>
			<span  class="title">开：</span>
			<input type="radio" value="1" name='base[clear_un_win_info]' {if $settings['base']['clear_un_win_info']}checked="checked"{/if}>
			<span  class="title">关：</span>
			<input type="radio" value="0" name='base[clear_un_win_info]' {if !$settings['base']['clear_un_win_info']}checked="checked"{/if}>
			<font class="important" style="color:red">开启后,如果设置了清除时间,计划任务会清除未确认和未中奖记录,关闭只清除未确认记录</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">中奖列表：</span>
			<input type="text" value="{$settings['base']['winlist']}" name='base[winlist]' style="width:80px;">
			<font class="important" style="color:red">显示我的中奖列表,1为显示,0为不显示</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">中奖列表地址：</span>
			<input type="text" value="{$settings['base']['winlist_url']}" name='base[winlist_url]' style="width:200px;">
			<font class="important" style="color:red">我的中奖列表跳转地址</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">兑换信息地址：</span>
			<input type="text" value="{$settings['base']['exchange_url']}" name='base[exchange_url]' style="width:200px;">
			<font class="important" style="color:red">扫描二维码跳转地址</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">未开始提示：</span>
			<input type="text" value="{$settings['base']['notstartdesc']}" name='base[notstartdesc]' style="width:200px;">
			<font class="important" style="color:red">抽奖活动未开始提示</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">结束提示：</span>
			<input type="text" value="{$settings['base']['finish_desc']}" name='base[finish_desc]' style="width:200px;">
			<font class="important" style="color:red">抽奖活动结束提示</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">限制中奖：</span>
			<input type="text" value="{$settings['base']['lottery_limit_tip']}" name='base[lottery_limit_tip]' style="width:200px;">
			<font class="important" style="color:red">限制中奖提示</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">抽奖筛选：</span>
			<span  class="title">开：</span>
			<input type="radio" value="1" name='base[lottery_filter]' {if $settings['base']['lottery_filter']}checked="checked"{/if}>
			<span  class="title">关：</span>
			<input type="radio" value="0" name='base[lottery_filter]' {if !$settings['base']['lottery_filter']}checked="checked"{/if}>
			<font class="important" style="color:red">开启后计划任务筛选符合条件抽奖活动缓存</font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">中奖列表：</span>
			<span  class="title">开：</span>
			<input type="radio" value="1" name='base[lottery_win_info]' {if $settings['base']['lottery_win_info']}checked="checked"{/if}>
			<span  class="title">关：</span>
			<input type="radio" value="0" name='base[lottery_win_info]' {if !$settings['base']['lottery_win_info']}checked="checked"{/if}>
			<font class="important" style="color:red">开启后中奖列表只显示当前活动中奖信息</font>
		</div>
	</li>
</ul>