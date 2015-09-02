{template:head}
{js:underscore}
{code}
{/code}
{css:ad_style}
{css:column_node}
{code}
foreach($fuctions[0] as $k=>$v)
{	
	$fuc_names[$k] = $v['brief'];
	$fuc_paras[$k] = $v['para'];
}
$fuc_values = unserialize($formdata['fuction_value']);
{/code}
<script>
</script>
<script type="tpl" id="param_tpl">
<span class='title'>参数名称: </span>
<input type='text' name='default_name[]' value='<%= brief %>' style='width:80px;' class='title'>&nbsp;&nbsp;
默认值: <input type='text' name='default_value[]' value='<%= default_value %>' style='width:80px;' class='title'>&nbsp;
<br>
</script>
<script>
gParamCache = {code}echo json_encode($fuc_paras);{/code};
var gParam_tpl = _.template($('#param_tpl').html());

function getFunctionParamHtml(data) {
	var html = '', i, l, v;
	for (i = 0, l = data.length; i < l; i++) {
		v = data[i];
		html += gParam_tpl(v);
	}
	return html;
}
	
function switchParam(a) {
	var id = $(a).attr('attrid');
	var param = gParamCache[id] || [];
	var box = $('#mode_functions_box');
	box.html( getFunctionParamHtml(param) );
}
$(function ($) {
	$('form').submit(function (e) {
		var name = $(this).find('input[name="name"]').val(),
			id = $(this).find('input[name="id"]').val();
		parent.updata_id(name, id);
	})
});
</script>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" class="ad_form h_l">
			{if $_INPUT['id']}
				<h2>编辑返回参数信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span class="title">参数名：</span>
								<input type="text" value="{$formdata['name']}" name='name' style="width:200px;"/>
								<font class="important"></font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">参数描述：</span>
								<input type="text" value="{$formdata['brief']}" name='brief' style="width:200px;"/>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">参数类型：</span>
								<select name="variable_type">
								<option value='1' {if $formdata['type']==1}selected{/if}>
								直接打印
								</option>
								<option value='2' {if $formdata['type']==2}selected{/if}>
								循环数组
								</option>
								</select>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">参数默认值：</span>
								<input type="text" value="{$formdata['value']}" name='var_value' style="width:200px;"/>
								<font class="important">默认值为直接输出的值</font>
							</div>
						</li>
						{code}
							$fuctions_arr = array(
								'class' => 'transcoding down_list',
								'show'  => 'select_fuc',
								'width' => 180,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
								'onclick' => 'switchParam(this);'
							);
							$fuc_names['-1'] = "-请选择-";
							$mode_fuctions = $formdata['mode_fuction'] ? $formdata['mode_fuction'] : -1;
							$selected_paras = $mode_fuctions == -1 ? array() : 	$fuc_paras[$mode_fuctions];
						{/code}
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">函数：</span>
								{template:form/search_source,mode_fuction,$mode_fuctions,$fuc_names,$fuctions_arr}	
							</div>
						</li>
						<div class='form_ul_div clear' id="mode_functions_box">
						{foreach $selected_paras as $k=>$v}
							<span class='title'>参数名称: </span><input type='text' name='default_name[]' value='{$v["brief"]}' style='width:80px;' class='title'>&nbsp;&nbsp;
							默认值: <input type='text' name='default_value[]' value='{$fuc_values[$k]}' style='width:80px;' class='title'>&nbsp;
						<br>
						{/foreach}
						</div>
					</ul>
					{else}
				{/if}
				<input type="hidden" name="a" value="update_out_variable" />
				<input type="hidden" name="id" value="{$_INPUT['id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="fid" value="{$_INPUT['fid']}" />
				<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
				<br />
				<input type="submit" name="sub" value="更新" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	</div>
