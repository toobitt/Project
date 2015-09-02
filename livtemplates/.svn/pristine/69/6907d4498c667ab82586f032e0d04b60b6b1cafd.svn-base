<?php 
?>

{template:head}
{css:ad_style}
{css:configSet_form}
{js:jqueryfn/jquery.tmpl.min}
{js:area}
{js:settings/configSet_form}
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}
{if is_array($formdata)}
	{foreach $formdata as $keys => $values}
		{code}
			$$keys = $values;
						
		{/code}
	{/foreach}
{/if}
{code}//hg_pre($get_sort);{/code}
<script>
jQuery(function($){
	//new PCAS("province", "city", "area");
})
</script>

<style>

</style>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<form name="editform" action="" method="post" class="ad_form h_l">
<h2>{$optext}配置项</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">配置名称: </span>
		<input type="text" name="settitle"  style="width:200px" value="{$settitle}" />
	</div>
	<div class="form_ul_div clear">
		<span class="title">配置标识: </span>
		<input type="text" name="setname"  size="20" style="width:150px" {if $setname} readonly="readonly" {/if} value="{$setname}" />
	</div>
	<div class="form_ul_div clear">
		<span class="title">配置备注: </span><textarea name="description" id="description"  cols="45" rows="4" />{$description}</textarea>
	</div>
</li>

<li class="i">
	<div class="form_ul_div clear sort">
	<span class="title">配置分类: </span>
	 <select id="groupmark" name="groupmark">
	 <option value=''>-请选择分类-</option>
	 {if is_array($setSortInfo)}
		{foreach $setSortInfo as $v}
         <option _id="{$v['groupmark']}" {if $v['groupmark'] == $groupmark} selected="selected"{/if}  value ="{$v[groupmark]}">{$v['grouptitle']}</option>
	     {/foreach}
	     {/if}
	   </select>
	</div>
</li>
			
<li class="i option-type">
	<div class="form_ul_div clear pre-option">
		<span class="title">配置样式: </span>
		<ul class="type-choose clear">
		{code}
		if(!$_configs['typeinfo'])
		{
			$_configs['typeinfo'] = array(
							"text" => "输入框",
							"textarea" => "文本域",
							"radio" => "单选按钮",
							"checkbox" => "多选按钮",
							"select"=>"下拉列表"
				);
		}
		{/code}
			{foreach $_configs['typeinfo'] as $k=>$v}
		         <li ><input type="radio" class="data-type" name="type" {if ($k==$type)} checked="checked"{/if}  value ="{$k}" _val="{$v}" _type="{$k}"><span>{$v}</span></li>
		    {/foreach}
		</ul>
	</div>
	<div id="option_value">
		<div id="default-option"  class="form_ul_div clear default-option" >
			<span class="title">预选项: </span>
			<div class="option-contain">
				{if $formdata['dropextra']}
				{foreach $formdata['dropextra'] as $k=>$v}
				<input name="dropextra[]"  type= "text" placeholder="请输入预设选项" value="{$v}">
				{/foreach}
				{else}
				<input name="dropextra[]"  type= "text" placeholder="请输入预设选项" />
				{/if}
			</div>
			<p class="add-option">+</p>
		</div>
		<div id="default-selected" class="form_ul_div clear default-value">
			<span class="title">默认值: </span>
			<textarea name="defaultvalue" id="defaultvalue"  cols="45" rows="4" placeholder="配置样式如果为单选、下拉，则默认值必须是预选项 ＝＝ 前面的值,一行一个" />{$defaultvalue}</textarea>
		</div>
		<div id="default-selected" class="form_ul_div clear default-value">
			<span class="title">当前值: </span>
			<textarea name="value" id="value"  cols="45" rows="4" />{$value}</textarea>
		</div>
	</div>	
</li>

<li class="i">
	<div class="form_ul_div clear">
		<span class="title">有效范围: </span>
		<ul class="type-choose clear">
			<li ><input type="radio" class="islimits" name="islimits" {if !$islimits} checked="checked"{/if}  value ="0" _val="通用" _type="0"><span>通用</span></li>
			<li ><input type="radio" class="islimits" name="islimits" {if  $islimits} checked="checked"{/if}  value ="1" _val="限制" _type="1"><span>限制</span></li>	
	        <span class="error" id="title_tips" style="display:block;">*通用：不限制，限制：部分应用有效</span>	
		</ul>
	</div>
<div id="limitapps" >	
<div class="form_ul_div clear">
		<span class="title">限制应用: </span>
		<ul id="_limitapps" class="type-choose clear">
			{if $setSortBindApp}
			{foreach $setSortBindApp as $v}
		        <li><input type="checkbox" {if in_array($v[app_uniqueid],$limitapps)} checked="checked"{/if} value="{$v[app_uniqueid]}" name="limitapps[]"/><span>{code}echo $v[appname];{/code}</span></li>
		    {/foreach}
		    {/if}
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
<div class="temp-edit-buttons" style="height: 50px;">
<input type="submit" name="sub" value="{$optext}" class="edit-button submit"/>
<input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
</div>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}

<script type = "text/x-jquery-tmpl" id="option-tpl">
	<input _val="${txt}" name="dropextra[]"  type= "text" placeholder="请输入预设选项" value="${value}">
</script>
<script type = "text/x-jquery-tmpl" id="add-option-tpl">
	<input name="dropextra[]"  type= "text" placeholder="请输入预设选项" value="" />
</script>


<script>
$.globaldefault = {code} echo json_encode($formdata['dropextra']);{/code}
</script>

<script type="text/javascript">
$(function(){
	var value = $('input[name="islimits"]:checked').val();
	if(value=="0") {
		 $("#limitapps").hide();
		 $('input[name="limitapps[]"]').attr('disabled',true);
	}
	var groupmark = $('#groupmark').find("option:selected").val();
	if(!groupmark)
	{
		$('input[name="islimits"][value=1]').attr('disabled',true);
	}
   $('input[name="islimits"]').click(function(){
	 var val = $(this).attr("value");
  if(val=="0")
  {
   $("#limitapps").hide();
   $('input[name="limitapps[]"]').attr('disabled',true);
  }
  else 
	  {
	  $('#limitapps').show();
	  $('input[name="limitapps[]"]').attr('disabled' , false);
	  }
   });
});

$('#groupmark').change(function() {  
    $.ajax({
             type: "post",
             url: './run.php?mid=' + gMid + '&a=getSetSortBindApp&ajax=1',
             data: {
            	 groupmark : $(this).find("option:selected").attr('_id'),
             	},
             dataType: "json",
             success: function(data){
					 var citys = ''; 
            		 if(!data){
						$('#_limitapps').empty();   //清空resText里面的所有内容
						$('input[name="islimits"][value=1]').attr('disabled',true);
						}
            		 	else
                		 {
                   			 $('#_limitapps').empty();   //清空resText里面的所有内容
                        	 $.each(data, function(Index, value){
                        	 	citys +='<li><input type="checkbox" value='+value.app_uniqueid+' name="limitapps[]" disabled="disabled"/><span>'+value.appname+'</span></li>';
                        	 });
                        	 $('#_limitapps').html(citys);
                        	 $('input[name="islimits"][value=1]').attr('disabled',false);
                		 }
							$('input:radio[name="islimits"][value=0]').attr("checked",true);
							$("#limitapps").hide();
            			 return;                         
                      }
         });
     });


</script>



