{code}
$time_css = array(
	'class' => 'transcoding down_list',
	'show' => 'time_item',
	'width' => 104,
	'state' => 1,/*0--正常数据选择列表，1--日期选择*/
);
$_INPUT['dateline'] = isset($_INPUT['dateline']) ? $_INPUT['dateline'] : 1;

$membermedal_style = array(
	'class' => 'colonm down_list data_time',
	'show' => 'membermedal_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);
if(!isset($_INPUT['medalid']))
{
    $_INPUT['medalid'] = -1;
}
$membermedal[-1] = '所有勋章';
if(is_array($medal_info)&&$medal_info)
{
	foreach($medal_info as $k=>$v)
	{
		$membermedal[$v['id']] = $v['name'];
	}
}
{/code}
<div class="common-list-search" id="info_list_search">
  <span class="serach-btn"></span>
  <form name="searchform" id="searchform" action="" method="get" target="mainwin">
    <div class="select-search">
    	{template:form/search_source,dateline,$_INPUT['dateline'],$_configs['date_search'],$time_css}
		{template:form/search_source,medalid,$_INPUT['medalid'],$membermedal,$membermedal_style}
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