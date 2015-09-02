{code}
{/code}
{template:head}
{css:vod_style}
{js:public_bicycle/station}
{template:list/common_list}
{css:station_style}

<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}&infrm=1" target="nodeFrame">
			<span class="left"></span>
			<span class="middle"><em class="add">新增公告</em></span>
			<span class="right"></span>
	   </a>
	</div>
	<div class="content clear">
 		<div class="f">
	    	<div class="right v_list_show">
	        	<div class="search_a" id="info_list_search">
	        	    <span class="serach-btn"></span>
	            	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                	<div class="select-search">
							{code}	
								$time_css = array(
									'class' => 'transcoding down_list',
									'show' => 'time_item',
									'width' => 104,	
									'state' => 1,/*0--正常数据选择列表，1--日期选择*/
								);
								$_INPUT['date'] = $_INPUT['date'] ? $_INPUT['date'] : 1;
								
								$audit_css = array(
									'class' => 'transcoding down_list',
									'show' => 'sort_audit',
									'width' => 104,	
									'state' => 0,
								);
								$default_audit = -1;
								$_configs['status'][$default_audit] = '所有状态';
								$_INPUT['state'] = $_INPUT['state'] ? $_INPUT['state'] : -1;
							{/code}
													
							{template:form/search_source,state,$_INPUT['state'],$_configs['status'],$audit_css}
							{template:form/search_source,date,$_INPUT['date'],$_configs['date_search'],$time_css}
							<input type="hidden" name="a" value="show" />
							<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
							<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
							<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
							<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
							<input type="hidden" id="forward_name" value="{$suobei['display_name']}">
	                	</div>
	                    <div class="text-search">
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
	            <form method="post" action="" name="pos_listform">
	               <!-- 标题 -->
                    <ul class="common-list public-list-head">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="notice-paixu common-list-item"><a class="common-list-paixu" onclick="hg_switch_order('contribute_list');"  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item open-close wd60">编辑</div>
                                <div class="common-list-item open-close wd60">删除</div>
                                <div class="common-list-item open-close wd80">站点</div>
                                <div class="common-list-item open-close wd60">状态</div>
                                <div class="common-list-item open-close wd120">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti">
                              <div class="common-list-item">标题</div>    
                            </div>
                        </li>
                    </ul>
		        	<ul class="common-list public-list hg_sortable_list" data-order_name="order_id" id="contribute_list">
						{if $list}
			       			{foreach $list as $k => $v} 
			                	{template:unit/noticelist}
			                {/foreach}
			  			{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  				{/if}
		            </ul>
			        <ul class="common-list">
				      <li class="common-list-bottom clear">
					   <div class="common-list-left">
			            	<input type="checkbox"  name="checkall"  value="infolist" title="全选" rowtag="LI" />
						    <a name="bataudit"  onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '', 'ajax');" style="cursor:pointer;">审核</a>
						    <a name="bataudit"  onclick="return hg_ajax_batchpost(this, 'back', '打回', 1, 'id', '', 'ajax');" style="cursor:pointer;">打回</a>
						    <a name="batdelete"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" style="cursor:pointer;">删除</a>
						</div>
			              {$pagelink}
			          </li>
			       </ul>
			        	
	    		</form>
	    		<div class="edit_show">
					<span class="edit_m" id="arrow_show"></span>
				<div id="edit_show"></div>
				</div>
	    	</div>
		</div>
	</div>
	<div id="infotip"  class="ordertip"></div>
</body>
{template:foot}