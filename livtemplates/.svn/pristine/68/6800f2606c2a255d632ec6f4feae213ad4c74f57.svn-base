<?php
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>   
{template:head}
{css:node_drop}
{css:2013/iframe}
{css:vod_live}
{js:tree/iframe_node}
{js:jqueryfn/jquery.tmpl.min}
{code}
//print_r($formdata);
$hg_data = $formdata;
{/code}
<script type="text/javascript">
$(function(){
    top == self && $('#nodeFrame').attr('src', function(){
        return $(this).attr('_src');
    });
});
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
            				{template:unit/node,node,$_INPUT['nodeid'],$hg_data, $hg_attr}
						</div>
					</div>
				{/if}
        		</div>
			</td>
			<td style="vertical-align: top;border-left: 1px solid #D8D8D8;">
				<div id="livnodewin">
					<iframe name="nodeFrame" id="nodeFrame"  frameborder="no" scrolling="no" hidefocus="hidefocus" allowtransparency="true" _src="{$node_iframe_attr['src']}" autocomplete="off" {$node_iframe_attr['attr']}>
					</iframe>
				</div>
			</td>
		</tr>
	</table>
</div>


{template:foot}