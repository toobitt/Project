<?php
set_time_limit(0);
$Cfg['upgradeServer'] = array(
	'host' => 'upgrade.hogesoft.com',
	'port' => 233,
	'dir' => '',
);
$Cfg['authServer'] = array(
	'host' => 'auth.hogesoft.com',
	'port' => 233,
	'dir' => '',
);
header('Content-Type:text/html; charset=utf-8');
// PHP 6 以后不需要再执行下面的操作
if (PHP_VERSION < '6.0.0')
{
	@set_magic_quotes_runtime(0);

	define('MAGIC_QUOTES_GPC', @get_magic_quotes_gpc() ? true : false);
	if (MAGIC_QUOTES_GPC)
	{
		function stripslashes_vars(&$vars)
		{
			if (is_array($vars))
			{
				foreach ($vars as $k => $v)
				{
					stripslashes_vars($vars[$k]);
				}
			}
			else if (is_string($vars))
			{
				$vars = stripslashes($vars);
			}
		}

		if (is_array($_FILES))
		{
			foreach ($_FILES as $key => $val)
			{
				$_FILES[$key]['tmp_name'] = str_replace('\\', '\\\\', $val['tmp_name']);
			}
		}

		foreach (array('_REQUEST', '_GET', '_POST', '_COOKIE', '_FILES') as $v)
		{
			stripslashes_vars($$v);
		}
	}

	define('SAFE_MODE', (@ini_get('safe_mode') || @strtolower(ini_get('safe_mode')) == 'on') ? true : false);
}
else
{
	define('MAGIC_QUOTES_GPC', false);
	define('SAFE_MODE', false);
}
define('TIMENOW', isset($_SERVER['REQUEST_TIME']) ? (int) $_SERVER['REQUEST_TIME'] : time());

define('CACHE_DIR', './db/');
if (!is_dir(CACHE_DIR))
{
	hg_errorReport(CACHE_DIR . '目录不存在，请创建此目录并将目录权限设为0777');
}
if (!is_writeable(CACHE_DIR))
{
	hg_errorReport(CACHE_DIR . '目录不可写，请创建此目录并将目录权限设为0777');
}
$action = trim($_REQUEST['action']);
if (!$action)
{
	$action = 'start';
}
if ($action != 'start' && $action != 'step1')
{
	$applyed_auth = @file_get_contents(CACHE_DIR . 'auth.tmp');
	if (!$applyed_auth)
	{
		header('Location:?action=start');
	}
	$applyed_auth = json_decode($applyed_auth, 1);
	if (!$applyed_auth['appid'])
	{
		header('Location:?action=start');
	}
	define('CUSTOM_APPID', $applyed_auth['appid']);
	define('CUSTOM_APPKEY', $applyed_auth['appkey']);
}
if (function_exists($action))
{
	$action();
}
else
{
	start();
}
function start($message = '')
{
	$auth = @file_get_contents(CACHE_DIR . 'auth.tmp');
	if ($auth)
	{
		$auth = json_decode($auth, 1);
		if ($auth)
		{
			header('Location:?action=step2');
		}
	}
	head('申请授权');
	?>
 <h2>申请授权</h2>
 <div style="color:red;"><?php echo $message;?></div>
<form action="?action=step1" name="start" method="post">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
<span class="title">客户名称：</span><input type="text" name="custom_name" value="<?php echo $_REQUEST['custom_name'];?>" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">　　简称：</span><input type="text" name="display_name" value="<?php echo $_REQUEST['display_name'];?>" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">客户标识：</span><input type="text" name="bundle_id" value="<?php echo $_REQUEST['bundle_id'];?>" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">授权域名：</span><input type="text" name="domain" value="<?php echo $_REQUEST['domain'];?>" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">客户描述：</span><textarea name="custom_desc" style="width:400px;height:100px;" cols="60" rows="5"><?php echo $_REQUEST['custom_desc'];?></textarea>
</div>
</li>
</ul>
<br />
<input type="submit" name="sub" value="下一步" />
</form>
	<?php
	foot();
}
function step1()
{
	$auth = @file_get_contents(CACHE_DIR . 'auth.tmp');
	if ($auth)
	{
		$auth = json_decode($auth, 1);
		if ($auth)
		{
			header('Location:?action=step2');
		}
	}
	global $Cfg;
	$curl = new curl($Cfg['authServer']['host'] . ':' . $Cfg['authServer']['port'], '', $Cfg['authServer']['token']);
	$curl->setSubmitType('get');
	$curl->setReturnFormat('json');
	$curl->initPostData();
	$curl->setErrorReturn('goon');
	$curl->addRequestData('a', 'create');
	$curl->addRequestData('custom_name', $_REQUEST['custom_name']);
	$curl->addRequestData('display_name', $_REQUEST['display_name']);
	$curl->addRequestData('domain', $_REQUEST['domain']);
	$curl->addRequestData('bundle_id', $_REQUEST['bundle_id']);
	$curl->addRequestData('custom_desc', $_REQUEST['custom_desc']);
	$ret = $curl->request('admin/auth_update.php');
	if (!$ret[0]['appid'])
	{
		$message = $ret['ErrorCode'];
		start($message);
		exit;
	}
	$licenseinfo = json_encode($ret[0]);
	file_put_contents(CACHE_DIR . 'auth.tmp', $licenseinfo);
	header('Location:?action=step2');
}
function step2($message = '')
{
	global $applyed_auth;
	if (!$_REQUEST['app'])
	{
		$ip = gethostbyname($_SERVER["SERVER_NAME"]);
		$_REQUEST['app'] = array(
			'ip' => $ip,	
			'outip' => $ip,	
			'php_run_type' => 'socket',	
			'php_socket_path' => '/dev/shm/php-cgi.sock',	
			'domain' => 'm2o.' . $applyed_auth['domain'],	
		);
	}
	$server_software = $_SERVER['SERVER_SOFTWARE'];

	head('设置程序安装目录');
	?>
 <h2>设置程序安装目录</h2>
	 <div style="color:red;"><?php echo $message;?></div>
	<form action="?action=step3" name="start" method="post">
	<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">&nbsp;&nbsp;&nbsp;访问域名：</span>
			<input type="text" value="<?php echo $_REQUEST['app']['domain']; ?>" name='app[domain]' style="width:200px;">
			<font style="color:gray;font-size:12px;"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">&nbsp;&nbsp;&nbsp;安装目录：</span>
			<input type="text" value="<?php echo $_REQUEST['app']['dir']; ?>" name='app[dir]' style="width:200px;">
			<font style="color:gray;font-size:12px;"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;php运行方式：</span>
			<input type="text" value="<?php echo $_REQUEST['app']['php_run_type']; ?>" name='app[php_run_type]' style="width:80px;" onblur="if(this.value == 'socket'){document.getElementById('php_socket_path').style.display='';}else{document.getElementById('php_socket_path').style.display='none';}">&nbsp;&nbsp;
			<input type="text" id="php_socket_path" value="<?php echo $_REQUEST['app']['php_socket_path']; ?>" name='app[php_socket_path]' style="width:200px;">
			<font style="color:red;font-size:12px;">重要，请核实</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;主机内网IP：</span>
			<input type="text" value="<?php echo $_REQUEST['app']['ip']; ?>" name='app[ip]' style="width:200px;">
			<font style="color:gray;font-size:12px;"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;对外访问ip：</span>
			<input type="text" value="<?php echo $_REQUEST['app']['outip']; ?>" name='app[outip]' style="width:200px;">
			<font style="color:gray;font-size:12px;"></font>
		</div>
	</li>
</ul>
<br />
<input type="hidden" name="app[server_software]" value="nginx" />
<input type="submit" name="sub" value="下一步" />
</form>
	<?php
	foot();
}

