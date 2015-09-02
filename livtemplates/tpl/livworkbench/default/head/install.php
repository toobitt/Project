<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"{$_scroll_style}>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$menu[$cur]}_{$this->mTemplatesTitle}安装</title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
{css:default}
{css:public}
{js:jquery.min}
{js:jquery.form}
{js:alertbox.min}
{js:alertbox}
{js:md5}
{js:common}
{js:ajax}
<script type="text/javascript">
var TIME_OUT = 30000;
function hg_getDb()
{
	url = 'index.php?a=showdb';
	data = {
		host : $('#host').val(),
		user : $('#user').val(),
		pass : $('#pass').val(),
	};
	hg_request_to(url, data, 'get', 'hg_showDb', 1);
}

var hg_showDb = function (data)
{
	var obj = eval(data);
	var html = '<select name="dbse" onchange="$(\'#database\').val(this.value)"><option>-请选择-</option>';
	var dbs = obj.dbs;
	for (var i=0; i<dbs.length; i++)
	{
		html = html + '<option>' + dbs[i] + '</option>';
	}
		html = html + '</select>';
	$('#dbs').html(html);
	$('#dbs').show();
}
</script>
</head>
<body>
<ul>
{foreach $menu AS $k => $v}
{code}
	if ($k == $cur)
	{
		$style = ' style="color:red"';
	}
	else
	{
		$style = '';
	}
{/code}
<li{$style}>{$v}</li>
{/foreach}
</ul>