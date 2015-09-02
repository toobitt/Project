{template:head}
{css:common/common_list}
{js:common/common_list}
<style>
.paixu{width:20px;}
.block{width:50px;}
</style>
{code}
$list = $block_list[0];
//print_r($block_list);exit;
{/code}
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
			<a class="add-button mr10" href="run.php?mid={$_INPUT['mid']}&a=block_form&_id={$_INPUT['_id']}&infrm={$_INPUT['infrm']}&site_id={$list['site_id']}&page_id={$list['page_id']}&page_data_id={$list['page_data_id']}&expand_name={$list['expand_name']}">新增区块</a>
</div>

<div class="common-list-content">
	<div class="common-list-search" id="info_list_search">
		<span class="serach-btn"></span>
		<form name="searchform" id="searchform" action="" method="POST" onsubmit="return hg_del_keywords();">
			<div class="select-search">
				<input type="hidden" name="a" value="show" />
				<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			</div>
			<div class="text-search">
				<div class="button_search">
					<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
				</div>
				{template:form/search_input,keyword,$_INPUT['keyword']}                        
			</div>
		</form>
	</div>
{if !$list}
	<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">无区块！</p>
	<script>hg_error_html('p',1);</script>
{else}
	<form class="common-list-form" name="listform">
		<ul class="common-list">
			<li class="common-list-head clear">
				{code}
				$headLeft = array(
					'block' => '区块',
					'name' => '名称'
				);
				$headRight = array(
					'opration' => '操作',
					'update-type' => '更新',
					'pinlu' => '频率',
					'lanmu' => '栏目',
					'app' => '应用',
					'quanzhong' => '权重',
					'tiaoshu' => '条数',
					'yinyong' => '引用页',
					'last-update' => '最后更新'
				);
				{/code}
				<div class="common-list-left ">
					<div class="common-list-item paixu">
						<a title="排序模式切换/ALT+R" onclick="hg_switch_order('contentlist');" class="common-list-paixu"></a>					
					</div>
					{foreach $headLeft as $k => $v}
						<div class="common-list-item {$k}">{$v}</div>
					{/foreach}
				</div>
				<div class="common-list-right ">
				{foreach $headRight as $k => $v}
					<div class="common-list-item {$k}">{$v}</div>
				{/foreach}
				</div>
			</li>
		</ul>
		<ul class="common-list" id="contentlist">
		{if $list['block']['block']}
			{foreach $list['block']['block'] as $k => $v} 
				{template:unit/blocklist}
			{/foreach}
		{else}
		<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有区块！</p>
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
{/if}
</div>
<div id="infotip"  class="ordertip"></div>
<div id="getimgtip"  class="ordertip"></div>
{template:foot}