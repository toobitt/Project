{template:head}
{code}
$list = $appmanger_list;

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
{js:appmanger}
<script type="text/javascript">
   $(document).ready(function(){
		/*拖动排序部分开始*/
		tablesort('appmanger_form_list','appmanger','order_id');
		$("#appmanger_form_list").sortable('disable');
		
   });   
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<span type="button" class="button_6" onclick="hg_showAddApp(0);">新增应用配置</span>
</div>
<div class="content clear">
 <div class="f">
 		<!-- 新增应用配置开始-->
 		 <div id="add_server"  class="single_upload">
 		 	<h2><span class="b" onclick="hg_closeServer();"></span><span id="server_title">新增应用配置</span></h2>
 		 	<div id="add_auth_tpl" class="add_collect_form">
 		 	   <div class="collect_form_top info  clear" id="server_form"></div>
 		 	</div>
		 </div>
 		 <!-- 新增应用配置开始-->

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

                <div class="list_first clear"  id="list_head">
                    	<span class="left"><a class="lb" style="cursor:pointer;"  onclick="hg_switch_order('appmanger_form_list');"  title="排序模式切换/ALT+R"><em></em></a>应用名称</span>
                        <span class="right" style="width:750px"><a class="fb">编辑</a><a class="fb">删除</a><a class="fl">类型</a><a class="fl" style="width:150px;">安装目录</a><a class="fl" style="width:150px;">域名</a><a class="fl">版本</a><a class="fl">所属服务器</a><a class="fl">创建时间</a></span>
                </div>
                <form method="post" action="" name="vod_sort_listform">
	                <ul class="list" id="appmanger_form_list">
					    {if $list}
		       			    {foreach  $list  as $k => $v}
		                      {template:unit/appmangerlist}
		                    {/foreach}
		                {else}
							<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
							<script>hg_error_html(vodlist,1);</script>
		  				{/if}
		  				<li style="height:0px;padding:0;" class="clear"></li>
	                </ul>
	                
		            <div class="bottom clear">
		               <div class="left">
		                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
					       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
					   </div>
		               {$pagelink}
		            </div>	
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