<?php 
/* $Id: crontab.php 17515 2013-03-05 08:23:17Z yudoudou $ */
?>
{template:head}
{js:crontab}

<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_plan first dq"><em style="margin-top: 12px !important;width: 20px;height: 20px;"></em><a>计划任务</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
		<span class="button_6" onclick="document.location.href='?a=form'"><strong>新增计划</strong></span>
	</div>
</div>
<div class="wrap n">
<div style="text-align:center;line-height:25px;">
<span id="crontab_state">计划任务{if $is_run}运行中...<a onclick="return hg_ajax_post(this, '停止', 0);" href="?a=stop" style="color:red;margin-left:5px;">停止</a>{else}已停止<a onclick="return hg_ajax_post(this, '开始', 0);" href="?a=start"  style="color:green;margin-left:5px;">开始</a>{/if}</span>
</div>
{template:list}
</div>
{template:foot}