{code}
$v = $formdata['tuji'];
$v['status_display'] = $v['status'];
$v['status'] = $_configs['image_upload_status'][$v['status']];
$v['pub'] = $v['column_name'];
$v['sort_name'] = $v['tuji_sort_name'];
$v['pub_url'] = unserialize($v['column_url']);
$v['img_src'] = array();
$v['fetch_one_li_model'] = true;
foreach($formdata['pics'] as $item) {
	$v['img_src'][] = $item['img_info']['host'] . $item['img_info']['dir'] . '60x45/' . $item['img_info']['filepath'] . $item['img_info']['filename'];
}
{/code}
{code}
$list_setting['status_color'] = array(
	0 => "#8ea8c8",
	1 => "#17b202",
	2 => "#f8a6a6"
);
{/code}
{template:unit/tuji_row}

			