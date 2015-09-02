<div class="common-list-search" id="info_list_search">
	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
		<div class="select-search">
		{code}
					$attr_sorts = array(
						'class' => 'transcoding down_list',
						'show' => 'sort_type_show',
						'width' => 104,/*列表宽度*/
						'state' => 0,/*0--正常数据选择列表，1--日期选择*/
					);
					$sorts[0][-1] = '全部分类';
					$attr_default = $_INPUT['sort_id'] ? $_INPUT['sort_id'] : -1;
					
					//获取所有站点
					$hg_sites = $publish->getallsites();
					
					$attr_site = array(
						'class'  => 'colonm down_list date_time',
						'show'   => 'app_show',
						'width'  => 104,
						'state'  => 0,
					);
					$hg_sites[-1] = '所有站点';
				{/code}	
				<!--{template:form/search_source,site_id,$_INPUT['site_id'],$hg_sites,$attr_site}-->
				{template:form/search_source,sort_id,$attr_default,$sorts[0],$attr_sorts}
		<input type="hidden" name="a" value="show" />
		<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
		</div>
		<div class="text-search">
			<div class="button_search">
				<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
			</div>
			{template:form/search_input,k,$_INPUT['k']}                        
		</div>
	</form>
</div>