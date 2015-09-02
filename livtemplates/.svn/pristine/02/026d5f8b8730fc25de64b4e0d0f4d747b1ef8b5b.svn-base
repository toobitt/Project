{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{css:ad_style}
{if is_array($formdata)}
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
			<div class="col-sm-10">设置<font color="red">{$name}</font>属性的值</div>
			<div class="col-sm-2">
				<a class="btn btn-info" href="{$_INPUT['referto']}">
					<span class="glyphicon glyphicon-circle-arrow-left"></span> 返回前一页
				</a>
			</div>
		</h3>
	</div>
	<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="form-horizontal">
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
				<input type="hidden" name="a" value="do_set_value" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="attr_type_id" value="{$attr_type_id}" />
				<button type="button" class="btn btn-primary submit-form-btn">
					<span class="glyphicon glyphicon-ok"></span> 保存
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
	$('.submit-form-btn').click(function(){
		$('form').submit();
	});
});
</script>
{template:foot}