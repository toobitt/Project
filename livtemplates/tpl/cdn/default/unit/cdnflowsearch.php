<div class="common-list-search" id="info_list_search" style="display:none;">
   <span class="serach-btn"></span>
	<form name="searchform" id="searchform" action="" method="get">
		<div class="select-search">
			{code}
			    $_INPUT['date_search'] = isset($_INPUT['date_search']) ? $_INPUT['date_search'] : '1';
				$attr_bu=array(
					'class' => 'colonm down_list data_time',
					'show' => 'bu_show',
					'width' =>104,
					'state' =>0,
				);
				$attr_do=array(
					'class' => 'colonm down_list data_time',
					'show' => 'do_show',
					'width' =>104,
					'state' =>0,
				);
				$buckets['-1'] = '全部空间';
				$domain['-1'] = '全部域名';
				$bu_default = $_INPUT['bucket'] ? $_INPUT['bucket'] : -1;
				$do_default = $_INPUT['domain'] ? $_INPUT['domain'] : -1;
				if(!$domain[$_INPUT['domain']])
				{
					$do_default = '-1';
				}
				
				$attr_date = array(
					'class' => 'colonm down_list data_time',
					'show' => 'colonm_show',
					'width' => 104,/*列表宽度*/
					'state' => 1,/*0--正常数据选择列表，1--日期选择*/
				);
			{/code}
			{template:form/search_source,bucket,$bu_default,$buckets,$attr_bu}
			{template:form/search_source,domain,$do_default,$domain,$attr_do}
			{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
			
		<input type="hidden" name="a" value="show" />
		<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		</div>
		<div class="text-search">
			<div class="button_search">
				<input type="submit" value="" name="hg_search" style="padding: 0; border: 0; margin: 0; background: none; cursor: pointer; width: 22px;" />
			</div>
			{template:form/search_input,key,$_INPUT['key']}
		</div>
	</form>
</div>