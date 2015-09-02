<?php 
/* $Id: email_settings_list.php 14297 2012-11-03 07:56:42Z lijiaying $ */
?>
{template:head}
{js:email}
{css:vod_style}
{js:vod_opration}
{css:edit_video_list}
{css:common/common_list}
{css:list_style}
{js:common/common_list}
{code}
/*hg_pre($list);*/
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}

if(!isset($_INPUT['status']))
{
    $_INPUT['status'] = -1;
}
{/code}

<script type="text/javascript">	

var gId = 0;
function hg_getEmailSettingsById(id)
{
	if(gDragMode)
	 {
		   return;
	 }
	 /*判断当前有没有打开，打开的话就关闭*/
	 if($('#vodplayer_'+id).length)
	 {
		 hg_close_opration_info();
		 return;
	 }
	/*关闭之前保存选项卡的状态到cookie*/
	 hg_saveItemCookie();

	 gId=id;
	 
	 var ajaxcallback = function(){
		var url = './run.php?mid=' + gMid + '&a=getEmailSettingsById&id=' + id;
		hg_ajax_post(url);
	}

	;(function(){
		var h=$('body',window.parent.document).scrollTop();
		$('#edit_show').html('<img src="'+ RESOURCE_URL + 'loading2.gif' +'" style="width:50px;height:50px;"/>');
		click_title_show(h, ajaxcallback);
	})();
}
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6"><strong>新增邮件配置</strong></a>
</div>
<div class="content clear">
	<div class="f">
		<div class="right v_list_show" style="float:none;">
			<!-- 搜索 -->
			<div class="search_a" id="info_list_search">
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="right_1">
						{code}
							$attr_state = array(
								'class' => 'transcoding down_list',
								'show' => 'state_show',
								'width' => 80,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
								'is_sub'=> 0,
							);
							
							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'colonm_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);
							
							$default_node_type = $_INPUT['node_type'] ? $_INPUT['node_type'] : 0;
							
						{/code}
						{template:form/search_source,status,$_INPUT['status'],$_configs['email_settings_status'],$attr_state}
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
			<form action="" method="post">
				<!-- 标题 -->
                   <ul class="common-list">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="email-paixu common-list-item"><a class="common-list-paixu"></a></div>
                            </div>
                            <div class="common-list-right">
                             <!--    <div class="email-yjbt common-list-item open-close">邮件标题</div> -->
                                <div class="email-fsyx common-list-item open-close">发送邮箱</div>
                                <div class="email-cz common-list-item open-close">操作</div>
                                <div class="email-zt common-list-item open-close">状态</div>
                                <div class="email-tjr common-list-item open-close">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close email-biaoti">名称</div>
					        </div>
                        </li>
                    </ul>
				<ul class="common-list" id="status_list">
					{if $list}
						{foreach $list as $k => $v}
							{template:unit/email_settings_list_list}
						{/foreach}
					{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						<script>hg_error_html(status_list,1);</script>
					{/if}
				</ul>
				<ul class="common-list">
				    <li class="common-list-bottom clear">
					 <div class="common-list-left">
					   <input type="checkbox" name="checkall"  value="infolist" title="全选" rowtag="LI" />
					  <a style="cursor:pointer;margin-left:10px;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="batdelete">批量删除</a>
				</div>
			   </li>
			   </ul>
			</form>
		</div>
		{$pagelink}
		</div>
		<div class="edit_show">
			<span class="edit_m" id="arrow_show"></span>
			<div id="edit_show"></div>
		</div>
	</div>
</div>
</body>
{template:foot}