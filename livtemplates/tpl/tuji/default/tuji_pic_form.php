{if $formdata['info']}
<form action="run.php?mid={$_INPUT['mid']}"  id="tuji_images_form"  name="tuji_images_form"  method="post" enctype="multipart/form-data"  onsubmit="return hg_ajax_submit('tuji_images_form')" >
	{foreach $formdata['info'] as $k => $v}
		<img src="{$v['pic_url']}" width="145px" height="140px" style="float:left;border:1px solid gray;" />
		<div  style="width:410px;height:148px;float:left;margin-left:5px;">
			<input  type="text" name="pic_title[]"  class="info-title info-input-left t_c_b" style="width:408px;" value="{$v['old_name']}" onfocus="text_value_onfocus(this,'在这里添加标题');" onblur="text_value_onblur(this,'在这里添加标题');" />
			<textarea rows="2" class="info-description info-input-left t_c_b"  style="height:96px;width:408px;margin-top:10px;"    name="pic_comment[]"    onfocus="text_value_onfocus(this,'这里输入描述');" onblur="text_value_onblur(this,'这里输入描述');">{$v['description']}</textarea>
			<input type="hidden" name="pic_id[]" value="{$v['id']}" />
		</div>
	{/foreach}
	<input type="hidden" name="a" value="update" />
	<input type="submit" name="sub" value="更新" class="button_4" style="margin-left:498px;"/>
	<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
{else}
	{if $formdata['mode'] == 1}
		{template:unit/tuji_add_pics_form}
	{else}
		{template:unit/tuji_add_more_pics_form}
	{/if}
{/if}
