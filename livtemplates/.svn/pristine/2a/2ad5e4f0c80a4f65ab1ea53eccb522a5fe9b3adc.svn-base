<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{code}
$states = array(-1 => '全部', 2=> '待审核', 1 => '审核通过', 0 => '审核不过');
$codeds = array(-1 => '全部', 0=> '转码中', 1 => '转码完成');
if(!isset($_INPUT['k']))
{
	$_INPUT['k'] = '关键字';
	$onclick = ' onfocus="if(this.value==\'关键字\') this.value=\'\'" onblur="if (this.value==\'\') this.value=\'关键字\'"';
}
if (!isset($_INPUT['state']))
{
	$_INPUT['state'] = -1;
}
if (!isset($_INPUT['coded']))
{
	$_INPUT['coded'] = -1;
}
{/code}
<div class="search_a">
<form name="searchform" action="" method="get" onsubmit="if(this.k.value=='关键字') this.k.value='';">
<div class="input" style="width:162px">
	<span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><input name="k" size="20" value="{$_INPUT['k']}"{$onclick} speech="speech" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate"/></span>
</div>

{template:form/dateselect,start_time,$_INPUT['start_time']}
{template:form/dateselect,end_time,$_INPUT['end_time']}
{template:form/select, state,$_INPUT['state'], $states}
{template:form/select, coded,$_INPUT['coded'], $codeds}
<input type="hidden" name="a" value="show" />
<input type="hidden" name="mid" value="{$_INPUT['mid']}" />&nbsp;
<input type="submit" name="hg_search" value="搜索" class="button_2" />
</form>
</div>