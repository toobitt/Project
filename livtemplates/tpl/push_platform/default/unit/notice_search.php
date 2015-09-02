<div class="search_a" id="info_list_search">
	            	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                	<div class="right_1">
							{code}	
								$time_css = array(
									'class' => 'transcoding down_list',
									'show' => 'time_item',
									'width' => 104,	
									'state' => 1,/*0--正常数据选择列表，1--日期选择*/
								);
								$_INPUT['admin_time'] = $_INPUT['admin_time'] ? $_INPUT['admin_time'] : 1;
								
								$audit_css = array(
									'class' => 'transcoding down_list',
									'show' => 'sort_audit',
									'width' => 104,	
									'state' => 0,
								);
								$default = -1;
								$_configs['notice_state'][$default] = '全部状态';
								$_INPUT['notice_state'] = $_INPUT['notice_state'] ? $_INPUT['notice_state'] : -1;
							{/code}
							{template:form/search_source,notice_state,$_INPUT['notice_state'],$_configs['notice_state'],$audit_css}
							{template:form/search_source,admin_time,$_INPUT['admin_time'],$_configs['date_search'],$time_css}
							<input type="hidden" name="a" value="show" />
							<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
							<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	                	</div>
	                    <div class="right_2">
	                    	<div class="button_search">
								<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
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