<?php
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{code}
        $hg_text_attr = array(
         20 => '全部视频',
         51 => '全部直播归档',
         58 =>'全部新闻',
         7 => '全部地盘',
         4 => '全部举报',
         13 => '全部图集',
         37 => '全部广告',
         27 => '全部广告位',
        270=>'组织机构',
        354	=>'全部频道',
        531 => '全部会员',
        766 => 'null'
        );
        $hg_attr['text'] = $hg_text_attr[$_INPUT['mid']];
{/code}   
{template:head}
{css:node_drop}
{css:2013/iframe}
{css:vod_live}
{js:tree/iframe_node}
{js:jqueryfn/jquery.tmpl.min}
<script type="text/javascript">
$(function(){
    top == self && $('#nodeFrame').attr('src', function(){
        return $(this).attr('_src');
    });
});
function hg_append_menu(obj) {
	$(obj).siblings().find('a').removeClass('append_cur');
	$(obj).find('a').addClass('append_cur');
}
</script>
{if $hg_data}
<script type="text/javascript">
$(function() {
	//iframe_node('{$hg_attr['nodeapi']}'.replace(/&amp;/g, '&'));
	new NodeTree({
		nodeapi: '{$hg_attr['nodeapi']}'.replace(/&amp;/g, '&'),
		el: $('#hg_node_node')
	});
});
</script>
{/if}
<div class="channels_menu" id="channels_menu" style="display:none"></div>
{code}
$isnode = $hg_node_template == "node" ? true : false;
{/code}
<style>
.leftmenu{margin-right:0;padding-bottom: 10px;overflow:hidden;}
#hg_node_node{position:relative;transition:left .3s;-webkit-transition:left .3s;width:3000px;}
.each-node{float:left;}
.each-node.with-loading{background:url({$RESOURCE_URL}loading2.gif) no-repeat 50% 50%;height:200px;background-size:40px 40px;}
#hg_node_node .back.allcond{display:block;text-indent:19pt;}
.livnodewin{position:relative;overflow:hidden;}
#nodeFrame{width:100%;}
</style> 
<div class="wrap clear">
	<table border="0" cellspacing="0" cellpadding="0" style="width:100%;">
		<tr>
			<td style="vertical-align: top;width:137px;">
				
				<div class="leftmenu">
				{if $hg_data}
					<div id="hg_node_node">
						<div class="each-node">
            				{template:node/node,node,$_INPUT['nodeid'],$hg_data, $hg_attr}
						</div>
					</div>
				{elseif $append_menu}
					<ul class="been_marked_second first_none" id="append_menu" style="border-top-left-radius:0;">
			        {foreach $append_menu AS $k => $v}
			            {code}
			            if (!$k)
			            {
			                $fcls = ' class="append_cur"';
			            }
			            else
			            {
			                $fcls = '';
			            }
			            {/code}
						<li class="{$v['class']}" onclick="hg_append_menu(this);"><a href="{$v['url']}&infrm=1&nav=1"{$fcls} target="nodeFrame">{$v['name']}</a></li>
			        {/foreach}
					</ul>
				{/if}
        		</div>
			</td>
			<td style="vertical-align: top;border-left: 1px solid #D8D8D8;">
				<div id="livnodewin">
					<iframe name="nodeFrame" id="nodeFrame" frameborder="no" scrolling="no" hidefocus="hidefocus" allowtransparency="true" _src="{$node_iframe_attr['src']}" autocomplete="off" {$node_iframe_attr['attr']}></iframe>
				</div>
			</td>
		</tr>
	</table>
</div>
{code}
	define('FORMDATA', 0);
{/code}
{css:hg_sort_box}
{js:hg_sort_box}
{js:2013/ajaxload_new}
{js:2013/new_search_init}
{template:unit/publish_for_form}
{template:foot}