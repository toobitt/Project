<?php 
/* $Id: program_list_day_single.php 5343 2011-12-06 05:38:54Z repheal $ */
?>
{code}
if(is_array($formdata) && !$formdata['tips'])
{
	$default =  -1;
	$item_source = array(
		'class' => 'down_lists',
		'show' => 'item_shows_'.$id,
		'width' => 83,/*列表宽度*/		
		'state' => 0, /*0--正常数据选择列表，1--日期选择*/
		'is_sub'=>1,
		'onclick'=>'hg_check_source(' . $id . ',' . $default . ');',
		'more' => $id,
	);
	$source[$default] = '录播分类';
	foreach($formdata as $k =>$v)
	{
		$source[$k] = $v;
	}

{/code}
{template:form/search_source,item,$default,$source,$item_source}
{code}
}
else
{
	echo 0;
}
{/code}