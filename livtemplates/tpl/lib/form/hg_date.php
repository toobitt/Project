<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* hg_name-------隐藏域的name和id
* hg_value------传入值
* hg_attr['type']---0--默认显示中文 1--英文
* hg_attr['sort']---0--默认显示按天 1--按周
* hg_attr['class']---div显示的class
* hg_attr['id']---div显示的ID
* hg_attr['default']---默认不显示
* hg_attr['extra_onclick']---日期点击的其他事件
*
* $Id: hg_date.php 5234 2011-12-05 07:43:04Z repheal $
***************************************************************************/

?>
{js:hg_date}
{css:hg_date}
{code}
	$sort = $hg_attr["sort"]?$hg_attr["sort"]:0;
	$type = $hg_attr["type"]?$hg_attr["type"]:0;
{/code}
<div id="{$hg_attr['id']}" class="{$hg_attr['class']}" {if !$hg_attr['default']} style="display:none;"{/if}></div>
<script type="text/javascript">var {$hg_attr["id"]} = new hgDate(); {$hg_attr["id"]}.hg_date('{$hg_attr["id"]}','{$hg_name}','{$hg_value}',{$sort},{$type},'{code} echo $hg_attr["extra_onclick"]?$hg_attr["extra_onclick"]:'';{/code}');</script>