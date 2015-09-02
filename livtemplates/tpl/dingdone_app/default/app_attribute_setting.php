<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z lijiaying $ */
?>
{template:head}
{css:ad_style}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{css:colorpicker}
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
{js:jqueryfn/colorpicker.min}
{js:2013/hg_colorpicker}
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

#defaultValue label {float:none;}
.alignEls {margin-right:10px;}
.alignEls input,.verticalAlign {vertical-align:middle; margin-right:5px;}
.addData, .dropData, .addData2 {cursor:pointer; width:20px; height:20px;}
img {vertical-align:middle;}
</style>
{code}
if (in_array($type, array('radio', 'checkbox', 'select')))
{
	$index = !$def_val ? 0 : count($def_val);
} else {
	$index = 0;
}
{/code}
<script type="text/javascript">
$(function() {
	var box = $('#wrapBox');
	var index = {$index};
	var bar = function(type) {
		return '<p><label class="alignEls"><input type="radio" name="def_type" class="def_type_select" value="0" attr="'+type+'" checked="checked" />文字</label>&nbsp;&nbsp;<label class="alignEls"><input type="radio" name="def_type" class="def_type_select" value="1" attr="'+type+'" />图片</label> <span class="addData" type="'+type+'">+</span></p>';
	};
	var con = function(type) {
		if (type == 'radio') {
			return '<p><input type="text" name="attr_def_name[0]" placeholder="名" /> - <input type="text" name="attr_def_val[0]" placeholder="值" /> <label><input type="'+type+'" name="defIndex" class="verticalAlign radioType" value="1" index="0" />设为选中</label> <span class="dropData">-</span></p>';
		} else if (type == 'checkbox') {
			return '<p><input type="text" name="attr_def_name[0]" placeholder="名" /> - <input type="text" name="attr_def_val[0]" placeholder="值" /> <label><input type="'+type+'" name="defIndex[0]" class="verticalAlign" value="1" />设为选中</label> <span class="dropData">-</span></p>';
		}
	};
	if ($('#attr_type')) {
		$('#attr_type').change(function() {
			index = 0;
			var v = $(this).val().trim();
			switch (v) {
				case 'input' :
					box.html('<input type="text" name="attr_defValue" placeholder="设置默认值" />');
				break;
				case 'textarea' :
					box.html('<textarea name="attr_defValue" cols="40" rows="5"></textarea>');
				break;
				case 'color' :
					box.html('<input class="select-input color-picker" type="text" name="attr_defValue" />');
				break;
				case 'radio' :
					box.html(bar(v)+con(v));
				break;
				case 'checkbox' :
					box.html(bar(v)+con(v));
				break;
				case 'select' :
					box.html('<span class="addData" type="radio">+</span>'+con('radio'));
				break;
				case 'mix' :
					box.html('<p><input type="radio" name="mixType" value="text" class="verticalAlign" /><input type="text" name="mixText" placeholder="设置默认值" /><br /><input type="radio" name="mixType" value="file" class="verticalAlign" /><input type="file" name="mixFile" /></p>');
				break;
				case 'singlefile' :
				case 'multiplefiles' :
				break;
				default :
					box.html('<input type="text" name="attr_defValue" placeholder="设置默认值" />');
			}
			$('.color-picker').hg_colorpicker();/*颜色选择器*/
			if (v == '' || v == 'singlefile' || v == 'multiplefiles') {
				$('#wrapBox').empty();
				$('#defaultValue').hide();
			} else {
				$('#defaultValue').show();
			}
		});
	}
	$('.addData').live('click', function() {
		var type = $(this).attr('type');
		var str = '';
		index++;
		if (type == 'checkbox') {
			str = '<p><input type="text" name="attr_def_name['+index+']" placeholder="名" /> - <input type="text" name="attr_def_val['+index+']" placeholder="值" /> <label><input type="'+type+'" name="defIndex['+index+']" value="1" class="verticalAlign" />设为选中</label> <span class="dropData">-</span></p>';
		} else if (type == 'radio') {
			str = '<p><input type="text" name="attr_def_name['+index+']" placeholder="名" /> - <input type="text" name="attr_def_val['+index+']" placeholder="值" /> <label><input type="'+type+'" name="defIndex" value="1" class="verticalAlign radioType" index="'+index+'" />设为选中</label> <span class="dropData">-</span></p>';
		}
		box.append(str);
	});

	$('.addData2').live('click', function() {
		var type = $(this).attr('type');
		var str = '';
		index++;
		if (type == 'checkbox') {
			str = '<p><input type="file" name="attr_def_pic['+index+']" /> - <input type="text" name="attr_def_val['+index+']" placeholder="值" /> <label><input type="'+type+'" name="defIndex['+index+']" value="1" class="verticalAlign" />设为选中</label> <span class="dropData">-</span></p>';
		} else if (type == 'radio') {
			str = '<p><input type="file" name="attr_def_pic['+index+']" /> - <input type="text" name="attr_def_val['+index+']" placeholder="值" /> <label><input type="'+type+'" name="defIndex" value="1" class="verticalAlign radioType" index="'+index+'" />设为选中</label> <span class="dropData">-</span></p>';
		}
		box.append(str);
	});

	$('.def_type_select').live('click', function() {
		var type = $(this).attr('attr');
		if ($(this).val() == 1) {
			var content = '<p><label class="alignEls"><input type="radio" name="def_type" class="def_type_select" value="0" attr="'+type+'" />文字</label>&nbsp;&nbsp;<label class="alignEls"><input type="radio" name="def_type" class="def_type_select" value="1" checked="checked" attr="'+type+'" />图片</label> <span class="addData2" type="'+type+'">+</span></p>';
			if (type == 'radio') {
				content += '<p><input type="file" name="attr_def_pic[0]" /> - <input type="text" name="attr_def_val[0]" placeholder="值" /> <label><input type="'+type+'" name="defIndex" value="1" class="verticalAlign radioType" index="0" />设为选中</label> <span class="dropData">-</span></p>';
			} else if (type == 'checkbox') {
				content += '<p><input type="file" name="attr_def_pic[0]" /> - <input type="text" name="attr_def_val[0]" placeholder="值" /> <label><input type="'+type+'" name="defIndex[0]" value="1" class="verticalAlign" />设为选中</label> <span class="dropData">-</span></p>';
			}
			box.html(content);
		} else {
			box.html(bar(type)+con(type));
		}
	});

	$('.dropData').live('click', function() {
		var n = $(this).parent().children('label').children('.radioType').attr('index');
		var radioIndex = $('#radioIndex').val();
		if (n == radioIndex) {
			$('#radioIndex').val('');
		}
		index--;
		$(this).parent().remove();
	});

	$('.radioType').live('click', function() {
		var index = $(this).attr('index');
		$('#radioIndex').val(index);
	});
});
</script>
	<div class="ad_middle">
	<form name="editform" action="" method="post" class="ad_form h_l" onsubmit="return hg_form_check();" enctype="multipart/form-data">
		<h2>{$name}属性编辑</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">属性名称：</span>
						<div class="input " style="width:345px;float: left;">
							<input type="text" name="attr_name" id="attr_name" value="{$name}" />
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">属性描述：</span>
						<textarea id="intro" name="attr_brief">{$brief}</textarea>
						<span class="error" id="intro_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li id="defaultValue" class="i"{if !$type || $type == 'singlefile' || $type == 'multiplefiles'} style="display: none;"{/if}>
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">默认值：</span>
						<div style="float:left;">
							<span id="wrapBox">
								{if $type == 'input'}
								<input type="text" name="attr_defValue" value="{$def_val}" />
								{elseif $type == 'textarea'}
								<textarea name="attr_defValue" cols="40" rows="5">{$def_val}</textarea>
								{elseif $type == 'color'}
								<input class="select-input color-picker" type="text" name="attr_defValue" value="{$def_val}" />
								{elseif $type == 'radio' || $type == 'checkbox'}
								<p><label class="alignEls"><input type="radio" name="def_type" class="def_type_select" value="0" attr="{$type}"{if !$def_val || $def_val[0]['type'] == 'text'} checked="checked"{/if} />文字</label>&nbsp;&nbsp;<label class="alignEls"><input type="radio" name="def_type" class="def_type_select" value="1" attr="{$type}"{if $def_val && $def_val[0]['type'] == 'image'} checked="checked"{/if} />图片</label> <span class="{if !$def_val || $def_val[0]['type'] == 'text'}addData{elseif $def_val && $def_val[0]['type'] == 'image'}addData2{/if}" type="{$type}">+</span></p>
									{if !$def_val}
										{if $type == 'radio'}
								<p><input type="text" name="attr_def_name[0]" placeholder="名" /> - <input type="text" name="attr_def_val[0]" placeholder="值" /> <label><input type="{$type}" name="defIndex" class="verticalAlign radioType" value="1" index="0" />设为选中</label> <span class="dropData">-</span></p>
										{elseif $type == 'checkbox'}
								<p><input type="text" name="attr_def_name[0]" placeholder="名" /> - <input type="text" name="attr_def_val[0]" placeholder="值" /> <label><input type="{$type}" name="defIndex[0]" class="verticalAlign" value="1" />设为选中</label> <span class="dropData">-</span></p>
										{/if}
									{else}
										{foreach $def_val as $k => $v}
										{if $type == 'radio'}
											{if $v['type'] == 'text'}
										<p><input type="text" name="attr_def_name[{$k}]" placeholder="名" value="{$v['name']}" /> - <input type="text" name="attr_def_val[{$k}]" placeholder="值" value="{$v['value']}" /> <label><input type="{$type}" name="defIndex" class="verticalAlign radioType" value="1" index="{$k}"{if $v['default']} checked="checked"{/if} />设为选中</label> <span class="dropData">-</span></p>
											{elseif $v['type'] == 'image'}
										<p>{if $v['pic_id']}<input type="hidden" name="attr_def_pic_val[{$k}]" value="{$v['pic_id']}" /><img src="{code}echo hg_bulid_img($v['name'], 50, 1);{/code}" />{else}<input type="file" name="attr_def_pic[{$k}]" />{/if} - <input type="text" name="attr_def_val[{$k}]" placeholder="值" value="{$v['value']}" /> <label><input type="{$type}" name="defIndex" value="1" class="verticalAlign radioType" index="{$k}" {if $v['default']} checked="checked"{/if} />设为选中</label> <span class="dropData">-</span></p>
											{/if}
											{code}if ($v['default']) $radioIndex = $k;{/code}
										{elseif $type == 'checkbox'}
											{if $v['type'] == 'text'}
										<p><input type="text" name="attr_def_name[{$k}]" placeholder="名" value="{$v['name']}" /> - <input type="text" name="attr_def_val[{$k}]" placeholder="值" value="{$v['value']}" /> <label><input type="{$type}" name="defIndex[{$k}]" class="verticalAlign" value="1"{if $v['default']} checked="checked"{/if} />设为选中</label> <span class="dropData">-</span></p>
											{elseif $v['type'] == 'image'}
										<p>{if $v['pic_id']}<input type="hidden" name="attr_def_pic_val[{$k}]" value="{$v['pic_id']}" /><img src="{code}echo hg_bulid_img($v['name'], 50, 1);{/code}" />{else}<input type="file" name="attr_def_pic[{$k}]" />{/if} - <input type="text" name="attr_def_val[{$k}]" placeholder="值" value="{$v['value']}" /> <label><input type="{$type}" name="defIndex[{$k}]" value="1" class="verticalAlign" {if $v['default']} checked="checked"{/if} />设为选中</label> <span class="dropData">-</span></p>
											{/if}
										{/if}
										{/foreach}
									{/if}
								{elseif $type == 'select'}
									<span class="addData" type="radio">+</span>
									{if $def_val}
									{foreach $def_val as $k => $v}
									<p><input type="text" name="attr_def_name[{$k}]" placeholder="名" value="{$v['name']}" /> - <input type="text" name="attr_def_val[{$k}]" placeholder="值" value="{$v['value']}" /> <label><input type="radio" name="defIndex" class="verticalAlign radioType" value="1" index="{$k}"{if $v['default']} checked="checked"{/if} />设为选中</label> <span class="dropData">-</span></p>
									{code}if ($v['default']) $radioIndex = $k;{/code}
									{/foreach}
									{else}
									<p><input type="text" name="attr_def_name[0]" placeholder="名" /> - <input type="text" name="attr_def_val[0]" placeholder="值" /> <label><input type="radio" name="defIndex" class="verticalAlign radioType" value="1" index="0" />设为选中</label> <span class="dropData">-</span></p>
									{/if}
								{elseif $type == 'mix'}
									<p><input type="radio" name="mixType" value="text" class="verticalAlign"{if $def_val['text']['default']} checked="checked"{/if} /><input type="text" name="mixText" placeholder="设置默认值" value="{$def_val['text']['def_value']}" /><br /><input type="radio" name="mixType" value="file" class="verticalAlign"{if $def_val['file']['default']} checked="checked"{/if} />{if $def_val['file']['def_value']}<img src="{code}echo hg_bulid_img($def_val['file']['def_value'], 50);{/code}" />{/if}<input type="file" name="mixFile" /></p>
								{/if}
							</span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">属性排序：</span>
						<div class="input " style="width:345px;float: left;">
							<input type="text" name="sort_order" id="sort_order" value="{$sort_order}" />
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
		</ul>
	<input type="hidden" name="a" value="updateAttr" />
	<input type="hidden" name="is_del" id="is_del" value="0" />
	<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	<input type="hidden" name="radioIndex" id="radioIndex" value="{$radioIndex}" />
	{if $_INPUT['ui_id']}
	<input type="hidden" name="ui_id" value="{$_INPUT['ui_id']}" />
	{/if}
	{if $_INPUT['temp_id']}
	<input type="hidden" name="temp_id" value="{$_INPUT['temp_id']}" />
	{/if}
	<input type="hidden" name="attr_type" value="{$type}" />
	</br>
	<input type="submit" name="sub" value="保存" id="sub" class="button_6_14" />
	</form>
	</div>
	<div class="right_version" style="width:290px;">
		<!--
		<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
		-->
		{code}
		if ($_INPUT['ui_id']) {
			$ids = $_INPUT['ui_id'];
		} elseif ($_INPUT['temp_id']) {
			$ids = $_INPUT['temp_id'];
		}
		{/code}
		<h2><a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$ids}&flag={$_INPUT['flag']}&infrm=1">返回前一页</a></h2>
	</div>
{template:foot}