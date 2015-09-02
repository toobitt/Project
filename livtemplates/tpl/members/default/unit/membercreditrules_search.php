{code}

$opened_css = array(
	'class' => 'transcoding down_list',
	'show' => 'sort_audit',
	'width' => 104,
	'state' => 0,
);
$default_open = -1;
$_configs['credits_rules_open'][$default_open] = '所有状态';
$_INPUT['opened'] = isset($_INPUT['opened']) ? $_INPUT['opened'] : -1;

$credit_diy_css = array(
	'class' => 'transcoding down_list',
	'show' => 'credit_diy_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);
$credits_diy_type = -1;
$_configs['credits_diy_type'][$credits_diy_type] = '所有定义类型';
$_INPUT['iscustom'] = isset($_INPUT['iscustom']) ? $_INPUT['iscustom'] : -1;

$cycletype_style = array(
	'class' => 'transcoding down_list',
	'show' => 'style_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);
$default_cycletype = -1;
$_configs['cycletype'][$default_cycletype] = '所有周期';
$_INPUT['cycletype'] = isset($_INPUT['cycletype']) ? $_INPUT['cycletype'] : -1;


{/code}
<div class="common-list-search" id="info_list_search">
  <span class="serach-btn"></span>
  <form name="searchform" id="searchform" action="" method="get" target="mainwin">
    <div class="select-search">
		{template:form/search_source,opened,$_INPUT['opened'],$_configs['credits_rules_open'],$opened_css}
		{template:form/search_source,iscustom,$_INPUT['iscustom'],$_configs['credits_diy_type'],$credit_diy_css}
		{template:form/search_source,cycletype,$_INPUT['cycletype'],$_configs['cycletype'],$cycletype_style}
		
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