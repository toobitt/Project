	<div class="right v_list_show" style="float:none;">
		<div class="search_a" id="info_list_search">
		    <span class="serach-btn"></span>
			<form name="searchform" id="searchform" action="" method="get" style="position:relative;" onsubmit="return hg_del_keywords();">
				<div class="select-search">
				{code}
				
							$attr_style = array(
								'class' => 'colonm down_list data_time',
								'show' => 'style_show',
								'width' => 90, /*列表宽度*/
								'state' => 0,   /*0--正常数据选择列表，1--日期选择*/
							);
							
							$style_default = $_INPUT['extension_sort_id'] ? $_INPUT['extension_sort_id'] : -1;
							$style_info['-1'] = '所有分类';
							//print_r($sorts);
							foreach($sorts as $k=>$v)
							{
								$style_info[$v['extension_sort_id']] = $v['extension_sort_name'];
							}
							
                        {/code}
						{template:form/search_source,extension_sort_id,$style_default,$style_info,$attr_style}
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