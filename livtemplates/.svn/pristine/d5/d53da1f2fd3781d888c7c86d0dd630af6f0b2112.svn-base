<?php 
/* $Id:$ */
?>
{template:head}
{code}
	$hg_attr['multiple'] = 1;
	$hg_attr['title'] = '发布标题';
	$hg_attr['request_url'] = 'http://localhost/livworkbench/fetch_column_node.php?1';
	$hg_attr['_callcounter'] = 1;
{/code}
{css:column_node}
{js:column_node}
<form action="" method="post" enctype="multipart/form-data">
{template:unit/column_node, columnid, $hg_columns_selected, $hg_column, $hg_attr}
<input type="hidden" value="create" name="a">
<input type="submit" class="btn_2" value="发布">
</form>
{template:foot}