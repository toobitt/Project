{code}
	$all_select_style = array(
		'class' 	=> 'down_list',
		'state' 	=> 	0,
		'is_sub'	=>	1,
		'show'		=> 'city_item_show',
		'onclick'	=> 'hg_get_area_by_city(this);',
	);
	$city_default = -1;
	$city_data[$city_default] = '所有市';
	foreach($formdata AS $k => $v)
	{
		$city_data[$v['id']] = $v['city'];
	}
{/code}
{template:form/search_source,city_id,$city_default,$city_data,$all_select_style}