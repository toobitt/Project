<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{if $batch_op}
<ul class="batch_opmenu">
	{foreach $batch_op AS $k => $v}
	<li>
		{if !$v['group_op']}
		<input type="button" name="bat{$k}" class="button_4" value="批量{$v['name']}" title="{$v['brief']}"{$v['attr']} />
		{else}
				{code}
				$group_op = $v['group_op'];
				$name = 'batch__' . $k;
				$attr['onchange'] = $v['attr'];
				{/code}
				{template:form/select, $name, -1, $group_op, $attr}&nbsp;
		{/if}
	</li>
	{/foreach}
</ul>
<div class="clear"></div>
{/if}