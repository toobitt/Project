<?php 
/* $Id: crontab.php 3047 2011-11-07 02:17:27Z repheal $ */
?>
{template:head}
<style type="text/css">
 .api_path_box{margin-top:27px;margin-left:20px;width:450px;height:300px;border:1px dotted #D9D9D9;}
 .api_path_box div{width:450px;height:30px;margin-top:20px;}
 .api_path_box div span{font-size:16px;font-weight:blod;width:90px;float:left;margin-top:4px;margin-left:20px;}
 .api_path_box div input{float:left;}
</style>
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_system first"><em></em><a>安装应用</a></li>
			<li class="nav_system dq"><em></em><a>设置HOST</a></li>
			<li class="nav_system"><em></em><a>目录权限检测</a></li>
			<li class="nav_system"><em></em><a>数据库配置</a></li>
			<li class="nav_system"><em></em><a>参数配置</a></li>
			<li class="nav_system"><em></em><a>完成</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap n">
	<form name="editform" action="" method="post" class="ad_form h_l" >
		{if $msg}
		<div style="color:red;" id="msg" class="msg">{$msg}</div>
		{/if}
		<div class="api_path_box">
			<div>
				<span>应用Host:</span>
				<input type="text" name="apihost" size="30" id="apihost"  value="{$formdata['apihost']}" onblur="hg_check_connect();"  />
			</div>
			<div>
				<span>应用&nbsp;&nbsp;Dir:</span>
				<input type="text" name="apidir"  size="30" id="apidir"   value="{$formdata['apidir']}"  onblur="hg_check_connect();"   />
				<span id="cstatus"></span>
			</div>
			<div>
				<input type="submit" name="sub" value="下一步" class="button_6_14" style="float:left;margin-left:20px;" />
			</div>
			<input type="hidden" name="a" value="{$a}" />
			<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			<input type="hidden" name="goon" value="1" />
		</div>
	</form>
</div>
<script type="text/javascript">
function hg_check_connect_back(data)
{
	if (data)
	{
		$('#cstatus').html('连接成功').css({'color':'green'});
	}
	else
	{
		$('#cstatus').html('连接失败').css({'color':'red'});
	}
}
function hg_check_connect()
{
	var url = 'api_install.php?a=ping';
	data = {
		host : $('#apihost').val(),
		dir : $('#apidir').val(),
	};
	hg_request_to(url, data, 'get', 'hg_check_connect_back', 1);
}
</script>
{template:foot}