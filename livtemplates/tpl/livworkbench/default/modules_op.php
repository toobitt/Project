<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_module first"><em></em><a>模块</a></li>
			<li class="dq"><em></em><a>操作</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
		<span class="button_6" onclick="document.location.href='?a=form&module_id={$module_id}'"><strong>新增操作</strong></span>
	</div>
</div>
<div class="wrap n">
{template:list}
</div>
{template:foot}