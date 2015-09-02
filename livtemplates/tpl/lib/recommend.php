<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{js:column_node}
{css:column_node}
<form name="recommendform" id="recommendform" action="run.php" method="post" class="form" onsubmit="return hg_ajax_submit('recommendform');">
<div>
{code}
$hg_attr['multiple'] = 1;
$hg_attr['multiple_site'] = 1;
$hg_attr['slidedown'] = 1;
{/code}
{template:unit/column_node,columnid,$default}
</div>
<input type="hidden" name="a" value="dorecommend" />
<!--
<input type="hidden" name="mid" value="{code}echo $relate_module_id ? $relate_module_id : $_INPUT['mid'];{/code}" />
-->
<input type="hidden" name="mid" value="{$pub_module_id}" />
<input type="hidden" name="hg_id" value="{$_INPUT[$primary_key]}" />
{if $default}
<input type="hidden" name="hg_recomend" value="{$formdata['hg_recommed_id']}" />
<span class="label">&nbsp;</span><input type="submit" name="rsub" value="重新发布" class="button_4" />
{else}
<span class="label">&nbsp;</span><input type="submit" name="rsub" value="确定发布"  class="button_4" />
{/if}
</form>