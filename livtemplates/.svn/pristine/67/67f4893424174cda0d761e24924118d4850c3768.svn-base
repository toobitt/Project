<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z lijiaying $ */
?>
{template:head}
{css:ad_style}
{js:area}
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{js:jquery.upload}
{js:team_apply}
{js:vod_opration}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{js:tree/animate}
{js:action_ts}
<style type="text/css">
.com_btn {background:#5B5B5B; color:#FFF; border-radius:2px; display:inline-block; padding:4px 8px; cursor:pointer;}
#upload_img {display:inline-block; width:100px; height:100px; background:url("{$RESOURCE_URL}add-bg.png") no-repeat center center; float:left; margin-right:20px; border:1px solid #DEDEDE;}
#auth_form li {float:left; margin:0 17px 20px 0; position:relative;}
#auth_form li span {display:block;}
#auth_form li span strong {cursor:pointer; font-weight:normal;}
#auth_form strong.delPic,#auth_form strong.cancel {float:right;}
.mark {background:url("{$RESOURCE_URL}video/select-2x.png") no-repeat center center; position:absolute; left:0; top:0; z-index:9999; width:100px; height:100px;}
#showPicMaterial {display:none;}
.addBtn,.dropBtn,.dropBtn2 {margin-left:10px; display:inline-block; width:20px; height:20px; line-height:20px; cursor:pointer;}
#example_box {margin-left:90px;}
#example_box p {margin:10px 0;}

.attr_list {clear:both;}
.attr_list li {float:left; margin:10px; border:1px solid #CCC; padding:5px; background:#EEE;}
.attr_list li span {margin-left:10px; cursor:pointer; color:#999;}
.title {width:90px !important; text-align:left !important;}
</style>
<script type="text/javascript">
$(function() {
	$('.addBtn').click(function() {
		var con = '<p><input type="file" name="example_pic[]" accept="image/*" /><span class="dropBtn">-</span></p>';
		$('#example_box').append(con);
	});
	$('.dropBtn').live('click', function() {
		$(this).parent().remove();
	});
	$('.dropBtn2').click(function() {
		var id = $(this).attr('data-id');
		var val = $('#drop_ids').val().trim();
		var ret = '';
		if (val == '') {
			ret = id;
		} else {
			var arr = val.split(',');
			arr.push(id);
			ret = arr.join(',');
		}
		$('#drop_ids').val(ret);
		$(this).parent().remove();
	});

	$('.attr_list input[name="attribute_ids[]"]').click(function() {
		var attr_id = $(this).val();
		if ($(this).attr('checked')) {
			$('#attr_'+attr_id).show();
		} else {
			$('#attr_'+attr_id).hide();
		}
	});
});
</script>
	<div class="ad_middle">
	<form name="editform" action="" method="post" class="ad_form h_l" onsubmit="return hg_form_check();" enctype="multipart/form-data">
		<h2>{$optext}模板</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">模板名称：</span>
						<div class="input " style="width:345px;float: left;">
							<input type="text" name="temp_name" id="template_name" value="{$name}" />
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">模板标识：</span>
						<div class="input " style="width:345px;float: left;">
							<input type="text" name="temp_mark" id="template_mark" value="{$mark}" />
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">模板简介：</span>
						<textarea id="intro" name="temp_brief">{$brief}</textarea>
						<span class="error" id="intro_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">模板图标：</span>
						<div>
							{if $pic}
							    {code}
								if($pic['dir'])
								{
									$_tp_pic = hg_bulid_img($pic, 50, 50);
								}
								else
								{
									$_tp_pic = $pic['host'] . $pic['filepath'] . $pic['filename'] .'!icon';
								}
								{/code}
							<img src="{$_tp_pic}" style="vertical-align:middle;" />
							{/if}
							<input type="file" name="template_pic" accept="image/*" />
						</div>
						<span class="error" id="logo_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">示例图片：</span>
						<div id="example_box">
							<p><input type="file" name="example_pic[]" accept="image/*" /><span class="addBtn">+</span></p>
							{if $example_pic}
							{foreach $example_pic as $k => $v}
								{code}
    								if($v['info']['dir'])
    								{
    									$_example_pic = hg_bulid_img($v['info'], 50, 50);
    								}
    								else
    								{
    									$_example_pic = $v['info']['host'] . $v['info']['filepath'] . $v['info']['filename'] .'!icon';
    								}
								{/code}
							<p><img src="{$_example_pic}" style="vertical-align:middle;" /><span class="dropBtn2" data-id="{$v['id']}">-</span></p>
							{/foreach}
							{/if}
						</div>
						<span class="error" id="logo_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">模板属性：</span>
						<div>
							{code}
							if ($attr)
							{
								$attr_arr = array();
								foreach ($attr as $v)
								{
									$attr_arr[$v['attr_id']] = $v;
								}
								$attr_ids = array_keys($attr_arr);
							}
							{/code}
							{if $attribute_info}
							<ul class="attr_list">
								{foreach $attribute_info as $attrs}
								<li>
									<label><input type="checkbox" name="attribute_ids[]" value="{$attrs['id']}"{if in_array($attrs['id'], $attr_ids)} checked="checked"{/if} style="vertical-align: top; margin-right:5px;" />{if in_array($attrs['id'], $attr_ids) && $attr_arr[$attrs['id']]['name']}{$attr_arr[$attrs['id']]['name']}{else}{$attrs['name']}{/if}</label>
									<span id="attr_{$attrs['id']}" {if !in_array($attrs['id'], $attr_ids)} style="display:none;"{/if}><a href="./run.php?mid={$_INPUT['mid']}&a=attr&id={$attrs['id']}&temp_id={$id}&flag={$_INPUT['flag']}&infrm=1">设置</a></span>
								</li>
								{/foreach}
							</ul>
							{/if}
							<div style="clear:both;"></div>
						</div>
						<span class="error" id="logo_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<!--
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">模块可选图标：</span>
						<div id="example_box">
							<p><input type="file" name="module_pic" accept="application/x-zip-compressed" /><span style="margin:0 10px;">请上传zip包</span>{if $module_pic_zip}<a href="#" data="{$module_pic_zip['url']}">[编辑模块图标]</a>{/if}</p>
						</div>
						<span class="error" id="logo_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			-->
		</ul>
	<input type="hidden" name="a" value="{$action}" />
	<input type="hidden" name="is_del" id="is_del" value="0" />
	<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	<input type="hidden" name="drop_ids" id="drop_ids" />
	<input type="hidden" name="attr" value="" />
	</br>
	<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14" />
	</form>
	</div>
	<div class="right_version" style="width:290px;">
		<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
	</div>
{template:foot}