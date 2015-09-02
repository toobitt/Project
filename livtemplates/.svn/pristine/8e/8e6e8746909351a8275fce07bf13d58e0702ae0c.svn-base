<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
<script type="text/javascript">
	$(function(){
	hg_get_modelfiled = function(applyid)
	{
		if(!applyid)
		{
			return '';
		}
		url = 'getcmsmodel.php?a=getModelField&applyid='+applyid;
		hg_request_to(url, '', '', 'hg_show_modelfield');
	};
	hg_show_modelfield  = function(data)
	{
		var html = '<select name="model_field[]">';
		var fields = new Array();
		var	titles = new Array();
		var i = 0;
		for(var n in data[0])
		{

			html += '<option value="'+n+'" title="'+data[0][n]+'">'+data[0][n]+'('+n+')'+'</option>';
			titles[i] = data[0][n];
			fields[i] = n;
			i++;
		}
		html += '</select>';
		$('table tr:gt(0)').each(function(){
			id = $(this).attr('id').split('@');
			title = id[0];
			field = id[1];
			if($.inArray(title, titles) != -1)
			{
				selected = html.replace('title="'+title+'"', 'title="'+title+'" selected ="selected"');
			}
			else if($.inArray(field, fields) != -1)
			{
				selected = html.replace('value="'+field+'"', 'value="'+field+'" selected ="selected"');
			}
			else
			{
				selected = html;
			}
			$(this).children('td:last').html(selected);
		});
	};
	})
</script>
<h3 class="">
{$formdata['file_name']}模块 &gt;&gt; 发布配置
</h3>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="form">
<ul>
<li><span  class="label">发布平台: </span><label><input type="radio" name="medium_type" value="1" checked="checked"/>网站</label><label><input type="radio" name="medium_type" value="2"/>OA</label><label><input type="radio" name="medium_type" value="3"/>手机</label></li>
<li><span  class="label">发布类型: </span><label><input type="radio" name="pub_type" value="1"/>栏目</label><label><input type="radio" checked="checked" name="pub_type" value="2"/>内容</label></li>
{code}
	$hg_attr['onchange'] = 'onchange = hg_get_modelfiled(this.value)';
{/code}
<li><span  class="label">CMS模型选择: </span>{template:form/select,cms_model,$formdata['model_id'],$cms_model, $hg_attr}</li>
<li>
	<table>
	<tr>
		<th>模块字典</th><th>模型字典</th>
	</tr>
	{if($module_field)}
	{code}
		$select = $select ? $select : array(0=>'请选择');
		$hg_attr = array();
	{/code}
	{foreach $module_field as $field=>$title}
	<tr id="{$title}@{$field}">
		<td><input type="hidden" value="{$field}" name="module_field[]">{$title}({$field})</td><td>{template:form/select, model_field[], $formdata['map_field'][$field],$select}</td>
	</tr>
	{/foreach}
	{/if}
	</table>
</li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="submit" name="sub" value="{$optext}" />
</form>
{template:foot}