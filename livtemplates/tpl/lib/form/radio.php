<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{if !is_array($hg_data)}
{code}
$hg_data = array(1 => '是', 0 => '否', );
{/code}
{/if}
{foreach $hg_data AS $hg_k => $hg_v}
{code}
if ($hg_k == $hg_value)
{
	$checked = ' checked="checked"';
}
else
{
	$checked = '';
}
{/code}
<label><input type="radio" name="{$hg_name}" value="{$hg_k}"{$checked} class="n-h"/><span>{$hg_v}</span></label>
{/foreach}