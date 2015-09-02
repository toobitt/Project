<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}

<div class="heard_menu">
	<div id="hg_parent_page_menu" class="new_menu">
		<span class="button_6" onclick="document.location.href='?a=form&id={$_INPUT['id']}'"><strong>新增平台</strong></span>
	</div>
</div>
<div class="wrap n">
<div class="search_a">
<form name="searchform" action="" method="get">
<input type="hidden" name="a" value="show" />
<input type="hidden" name="mid" value="{$_INPUT['mid']}" />&nbsp;
</form>
</div>
{template:list}
</div>
{template:foot}