function step3()
{
	$app = $_REQUEST['app'];
	if (strstr($app['domain'], '/'))
	{
		step2('只能填写域名，不能带有/');
	}
	if(!in_array($app['php_run_type'], array('tcp', 'socket')))
	{
		step2('php运行方式只能是tcp或者socket');
	}
	$php_socket_path = trim($app['php_socket_path']);
	if (!$php_socket_path)
	{
		$php_socket_path = '/dev/shm/php-cgi.sock';
	}
	$app['port'] = 6233;
	$socket = new hgSocket();
	$con = $socket->connect($app['ip'], $app['port']);
	if (!intval($con))
	{
		step2('服务器无法连接，请确认服务器ip是否正确或服务程序hogeMonitor.py是否监听在'. $app['ip'] . ':' . $app['port'] . '上');
	}
	$socket->close();
	$app['dir'] = str_replace('//', '/', $app['dir']);
	$app_dir = trim($app['dir'], '/');
	if (count(explode('/', $app_dir)) < 3)
	{
		step2('安装目录必须达到3级及以上');
	}
	$app['dir'] = '/' . $app_dir . '/';
	//print_r($_SERVER);
	//增加hosts
	ob_start();
	$hostscontent = get_serv_file( $app, '/etc/hosts');
	$hosts = hg_get_hosts($hostscontent);
	if(!$hosts[$app['domain']])
	{
		hg_flushMsg('开始修改服务器hosts');
		$hostscontent .= "\n" . $app['ip'] . '	' . $app['domain'];
		write_serv_file( $app, '/etc/hosts', $hostscontent);
		hg_flushMsg('hosts配置完成');
	}
	//配置nginx
	if($app['server_software'] == 'nginx')
	{
		hg_flushMsg('开始修改服务器nginx配置');
		$content = get_serv_file( $app, '/usr/local/nginx/conf/nginx.conf');
		if ($app['php_run_type'] == 'socket')
		{
			$fastcgi_pass = '
				fastcgi_pass unix:' . $php_socket_path . ';';
		}
		else
		{
			$fastcgi_pass = '
				fastcgi_pass   127.0.0.1:9000;
				fastcgi_param  SCRIPT_FILENAME  $htdocs$fastcgi_script_name;';
		}
		$runtype ='
			root          $htdocs;' . $fastcgi_pass . '
			fastcgi_index  index.php;
			include        fastcgi_params;
		';
		if (!@preg_match('/server_name\s+' . $app['domain'] . '/is', $content))
		{
			$serv_content = 'server {
				set $htdocs ' . $app['dir'] . ';
				listen       80;
				server_name  ' . $app['domain'] . ';

				#charset koi8-r;

				#access_log  logs/host.access.log  main;

				location / {
					root   $htdocs;
					index  index.html index.htm index.php;
				}
				location ~ .*\.php?$ {' . $runtype . '}
			}';
			$content = preg_replace("/(http[\s\n]*\{.*)\}/is", '\\1' . $serv_content . '}', $content);
			write_serv_file( $app, '/usr/local/nginx/conf/nginx.conf', $content);
			hg_run_cmd( $app, 'restart', '/usr/local/nginx/sbin/nginx -s reload');
			hg_flushMsg('nginx配置完成');
		}
	}
	//下载程序
	global $Cfg;
	$curl = new curl($Cfg['upgradeServer']['host'] . ':' . $Cfg['upgradeServer']['port'], '', $Cfg['upgradeServer']['token']);
	$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
	$curl->setSubmitType('get');
	$curl->setReturnFormat('json');
	$curl->initPostData();
	$curl->addRequestData('install', 1);
	$curl->addRequestData('app', 'livworkbench');
	$program_url = $curl->request('check_version.php');
	if (!(strstr($program_url, 'http://') && strstr($program_url, '.zip')) || $program_url == 'NO_VERSION')
	{
		$message = '获取应用程序失败或程序版本不存在.';
	}
	hg_flushMsg('开始下载程序');
	hg_run_cmd( $app, 'mkdirs', $app['dir']);
	hg_run_cmd( $app, 'download', $program_url, $app['dir']);
	hg_run_cmd( $app, 'mkdirs', $app['dir'] . 'cache');
	file_put_contents(CACHE_DIR . 'app.tmp', json_encode($app));
	hg_flushMsg('程序下载完毕');
	hg_redirect('?action=step4');
}

