{code}
	$all_select_style = array(
		'class' 	=> 'down_list',
		'state' 	=> 	0,
		'is_sub'	=>	1,
		'show'		=> 'area_item_show',
	);
	$area_default = -1;
	$area_data[$area_default] = '所有区';
	foreach($formdata AS $k => $v)
	{
		$area_data[$v['id']] = $v['area'];
	}
{/code}
{template:form/search_source,area_id,$area_default,$area_data,$all_select_style}