<?php
if ((!isset($formdata) || !is_array($formdata)) && (defined('FORMDATA') && FORMDATA)) {

} else { 
$item = $formdata;
if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
}
$column = new column();
$publish = array();
$publish['sites'] = $column->getallsites();
list($default_site, $default_name) = each($publish['sites']);
reset($publish['sites']);
$publish['items'] = $column->getAuthoredColumns($default_site);
$publish['selected_ids'] = $item['column_id'] ? $item['column_id'] : '';
$publish['selected_items'] = $column->get_selected_column_path($publish['selected_ids']);
$publish['default_site'] = each($publish['sites']);
$publish['pub_time'] = $item['pub_time'];

$hg_print_selected = array();
foreach ($publish['selected_items'] as $index => $item) {
	$hg_print_selected[$index] = array();
	$current = &$hg_print_selected[$index];
	$current['showName'] = '';
	foreach ($item as $sub_item) {
		if($sub_item['is_auth'])
		{
			$current['is_auth'] = 1;
		}
		$current['id'] = $sub_item['id'];
		$current['name'] = $sub_item['name'];
		$current['showName'] .= $sub_item['name'] . ' > ';
	}
	
	if(!$current['is_auth'])
	{
		$current['is_auth'] = 0;
	}
	$current['showName'] = substr($current['showName'], 0, -3);
	$selected_names[] = $current['name'];
}
$publish['selected_items'] = $hg_print_selected;
$publish['selected_names'] = isset($selected_names) ? implode(',', $selected_names) : '';

}
?>

<div class="common-form-pop" id="form_publish" _type="publish" style="top:-450px;position:fixed;z-index:99999;-webkit-transition:top .5s;left:50%;margin-left:-312px;">
	{template:unit/publish, 1}
	<div class="publish-box-close"></div>	
</div>