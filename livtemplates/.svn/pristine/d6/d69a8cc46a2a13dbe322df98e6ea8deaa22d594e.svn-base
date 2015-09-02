{template:head}
{code}
$list = $server_list;

if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}

if(!isset($_INPUT['status']))
{
    $_INPUT['status'] = -1;
}

$attr_date = array(
	'class' => 'colonm down_list data_time',
	'show' => 'colonm_show',
	'width' => 104,/*列表宽度*/
	'state' => 1,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);

{/code}
{css:vod_style}
{css:edit_video_list}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:servermonitor}
{css:common/common_list}
{css:server_list}
{js:common/common_list}
<script type="text/javascript">
   $(document).ready(function(){
		/*拖动排序部分开始*/
		tablesort('server_form_list','server','order_id');
		$("#server_form_list").sortable('disable');
		
   });   
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<span type="button" class="button_6" onclick="hg_showAddServer(0);">新增服务器</span>
</div>
<div class="content clear">
 <div class="f">
 
 		<!-- 新增新增服务器开始-->
 		 <div id="add_server"  class="single_upload">
 		 	<h2><span class="b" onclick="hg_closeServer();"></span><span id="server_title">新增服务器</span></h2>
 		 	<div id="add_auth_tpl" class="add_collect_form">
 		 	   <div class="collect_form_top info  clear" id="server_form"></div>
 		 	</div>
		 </div>
 		 <!-- 新增新增服务器结束-->
 		 
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="right_1">
							{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
							<input type="hidden" name="a" value="show" />
							<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
							<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
							<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
							<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
	                    </div>
	                    <div class="right_2">
	                    	<div class="button_search">
								<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
	                        </div>
							{template:form/search_input,k,$_INPUT['k']}
	                    </div>
                   </form>
                </div>

                <form method="post" action="" name="vod_sort_listform">
                    <!-- 标题 -->
                   <ul class="common-list">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="server-paixu common-list-item"><a class="common-list-paixu" onclick="hg_switch_order('server_form_list');"  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right">
                                <div class="server-xx common-list-item open-close">信息</div>
                                <div class="server-bj common-list-item open-close">编辑</div>
                                <div class="server-sc common-list-item open-close">删除</div>
                                <div class="server-dq common-list-item open-close">当前服务器</div>
                                <div class="server-bs common-list-item open-close">服务器标识</div>
                                <div class="server-nw common-list-item open-close">内网ip</div>
                                <div class="server-ww common-list-item open-close">外网ip</div>
                                <div class="server-dk common-list-item open-close">端口</div>
                                <div class="server-cjsj common-list-item open-close">创建时间</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close server-biaoti">服务器名称</div>
					        </div>
                        </li>
                    </ul>
	                <ul class="common-list" id="server_form_list">
					    {if $list}
		       			    {foreach  $list  as $k => $v}
		                      {template:unit/serverlist}
		                    {/foreach}
		                {else}
							<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
							<script>hg_error_html(vodlist,1);</script>
		  				{/if}
	                </ul>
	                
		            <ul class="common-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
		                   <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
					       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
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