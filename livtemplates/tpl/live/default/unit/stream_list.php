<div class="stream">
	<span class="s_left" onclick="hg_stream_operate(this, 1);"></span>
	<span style="margin-left: 10px;">输出标识</span>
	<input name="stream_name[]" type="text" value="" style="width:30px;" />
	<span>来源地址</span>
	<input name="url[]" type="text" value="" style="width:200px;" />
<span>码流</span>
	<input name="bitrate[]" type="text" style="width:50px;" />
	<span>默认</span><input name="is_default" type="radio" value="0"{if $hg_value} checked="checked"{/if} title="默认流" />
	<span name="delete_submit[]" class="s_right display" onclick="hg_stream_operate(this, 0);"></span>
	<input type="hidden" name="stream_id[]" value="" />
</div>