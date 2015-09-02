<div class="common-list-search" id="info_list_search" style="display:none;">
	<span class="serach-btn"></span>
	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
		<div class="select-search">
					{template:site/new_site_search, site_id, $_INPUT['site_id'] , $hg_sites}
					<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
					<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
					<input type="hidden" name="nav" value="{$_INPUT['nav']}" />
					<input type="hidden" id="default-site" value="{$_INPUT['site_id']}" />
		</div>
		<div class="text-search">
			<div class="button_search">
				<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
			</div>
			{template:form/search_input,k,$_INPUT['k']}                        
		</div>
	</form>        
</div>
