<?php
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{js:mblog}
<div id="hg_parent_page_menu" style="margin-right:10px;margin-top:12px;"></div>
<div id="livnodewin_s" style="width:100%;margin-bottom:20px;">
	<div class="wrap n" style="width:12.45%;height:670px;float:left;padding:0px;margin:0px;">
	{code}
		$attr['_selfurl'] = $_selfurl;
	{/code}
	{template:node/node_drop,'',$_INPUT['_type'],$show_server_node, $attr}
	</div>
	<div id="hg_page_menu" class="head_op"></div>
	<iframe name="nodeFrame" id="nodeFrame" src='?infrm=1&_type={$defaultmid}' style="float:left;width:86%;height:670px;scrolling:no;margin-bottom:10px;"></iframe>
</div>
{template:foot}