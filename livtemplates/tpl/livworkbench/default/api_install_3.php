<?php 
/* $Id: crontab.php 3047 2011-11-07 02:17:27Z repheal $ */
?>
{template:head}
<style type="text/css">
.db_config{width:560px;height:86px;border:1px dotted #D9D9D9;margin-left:20px;margin-top:20px;}
.title_info{width:200px;margin-top:10px;font-size:13px;font-weight:blod;margin-left:55px;}
.dbconfig_info{width:560px;height:268px;border:1px dotted #D9D9D9;margin-left:20px;margin-top:20px;}
.m_t{margin-top:15px;margin-left:20px;}
.b_g{background:url("{$RESOURCE_URL}loading.gif") no-repeat;}
.info_box{width:100px;height:30px;}
</style>
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_system first"><em></em><a>安装应用</a></li>
			<li class="nav_system dq"><em></em><a>设置HOST</a></li>
			<li class="nav_system dq"><em></em><a>目录权限检测</a></li>
			<li class="nav_system dq"><em></em><a>数据库配置</a></li>
			<li class="nav_system"><em></em><a>参数配置</a></li>
			<li class="nav_system"><em></em><a>完成</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap n">
	<form name="editform" action="" method="post">
		{if $msg}
		<div style="color:red;" id="msg" class="msg">{$msg}</div>
		{/if}
		<div class="db_config">
			<div class="title_info">{$appinfo['name']}{$appinfo['version']} ( {$appinfo['description']} ) </div>
			<div style="height:100px;width:100%;margin-top:20px;">
				<div style="width:48px;float:left;font-size:13px;margin-left:55px;">数据库:</div>
				{code}
						$item_source = array(
							'class' => 'down_list i',
							'show' => 'item_shows_',
							'width' => 155,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
							'onclick' => 'hg_change_db();'
						);
						$default = 1;
				{/code}
				<div style="float:left;width:122px;"> 
					{template:form/search_source,db,$default,$serversopt,$item_source}
				</div>
				<input type="hidden" name="servs" id="servs" value="{$servers}" />
			</div>
		</div>
		<div class="dbconfig_info"> 
			<ul class="form_ul">
				<li class="i m_t">
					<div class="form_ul_div clear">
						<span class="title">数据库服务器：</span><input type="text" name="host" id="host" value="{$formdata['host']}" />
					</div>
				</li>
				<li class="i m_t">
					<div class="form_ul_div clear">
						<span class="title">　数据库用户：</span><input type="text" name="user" id="user" value="{$formdata['user']}" />
					</div>
				</li>
				<li class="i m_t">
					<div class="form_ul_div clear">
						<span class="title">　数据库密码：</span><input type="password" name="pass" id="pass" value="{$formdata['pass']}" />
					</div>
				</li>
				<li class="i m_t">
					<div class="form_ul_div clear" style="position:relative;">
						<span class="title">　数据库名称：</span><input type="text" name="database" id="database" onfocus="hg_getDb()" value="{$formdata['database']}" />
						<div id="dbs" class="info_box" style="position:absolute;top:0px;left:249px;width:280px;"></div>
					</div>
				</li>
				<li class="i m_t">
					<div class="form_ul_div clear">
						<span class="title">　数据表前缀：</span><input type="text" name="dbprefix" id="dbprefix" value="{$formdata['dbprefix']}" />
					</div>
				</li>
				<li class="i m_t">
					<div>
						<div style="width:73px;float:left;margin-top:2px;margin-left:12px;">覆盖数据表：</div><input type="checkbox" name="cover" id="cover" value="1" />
					</div>
				</li>
			</ul>
		</div>
		<input type="hidden" name="a" value="{$a}" />
		<input type="hidden" name="apihost" value="{$_INPUT['apihost']}" />
		<input type="hidden" name="apidir" value="{$_INPUT['apidir']}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="goon" value="1" />
		<input type="submit" name="sub" value="下一步" class="button_6_14" style="margin-left:20px;margin-top:20px;"/>
	</form>
</div>
<script type="text/javascript">
function hg_change_db()
{
	alert(1);
	var servers =  $('#servs').val();
	alert(servers);
	eval(servers);
}
function hg_getDb()
{
	$('#dbs').addClass('b_g').html('');
	url = 'api_install.php?a=showdb';
	data = {
		host : $('#host').val(),
		user : $('#user').val(),
		pass : $('#pass').val(),
	};
	hg_request_to(url, data, 'get', 'hg_showDb', 1);
}

var hg_showDb = function (data)
{
	$('#dbs').removeClass('b_g');
	if(data.errorcode)
	{
		$('#dbs').html('<span style="color:red;">'+data.errorinfo+'</span>').show();
	}
	else
	{
		var all_db = data.dbs;
		var html = '<select name="dbse" onchange="$(\'#database\').val(this.value)"><option disabled="disabled">-请选择-</option>';
		for (var i=0; i<all_db.length; i++)
		{
			html = html + '<option>' + all_db[i] + '</option>';
		}
			html = html + '</select>';
		$('#dbs').html(html);
		$('#dbs').show();
	}
}
</script>
{template:foot}