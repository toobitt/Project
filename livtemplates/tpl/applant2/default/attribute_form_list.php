{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{css:ad_style}
{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{css:colorpicker}
{css:attribute_form_list}
{js:jqueryfn/colorpicker.min}
{js:2013/hg_colorpicker}
<style>
html{background:#fff;}
</style>
<div class="boot-form" style="float:left;width:100%;">
	<div class="page-header">
		<h3 class="clearfix">
			<div class="col-sm-10">{$optext}属性</div>
			<div class="col-sm-2">
				<a class="btn btn-info" href="{$_INPUT['referto']}">
					<span class="glyphicon glyphicon-circle-arrow-left"></span> 返回前一页
				</a>
			</div>
		</h3>
	</div>
	<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="form-horizontal">
		<div class="form-group">
			<div class="col-sm-2 control-label">属性名称</div>
			<div class="col-sm-3">
				<input class="form-control" required="true" value="{$name}" name="name">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-2 control-label">属性标识</div>
			<div class="col-sm-3">
				<input class="form-control" required="true" value="{$uniqueid}" name="uniqueid">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-2 control-label">属性简介</div>
			<div class="col-sm-3">
				<textarea class="form-control" name="brief">{$brief}</textarea>
			</div>
		</div>
			{code}
			$attr_type_source = array(
			'class'  => 'attr_type down_list',
			'show'   => 'attr_type_show',
			'width'  => 200,
			'state'  => 0,
			'is_sub' => 1,
			);
			
			if(!$attr_type_id)
			{
			$attr_type_id = 0;
			}
			
			$attr_type_arr = array();
			foreach($_configs['attribute_type'] AS $_k => $_v)
			{
			if(intval($_k) == 0)
			{
			$attr_type_arr[$_k] = $_v;
			}
			else
			{
			$attr_type_arr[$_k] = $_v['name'];
			}
			}
			{/code}
		<div class="form-group">
			<div class="col-sm-2 control-label">属性类型</div>
			<div class="col-sm-3 select-attr-type-wrap">
				<select class="form-control" name="attr_type_id" value="{$attr_type_id}">
					{foreach $attr_type_arr as $attrTypeIndex=>$attrTypeValue}
					<option value="{$attrTypeIndex}" {if $attr_type_id == $attrTypeIndex}selected{/if}>{$attrTypeValue}</option>
					{/foreach}
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-2 control-label">表现样式设置</div>
			<div class="col-sm-8">
				<div class="panel panel-default">
					<div class="panel-heading">表现样式设置</div>
					<div class="panel-body set-attr-default">
					
					</div>
				</div>
			</div>
		</div>
		{template:unit/attrs_b}
		<div class="form-group">
			<div class="col-sm-2 control-label"></div>
			<div class="col-sm-8">
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<button type="button" class="btn btn-primary submit-form-btn">
					<span class="glyphicon glyphicon-ok"></span> {$optext}
				</button>
				<button type="button" class="btn btn-default" onclick="javascript:history.go(-1);">取消</button>
			</div>
		</div>
	</form>
</div>
<script>
$(function(){
	window.attrConfig = {code}echo json_encode($_configs['attribute_type']){/code};
	window.currentData = {code}echo $formdata ? json_encode($formdata) : '{}'{/code};
	var typeFlag = currentData.attr_type_id ? attrConfig[ currentData['attr_type_id'] ]['uniqueid'] : '',
		aim = $('.set-attr-default').attr('_type', typeFlag );
	var data = {
			typeFlag : typeFlag,
			style_value : currentData['style_value']
		};
	$('#attrs-tpl').tmpl(data).appendTo( aim.empty() );
	aim.appAttrs();
	$('.select-attr-type-wrap').appAttrs();
	$('.submit-form-btn').click(function(){
		$('form').submit();
	});
});
</script>
{template:foot}