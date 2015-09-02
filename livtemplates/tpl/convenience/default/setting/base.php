<ul class="form_ul" style="margin-bottom:50px;">
		<li class="i">
		<div class="form_ul_div">
			<span  class="title">客运接口开关：</span>
			<label><input type="radio" name="define[BUS_ON_OFF]" value="1"{if $settings['define']['BUS_ON_OFF'] == 1} checked="checked"{/if} />开启</label><label><input type="radio" name="define[BUS_ON_OFF]" value="0"{if $settings['define']['BUS_ON_OFF'] == 0} checked="checked"{/if} />关闭</label>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">船期接口开关：</span>
			<label><input type="radio" name="define[SHIP_ON_OFF]" value="1"{if $settings['define']['SHIP_ON_OFF'] == 1} checked="checked"{/if} />开启</label><label><input type="radio" name="define[SHIP_ON_OFF]" value="0"{if $settings['define']['SHIP_ON_OFF'] == 0} checked="checked"{/if} />关闭</label>
		</div>
	</li>
		<li class="i">
		<div class="form_ul_div">
			<span  class="title">最大查询天数：</span>
			<input type="text" value="{$settings['define']['MAX_SEARCH_TIME_RANGE']}" name='define[MAX_SEARCH_TIME_RANGE]' style="width:25px;">天(客运)
			<font class="important" style="color:red">最大查询时间范围,空值或者为0代表只可以查询当天数据.</font>
		</div>
	</li>
	</li>
		<li class="i">
		<div class="form_ul_div">
			<span  class="title">最大查询天数：</span>
			<input type="text" value="{$settings['define']['MAX_SEARCH_TIME_RANGE_SHIP']}" name='define[MAX_SEARCH_TIME_RANGE_SHIP]' style="width:25px;">天(船期)
			<font class="important" style="color:red">最大查询时间范围,空值或者为0代表只可以查询当天数据.</font>
		</div>
	</li>
	
</ul>