function step4($message = '')
{
	$db = @file_get_contents(CACHE_DIR . 'db.tmp');
	if ($db)
	{
		$auth = @file_get_contents(CACHE_DIR . 'auth.tmp');
		$auth = json_decode($auth, 1);
		$db = json_decode($db, 1);
		$db['pass'] = hg_encript_str($db['pass'], true, $auth['appkey']);
	}
	else
	{
		$db = array(
		'host' => '127.0.0.1',	
		'user' => 'forapp',	
		'pass' => '',	
		'database' => 'm2o',	
		'dbprefix' => 'm2o_',	
	);
	}
	$_REQUEST['db'] = $_REQUEST['db'] ? $_REQUEST['db'] : $db;
	head('设置数据库');
	?>
 <h2>设置数据库</h2>
 <div style="color:red;"><?php echo $message;?></div>
	<form action="?action=step5" name="start" method="post">
	<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">&nbsp;&nbsp;&nbsp;主机名：</span>
			<input type="text" value="<?php echo $_REQUEST['db']['host']; ?>" name='db[host]' style="width:200px;">
			<font style="color:gray;font-size:12px;">IP或者域名，必填</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">&nbsp;&nbsp;&nbsp;用户名：</span>
			<input type="text" value="<?php echo $_REQUEST['db']['user']; ?>" name='db[user]' style="width:150px;">
			<font style="color:gray;font-size:12px;">数据库连接用户名</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;密码：</span>
			<input type="text" value="" name='db[pass]' style="width:80px;">
			<font style="color:gray;font-size:12px;">数据库连接密码</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">数据库名：</span>
			<input type="text" value="<?php echo $_REQUEST['db']['database']; ?>" name='db[database]' style="width:150px;">
			<font style="color:gray;font-size:12px;">选用的数据库</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;前缀：</span>
			<input type="text" value="<?php echo $_REQUEST['db']['dbprefix']; ?>" name='db[dbprefix]' style="width:50px;">
			<font style="color:gray;font-size:12px;">数据库表前缀</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">覆盖数据库：</span>
			<input type="checkbox" value="1" name='db[cover]' style="width:50px;">
			<font style="color:gray;font-size:12px;"></font>
		</div>
	</li>
</ul>
<br />
<input type="submit" name="sub" value="下一步" />
</form>
	<?php
	foot();
}


