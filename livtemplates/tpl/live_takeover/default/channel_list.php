<?php 
/* $Id: channel_list.php 21183 2013-05-22 00:33:44Z yizhongyue $ */
?>
{code}
$attrs_for_edit = array(
	'id', 'click_num', 'click_count' ,'comm_num', 'img_info', 'downcount', 'is_control', 'channel_stream', 'preview'
);
//hg_pre($list);
{/code}
{template:head}
{template:list/common_list}
{css:vod_style}
{css:edit_video_list}
{js:channels}
{js:vod_opration}
<script type="text/javascript">

var gId = 0;
function hg_channel_edit_info(id)
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
		var url = "./run.php?mid=" + gMid + "&a=show_opration&id=" + id;
		hg_ajax_post(url);
	}

	;(function(){
		var h=$('body',window.parent.document).scrollTop();
		$('#edit_show').html('<img src="'+ RESOURCE_URL + 'loading2.gif' +'" style="width:50px;height:50px;"/>');
		click_title_show(h, ajaxcallback);
	})();
	
	
}
</script>
<script type="text/javascript">
/*flash相关*/
function setSwfPlay(flashId, url ,width, height, mute, objectId)
{
	var swfVersionStr = "11.1.0";

	var xiSwfUrlStr = RESOURCE_URL+"swf/playerProductInstall.swf?20120910";
	var flashvars = {objectId: objectId, namespace: "player", url: url, mute: mute};
	var params = {};
	params.quality = "high";
	params.bgcolor = "#000";
	params.allowscriptaccess = "sameDomain";
	params.allowfullscreen = "true";
	params.wmode = "transparent";
	var attributes = {};
	attributes.id = flashId;
	attributes.name = flashId;
	attributes.align = "middle";
	swfobject.embedSWF(
	   RESOURCE_URL+"swf/Main.swf?20120910", flashId, 
	    width, height, 
	    swfVersionStr, xiSwfUrlStr, 
	    flashvars, params, attributes);

	swfobject.createCSS("#"+flashId, "display:block;text-align:left;background:black;");

}
function hg_set_url(obj, flashId)
{
	var url = $(obj).attr('_url');
	var name = $(obj).attr('_name');
	var stream_name = $(obj).attr('_stream_name');
	if (url)
	{
		$('#play_title').text(name + ' [' + stream_name + ']');
		setUrl(flashId, url);
	}
}

function setUrl(flashId, url)
{
	document.getElementById(flashId).setUrl(url);
}
</script>

{js:ios/switch}
<script>
$(function(){
	var stream_status_id = '';
	var i = 1;
	var custom_stream_status = function(id, callback){
		stream_status_id = id;
		var url = "./run.php?mid=" + gMid + "&a=audit&id=" + id + "&infrm=1";
		hg_ajax_post(url,"","", callback);
	}
	var onandoff = function(self, state){
		var tmp = 'mySwitchCallback' + ++i;
		window[tmp]= function(ajax){
					ajax = ajax[0];
					var title = '';
					if(ajax == 1){
						title = '已启动';
					}else if(ajax == 2){
						title = '未启动';
					}else{
						self.trigger('callback', [state == 'on' ? 'off' : 'on']);
						return;
					}
					self.selector.attr('title', title);
					self.trigger('callback', ['ok']);
					delete window[tmp];
				}
				custom_stream_status(self.data('data'), tmp);
	}
	$('.need-switch').each(function(){
		$(this).switchButton({
			data : $(this).attr('vid'),
			init : $(this).attr('state') > 0 ? 'on' : 'off',
			on : function(self){
				onandoff(self, 'on');
			},
			off : function(self){
				onandoff(self, 'off');
			}
		})
	});
});

$(function () {
	$("#edit_show").on("click", ".anchor", function () {
		$("#edit_show").find("object").replaceWith("<div style=\"backgournd:black;width:400px;height:300px;\"></div>");
	});
});
</script>
<script>
$(function($){
    $.commonListCache('channel-list');
});
</script>
{css:channel_list}
<script type="text/javascript">
	function hg_sys_live()
	{
		var url = './run.php?mid=' + gMid + '&a=sys_live';
		hg_ajax_post(url, '', '', 'sys_live_callback');
	}
	
	function sys_live_callback(obj)
	{
		if (obj == 'success')
		{
			jAlert('同步频道成功');
			
		}
	}
