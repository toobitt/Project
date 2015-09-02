<?php 
/* $Id: channel_list.php 26663 2013-09-11 06:46:37Z wangleyuan $ */
?>
{code}
	
$attrs_for_edit = array(
	'id', 'click_num', 'click_count' ,'comm_num', 'img_info', 'downcount', 'is_audio', 'is_control', 'channel_stream', 'preview'
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
	var gNodeChannelId = '';
	$(function ($) {
		$(document).click(function () {
			$($('#channellist').find('li')).each(function(){
				$(this).find('div[name^="node_"]').html('');
				$(this).find('div[name^="node_"]').prev().show();
			});
		});
	});
	function hg_channel_node_show(obj, id, node_id)
	{
		$($('#channellist').find('li')).each(function(){
			$(this).find('div[name^="node_"]').html('');
			$(this).find('div[name^="node_"]').prev().show();
		});
		
		gNodeChannelId = id;
		var url = "./run.php?mid=" + gMid + "&a=channel_node_show&id=" + id + "&node_id=" + node_id;
		hg_ajax_post(url);
		
	}
	
	function channel_node_show_callback(html)
	{
		html += '<input type="hidden" value="'+gNodeChannelId+'" id="channel_id" />';
		/*$('#defalut_node_' + gNodeChannelId).hide();*/
		$('#node_' + gNodeChannelId ).html(html).find('ul').show();
	}
	
	function hg_channel_node(obj)
	{
		var id = $('#channel_id' ).val();
		var node_id = $('#node_id').val();
		var node_name = $(obj).html();
		var url = "./run.php?mid=" + gMid + "&a=channel_node_edit&id=" + id + "&node_id=" + node_id + "&node_name=" + node_name;
		hg_ajax_post(url,'','','channel_node_edit_back');
	}
	function channel_node_edit_back(obj)
	{
		var obj = obj[0];
		$('#defalut_node_' + obj['id']).html(obj['node_name']).show();
		$('#defalut_node_' + obj['id']).attr('onclick','hg_channel_node_show(this, '+obj['id']+', '+obj['node_id']+')');
		$('#node_' + obj['id']).html('');
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
					
					<!--
{code}
						$_attr_channel_node = array(
							'class' => 'transcoding down_list',
							'show' => '_node_show_',
							'width' => 100,/*列表宽度*/
							'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							'is_sub'=> 0,
						);
						
						$_INPUT['_node_id'] = $_INPUT['_node_id'] ? $_INPUT['_node_id'] : -1;
						$_node[-1] = '所有分类';
						if (!empty($channel_node))
						{
							foreach($channel_node AS $kk =>$vv)
							{
								$_node[$vv['id']] = $vv['name'];
							}
						}
						
					{/code}
					{template:form/search_source,_node_id,$_INPUT['_node_id'],$_node,$_attr_channel_node}
					{if !empty($server_info)}	
						{code}
							$_attr_server = array(
								'class' => 'transcoding down_list',
								'show' => '_server_show_',
								'width' => 100,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
								'is_sub'=> 0,
							);
							
							$_INPUT['server_id'] = $_INPUT['server_id'] ? $_INPUT['server_id'] : -1;
							$server[-1] = '所有服务器';
							
							foreach($server_info AS $kk =>$vv)
							{
								$server[$vv['id']] = $vv['name'];
							}
						{/code}
						{template:form/search_source,server_id,$_INPUT['server_id'],$server,$_attr_server}
					{/if}
-->
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
	<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">暂无记录！</p>
	<script>hg_error_html('p',1);</script>	
{else}
	<div style="position: relative;">
		<div id="open-close-box">
			<span></span>
			<div class="open-close-title">显示/关闭</div>
			<ul>
				<!-- <li which="fbz"><label><input type="checkbox" checked />发布至</label></li> -->
				<li which="fl"><label><input type="checkbox" checked />分类</label></li>
				<li which="zt"><label><input type="checkbox" checked />状态</label></li>
				<li which="xhl"><label><input type="checkbox" checked />输入流</label></li>
				<li which="scl"><label><input type="checkbox" checked />输出流</label></li>
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
					<!-- <div class="common-list-item fbz common-list-pub-overflow">发布至</div> -->
					<div class="common-list-item fl wd80">分类</div>
					<div class="common-list-item zt wd70">状态</div>
					<div class="common-list-item xhl wd80">输入流</div>
					<div class="common-list-item kong wd50"></div>
					<div class="common-list-item scl wd60">输出流</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item">频道名称/台号</div>
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
						<img _src="{$v['logo_rectangle_url']}" onclick="hg_channel_edit_info({$v['id']});" width="90" height="30" class="img-middle"/>
						{/if}
					</div>
				</div>
				<div class="common-list-right ">
					<div class="common-list-item fbz overflow common-list-pub-overflow">
						<!--
{if $v['column_id']}
							{code}
								$column_id = implode(',',$v['column_id']);
							{/code}
						<div title="发布至:{$column_id}" style="color:#1FB34E;" class="common-list-pub-overflow">
							{$column_id}
						</div>
						{/if}
-->
					</div>
					<div class="common-list-item fl wd80">
					<div  style="position:relative;">
						<span id="defalut_node_{$v['id']}" style="cursor: pointer;" onclick="hg_channel_node_show(this, {$v['id']}, {$v['node_id']});">{if !$v['node_id']}未分类{else}{$v['node_name']}{/if}</span>
                        <div id="node_{$v['id']}" name="node_{$v['id']}" style="position:absolute;top:0px;left:-35px;"></div>
                    </div>
                    </div>
					<div class="common-list-item zt wd70">
						<div align="center" style="padding-top:4px;margin-left:-30px;">
							<div class="need-switch" title="{if !$v['status']}未启动{else}已启动{/if}" state="{$v['status']}" style="cursor:pointer;" vid="{$v['id']}"></div>
						</div>
					</div>
					<div class="common-list-item xhl wd80">
						<span>
						{if $v['channel_stream']}
							{foreach $v['channel_stream'] AS $vv}
							<span title="{$vv['url']}">{$vv['stream_name']}</span>
							{/foreach}
						{/if}
						</span>
					</div>
					<div class="common-list-item kong wd50"></div>
					<div class="common-list-item scl wd60">
						<span>
						{if $v['channel_stream']}
							{foreach $v['channel_stream'] AS $vv}
							<span title="{$vv['output_url']}">{$vv['stream_name']}</span>
							{/foreach}
						{/if}
						</span>
					</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item biaoti-transition">
							<span title="编辑"  style="cursor:pointer;"><span><a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><span class="channel-pd-name m2o-common-title">{$v['name']}</span><span class="channel-pd-taihao">{$v['code']}</span></a></span>{if $v['chg2_stream_id']}<span></span>{/if}</span>
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