function step5()
{
	$db = $_REQUEST['db'];
	
	if (!$db['host'] || !$db['user'] || !$db['database'])
	{
		step4('请设置数据库服务器地址，用户和数据库名');
	}
	$link = @mysql_connect($db['host'], $db['user'], $db['pass']);
	if (!$link)
	{
		step4($db['host'] . '连接失败，请确认输入正确的服务器地址,账号和密码');
	}
	mysql_query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary", $link);
	if (!$db['cover'])
	{
		$sql = "SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME='{$db['database']}'";
		$q = mysql_query($sql, $link);
		$r = mysql_fetch_array($q);
		if ($r)
		{
			step4($db['database'] . '数据库已存在，您可以勾选覆盖数据库');
		}
	}
	$app = file_get_contents(CACHE_DIR . 'app.tmp');
	$app = json_decode($app, 1);
	
	$socket = new hgSocket();
	$con = $socket->connect($app['ip'], $app['port']);
	if (!intval($con))
	{
		step4('安装无法完成，服务器无法连接，请确认服务器ip是否正确或服务程序hogeMonitor.py是否监听在'. $app['ip'] . ':' . $app['port'] . '上');
	}
	$socket->close();
	$auth = @file_get_contents(CACHE_DIR . 'auth.tmp');
	$auth = json_decode($auth, 1);
	$db['pass'] = hg_encript_str($db['pass'], true, $auth['appkey']);
	file_put_contents(CACHE_DIR . 'db.tmp', json_encode($db));
	global $Cfg;
	$curl = new curl($Cfg['upgradeServer']['host'] . ':' . $Cfg['upgradeServer']['port'], '', $Cfg['upgradeServer']['token']);
	$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
	$curl->setSubmitType('get');
	$curl->setReturnFormat('json');
	$curl->initPostData();
	$curl->addRequestData('install', 1);
	$curl->addRequestData('app', 'livworkbench');
	$ret = $curl->request('db.php');
	if (!is_array($ret))
	{
		hg_errorReport('获取数据库结构失败，无法完成安装，请联系软件提供商');
	}	
	
	$curl->initPostData();
	$curl->addRequestData('a', 'checklastversion');
	$curl->addRequestData('app', 'livworkbench');
	$lastversion = $curl->request('check_version.php');
	ob_start();
	if (!$db['cover'])
	{
		hg_flushMsg('开始创建数据库 ' . $db['database']);
		$sql = 'CREATE DATABASE ' . $db['database'] . ' DEFAULT CHARACTER SET UTF8';
		$q = mysql_query($sql, $link);	
		if (!$q)
		{	
			step4($db['database'] . '创建失败, 请确认此账号有权限创建数据库');
			continue;
		}
		hg_flushMsg('数据库 ' . $db['database'] . ' 已创建');
	}
	
	mysql_select_db($db['database'], $link);
	foreach ($ret AS $table => $sql)
	{
		if (substr($table, 0, 4) == 'liv_')
		{
			$table = $db['dbprefix'] . substr($table, 4);
			$sql = preg_replace('/CREATE\s+TABLE\s+([`]{0,1})liv_/is', 'CREATE TABLE \\1' . $db['dbprefix'], $sql);
		}
		if (substr($table, 0, 4) == 'm2o_')
		{
			$table = $db['dbprefix'] . substr($table, 4);
			$sql = preg_replace('/CREATE\s+TABLE\s+([`]{0,1})m2o_/is', 'CREATE TABLE \\1' . $db['dbprefix'], $sql);
		}
		hg_flushMsg('开始创建数据表 ' . $table);
		if ($db['cover'])
		{
			$dropsql = 'DROP table ' . $table;
			mysql_query($dropsql, $link);	
		}
		mysql_query($sql, $link);
	}
	hg_flushMsg('数据库创建完成 ');
	$content = get_serv_file( $app, $app['dir'] . 'conf/config.php');
	$content = preg_replace("/\\\$gGlobalConfig\s*\['App_.*'\]\s*=\s*array\s*(.*?);/is", $appstring, $content);		
	if ($db)
	{
		
		$db['pass'] = hg_encript_str($db['pass'], false, $auth['appkey']);
		$string = "\$gDBconfig = array(
'host'     => '{$db['host']}',
'user'     => '{$db['user']}',
'pass'     => '{$db['pass']}',
'database' => '{$db['database']}',
'charset'  => 'utf8',
'pconncet' => 0,
);";
		$content = preg_replace("/\\\$gDBconfig\s*=\s*array\s*(.*?);/is", $string, $content);
		$content = preg_replace("/define\('DB_PREFIX',\s*'.*?'\);/is","define('DB_PREFIX','{$db['dbprefix']}');", $content);
	}			
	$match = preg_match("/define\('INITED_APP',\s*.*?\s*\);/is", $content);
	if($match)
	{
		$content = preg_replace("/define\('INITED_APP',\s*.*?\s*\);/is","define('INITED_APP', false);", $content);
	}
	else
	{
		$content = preg_replace("/\?>/is", "\ndefine('INITED_APP', false);\n?>", $content);
	}
	
	$content = preg_replace("/define\('APPID',\s*'*\d+'*\);/is","", $content);
	$content = preg_replace("/define\('APPKEY',\s*'.*?'\);/is","", $content);
	$content = preg_replace("/define\('CUSTOM_APPID',\s*\d+\);/is","define('CUSTOM_APPID','{$auth['appid']}');", $content);
	$content = preg_replace("/define\('CUSTOM_APPKEY',\s*'.*?'\);/is","define('CUSTOM_APPKEY','{$auth['appkey']}');", $content);
	$content = preg_replace("/define\('CUSTOM_NAME',\s*'.*?'\);/is","define('CUSTOM_NAME','{$auth['display_name']}');", $content);
	$content = preg_replace("/define\('DEBUG_MODE',\s*.*?\);/is","define('DEBUG_MODE',false);", $content);
	$content = preg_replace("/define\('DEVELOP_MODE',\s*.*?\);/is","define('DEVELOP_MODE',0);", $content);
	
	$match = preg_match("/\\\$gGlobalConfig\['license'\]\s*=\s*\'.*\';/is", $content);
	if($match)
	{
		$content = preg_replace("/\\\$gGlobalConfig\['license'\]\s*=\s*\'.*?\';/is", "\$gGlobalConfig['license'] = '{$auth['domain']}';", $content);
	}
	else
	{
		$content = preg_replace("/\?>/is", "\n\$gGlobalConfig['license'] = '{$auth['domain']}';\n?>", $content);
	}
	$match = preg_match("/\\\$gGlobalConfig\['version'\]\s*=\s*\'.*\';/is", $content);
	if($match)
	{
		$content = preg_replace("/\\\$gGlobalConfig\['version'\]\s*=\s*\'.*?\';/is", "\$gGlobalConfig['version'] = '{$lastversion}';", $content);
	}
	else
	{
		$content = preg_replace("/\?>/is", "\n\$gGlobalConfig['version'] = '{$lastversion}';\n?>", $content);
	}
	$match = preg_match("/\\\$gGlobalConfig\['mcphost'\]\s*=\s*\'.*\';/is", $content);
	if($match)
	{
		$content = preg_replace("/\\\$gGlobalConfig\['mcphost'\]\s*=\s*\'.*?\';/is", "\$gGlobalConfig['mcphost'] = '{$app['ip']}';", $content);
	}
	else
	{
		$content = preg_replace("/\?>/is", "\n\$gGlobalConfig['mcphost'] = '{$app['ip']}';\n?>", $content);
	}

	$match = preg_match("/\\\$gGlobalConfig\['php_run_type'\]\s*=\s*\'.*\';/is", $content);
	if($match)
	{
		$content = preg_replace("/\\\$gGlobalConfig\['php_run_type'\]\s*=\s*\'.*?\';/is", "\$gGlobalConfig['php_run_type'] = '{$app['php_run_type']}';", $content);
	}
	else
	{
		$content = preg_replace("/\?>/is", "\n\$gGlobalConfig['php_run_type'] = '{$app['php_run_type']}';\n?>", $content);
	}
	$match = preg_match("/\\\$gGlobalConfig\['php_socket_path'\]\s*=\s*\'.*\';/is", $content);
	if($match)
	{
		$content = preg_replace("/\\\$gGlobalConfig\['php_socket_path'\]\s*=\s*\'.*?\';/is", "\$gGlobalConfig['php_socket_path'] = '{$app['php_socket_path']}';", $content);
	}
	else
	{
		$content = preg_replace("/\?>/is", "\n\$gGlobalConfig['php_socket_path'] = '{$app['php_socket_path']}';\n?>", $content);
	}
	write_serv_file( $app, $app['dir'] . 'conf/config.php', $content, 'utf8');
	
	$template_conf = $app['dir'] . 'conf/template.conf.php';
	$content = get_serv_file( $app, $template_conf);
	if ($content)
	{
		global $Cfg;
		$curl = new curl($Cfg['upgradeServer']['host'] . ':' . $Cfg['upgradeServer']['port'], '', $Cfg['upgradeServer']['token']);
		
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->setSubmitType('get');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('install', 1);
		$curl->addRequestData('app', 'livtemplates');
		$templates_url = $curl->request('check_version.php');
		$content = preg_replace("/define\('CACHE_TEMPLATE',\s*.*?\);/is","define('CACHE_TEMPLATE',true);", $content);
		$content = preg_replace("/define\('RESOURCE_URL',\s*.*?\);/is","define('RESOURCE_URL','./res/images/');", $content);
		$content = preg_replace("/define\('SCRIPT_URL',\s*.*?\);/is","define('SCRIPT_URL','./res/scripts/');", $content);
		$content = preg_replace("/define\('COMBO_URL',\s*.*?\);/is","define('COMBO_URL','');", $content);
		$content = preg_replace("/define\('TEMPLATE_API',\s*.*?\);/is","define('TEMPLATE_API','{$templates_url}');", $content);
		write_serv_file( $app, $template_conf, $content, 'utf8');
	}
	
	$cron_conf = $app['dir'] . 'cron/config.py';
	$content = get_serv_file( $app, $cron_conf);
	if ($content)
	{
		$content = preg_replace("/CRON_TAB\s*=\s*\(.*?\)/is","CRON_TAB = ('{$app['domain']}', 80, 'token', 'crontab.php')", $content);
		write_serv_file( $app, $cron_conf, $content, 'utf8');
	}
	hg_flushMsg('配置系统完成');
	hg_redirect('?action=step6');
}

