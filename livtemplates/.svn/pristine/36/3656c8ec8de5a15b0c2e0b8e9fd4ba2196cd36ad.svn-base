<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
<style>
.create_server {position:absolute;width:600px;margin:auto;border:1px solid #999;background:#ccc;top:100px;padding:10px;}
.form_ul li {line-height:32px;}
.form_ul span {width:80px;  display: inline-block;}
</style>
<script type="text/javascript">
function hg_select_db(val)
{
	if (val =='0')
	{
		$('#adddbserver').show();
	}
	else
	{
		$('#adddbserver').hide();
	}
}
function hg_add_dbservererr_back(html)
{
	$('#dberrmsg').html(html);
	$('#dberrmsg').show();
}
function hg_add_dbserver_back(id, name)
{
	$('#adddbserver').hide();
	$('#sdb').append('<option value="' + id + '">' + name + '</option>'); 
	$('#sdb').val(id);
	$('#dberrmsg').hide();

}
function hg_select_app(val)
{
	if (val =='0')
	{
		$('#addappserver').show();
	}
	else
	{
		$('#addappserver').hide();
	}
}
function hg_add_appserver_back(id, name)
{
	$('#addappserver').hide();
	$('#sapp').append('<option value="' + id + '">' + name + '</option>'); 
	$('#sapp').val(id);
	$('#apperrmsg').hide();

}
function hg_add_appservererr_back(html)
{
	$('#apperrmsg').html(html);
	$('#apperrmsg').show();

}

function hg_check_domains(value)
{
	url = 'appstore.php?a=check_domains';
	data = {
		appserver : $('#sapp').val(),
		domain : value,
	};
	hg_request_to(url, data, 'get', 'hg_show_domains', 0);

}
var dircanchange = false;
var hg_show_domains = function (domains)
{	
	if(domains.errorcode)
	{
		$('#show_domains').html('');
		return;
	}
	if (domains.length == 1 && domains[0].status == 1)
	{
		$('#show_domains').hide();
		$('#domain').val(domains[0].domain);
		$('#dir').val(domains[0].dir);
		$('#dir').attr('readonly', 'readonly');
		return;
	}
	var html = '';
	for (var i=0; i < domains.length; i++)
	{
		html = html + '<div style="cursor:pointer;" onclick="hg_select_domain(\'' + domains[i].domain + '\', \'' + domains[i].dir + '\');">' + domains[i].domain + '</div>';
	}
	$('#show_domains').html(html);
	$('#dir').val('');
	$('#dir').attr('readonly', false);
	$('#show_domains').show();
}

function hg_select_domain(domain, dir)
{
	$('#domain').val(domain);
	$('#dir').val(dir);
	$('#show_domains').hide();
	$('#dir').attr('readonly', 'readonly');
}
</script>
<h2>正在安装[{$appinfo['name']}_{$appinfo['version']}]应用</h2>
{if $appinfo['sourceapp']}{$appinfo['sourceapp']['name']}依赖于{$appinfo['name']},需先安装{$appinfo['name']}{/if}
<div class="wrap n">
	<form name="editform" action="" method="post" class="ad_form h_l" >
		{if $message}
		<div style="color:red;" id="msg" class="msg">{$message}</div>
		{/if}
			<ul class="form_ul">
			{if $hasdb}
			<li class='i'>
				<span>数据库位置:</span>
				{code}
				$hg_attr['onchange'] = ' id="sdb" onchange="hg_select_db(this.value)"';
				{/code}
				{template:form/select,dbserver,$formdata['dbserver'],$servers['db'],$hg_attr}
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span  class="title">数据库名：</span>
					<input type="text" value="{$formdata['database']}" name='database' style="width:150px;">
					<span  class="title">覆盖数据库：</span>
					<input type="checkbox" value="1" name='cover' style="width:50px;">
					<font class="important">选用的数据库</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span  class="title">表前缀：</span>
					<input type="text" value="{$formdata['dbprefix']}" name='dbprefix' style="width:50px;">
					<font class="important">数据库表前缀</font>
				</div>
			</li>
			{/if}
			<li class='i'>
				<span>程序位置:</span>
				{code}
				$hg_attr['onchange'] = ' id="sapp" onchange="hg_select_app(this.value)"';
				{/code}
				{template:form/select,appserver,$formdata['appserver'],$servers['app'],$hg_attr}
			</li>
			<li class='i'>
				<span>访问域名:</span>
				<input type="text" name="apidomain" id="domain" size="30" value="{$formdata['apidomain']}" onkeyup="hg_check_domains(this.value);" onfocus="hg_check_domains(this.value);" autocomplete="off" />
				<font class="important">如:{$formdata['example_domain']}</font>
				<div id="show_domains">
				</div>
			</li>
			<li class='i'>
				<span>程序目录:</span>
				<input type="text" name="dir" id="dir"  size="30" value="{$formdata['dir']}" />
				<font class="important" style="color:red">一旦设定，程序将强制覆盖此目录下所有文件，不可恢复</font>
			</li>
			<li class='i'>
				<span  class="title" style="width:100px;">应用包已下载：</span>
				<input type="checkbox" value="1" name='haveapi' style="width:50px;">
				<span  class="title" style="width:120px;">应用素材包已下载：</span>
				<input type="checkbox" value="1" name='havemat' style="width:50px;">
			</li>
			<li class='i'>
				<input type="submit" name="sub" value="下一步" class="button_6_14" style="float:left;margin-left:20px;" />
			</li>
			<input type="hidden" name="a" value="doinstall" />
			<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			<input type="hidden" name="goon" value="1" />
		</ul>
	</form>
</div>

<div id="adddbserver" class="create_server" style="display:none">
<h3>新增数据库服务器</h3>
<form name="dbform" id="dbform" action="?a=add_dbserver" method="post" class="form" onsubmit="return hg_ajax_submit('dbform');">
<div style="color:red;" id="dberrmsg" class="msg"></div>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">名称：</span>
			<input type="text" value="" name='db[name]' style="width:200px;">
			<font style="color:gray;font-size:12px;"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">主机名：</span>
			<input type="text" value="{$settings['db']['host']}" name='db[host]' style="width:200px;">
			<font class="important" style="color:red">IP或者域名，必填</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">用户名：</span>
			<input type="text" value="{$settings['db']['user']}" name='db[user]' style="width:150px;">
			<font class="important" style="color:red">数据库连接用户名</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">密码：</span>
			<input type="text" value="" name='db[pass]' style="width:150px;">
			<font class="important" style="color:red">数据库连接密码</font>
		</div>
	</li>
</ul>
<input type="hidden" name="app" value="{$_INPUT['app']}" />
<input type="submit" name="rsub" value=" 确定 " class="button_4" />
<input type="button" name="rsub" value=" 取消 " class="button_4" onclick="$('#adddbserver').hide();" />
</form>
</div>

<div id="addappserver" class="create_server" style="display:none">
<h3>新增应用服务器</h3>
<form name="appform" id="appform" action="?a=add_appserver" method="post" class="form" onsubmit="return hg_ajax_submit('appform');">
<div style="color:red;" id="apperrmsg" class="msg"></div>
	<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">服务器名称：</span>
			<input type="text" value="" name='appserver[name]' style="width:200px;">
			<font style="color:gray;font-size:12px;"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">主机内网IP：</span>
			<input type="text" value="" name='appserver[ip]' style="width:200px;">
			<font style="color:gray;font-size:12px;"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">对外访问ip：</span>
			<input type="text" value="" name='appserver[outip]' style="width:200px;">
			<font style="color:gray;font-size:12px;">可不填</font>
		</div>
	</li>
</ul>
<input type="hidden" name="appserver[server_software]" value="nginx" />
<input type="hidden" name="app" value="{$_INPUT['app']}" />
<input type="submit" name="rsub" value=" 确定 " class="button_4" />
<input type="button" name="rsub" value=" 取消 " class="button_4" onclick="$('#addappserver').hide();" />
</form>
</div>
{template:foot}