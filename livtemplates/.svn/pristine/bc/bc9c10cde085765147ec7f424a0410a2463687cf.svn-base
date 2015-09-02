<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z ayou $ */
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
{code}//print_r($updatetype);{/code}
<script>
$.globaldefault = {code} echo json_encode($formdata['field_default']);{/code}
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<form name="editform" action="" method="post" enctype='multipart/form-data' class="ad_form h_l">
<h2>{$optext}我的模块字段</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">字段名称: </span>
		<input type="text" name="fieldname"  value="{$fieldname}" style="width:130px;"/>
	</div>
		<div class="form_ul_div clear info">
		<span class="title">字段标识: </span>
		<input type="text" name="fieldmark"  value="{$fieldmark}" style="width:80px;" {if $fieldmark}disabled="disabled"{/if}/>
	</div>
	<div class="form_ul_div clear info">
		<span class="title">描述备注: </span><textarea name="brief" id="brief"  cols="45" rows="4" />{$brief}</textarea>
	</div>
			{code}
				$isuniquetype = array(
					'0'=>'允许',
					'1'=>'禁止',
				);
			{/code}
		<div class="form_ul_div clear pre-option">
		<span class="title">重复数据:</span>
		<div style="width:335px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{if is_array($isuniquetype)}
			{foreach $isuniquetype as $k=>$v}
		         <li ><input type="radio" class="isunique isunique_{$k}" name="isunique" {if ($isunique == $k)} checked="checked"{/if}  value ="{$k}" _val="{code} echo $v;{/code}"><span>{$v}</span></li>
		    {/foreach}
		    {/if}
			</ul>
			<span class="error" id="title_tips" style="display:block;">*当某个模块开启重复数据过滤时,此选项配置生效，否则无效;注：支持模块内多字段组合限制唯一</span>
		</div>
	</div>
	
		{code}
				$addStatusType = array(
					'0'=>'用户传值',
					'1'=>'系统默认',
					'2'=>'系统时间',
					'3'=>'用户IP',
				);
			{/code}
		<div class="form_ul_div clear pre-option">
		<span class="title">添加方式:</span>
		<div style="width:335px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{if is_array($addStatusType)}
			{foreach $addStatusType as $k=>$v}
		         <li ><input type="radio" class="addstatus addstatus_{$k}" name="addstatus" {if ($addstatus == $k)} checked="checked"{/if}  value ="{$k}" _val="{code} echo $v;{/code}"><span>{$v}</span></li>
		    {/foreach}
		    {/if}
			</ul>
			<span class="error" id="title_tips" style="display:block;">*字段传值类型</span>
		</div>
	</div>
			{code}
				$CmySetDisplay = array(
					'1'=>'是',
					'0'=>'否',
				);
			{/code}
		<div class="form_ul_div clear pre-option">
		<span class="title">必填项:</span>
		<div style="width:335px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{if is_array($CmySetDisplay)}
			{foreach $CmySetDisplay as $k=>$v}
		         <li ><input type="radio" class="isrequired" name="isrequired" {if ($isrequired == $k)} checked="checked"{/if}  value ="{$k}" _val="{code} echo $v;{/code}"><span>{$v}</span></li>
		    {/foreach}
		    {/if}
			</ul>
			<span class="error" id="title_tips" style="display:block;">*是否为必填字段检测，仅当传值类型为“用户传值”时生效</span>
		</div>
	</div>
		<div class="form_ul_div clear pre-option">
		<span class="title">检索项:</span>
		<div style="width:335px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{if is_array($CmySetDisplay)}
			{foreach $CmySetDisplay as $k=>$v}
		         <li ><input type="radio" class="issearch issearch_{$k}" name="issearch"  {if ($issearch==$k)} checked="checked"{/if}  value ="{$k}" _val="{code} echo $v;{/code}"><span>{$v}</span></li>
		    {/foreach}
		    {/if}
			</ul>
			<span class="error" id="title_tips" style="display:block;">*是否允许快速检索</span>
		</div>
	</div>
		<div class="form_ul_div clear info">
		<span class="title">默认值: </span><textarea name="defaultsvalue" id="defaultsvalue"  cols="45" rows="4" />{$defaultsvalue}</textarea> <span class="error" id="title_tips" style="display:block;">*仅当用户未传值(需设置非必填项，否则不传会报错) 或者 传值方式设置为“系统默认”时有效</span>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">绑定模块: </span>
		<div style="display: -webkit-box;">
			<ul class="type-choose">
			{foreach $mySetInfo as $key => $value}
		         {code}
		            $msid = $value['msid'];
			        $mstitle = $value['mstitle'];
			        $flag = 0;
			        if(is_array($bindms))
			        {
			            if (in_array($msid,$bindms)) $flag=1;
			        }
			        else if ($bindms)
			        {
			            if($msid == $bindms) $flag = 1;
			        }
		         {/code}
		         <li><input type="checkbox" {if $flag} checked="checked"{/if} value="{$msid}" size="50" name="bindms[]"/><span>{code}echo $mstitle;{/code}</span></li>
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
<!--  
<script>
$(function(){
	$('.isrequired').on('click' , function( event ){
		var target = $( event.currentTarget ),
			val = target.val();
		if( val == '0' ){
			$('.issearch').attr('disabled' , true);
			$('.issearch_0').attr('checked' , true );
			$('.issearch_1').attr('checked' , false );
		}else{
			$('.issearch').attr('disabled' , false);
		}

	})
});
</script>
-->
{template:foot}

