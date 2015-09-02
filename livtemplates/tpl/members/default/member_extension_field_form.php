<?php 
/* $Id: member_extension_field_form.php 35005 2015-01-14 08:44:55Z youzhenghuan $ */
?>
{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{template:head}
{css:member_form}
{css:ad_style}
{css:style}
<script type="text/javascript">
</script>
{if $a}
	{code}
		$action = $a;
		
		if (!$formdata['extension_field'])
		{
			$action = 'create';
		}
	
	{/code}
{/if}
<div class="ad_middle">
	<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' class="ad_form h_l">
		<h2>{$optext}会员扩展字段</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<span class="title">名称：</span>
					<input type="text" name="extension_field_name" value="{$extension_field_name}" style="width:192px"/>
					<font class="important">必填</font>
				</div>
				<div class="form_ul_div">
					<span class="title">字段：</span>
					<input type="text" name="extension_field" {if $id}readonly = "readonly";{/if} value="{$extension_field}" style="width:192px"/>
					<font class="important">{if !$id}必填,提交后不可修改{else}不可修改{/if}</font>
				</div>
			</li>
<li class="i option-type">
	<div class="form_ul_div clear pre-option">
		<span class="title">字段类型: </span>
		<div style="width:335px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{if is_array($_configs['extension_field_type'])}
			{foreach $_configs['extension_field_type'] as $key => $val}
		         <li ><input type="radio" class="type" name="type" {if $key==$type} checked="checked"{/if}  value ="{$key}" _val="{code} echo $val['type_name'];{/code}" {if $id}disabled = "disabled";{/if}><span>{$val['type_name']}</span></li>
		    {/foreach}
		    {/if}
			</ul>
		</div>
		<font class="important">{if !$id}必选,提交后不可修改{else}不可修改{/if}</font>
	</div>
</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">分类：</span>
						<div class="input " style="width:210px; float:left;">
		                         <select name="extension_sort_id">
								{foreach $sorts as $k=>$v}
		                        {code}
		                            $sorts_name = $v['extension_sort_name'];
		                            $sorts_id = $v['extension_sort_id'];
		                        {/code}
                                 <option {if ($sorts_id == $extension_sort_id)} selected="selected"{/if}  value ="{$sorts_id}">{code} echo $sorts_name;{/code}</option>
		                        {/foreach}
		                        </select>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">唯一性：</span>
					<label>
						<input type="checkbox" name="is_unique" value="1" class="n-h"
						{if $is_unique}
							checked="checked"
						{/if}
					/>
						<span class="s">开启</span>
					</label>
				</div>
			</li>
		</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}