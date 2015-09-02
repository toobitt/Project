<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_module first dq"><em></em><a>服务器管理</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
		<span class="button_6" onclick="document.location.href='?a=form'"><strong>新增服务器</strong></span>
	</div>
</div>

<div style="width:100%;margin-bottom:20px;">
	<div class="wrap n" style="width:12%;height:670px;float:left;padding:0px;">
	{code}
		$attr['_selfurl'] = $_selfurl;
	{/code}
	{template:node/node_drop,'',$_INPUT['_type'],$show_server_node, $attr}

	</div>
	<iframe name="nodeFrame" src='?infrm=1&_type=1' style="float:left;width:86%;height:670px;scrolling:no;" ></iframe>
</div>
{template:foot}