function step6($message = '')
{
	head('创建管理员');
	?>
<h2>创建管理员</h2>
 <div style="color:red;"><?php echo $message;?></div>
<form name="editform" action="?action=step7" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
<span class="title">账号名称：</span><input type="text" name="user_name" value="<?php echo $_REQUEST['user_name'];?>" size="20" />
<span style="color:red">此账号为超级管理员，拥有最高权限，请妥善保管。</span>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">　　密码：</span><input type="password" name="password" value="" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">确认密码：</span><input type="password" name="confirmpassword" value="" />
</div>
</li>
</ul>
<br />
<input type="submit" name="sub" value="创建管理员" />
</form>
	<?php
	foot();
}

function step7()
{
	$user_name = trim($_REQUEST['user_name']);
	$password = trim($_REQUEST['password']);
	$confirmpassword = trim($_REQUEST['confirmpassword']);
	if (!$user_name)
	{
		$message = '请设置用户名';
		step6($message);
	}
	if (!$password || $confirmpassword != $password)
	{
		$message = '未设置密码或两次密码不一致';
		step6($message);
	}
	$app = file_get_contents(CACHE_DIR . 'app.tmp');
	$app = json_decode($app, 1);
	$db = file_get_contents(CACHE_DIR . 'db.tmp');
	$db = json_decode($db, 1);
	$auth = @file_get_contents(CACHE_DIR . 'auth.tmp');
	$auth = json_decode($auth, 1);
	
	$socket = new hgSocket();
	$con = $socket->connect($app['ip'], $app['port']);
	if (!intval($con))
	{
		step6('安装无法完成，服务器无法连接，请确认服务器ip是否正确或服务程序hogeMonitor.py是否监听在'. $app['ip'] . ':' . $app['port'] . '上');
	}
	$socket->close();
	$password = md5($password);
	$content = get_serv_file( $app, $app['dir'] . 'conf/config.php');	
	$string = "\$gGlobalConfig['admin_user'] = array(
'{$user_name}'     => '{$password}',
);";
	$content = preg_replace("/\?>/is", "\n{$string}\n?>", $content);
	write_serv_file( $app, $app['dir'] . 'conf/config.php', $content, 'utf8');
	
	hg_run_cmd( $app, 'runcmd', 'chmod -Rf 777 ' . $app['dir'] . 'cache/ ' . $app['dir'] . 'conf/config.php');
	if ($db)
	{
		$pass = hg_encript_str($db['pass'], false, $auth['appkey']);
		$link = mysql_connect($db['host'], $db['user'], $pass);
		mysql_query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary", $link);
		mysql_select_db($db['database'], $link);
		
		$db_data = json_encode($db);
		$app_data = json_encode($app);
		$sql = 'INSERT INTO ' . $db['dbprefix'] . "server (name, brief, ip, outip, type, more_data,create_time) VALUES 
				('app数据库服务器', '', '{$db['host']}', '', 'db', '$db_data', " . time(). ");
				";
		mysql_query($sql, $link);
		$sql = 'INSERT INTO ' . $db['dbprefix'] . "server (name, brief, ip, outip, type, more_data,create_time) VALUES 
				('app服务器', '', '{$app['ip']}', '{$app['outip']}', 'app', '$app_data', " . time(). ");
				";
		mysql_query($sql, $link);
		$server_id = mysql_insert_id();
		$sql = 'INSERT INTO ' . $db['dbprefix'] . "server_domain (server_id, domain, dir, create_time) VALUES 
				('{$server_id}', '{$app['domain']}', '{$app['dir']}', " . time(). ");
				";
		mysql_query($sql, $link);
	}
	
	$appstore = new curl('appstore.hogesoft.com:233', '');
	$appstore->mAutoInput = false;
	$appstore->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
	$appstore->initPostData();
	$appstore->addRequestData('a', 'installed');
	$appstore->addRequestData('app', 'livworkbench');
	$appstore->request('index.php');

	header('Location:' . '?action=complete');
}
function complete()
{
	$app = file_get_contents(CACHE_DIR . 'app.tmp');
	$app = json_decode($app, 1);
	$app['outip'] = $app['outip'] ? $app['outip'] : $app['ip'];
	head('安装完成');
	?>
	<h2>安装完成</h2>
	<div>
	请将域名<?php echo $app['domain'];?>解析至<?php echo $app['outip'];?>
	</div>
	<div>
	或配置hosts文件,在文件结尾增加一行 <?php echo $app['outip'] . '		' . $app['domain'];?>
	</div>
	<div>
	然后通过此链接访问系统：<a href="http://<?php echo $app['domain'];?>">http://<?php echo $app['domain'];?></a>
	</div>

	<?php
	foot();
}

