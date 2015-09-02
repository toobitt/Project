<div class="common-list-search" id="info_list_search">
	<span class="serach-btn"></span>
	<form name="searchform" id="searchform" action="" method="get" target="mainwin">
		<div class="select-search">
			{code}
				if(!isset($_INPUT['status']))
				{
				    $_INPUT['status'] = -1;
				}
				if(!isset($_INPUT['date_search']))
				{
				    $_INPUT['date_search'] = 1;
				}
				$attr_status=array(
						'class' => 'colonm down_list data_time',
						'show' => 'status_show',
						'width' =>104,
						'state' =>0,
						'is_sub'=> 0,
					);
				if(!isset($_INPUT['company']))
				{
				    $_INPUT['company'] = '0';
				}
				$company = $company[0];
				$company[0] = '所有机构';
				$attr_company=array(
						'class' => 'colonm down_list data_time',
						'show' => 'company_show',
						'width' =>104,
						'state' =>0,
						'is_sub'=> 0,
					);
				if(!isset($_INPUT['date_search']))
				{
				    $_INPUT['date_search'] = '-1';
				}
				$attr_date = array(
					'class' => 'colonm down_list data_time',
					'show' => 'colonm_show',
					'width' => 104,/*列表宽度*/
					'state' => 1,/*0--正常数据选择列表，1--日期选择*/
				);
			{/code}
			{template:form/search_source,status,$_INPUT['status'],$_configs['status'],$attr_status}
			{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
			{template:form/search_source,company,$_INPUT['company'],$company,$attr_company}
			<input type="hidden" name="mid" value="{$_INPUT['mid']}" /> 
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			
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
					'state' =>2, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
					'place' =>'添加人'
				);
			{/code}
			{template:form/search_input,user_name,$_INPUT['user_name'],1,$attr_creater}
		</div>
		 
	</form>
</div>
