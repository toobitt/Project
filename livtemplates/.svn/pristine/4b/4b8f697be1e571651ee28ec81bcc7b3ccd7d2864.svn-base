<div class="common-list-search" id="info_list_search">
   <span class="serach-btn"></span>
	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
		<div class="select-search">
			{code}	
				$time_css = array(
					'class' => 'transcoding down_list',
					'show' => 'time_item',
					'width' => 120,	
					'state' => 1,/*0--正常数据选择列表，1--日期选择*/
					'para'=> array('fid'=>$_INPUT['fid']),
				);
				$_INPUT['create_time'] = $_INPUT['create_time'] ? $_INPUT['create_time'] : 1;
				$audit_css = array(
					'class' => 'transcoding down_list',
					'show' => 'audit',
					'width' => 120,	
					'state' => 0,
					'para'=> array('fid'=>$_INPUT['fid']),
				);
			
				$default_audit = -1;
				$_configs['status'][$default_audit] = '所有状态';
				$_INPUT['status'] = isset($_INPUT['status']) ? $_INPUT['status'] : -1;
			{/code}						
			{template:form/search_source,status,$_INPUT['status'],$_configs['status'],$audit_css}
			{template:form/search_source,create_time,$_INPUT['create_time'],$_configs['date_search'],$time_css}
			
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