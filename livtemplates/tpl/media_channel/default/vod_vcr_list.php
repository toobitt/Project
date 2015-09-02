{foreach $formdata as $k => $v}
<li class="clear"  id="video_box_{$v['order_id']}"  onmousedown="hg_display_current({$v['order_id']});" onmouseout="con_m_l_t_hide();" onmouseover="con_m_l_t_show(this,{$v['order_id']});">
	<div class="n">
		<img src="{$v['img_src']}">
		<span class="bor"></span>
		<span class="bg"></span>
		<span class="text"  id="duration_box_{$v['order_id']}">{$v['format_duration']}</span>
		<span class="rm"  onmousedown="hg_remove_videobox({$v['order_id']});" ></span>
	</div>
	<input type="hidden" name="vodid[]"       value="{$v['vodid']}"         id="vodid_{$v['order_id']}"       />
	<input type="hidden" name="start_time[]"  value="{$v['start_time']}"    id="start_time_{$v['order_id']}"  />
	<input type="hidden" name="duration[]"    value="{$v['duration']}"      id="duration_{$v['order_id']}"    />
	<input type="hidden" name="original_id[]" value="{$v['original_id']}"   id="original_id_{$v['order_id']}" />
	<input type="hidden" name="name[]"        value="{$v['name']}"   	    id="name_{$v['order_id']}" 		 />
	<input type="hidden" name="order_id[]"    value="{$v['order_id']}"  	id="order_id_{$v['order_id']}"    />
	<input type="hidden"  					  value="{$v['max_duration']}"  id="max_duration_{$v['order_id']}"   />
	<input type="hidden"  					  value="{$v['totalsize']}"  	id="totalsize_{$v['order_id']}"   />
	<input type="hidden" value="0"     						 				id="attrflag_{$v['order_id']}"    />
</li>
{/foreach}