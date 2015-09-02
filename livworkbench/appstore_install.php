<?php
if (!defined('SCRIPT_NAME'))
{
	exit;
}
		if (DEVELOP_MODE)
		{
			$this->ReportError('对不起，开发模式不允许安装');
		}
		$m2oserver = array(
			'ip' => $this->settings['mcphost'],	
			'port' => 6233,
		);
		if (!is_writeable('conf/config.php'))
		{
			hg_run_cmd( $m2oserver, 'runcmd', 'chmod -Rf 777 ' .  realpath('conf/config.php'));
		}
		if (!is_writeable('conf/config.php'))
		{
			$message = '应用无法安装，请将此文件（conf/config.php）设为可写';
			$this->install($message);
		}
		if ($this->input['app'])
		{
			$this->appstore->initPostData();
			$this->appstore->addRequestData('a', 'detail');
			$this->appstore->addRequestData('app', $this->input['app']);
			$appinfo = $this->appstore->request('index.php');
			$appinfo = $appinfo[0];
		}
		if (!$appinfo)
		{
			$this->install('指定应用不存在或被删除，无法安装');
		}
		if ($appinfo['relyon'])
		{
		//	$this->install('请通过商店安装');
		}

		$this->input['app'] = $appinfo['app_uniqueid'];
		if ($appinfo['status'] != 0)
		{
			$this->install('应用' . $appinfo['name'] . '已经安装');
		}
		$dbserver = intval($this->input['dbserver']);
		$appserver = intval($this->input['appserver']);
		$cover = intval($this->input['cover']);
		$database = trim($this->input['database']);
		$domain = trim($this->input['apidomain']);
		$dir = trim($this->input['dir']);
		$dbprefix = trim($this->input['dbprefix']);
		if (!$appserver)
		{
			$this->install('请指定程序安装位置');
		}
		if (!$domain)
		{
			$this->install('请设定安装目录访问域名');
		}
		if (!$dir)
		{
			$this->install('请设定安装目录');
		}
		if (strstr($domain, '/'))
		{
			$this->install('只能填写域名，不能带有/');
		}
		$dir = str_replace('//', '/', $dir);
		$app_dir = trim($dir, '/');
		if (count(explode('/', $app_dir)) < 3)
		{
			$this->install('安装目录必须达到3级及以上');
		}
		
		$app_dir = '/' . $app_dir;
		$servers = array();
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'server';
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$servers[$r['id']] = $r;
		}

		$install_app = trim($this->input['app']);
		$curl = new curl($this->product_server['host'] . ':' . $this->product_server['port'], '');
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->setSubmitType('get');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('install', 1);
		$curl->addRequestData('app', $install_app);
		$ret = $curl->request('db.php');
		$appdb = $ret;
		if (!$appinfo['nodb'])
		{
			if ($appdb['app'])
			{
				if(!$dbserver)
				{
					$this->install('请指定数据库位置');
				}
				if (!$database)
				{
					$this->install('请设定数据库名');
				}

				if (!$servers[$dbserver])
				{
					$this->install('所选数据库服务器不存在');
				}
				$db = json_decode($servers[$dbserver]['more_data'], 1);
				$db['pass'] = hg_encript_str($db['pass'], false);
				$link = @mysql_connect($db['host'], $db['user'], $db['pass']);
				if (!$link)
				{
					$message = '此数据库服务器无法连接，请确认信息是否准确';
					$this->install($message);
				}
				mysql_query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary", $link);
				
				$sql = "SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME='{$database}'";
				$q = mysql_query($sql, $link);
				$dbexist = mysql_fetch_array($q);
				if (!$cover)
				{
					if ($dbexist)
					{
						$message = $database . '数据库已存在，您可以勾选覆盖数据库';
						$this->install($message);
					}
				}
			}
		}
		else
		{
			$appdb['app'] = array();
		}
		if (!$servers[$appserver])
		{
			$this->install('所选应用服务器不存在');
		}
		$app = json_decode($servers[$appserver]['more_data'], 1);

		if ($servers[$appserver]['type'] == 'app')
		{ 
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'server_domain WHERE server_id=' . $appserver . ' AND dir=\'' . $app_dir . "'";
			$q = $this->db->query_first($sql);
			if ($q)
			{
				$message = '此目录已经被使用，无法安装到此目录';
				$this->install($message);
			}
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'server_domain WHERE server_id=' . $appserver . ' AND domain=\'' . $domain . "'";
		$q = $this->db->query_first($sql);
		if (!$q)
		{
			$sql = 'INSERT INTO ' . DB_PREFIX . "server_domain (server_id, domain, dir, create_time) VALUES 
				($appserver, '$domain', '{$app_dir}/', " . TIMENOW . ")";
				$this->db->query($sql);
		}
		
		$socket = new hgSocket();
		$con = $socket->connect($app['ip'], $app['port']);
		if (!intval($con))
		{
			$message = '服务器无法连接，请确认服务器ip是否正确或服务程序hogeMonitor.py是否监听在'. $app['ip'] . ':' . $app['port'] . '上';
			$socket->close();
			$this->install($message);
		}
		$socket->close();
		ob_start();
		hg_flushMsg('更新所有服务器hosts');
		//修改所有服务器hosts
		foreach ($servers AS $server)
		{
			if ($server['type'] == 'db')
			{
				continue;
			}
			$sapp = json_decode($server['more_data'], 1);
			$hostscontent = get_serv_file( $sapp, '/etc/hosts');
			$hosts = hg_get_hosts($hostscontent);
			if($domain && !$hosts[$domain])
			{
				$hostscontent .= "\n" . $servers[$appserver]['ip'] . '	' . $domain;
				write_serv_file( $sapp, '/etc/hosts', $hostscontent);
			}
		}
		hg_flushMsg('hosts更新完毕');
		//修改nginx
		if($app['server_software'] == 'nginx')
		{
			hg_flushMsg('开始更新nginx设置');
			$content = get_serv_file( $app, '/usr/local/nginx/conf/nginx.conf');
			if (!@preg_match('/include\s+conf\.d\/.+\.conf/is', $content))
			{
				$message = '请修改/usr/local/nginx/conf/nginx.conf文件，增加include conf.d/*.conf配置';
				$socket->close();
				$this->install($message);
			}
			if (@preg_match('/server_name\s+' . $domain . '/is', $content))
			{
				$message = '域名' . $domain . '已经存在于配置/usr/local/nginx/conf/nginx.conf中';
				$socket->close();
				$this->install($message);
			}
			
			if (!in_array($this->settings['php_run_type'], array('tcp', 'socket')))
			{
				$message = '请修改conf/config.php文件，增加$gGlobalConfig[\'php_run_type\']=\'tcp或socket\';';
				$socket->close();
				$this->install($message);
			}
			
			$content = get_serv_file( $app, '/usr/local/nginx/conf/conf.d/m2o_nginx.conf');
			if (!$content)
			{
				$message = '请创建/usr/local/nginx/conf/conf.d/m2o_nginx.conf，并设置内容为"#m2o installed config, please don\'t modify it. Thank you."';
				$socket->close();
				$this->install($message);
			}
			if (!@preg_match('/server_name\s+' . $domain . '/is', $content))
			{
				if ($this->settings['php_run_type'] == 'socket')
				{
					$php_socket_path = $this->settings['php_socket_path'] ? $this->settings['php_socket_path'] : '/dev/shm/php-cgi.sock';
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
				$content .= '
				server {
					set $htdocs ' . $app_dir . '/api;
					listen       80;
					server_name  ' . $domain . ';

					#charset koi8-r;

					#access_log  logs/host.access.log  main;

					location / {
						root   $htdocs;
						index  index.html index.htm index.php;
					}
					location ~ .*\.php?$ {' . $runtype . '}
				}
				';

				write_serv_file( $app, '/usr/local/nginx/conf/conf.d/m2o_nginx.conf', $content);
				hg_run_cmd( $app, 'restart', '/usr/local/nginx/sbin/nginx -s reload');
			}
			hg_flushMsg('nginx设置更新完毕');
		}

		hg_flushMsg('开始下载应用程序包');
		//下载程序
		$install_app = trim($this->input['app']);
		$curl = new curl($this->product_server['host'] . ':' . $this->product_server['port'], '');
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->setSubmitType('get');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('install', 1);
		$curl->addRequestData('app', $install_app);
		$program_url = $curl->request('check_version.php');
		if (!(strstr($program_url, 'http://') && strstr($program_url, '.zip')) || $program_url == 'NO_VERSION')
		{
			$message = '获取应用程序失败或程序版本不存在.';
			$this->install($message);
		}
		$app_dir .= '/';
		//hg_flushMsg('开始下载程序');
		hg_run_cmd( $app, 'mkdirs', $app_dir);
		hg_run_cmd( $app, 'download', $program_url, $app_dir);
		hg_run_cmd( $app, 'mkdirs', $app_dir . 'uploads');
		hg_run_cmd( $app, 'mkdirs', $app_dir . 'api/' . $install_app . '/cache');
		hg_run_cmd( $app, 'mkdirs', $app_dir . 'api/' . $install_app . '/data');


		$curl->initPostData();
		$curl->addRequestData('js', 1);
		$curl->addRequestData('app', $install_app);
		$program_url = $curl->request('check_version.php');
		$m2oserv = array(
			'ip' => $this->settings['mcphost'],	
			'port' => 6233
		);
		$m2oscriptdir = realpath('./') . '/res/scripts/app_' . $install_app . '/';
		hg_run_cmd( $m2oserv, 'mkdirs', $m2oscriptdir);
		hg_run_cmd( $m2oserv, 'download', $program_url, $m2oscriptdir);
		hg_flushMsg('应用程序包下载完毕');
		$ret = $appdb;
		if (is_array($ret))
		{
			$m2odata = $ret['m2o'];
			ob_start();
			if ($ret['app'])
			{
				hg_flushMsg('开始安装数据库');
				if (!$cover || !$dbexist)
				{
					//hg_flushMsg('开始创建数据库 ' . $db['database']);
					hg_flushMsg('开始创建数据库' . $database);
					$sql = 'CREATE DATABASE ' . $database . ' DEFAULT CHARACTER SET UTF8';
					$q = mysql_query($sql, $link);	
					if (!$q)
					{	
						$message = $database . '创建失败, 请确认此账号有权限创建数据库';
						$this->install($message);
					}
					//hg_flushMsg('数据库 ' . $db['database'] . ' 已创建');
				}
				mysql_select_db($database, $link);
				foreach ($ret['app'] AS $table => $sql)
				{
					if (substr($table, 0, 4) == 'liv_')
					{
						$table = $dbprefix . substr($table, 4);
						$sql = preg_replace('/CREATE\s+TABLE\s+([`]{0,1})liv_/is', 'CREATE TABLE \\1' . $dbprefix, $sql);
					}
					if (substr($table, 0, 4) == 'm2o_')
					{
						$table = $dbprefix . substr($table, 4);
						$sql = preg_replace('/CREATE\s+TABLE\s+([`]{0,1})m2o_/is', 'CREATE TABLE \\1' . $dbprefix, $sql);
					}
					$sql = preg_replace('/UNION=\(`liv\_(.+?)`\)/is', 'UNION=(`' .  $dbprefix . '\\1`)', $sql);
					$sql = preg_replace('/UNION=\(`m2o\_(.+?)`\)/is', 'UNION=(`' .  $dbprefix . '\\1`)', $sql);
					hg_flushMsg('开始创建数据表 ' . $table);
					if ($cover)
					{
						$dropsql = 'DROP table ' . $table;
						mysql_query($dropsql, $link);	
					}
					mysql_query($sql, $link);
					//hg_flushMsg('数据表 ' . $table . '创建完毕');
				}
			}
			hg_flushMsg('数据库安装完成 ');
		}

		//修改配置文件
		hg_flushMsg('开始初始化应用 ');
		$conf = $app_dir . 'api/' . $install_app . '/conf/config.php';
		$content = get_serv_file( $app, $conf);
		if ($install_app != 'auth')
		{
			$match = preg_match("/define\('APPID',\s*\'*.*?\'*\s*\);/is", $content);
			if ($match)
			{
				$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
				$curl->setSubmitType('post');
				$curl->setReturnFormat('json');
				$curl->addRequestData('a', 'create');
				$curl->addRequestData('custom_name', $appinfo['name']);
				$curl->addRequestData('display_name', $appinfo['name']);
				$curl->addRequestData('bundle_id', $install_app);
				$curl->addRequestData('custom_desc', '');
				$curl->addRequestData('expire_time', 0);
				$ret = $curl->request('admin/auth_update.php');
				$ret = $ret[0];
				$appid = $ret['appid'];
				$appkey = $ret['appkey'];
				$content = preg_replace("/define\('APPID',\s*\'*.*?\'*\s*\);/is", "define('APPID', '$appid');",  $content);
				$content = preg_replace("/define\('APPKEY',\s*\'*.*?\'*\s*\);/is", "define('APPKEY','$appkey');",  $content);
			}
		}
		if ($db)
		{
			
			//$db['pass'] = hg_encript_str($db['pass'], false, $auth['appkey']);
			$string = "\$gDBconfig = array(
	'host'     => '{$db['host']}',
	'user'     => '{$db['user']}',
	'pass'     => '{$db['pass']}',
	'database' => '{$database}',
	'charset'  => 'utf8',
	'pconnect' => 0,
	);";
			$content = preg_replace("/\\\$gDBconfig\s*=\s*array\s*(.*?);/is", $string, $content);
			$content = preg_replace("/define\('DB_PREFIX',\s*'.*?'\);/is","define('DB_PREFIX','{$dbprefix}');", $content);
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
		$selfconfigcontent = $content;
		$selfconfigfile = $conf;
		write_serv_file( $app, $conf, $content, 'utf8');
		
		hg_run_cmd( $app, 'runcmd', 'chmod -Rf 777 ' .  $app_dir . 'uploads ' .  $app_dir . 'api/' . $install_app . '/cache ' .  $app_dir . 'api/' . $install_app . '/data ' .  $app_dir . 'api/' . $install_app . '/conf/config.php');
		$curl = new curl($domain, $install_app . '/');
		if($appinfo['inited_app'] == 'true')
		{
			$curl->initPostData();
			$curl->addRequestData('a', 'doset');
			$ret = $curl->request('configuare.php');
			if(!$ret['success'])
			{
				$message = $install_app . '系统初始化失败';
				$this->install($message);
			}
			$this->db->select_db($this->db->dbname);
			$sql = 'SELECT * FROM ' . DB_PREFIX . "crontab WHERE app_uniqueid='{$install_app}'";
			$q = $this->db->query($sql);
			$crontabs = array();
			$exist_crontabs = array();
			while($r = $this->db->fetch_array($q))
			{
				$r['run_time'] = date('Y-m-d H:i:s',$r['run_time']);
				if ($r['is_use'])
				{
					$r['is_use'] = '是';
					$r['op'] = '停止';
				}
				else
				{
					$r['is_use'] = '否';
					$r['op'] = '启用';
				}
				$exist_crontabs[$r['mod_uniqueid']] = $r['id'];
				$crontabs[$r['id']] = $r;
			}
			$curl->initPostData();
			$curl->addRequestData('a', 'get_cron_file');
			$init_crontabs = $curl->request('configuare.php');
			
			$used_crontables = array();
			if ($init_crontabs)
			{
				$curl->mNotInitedNeedExit = false;
				$curl->setErrorReturn(false);
				foreach($init_crontabs AS $cron)
				{
					$curl->initPostData();
					$curl->addRequestData('a', 'initcron');
					$crondata = $curl->request('cron/' . $cron);
					if (!is_array($crondata))
					{
						continue;
					}
					$crondata = $crondata[0];

					if (!$crondata['mod_uniqueid'])
					{
						continue;
					}
					if ($exist_crontabs[$crondata['mod_uniqueid']])
					{
						//计划任务数据有变动
						$data = array(
							'name' => $crondata['name'],
							'brief' => $crondata['brief'],
							'file_name' => $cron,
						);
						hg_fetch_query_sql($data, 'crontab', 'id=' . $exist_crontabs[$crondata['mod_uniqueid']]);
						unset($exist_crontabs[$crondata['mod_uniqueid']]);
					}
					else
					{ //新增计划任务
						$data = array(
							'app_uniqueid' => $this->app['softvar'],
							'mod_uniqueid' => $crondata['mod_uniqueid'],
							'name' => $crondata['name'],
							'brief' => $crondata['brief'],
							'space' => $crondata['space'],
							'file_name' => $cron,
							'is_use' => $crondata['is_use'],
							'host' => $this->app['host'],
							'dir' => $this->app['dir'] . 'cron/',
							'port' => 80,
							'run_time' => TIMENOW,
							'create_time' => TIMENOW,
						);
						hg_fetch_query_sql($data, 'crontab');
					}
				}
				//清除已经取消的计划任务
				if ($exist_crontabs)
				{
					$sql = 'DELETE FROM ' . DB_PREFIX . 'crontab WHERE id IN(' . implode(',', $exist_crontabs) . ')';
					$this->db->query($sql);
				}
			}
			else
			{
				//该应用无计划任务
				$sql = 'DELETE FROM ' . DB_PREFIX . "crontab WHERE app_uniqueid='{$this->app['softvar']}'";
				$this->db->query($sql);
			}
			
			$selfconfigcontent = preg_replace("/define\('INITED_APP',\s*.*?\s*\);/is","define('INITED_APP', true);", $selfconfigcontent);
		}
		$conf = $app_dir . 'conf/global.conf.php';
		$content = get_serv_file( $app, $conf);
		
		$string1 = "\$gGlobalConfig['is_open_xs'] = 0;";
		$content = preg_replace("/\\\$gGlobalConfig\s*\['is_open_xs'\]\s*=\s*(.*?);/is", $string1, $content);
		$content = preg_replace("/define\('DEBUG_MODE',\s*.*?\);/is","define('DEBUG_MODE',false);", $content);
		$content = preg_replace("/define\('DEVELOP_MODE',\s*.*?\);/is","define('DEVELOP_MODE',false);", $content);
		$content = preg_replace("/\\\$gGlobalConfig\s*\['App_.*'\]\s*=\s*array\s*(.*?);/is", '', $content);	
		$match = preg_match("/define\('CUSTOM_APPKEY',\s*.*?\s*\);/is", $content);
		if($match)
		{
			$content = preg_replace("/define\('CUSTOM_APPKEY',\s*.*?\s*\);/is","define('CUSTOM_APPKEY', '" . CUSTOM_APPKEY . "');", $content);
			$content = preg_replace("/define\('CUSTOM_APPID',\s*.*?\s*\);/is","define('CUSTOM_APPID', '" . CUSTOM_APPID . "');", $content);
		}
		else
		{
			$content = preg_replace("/\?>/is", "\ndefine('CUSTOM_APPID', '" . CUSTOM_APPID . "');\ndefine('CUSTOM_APPKEY', '" . CUSTOM_APPKEY . "');\n?>", $content);
		}
		write_serv_file( $app, $conf, $content, 'utf8');
		
		if ($install_app == 'auth')
		{
			if (!$this->settings['App_auth'])
			{
				$this->settings['App_auth']['host'] = $domain;
				$this->settings['App_auth']['dir'] = $install_app . '/';
				//申请授权
				$curl->initPostData();
				$curl->addRequestData('a', 'create');
				$curl->addRequestData('custom_name', 'M2O管控平台');
				$curl->addRequestData('display_name', 'M2O管控平台');
				$curl->addRequestData('bundle_id', 'm2o');
				$curl->addRequestData('custom_desc', 'm2o管控平台使用');
				$curl->addRequestData('expire_time', 0);
				$ret = $curl->request('admin/auth_update.php');
				$ret = $ret[0];
				$m2oappid = $ret['appid'];
				$m2oappkey = $ret['appkey'];
				if (!$m2oappid)
				{
					$message = $install_app . '系统初始化失败';
					$this->install($message);
				}
				$curl->setClient($m2oappid, $m2oappkey);
				$m2oconfigstr = "\ndefine('APPID', '{$m2oappid}');\ndefine('APPKEY', '{$m2oappkey}');";
				//创建管理员
				$user_name = array_keys($this->settings['admin_user']);
				$user_name = $user_name[0];
				$curl->initPostData();
				$curl->addRequestData('a', 'create');
				$curl->addRequestData('user_name', $user_name);
				$curl->addRequestData('password', $this->settings['admin_user'][$user_name]);
				$curl->addRequestData('md5once', '1');
				$curl->addRequestData('admin_role_id[]', 1);
				$curl->addRequestData('father_org_id', 1);
				$curl->request('admin/admin_update.php');
				
				//申请计划任务授权
				$curl->initPostData();
				$curl->addRequestData('a', 'create');
				$curl->addRequestData('custom_name', '计划任务');
				$curl->addRequestData('display_name', '自动收录');
				$curl->addRequestData('bundle_id', 'cron');
				$curl->addRequestData('custom_desc', 'm2o管控平台计划任务使用');
				$curl->addRequestData('expire_time', 0);
				$ret = $curl->request('admin/auth_update.php');
				$ret = $ret[0];
				$appid = $ret['appid'];
				$appkey = $ret['appkey'];
				if ($appid)
				{
					$cronfile = realpath('./cron/config.py');
					$content = get_serv_file( $m2oserver, $cronfile);
					$content = preg_replace("/APPID\s*=\s*\d+/is","APPID = {$appid}", $content);
					$content = preg_replace("/APPKEY\s*=\s*\'.*?\'/is","APPKEY = '{$appkey}'", $content);
					write_serv_file( $m2oserver, $cronfile, $content, 'utf8');
					hg_run_cmd( $m2oserver, 'runcmd', 'chmod -Rf 755 ' .  realpath('./cron/'));
				}
			}
		}
		else
		{
			//取出已经安装应用配置
			
			$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
			$curl->setSubmitType('post');
			$curl->setReturnFormat('json');
			$ret = $curl->request('applications.php');
			$installed_app_conf = '';
			$instlled_apps = array();
			$need_mod_global = array();
			if (is_array($ret))
			{
				foreach ($ret AS $v)
				{
					$instlled_apps[$v['bundle']] = $v;
					$need_mod_global[$v['host']] = $v;
					$installed_app_conf .= "\n\$gGlobalConfig['App_{$v['bundle']}'] = array('name' => '{$v['name']}', 'protocol' => 'http://', 'port' => '{$v['port']}', 'host' => '{$v['host']}', 'dir' => '{$v['dir']}', 'token' => 'tmp');";
				}
			}

			if ($appinfo['dbconfigapp'])
			{
				if (!$this->settings['App_' . $appinfo['dbconfigapp']])
				{
					$message = '安装失败，原因：' . $appinfo['dbconfigapp'] . '未安装';
					$this->install($message);
				}
				$dbconfserv = array(
					'ip' => $this->settings['App_' . $appinfo['dbconfigapp']]['host'],
					'port' => 6233,
				);
				
				$appcurl = new curl($this->settings['App_' . $appinfo['dbconfigapp']]['host'], $this->settings['App_' . $appinfo['dbconfigapp']]['dir']);
				$appcurl->setSubmitType('get');
				$appcurl->setReturnFormat('str');
				$appcurl->initPostData();
				$appcurl->addRequestData('a', 'getapp_path');
				$confile = $appcurl->request('configuare.php');
				$confile = $confile . '/conf/config.php';
				$dbconfcontent = get_serv_file( $dbconfserv, $confile);

				preg_match("/\\\$gDBconfig\s*=\s*array\s*(.*?);/is", $dbconfcontent, $match);
				$dbconfig = $match[0];
				preg_match("/define\('DB_PREFIX',\s*'(.*?)'\);/is", $dbconfcontent, $match);
				$dbprefix = $match[1];
				$conf = $app_dir . 'api/' . $install_app . '/conf/config.php';
				$content = get_serv_file( $app, $conf);
				$content = preg_replace("/\\\$gDBconfig\s*=\s*array\s*(.*?);/is", $dbconfig, $content);
				$content = preg_replace("/define\('DB_PREFIX',\s*'.*?'\);/is","define('DB_PREFIX','{$dbprefix}');", $content);
				$selfconfigcontent = $content;
				write_serv_file( $app, $conf, $content, 'utf8');
				hg_run_cmd( $app, 'runcmd', 'chmod -Rf 777 ' .  $conf);
			}
		}
			
		//更改config配置

		$configstr = "\n\$gGlobalConfig['App_{$install_app}'] = array('name' => '{$appinfo['name']}','protocol' => 'http://', 'port' => '80','host' => '$domain', 'dir' => '{$install_app}/', 'token' => 'tmp');";

		$content = @file_get_contents('conf/config.php');
		if ($m2oconfigstr . $configstr)
		{
			$content = preg_replace("/\?>/is", $m2oconfigstr . $configstr . "\n?>", $content);
		}
		@file_put_contents('conf/config.php', $content);
		$conf = $app_dir . 'conf/global.conf.php';
		$content = get_serv_file( $app, $conf);
		
		$content = preg_replace("/\?>/is", $installed_app_conf . $configstr  . "\n?>", $content);
		write_serv_file( $app, $conf, $content, 'utf8');
		if ($need_mod_global) //重写所有服务器的global.conf.php中的应用配置
		{
			foreach($need_mod_global AS $host => $v)
			{
				if ($v['bundle'] == $install_app)
				{
					continue;
				}
				$gcurl = new curl($v['host'], $v['dir']);
				$gcurl->initPostData();
				$gcurl->addRequestData('a', 'getapp_path');
				$app_path = $gcurl->request('configuare.php');
				$app_path .= '/';
				$app_path = str_replace('api/' . $v['bundle'] . '/', '/', $app_path);
				$app_path = str_replace('//', '/', $app_path);
				$servinfo = array(
					'ip' => $v['host'],
					'port' => 6233,
				);
				$gconfile = $app_path . 'conf/global.conf.php';
				$ccontent = get_serv_file( $servinfo, $gconfile);
				$ccontent = preg_replace("/\\\$gGlobalConfig\s*\['App_.*'\]\s*=\s*array\s*(.*?);/is", '', $ccontent);	
				$match = preg_match("/define\('CUSTOM_APPKEY',\s*.*?\s*\);/is", $content);
				if($match)
				{
					$content = preg_replace("/define\('CUSTOM_APPKEY',\s*.*?\s*\);/is","define('CUSTOM_APPKEY', '" . CUSTOM_APPKEY . "');", $content);
					$content = preg_replace("/define\('CUSTOM_APPID',\s*.*?\s*\);/is","define('CUSTOM_APPID', '" . CUSTOM_APPID . "');", $content);
				}
				else
				{
					$content = preg_replace("/\?>/is", "\ndefine('CUSTOM_APPID', '" . CUSTOM_APPID . "');\ndefine('CUSTOM_APPKEY', '" . CUSTOM_APPKEY . "');\n?>", $content);
				}
				$ccontent = preg_replace("/\?>/is", $installed_app_conf . $configstr  . "\n?>", $ccontent);
				write_serv_file( $servinfo, $gconfile, $ccontent, 'utf8');
			}
		}
		if ($this->settings['App_publishcontent'] && $install_app != 'mediaserver')
		{
			//发布计划创建
			
			$match = preg_match("/define\('PUBLISH_SET_ID',\s*.*?\s*\);/is", $selfconfigcontent);
			if ($match)
			{
				$pubcurl =  new curl($domain, $install_app . '/');
				
				$pubcurl->setSubmitType('post');
				$pubcurl->setReturnFormat('json');
				$pubcurl->initPostData();
				$pubcurl->addRequestData('a', 'create_publish_table');
				$pubcurl->addRequestData('apihost', $domain);
				$pubcurl->addRequestData('apidir', $install_app . '/');
				$planret = $pubcurl->request('configuare.php');
				$planret = $planret['ret'];
				if ($planret)
				{
					$selfconfigcontent = preg_replace("/define\('PUBLISH_SET_ID',\s*.*?\s*\);/is","define('PUBLISH_SET_ID', '{$planret[1]}');", $selfconfigcontent);
					$selfconfigcontent = preg_replace("/define\('PUBLISH_SET_SECOND_ID',\s*.*?\s*\);/is","define('PUBLISH_SET_SECOND_ID', '{$planret[2]}');", $selfconfigcontent);
					write_serv_file( $app, $selfconfigfile, $selfconfigcontent, 'utf8');
					hg_run_cmd( $app, 'runcmd', 'chmod -Rf 777 ' .  $selfconfigfile);
					if ($install_app == 'livmedia')
					{
						$pubcurl =  new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir']);
						$pubcurl->setSubmitType('post');
						$pubcurl->setReturnFormat('str');
						$pubcurl->initPostData();
						$pubcurl->addRequestData('a', 'getapp_path');
						$confile = $pubcurl->request('configuare.php');
						$confile = $confile . '/conf/config.php';
						$mediaserver = array(
							'ip' => $this->settings['App_mediaserver']['host'],
							'port' => 6233
						);
						$socket = new hgSocket();
						$con = $socket->connect($mediaserver['ip'], $mediaserver['port']);
						if (!intval($con))
						{
							$message = 'mediaserver服务器无法连接，请确认服务器ip是否正确或服务程序hogeMonitor.py是否监听在'. $mediaserver['ip'] . ':' . $mediaserver['port'] . '上';
							$socket->close();
							$this->install($message);
						}
						$socket->close();
						$mediaservercontent = get_serv_file( $mediaserver, $confile);

						$match = preg_match("/define\('PUBLISH_SET_ID',\s*.*?\s*\);/is", $mediaservercontent);
						if ($match)
						{	
							$mediaservercontent = preg_replace("/define\('PUBLISH_SET_ID',\s*.*?\s*\);/is","define('PUBLISH_SET_ID', '{$planret[1]}');", $mediaservercontent);
							$mediaservercontent = preg_replace("/define\('PUBLISH_SET_SECOND_ID',\s*.*?\s*\);/is","define('PUBLISH_SET_SECOND_ID', '{$planret[2]}');", $mediaservercontent);
							write_serv_file( $mediaserver, $confile, $mediaservercontent, 'utf8');
							hg_run_cmd( $mediaserver, 'runcmd', 'chmod -Rf 777 ' .  $confile);
						}
					}
				}
			}
		}
		//插入应用和模块
		$menu = array();
		$applications = $modules = array();
		if ($m2odata)
		{
			if ($appinfo['target'])
			{
				$atarget = '';
			}
			else
			{
				$atarget = 'a=frame&';
			}
			$this->db->select_db($this->db->dbname);
			$application_id = 0;
			foreach ($m2odata AS $table => $data)
			{
				$sql = '';
				
				if ($table == 'applications')
				{
					$application_id = $data['id'];
					$data['name'] = $appinfo['name'];
					$data['host'] = $domain;
					if ($appinfo['api_uniqueid'])
					{
						$data['dir'] = $appinfo['api_uniqueid'] . '/admin/';
					}
					else
					{
						$data['dir'] = $install_app . '/admin/';
					}
					$data['softvar'] = $install_app;
					$data['version'] = $appinfo['version'];
					$applications = $data;
					$sql = 'REPLACE INTO ' . DB_PREFIX . $table . ' (' . implode(',', array_keys($data)) . ') VALUES ';
					$sql .= "('" . implode("','", $data) . "')";
					$this->db->query($sql);
					$appname = $data['name'];

					continue;
				}
				if (is_array($data))
				{
					foreach ($data AS $row)
					{
						if ($row['host'])
						{
							$row['host'] = '';
						}
						if ($row['dir'])
						{
							$row['dir'] = '';
						}
						if ($row['app_uniqueid'])
						{
							$row['app_uniqueid'] = $install_app;
						}
						if ($table == 'modules')
						{
							$main_module = 0;
							if ($row['menu_pos'] == -1)
							{
								$menu[-1] = array(
									'name' => $appname, 	 	
									'module_id' => $row['id'],
									'app_uniqueid' => $row['app_uniqueid'],
									'mod_uniqueid' => $row['mod_uniqueid'],
									'url' => 'run.php?' . $atarget . 'mid=' . $row['id'],
									'close' => 0,
									'father_id' => $appinfo['class_id'],
									'order_id' => $row['order_id'],
									'include_apps'=>$install_app,
									'`index`'=>0,
								);
								$main_module = 1;
							}
							if ($row['app_uniqueid'] == $row['mod_uniqueid'])
							{
								if (!$menu[-1])
								{
									$menu[-1] = array(
										'name' => $appname, 	 	
										'module_id' => $row['id'],
										'app_uniqueid' => $row['app_uniqueid'],
										'mod_uniqueid' => $row['mod_uniqueid'],
										'url' => 'run.php?' . $atarget . 'mid=' . $row['id'],
										'close' => 0,
										'father_id' => $appinfo['class_id'],
										'order_id' => $row['order_id'],
										'include_apps'=>$install_app,
										'`index`'=>0,
									);
									$main_module = 1;
								}
							}
							else
							{
								if ($row['menu_pos'] == 0)
								{
									$menu[] = array(
										'name' => $row['name'],  	
										'module_id' => $row['id'],
										'app_uniqueid' => $row['app_uniqueid'],
										'mod_uniqueid' => $row['mod_uniqueid'],
										'url' => 'run.php?mid=' . $row['id'],
										'close' => 0,
										'father_id' => 0,
										'order_id' => $row['order_id'],
										'include_apps'=>$install_app,
										'`index`'=>0,
									);
								}
							}
							$row['main_module'] = $main_module;
							$modules[] = $row;
							unset($row['main_module']);
						}
						if (!$sql)
						{
							$sql = 'REPLACE INTO ' . DB_PREFIX . $table . ' (' . implode(',', array_keys($row)) . ') VALUES ';
						}
						$sql .= "('" . implode("','", $row) . "'),";
					}
					if ($sql)
					{
						$sql = rtrim($sql, ',');
						$this->db->query($sql);
					}
				}
			}
			if ($menu[-1])
			{
				$mmenu = $menu[-1];
				$sql = 'SELECT * FROM ' . DB_PREFIX . "menu WHERE app_uniqueid='{$mmenu['app_uniqueid']}' AND mod_uniqueid='{$mmenu['mod_uniqueid']}'";
				$q = $this->db->query_first($sql);
				if ($q)
				{
					$sql = 'UPDATE ' . DB_PREFIX . "menu SET name='{$mmenu['name']}', father_id={$mmenu['father_id']},order_id={$mmenu['order_id']},url='{$mmenu['url']}', module_id='{$mmenu['module_id']}' WHERE id={$q['id']} ";
					$this->db->query($sql);
				}
				else
				{
					$sql = 'INSERT INTO ' . DB_PREFIX . 'menu (' . implode(',', array_keys($mmenu)) . ') VALUES ';
					$sql .= "('" . implode("','", $mmenu) . "')";
					
					$this->db->query($sql);
					$q['id'] = $this->db->insert_id();
					$sql = 'UPDATE ' . DB_PREFIX . "menu set include_apps=concat(include_apps, '{$install_app}', ',') WHERE id=" . intval($mmenu['father_id']);
					$this->db->query($sql);
				}
				//向授权系统提交应用信息
				$curl =  new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
				$curl->setClient($m2oappid, $m2oappkey);
				$curl->initPostData();
				$curl->mAutoInput = false;
				unset($applications['appid'], $applications['appkey']);
				if ($appinfo['api_uniqueid'])
				{
					$applications['dir'] = $appinfo['api_uniqueid'] . '/';
				}
				else
				{
					$applications['dir'] = $install_app . '/';
				}
				$applications['use_message'] = $appinfo['use_message'];
				$applications['use_material'] = $appinfo['use_material'];
				$applications['use_textsearch'] = $appinfo['use_textsearch'];
				$applications['use_logs'] = $appinfo['use_logs'];
				$applications['use_recycle'] = $appinfo['use_recycle'];
				$applications['use_access'] = $appinfo['use_access'];
				foreach ($applications AS $k => $v)
				{
					$curl->addRequestData($k, $v);
				}
				$curl->addRequestData('bundle', $applications['softvar']);
				$ret = $curl->request('admin/apps.php');
				foreach ($modules AS $k => $v)
				{
					$curl->initPostData();
					foreach ($v AS $kk => $vv)
					{
						$curl->addRequestData($kk, $vv);
					}
					$ret = $curl->request('admin/modules.php');
				}
				foreach ($menu AS $k => $mmenu)
				{
					if($k != -1)
					{
						$mmenu['father_id'] = $q['id'];
						$sql = 'SELECT * FROM ' . DB_PREFIX . "menu WHERE app_uniqueid='{$mmenu['app_uniqueid']}' AND mod_uniqueid='{$mmenu['mod_uniqueid']}'";
						$exist = $this->db->query_first($sql);
						if ($exist)
						{
							$sql = 'UPDATE ' . DB_PREFIX . "menu SET name='{$mmenu['name']}', order_id={$mmenu['order_id']},father_id={$mmenu['father_id']},url='{$mmenu['url']}', module_id='{$mmenu['module_id']}' WHERE id={$exist['id']} ";
							$this->db->query($sql);
						}
						else
						{
							$sql = 'INSERT INTO ' . DB_PREFIX . 'menu (' . implode(',', array_keys($mmenu)) . ') VALUES ';
							$sql .= "('" . implode("','", $mmenu) . "')";
							
							$this->db->query($sql);
						}
					}
				}
			}
			if ($application_id)
			{
				$this->rebuild_templates($application_id);
			}
			$this->cache->recache('applications');
			$this->cache->recache('modules');
			$this->cache->recache('menu');
		}
		
		hg_flushMsg('初始化应用完成');
		//记录已安装应用
		$this->appstore->initPostData();
		
		
		$this->appstore->addRequestData('a', 'installed');
		$this->appstore->addRequestData('app', $this->input['app']);
		$this->appstore->request('index.php');

		if ($appinfo['app_uniqueid'] == 'publishcontent')
		{
			if ($instlled_apps)
			{
				foreach ($instlled_apps AS $v)
				{
					if ($v['bundle'] == 'mediaserver')
					{
						$mediaserver = $v;
						continue;
					}
					$v['ip'] = $v['host'];
					$v['port'] = 6233;
					$appcurl = new curl($v['host'], $v['dir']);
					$appcurl->setSubmitType('post');
					$appcurl->setReturnFormat('str');
					$appcurl->initPostData();
					$appcurl->addRequestData('a', 'getapp_path');
					$confile = $appcurl->request('configuare.php');
					$confile = $confile . '/conf/config.php';
					$content = get_serv_file( $v, $confile);

					$match = preg_match("/define\('PUBLISH_SET_ID',\s*.*?\s*\);/is", $content);
					if ($match)
					{	
						$appcurl->setReturnFormat('json');
						$appcurl->initPostData();
						$appcurl->addRequestData('a', 'create_publish_table');
						$appcurl->addRequestData('apihost', $v['host']);
						$appcurl->addRequestData('apidir', $v['dir']);
						$planret = $appcurl->request('configuare.php');
						$planret = $planret['ret'];
						if ($planret)
						{
							$content = preg_replace("/define\('PUBLISH_SET_ID',\s*.*?\s*\);/is","define('PUBLISH_SET_ID', '{$planret[1]}');", $content);
							$content = preg_replace("/define\('PUBLISH_SET_SECOND_ID',\s*.*?\s*\);/is","define('PUBLISH_SET_SECOND_ID', '{$planret[2]}');", $content);
						}
						write_serv_file( $v, $confile, $content, 'utf8');
						hg_run_cmd( $app, 'runcmd', 'chmod -Rf 777 ' .  $confile);
					}
				}
				if ($this->settings['App_mediaserver'])
				{
					$v['ip'] = $this->settings['App_mediaserver']['host'];
					$v['port'] = 6233;
					$appcurl = new curl($v['host'], $v['dir']);
					$appcurl->setSubmitType('post');
					$appcurl->setReturnFormat('str');
					$appcurl->initPostData();
					$appcurl->addRequestData('a', 'getapp_path');
					$confile = $appcurl->request('configuare.php');
					$confile = $confile . '/conf/config.php';
					$content = get_serv_file( $v, $confile);

					$match = preg_match("/define\('PUBLISH_SET_ID',\s*.*?\s*\);/is", $content);
					if ($match)
					{	
						$appcurl->setReturnFormat('json');
						$appcurl->initPostData();
						$appcurl->addRequestData('a', 'create_publish_table');
						$appcurl->addRequestData('apihost', $v['host']);
						$appcurl->addRequestData('apidir', $v['dir']);
						$planret = $appcurl->request('configuare.php');
						$planret = $planret['ret'];
						if ($planret)
						{
							$content = preg_replace("/define\('PUBLISH_SET_ID',\s*.*?\s*\);/is","define('PUBLISH_SET_ID', '{$planret[1]}');", $content);
							$content = preg_replace("/define\('PUBLISH_SET_SECOND_ID',\s*.*?\s*\);/is","define('PUBLISH_SET_SECOND_ID', '{$planret[2]}');", $content);
						}
						write_serv_file( $v, $confile, $content, 'utf8');
						hg_run_cmd( $app, 'runcmd', 'chmod -Rf 777 ' .  $confile);
					}
				}
			}
			//发布计划创建
		}
		if ($appinfo['sourceapp']['app_uniqueid'])
		{
			$url = '?a=install&app=' . $appinfo['sourceapp']['app_uniqueid'];
		}
		else
		{
			$url = '?app=' . $install_app;
		}
		if ($install_app == 'auth')
		{
			$url = 'login.php?a=logout';
		}
		
		hg_flushMsg('应用安装成功', $url);
?>