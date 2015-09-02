<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
<style>
.create_server {position:absolute;width:600px;margin:auto;border:1px solid #999;background:#ccc;top:100px;padding:10px;}
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
<h2>正在更新[{$appinfo['name']}_{$appinfo['version']}]应用</h2>
<div class="wrap n">
	<form name="editform" action="" method="post" class="ad_form h_l" >
		{if $message}
		<div style="color:red;" id="msg" class="msg">{$message}</div>
		{/if}
			<ul class="form_ul">
			<li class='i'>
				<span>程序位置:</span>
				{$installinfo['host']}
			</li>
			<li class='i'>
				<span>程序目录:</span>
				{$installinfo['dir']}
			</li>
			{if $dbinfo}
			<li class='i'>
				<span>数据库位置:</span>{$dbinfo['host']}
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span  class="title">数据库名：</span>{$dbinfo['database']}
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span  class="title">表前缀：</span>
					{$dbinfo['dbprefix']}
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span  class="title">数据库用户：</span>
					<input type="text" value="{$dbinfo['user']}" name='dbuser' style="width:150px;">
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span  class="title">数据库密码：</span>
					<input type="text" value="" name='dbpass' style="width:150px;">
				</div>
			</li>
			{/if}
			<li class='i'>
				<span  class="title" style="width:100px;">应用包已更新：</span>
				<input type="checkbox" value="1" name='haveapi' style="width:50px;">
				<span  class="title" style="width:120px;">应用素材包已更新：</span>
				<input type="checkbox" value="1" name='havemat' style="width:50px;">
			</li>
			<li class='i'>
				<input type="submit" name="sub" value="确认更新" class="button_6_14" style="float:left;margin-left:20px;" />
			</li>
			<input type="hidden" name="a" value="doupgrade" />
			<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			<input type="hidden" name="goon" value="1" />
		</ul>
	</form>
</div>
{template:foot}