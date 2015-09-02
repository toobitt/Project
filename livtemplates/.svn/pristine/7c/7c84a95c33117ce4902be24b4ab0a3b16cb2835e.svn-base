<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}

<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_module first dq"><em></em><a>模块</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
		<span class="button_6" onclick="document.location.href='?a=form'"><strong>新增模块</strong></span>
	</div>
</div>
<div class="wrap n">
<div class="search_a">
<form name="searchform" action="" method="get">
{code}
$attr = array(
	'onchange' => ' onchange="document.location.href=\'?application_id=\' + this.value"',
);
$_INPUT['application_id'] = $_INPUT['application_id']?$_INPUT['application_id']:$appid;
{/code}
{template:form/select,application_id,$_INPUT['application_id'],$applications,$attr}
{code}
$attr = array(
	'onchange' => ' onchange="document.location.href=\'?fatherid=\' + this.value"',
);

{/code}
{template:form/select,fatherid,$_INPUT['fatherid'],$all_m,$attr}
<input type="hidden" name="a" value="show" />
<input type="hidden" name="mid" value="{$_INPUT['mid']}" />&nbsp;
</form>
</div>
{template:list}
</div>
{template:foot}