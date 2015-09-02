<?php 
/* $Id: crontab.php 3047 2011-11-07 02:17:27Z repheal $ */
?>
{template:head}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_system first"><em></em><a>安装应用</a></li>
			<li class="nav_system dq"><em></em><a>设置HOST</a></li>
			<li class="nav_system dq"><em></em><a>目录权限检测</a></li>
			<li class="nav_system dq"><em></em><a>数据库配置</a></li>
			<li class="nav_system dq"><em></em><a>参数配置</a></li>
			<li class="nav_system"><em></em><a>完成</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap n">
	<form name="editform" action="" method="post" class="ad_form h_l">
	<ul class="form_ul">
	<li class="i">
		<div style="width:700px;height:330px;margin-left:20px;margin-top:20px;">
			{foreach $dbconfig AS $kk => $vv}
				<div style="width:400px;height:30px;margin-top:10px;">
					<div style="width:50px;height:30px;float:left;">{$kk}:</div>
					<input type="text" name="{$kk}" value="{$vv}" readonly="readonly"  style="float:left;margin-left:10px;" />
				</div>
			{/foreach}
		</div>

		{foreach $formdata[0] AS $k => $v}
			<div style="width:700px;height:30px;margin-top:10px;margin-left:20px;">
				{code}
					if(is_array($v['value']))
					{
						$v['value'] = var_export($v['value'],1);
					}
					
					if($v['const'] == 1)
					{
						$datatype = '常量';
					}
					else
					{
						$datatype = '全局变量';
					}
					
					if(!$v['desc'])
					{
						$v['desc'] = '没有描述';
					}
				{/code}
				<div style="float:left;width:100px;margin-top:8px;">{$k}</div>
				<input type="text" style="float:left;margin-left:20px;width:200px;" name="cfg_val[]" value="{$v['value']}" />
				<div style="float:left;margin-left:20px;width:150px;margin-top:8px;">{$v['desc']}</div>
				<div style="float:left;margin-left:20px;width:100px;margin-top:8px;">{$datatype}</div>
				<input type="hidden" name="cfg_type[]" value="{$v['const']}" />
				<input type="hidden" name="cfg_var[]" value="{$k}" />
			</div>
		{/foreach}
	</li>
	</ul>
	<input type="hidden" name="html" value=true />
	<input type="hidden" name="a" value="{$a}" />
	<input type="hidden" name="apihost" value="{$_INPUT['apihost']}" />
	<input type="hidden" name="apidir" value="{$_INPUT['apidir']}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="goon" value="1" />
	<div style="width:110px;height:50px;margin-left:20px;margin-top:10px;">
		<input type="submit" name="sub" value="下一步" class="button_6_14"/>
	</div>
	</form>
</div>
{template:foot}