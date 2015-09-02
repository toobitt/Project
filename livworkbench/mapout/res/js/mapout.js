function change_servertype(type, id)
{
	if (!type || type == '0')
	{
		return;
	}
	if (id == 'undefined')
	{
		id = -1;
	}
	url = 'ajax.php?action=getservertype';
	data = {
		type : type,
		id : id,
	};
	hg_request_to(url, data, 'get', 'change_servertype_callback', 1);
}

var change_servertype_callback = function (data)
{
	var obj = eval(data);
	$('#servertype').html(obj.html);
	$('#servtyp').val(obj.servtyp);
}

function sync_text(v)
{
	$('input[class="apidomain"]').each(function() 
	{
		$(this).val(v);
	});
}

function check_server_connect()
{
	var ip = $('#ip').val();
	var port = $('#port').val();
	if (ip == '' || port == '')
	{
		return;
	}
	url = 'ajax.php?action=check_server_connect';
	data = {
		ip : ip,
		port : port,
	};
	hg_request_to(url, data, 'get', 'check_server_connect_callback', 1);
}
var check_server_connect_callback = function (data)
{
	var obj = eval(data);
	if (!obj.connected)
	{
		$('#check_server_connect_result').show();
	}
	else
	{
		$('#check_server_connect_result').hide();
	}
}

function check_server_pass()
{
	var ip = $('#ip').val();
	var port = $('#port').val();
	var user = $('#user').val();
	var pass = $('#pass').val();
	if (ip == '' || port == '' || user == '' || pass == '')
	{
		return;
	}
	url = 'ajax.php?action=check_server_pass';
	data = {
		ip : ip,
		port : port,
		user : user,
		pass : pass,
	};
	hg_request_to(url, data, 'get', 'check_server_pass_callback', 1);
}
var check_server_pass_callback = function (data)
{
	var obj = eval(data);
	if (!obj.match)
	{
		$('#check_server_pass_result').show();
	}
	else
	{
		$('#check_server_pass_result').hide();
	}
}
function ls(objid, val)
{
	if (val == 0)
	{
		$('#sel_dir').hide();
		return;
	}
	var ip = $('#ip').val();
	var port = $('#port').val();
	var user = $('#user').val();
	var pass = $('#pass').val();
	if (ip == '' || port == '' || user == '' || pass == '')
	{
		$('#sel_dir').hide();
		return;
	}
	url = 'ajax.php?action=ls';
	data = {
		ip : ip,
		port : port,
		user : user,
		pass : pass,
		para : val,
		objid : objid,
	};
	hg_request_to(url, data, 'get', 'ls_callback', 1);
}
var ls_callback = function (data)
{
	var obj = eval(data);
	if (obj.html == '')
	{
		$('#sel_dir').hide();
	}
	else
	{
		$('#sel_dir').show();
		$('#sel_dir').html(obj.html);
		$('#' + obj.objid).val(obj.para);
		gTimer = setTimeout(function(){hide_ele();}, 2000);
	}
	$('#mkdir').bind('click', function(){mkdir(obj.objid, obj.para)});
}

function mkdir(objid, val)
{
	var html = '<input type="text" id="dirname" name="dirname" value="" size="12" onblur="domkdir(\'' + objid + '\', \'' + val + '\')" />';
	$('#mkdir').html(html);
	$('#dirname').val('');
	$('#dirname').focus();
	$('#mkdir').unbind();
}
function domkdir(objid, val)
{
	var dirname = $('#dirname').val();
	if (dirname == '')
	{
		ls(objid, val);
		return;
	}
	var ip = $('#ip').val();
	var port = $('#port').val();
	var user = $('#user').val();
	var pass = $('#pass').val();
	if (ip == '' || port == '' || user == '' || pass == '')
	{
		ls(objid, val);
		return;
	}
	url = 'ajax.php?action=mkdir';
	data = {
		ip : ip,
		port : port,
		user : user,
		pass : pass,
		fdir : val,
		para : dirname,
		objid : objid,
	};
	hg_request_to(url, data, 'get', 'mkdir_callback', 1);
}
var mkdir_callback = function (data)
{
	var obj = eval(data);
	ls(obj.objid, obj.dir);
}
var hide_ele = function()
{
	$('#sel_dir').hide();
}


function showdb(dbsel, objid, db)
{
	if (db == '' || !db || db == -1)
	{
		return;
	}
	url = 'ajax.php?action=showdb';
	data = {
		db : db,
		objid : objid,
		dbsel : dbsel,
	};
	hg_request_to(url, data, 'get', 'showdb_callback', 1);
}
var showdb_callback = function (data)
{
	var obj = eval(data);
	var objid = data.objid;
	var dbsel = data.dbsel;
	var error = data.error;
	var dbid = data.dbid;
	var defaultdb = $('#' + objid).val();
	if (error != '')
	{
		alert(error);
		return;
	}
	var html = '<select onchange="hg_choice_db(\'' + dbid + '\', \'' + dbsel + '\',\'' + objid + '\',\'' + defaultdb + '\',this.value)"><option>-请选择-</option>';
	var dbs = obj.dbs;
	html = html + '<option value="-1">新建数据库</option>';
	if (dbs.length > 0)
	{
		for (var i=0; i<dbs.length; i++)
		{
			html = html + '<option>' + dbs[i] + '</option>';
		}
	}
	html = html + '</select>';
	$('#' + dbsel).html(html);
	$('#' + dbsel).show();
}

function hg_choice_db(dbid, dbsel, objid, defaultdb, val)
{
	if (val == -1)
	{
		var dbname = window.prompt('请输入数据库名:', defaultdb);

		if (dbid && dbname)
		{
			url = 'ajax.php?action=createdb';
			data = {
				db : dbid,
				objid : objid,
				dbsel : dbsel,
				dbname : dbname,
			};
			hg_request_to(url, data, 'get', 'showdb_callback', 1);
		}
		return;
	}
	$('#' + objid).val(val);
}
