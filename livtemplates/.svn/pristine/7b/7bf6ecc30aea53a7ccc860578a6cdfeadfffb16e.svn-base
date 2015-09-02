<?php 
?>
{template:head}
{code}
$list = $mode_sort_list[0][0];
$sites = $mode_sort_list[0][1];
{/code}
{css:vod_style}
{css:mark_style}
{css:common/common_list}
{js:common/common_list}
<script>
gBatchAction['delete'] = './run.php?mid=' + gMid + '&a=delete';
</script>
<div id="hg_page_menu" class="head_op_program"{if $_INPUT['infrm']} style="display:none"{/if}>
<form action="" method="POST" name="add_mode_sort" id="add_mode_sort">
	<span type="button" class="button_6"  onclick="mode_sort_form()">新增样式分类</span>
</form>
</div>
<div class="wrap">
	<div class="search_a" id="info_list_search">
		<form name="searchform" id="searchform" action="" method="get" >
			<div class="right_1">
				{code}
				if(!class_exists('column'))
				{
				    include_once(ROOT_DIR . 'lib/class/column.class.php');
				    $publish = new column();
				}
				//获取所有站点
				//$hg_sites = $publish->getallsites();
				//$_INPUT['site_id'] = $_INPUT['site_id'] ? $_INPUT['site_id'] : 1;
				
				$arr_site = array(
				'class'  => 'colonm down_list date_time',
				'show'   => 'app_shows',
				'width'  => 104,
				'state'  => 0,
				);
				$hg_sites[0] = '所有站点';
				{/code}	
				<!--{template:form/search_source,site_id,$_INPUT['site_id'],$hg_sites,$arr_site}-->
				<input type="hidden" name="a" value="show" />
				<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			  </div>
         </form>
	</div>
	<form method="post" action="" id="channel_table" class="common-list-form">
	<ul class="common-list news-list">
		<li class="common-list-head clear public-list-head">
			<div class="common-list-left">
                <div class="common-list-item paixu"><a class="common-list-paixu" onclick="hg_switch_order('vodlist');"  title="排序模式切换/ALT+R"></a></div>
            </div>
            <div class="common-list-right"> 
                <div class="common-list-item">分类名</div>
                <!--<div class="common-list-item">站点名称</div>-->
                <div class="common-list-item">样式操作</div>
             </div>
             <div class="common-list-biaoti">
				<div class="common-list-item biaoti-transition">ID</div>
			</div>   
		</li>	
	</ul>
	<ul class="common-list public-list" id="status">
		{if $list}
		   {foreach $list as $k => $v}
			<li id="r_{$v['id']}" class="common-list-data public-list clear"   name="{$v['id']}">
				<div class="common-list-left">
       				<div class="common-list-item paixu" >
       					<div id="primary_key_img_{$v['id']}">
       					<input id="primary_key_{$v['id']}" type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"/>
						</div>
					</div>
				</div>
				<div class="common-list-right">
					<!--<div class="common-list-item"><span id="name_{$v['title']}">{$v['name']}</span></div>-->
					<div class="common-list-item"><span id="name_{$v['title']}">{$v['name']}</span></div>
					<div class="common-list-item">
						<a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a>
						<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除</a>	
					</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item biaoti-transition"><span id="name_{$v['id']}" class="m2o-common-title">{$v['id']}</span></div>
				</div>
			</li>
			{/foreach}
		{else}
		<tr><td class="hg_error" colspan="10">暂无记录</td></tr>
		{/if}
		</ul>
		<ul class="common-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left">		                   
					<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" />
					<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');"    name="batdelete">删除</a>
		   		</div>
		   		{$pagelink}
		   	</li>
		</ul>
		</form>
	{template:foot}
</div>
<script>
function mode_sort_form(id)
{
	window.location.href="./run.php?mid={$_INPUT['mid']}&a=form&site_id={$_INPUT['site_id']}&infrm=1";
}
</script>
