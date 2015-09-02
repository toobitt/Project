<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{code}
$states = array(-1 => '全部', 2=> '待审核', 1 => '审核通过', 0 => '审核不过');
if(!isset($_INPUT['k']))
{
	$_INPUT['k'] = '请输入关键字';
	$onclick = ' onfocus="text_value_onfocus(this,\'请输入关键字\');" onblur="text_value_onblur(this,\'请输入关键字\');" class="t_c_b"';
}
if (!isset($_INPUT['state']))
{
	$_INPUT['state'] = -1;
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
<input type="hidden" name="a" value="show" />
<input type="hidden" name="mid" value="{$_INPUT['mid']}" />&nbsp;
<input type="submit" name="hg_search" value="搜索" class="button_2" />
</form>
</div>