function show_opration_button(btn, showElem)
{
	for(var i = 0; i < btn.length; i++){
		btn[i].onmouseover = function(){
			$(this).find(showElem).show();
		};
		btn[i].onmouseout = function(){
			$(this).find(showElem).hide();
		};

	}
}

$(function(){
	show_opration_button($(".cz"), ".show_opration_button");
});

/*用户编辑*/
function hg_userDel(uid)
{
	if (confirm('确定删除该选项吗？'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + uid;
		hg_ajax_post(url);
	}
}

/*邮件发送方式*/
function hg_mailsend(type)
{
	switch (type)
	{
		case '1':
			$('#mail_server_port').hide();
			$('#mail_auth_from').hide();
			break;
		case '2':
			$('#mail_server_port').show();
			$('#mail_auth_from').show();
			break;
		default:
			$('#mail_server_port').show();
			$('#mail_auth_from').hide();
			break;
	}
	hg_resize_nodeFrame();
}

/*应用删除*/
function hg_appDel(id)
{
	if (confirm('确定删除该选项吗？'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id;
		hg_ajax_post(url);
	}
}
/*url应用安装*/
function hg_installtype(type)
{
	if (type == 1)
	{
		$('#box_1').hide();
		$('#box_2').hide();
		$('#box_3').hide();
		$('#sub').hide();
		$('#box_5').show();
	}
	else
	{
		$('#box_1').show();
		$('#box_2').show();
		$('#box_3').show();
		$('#sub').show();
		$('#box_5').hide();
	}
	hg_resize_nodeFrame();
}

function appConfig_back(obj)
{
	var obj = $.parseJSON(obj);
	if (obj.appid)
	{
		$('#conf_box').show();
		var conf = 'define(\'UC_CONNECT\', \''+obj.conf.UC_CONNECT+'\');\n'; 
			conf += 'define(\'UC_DBHOST\', \''+obj.conf.UC_DBHOST+'\');\n'; 
			conf += 'define(\'UC_DBUSER\', \''+obj.conf.UC_DBUSER+'\');\n'; 
			conf += 'define(\'UC_DBPW\', \''+obj.conf.UC_DBPW+'\');\n'; 
			conf += 'define(\'UC_DBNAME\', \''+obj.conf.UC_DBNAME+'\');\n'; 
			conf += 'define(\'UC_DBCHARSET\', \''+obj.conf.UC_DBCHARSET+'\');\n'; 
			conf += 'define(\'UC_DBTABLEPRE\', \''+obj.conf.UC_DBTABLEPRE+'\');\n'; 
			conf += 'define(\'UC_DBCONNECT\', \''+obj.conf.UC_DBCONNECT+'\');\n'; 
			conf += 'define(\'UC_KEY\', \''+obj.conf.UC_KEY+'\');\n'; 
			conf += 'define(\'UC_API\', \''+obj.conf.UC_API+'\');\n'; 
			conf += 'define(\'UC_CHARSET\', \''+obj.conf.UC_CHARSET+'\');\n'; 
			conf += 'define(\'UC_IP\', \''+obj.conf.UC_IP+'\');\n'; 
			conf += 'define(\'UC_APPID\', \''+obj.conf.UC_APPID+'\');\n'; 
			conf += 'define(\'UC_PPP\', \''+obj.conf.UC_PPP+'\');\n'; 
		$('#getconfig').text(conf);
		hg_resize_nodeFrame();
	}
}