function head($title = '开始', $url = '', $delay = 1)
{
	if ($url)
	{
		$redirect = '<meta http-equiv="refresh" content="' . $delay . '; url=' . $url . '" />';
	}
	?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title><?php echo $title?> - 新媒体综合运营平台安装程序</title>
	<meta name="Author" content="">
	<meta name="Keywords" content="">
	<meta name="Description" content="">
	<?php echo $redirect;?>
</head>

<body style="margin:0 10px 0 10px;">
	<?php
}
function foot()
{
	?>
</body>
</html>
	<?php
	exit;
}
function hg_errorReport($message)
{
	head('出错了');
	echo $message;
	foot();
}

function hg_redirect($url, $message = '', $time = 1)
{
	head('下一步', $url, $time);
	echo $message;
	foot();
}

function hg_flushMsg($msg)
{
	echo $msg . str_repeat(' ', 4096). '<br /><script type="text/javascript">window.scrollTo(0,10000);</script>';
	ob_flush();
}
function hg_chk_result($result)
{
	if ($result == 'Data format error!')
	{
		return -1;
	}
	if ($result == 'Unknow action!')
	{
		return -2;
	}
	if ($result == 'Unknow user!')
	{
		return -3;
	}
	if ($result == 'user or pass error!')
	{
		return -4;
	}
	if ($result == 'Unknow action or account error!')
	{
		return -1;
	}
	return 1;
}
function get_serv_file($server, $file)
{
	$socket = new hgSocket();
	$ip = trim($server['ip']);
	$port = intval($server['port']);
	if (!$ip || !$port)
	{
		return array();
	}	
	$cmd = array(
		'action' => 'getfile',
		'para' => $file,
		'user' => $user,
		'pass' => $pass,
	);
	$con = $socket->connect($ip, $port);
	$socket->sendCmd($cmd);
	$content = $socket->readall();
	if ($content == 'Can\'t access this file')
	{
		$content = '';
	}
	return $content;
}
function hg_run_cmd($server, $cmd, $para= '', $dir = '')
{
	$socket = new hgSocket();
	$ip = trim($server['ip']);
	$port = intval($server['port']);
	if (!$ip || !$port)
	{
		return array();
	}	
	$cmd = array(
		'action' => $cmd,
		'para' => $para,
		'dir' => $dir,
		'user' => $user,
		'pass' => $pass,
	);
	$con = $socket->connect($ip, $port);
	$socket->sendCmd($cmd);
	$content = $socket->readall();
	return $content;
}

