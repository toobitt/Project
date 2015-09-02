<div class="common-list-search" id="info_list_search">
	<span class="serach-btn"></span>
	<form name="searchform" id="searchform" action="" method="get">
		<div class="select-search">
			{code}
				$attr_date = array(
					'class' => 'colonm down_list data_time',
					'show' => 'colonm_show',
					'width' => 104,/*列表宽度*/
					'state' => 1,/*0--正常数据选择列表，1--日期选择*/
				);
				if(!$_INPUT['date_search'])
				{
					$_INPUT['date_search'] = 1;
				}

			{/code}
			{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
			
			<input type="hidden" name="a" value="get_content" /> 
			<input type="hidden" name="mid" value="{$_INPUT['mid']}" /> 
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			<input type="hidden" name="user_id" value="" />
			<input type="hidden" name="format_date" value="" />
			<input type="hidden" name="node_en" value="{$_INPUT['node_en']}" />
			<input type="hidden" name="_id" value="{$_INPUT['_id']}" /> 
			<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
		</div>
		<div class="text-search">
			<div class="button_search">
				<input type="submit" value="" name="hg_search" style="padding: 0; border: 0; margin: 0; background: none; cursor: pointer; width: 22px;" />
			</div>
			{template:form/search_input,key,$_INPUT['key']}
		</div>
	</form>
</div>
