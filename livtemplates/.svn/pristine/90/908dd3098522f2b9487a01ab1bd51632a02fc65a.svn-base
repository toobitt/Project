{template:head}
{code}
$list = $auth_list;

if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}

if(!isset($_INPUT['auth_status']))
{
    $_INPUT['auth_status'] = -1;
}

$attr_date = array(
	'class' => 'colonm down_list data_time',
	'show' => 'colonm_show',
	'width' => 104,/*列表宽度*/
	'state' => 1,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);

$attr_source = array(
	'class' => 'transcoding down_list',
	'show' => 'auth_status_show',
	'width' => 90,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);

{/code}

{css:vod_style}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:auth}
{css:common/common_list}
{css:auth_list}
{js:common/common_list}
<script type="text/javascript">

   $(document).ready(function(){
	   
		/*拖动排序部分开始*/
		tablesort('auth_form_list','appinfo','order_id');
		$("#auth_form_list").sortable('disable');
		
   });   

</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<span type="button" class="button_6" onclick="hg_showAddAuth(0);">新增auth</span>
</div>
<div class="content clear">
 <div class="f">
 
 		<!-- 新增分类面板 开始-->
 		 <div id="add_auth"  class="single_upload">
 		 	<h2><span class="b" onclick="hg_closeAuth();"></span><span id="auth_title">新增auth</span></h2>
 		 	<div id="add_auth_tpl" class="add_collect_form">
 		 	   <div class="collect_form_top info  clear" id="auth_form"></div>
 		 	</div>
		 </div>
 		 <!-- 新增分类面板结束-->
 		 
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="right_1">
							{template:form/search_source,auth_status,$_INPUT['auth_status'],$_configs['auth_status'],$attr_source}
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
                                <div class="auth-paixu common-list-item"><a class="common-list-paixu" onclick="hg_switch_order('auth_form_list');"  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right">
                                <div class="auth-bj common-list-item open-close">编辑</div>
                                <div class="auth-bj common-list-item open-close">删除</div>
                                <div class="auth-khxs common-list-item open-close">客户显示名称</div>
                                <div class="auth-khbs common-list-item open-close">客户标识</div>
                                <div class="auth-khms common-list-item open-close">客户描述</div>
                                <div class="auth-app common-list-item open-close">appkey</div>
                                <div class="auth-gqsj common-list-item open-close">过期时间</div>
                                <div class="auth-cjsj common-list-item open-close">创建时间</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close auth-biaoti">客户名称</div>
					        </div>
                        </li>
                    </ul>
	                <ul class="common-list" id="auth_form_list">
					    {if $list}
		       			    {foreach  $list  as $k => $v}
		                      {template:unit/authlist}
		                    {/foreach}
		                {else}
							<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
							<script>hg_error_html(vodlist,1);</script>
		  				{/if}
	                </ul>
	                
		            <ul class="common-list">
				     <li class="common-list-bottom clear">
		               {$pagelink}
		            </li>
		          </ul>	
    			</form>
            </div>
        </div>
</div>
   <div id="infotip"  class="ordertip"></div>
</body>
{template:foot}