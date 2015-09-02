<li class="day_default">
	<div class="single_item" style="background:{$pcolor};border-left-color: {$bcolor};border-left-width: 2px;">
		<span name="box[]" class="box" style="cursor:default;margin-left: -5px;{if $v['dvr_delete']}background: #DF6564;{/if}"></span>
		
		{code}
			$type_source = array(
				'other'=>' size="14" autocomplete="off" style="width:80px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;position: relative;top: -23px;" onchange="hg_time_edit(this,1);"',
				'name'=>'start_time[]',
				'style'=>'width:80px;float: left;','type'=>'HH:mm:ss',
				'other_focus' => ''
			);
			$default_start = $v['start'];
		{/code}
		{template:form/wdatePicker,'',$default_start,'',$type_source}
		<div class="input text">
			<span class="input_left"></span>
			<span class="input_right"></span>
			<span class="input_middle">
				<input type="text" name="theme[]" value="{$v['theme']}" onchange="hg_theme_edit(this);" />
			</span>
		</div>

		{code}
			$type_source = array(
				'other'=>' size="14" autocomplete="off" style="width:80px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;position: relative;top: -23px;" onchange="hg_time_edit(this,0);"',
				'name'=>'end_time[]',
				'style'=>'width:80px;float: left;','type'=>'HH:mm:ss',
				'other_focus' => ''
			);
			$default_end = $v['end'];
		{/code}
		{template:form/wdatePicker,'',$default_end,'',$type_source}
		<span class="focus_move" onmouseover="hg_show_delete(this,true);" onmouseout="hg_show_delete(this,false);">
			<span title="删除屏蔽节目" name="delete_buttom[]" class="down_del" onclick="hg_delete(this,{$v['id']});" style="display:none;"></span>
			{if !$v['dvr_delete']}
			<span title="删除时移" name="dvr_delete[]" class="down_del" onclick="hg_dvr_delete(this,{$v['id']},{$_INPUT['channel_id']});" style="display:none;"></span>
			{/if}
		</span>
		<input type="hidden" name="ids[]" value="{$v['id']}" />
		<input type="hidden" name="start[]" value="{$v['start']}" />
		<input type="hidden" name="end[]" value="{$v['end']}" />
		<input type="hidden" name="flag[]" value="" />
		<input type="hidden" name="dvr_delete[]" value="{$v['dvr_delete']}" />
	</div>
</li>
