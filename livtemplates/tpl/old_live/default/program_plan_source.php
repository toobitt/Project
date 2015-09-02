<?php 
/* $Id: program_list_week.php 5656 2011-12-10 09:30:02Z repheal $ */
?>
{code}
$default = -1;
$item_source = array(
	'class' => 'down_lists',
	'show' => 'item_shows',
	'width' => 83,	
	'state' => 0, /*0--正常数据选择列表，1--日期选择*/
	'is_sub'=>1,
);
$program_item[-1] = '录播分类';
foreach($formdata as $k =>$v)
{
	$program_item[$k] = $v;
}
{/code}
{template:form/search_source,item,$default,$program_item,$item_source}