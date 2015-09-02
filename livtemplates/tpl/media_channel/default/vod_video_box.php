<li class="clear {if !$formdata['is_current']}h{/if}"  id="video_box_{$formdata['vcr_num']}"   onmousedown="hg_display_current({$formdata['vcr_num']});"  onmouseout="con_m_l_t_hide();" onmouseover="con_m_l_t_show(this,{$formdata['vcr_num']});" >
	<div class="n">
		<img src="{$formdata['img_src']}">
		<span class="bor"></span>
		<span class="bg"></span>
		<span class="text"  id="duration_box_{$formdata['vcr_num']}">{$formdata['format_duration']}</span>
		<span class="rm"  onmousedown="hg_remove_videobox({$formdata['vcr_num']});" ></span>
	</div>
	<input type="hidden" name="vodid[]"       value="{$formdata['vodid']}"         id="vodid_{$formdata['vcr_num']}"       />
	<input type="hidden" name="start_time[]"  value="{$formdata['start']}"         id="start_time_{$formdata['vcr_num']}"  />
	<input type="hidden" name="duration[]"    value="{$formdata['duration']}"      id="duration_{$formdata['vcr_num']}"    />
	<input type="hidden" name="original_id[]" value="{$formdata['id']}"            id="original_id_{$formdata['vcr_num']}" />
	<input type="hidden" name="name[]"        value="{$formdata['title']}(片段)"   id="name_{$formdata['vcr_num']}" />
	<input type="hidden" name="order_id[]"    value="{$formdata['vcr_num']}"  	   id="order_id_{$formdata['vcr_num']}"    />
	<input type="hidden"  					  value="{$formdata['max_duration']}"  id="max_duration_{$formdata['vcr_num']}"   />
	<input type="hidden" {if !$formdata['is_current']}value="1"{else}value="0"{/if}   id="attrflag_{$formdata['vcr_num']}"    />
</li>