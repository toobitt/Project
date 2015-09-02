{template:head}
{template:list/common_list}
{css:vod_style}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}&infrm=1" target="nodeFrame">
			<span class="left"></span>
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
									'width' => 120,	
									'state' => 1,/*0--正常数据选择列表，1--日期选择*/
								);
								$_INPUT['archive_sort_time'] = $_INPUT['archive_sort_time'] ? $_INPUT['archive_sort_time'] : 1;
							{/code}
							{template:form/search_source,archive_sort_time,$_INPUT['archive_sort_time'],$_configs['date_search'],$time_css}
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
							{template:form/search_input,k,$_INPUT['k']}                        
	                    </div>
	               	</form>
	            </div>
	            <form method="post" action="" name="pos_listform">
	               <!-- 标题 -->
                    <ul class="common-list public-list-head">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="archive-paixu common-list-item"><a class="common-list-paixu" onclick="hg_switch_order('archive_list');"  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right">
                                <div class="archive-sc common-list-item open-close wd60">删除</div>
                                <div class="archive-hy common-list-item open-close wd60">还原</div>
                                <div class="archive-ssyy common-list-item open-close wd100">所属应用</div>
                                <div class="archive-gdr common-list-item open-close wd100">归档人</div>
                                <div class="archive-gdip common-list-item open-close wd100">归档ip</div>
                           		<div class="archive-gdsj common-list-item open-close wd150">归档时间</div>
                            </div>
                            <div class="common-list-biaoti">
                              <div class="common-list-item">归档名称</div>    
                            </div>
                        </li>
                    </ul>
		        	<ul class="common-list public-list hg_sortable_list" data-order_name="order_id" id="archive_list">
						{if $archive_list}
			       			{foreach $archive_list as $k => $v} 
			                	{template:unit/archive_list}
			                {/foreach}
			  			{/if}
		            </ul>
			        <ul class="common-list">
				      <li class="common-list-bottom clear">
					   <div class="common-list-left">
			            	<input type="checkbox"  name="checkall"  value="infolist" title="全选" rowtag="LI" />
						   	<a name="bataudit"  onclick="return hg_ajax_batchpost(this, 'recover_archive', '还原', 1, 'id', '', 'ajax');" style="cursor:pointer;">还原</a>
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
	
	<!--发布-->
	<div id="infotip"  class="ordertip"></div>
	<!-- 图片上传表单 -->
	<from id="uploadForm" style="display:none;" method="post" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data">
		<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		<input type="hidden" name="a" value="upload" />
		<input type="hidden" name="content_id" />
		<input type="file" name="Filedata" />
   </from>
</body>
{template:foot}