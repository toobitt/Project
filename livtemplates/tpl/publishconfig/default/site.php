{template:head}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:share}


{css:edit_video_list}
{css:common/common_list}
{css:vod_style}
{code}
$list = $site[0];
{/code}
<style>
	.common-list-cell{height:40px;}
	.common-list-i{top:12px;}
	.paixu{width:20px;}
	.shijian{width:70px;}
	.shanchu{width:40px;}
	.bianji{width:40px;}
	.biaoti-content a{color:#282828;font-size:14px;}
	.bianji em{width:16px;height:16px;background:url({$RESOURCE_URL}bg-all.png) no-repeat -60px -24px;}
	.shanchu em{width:16px;height:16px;background:url({$RESOURCE_URL}bg-all.png) no-repeat -64px -118px;}
	.shijian .time{font-size:10px;}
</style>
<script>
function formsubmit(id)
{
	document.getElementById(id).submit();
}
function site_form()
{
	window.location.href="./run.php?mid={$_INPUT['mid']}&a=site_form&infrm=1";
}
</script>

<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<span type="button" class="button_6" onclick="site_form()" href="./run.php?mid={$_INPUT['mid']}&a=site_form&infrm=1">新增站点</span>
</div>
<div class="content clear">
	<div class="f">
		{template:unit/site_search}
		<div style="position: relative;">
			<div id="open-close-box">
				<span></span>
				<div class="open-close-title">显示/关闭</div>
				<ul>
					<li which="bianji"><label><input type="checkbox" checked />编辑</label></li>
					<li which="shanchu"><label><input type="checkbox" checked />删除</label></li>
					<li which="shijian"><label><input type="checkbox" checked />添加时间</label></li>
				</ul>
			</div>
		</div>
		<form method="post" action="" name="listform">
			<ul class="common-list">
				<li class="common-list-head clear">
					<div class="common-list-left">
						<div class="common-list-item paixu">
							<div></div>
						</div>
					</div>
					<div class="common-list-right">
						<div class="common-list-item bianji">编辑</div>
						<div class="common-list-item shanchu">删除</div>
						<div class="common-list-item shijian">添加时间</div>
					</div>
					<div class="common-list-biaoti">
						<div class="common-list-item">站点名称</div>
					</div>
				</li>
			</ul>
			<ul class="common-list site-list" id="sitelist">
				{if $list}
				{foreach $list as $k => $v}
					{template:unit/sitelist}
				{/foreach}	
				{else}
					<li>
						<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">没有站点！</p>
						<script>hg_error_html('#sitelist',1);</script>
					</li>						
				{/if}
			</ul>
			<div class="bottom clear">
				<div class="left">
					<input type="checkbox" name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" /> 
					<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" name="batdelete">删除</a>
				</div>
				{$pagelink}
			</div>
		</form>		
		<div class="edit_show">
			<span class="edit_m" id="arrow_show"></span>
			<div id="edit_show"></div>
		</div>	
	</div>
</div>

<div id="infotip"  class="ordertip"></div>
<div id="getimgtip"  class="ordertip"></div>
</body>
<script>
$(function($){
    {js:domcached/jquery.json-2.2.min}
    {js:domcached/domcached-0.1-jquery}
    {js:common/common_list}
    $.commonListCache('site-list');
});
</script>
<script type="text/javascript">
$(function($) {
	$('.common-list-data .lb').on('click', function() {
		$(this).closest('.common-list-data').toggleClass('cur');
	});
});
</script>
{template:foot}