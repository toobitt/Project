<?php 
/* $Id: stream_mms_list.php 9978M 2012-07-20 08:54:31Z (local) $ */
?>
{template:head}
{css:vod_style}
{css:upload_vod}
{css:edit_video_list}
{template:list/common_list}
{js:channels}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{code}
if ($_INPUT['server_id'])
{
	$server_info = $list['server_info'];
	unset($list['server_info']);
}
{/code}

{js:ios/switch}
<script>
$(function(){
	var stream_status_id = '';
	var i = 1;
	var custom_stream_status = function(id, callback){
		stream_status_id = id[0];
		var url = "./run.php?mid=" + gMid + "&a=streamStatus&id=" + id[0] + "&server_id=" + id[1] + "&infrm=1";
		hg_ajax_post(url,"","", callback);
	}
	var onandoff = function(self, state){
		var tmp = 'mySwitchCallback' + ++i;
		window[tmp]= function(ajax){
					ajax = ajax[0];
					var title = '';
					if(ajax == 1){
						title = '已启动';
						$('#a_' + stream_status_id).addClass('a');
						$('span[id^=out_uri_'+stream_status_id+']').addClass('channel_stream');
						$('span[id^=out_uri_'+stream_status_id+']').removeClass('channel_stream_b')
						$('#a_' + stream_status_id).removeClass('b');
					}else if(ajax == 2){
						title = '未启动';
						$('#a_' + stream_status_id).addClass('b');
						$('span[id^=out_uri_'+stream_status_id+']').addClass('channel_stream_b');
						$('span[id^=out_uri_'+stream_status_id+']').removeClass('channel_stream');
						$('#a_' + stream_status_id).removeClass('a');
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
			data : [$(this).attr('vid'),$(this).attr('server_id')],
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
</script>
<script type="text/javascript">
	/*删除信号之前检查是否被频道所占用*/
	var gStreamId = '';
	function hg_check_channel(id)
	{
		if (!id)
		{
			return false;
		}
		gStreamId = id;
		var url = './run.php?mid=' + gMid +'&a=check_channel&id=' + id;
		hg_ajax_post(url,'','','check_channel_back');
	}

	function check_channel_back(obj)
	{
		id = gStreamId;
		if(obj == -10)
		{
			var checkConfirm = confirm("确定删除该信号吗？");
		}
		else
		{
			var checkConfirm = confirm("["+obj+"] 在用此信号，还确定删除此信号吗？");
		}
		if (checkConfirm==true)
		{
			var url = './run.php?mid=' + gMid + '&a=delete&id=' + id;
			hg_ajax_post(url);
		}
	}
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="formwin">
	               <span class="left"></span>
	               <span class="middle"><em class="add">新增信号流</em></span>
	               <span class="right"></span>
	    </a>
	</div>
	<!-- 搜索 -->
	<div class="content clear" style="padding-bottom:0;">
		<div class="right v_list_show" style="float:none;">
			<div class="search_a" id="info_list_search">
			    <span class="serach-btn"></span>
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="select-search">
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
	</div>
	<!-- 搜索 -->
	<form style="position:relative;">
		{css:stream_list}
		<ul class="common-list">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
					<div class="common-list-item paixu"><a class="fz0">排序</a></div>	
					
				</div>
				<div class="common-list-right">
				    <!--  <div class="common-list-item option wd100">操作</div>-->
				    {if !empty($server_info)}
				    <div class="common-list-item token wd120">所属服务器</div>
				    {/if}
					<div class="common-list-item token wd70">类型</div>
					<div class="common-list-item status wd70">状态</div>
					<div class="common-list-item channel-stream wd150">视频流</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item">信号标识</div>
				</div>
			</li>
		</ul>
		<ul class="common-list public-list" id="status">
		{if $list}
		{foreach $list as $k => $v}
			{template:unit/stream_row}
		{/foreach}
		{else}
			<li>
				<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">暂无记录！</p>
				<script>hg_error_html('#status',1);</script>
			</li>		
		{/if}
		</ul>
		<ul class="common-list public-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left">
				 <input type="checkbox" title="全选" value="infolist" name="checkall" class="n-h" >
				 <a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" style="cursor:pointer;">批量删除</a>
			  </div>
			{$pagelink}
		  </li>
		</ul>
		<div class="edit_show">
			<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
			<div id="edit_show"></div>
		</div>
	</form>
</body>

<script type="text/javascript">
$(function(){
	getIsPlay();
});
/*检测备播信号是否正常 setTimeout("getIsPlay();", 10000);alert(obj[0][1][0][1]['name']);*/
	function getIsPlay()
	{
		 var url = './run.php?mid=' + gMid + '&a=getIsPlay';
		 hg_ajax_post(url,'','','getIsPlay_back');
		 setTimeout("getIsPlay();", 10000);
	}
	function getIsPlay_back(obj)
	{
		var obj = obj[0];
		var name = '';
		var isVideoReady = '';
		var isAudioReady = '';
		var enable = '';
		var audio_only = '';
		
		for (var i in obj)
		{
			var text = '';
			for (var j in obj[i])
			{
				name 		 = obj[i][j]['name'];
				audio_only 	 = obj[i][j]['audio_only'];
				isVideoReady = obj[i][j]['isVideoReady'];
				isAudioReady = obj[i][j]['isAudioReady'];
				enable 		 = obj[i][j]['enable'];

				text += '['+name+'] &nbsp;';
				if (audio_only == 0)
				{
					if (isVideoReady == 1)
					{
						text += '<font style="color: #30B430;">来源正常 </font>';
						$('#isPlay_' + i).html(text);
					}
					else
					{
						text += '<font style="color: red;">来源异常 </font>';
						$('#isPlay_' + i).html(text);
					}
				}
				else
				{
					if (isAudioReady == 1)
					{
						text += '<font style="color: #30B430;">来源正常 </font>';
						$('#isPlay_' + i).html(text);
					}
					else
					{
						text += '<font style="color: red;">来源异常 </font>';
						$('#isPlay_' + i).html(text);
					}
				}
			}
		}
	}
	setTimeout("getIsPlay();", 10000);
</script>
{template:foot}