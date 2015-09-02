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
		{code}
		if(!$ui_id){
			$ui_id = 0;
		}
		if($ui_data){
			foreach($ui_data AS $_k => $_v){
				$user_inerface_attr[$_v['id']] = $_v['name'];                                  		
			}
		}
		{/code}
		<div class="form-group">
			<div class="col-sm-2 control-label">所属UI</div>
			<div class="col-sm-3">
				<select class="form-control" value="{$ui_id}" name="ui_id">
					<option value="">选择属性</option>
					{foreach $user_inerface_attr as $attrIndex=>$attrValue}
					<option value="{$attrIndex}" {if $ui_id == $attrIndex}selected{/if}>{$attrValue}</option>
					{/foreach}
				</select>
			</div>
		</div>
	      {code}
	      if(!$role_type_id)
	      {
	      $role_type_id = 0;
	      }
	
	      $ui_role_arr = array();
	      foreach($_configs['role_type'] AS $_k => $_v)
	      {
	      if($_k == -1)
	      {
	      continue;
	      }
	      $ui_role_arr[$_k] = $_v;
	      }
	      {/code}
		<div class="form-group">
			<div class="col-sm-2 control-label">所属角色</div>
			<div class="col-sm-3">
				<select class="form-control" value="{$role_type_id}" name="role_type_id">
					{foreach $ui_role_arr as $roleIndex=>$roleValue}
					<option value="{$roleIndex}" {if $role_type_id == $roleIndex}selected{/if}>{$roleValue}</option>
					{/foreach}
				</select>
			</div>
		</div>
	      {code}
	      if(!$group_id)
	      {
	      $group_id = 0;
	      }
	
	      $ui_group[0] = '选择属性';
	      if($ui_group_data)
	      {
	      foreach($ui_group_data AS $_k => $_v)
	      {
	      $ui_group[$_v['id']] = $_v['name'];                                          
	      }
	      }
	      {/code}
		<div class="form-group">
			<div class="col-sm-2 control-label">所属分组</div>
			<div class="col-sm-3">
				<select class="form-control" value="{$group_id}" name="group_id">
					{foreach $ui_group as $groupIndex=>$groupValue}
					<option value="{$groupIndex}" {if $group_id == $groupIndex}selected{/if}>{$groupValue}</option>
					{/foreach}
				</select>
			</div>
		</div>
	      {code}
	      if(!$set_value_type)
	      {
	      $set_value_type = 0;
	      }
	
	      $set_value_type_data = array(
	      0 => '选择设置方式',
	      1 => '对关联属性统一设值',
	      2 => '对关联属性分别设置',
	      );
	
	      {/code}
		<div class="form-group">
			<div class="col-sm-2 control-label">设置属性值方式</div>
			<div class="col-sm-3">
				<select class="form-control" value="{$set_value_type}" name="set_value_type">
					{foreach $set_value_type_data as $typeIndex=>$typeValue}
					<option value="{$typeIndex}" {if $set_value_type == $typeIndex}selected{/if}>{$typeValue}</option>
					{/foreach}
				</select>
			</div>
		</div>
	      {code}
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
				<select class="form-control" value="{$attr_type_id}" name="attr_type_id">
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
				<input type="hidden" name="sub" value="{$optext}" />
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