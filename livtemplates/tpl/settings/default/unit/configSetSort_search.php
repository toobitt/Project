{code}
$setApp_style = array(
				'class' => 'colonm down_list data_time',
				'show' => 'setApp_style_show',
				'width' => 104,/*列表宽度*/
				'state' => 0,/*0--正常数据选择列表，1--日期选择*/
				'is_sub'=> 0,
			);
if(!isset($_INPUT['app_uniqueid']))
{
	 $_INPUT['app_uniqueid'] = -1;
}

$setApp[-1] = '所有应用';
if(is_array($setAppInfo)&&$setAppInfo)
{
	foreach($setAppInfo as $k=>$v)
	{
		$setApp[$v['app_uniqueid']] = $v['appname'];
	}
}
{/code}
<div class="common-list-search" id="info_list_search">
 <span class="serach-btn"></span> 
  <form name="searchform" id="searchform" action="" method="get" target="mainwin">
    <div class="select-search">
	{template:form/search_source,app_uniqueid,$_INPUT['app_uniqueid'],$setApp,$setApp_style}	
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