<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{if !is_array($hg_data)}
{code}
$hg_data = array(1 => '');
{/code}
{/if}
{if !is_array($hg_value)}
{code}
$hg_value = array($hg_value);
{/code}
{/if}
{code}
$checked_value = array_keys($hg_value);
{/code}
{foreach $hg_data AS $hg_k => $hg_v}
{code}
if (in_array($hg_k, $checked_value))
{
	$checked = ' checked="checked"';
}
else
{
	$checked = '';
}
$tvalue = $hg_value[$hg_k] ? $hg_value[$hg_k] : $hg_v;
{/code}
<label>
<input type="checkbox" name="{$hg_name}[{$hg_k}]" value="{$hg_k}"{$checked} class="n-h" /><span>{$hg_v}</span></label>
<input type="text" name="{$hg_name}_name[{$hg_k}]" value="{$tvalue}" size="5" />
{/foreach}