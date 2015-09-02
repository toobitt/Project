<li class="day_default">
	<div class="single_item" style="background:{$pcolor};border-left-color: {$bcolor};border-left-width: 2px;">
		<span class="box" style="margin-left: -5px;background: {$bcolor}"></span>
		<!--
{code}
		$type_source = array('other'=>' size="14" autocomplete="off" style="width:80px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;position: relative;top: -23px;" onblur=""','name'=>'start_time[]','style'=>'width:80px;float: left;','type'=>'HH:mm:ss','other_focus' => '');
		$default_start = '';
		{/code}
		{template:form/wdatePicker,'',$default_start,'',$type_source}
-->
		<div class="input text">
			<span class="input_left"></span>
			<span class="input_right"></span>
			<span class="input_middle">
				<input type="text" name="theme[]" value="" />
			</span>
		</div>
		<!--
{code}
		$type_source = array('other'=>' size="14" autocomplete="off" style="width:80px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;position: relative;top: -23px;" onblur=""','name'=>'end_time[]','style'=>'width:80px;float: left;','type'=>'HH:mm:ss','other_focus' => '');
		$default_end = '';
		{/code}
		{template:form/wdatePicker,'',$default_end,'',$type_source}
-->
		<span class="focus_move" onmouseover="hg_show_delete(this,true);" onmouseout="hg_show_delete(this,false);">
			<span name="delete_buttom[]" class="down_del" onclick="hg_program_delete(this);" style="display:none;"></span>
		</span>
		<input type="hidden" name="ids[]" value="" />
	</div>
</li>
