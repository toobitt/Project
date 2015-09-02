{code}
	/*新增集合面板里用于搜索的分类控件样式*/
	$item_sea_collect_addsort = array(
		'class' => 'transcoding down_list',
		'show' => 'sea_collect_addsort_show',
		'width' => 79,	
		'state' => 0, 
		'is_sub'=>1,
	);
	
	$leixing_sort_default = -1;
	$collect_lexing_sorts[$leixing_sort_default] = '全部分类';
	foreach($formdata as $k => $v)
	{
		$collect_lexing_sorts[$v['id']] = $v['name'];
	}
	
{/code}
{template:form/search_source,sea_add_collect_id,$leixing_sort_default,$collect_lexing_sorts,$item_sea_collect_addsort}