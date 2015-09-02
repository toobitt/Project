<?php 
/* $Id: crontab.php 3047 2011-11-07 02:17:27Z repheal $ */
?>
{template:head}
<style type="text/css">
 .dir_box{margin-top:27px;margin-left:20px;width:900px;height:170px;border:1px dotted #D9D9D9;}
 .dir_box div{width:100%;height:30px;}
 .dir_box div span{width:33.1%;line-height:30px;float:left;text-align:center;border-bottom:1px dotted #D9D9D9;}
 .dir_box div .d_l{border-right:1px dotted #D9D9D9;}
</style>
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_system first"><em></em><a>安装应用</a></li>
			<li class="nav_system dq"><em></em><a>设置HOST</a></li>
			<li class="nav_system dq"><em></em><a>目录权限检测</a></li>
			<li class="nav_system"><em></em><a>数据库配置</a></li>
			<li class="nav_system"><em></em><a>参数配置</a></li>
			<li class="nav_system"><em></em><a>完成</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap n">
	<form name="editform" action="" method="post" class="ad_form h_l">
		{if $msg}
		<div style="color:red;" id="msg" class="msg">{$msg}</div>
		{/if}
		<div class="dir_box">
			<div>
				<span  class="d_l">目录/文件</span><span  class="d_l">文件当前权限</span><span>文件所需权限</span>
			</div>
			{foreach $dirprms AS $k => $v}
				{code}
					if ($v['attr'] != $v['need_attr'])
					{
						$style = ' style="color:red"';
					}
					else
					{
						$style = '';
					}
				{/code}
			<div>
				<span  class="d_l">{$v['dir']}</span>
				<span  class="d_l" {$style}>{$v['attr']}</span>
				<span {$style}>{$v['need_attr']}</span>
			</div>
			{/foreach}
			<input type="hidden" name="apihost" value="{$_INPUT['apihost']}" />
			<input type="hidden" name="apidir" value="{$_INPUT['apidir']}" />
			<input type="hidden" name="a" value="{$a}" />
			<input type="submit" name="sub" value="下一步" class="button_6_14" style="float:left;margin-top:20px;"/>
		</div>
	</form>
</div>
{template:foot}