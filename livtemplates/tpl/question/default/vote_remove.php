{css:vote_style}
{js:vote}
<form name="voteMoveForm" id="voteMoveForm" action="./run.php?mid={$_INPUT['mid']}&a=voteMove" method="post" enctype='multipart/form-data' onsubmit="return hg_ajax_submit('voteMoveForm');">
	<div>
	<span class="f_l" style="margin:3px 20px;">请选择要移动到的类别：</span>
	{code}
		$group_id = $formdata['group_id'];
		$item_source = array(
			'class' => 'down_list i f_l',
			'show' => 'item_shows_',
			'width' => 100,/*列表宽度*/		
			'state' => 0, /*0--正常数据选择列表，1--日期选择*/
			'is_sub'=>1,
			'onclick'=>'',
		);
		$default = $group_id ? $group_id : -1;
		$gname[$default] = '选择分类';
		foreach($group_info AS $k =>$v)
		{
			$gname[$v['id']] = $v['name'];
		}
	{/code}
	{template:form/search_source,group_id,$default,$gname,$item_source}
	</div>
<input style="margin-left: 20px;" type="submit" class="button_2" value="移动" />
<input type="hidden" name="id" value="{$formdata['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<form>