</script>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="add-button mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="nodeFrame">新增直播频道</a>
</div>
<!-- 搜索 -->
	<div class="right v_list_show" style="float:none;">
		<div class="search_a" id="info_list_search">
		    <span class="serach-btn"></span>
			<form name="searchform" id="searchform" action="" method="get" style="position:relative;" onsubmit="return hg_del_keywords();">
				<div class="select-search">
					<input type="hidden" name="a" value="show" />
					<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
					<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				</div>
				<div class="text-search">
					<div class="button_search">
						<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
					</div>
					{template:form/search_input,k,$_INPUT['k']}                        
				</div>
			</form>
		</div>
	</div>
	<!-- 搜索 -->

<div id="infotip" class="ordertip" ></div>
{template:list/ajax_pub}

<div class="common-list-content" style="min-height:auto;min-width:auto;">

{if !$list}
	<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">暂无记录！<a style="color: red;text-decoration: underline;	" href="javascript:void(0);" onclick="hg_sys_live();" title="点击同步频道">同步频道</a></p>
	<script>hg_error_html('p',1);</script>	
{else}
	<div style="position: relative;">
		<div id="open-close-box">
			<span></span>
			<div class="open-close-title">显示/关闭</div>
			<ul>
				<!-- <li which="xhl"><label><input type="checkbox" checked />输出流</label></li> -->
				<li which="zt"><label><input type="checkbox" checked />状态</label></li>
				
			</ul>
		</div>
	</div>
	<form method="post" action="" name="listform">
		<ul class="common-list">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
					<div class="common-list-item paixu">
						<a title="排序模式切换/ALT+R" onclick="hg_switch_order('channellist');" style="cursor:pointer;" class="common-list-paixu" id="is_order"></a>
					</div>
					<div class="common-list-item wd120">台标</div>
				</div>
				<div class="common-list-right">
					<!-- <div class="common-list-item xhl wd80">输出流</div> -->
					<div class="common-list-item zt wd70">
					</div>
					<div class="common-list-item zt wd70">状态</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item">频道名称</div>
				</div>
			</li>
		</ul>
		<ul id="channellist" class="common-list channel-list  public-list hg_sortable_list" data-table_name="channel" data-order_name="order_id">
		{foreach $list AS $k => $v}
			<li class="common-list-data clear" orderid="{$v['order_id']}"  order_id="{$v['order_id']}" _id="{$v['id']}" id="r_{$v['id']}" class="h"   name="{$v['id']}">
				<div class="common-list-left">
					<div class="common-list-item paixu">
						<a name="alist[]">
							<input type="checkbox" title="{$v['id']}" value="{$v['id']}" name="infolist[]" id="primary_key_19">
						</a>
					</div>
					<div class="common-list-item wd120">
						{if $v['logo_rectangle_url']}
						<img _src="{$v['logo_rectangle_url']}" width="90" height="30" class="img-middle"/>
						{/if}
					</div>
				</div>
				<div class="common-list-right ">
					<!--
<div class="common-list-item xhl wd80">
						<span>
						{if $v['channel_stream']}
							{foreach $v['channel_stream'] AS $vv}
							<span title="{$vv['url']}">{$vv['url']}</span>
							{/foreach}
						{/if}
						</span>
					</div>
-->
					<div class="common-list-item zt wd70">
						<div align="center" style="padding-top:4px;margin-left:-30px;">
							<div class="need-switch" title="{if !$v['status']}未启动{else}已启动{/if}" state="{$v['status']}" style="cursor:pointer;" vid="{$v['id']}"></div>
						</div>
					</div>
					
					
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item biaoti-transition">
							<span title="编辑"  style="cursor:pointer;"><span><a href="{if !$v['is_sys']}./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1{else}javascript:void(0);{/if}"><span class="channel-pd-name m2o-common-title">{$v['name']}</span><span class="channel-pd-taihao">{$v['code']}</span></a></span></span>
					</div>
				</div>
				<div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
			</li>
		{/foreach}
		</ul>
		<ul class="common-list public-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left">
					<input type="checkbox" title="全选" value="infolist" name="checkall">
					<a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" >批量删除</a>
				</div>
				{$pagelink}
			</li>
		</ul>
		<div class="edit_show">
			<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
			<div id="edit_show"></div>
		</div>
	</form>
{/if}
</div>
{template:unit/record_edit}

{template:foot}