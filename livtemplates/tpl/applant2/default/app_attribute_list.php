<?php 
/* $Id: group_list.php 9410 2012-05-22 07:43:34Z lijiaying $ */
?>
{template:head}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{css:common/common_list}
{css:group_list}
{js:common/common_list}
<script type="text/javascript">
var id = '{$id}';
var frame_type = "{$_INPUT['_type']}";
var frame_sort = "{$_INPUT['_id']}";
$(document).ready(function(){
	if (id) hg_show_opration_info(id,frame_type,frame_sort);
});
</script>
<style type="text/css">
.columnList th,.columnList td {padding:5px; text-align:center; border-bottom:1px solid #EEE;}
.columnList tfoot td {border:0;}
.columnBtn {cursor:pointer;}
#auth_title {font-size:18px;}
.columnForm td {padding:5px;}
.columnForm h3 {font-size:16px; font-weight:bold; margin-left:5px; margin-bottom:10px;}
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
	<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}">
		<span class="left"></span>
		<span class="middle"><em class="add">新增属性</em></span>
		<span class="right"></span>
	</a>
</div>
<div class="content clear">
	<div class="f">
		<!-- 新增分类面板 开始-->
 		 <div id="add_auth" class="single_upload">
 		 	<h2><span class="b" onclick="hg_closeAuth();"></span><span id="auth_title">推送</span></h2>
 		 	<div id="add_auth_tpl" class="add_collect_form">
 		 	   <div class="collect_form_top info  clear" id="auth_form"></div>
 		 	</div>
		 </div>
 	    <!-- 新增分类面板结束-->
          <div class="right v_list_show">
		  	<div id="infotip"  class="ordertip"></div>
	        <div id="getimgtip"  class="ordertip"></div>
			<div class="search_a" id="info_list_search">
			    <span class="serach-btn"></span>
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="select-search">
						{code}
							$attr_flag = array(
								'class' => 'colonm down_list data_time',
								'show' => 'flag_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							$attr_type = array(
								'class' => 'colonm down_list data_time',
								'show' => 'type_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							if (!$_INPUT['flag']) $_INPUT['flag'] = 0;
							if (!$_INPUT['type']) $_INPUT['type'] = 0;
							$flag_info = array(
								'0' => '所有属性',
								'1' => '界面属性',
								'2' => '模板属性'
							);
							array_unshift($_configs['attr_type'], '所有类型');
						{/code}
						{template:form/search_source,flag,$_INPUT['flag'],$flag_info,$attr_flag}
						{template:form/search_source,type,$_INPUT['type'],$_configs['attr_type'],$attr_type}
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
              <form method="post" action="" name="listform">
                    <!-- 标题 -->
                    <ul class="common-list">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="group-paixu common-list-item"><a class="common-list-paixu" style="cursor:pointer;"  {if !$list['colname']}onclick="hg_switch_order('vodlist');"{/if}  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right">
                            	<div class="group-cz common-list-item open-close" style="width:280px;">属性描述</div>
                            	<div class="group-ht common-list-item open-close" style="width:100px; padding-right:0px;">属性类型</div>
                            	<div class="group-cz2 common-list-item open-close">默认值</div>
                            	<div class="group-ht common-list-item open-close" style="width:80px; padding-right:5px;">属性对象</div>
                            	<div class="group-tjr common-list-item open-close" style="padding-right:5px;">操作</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item open-close group-title">属性名称</div>
					        </div>
                        </li>
                    </ul>
               		<ul class="common-list" id="newlist">
					  	{if is_array($list) && count($list)>0}
							{foreach $list as $k => $v}	
		                      {template:unit/attributelist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;margin:0 10px">没有您要找的内容！</p>
						<script>hg_error_html(newlist,1);</script>
		  				{/if}
                	</ul>
	            <ul class="common-list">
	              <li class="common-list-bottom clear">
	                <div class="common-list-left">
	                   <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
	                   <!--
	                   <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '', 'ajax', '');" name="bataudit">审核</a>
	                   <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'back', '打回', 1, 'id', '', 'ajax', '');" name="batback">打回</a>
	                   -->
				       <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="batdelete">删除</a>
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
</body>
<script type="text/javascript">
function hg_delete_call(id)
{
	var ids = id.split(',');
	for (var i = 0; i < ids.length; i++)
	{
		$('#r_' + ids[i]).remove();
	}
}
</script>
{template:foot}