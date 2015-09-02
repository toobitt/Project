<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z lijiaying $ */
?>

{template:head}
{css:ad_style}
{css:lbs_field_form}
{js:jqueryfn/jquery.tmpl.min}
{js:area}
{js:lbs/lbs_field_form}

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
{code}//print_r($formdata['field_default']);{/code}
<script>
$.globaldefault = {code} echo json_encode($formdata['field_default']);{/code}
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<form name="editform" action="" method="post" class="ad_form h_l">
<h2>{$optext}扩展信息</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">信息名称: </span>
		<input type="text" name="zh_name"  value="{$zh_name}" />
	</div>
	<div class="form_ul_div clear info">
		<span class="title">信息标识: </span>
		<input type="text" name="field"  {if $field} readonly="readonly" {/if} value="{$field}" />
	</div>
	<div class="form_ul_div clear info">
		<span class="title">描述备注: </span><textarea name="remark" id="remark"  cols="45" rows="4" />{$remark}</textarea>
	</div>
</li>

<li class="i option-type">
	<div class="form_ul_div clear pre-option">
		<span class="title">数据类型: </span>
		<div style="width:335px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{foreach $get_styles as $k=>$v}
		         {code}
		         	$style = $v['zh_name'];
		            $form_style_id = $v['id'];
		            $form_type = $v['datatype'];
		         {/code}
		         <li ><input type="radio" class="data-type" name="form_style" {if ($form_style_id==$form_style)} checked="checked"{/if}  value ="{$form_style_id}" _val="{code} echo $style;{/code}" _type="{code} echo $form_type;{/code}"><span>{$style}</span></li>
		    {/foreach}
			</ul>
		</div>
	</div>
	
<div  class="default-option" style="{if $formdata['field_default']} display:-webkit-box;{/if}">
	<div class="option-contain">
	{foreach $formdata['field_default'] as $k=>$v}
		<input name="field_default[]"  type= "text" placeholder="请输入预设选项" value="{$v}">
	{/foreach}
	</div>
		<p class="add-option">+</p>
</div>
<div class="form_ul_div clear info">
		<span class="title">默认值: </span>
		<input type="text" name="selected"  style="text-indent: 10px;" value="{$selected}" placeholder="默认值必须是预选值的一项"/>
	</div>
	
				<div id="is_batch" class="form_ul_div col_choose clear">
						<span class="title" style="width: 80px;">支持批量：</span>
							<input type="radio" name="batch" id="batch" {if  $batch } checked="checked"{/if} value="1" /><span>是</span>
							<input type="radio" name="batch" id="batch" {if  $batch==0 } checked="checked"{/if} value="0" /><span>否</span>
						<span class="error" id="title_tips" style="display:none;">是否支持批量上传</span>
				</div>	

</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">适用分类: </span>
		<div style="display: -webkit-box;">
			<ul class="type-choose">
			{foreach $get_sort as $key => $value}
		         {code}
		            $sortid = $value['id'];
			        $name = $value['name'];
			        $flag = 0;
			        if(is_array($sort_id))
			        {
			            if (in_array($sortid,$sort_id)) $flag=1;
			        }
			        else 
			        {
			            if($sortid == $sort_id) $flag=1;
			        }
		         {/code}
		         <li><input type="checkbox" {if $flag} checked="checked"{/if} value="{$sortid}" size="50" name="sort[]"/><span>{code}echo $name;{/code}</span></li>
	        {/foreach}		
			</ul>
		</div>
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

<script type = "text/x-jquery-tmpl" id="option-tpl">
		<input _val="${txt}" name="field_default[]"  type= "text" placeholder="请输入预设选项" value="${value}">
</script>
<script type = "text/x-jquery-tmpl" id="add-option-tpl">
		<input  name="field_default[]"  type= "text" placeholder="请输入预设选项" value="">
</script>
<script type="text/javascript">
$(function(){
	var form_type = $('input[name="form_style"]:checked').attr("_type");
	if(form_type!="img"&&form_type!="video")	
	$("#is_batch").toggle();
	else
	{
		$("#option_value").toggle();
		}
	
   $(".data-type").click(function(){
  if($(this).attr("_type")=="img"||$(this).attr("_type")=="video")
  {
   $("#is_batch").show();
  $("#option_value").hide();
  }
  else
  {
   $("#is_batch").hide();
  $("#option_value").show();
  }
   });
});
</script>

