<?php 
/* $Id: record_config_list.php 20488 2013-05-07 02:44:14Z lijiaying $ */
?>
{template:head}
{template:list/common_list}
{css:vod_style}
{css:edit_video_list}
{js:channels}
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
		var url = "./run.php?mid=" + gMid + "&a=show_opration&id=" + id;
		hg_ajax_post(url);
	}	
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
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="add-button mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="nodeFrame">新增配置</a>
</div>

<script>
$(function($){
    $.commonListCache('channel-list');
});
</script>
{css:channel_list}

<div id="infotip" class="ordertip" ></div>
{template:list/ajax_pub}


	<!-- 搜索 -->
	<div class="right v_list_show" style="float:none;">
		<div class="search_a" id="info_list_search">
			<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
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
<div class="common-list-content" style="min-height:auto;min-width:auto;">
{if !$list}
	<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">没有您要找的内容！</p>
	<script>hg_error_html('p',1);</script>	
{else}
	<div style="position: relative;">
		<div id="open-close-box">
			<span></span>
			<div class="open-close-title">显示/关闭</div>
			<ul>
				<li which="yxzt"><label><input type="checkbox" checked />运行状态</label></li>
				<li which="host"><label><input type="checkbox" checked />主机/端口</label></li>
				<li which="fl"><label><input type="checkbox" checked />源视频目录访问地址</label></li>
				<li which="zt"><label><input type="checkbox" checked />状态</label></li>
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
 					<div class="common-list-item yxzt wd100">运行状态</div>
 					<div class="common-list-item host wd150">主机/端口</div>
 					<div class="common-list-item fl wd150">源视频目录访问地址</div>
 					<div class="common-list-item zt wd70">状态</div>
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
					<div class="common-list-item yxzt wd100">
						<span class="common-list-pub-overflow wd100">{if $v['is_success']}<font style="color:#59b630;">正常</font>{else}<font style="color:red;">异常</font>{/if}</span>
					</div>
					<div class="common-list-item host wd150">
						<span class="common-list-pub-overflow wd150">{$v['record_host']}:{$v['record_port']}</span>
					</div>
					<div class="common-list-item fl wd150">
                        <span style="cursor: pointer;">{$v['record_output_host']}</span>
                    </div>
                    <div class="common-list-item zt wd70">
						<div align="center" style="padding-top:4px;margin-left:-30px;">
							<div class="need-switch" title="{if !$v['status']}未启动{else}已启动{/if}" state="{$v['status']}" style="cursor:pointer;" vid="{$v['id']}"></div>
						</div>
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
				<div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
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
			<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
			<div id="edit_show"></div>
		</div>
	</form>
{/if}
</div>

<div id="record-edit">
	<div class="record-edit" >
		<div class="record-edit-btn-area clear">
			<a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}&infrm=1">编辑</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=delete&id=${id}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
			<a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}&copy=1&infrm=1">复制</a>
		</div>
		<!--
<div class="record-edit-btn-area clear">
			<a>移动</a>
			
		</div>
-->
		<div class="record-edit-line mt20"></div>
		<div class="record-edit-info">
			<!--
<span>访问:${click_num}</span>
			<span>评论:${comm_num}</span>
-->
		</div>
		<span class="record-edit-close"></span>
	</div>
	<div class="record-edit-confirm">
		<p>确定要删除该内容吗？</p>
		<div class="record-edit-line"></div>
		<div class="record-edit-confirm-btn">
			<a>确定</a>
			<a>取消</a>
		</div>
		<span class="record-edit-confirm-close"></span>
	</div>
</div>
{template:foot}