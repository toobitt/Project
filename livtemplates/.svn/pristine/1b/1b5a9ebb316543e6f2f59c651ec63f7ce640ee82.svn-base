<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{if $formdata[$hg_title]}
{code}
$mod_title = hg_cutchars($formdata[$hg_title], 10);
{/code}
<h3>修改 {$mod_title}</h3>
{else}
<br />
{/if}
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post">
{code}

{/code}
<ul>
{foreach $form_set AS $k => $v}
{code}
if (!$v['title'])
{
	$v['title'] = $k;
}
$hg_attr = array(
	'width' => $v['width'] ? $v['width'] : 30,
	'height' => $v['height'] ? $v['height'] : 5,
);
{/code}
<li><label>{$v['title']}:</label>
{if $v['show_type'] == 'textarea'}
{template:form/textarea,$k,$formdata[$k],,$hg_attr}
{elseif $v['show_type'] == 'radio'}
{template:form/radio,$k,$formdata[$k],,$hg_attr}
{else}
{template:form/text,$k,$formdata[$k],,$hg_attr}
{/if}
</li>
{/foreach}
<li>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="submit" name="sub" value="提交" />
</li>
</form>
</ul>
{template:foot}