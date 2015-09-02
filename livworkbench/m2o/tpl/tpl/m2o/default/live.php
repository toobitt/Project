{template:unit/head}
{template:unit/head_menu}
<style>
a{color:inherit;}
</style>
<div class="container clearfix layout page-{$data['page_name']}">
	<div class="live-display clearfix">
		<div class="live-display-flash fl">
			<object id="CUTV_PLAYER_0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="640" height="510">
				<param name="movie" value="http://www.cutv.com/static/player/v.swf">
				<param name="allowscriptaccess" value="always">
				<param name="allowFullScreen" value="true">
				<param name="wmode" value="opaque">
				<param name="flashvars" value="id=E16ghggghhjlppmgiinx41&amp;tvie=media-api.cutv.com&amp;hd=false&amp;keyword=&amp;autoplay=false&amp;norecomm=true">
			  	<embed type="application/x-shockwave-flash" flashvars="id=E16ghggghhjlppmgiinx41&amp;tvie=media-api.cutv.com&amp;hd=false&amp;keyword=&amp;autoplay=false&amp;norecomm=true&amp;allowFullScreen=true" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" allowscriptaccess="always" allowfullscreen="true" wmode="transparent" src="http://www.cutv.com/static/player/v.swf" name="CUTV_PLAYER_0" style="width:640px;height:510px;outline:0">
	        </object>
		</div>
		<div class="live-display-channel-list tab-area fl">
			<div class="live-display-day clearfix">
				<a class="tab-trigger default" data-index="0">周一</a>
				<a class="tab-trigger default" data-index="1">周二</a>
				<a class="tab-trigger" data-index="2">周三</a>
				<a class="tab-trigger default" data-index="3">周四</a>
				<a class="tab-trigger default" data-index="4">昨天</a>
				<a class="tab-trigger default" data-index="5">今天</a>
				<a class="tab-trigger default" style="width:51px;" data-index="6">明天</a>
			</div>
			<div class="live-display-channel-list-area">
				{foreach range(0, 6) as $v}
				<ul class="tab-cont tab-cont{$v} {if $v != 2}default{/if}">
					{foreach range(0, 9) as $vv}
					<li><a href="javascript:" {if $vv + $v == 7}class="current"{/if}><span>00:21</span><span>安徽卫视：科学第七天</span></a></li>
					{/foreach}
				</ul>
				{/foreach}
			</div>
		</div>
		
	</div>
	<div class="live-channel-interactive clearfix">
		<div class="live-channel">
			<h3 class="ft16 live-title"><em></em>正在直播</h3>
			<ul class="clearfix mt20">
				{foreach range(0, 10) as $v}
				<li {if $v % 2 == 0}class="odd-bg {if $v == 2}current{/if}"{/if}>
					<h4>广西卫视</h4><p>下午剧场：封神榜好看不错啊 3</p><strong>正在播放</strong><em>看直播</em>
				</li>
				{/foreach}
			</ul>
		</div>
		<div class="live-interactive">
			<h3 class="ft16 live-title"><em></em>边看边聊</h3>
			<div class="live-interactive-cont mt20 clearfix">
				{foreach range(0, 2) as $v}
				<div class="live-interactive-item">
					<div class="user-head">
						<img src="http://tp4.sinaimg.cn/1664207987/50/5648352952/1" width="50" height="50">
					</div><div>
						<strong class="lh24">帅气飞扬</strong>
						<p><span class="live-reply-bg1"></span><span class="live-reply-cont">俩垃圾发垃圾分类</span><span class="live-reply-bg2"></span></p>
						<em>7分钟前</em><em>来自手机客户端</em>
					</div>
					<a class="live-reply-btn">回复</a>
				</div>
				{/foreach}
				<div class="common-button"><em></em><span>下一页</span><i></i></div>
			</div>
			<a class="live-interactive-F5"></a>
		</div>
	</div>
	<div class="live-bottom-program clearfix">
		<a>热门节目</a>
		<a>热门节目</a>
		<a>热门节目</a>
		<a>热门节目</a>
		<a>热门节目</a>
		<a>热门节目</a>
		<a>热门节目</a>
		<a>热门节目</a>
	</div>
</div>
<script>
$(function ($) {
	$('.tab-area').each(function () {
		var area = $(this);
		area.on('click', '.tab-trigger', function () {
			area.find('.tab-cont,.tab-trigger').addClass('default');
			$(this).add(
				area.find(
					'.tab-cont' + $(this).data('index')
				)
			).removeClass('default');
		});
	});
});
</script>
{template:unit/foot}