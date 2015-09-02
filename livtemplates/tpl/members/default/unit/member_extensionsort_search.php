	<div class="right v_list_show" style="float:none;">
		<div class="search_a" id="info_list_search">
		    <span class="serach-btn"></span>
			<form name="searchform" id="searchform" action="" method="get" style="position:relative;" onsubmit="return hg_del_keywords();">
				<div class="select-search">
					<input type="hidden" name="a" value="show" />
					<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
					<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				</div>
				<div class="text-search">
					<div class="button_search">
						<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
					</div>
					{template:form/search_input,k,$_INPUT['k']}                        
				</div>
			</form>
		</div>
	</div>