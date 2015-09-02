{template:head}
{code}
	
	$list = $logindb_list;
{/code}
{template:list/common_list}
{css:list}
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="nodeFrame" class="button_6">新增服务器</a>
</div>
<script type="text/javascript"></script>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
</div>
{template:unit/search}
<div class="common-list-content">
{if !$logindb_list}
	<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">
		没有数据库信息
	</p>
	<script>
		hg_error_html('p', 1);
	</script>
{else}
	<form method="post" action="" name="listform" style="position:relative;">
		<!-- 标题 -->
		<ul class="common-list id="list_head">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
					<div class="common-paixu common-list-item">
						<a class="common-list-paixu" style="cursor:pointer;"  onclick="hg_switch_order('webvodlist');"  title="排序模式切换/ALT+R"></a>
					</div>
					<div class="special-slt common-list-item">
						ID#
					</div>
				</div>
				<div class="common-list-right">
					<div class="common-list-item open-close wd70">
						状态
					</div>
					<div class="common-list-item open-close wd70">
						编码
					</div>
					<div class="common-list-item open-close wd70">
						用户
					</div>
					<div class="common-list-item open-close wd120">
						数据库名
					</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item open-close special-biaoti">
						主机/端口
					</div>
				</div>
			</li>
		</ul>
		<ul class="common-list hg_sortable_list public-list" id="webvodlist" data-order_name="orderid">
		{foreach $logindb_list as $k => $v}
			{template:unit/list}
		{/foreach}
		</ul>
		<ul class="common-list public-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left">
				<input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
					<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'build_logindb_cache', '批量启用', 1, 'id', '', 'ajax');"    name="batadonline">批量启用</a>
					<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'unset_logindb_cache', '批量禁用', 1, 'id', '', 'ajax');"    name="batadonline">批量禁用</a>
					<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'syntable', '同步', 1, 'id', '', 'ajax');"    name="batadonline">同步主服务器</a>
				</div>
				</div>
				{$pagelink}
			</li>
		</ul>
	</form>
{/if}
</div>

<div id="infotip"  class="ordertip"></div>
<div id="getimgtip"  class="ordertip"></div>
<from id="uploadForm" style="display:none;" method="post" action="run.php" enctype="multipart/form-data">
	<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
	<input type="hidden" name="a" value="upload_pic" />
	<input type="hidden" name="program_id" />
	<input type="file" name="Filedata" />
</from>
{template:unit/record_edit}
<script>
$(function () {
	hg_resize_nodeFrame(1);
	$([parent, parent.document, parent.document.documentElement]).scrollTop(0);
	
	$('#record-edit').on('click','.regsend',function(event){
		hg_ajax_post(this, '注册至IMS', 0);
		event.preventDefault();
	});
});
</script>
{template:foot}