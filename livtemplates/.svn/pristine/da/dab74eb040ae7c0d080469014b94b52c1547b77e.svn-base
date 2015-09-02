<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>  
{code}
if (!$hg_attr['level'])
{
	$hg_attr['level'] = 0;
}
if (!$hg_attr['text'])
{
	$hg_attr['text'] = '首页';
}
if(!$_parenturl)
{
	$_parenturl = 'run.php?mid=' . $_INPUT['mid'];
}
$infrm = 'infrm';
$infrm_v = 1;
$is_root = ($hg_data[0]['fid'] == '0' || $hg_data[0]['depath'] == 1);
$need_more = $hg_data[0]['total'] ? $hg_data[0]['total'] - count($hg_data) : false;

function create_node_href($v, $_selfurl)
{
	$input_k = $v['input_k'] ? $v['input_k'] : '_id';
	$href = "$_selfurl&$input_k=" . $v['id'];
	$href .= $v['para'] ? "&para=" . $v['para'] : '';	
	$href .= $v['_appid'] ? "&_appid=" . $v['_appid'] : '';
	$href .= "&_modid=" . $v['_modid'] ;
	return $href;	
}
{/code}
{code} 
{/code}
<div class="been_marked_second">
	<div class="first {if $is_root}top current{else}normal{/if}">
		<a class="back {if $is_root}allcond{/if}" {if $is_root}href="{$_parenturl}&amp;{$infrm}={$infrm_v}" target="nodeFrame"{else} href="javascript:;"{/if}>
			{$hg_attr['text']}
		</a>
	</div>
	<ul class="hg_node_depth_node_ul">
		{foreach $hg_data as $v}
		<li>
			<span class="a">
				{if !$v['is_last']}
				<a class="i" href="javascript:" data-name="{$v['name']}"  data-id="{$v['id']}" target="nodeFrame">
					<span class="img">&nbsp;</span>
				</a>
				{/if}
				<a class="l" href="./run.php?mid={$_INPUT['mid']}&a=get_deploy&id={$v['id']}" target="nodeFrame">{$v['name']}</a>
			</span>
		</li>
		{/foreach}
	</ul>
	{if $need_more}
	<div class="addMoreNode" data-total="{$hg_data[0]['total']}" data-fid="{$hg_data[0]['fid']}">展开更多<span class="addMoreNode-icon"></span><img class="addMoreNode-loading" src="{$RESOURCE_URL}loading2.gif" /></div>
	{/if}
</div>