<?php 
/* $Id: list.php 21480 2013-05-28 07:01:10Z yizhongyue $ */
?>
{code}
if(!$_INPUT['article_status'])
{
	$_INPUT['article_status']=1;
}
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
{/code}

{template:head}
{code}
$attrs_for_edit = array('status');

if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
    $pub = new column();
}
if(!class_exists('publishsys'))
{
include_once(ROOT_DIR . 'lib/class/publishsys.class.php');
$publishsys = new publishsys();
}
//获取所有站点
//$hg_sites = $pub->getallsites();
$hg_sites = $publishsys->getallsites();
{/code}
{template:list/common_list}

<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div {if $_INPUT['infrm']}style="display:none"{/if}>
	<div class="common-list-search" id="info_list_search">
		<span class="serach-btn"></span>
		<form target="nodeFrame" name="searchform" id="searchform" action="" method="get">
			<div class="select-search">
				{code}
					$default = $_INPUT['site_id'] ? $_INPUT['site_id'] : 0;
					$hg_sites[0] = '所有站点';
					$attr_site = array(
						'class'  => 'colonm down_list date_time',
						'show'   => 'app_show',
						'width'  => 104,
						'state'  => 0,
					);
				{/code}
				{template:form/search_source,site_id,$default,$hg_sites,$attr_site}
				<input type="hidden" name="a" value="show" /> 
				<input type="hidden" name="mid" value="{$_INPUT['mid']}" /> 
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="node_en" value="{$_INPUT['node_en']}" />
				<input type="hidden" name="_id" value="{$_INPUT['_id']}" /> 
				<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
			</div>
			<div class="text-search">
				<div class="button_search">
					<input type="submit" value="" name="hg_search" style="padding: 0; border: 0; margin: 0; background: none; cursor: pointer; width: 22px;" />
				</div>
				{template:form/search_input,key,$_INPUT['key']}
			</div>
		</form>
	</div>
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a class="add-button news mr10" target="nodeFrame" onclick="hg_add_dynpro()">添加</a>
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
					<div class="common-list-item news-ren open-close wd100">站点</div>
					<div class="common-list-item news-ren open-close wd100">操作</div>
                    <div class="common-list-item news-ren open-close wd100">添加人/时间</div>
                </div>
                <div class="common-list-biaoti">
					<div class="common-list-item">标题</div>
				</div>
			</li>
		</ul>
		<!-- 主题，记录的每一行 -->
		<ul class="news-list common-list public-list hg_sortable_list" id="newslist" data-table_name="article" data-order_name="order_id">
		{foreach $list as $k => $v}
			{template:unit/dynprolist}
		{/foreach}
		</ul>
		<!-- foot，全选、批处理、分页 -->
		<ul class="common-list public-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left">
					<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" /> 
					<a style="cursor:pointer;" onclick="return hg_ajax_batchbuilt(this);" name="built_api">生成API</a>
					<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="delete">删除</a>
				</div>
				{$pagelink}
			</li>
		</ul>   	
	</form>	
{/if}
</div>   
{template:unit/dynpro_record_edit}
<script type="text/javascript">
function hg_ajax_batchbuilt(obj,ids,href)
{
	if(!ids)
	{
		var ids = $(obj).closest('form')
				.find('input:checked:not([name="checkall"])')
				.map(function() { return this.value; }).get().join(','),
				msg;
	}	
	if(!href)
	{		
		var href = './run.php?mid='+gMid+'&id='+ids+'&a=built_api';
	}
	$.get(href).done(function(data){
		jAlert ? jAlert('生成成功', '成功提醒').position(obj) :alert('生成成功');
	});	
	return false;
}

function hg_add_dynpro()
{
	window.location.href = "./run.php?mid={$_INPUT['mid']}&a=form&site_id={$_INPUT['site_id']}&infrm=1";
	return false;
}
</script>
{template:foot}     				