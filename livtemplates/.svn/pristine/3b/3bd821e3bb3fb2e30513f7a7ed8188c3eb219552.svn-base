<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{css:tab_btn}
{if $_btn_menu}
{code}
$len = count($_btn_menu) - 1;
$i = 0;
{/code}
<span class="btn_sl" style="float:right;margin-right:10px">
{foreach $_btn_menu AS $menu}
{code}
if ($i == 0)
{
	$class = 'first';
}
else if($i == $len)
{
	$class = 'last';
}
else
{
	$class = '';
}
if ($_INPUT['mid'] == $menu['module_id'])
{
	$class .= ' cur';
}
$i++;
{/code}
<a class="{$class}" href="{code} echo $menu['url'] . $_ext_link . $hg_attr['extra'];{/code}">{$menu['name']}</a>
{/foreach}
</span>
{/if}