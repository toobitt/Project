<?php 
?>
{template:head}
{code}
$list = $data_source_list[0][0];
{/code}
{css:vod_style}
{css:mark_style}
{css:common/common_list}
{css:common/common_publish}
{js:common/common_list}
<script>
gBatchAction['delete'] = './run.php?mid=' + gMid + '&a=delete';
</script>
<style>
</style>
<script>
$(function(){
	$('.print_view').on('click',function(event){
		var id = $(this).data('id'),
		    url = "./run.php?mid={$_INPUT['mid']}&a=get_datasource_data";
		$.get(url, {id: id}, function(data){
			da = JSON.parse(data)[0];
			if(da.error)
			{
				jAlert(da.error,'数据预览提醒');
			}
			else
			{
				$('.common-list-ajax-pub').css({'top':0});
				$('.view-content').val(print_r(da, 1));
			}
		});
	});
})
</script>
<div id="hg_page_menu" class="head_op_program"{if $_INPUT['infrm']} style="display:none"{/if}>

<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}&_id={$_INPUT['_id']}" target="formwin" class="button_6"><strong>新增数据源</strong></a>
</div>
<div class="wrap">
	<div class="search_a" id="info_list_search">
				<form name="searchform" id="searchform" action="" method="get" >
					<div class="right_1">
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
					  </div>
	             </form>
			</div>
<div class="common-list-content" style="min-height:auto;min-width:auto">
	<form method="post" action="" id="channel_table" class="common-list-form" id="channel_table">
		<ul id="item_th" class="h common-list public-list" >
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
					<div class="common-list-item paixu"></div>
				</div>
				<div class="common-list-right">
					<div class="common-list-item wd80">所属模块</div>
					<div class="common-list-item wd150">数据源操作</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item biaoti-transition">数据源名</div>
				</div>
			</li>
		</ul>	
		<ul id="status"class="common-list public-list">
		{if $list}
		   {foreach $list as $k => $v}
			<li id="r_{$v['id']}" class="h common-list-data clear"   name="{$v['id']}">
				<div class="common-list-left">
					<div class="common-list-item paixu">
						<div id="primary_key_img_{$v['id']}"><input id="primary_key_{$v['id']}" type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"/></div>
					</div>
				</div>
				<div class="common-list-right">
					<div class="common-list-item wd80"><span id="name_{$v['app_name']}">{$v['app_name']}</span></div>
					<div class="common-list-item wd150">
						<a title="生成API" href="./run.php?mid={$_INPUT['mid']}&a=build_api_file&id={$v['id']}&infrm=1">生成API</a>
				  	 	<a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin">编辑</a>
						<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除</a>
						<span class="print_view" data-id="{$v['id']}">预览</span>
					</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item biaoti-transition"><span id="name_{$v['name']}" class="m2o-common-title">{$v['name']}</span></div>
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
	                     <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
				         <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
				      </div>
	                  {$pagelink}
	               </li>
	             </ul>	
	</form>
</div>
	<div>
	</div>
	{template:foot}
</div>
<div id="vodpub" class="common-list-ajax-pub"  style="margin-left:-350px;">
	<div class="common-list-pub-title">
		<p>预览</p>
	</div>
	<div id="vodpub_body" class="common-list-pub-body">
	    <div class="publish-box">
	         <textarea class="view-content" style="height:300px;width:98%;"></textarea>
	    </div>
	</div>
	<span onclick="hg_vodpub_hide();"></span>
</div>
