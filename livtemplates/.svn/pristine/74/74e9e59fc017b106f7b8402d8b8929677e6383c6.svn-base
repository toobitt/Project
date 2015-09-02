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
	{foreach $formdata as $k => $v}
		{code}
			$$k = $v;
						
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
<h2>{$optext}文案</h2>
<ul class="form_ul">
<li class="i">
		<div class="form_ul_div clear info">
		<span class="title">名称: </span><textarea name="name" id="name"  cols="45" rows="4" />{$name}</textarea>{if !$operate}*必填{/if}
	</div>
		<div class="form_ul_div clear info">
		<span class="title">标识: </span>
		<input type="text" name="operate"  value="{$operate}" {if $operate}readonly="true"{/if}/>{if $operate}*禁止修改,会出现问题{else}*必填{/if}
	</div>
				<div class="form_ul_div clear info">
					<div class="col_choose clear">
						<span class="title">所属分类：</span>
						<div class="input " style="width:210px; float:left;">
		                         <select name="field">
								{foreach $sorts as $k=>$v}
		                        {code}
		                            $sorts_name = $v['name'];
		                            $sorts_field = $v['field'];
		                        {/code}
                                 <option {if ($sorts_field == $field)} selected="selected"{/if}  value ="{$sorts_field}">{code} echo $sorts_name;{/code}</option>
		                        {/foreach}
		                        </select>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
	<div class="form_ul_div clear info">
		<span class="title">内容: </span><textarea name="value" id="value"  cols="45" rows="4" />{$value}</textarea>
	</div>
</li>

<li class="i icon">
	<div class="form_ul_div clear">
				{if $icon}
				{code}$icon=hg_fetchimgurl($icon);{/code}
			<img id="icon" style="width: auto; height: auto;" src="{$icon}">
			{/if}
		<span class="title" >图标：</span>
			<input type="file" name="icon"  value="submit">
		{if $icon}	<em class="del-extend" title="删除图标"></em>{/if}
			</div>
		</li>
{if $icon}<input type="hidden" class="icondel" name="icondel" value>{/if}
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
$(function(){
   $(".del-extend").click(function(){
	   $(".icondel").val("1");
	   $("#icon").remove();
});

});
</script>

