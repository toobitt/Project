<?php 
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
						
		{/code}
	{/foreach}
{/if}
{code}//hg_pre($get_credit_type);{/code}
<script>
$.globaldefault = {code} echo json_encode($formdata['field_default']);{/code}
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<form name="editform" action="" method="post" enctype='multipart/form-data' class="ad_form h_l">
<h2>{$optext}积分规则</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">规则名称: </span>
		<input type="text" name="rname"  value="{$rname}" style="width:200px;"/>
	</div>
		<div class="form_ul_div clear info">
		<span class="title">标识: </span>
		<input type="text" name="operation"  value="{$operation}" {if $operation}readonly="true"{/if} style="width:180px;"/><span class="error" id="title_tips" >*{if $operation}禁止修改{else}必填,例如:members_members_login_login(应用_模块_方法_操作)或者newsComment(功能描述英文){/if}</span>
	</div>
</li>
<li class="i option-type">
	<div class="form_ul_div clear pre-option">
		<span class="title">自定义: </span>
			<div style="width:335px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{foreach $_configs['credits_diy_type'] as $credits_diy_id=>$credits_diy_name}
		    <li ><input type="radio" class="iscustom" name="iscustom" {if ($credits_diy_id==$iscustom)} checked="checked"{/if}  value ="{$credits_diy_id}" _val="{code} echo $credits_diy_name;{/code}"><span>{$credits_diy_name}</span></li>
		    {/foreach}
		</ul><span class="error" id="title_tips" style="display:block;">*是否允许自定义积分规则,允许此规则,则会出现在分组自定义规则列表</span>
		</div>
	
	</div>
</li>
<li class="i option-type">
	<div class="form_ul_div clear pre-option">
		<span class="title">周期级别: </span>
			<div style="width:335px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{foreach $_configs['RulesCycleLevel'] as $k=>$v}
		    <li ><input type="radio" class="cyclelevel" name="cyclelevel" {if ($k==$cyclelevel)} checked="checked"{/if}  value ="{$k}" _val="{code} echo $v['name'];{/code}"><span>{$v['name']}</span></li>
		    {/foreach}
		</ul><span class="error" id="title_tips" style="display:block;">*规则级>应用级>模块级>分类级>内容级，例如：规则级为同一积分规则在任何应用周期类型只计算一次，应用级为不同应用可以重复执行，模块级为不同应用的不同模块，分类级为每个应用的不同分类都可以重复执行，内容级为每个应用的的每个模块的每个分类的每个内容</span>
		</div>
	
	</div>
</li>
<li class="i option-type">
	<div class="form_ul_div clear pre-option">
		<span class="title">周期类型: </span>
		<div style="width:335px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{foreach $_configs['cycletype'] as $cycletype_id=>$cycletype_name}
		         <li ><input type="radio" class="cycletype" name="cycletype" {if ($cycletype_id==$cycletype)} checked="checked"{/if}  value ="{$cycletype_id}" _val="{code} echo $cycletype_name;{/code}"><span>{$cycletype_name}</span></li>
		    {/foreach}
			</ul>
		</div>
	</div>
	<div id='cycletime' >
	<div class="form_ul_div clear info" >
		<span class="title">间隔时间: </span>
		<input type="text" name="cycletime"  value="{$cycletime}" style="width:100px;"/>*当选择“整点”时，该单位为“小时”；选择“间隔分钟”时，该单位为“分钟”
	</div>
	</div>
	<div id='rewardnum' >
	<div class="form_ul_div clear info">
		<span class="title">奖励次数: </span>
		<input type="text" name="rewardnum"  value="{$rewardnum}" placeholder="周期内最多奖励次数，0为不限制" style="width:100px;" />*周期内最多奖励次数，0为不限制
	</div>
	</div>
</li>
<li class="i">
{if is_array($get_credit_type)}
	{foreach $get_credit_type as $key => $value}
	<div class="form_ul_div clear info">
		<span class="title">{$value['title']}: </span>
		<input type="text" name="credits[{$value['db_field']}]"  value="{$$value['db_field']}" style="width:100px;" />
	</div>
		{/foreach}
	{/if}
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
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>{if !$formdata['id']}<span class="error" id="title_tips" style="display:block;">注: 新增积分规则需要至相应地方嵌套规则才能使用</span>{/if}</div>
</div>
{template:foot}

<script type="text/javascript">
$(function(){
	var value = $('input[name="cycletype"]:checked').val();
	if(value!="2"&&value!="3") {
		$('#cycletime').toggle();
		$('input[name="cycletime"]').attr("disabled","disabled");
	}
	if(value=="0"){
		$('#rewardnum').toggle();
		$('input[name="rewardnum"]').attr("disabled","disabled");
	}
   $(".cycletype").click(function(){
	 var val = $(this).attr("value");
  if(val=="1"||val=="4")
  {
   $("#cycletime").hide();
   $('input[name="cycletime"]').attr("disabled","disabled");
   $("#rewardnum").show();
   $('input[name="rewardnum"]').removeAttr("disabled");
  }
  else 
	  {
	  $('#cycletime').show();
	  $('input[name="cycletime"]').removeAttr("disabled");
	  $('#rewardnum').show();
	  $('input[name="rewardnum"]').removeAttr("disabled");
	  }
	if(val=="0")
	{
	$('#cycletime').hide();
	$('input[name="cycletime"]').attr("disabled","disabled");
	$('#rewardnum').hide();
	$('input[name="rewardnum"]').attr("disabled","disabled");
	}
   });
});
</script>

