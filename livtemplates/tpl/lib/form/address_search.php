{code}
$province_style = array(
	'class' => 'province_search down_list',
	'show' => 'province_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);
if($hg_data[0]){
	$province_default = $hg_data[0];
}else{
	$province_default = $_INPUT['province_id'] ? $_INPUT['province_id'] : 0;
}
$province_data[0] = '所有省';

$city_style = array(
	'class' => 'city down_list',
	'show' => 'city_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);
if($hg_data[1]){
	$city_default = $hg_data[1];
}else{
	$city_default = $_INPUT['city_id'] ? $_INPUT['city_id'] : 0;
}
$city_data[0] = '所有市';

$area_style = array(
	'class' => 'area down_list',
	'show' => 'area_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);
if($hg_data[2])
{
	$area_default = $hg_data[2];
}else{
	$area_default = $_INPUT['city_id'] ? $_INPUT['area_id'] : 0;
}
$area_data[0] = '所有区';

{/code}
<div class="right_1 address_box">
{template:form/search_source,province_id,$province_default,$province_data,$province_style}
{template:form/search_source,city_id,$city_default,$city_data,$city_style}
{template:form/search_source,area_id,$area_default,$area_data,$area_style}
</div>