function write_serv_file($server, $file, $content, $charset = '')
{
	$socket = new hgSocket();
	$ip = trim($server['ip']);
	$port = intval($server['port']);
	if (!$ip || !$port || !$content)
	{
		return array();
	}	
	$cmd = array(
		'action' => 'write2file',
		'para' => $file,
		'data' => $content,
		'user' => $user,
		'pass' => $pass,
		'charset' => $charset,
	);
	$con = $socket->connect($ip, $port);
	$socket->sendCmd($cmd);
	$content = $socket->readall();
	if ($content == 'success')
	{
		return 1;
	}
	return 0;
}

function hg_get_hosts($content)
{
	if (!$content)
	{
		return array();
	}
	$content = str_replace("\t", ' ', $content);
	$content = str_replace(array("\r\n", "\r"), "\n", $content);
	$hosts = explode("\n", $content);
	$lines = array();
	$domain = array();
	foreach($hosts AS $line)
	{
		$line = trim($line);
		$fc = substr($line, 0, 1);
		if ($fc == '#' || !$fc)
		{
			$lines[] = $line;
		}
		else
		{
			$line = explode(' ', $line);
			$ip = $line[0];
			unset($line[0]);
			foreach($line AS $v)
			{
				$v = trim($v);
				if ($v)
				{
					$domain[$v] = $ip;
				}
			}
		}
	}
	return $domain;
}

function hg_encript_str($str, $en = true, $salt)
{
	if ($en)
	{
		$str = $str . $salt;
		$str = base64_encode($str);
	}
	else
	{
		$str = base64_decode($str);
		$str = str_replace($salt, '', $str);
	}
	return $str;
}
class curl
{
	private $mRequestType = 'http';
	private $mReturnType = 'json';
	private $mSubmitType = 'post';
	private $mUrlHost = 'localhost';
	private $mApiDir = 'livsns/api/';
	private $mToken = '';
	private $mErrorReturn = 'exit';
	private $mAppid = '';
	private $mAppkey = '';
	private $mFile = '';
	private $mAuth = '';
	private $mCookies = array();
	private $mRequestData = array();
	private $globalConfig = array();
	private $input = array();
	private $user = array();
	function __construct($host = '', $apidir = '', $token='',$stype = 'post' , $request_type = 'http')
	{
		$this->mAuth = $token;
		$this->setUrlHost($host, $apidir);
		$this->setToken();
		$this->setClient();
		$this->setRequestType($request_type);
		$this->setSubmitType($stype);
	}

	function __destruct()
	{
	}

	public function initPostData()
	{
		$this->mRequestData = array();
	}

	public function setReturnFormat($format)
	{
		if (!in_array($format, array('json', 'xml', 'str')))
		{
			$format = 'json';
		}
		$this->mReturnType = $format;
	}

	public function setUrlHost($host, $apidir)
	{
		if (!$host)
		{
			global $gApiConfig;
			$host = $gApiConfig['host'];
			$apidir = $gApiConfig['apidir'];
		}
		$this->mUrlHost = $host;
		$this->mApiDir = $apidir;
	}

	public function setClient()
	{
		$this->mAppid = $this->input['appid'];
		$this->mAppkey = $this->input['appkey'];
	}

	public function setErrorReturn($type = 'exit')
	{
		$this->mErrorReturn = $type;
	}

	public function setToken()
	{
		$this->mToken = $this->user['token'];
	}

