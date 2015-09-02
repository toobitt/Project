<?php 
/* $Id: list.php 32546 2014-06-24 09:05:24Z hujinxia $ */
?>
{code}
if(!$_INPUT['template_status'])
{
	$_INPUT['template_status']=-2;
}
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
{/code}

{template:head}
{code}
$audit_value = 2;
$back_value = 3;
$status_key ='status';
$back_label = '被打回';
$attrs_for_edit = array('pub_url', 'catalog','m3u8', 'video_preview', 'material','status');
{/code}
{template:list/common_list}
{css:news_list}
{js:2013/cloud_pop}
{js:template_store/template_list}
<style>
.download-record{color:#8fa8c6;}
</style>

<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div {if $_INPUT['infrm']}style="display:none"{/if}>
	{template:unit/news_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a class="add-button news mr10" href="run.php?mid={$_INPUT['mid']}&a=form&infrm=1" target="formwin">新增模板</a>
	</div>
</div>

<!-- 记录列表 -->
<div class="common-list-content" style="min-height:auto;min-width:auto;">
{if !$list}
	<p id="emptyTip" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
	<script>hg_error_html('#emptyTip',1);</script>
{else}
	<form method="post" action="" name="listform" class="common-list-form">
		<!-- 头部，记录的列属性名字 -->
		<ul class="common-list news-list">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
	                <div class="common-list-item paixu open-close">
 	                   <a title="排序模式切换/ALT+R" onclick="hg_switch_order('newslist');"  class="common-list-paixu"></a>
                    </div>
                </div>
				<div class="common-list-right">
					<div class="common-list-item wd100">记录</div>
                    <div class="common-list-item wd100">分类</div>
                    <!-- <div class="common-list-item wd70">价格</div> -->
                    <div class="common-list-item news-quanzhong open-close wd60">权重</div>
                    <div class="common-list-item news-zhuangtai open-close wd60">状态</div>
                    <div class="common-list-item news-ren open-close wd100">添加人/时间</div>
                </div>
                <div class="common-list-biaoti">
					<div class="common-list-item">模板名称</div>
				</div>
			</li>
		</ul>
		<!-- 主题，记录的每一行 -->
		<ul class="news-list common-list public-list hg_sortable_list" id="newslist" data-table_name="templates" data-order_name="order_id">
		{foreach $list as $k => $v}
			{template:unit/news_row}
		{/foreach}
		</ul>
		<!-- foot，全选、批处理、分页 -->
		<ul class="common-list public-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left">
					<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" /> 
					<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '&audit=1', 'ajax', 'hg_change_status');" name="bataudit">审核</a>
					<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '打回', 1, 'id', '&audit=0', 'ajax', 'hg_change_status');" name="batgoback">打回</a>
					<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="batdelete">删除</a>
				</div>
				{$pagelink}
			</li>
		</ul>   	
	</form>	
{/if}
</div>   
<div id="add_share" ></div> 
<!-- 排序模式打开后显示，排序状态的 -->
<div id="infotip"  class="ordertip"></div>
<!-- 关于记录的操作和信息 -->
{template:unit/record_edit}
{template:foot}     				