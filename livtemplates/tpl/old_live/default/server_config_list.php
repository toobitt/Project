<?php 
/* $Id: server_config_list.php 17352 2013-03-02 08:50:48Z yizhongyue $ */
?>
{template:head}
{css:vod_style}
{css:edit_video_list}
{css:common/common_list}
{js:channels}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}

<script type="text/javascript">

var gId = 0;
function hg_server_info(id)
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
		var url = "./run.php?mid=" + gMid + "&a=server_info&id=" + id;
		hg_ajax_post(url);
	}

	;(function(){
		var h=$('body',window.parent.document).scrollTop();
		$('#edit_show').html('<img src="'+ RESOURCE_URL + 'loading2.gif' +'" style="width:50px;height:50px;"/>');
		click_title_show(h, ajaxcallback);
	})();	
}
</script>

<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="formwin">
	               <span class="left"></span>
	               <span class="middle"><em class="add">新增配置</em></span>
	               <span class="right"></span>
	</a>
</div>

<script>
$(function($){
    {js:domcached/jquery.json-2.2.min}
    {js:domcached/domcached-0.1-jquery}
    {js:common/common_list}
    $.commonListCache('channel-list');
});
</script>
{css:channel_list}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div class="search_a" id="info_list_search" style="height:0;padding:0"></div>
<div id="infotip" class="ordertip" ></div>
{template:list/ajax_pub}
<div class="content clear">
<div class="f">	

	<!-- 搜索 -->
	<div class="right v_list_show" style="float:none;">
		<div class="search_a" id="info_list_search">
			<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
				<div class="right_1">
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
			</form>
		</div>
	</div>
	<!-- 搜索 -->
<div class="common-list-wrap v_list_show">
{if !$list}
	<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">没有您要找的内容！</p>
	<script>hg_error_html('p',1);</script>	
{else}
	<div style="position: relative;">
		<div id="open-close-box">
			<span></span>
			<div class="open-close-title">显示/关闭</div>
			<ul>
				<li which="fbz"><label><input type="checkbox" checked />主控host</label></li>
				<li which="fl"><label><input type="checkbox" checked />信号数目</label></li>
				<li which="scl"><label><input type="checkbox" checked />添加人/时间</label></li>
			</ul>
		</div>
	</div>
	<form method="post" action="" name="listform">
		<ul class="common-list">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
					<div class="common-list-item paixu">
						<a class="common-list-paixu" style="background:none;" id="is_order"></a>
					</div>
				</div>
				<div class="common-list-right">
 					<div class="common-list-item fbz wd150">主控host</div>
 					<div class="common-list-item fl wd80">信号数目</div>
					<div class="common-list-item scl wd100">添加人/时间</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item">配置名称</div>
				</div>
			</li>
		</ul>
		<ul id="channellist" class="common-list channel-list public-list">
		{foreach $list AS $k => $v}
			<li class="common-list-data clear" orderid="{$v['order_id']}"  id="r_{$v['id']}" class="h"   name="{$v['id']}">
				<div class="common-list-left">
					<div class="common-list-item paixu">
							<a name="alist[]">
								<input type="checkbox" title="{$v['id']}" value="{$v['id']}" name="infolist[]" id="primary_key_19">
							</a>
					</div>
				</div>
				<div class="common-list-right ">
					<div class="common-list-item fbz wd150">
							<span class="common-list-pub-overflow wd150">{$v['core_in_host']}</span>
					</div>
					<div class="common-list-item fl wd80">
                            <span id="defalut_node_{$v['id']}" style="cursor: pointer;">{$v['counts']}</span>
                    </div>
					<div class="common-list-item scl wd100">
							<span class="common-name">{$v['user_name']}</span>
							<span class="common-time">{$v['create_time']}</span>
					</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item pd biaoti-transition">
							<span title="编辑"  style="cursor:pointer;"><span>
								<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
									<span class="channel-pd-name">{$v['name']}</span>
								</a>
							</span>
					</div>
				</div>
				<div class="common-list-i" onclick="hg_server_info({$v['id']});"></div>
			</li>
		{/foreach}
		</ul>
		<ul class="common-list public-list">
			<li class="common-list-bottom">
				<div class="common-list-left">
					<input type="checkbox" title="全选" value="infolist" id="checkall" name="checkall">
					<a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" >批量删除</a>
				</div>
			</li>
			{$pagelink}
		</ul>
		<div class="edit_show">
			<span class="edit_m" id="arrow_show"></span>
		<div id="edit_show"></div>
</div>
	</form>
{/if}
</div>

</div>
</div>
</body>
{template:foot}