{code}
$time_css = array(
	'class' => 'transcoding down_list',
	'show' => 'time_item',
	'width' => 104,
	'state' => 1,/*0--正常数据选择列表，1--日期选择*/
);
$_INPUT['date_search'] = isset($_INPUT['date_search']) ? $_INPUT['date_search'] : 1;

$status_css = array(
	'class' => 'transcoding down_list',
	'show' => 'sort_audit',
	'width' => 104,
	'state' => 0,
);
$default_audit = -1;
$_configs['state'][$default_audit] = '所有状态';
$_INPUT['state'] = isset($_INPUT['state']) ? $_INPUT['state'] : -1;
{/code}
<div class="common-list-search" id="info_list_search">
  <span class="serach-btn"></span>
  <form name="searchform" id="searchform" action="" method="get" target="mainwin">
    <div class="select-search">
    	{template:form/search_source,state,$_INPUT['state'],$_configs['state'],$status_css}
    	{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$time_css}
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