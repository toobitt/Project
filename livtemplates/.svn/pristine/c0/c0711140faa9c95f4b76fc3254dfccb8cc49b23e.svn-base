<div class="search_a" id="info_list_search">
                  <span class="serach-btn"></span>
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="select-search">
						{code}
							$attr_trace_step = array(
								'class' => 'transcoding down_list',
								'show' => 'source_type_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							
							$attr_order_status = array(
								'class' => 'transcoding down_list',
								'show' => 'transcoding_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							
							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'colonm_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);
						{/code}
						
						{template:form/search_source,pay_status,$_INPUT['pay_status'],$_configs['search']['order_status'],$attr_order_status}
						{template:form/search_source,trace_step,$_INPUT['trace_step'],$_configs['search']['trace_step'],$attr_trace_step}
						{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['search']['date_search'],$attr_date}
						
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
						{template:form/search_input,title,$_INPUT['k']}                        
                    </div>
                    </form>
</div>