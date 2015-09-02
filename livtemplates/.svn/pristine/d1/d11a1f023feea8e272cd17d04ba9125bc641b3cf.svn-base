<div class="common-list-search" id="info_list_search">
   <span class="serach-btn"></span>
	<form name="searchform" id="searchform" action="" method="get">
		<div class="select-search">
			{code}	
			$item_css = array(
				'class' => 'transcoding down_list',
				'show' => 'sort',
				'width' => 120,	
				'state' => 0,
			);
			$default_sort = -1;
			$appendSort[0][$default_sort] = '所有分类';
			$_INPUT['maga_sort'] = $_INPUT['maga_sort'] ? $_INPUT['maga_sort'] : -1;
									
			$time_css = array(
				'class' => 'transcoding down_list',
				'show' => 'time_item',
				'width' => 104,	
				'state' => 1,/*0--正常数据选择列表，1--日期选择*/
			);
			$_INPUT['maga_time'] = $_INPUT['maga_time'] ? $_INPUT['maga_time'] : 1;
			$audit_css = array(
				'class' => 'transcoding down_list',
				'show' => 'sort_audit',
				'width' => 120,	
				'state' => 0,
			);
			$default_audit = -1;
			$_configs['audit'][$default_audit] = '所有状态';
			$_INPUT['maga_audit'] = isset($_INPUT['maga_audit']) ? $_INPUT['maga_audit'] : -1;
		{/code}						
			<!-- {template:form/search_source,maga_sort,$_INPUT['maga_sort'],$appendSort[0],$item_css} -->
			<!-- {template:form/search_source,maga_audit,$_INPUT['maga_audit'],$_configs['audit'],$audit_css} -->
			{template:form/search_source,maga_time,$_INPUT['maga_time'],$_configs['date_search'],$time_css}
			<input type="hidden" name="a" value="get_article" /> 
			<input type="hidden" name="mid" value="{$_INPUT['mid']}" /> 
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" /> 
			<input type="hidden" name="_id" value="{$_INPUT['_id']}" /> 
			<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
			<input type="hidden" name="issue_id" value="{$_INPUT['issue_id']}" />
			<input type="hidden" name="maga_id" value="{$_INPUT['maga_id']}" />
		</div>
		<div class="text-search">
			<div class="button_search">
				<input type="submit" value="" name="hg_search" style="padding: 0; border: 0; margin: 0; background: none; cursor: pointer; width: 22px;" />
			</div>
			{template:form/search_input,k,$_INPUT['k']}
		</div>
		<div class="custom-search">
			{code}
				$attr_creater = array(
					'class' => 'custom-item',
					'state' =>0, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
					'place' =>'添加人'
				);
			{/code}
			{template:form/search_input,user_name,$_INPUT['user_name'],1,$attr_creater}
		</div>
		<div class="custom-search">
			{code}
				$attr_author = array(
					'class' => 'custom-item',
					'state' =>0, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
					'search_btn' => 1, /*添加搜索按钮*/
					'place' =>'作者'
				);
			{/code}
			{template:form/search_input,author,$_INPUT['author'],1,$attr_author}
		</div>
	</form>
</div>
