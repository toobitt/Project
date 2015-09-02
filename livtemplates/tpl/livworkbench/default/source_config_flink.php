{template:head}
{css:ad_style}
{code}
$info = $formdata['info'];
$column = serialize($formdata['dbcolumn']);
$field_info = serialize($formdata['field']);
$fcon = $formdata['field_condition'];
{/code}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_web_site first"><em></em><a>来源配置</a></li>
			<li class=" dq"><em></em><a>字段关联</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap clear" >
<div class="ad_middle" style="width:70%;">
<h2>字段关联</h2>
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<ul class="form_ul">
	<li class="i">
		{foreach $formdata['field'] as $k=>$v}
			{code}
				$attr_colinfo = array(
					'class' => 'transcoding down_list',
					'show'  => 'dbinfo_show_'.$k,
					'width' => 200,/*列表宽度*/
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
				);
				$formdata['dbcolumn'][-1] = '- 请选择 -';
				$finfo = $fcon[$k]['v'] ? $fcon[$k]['v'] : -1;
				$field = $k;
				$input = 'field_'.$k;
				$fvalue = $fcon[$k][$input];
				if($k == 'indexpic')
				{
					$flag = 1;
				}
				else
				{
					$flag = 0;
				}
			{/code}	
			<div style='width:100%;height:30px;'>
				<div style="float:left;width:120px;">{$v}:</div>
				<div style="float:left;width:300px;">{template:form/search_source,$field,$finfo,$formdata['dbcolumn'],$attr_colinfo}</div>
				{if $flag == 1}
				路径计算<input type="text" name='{$input}' value="{$fvalue}" size="40"/>
				{else}
				<input type="checkbox" name='{$input}' style='margin-left:20px;' value='1' {if $fvalue == 1} checked='checked'{/if} />
				<span style='width:120px;height:30px;'>是否转化成时间戳</span>
				{/if}
			</div>
		{/foreach}
	</li>
</ul>
<input type="hidden" name="a" value="flink_edit" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="column" value='{$column}' />
<input type="hidden" name="field_info" value='{$field_info}' />
<input type="hidden" name="html" value="true"/>
<input type="hidden" name="referto" value="{$_INPUT['referto']}" class="button_6_14"/>
<br>
<input type="submit" name="sub" value="确定" class="button_6_14"/>
<input type="button" value="返回" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version"><h2><a href="./source_config.php">返回前一页</a></h2></div>
</div>
{template:foot}