{template:head}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{js:2013/ajaxload_new}
{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{css:bootstrap/3.3.0/bootstrap.min}
<style>
html{background:#fff;}
.page-header{margin:0 0 20px;}
.page-header h3{line-height:34px;}
.my-form{float:left;width:100%;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="page-header">
			<h3 class="clearfix">
				<div class="col-sm-10">{$optext}预设值</div>
	  			<div class="col-sm-2">
	  				<a class="btn btn-info" href="{$_INPUT['referto']}">
						<span class="glyphicon glyphicon-circle-arrow-left"></span> 返回前一页
					</a>
	  			</div>
			</h3>
		</div>
		<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="form-horizontal my-form" role="form">
			<div class="form-group">
				<div class="col-sm-2 control-label">预设值名</div>
				<div class="col-sm-3">
					<input class="form-control" required="true" value="{$name}" name="name" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">预设值</div>
				<div class="col-sm-3">
					<input class="form-control" required="true" value="{$value_id}" name="value_id" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">属性名</div>
				<div class="col-sm-3">
					<input class="form-control current-attr-name" value="{$attr_name}" disabled/>
				</div>
				<div class="col-sm-2">
					<button type="button" class="btn btn-info show-ui-list-pop">选择属性</button>
				</div>
				<input type="hidden" name="relate_id" value="{$relate_id}"/>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">设置属性值</div>
				<div class="col-sm-8">
					<div class="panel panel-default">
						<div class="panel-heading">属性值设置</div>
						<div class="panel-body set-attr-default">
							Panel content
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2"></div>
				<div class="col-sm-3">
					<button type="button" class="btn btn-primary submit-form-btn">
						<span class="glyphicon glyphicon-ok"></span> {$optext}
					</button>
					<button type="button" class="btn btn-default" onclick="javascript:history.go(-1);">取消</button>
					<input class="btn btn-primary" type="submit" name="sub" value="{$optext}" style="display:none;"/>
					<input type="hidden" name="a" value="{$a}" />
					<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
					<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
					<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				</div>
			</div>
		</form>
	</div>
<style>
.set-attr-default{padding:15px;}
</style>
{template:unit/attrs_b}
{css:attribute_form_list}
{template:unit/ui_and_uiattrs}
<script>
$(function(){
	window.mySelectType = 'single';
	window.attrConfig = {code}echo json_encode($_configs['attribute_type']){/code};
	window.currentData = {code}echo $formdata ? json_encode($formdata) : '{}'{/code};
	console.log( window.currentData );
	var aim = $('.set-attr-default');
	(function(){	//初始化	
		var typeFlag = currentData.attr_type_id ? attrConfig[ currentData['attr_type_id'] ]['uniqueid'] : '';
		aim.attr('_type', typeFlag );
		var data = {
				typeFlag : typeFlag,
				style_value : currentData['style_value']
			};
		$('#attrs-tpl').tmpl(data).appendTo( aim.empty() );
		aim.appAttrs();
		aim.appAttrs('initWidgets');
	})();
	var uiList = new myUIList({
		el : '#ui-list-pop',
		callback : function( target, id ){
			uiAttrs.reset( id );
			uiAttrs.ajaxUiAttrs(target);
		}
	});
	var uiAttrs = new myUIAttrs({
		el : '#ui-attr-pop',
		callback : function( target, current ){
			$('.modal').removeClass('in').hide();
			var json = current.currentItem.data('json'),
				param = {
					typeFlag : json.attr_type_mark,
					style_value : false,
					isNew : true
				};
			$('.current-attr-name').val( json.attr_name );
			$('#attrs-tpl').tmpl(param).appendTo( aim.attr('_type', param.typeFlag ).empty() );
			aim.appAttrs('initWidgets');
			$('input[name="relate_id"]').val( json.id );
		}
	});
	$('.submit-form-btn').click(function(){
		$('.my-form').submit();
	});
});
</script>
{template:foot}