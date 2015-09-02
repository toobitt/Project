<!-- the categorylist -->
{template:head}
{css:vod_style}
{css:mark_style}
{css:common/common_list}
{css:common/common_publish}
<style>
</style>
<div id="hg_page_menu" class="head_op_program"{if $_INPUT['infrm']} style="display:none"{/if}>
<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}&_id={$_INPUT['_id']}" class="button_6"><strong>新增类别</strong></a>
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
					<div class="common-list-item wd80">类别ID</div>
					<div class="common-list-item wd150">类别描述</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item biaoti-transition">类别名称</div>
				</div>
			</li>
		</ul>	
		<ul id="status"class="common-list public-list">
		
		{if $list&&is_array($list)}
		
		   {foreach $list as $k => $v}
			<li id="r_{$v['id']}" class="h common-list-data clear"   name="{$v['id']}">
				<div class="common-list-left">
					<div class="common-list-item paixu">
						<div id="primary_key_img_{$v['id']}"><input id="primary_key_{$v['id']}" type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"/></div>
					</div>
				</div>
				<div class="common-list-right">
					<div class="common-list-item wd80"><span id="name_{$v['name']}">{$v['id']}</span></div>
					<div class="common-list-item wd150">
						<div class="common-list-item biaoti-transition"><span id="name_{$v['name']}">{$v['desc']}</span></div>
				  	 	<a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a>
				  	 	<!-- <a title="查看分类数据" href="./run.php?mid={$_INPUT['mid']}&a=data_show&cate_id={$v['id']}&infrm=1">查看分类数据</a> -->
						<a title="查看分类数据" href="./run.php?mid={$relate_module_id}&a=show&cate_id={$v['id']}&infrm=1">查看分类数据</a>
						<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除</a>
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
		{$pagelink}	
	</div>
	{template:foot}
</div>