	public function setRequestType($type)
	{
		$this->mRequestType = $type;
	}

	public function setSubmitType($type)
	{
		$this->mSubmitType = $type;
	}

	public function addCookie($name, $value)
	{
		$this->mCookies[] = $this->globalConfig['cookie_prefix'] . $name . '=' . $value;
	}

	public function addFile($file)
	{
		if(isset($file))
		{
			foreach ($file as $var => $val)
			{
				if (is_array($val['tmp_name']))
				{
					foreach ($val['tmp_name'] as $k=>$fname)
					{
						if($fname)
						{
							$this->mRequestData[$var . "[$k]"] = "@".$fname . ';type=' . $val['type'][$k] . ';filename=' . $val['name'][$k];
						}
					}
				}
				else
				{
					if ($val['tmp_name'])
					{
						$this->mRequestData[$var] = "@".$val['tmp_name'] . ';type=' . $val['type'] . ';filename=' . $val['name'];
					}
				}
			}
		}
	}

	public function addRequestData($name, $value)
	{
		$this->mRequestData[$name] = $value;
	}

    public function request($file)
    {
		/*
		 * 接口基类方法verifyToken根据token获取登录用户信息
		 * 或者根据客户端ID和客户端KEY也可以获取虚拟用户信息（超级用户权限）
		 */
		$para = '&appid=' . CUSTOM_APPID;
		$para .= '&appkey=' . CUSTOM_APPKEY;
		if($this->mToken)
		{
			$para .= '&access_token=' . $this->mToken;
		}

		$para .= '&auth=' . $this->mAuth;
		$para .= '&token=' . $this->mAuth;

		if ($this->input)
		{
			foreach ($this->input AS $k => $v)
			{
				if (in_array($k, array('a', 'pp', 'mid', 'count', 'id')))
				{
					continue;
				}
				if (is_array($v))
				{
					foreach ($v AS $kk => $vv)
					{
						//二维数组
						if(is_array($vv))
						{
							foreach($vv as $kkk=>$vvv)
							{
								$this->addRequestData($k . "[$kk]" . "[$kkk]", $vvv);
							}
						}
						else
						{
							$this->addRequestData($k . "[$kk]", $vv);
						}
					}
				}
				else
				{
					$this->addRequestData($k, $v);
				}
			}
		}

		if ($_FILES)
		{
			$this->addFile($_FILES);
		}
		if ($this->input['html'])
		{
			$para .= '&html=' . $this->input['html'];
		}
		if ('get' == $this->mSubmitType && $this->mRequestData)
		{
			foreach ($this->mRequestData AS $k => $v)
			{
				$para .= '&' . $k . '=' . urlencode($v);
			}
		}

		$url = $this->mRequestType . '://' . $this->mUrlHost . '/' . $this->mApiDir . $file . '?format=' . $this->mReturnType . $para;

		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
		curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		if ($this->mCookies)
		{
			$cookies = implode(';', $this->mCookies);

			curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		}
		if ('post' == $this->mSubmitType)
		{
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->mRequestData);
		}
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
        $ret = curl_exec($ch);	
		$head_info = curl_getinfo($ch);
        curl_close($ch);
		if($head_info['http_code']!= 200)
		{
			exit('服务器访问接口[' . $url . ']异常,错误:' . $head_info['http_code']);
		}
        if($ret == 'null')
        {
        	return '';
        }
        $func = $this->mReturnType . 'ToArray';
        $ret = $this->$func($ret);

        return $ret;
    }
    private function jsonToArray($json)
    {
    	$ret = json_decode($json,true);
		if(is_array($ret))
		{
			unset($ret['Debug']);
			return $ret;
		}
		else
		{
			return $json;
		}
    }

    private function xmlToArray($xml)
    {
    	return $xml;
    }

    private function strToArray($str)
    {
    	return $str;
    }
}
class hgSocket
{
	private $scoket;
	private $connetced;
	function __construct()
	{
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	}
	function __destruct()
	{
		$this->close();
	}

	public function close()
	{
		if($this->connetced)
		{
			@socket_close($this->socket);
		}
	}
	public function connect($ip, $port)
	{
		//echo $ip . $port . '<br />';
		$result = @socket_connect($this->socket, $ip, $port);
		if (!$result)
		{
			$this->connetced = false;
		}
		else
		{
			$this->connetced = true;
		}
		return $this->connetced;
	}

	public function sendCmd($cmd)
	{
		if (!$this->connetced)
		{
			return false;
		}
		if (!isset($cmd['charset']))
		{
			$cmd['charset'] = '';
		}
		$str = json_encode($cmd);
		socket_write($this->socket, $str, strlen($str));
	}

	public function read($size = 256)
	{
		if (!$this->connetced)
		{
			return false;
		}
		$out = socket_read($this->socket, $size);
		return $out;
	}

	public function readall()
	{
		if (!$this->connetced)
		{
			return false;
		}
		$data = '';
		$size = 4096;
		while ($out = $this->read($size))
		{
			$data .= $out;
			if (strlen($out) < $size)
			{
				break;
			}
		}
		return $data;
	}
}
?>