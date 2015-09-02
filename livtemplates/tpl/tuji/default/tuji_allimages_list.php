{code}
   $img_url = RESOURCE_URL;
{/code}
{if $formdata}
{foreach $formdata as $v}
	<div style="width:100%;height:55px;margin-top:10px;position:relative;z-index:1;" id="pic_info_{$v['id']}">
	    <div style="background:url('{$img_url}bg-all.png') no-repeat -121px -164px;width:16px;height:16px;position:absolute;left:0px;top:0px;z-index:10;cursor:pointer;display:none;" id="select_cover_{$v['id']}"></div>
		<div style="background:url('{$img_url}close_plan.png') no-repeat;width:16px;height:16px;position:absolute;left:36px;top:-6px;z-index:10;display:none;cursor:pointer;" id="remove_icon_{$v['id']}" onmouseover="hg_show_png({$v['id']},1);" onmouseout="hg_show_png({$v['id']},0);" onclick="hg_remove_thisone({$v['id']});"></div>
		<div style="background:url('{$v[img]}');height:48px;width:48px;border-radius:6px;float:left;" onmouseover="hg_show_png({$v['id']},1);" onmouseout="hg_show_png({$v['id']},0);"  onclick="hg_switch_cover('#select_cover_{$v[id]}','{$v[id]}');" title="单击选中设为封面"></div>
		<textarea rows="2"     id="pic_comment_{$v['id']}" name="pic_comment[]"  class="info-description info-input-left t_c_b"  style="height:42px;width:450px;float:left;margin-left:15px;"  onfocus="text_value_onfocus(this,'这里输入图片描述');" onblur="text_value_onblur(this,'这里输入图片描述');">{if $v['description']}{$v['description']}{else if $v['is_namecomment']}{$v['old_name']}{else}{$v['default_comment']}{/if}</textarea>
		<input type="hidden" name="image_ids[]"  value="{$v['id']}" />
		<input type="hidden" name="order_ids[]"  value="{$v['order_id']}" />
	</div>
{/foreach}
{else}
<div id="pic_tips" style="display:none;color:#9c5e0c;background:#ffecb9;height:30px;width:100%;text-align:center;line-height:30px;">该图集下没有图片</div>
<script type="text/javascript">
	$('#pic_tips').fadeIn(2000);
	setTimeout(function(){
		$('#pic_tips').fadeOut(3000);
	},2000);
</script>
{/if}

