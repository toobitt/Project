{foreach $formdata AS $k => $v}
<li class="clear {if !$v['is_current']}h{/if}"  id="video_box_{$v['vcr_num']}"   onmousedown="hg_display_current({$v['vcr_num']});"  onmouseout="con_m_l_t_hide();" onmouseover="con_m_l_t_show(this,{$v['vcr_num']});" >
	<div class="n">
		<img src="{$v['img_src']}">
		<span class="bor"></span>
		<span class="bg"></span>
		<span class="text"  id="duration_box_{$v['vcr_num']}">{$v['format_duration']}</span>
		<span class="rm"  onmousedown="hg_remove_videobox({$v['vcr_num']});" ></span>
	</div>
	<input type="hidden" name="vodid[]"       value="{$v['vodid']}"         id="vodid_{$v['vcr_num']}"       />
	<input type="hidden" name="start_time[]"  value="{$v['start']}"         id="start_time_{$v['vcr_num']}"  />
	<input type="hidden" name="duration[]"    value="{$v['duration']}"      id="duration_{$v['vcr_num']}"    />
	<input type="hidden" name="original_id[]" value="{$v['id']}"            id="original_id_{$v['vcr_num']}" />
	<input type="hidden" name="name[]"        value="{$v['title']}(片段)"   id="name_{$v['vcr_num']}" />
	<input type="hidden" name="order_id[]"    value="{$v['vcr_num']}"  	   id="order_id_{$v['vcr_num']}"    />
	<input type="hidden"  					  value="{$v['max_duration']}"  id="max_duration_{$v['vcr_num']}"   />
	<input type="hidden" {if !$v['is_current']}value="1"{else}value="0"{/if}   id="attrflag_{$v['vcr_num']}"    />
</li>
{/foreach}