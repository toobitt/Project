<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z ayou $ */
?>
{template:head}
{css:ad_style}
{css:bigcolorpicker}
{css:copywriting_form}
{js:jqueryfn/jquery.tmpl.min}
{js:bigcolorpicker}
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
{code}//print_r($updatetype);{/code}
<script>
$.globaldefault = {code} echo json_encode($formdata['field_default']);{/code}
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<form name="editform" action="" method="post" enctype='multipart/form-data' class="ad_form h_l">
<h2>{$optext}配置应用</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">应用名称: </span>
		<input type="text" name="appname"  value="{$appname}" />{if !$field}*必填{/if}
	</div>
		<div class="form_ul_div clear info">
		<span class="title">应用标识: </span>
		<input type="text" name="app_uniqueid"  value="{$app_uniqueid}" {if $app_uniqueid}readonly="true"{/if}/>{if $app_uniqueid}*禁止修改,会出现问题{else}*必填{/if}
	</div>
	<div class="form_ul_div clear info">
		<span class="title">描述: </span><textarea name="brief" id="brief"  cols="45" rows="4" />{$brief}</textarea>
	</div>
	<div class="form_ul_div clear">
		<span class="title">更新方式: </span>
		<ul class="type-choose clear">
			<li ><input type="radio" class="updatetype" name="updatetype" {if !$updatetype} checked="checked"{/if}  value ="0" _val="推送" _type="0"><span>推送</span></li>
			<li ><input type="radio" class="updatetype" name="updatetype" {if  $updatetype} checked="checked"{/if}  value ="1" _val="拉取" _type="1"><span>拉取</span></li>	
	        <span class="error" id="title_tips" style="display:block;">*推送：会主动推送最新配置(POST方式);拉取：会通知应用配置已经更新,不会主动发送任何配置.</span>	
		</ul>
	</div>
	<div class="form_ul_div clear info">
		<span class="title">调用URL: </span>
		<input type="text" name="callurl"  value="{$callurl}" />
		*用于应用相关配置被更新后通知被更新的应用
	</div>
</li>
		<li class="i">
			
			{if($formdata['argument'])}
			
			{foreach $formdata['argument']['argument_name'] as $k=>$v}
			<div class='form_ul_div clear'>
				<span class='title'>参数名称: </span>
				<input type='text' name='argument_name[]' value='{$formdata["argument"]["argument_name"][$k]}' style='width:90px;' class='title'>&nbsp;
				标识: <input type='text' name='ident[]' value='{$formdata["argument"]["ident"][$k]}' style='width:90px;' class='title'>&nbsp;		
				值: <input type='text' name='value[]' value='{$formdata["argument"]["value"][$k]}' size='16'/>&nbsp;
				<span>传值方式: </span>
				<select name='argument_type[]'>
					<option {if $formdata['argument']['argument_type'][$k] == 'get'}selected='selected'{/if} value ='get'>GET</option>
					<option {if $formdata['argument']['argument_type'][$k] == 'post'}selected='selected'{/if} value ='post'>POST</option>
				</select>
				<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' data-save="1" onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span>
			</div>
			{/foreach}
			{/if}
			<div id="extend"></div>
			<div class="form_ul_div clear">
				<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addArgumentDom();">添加参数</span>
			</div>
			
		</li>
</ul>

<input type="hidden" name="a" value="{$action}" />
<input type="hidden" name="is_del" id="is_del" value="0" />
<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<div class="temp-edit-buttons">
<input type="submit" name="sub" value="{$optext}" class="edit-button submit"/>
<input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
</div>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}

<script type="text/javascript">
function hg_addArgumentDom()
{
	var div = "<div class='form_ul_div clear'><span class='title'>参数名称: </span><input type='text' name='argument_name[]' style='width:90px;' class='title'>&nbsp;&nbsp;标识: <input type='text' name='ident[]' style='width:90px;' class='title'>&nbsp;&nbsp;值: <input type='text' name='value[]' size='16'/>&nbsp;&nbsp;<span>传值方式: </span><select name='argument_type[]'><option value='get'>GET</option><option value='post'>POST</option></select><span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
	$('#extend').append(div);
	hg_resize_nodeFrame();
}

function hg_optionTitleDel(obj)
{
	if($(obj).data("save"))
	{
		if(confirm('确定删除该参数配置吗？'))
		{
			$(obj).closest(".form_ul_div").remove();
		}
	}
	else
	{
		$(obj).closest(".form_ul_div").remove();
	}
	hg_resize_nodeFrame();
}

</script>
