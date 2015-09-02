{template:head}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:share}

{css:edit_video_list}
{js:common/common_list}
{css:common/common_list}
{css:vod_style}
{css:2013/list}

{code}
$list = $site[0];
{/code}
<style>
	.common-list-i{top:12px;}
	.biaoti-content a{color:#282828;font-size:14px;}
	.bianji em{width:16px;height:16px;background:url({$RESOURCE_URL}bg-all.png) no-repeat -60px -24px;}
	.shanchu em{width:16px;height:16px;background:url({$RESOURCE_URL}bg-all.png) no-repeat -64px -118px;}
	.shijian .time{font-size:10px;}
	.bj, .sc{width:45px;text-align:center;}
	.paixu input{height:auto;}
	.max-wd{max-width:480px;}
	.weburl{display: block;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;font-size:14px;}
	.site_dir {display: block;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;font-size:14px;}
	.site_keywords{font-size:14px;display: block;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}
	.produce_format{font-size:14px;}
	.m2o-keywords{margin-right:10px;}
	.m2o-realmname{margin-right:10px;}
	 .m2o-catalogue{margin-right:10px;}
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
<div id="hg_page_menu" class="head_op" style="display:none;">
	<a type="button" class="button_6"  href="./run.php?mid={$_INPUT['mid']}&a=site_form&infrm=1" target="formwin">新增站点</a>
</div>
<div class="content clear">
	<div class="f">
		{template:unit/site_search}
		<form method="post" action="" name="listform">
			<!-- 
			<ul class="common-list">
				<li class="common-list-head public-list-head clear">
					<div class="common-list-left">
						<div class="common-list-item paixu">
						</div>
					</div>
					<div class="common-list-right">
						<div class="common-list-item wd60">编辑</div>
						<div class="common-list-item wd60">删除</div>
						<div class="common-list-item wd120">添加时间</div>
					</div>
					<div class="common-list-biaoti">
						<div class="common-list-item">站点名称</div>
					</div>
				</li>
			</ul>
			<ul class="common-list public-list site-list" id="sitelist">
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
			-->
			<div class="m2o-list">
				<div class="m2o-title m2o-flex m2o-flex-center">
					<div class="m2o-item paixu">
						<a class="m2o-list-paixu"></a>
					</div>
					<div class="m2o-item m2o-flex-one">站点名称</div>
					<div class="m2o-item m2o-keywords">关键字</div>
					<div class="m2o-item m2o-realmname">域名</div>
					<div class="m2o-item m2o-catalogue">生成目录</div>
					<div class="m2o-item m2o-type">生成方式</div>
					<!--<div class="m2o-item bj">编辑</div>-->
					<!--<div class="m2o-item sc">删除</div>-->
					<div class="m2o-item m2o-time">添加时间</div>
				</div>
				<div class="m2o-each-list">
				{if $list}
				{foreach $list as $k => $v}
					{template:unit/sitelist}
				{/foreach}	
				{else}
					<div>
						<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">没有站点！</p>
						<script>hg_error_html('#sitelist',1);</script>
					</div>						
				{/if}
				</div>
			</div>
			<ul class="common-list public-list">
				<li class="common-list-bottom clear">
					<!--<div class="common-list-left">
						<input type="checkbox" name="checkall"  value="infolist" title="全选" rowtag="LI" /> 
						<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" name="batdelete">删除</a>
					</div>-->
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