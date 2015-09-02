<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z lijiaying $ */
?>

{template:head}
{css:ad_style}
{css:bigcolorpicker}
{css:member_form}
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
				if($img&&is_array($img))
				{
					$img=hg_fetchimgurl($img);
				}		
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
<h2>{$optext}积分类型</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">积分名称: </span>
		<input type="text" name="title"  value="{$title}" style="width:100px;"/>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">数据库字段: </span>
		<input type="text" name="db_field"  value="{$db_field}" style="width:130px;" readonly="true"/>*禁止修改,会出现问题
	</div>
</li>
<li class="i icon">
	<div class="form_ul_div clear">
				{if $img}
			<img id="icon" style="width: auto; height: auto;" src="{$img}">
			{/if}
		<span class="title" >图标：</span>
			<input type="file" name="img"  value="submit"> 
		{if $img}<em class="del-extend" title="删除图标"></em>{/if}	
			</div>
		</li>
		{if $img}<input type="hidden" class="icondel" name="icondel" value>{/if}
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
	var value = $('input[name="isupdate"]:checked').val();
	if(value=="0")
	$('#credits').toggle();
   $(".isupdate").click(function(){
  if($(this).attr("value")=="1")
   $("#credits").show();
  else
   $("#credits").hide();
   });
   $(":text[name='usernamecolor']").bigColorpicker(function(el,color){
		$(el).val(color);
	}); 
   $(".del-extend").click(function(){
	   $(".icondel").val("1");
	   $("#icon").remove();
});
});
</script>

