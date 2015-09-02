{css:vote_style}
{js:vote}
<form name="voteMoveForm" id="voteMoveForm" action="./run.php?mid={$_INPUT['mid']}&a=voteMove" method="post" enctype='multipart/form-data' onsubmit="return hg_ajax_submit('voteMoveForm');">
	<div>
		<span class="f_l" style="margin:3px 20px;">请选择要移动到的类别：</span>
		
		<span class="title">父级分类：</span>
		{code}
			$hg_attr['node_en'] = 'vote_node';
		{/code}
		{template:unit/class,node_id,$formdata['node_id'],$node_data}
	</div>
<input style="margin-left: 20px;" type="submit" class="button_2" value="移动" />
<input type="hidden" name="id" value="{$formdata['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<form>