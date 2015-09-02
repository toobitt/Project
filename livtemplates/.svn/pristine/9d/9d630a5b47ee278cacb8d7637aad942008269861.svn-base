{code}
$time_css = array(
	'class' => 'transcoding down_list',
	'show' => 'time_item',
	'width' => 104,
	'state' => 1,/*0--正常数据选择列表，1--日期选择*/
);
$_INPUT['catalog_time'] = isset($_INPUT['catalog_time']) ? $_INPUT['catalog_time'] : 1;

$switch_css = array(
	'class' => 'transcoding down_list',
	'show' => 'sort_audit',
	'width' => 104,
	'state' => 0,
);
$default_audit = -1;
$_configs['catalog_switch'][$default_audit] = '编目状态';
$_INPUT['switch'] = isset($_INPUT['switch']) ? $_INPUT['switch'] : -1;

$app_style = array(
	'class' => 'colonm down_list data_time',
	'show' => 'app_style_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);
if(!isset($_INPUT['app_uniqueid']))
{
    $_INPUT['app_uniqueid'] = 0;
}
$app_info[0] = '所有应用';
foreach($get_catalog_app as $v)
{
	$app_info[$v['bundle']] = $v['name'];
}

$type_style = array(
	'class' => 'colonm down_list data_time',
	'show' => 'type_style_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);
if(!isset($_INPUT['form_style_id']))
{
    $_INPUT['form_style_id'] = 0;
}
$type_info[0] = '所有类型';
foreach($typenames as $k=>$v)
{
	$type_info[$v['id']] = $v['zh_name'];
}

$bak_css = array(
	'class' => 'transcoding down_list',
	'show' => 'bak_audit',
	'width' => 104,
	'state' => 0,
);
$default_bak = -1;
$_configs['catalog_bak'][$default_bak] = '冗余状态';
$_INPUT['bak'] = isset($_INPUT['bak']) ? $_INPUT['bak'] : -1;

$required_css = array(
	'class' => 'transcoding down_list',
	'show' => 'required_audit',
	'width' => 104,
	'state' => 0,
);
$default_required = -1;
$_configs['catalog_required'][$default_required] = '必填状态';
$_INPUT['required'] = isset($_INPUT['required']) ? $_INPUT['required'] : -1;
{/code}
<div class="common-list-search" id="info_list_search">
  <span class="serach-btn"></span>
  <form name="searchform" id="searchform" action="" method="get" target="mainwin">
    <div class="select-search">
    	{template:form/search_source,catalog_time,$_INPUT['catalog_time'],$_configs['date_search'],$time_css}
		{template:form/search_source,app_uniqueid,$_INPUT['app_uniqueid'],$app_info,$app_style}
		{template:form/search_source,form_style_id,$_INPUT['form_style_id'],$type_info,$type_style}
		{template:form/search_source,switch,$_INPUT['switch'],$_configs['catalog_switch'],$switch_css}
		{template:form/search_source,bak,$_INPUT['bak'],$_configs['catalog_bak'],$bak_css}
		{template:form/search_source,required,$_INPUT['required'],$_configs['catalog_required'],$required_css}
		
		<input type="hidden" name="a" value="show" />
		<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
		<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
     </div>
     <div class="text-search">
         <div class="button_search">
			<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
         </div>
		 {template:form/search_input,k,$_INPUT['k']}                        
     </div>
 </form>       	   	
</div>