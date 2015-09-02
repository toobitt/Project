{code}
	$attr_source = array(
		'class' => 'transcoding down_list',
		'show' =>  'tuhji_sort_show',
		'width' => 100,/*列表宽度*/
		'state' => 0,/*0--正常数据选择列表，1--日期选择*/
		'is_sub'=> 1
	);
{/code}
{css:ad_style}
{css:column_node}
{js:column_node}
<form action="run.php?mid={$_INPUT['mid']}"   method="post" enctype="multipart/form-data" name="tuji_sortform" id="tuji_sortform"  onsubmit="return hg_ajax_submit('tuji_sortform')" >
<!--
	<div style="float:left;margin-top:5px;">选择要移动的类别:</div>
	<div style="margin-left:10px;float:left;">
	{template:form/search_source,tuji_sort,$formdata['info']['tuji_sort_id'],$formdata['sort'],$attr_source}
	</div>
-->
	<ul class="form_ul">
		<li class="i">
			<div class="form_ul_div clear" style="border:0px;">
			<div style="float:left;margin-top:10px;margin-bottom:10px;">选择要移动到类别:</div>
			{code}
				$hg_attr['node_en'] = 'tuji_node';
				$hg_attr['_callcounter'] = 2;
			{/code}
			{template:unit/class,tuji_sort,$formdata['tuji_sort_id'],$node_data}
			</div>
			</div>
		</li>
	</ul>
	<input type="submit" class="button_6" value="移动" style="float:right;margin-top:20px;" />
	<input type="hidden" name="a" value="update_move" />
	<input type="hidden" name="id" value="{$formdata[id]}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>