<?php 
/* $Id: station_config_list.php 13635 2012-10-19 01:43:33Z lijiaying $ */
?>
{template:head}
{js:member}
{css:vod_style}
{css:edit_video_list}
{css:common/common_list}
{css:mem_list}
{js:vod_opration}
{js:common/common_list}
{js:tree/animate}
{js:jquery-ui-1.8.16.custom.min}

{code}
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
$(function(){
	tablesort('_list','station_config','order_id');
	$("#_list").sortable('disable');
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6"><strong>新增站外配置</strong></a>
</div>
{template:list/ajax_pub}
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
						{template:form/search_source,status,$_INPUT['status'],$_configs['member_state'],$attr_state}
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
			<div id="infotip" class="ordertip" ></div>
			<form action="" method="post">
				<!-- 标题 -->
              <ul class="common-list">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="mem-paixu common-list-item"><a title="排序模式切换/ALT+R" onclick="hg_switch_order('_list');" class="common-list-paixu" style="cursor:pointer;"></a></div>
                               <!--  <div class="mem-fengmian common-list-item">缩略图</div> -->
                            </div>
                            <div class="common-list-right">
	                            <div class="mem-huiyuan common-list-item open-close" which="mem-huiyuan">标识</div>
                                <div class="mem-email common-list-item open-close" which="mem-email">回调函数</div>
                                <div class="mem-cz common-list-item open-close" which="mem-cz">操作</div>
                                <div class="mem-zt common-list-item open-close" which="mem-zt">状态</div>
                                <div class="mem-sj common-list-item open-close" which="mem-sj">添加人/时间</div>
                            </div>
                            <div class="mem-title">名称</div>
                        </li>
                </ul>
                <ul class="common-list" id="_list">
					{if $list}
						{foreach $list as $k => $v}
							{template:unit/station_config_list_list}
						{/foreach}
					{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						<script>hg_error_html(status_list,1);</script>
					{/if}
				</ul>
				<ul class="common-list">
				    <li class="common-list-bottom clear">
					   <div class="common-list-left">
					      <input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI"/>
					      <a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="batdelete">批量删除</a>
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