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
		if(id)
		{
		   hg_show_opration_info(id,frame_type,frame_sort);
		}
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
	<a class="blue mr10" onclick="hg_add_code('生成邀请码')">
		<span class="left"></span>
		<span class="middle"><em class="add">新增邀请码</em></span>
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
							$attr_send = array(
								'class' => 'colonm down_list data_time',
								'show' => 'send_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							$attr_use = array(
								'class' => 'colonm down_list data_time',
								'show' => 'use_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							if (!$_INPUT['send_id']) $_INPUT['send_id'] = 0;
							if (!$_INPUT['uid']) $_INPUT['uid'] = 0;
							
							$con_send = array(
								0 => '发送状态',
								1 => '已发送',
								-1 => '未发送'
							);
							$con_use = array(
								0 => '使用状态',
								1 => '已使用',
								-1 => '未使用'
							);
						{/code}
						{template:form/search_source,send_id,$_INPUT['send_id'],$con_send,$attr_send}
						{template:form/search_source,uid,$_INPUT['uid'],$con_use,$attr_use}
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
                            	<div class="group-cz2 common-list-item open-close" style="padding-right:5px;">是否发送</div>
                            	<div class="group-cz2 common-list-item open-close" style="padding-right:5px;">状态</div>
                            	<div class="group-cz2 common-list-item open-close" style="padding-right:5px;">使用者</div>
                            	<div class="group-tz common-list-item open-close" style="padding-right:5px;">操作</div>
                                <div class="group-tjr common-list-item open-close">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item open-close group-title">邀请码</div>
					        </div>
                        </li>
                    </ul>
               		<ul class="common-list" id="newlist">
					  	{if is_array($list) && count($list)>0}
							{foreach $list as $k => $v}	
		                      {template:unit/codelist}
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
	location.reload();
}

function hg_send_call()
{
	location.reload();
}

function hg_add_code(title)
{
	$('#auth_title').html(title);
	if ($('#add_auth').css('display') == 'none')
	{
		var data = '<form id="generateCode">';
		data += '<p><label>生成个数 : </label><input type="text" name="num" value="10" /></p>';
		data += '<p><input type="button" id="createCode" value="创建" /></p>';
		data += '<p><input type="hidden" name="referto" value="{$_INPUT['referto']}" /></p>';
		data += '</form>';
		$('#auth_form').html(data);
		$('#add_auth').css({'display':'block'});
	 	$('#add_auth').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
			hg_resize_nodeFrame();
		});
	}
	else
	{
		hg_closeAuth();
	}
}

//关闭面板
function hg_closeAuth()
{
	$('#log_box').html();
	$('#add_auth').animate({'right':'120%'},'normal',function(){
		$('#add_auth').css({'display':'none','right':'0'});
		hg_resize_nodeFrame();
	});
}

$(function() {
	$('#createCode').live('click', function() {
		var refer = $('#generateCode input[name="referto"]').val();
		var url = '?mid=' + gMid;
		var num = $('#generateCode input[name="num"]').val();
		var queryData = {
			'a' : 'create',
			'num' : num
		};
		$.post(url, queryData, function(data) {
			if (data) location.href=refer;
		});
	});
});
</script>
{template:foot}