<div class="common-list-search" id="info_list_search" style="display:none;">
   <span class="serach-btn"></span>
	<form name="searchform" id="searchform" action="" method="get">
		<div class="select-search">
			{code}
				$attr_log=array(
					'class' => 'colonm down_list data_time',
					'show' => 'bu_show',
					'width' =>104,
					'state' =>0,
				);
				$log_default = $_INPUT['type'] ? $_INPUT['type'] : -1;
				
			{/code}
			{template:form/search_source,type,$log_default,$_configs['cdn_log_type'],$attr_log}
			
		<input type="hidden" name="a" value="show" />
		<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		</div>
		<div class="text-search">
			<div class="button_search">
				<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
			</div>
			{template:form/search_input,key,$_INPUT['key']}
		</div>
	</form>
</div>