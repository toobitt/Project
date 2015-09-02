<?php
/**
*
*
	$str = array(
		'action' => 'restart',
		'env' => 'nginx',
		'user' => 'root',
		'pass' => 'nginx',
	);
	exit;
	echo '<textarea rows="40" cols="120">' . $data . '</textarea><br />';
*/
require('./global.php');
set_time_limit(0);
$Cfg['upgradeServer'] = array(
	'host' => 'upgrade.hogesoft.com',
	'port' => 233,
	'dir' => '',
	'token' => 'dfsadfadsffadf',
);
$Cfg['authServer'] = array(
	'host' => 'auth.hogesoft.com',
	'port' => 80,
	'dir' => '',
	'token' => 'dfsadfadsffadf',
);
$action = $_REQUEST['action'];
if (!$action)
{
	include('tpl/index.tpl.php');
}
if ($action == 'addserver')
{
	$pagetitle = '增加服务器_';
	$server['port'] = 6233;
	$doaction = 'doaddserver';
	$stext = '增加';
	include('tpl/form_server.tpl.php');
}
if ($action == 'editserver')
{
	$pagetitle = '修改服务器_';
	$id = $_REQUEST['id'];
	$server = $servers[$id];
	$doaction = 'doeditserver';
	$stext = '修改';
	$autoload = 'change_servertype(\'' . $server['type'] . '\', ' . $id . ');';
	include('tpl/form_server.tpl.php');
}
if ($action == 'doeditserver')
{
	$id = $_REQUEST['id'];
	$data = $_REQUEST;
	unset($data['action'],$data['s']);

//	$data['pass'] = hg_encript_str($data['pass']);
	$data['id'] = $id;
	$servers[$id] = $data;
	$servers = json_encode($servers);
	hg_file_write('db/server.' . $customer, $servers);
	header('Location:./index.php');
}
if ($action == 'copy')
{
	$id = $_REQUEST['id'];
	$server = $servers[$id];
	$pagetitle = '增加服务器_';
	$doaction = 'doaddserver';
	$stext = '增加';
	$autoload = 'change_servertype(\'' . $server['type'] . '\', ' . $id . ');';
	include('tpl/form_server.tpl.php');
}
if ($action == 'doaddserver')
{
	$id = intval(@file_get_contents('db/autoid'));
	$id = $id + 1;
	$data = $_REQUEST;
	unset($data['action'],$data['s']);
	$data['id'] = $id;
	//$data['pass'] = hg_encript_str($data['pass']);
	$servers[$id] = $data;
	$servers = json_encode($servers);
	hg_file_write('db/server.' . $customer, $servers);
	hg_file_write('db/autoid', $id);
	header('Location:./index.php');
}
if ($action == 'deleteserver')
{
	$id = $_REQUEST['id'];
	unset($servers[$id]);
	$servers = json_encode($servers);
	hg_file_write('db/server.' . $customer, $servers);
	header('Location:./index.php');
}
if ($action == 'df')
{
	$id = $_REQUEST['id'];
	$server = $servers[$_REQUEST['id']];
	$configs = hg_run_cmd($server, 'df');
	$doaction = 'babbaa';
	include('tpl/man.tpl.php');
} 
//更改服务器hosts 
if ($action == 'install')
{
	if (!$servers)
	{
		exit('未部署服务器！');
	}
	include('tpl/head.tpl.php');
	echo '<br />开始修改服务器 hosts 配置 <br />';
	foreach($servers AS $k => $v)
	{
		
		$hostscontent = get_serv_file( $v, '/etc/hosts');
		$hosts = hg_get_hosts($hostscontent);
		$hosts = hg_mk_hosts($servers, 'ip', $hosts);
		if ($hosts)
		{
			$lhost = array();
			foreach ($hosts AS $ip => $domain)
			{
				if (!is_array($domain))
				{
					continue;
				}
				$lhost[] = $ip . '	' . implode(' ', $domain);
			}
			$hoststr = "\n" . implode("\n", $lhost);
			
			write_serv_file( $v, '/etc/hosts', $hostscontent . $hoststr);
			echo $v['ip'] . ' hosts已更新 <br />';
		}
	}
	
	echo '<br/ >服务器需配置 hosts<br /><textarea name="c" cols="80" rows="10">' . implode("\n",$lhost) . '</textarea>';
	$hosts = hg_mk_hosts($servers, 'outip', $hosts);
	$lhost = array();
	foreach ($hosts AS $ip => $domain)
	{
		if (!is_array($domain))
		{
			continue;
		}
		$lhost[] = $ip . '	' . implode(' ', $domain);
	}
	echo '<br />本地需配置 hosts<br ><textarea name="c" cols="80" rows="10">' . implode("\n",$lhost) . '</textarea>';
	echo '  <div align="center">
	<input type="button" name="s" value=" 下一步 " style="width:100px;height:40px;color:#fff;font-size:16px;margin-top:20px;" onclick="document.location.href=\'index.php?action=install2\'" />
  </div>';
	include('tpl/foot.tpl.php');
}
//计算出服务器需要更换的配置
if ($action == 'install2')
{
	if (!$servers)
	{
		exit('未部署服务器！');
	}
	include('tpl/head.tpl.php');
	$serv = array();
	foreach ($servers AS $k => $v)
	{
		$serv[$v['type']][] = $v;
	}
	foreach($Cfg['servertype'] AS $k => $v)
	{
		if (!in_array($k, array('app', 'api', 'img', 'mediaserver', 'vodplay')))
		{
			continue;
		}
		$data = $serv[$k];
		if ($data)
		{
			$func = 'hg_' . $k . '_parse_domain';
			if (!function_exists($func))
			{
				$func = 'hg_default_parse_domain';
			}
			foreach ($data AS $kk => $vv)
			{
				unset($vv['checked']);
				$func($k, $vv);
			}
		}

	}
	$config_write = false;
	echo '<form name="ff" action="?action=install3" method="post">';
	if ($gDomains)
	{
		foreach($gDomains AS $k => $v)
		{
			if ($v)
			{
				$apache_configs = $nginx_configs = '';
				$conffiles = $ip2serv = array();
				foreach ($v AS $DOMAIN => $vv)
				{
					foreach ($Cfg['server'] AS $kkk => $vvv)
					{
						$conf = $Cfg['serverconf'][$vv['type']][$kkk];
						if (!$conf)
						{
							$conf = $Cfg['serverconf']['default'][$kkk];
						}
						if (!$conf)
						{
							continue;
						}
						$confile = $Cfg['servertype'][$vv['type']]['conf'][$kkk];
						if (!$confile)
						{
							continue;
						}
						$conffiles[$kkk] = $confile;
						
						$content = get_serv_file( $vv, $confile);
						if (@preg_match('/server_name\s+' . $DOMAIN . '/is', $content))
						{
							continue;
						}
						if (@preg_match('/ServerName\s+' . $DOMAIN . '/is', $content))
						{
							continue;
						}
						$config_write = true;
						if (!$IMGAPIDOMAIN)
						{
							$conf = preg_replace("/(rewrite[\s]+\$[\s]+http:\/\/\$IMGAPIDOMAIN\/createfile\.php?host=\$host\&refer_to=\$request_uri;)/is", '', $conf);
						}
						if (!$VODDOMAIN)
						{
							$conf = preg_replace("/(location[\s]+\/vod\/[\s\n]*\{.*?\})/is", '', $conf);
						}
						$conf = str_replace(array('$DOMAIN', '$DIR', '$IMGAPIDOMAIN', '$VODDOMAIN'), array($DOMAIN, $vv['dir'], $IMGAPIDOMAIN, $VODDOMAIN), $conf);
						$var = $kkk . '_configs';
						$$var .= $conf . "\n";
					}
					$ip2serv[$k] = $vv;
				}
				
				foreach ($Cfg['server'] AS $kk => $vv)
				{
					$var = $kk . '_configs';
					if ($$var)
					{
						echo '<br/ >' . $kk . '服务器' . $k . '<br /><textarea name="c[' . $k . '][' . $kk . ']" cols="100" rows="12">' . $$var . '</textarea>
						<input type="hidden" name="f[' . $k . '][' . $kk . ']" value="' . $conffiles[$kk] . '" />
						<input type="hidden" name="serv[' . $k . '][' . $kk . '][ip]" value="' . $ip2serv[$k]['ip'] . '" />
						<input type="hidden" name="serv[' . $k . '][' . $kk . '][user]" value="' . $ip2serv[$k]['user'] . '" />
						<input type="hidden" name="serv[' . $k . '][' . $kk . '][pass]" value="' . $ip2serv[$k]['pass'] . '" />
						<input type="hidden" name="serv[' . $k . '][' . $kk . '][port]" value="' . $ip2serv[$k]['port'] . '" />
						';
					}
				}
			}
		}
	}
	if (!$config_write)
	{
		echo '服务器域名配置已配置，若有不正确，请登录服务器进行修改';
	}
	echo '  <div align="center">
	<input type="submit" name="s" value=" 下一步 " style="width:100px;height:40px;color:#fff;font-size:16px;margin-top:20px;" />
  </div></form>';
	include('tpl/foot.tpl.php');
}
//更改服务器配置
if ($action == 'install3')
{
	include('tpl/head.tpl.php');
	$c = $_REQUEST['c'];
	$f = $_REQUEST['f'];
	$serv = $_REQUEST['serv'];
	if ($c)
	{
		foreach ($c AS $ip => $v)
		{
			if ($v)
			{
				foreach ($v AS $kk => $content)
				{
					$servinfo = $serv[$ip][$kk];
					$file = $f[$ip][$kk];
					if ($servinfo && $file)
					{
						
						$serv_content = get_serv_file( $servinfo, $file);
						if ($kk == 'nginx')
						{
							$content = preg_replace("/(http[\s\n]*\{.*)\}/is", '\\1' . $content . '}', $serv_content);
						}
						elseif ($kk == 'apache')
						{
							$content = $serv_content . $content;
						}
						
						write_serv_file( $servinfo, $file, $content);
						$cmd = $Cfg['server'][$kk]['restart'];
						if ($cmd)
						{
							
							hg_run_cmd( $servinfo, 'restart', $cmd);
						}
					}
				}
			}
		}
	}	
	echo '  <div align="center">
		<input type="button" name="s" value=" 下一步 " style="width:100px;height:40px;color:#fff;font-size:16px;margin-top:20px;" onclick="document.location.href=\'index.php?action=install4\'" />
	  </div>';
	include('tpl/foot.tpl.php');
}
//下载app程序
if ($action == 'install4')
{
	$serv = array();
	foreach ($servers AS $k => $v)
	{
		$serv[$v['type']][] = $v;
	}
	include('tpl/head.tpl.php');
	ob_start();
	include_once('curl.php');
	$curl = new curl($Cfg['upgradeServer']['host'] . ':' . $Cfg['upgradeServer']['port'], '', $Cfg['upgradeServer']['token']);
	$curl->setSubmitType('get');
	$curl->setReturnFormat('json');
	$curl->initPostData();
	$curl->addRequestData('install', 1);
	if ($serv['app'])
	{
		hg_flushMsg('开始下载管控程序');
		foreach ($serv['app'] AS $k => $v)
		{
			if (!$v['checked'])
			{
				continue;
			}
			foreach ($v['checked'] AS $kk)
			{
				$app = $v['app'][$kk];
				if (!$app['dir'])
				{
					continue;
				}
				hg_flushMsg('正在获取软件包到' . $kk . $v['ip']);
				$curl->addRequestData('app', $kk);
				$ret = $curl->request('file.php');
				if ($ret['zip'])
				{
					hg_flushMsg('正在下载' . $ret['zip']);
					$para = 'http://' . $Cfg['upgradeServer']['host'] . ':' . $Cfg['upgradeServer']['port'] . '/' . $ret['zip'];
					
					hg_run_cmd( $v, 'download', $para, $app['dir']);
					hg_run_cmd( $v, 'mkdirs', $app['dir'] . 'cache/');
					if ($kk == 'livmcp')
					{
						hg_run_cmd( $v, 'mkdirs', $app['dir'] . 'upgrade/temp/');
						$fileupdate = $app['dir'] . 'upgrade/temp/update.time';
						$content = time();
						write_serv_file( $v, $fileupdate, $content, 'utf8');
						hg_run_cmd( $v, 'runcmd', 'chmod -Rf 755 ' .$app['dir'] . 'cron/ ' . $app['dir'] . 'upgrade/temp/');
					}
					if ($kk == 'livcmscp')
					{
						$dir777 = ' ' . $app['dir'] . 'backup/ ' . $app['dir'] . 'templates/ ' . $app['dir'] . 'tempdata/';
					}
					hg_run_cmd( $v, 'runcmd', 'chmod -Rf 777 ' .$app['dir'] . 'cache/' . $dir777);
					hg_flushMsg('下载完成');
				}
			}
		}
	}
	echo '  <div align="center">
		<input type="button" name="s" value=" 下一步 " style="width:100px;height:40px;color:#fff;font-size:16px;margin-top:20px;" onclick="document.location.href=\'index.php?action=install4_1\'" />
	  </div>';
	include('tpl/foot.tpl.php');
}
if ($action == 'install4_1')
{
	$serv = array();
	foreach ($servers AS $k => $v)
	{
		$serv[$v['type']][] = $v;
	}
	include('tpl/head.tpl.php');
	ob_start();
	include_once('curl.php');
	$curl = new curl($Cfg['upgradeServer']['host'] . ':' . $Cfg['upgradeServer']['port'], '', $Cfg['upgradeServer']['token']);
	$curl->setSubmitType('get');
	$curl->setReturnFormat('json');
	$curl->initPostData();
	$curl->addRequestData('install', 1);
	if ($serv['api'])
	{
		hg_flushMsg('开始下载应用程序');		
		foreach ($serv['api'] AS $k => $v)
		{
			if (!$v['checked'])
			{
				continue;
			}
			$apibasedir = $v['api']['dir'];
			hg_flushMsg('正在获取软件包到' . $v['ip']);
			foreach ($v['checked'] AS $kk)
			{
				$app = $v['api'][$kk];
				if (!$app)
				{
					continue;
				}
				$apidir = $apibasedir . 'api/' . $kk . '/';
				hg_run_cmd( $v, 'mkdirs', $apibasedir);
				$curl->addRequestData('app', $kk);
				$ret = $curl->request('file.php');
				if ($ret['zip'])
				{
					$para = 'http://' . $Cfg['upgradeServer']['host'] . ':' . $Cfg['upgradeServer']['port'] . '/' . $ret['zip'];
					
					hg_run_cmd( $v, 'download', $para, $apibasedir);
					hg_run_cmd( $v, 'mkdirs', $apibasedir . 'uploads/');
					hg_run_cmd( $v, 'mkdirs', $apidir . 'cache/');
					hg_run_cmd( $v, 'mkdirs', $apidir . 'data/');
					hg_run_cmd( $v, 'runcmd', 'chmod -Rf 777  ' . $apibasedir . 'uploads/ ' . $apidir . 'cache/ ' . $apidir . 'data/');
					if ($kk == 'mobile')
					{
						hg_run_cmd( $v, 'mkdirs', $apidir . 'api/');
						hg_run_cmd( $v, 'mkdirs', $apidir . 'certificate/');
						hg_run_cmd( $v, 'runcmd', 'chmod -Rf 777 ' . $apidir . 'api/ ' . $apidir . 'certificate/');
					}
				}
			}
			hg_flushMsg('软件包获取完成');
		}
	}
	echo '  <div align="center">
		<input type="button" name="s" value=" 下一步 " style="width:100px;height:40px;color:#fff;font-size:16px;margin-top:20px;" onclick="document.location.href=\'index.php?action=install4_2\'" />
	  </div>';
	include('tpl/foot.tpl.php');
}
if ($action == 'install4_2')
{
	$serv = array();
	foreach ($servers AS $k => $v)
	{
		$serv[$v['type']][] = $v;
	}
	include('tpl/head.tpl.php');
	ob_start();
	include_once('curl.php');
	$curl = new curl($Cfg['upgradeServer']['host'] . ':' . $Cfg['upgradeServer']['port'], '', $Cfg['upgradeServer']['token']);
	$curl->setSubmitType('get');
	$curl->setReturnFormat('json');
	$curl->initPostData();
	$curl->addRequestData('install', 1);
	//安装图片服务器
	if ($serv['img'])
	{
		hg_flushMsg('开始下载图片服务程序');		
		foreach ($serv['img'] AS $k => $v)
		{
			$app = $v['img'];
			hg_flushMsg('正在获取软件包到' . $v['ip']);
			$apidir = $app['dir'] . 'api/material/';
			hg_run_cmd( $v, 'mkdirs', $app['dir']);
			$curl->addRequestData('app', 'material');
			$ret = $curl->request('file.php');
			if ($ret['zip'])
			{
				$para = 'http://' . $Cfg['upgradeServer']['host'] . ':' . $Cfg['upgradeServer']['port'] . '/' . $ret['zip'];
				
				hg_run_cmd( $v, 'download', $para, $app['dir']);
				hg_run_cmd( $v, 'mkdirs', $apidir . 'cache/');
				hg_run_cmd( $v, 'mkdirs', $apidir . 'data/');
				hg_run_cmd( $v, 'mkdirs', $app['uploaddir']);
				$content = '<?xml version="1.0"?>
<!-- http://www.adobe.com/crossdomain.xml -->
<cross-domain-policy>
<allow-access-from domain="*" />
</cross-domain-policy>';
				write_serv_file( $v, $app['uploaddir'] . '/crossdomain.xml', $content, 'utf8');
				hg_run_cmd( $v, 'runcmd', 'chmod -Rf 777 ' . $apidir . 'cache/ ' . $apidir . 'data/ ' . $app['uploaddir']);
			}
			hg_flushMsg('软件包获取完成');
		}
		hg_flushMsg('图片服务程序下载完成');
	}
	//安装上传服务器
	
	if ($serv['mediaserver'])
	{
		hg_flushMsg('开始下载上传程序');		
		foreach ($serv['mediaserver'] AS $k => $v)
		{
			$app = $v['mediaserver'];
			hg_flushMsg('正在获取软件包到' . $v['ip']);
			$apidir = $app['dir'] . 'api/mediaserver/';
			hg_run_cmd( $v, 'mkdirs', $apidir);
			$curl->addRequestData('app', 'mediaserver');
			$ret = $curl->request('file.php');
			if ($ret['zip'])
			{
				$para = 'http://' . $Cfg['upgradeServer']['host'] . ':' . $Cfg['upgradeServer']['port'] . '/' . $ret['zip'];
				
				hg_run_cmd( $v, 'download', $para, $app['dir']);
				hg_run_cmd( $v, 'mkdirs', $app['dir'] . 'uploads/');
				hg_run_cmd( $v, 'mkdirs', $app['uploaddir']);
				hg_run_cmd( $v, 'mkdirs', $app['targetdir']);
				hg_run_cmd( $v, 'runcmd', 'chmod -Rf 777 ' . $app['dir'] . 'uploads/ ' . $app['uploaddir'] . ' ' . $app['targetdir']);
				$content = '<?xml version="1.0"?>
<!-- http://www.adobe.com/crossdomain.xml -->
<cross-domain-policy>
<allow-access-from domain="*" />
</cross-domain-policy>';
				write_serv_file( $v, $app['uploaddir'] . '/crossdomain.xml', $content, 'utf8');
				write_serv_file( $v, $app['targetdir'] . '/crossdomain.xml', $content, 'utf8');
				hg_run_cmd( $v, 'mkdirs', $apidir . 'cache/');
				hg_run_cmd( $v, 'mkdirs', $apidir . 'data/');
				hg_run_cmd( $v, 'runcmd', 'chmod -Rf 777 ' . $apidir . 'cache/ ' . $apidir . 'data/');
			}
			hg_flushMsg('软件包获取完成');
		}
		hg_flushMsg('上传程序下载完成');	
	}
	echo '  <div align="center">
		<input type="button" name="s" value=" 下一步 " style="width:100px;height:40px;color:#fff;font-size:16px;margin-top:20px;" onclick="document.location.href=\'index.php?action=install5\'" />
	  </div>';
	include('tpl/foot.tpl.php');
}

//申请授权
if ($action == 'install5')
{
	$license = @include('./db/license.php');
	if($license)
	{
		header('Location:index.php?action=install6');
	}
	include('tpl/customer_info.tpl.php');
}

//配置程序
if ($action == 'install6')
{
	$license = @include('./db/license.php');
	if(!$license)
	{
		include_once('curl.php');
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
		$ret = $curl->request('auth_update.php');
		if (!$ret[0]['appid'])
		{
			$message = $ret['ErrorCode'];
			include('tpl/customer_info.tpl.php');
			exit;
		}
		$licenseinfo = $ret[0];
		file_put_contents('./db/license.php', '<?php $licenseinfo = ' . var_export($licenseinfo, 1) . ' ?>');
	}
	$needappkey = array();
	$serv = array();
	foreach ($servers AS $k => $v)
	{
		$serv[$v['type']][$k] = $v;
	}
	$dvrserver = @array_pop($serv['dvr']);
	$liveserver = @array_pop($serv['live']);
	$recordserver = $serv['record'];
	$recordserver = @array_pop($recordserver);
	$vodserver = @array_pop($serv['vodplay']);
	$mediaserver = $serv['mediaserver'];
	$mediaserver = @array_pop($mediaserver);
	$imgserver = @array_pop($serv['img']);
	if (!$recordserver)
	{
		$recordserver['host'] = $liveserver['live']['domain'] . ':8086';
		$recordserver['dir'] = 'recordmanager/';
	}
	else
	{
		$recordserver['host'] = $recordserver['ip'] . ':' . $recordserver['record']['port'];
		$recordserver['dir'] = '';
	}
	//	print_r($imgserver);exit;
	include('tpl/head.tpl.php');
	ob_start();
	$global_conf = array();
	$global_conf_info = array();
	$dbs = array();
	if ($serv['api'])
	{
		hg_flushMsg('开始配置应用程序');
		foreach ($serv['api'] AS $k => $v)
		{
			if (!$v['checked'])
			{
				continue;
			}
			
			foreach ($v['checked'] AS $kk)
			{
				$app = $v['api'];
				$global_conf_info['App_' . $kk] = array(
					'protocol' => 'http://',
					'host' => $app['domain'],
					'dir' => $kk . '/',
				);
			}
		}

		foreach ($serv['api'] AS $k => $v)
		{
			if (!$v['checked'])
			{
				continue;
			}
			$apidir = $v['api']['dir'];
			foreach ($v['checked'] AS $kk)
			{
				$app = $v['api'][$kk];
				$db = $serv['db'][$app['db']]['db'];
				$config_conf = $v['api']['dir'] . 'api/' . $kk . '/conf/config.php';
				$content = get_serv_file( $v, $config_conf);
				if ($content == 'Can\'t access this file')
				{
					echo $config_conf . '文件无法读取并配置';
					continue;
				}
				if(!$content)
				{
					continue;
				}
				$content = preg_replace("/http\:\/\/img\.dev\.hogesoft\.com\:{0,1}\d*/is", 'http://' . $imgserver['img']['uploaddomain'], $content);
				if ($db)
				{
					$string = "\$gDBconfig = array(
	'host'     => '{$db['host']}',
	'user'     => '{$db['user']}',
	'pass'     => '{$db['pass']}',
	'database' => '{$app['dbname']}',
	'charset'  => 'utf8',
	'pconncet' => 0,
);";
					$dbs[$app['db']][$app['dbname']] = $kk;
					$content = preg_replace("/\\\$gDBconfig\s*=\s*array\s*(.*?);/is", $string, $content);
				}
				if ($kk == 'live' || $kk == 'new_live')
				{
					$s = "\$gGlobalConfig['mms'] = array(
	'open' => '1',
	'input_stream_server' => array(//主控输入
		'protocol' => 'http://',
		'host' => '{$liveserver['live']['domain']}:8086',
		'dir' => 'inputmanager/',
	),
	'schedul_stream_server' => array(//主控切播
		'protocol' => 'http://',
		'host' => '{$liveserver['live']['domain']}:8086',
		'dir' => 'inputmanager/',
	),
	
	'record_server' => array(//录制
		'protocol' => 'http://',
		'host' => '{$recordserver['host']}',
		'dir' => '{$recordserver['dir']}',
	),
	'record_server_callback' => array(//wowza抓取时移
		'protocol' => 'http://',
		'host' => '{$vodserver['vodplay']['domain']}',
		'dir' => 'schedul/',
		'prefix' => 'schedul_',
	),
	'output_stream_server' => array(//时移(输出)
		'protocol' => 'http://',
		'host' => '{$liveserver['live']['domain']}:8086',
		'dir' => 'outputmanager/',
	),
	'input' =>array(
		'protocol' => 'rtmp://',
		'wowzaip' => '{$liveserver['live']['domain']}', //前台js调用
		'type' => 'push',
		'appName' => 'input',
		'suffix' => '.stream',
	),
	'file' =>array(
		'protocol' => 'rtmp://',
		'wowzaip' => '{$liveserver['live']['domain']}',
		'appName' => 'file',
		'suffix' => '.list',
	),
	'output_file' =>array(
		'protocol' => 'rtmp://',
		'wowzaip' => '{$liveserver['live']['domain']}',
		'appName' => 'file',
		'prefix' => 'mp4:',
		'fileNamePrefix' => 'vod_',
		'suffix' => '.mp4',
	),
	'delay' => array(
		'wowzaip' => '{$liveserver['live']['domain']}',
		'appName' => 'input',
		'suffix' => '.delay',
	),
	'output' => array(
		'wowzaip' => '{$liveserver['live']['domain']}:1935',
		'suffix' => '.stream',
	),
	'_output' => array(
		'wowzaip' => '{$liveserver['live']['domain']}:1935',
		'suffix' => '.stream',
	),
	'chg' => array(
		'wowzaip' => '{$liveserver['live']['domain']}:1935',
		'appName' => 'input',
		'suffix' => '.output',
	),
	'chg_append_host' => array(
		'1' => '1{$liveserver['live']['domain']}:1935',
		'2' => '2{$liveserver['live']['domain']}:1935',
		'3' => '3{$liveserver['live']['domain']}:1935',
		'4' => '4{$liveserver['live']['domain']}:1935',
	),
	'_chg_append_host' => array(
		'1' => '1{$liveserver['live']['domain']}:1935',
		'2' => '2{$liveserver['live']['domain']}:1935',
		'3' => '3{$liveserver['live']['domain']}:1935',
		'4' => '4{$liveserver['live']['domain']}:1935',
	),
);";

					$content = preg_replace("/\\\$gGlobalConfig\['mms'\]\s*=\s*array\s*(.*?);/is", $s, $content);

					$s = "\$gGlobalConfig['wowza'] = array(
	'counts' 		=> 100,
	'in_port'		=> 8086,
	'out_port'		=> 1935,
	'record_port'	=> 8089,
	'core_input_server' => array(//主控
		'protocol' 		=> 'http://',
		'host' 			=> '{$liveserver['live']['domain']}',
		'port'			=> '8086',
		'input_dir'		=> 'inputmanager/',
		'output_dir'	=> 'outputmanager/',
	),
	'dvr_output_server' => array(//时移
		'protocol' 		=> 'http://',
		'host' 			=> '{$liveserver['live']['domain']}',
		'port'			=> '8086',
		'output_dir' 	=> 'outputmanager/',
	),
	'record_server' => array(//录制
		'protocol' 		=> 'http://',
		'host' => '{$recordserver['host']}',
		'dir' => '{$recordserver['dir']}',
	),
	//输入的输入
	'input' =>array(
		'protocol' 	=> 'rtmp://',
		'type' 		=> 'push',
		'app_name' 	=> 'input',
		'suffix' 	=> '.stream',
	),
	//输入的输出 1935
	'chg' => array(
		'protocol' 	=> 'rtmp://',
		'app_name' 	=> 'input',
		'suffix' 	=> '.output',
	),
	//备播信号形成list流
	'list' =>array(
		'protocol' 	=> 'rtmp://',
		'app_name' 	=> 'file',
		'suffix' 	=> '.list',
	),
	//备播文件
	'backup' =>array(
		'protocol' 	=> 'rtmp://',
		'app_name' 	=> 'file',
		'prefix'	=> 'mp4:',
		'midfix' 	=> 'vod_',
		'suffix' 	=> '.mp4',
	),
	//延时
	'delay' => array(
		'protocol' 	=> 'rtmp://',
		'app_name' 	=> 'input',
		'suffix' 	=> '.delay',
	),
	//dvr输出 1935
	'dvr_output' => array(
		'protocol' 	=> 'rtmp://',
		'suffix' 	=> '.stream',
	),
	//直播输出 1935
	'live_output' => array(
		'protocol' 	=> 'rtmp://',
		'suffix' 	=> '.stream',
	),
	//录制
	'record' => array(//wowza抓取时移
		'protocol' 	=> 'http://',
		'host' 	=> '{$mediaserver['mediaserver']['uploaddomain']}',
		'dir' 	=> '',
		'prefix' 	=> 'schedul_',
	),
	'dvr_append_host' => array(
		'1' => '1{$liveserver['live']['domain']}',
		'2' => '2{$liveserver['live']['domain']}',
		'3' => '3{$liveserver['live']['domain']}',
		'4' => '4{$liveserver['live']['domain']}',
	),
	'live_append_host' => array(
		'1' => '1{$liveserver['live']['domain']}',
		'2' => '2{$liveserver['live']['domain']}',
		'3' => '3{$liveserver['live']['domain']}',
		'4' => '4{$liveserver['live']['domain']}',
	),
);";

					$content = preg_replace("/\\\$gGlobalConfig\['wowza'\]\s*=\s*array\s*(.*?);/is", $s, $content);
				}
				if ($kk == 'player')
				{
					$needappkey[$kk] = array(
						'confile' => $config_conf,
						'serv' => $v,
						'appid' => 3,
						'vname' => '播放器',
						'name' => '播放器'
					);
					$content = preg_replace("/define\('ADV_DATA_HOST',\s*'.*?'\);/is","define('ADV_DATA_HOST','{$global_conf_info['App_adv']['host']}');", $content);
					$content = preg_replace("/define\('ADV_DATA_DIR',\s*'.*?'\);/is","define('ADV_DATA_DIR','{$global_conf_info['App_adv']['dir']}');", $content);
					$content = preg_replace("/define\('THUMB_URL',\s*'.*?'\);/is","define('THUMB_URL','http://{$vodserver['vodplay']['domain']}/');", $content);
					$content = preg_replace("/define\('CURDOMAIN',\s*'.*?'\);/is","define('CURDOMAIN','{$licenseinfo['domain']}');", $content);
					$s = "\$gVodApi = array(
		'host' => '{$global_conf_info['App_livmedia']['host']}',
		'dir' => '{$global_conf_info['App_livmedia']['dir']}',
	);";
					$content = preg_replace("/\\\$gVodApi\s*=\s*array\s*(.*?);/is", $s, $content);
					$s = "\$gLiveApi = array(
		'host' => '{$global_conf_info['App_live']['host']}',
		'dir' => '{$global_conf_info['App_live']['dir']}',
	);";
					$content = preg_replace("/\\\$gLiveApi\s*=\s*array\s*(.*?);/is", $s, $content);
				}
				if ($kk == 'adv')
				{
					$content = preg_replace("/define\('ADV_DATA_URL',\s*'.*?'\);/is","define('ADV_DATA_URL','http://{$global_conf_info['App_adv']['host']}/{$global_conf_info['App_adv']['dir']}data/');", $content);
				}
				if ($kk == 'publishcontent')
				{
					$content = preg_replace("/define\('IMG_URL',\s*'.*?'\);/is","define('IMG_URL','http:\/\/" . str_replace('.', '\.', $imgserver['img']['uploaddomain']) . "');", $content);
				}
				if ($kk == 'livcms')
				{
					$livcmsdb = $db;
					$livcmsdb['dbname'] = $app['dbname'];
					$livcmscp = $serv['app'];
					$livcmscp = @array_pop($livcmscp);
					$content = preg_replace("/define\('LIVCMS_HOST',\s*.*?\);/is","define('LIVCMS_HOST','{$livcmscp['app']['livcmscp']['domain']}/');", $content);
					$content = preg_replace("/define\('CMS_IMG_DOMAIN',\s*.*?\);/is","define('CMS_IMG_DOMAIN','');", $content);
				}
				if ($kk == 'livmedia')
				{
					$content = preg_replace("/define\('PREVIEW_DIR',\s*'.*?'\);/is","define('PREVIEW_DIR',CUR_CONF_PATH . 'data/');", $content);
					$content = preg_replace("/define\('FAST_EDIT_IMGDATA_PATH',\s*.*?\);/is","define('FAST_EDIT_IMGDATA_PATH',CUR_CONF_PATH . 'data/tmp/');", $content);
					$content = preg_replace("/define\('PREVIEW_SOURCE',\s*'.*?'\);/is","define('PREVIEW_SOURCE','http://{$global_conf_info['App_livmedia']['host']}/{$global_conf_info['App_livmedia']['dir']}data/');", $content);
					$content = preg_replace("/\\\$gGlobalConfig\s*\['vod_url'\]\s*=\s*(.*?);/is", "\$gGlobalConfig['vod_url'] = 'http://{$vodserver['vodplay']['domain']}/';", $content);
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

				write_serv_file( $v, $config_conf, $content, 'utf8');
			}

			$si = $v;
			unset($si['api'], $si['checked']);
			$global_conf[$v['ip']][$apidir] = $si; 
			
			hg_flushMsg('应用服务器配置完成');
		}
		if ($imgserver)
		{
			hg_flushMsg('开始配置图片服务器');
			$app = $imgserver['img'];
			$global_conf_info['App_material'] = array(
				'protocol' => 'http://',
				'host' => $app['domain'],
				'dir' => '',
			);
			$config_conf = $app['dir'] . 'api/material/conf/config.php';
			$db = $serv['db'][$imgserver['db']]['db'];
			$content = get_serv_file( $imgserver, $config_conf);
			
			$global_conf[$imgserver['ip']][$app['dir']] = $imgserver; 
			if (!$content)
			{
				echo $config_conf . '文件无法读取并配置';
			}
			$content = preg_replace("/http\:\/\/img\.dev\.hogesoft\.com\:{0,1}\d*/is", 'http://' . $imgserver['img']['uploaddomain'], $content);
			if ($db)
			{
				$string = "\$gDBconfig = array(
'host'     => '{$db['host']}',
'user'     => '{$db['user']}',
'pass'     => '{$db['pass']}',
'database' => '{$imgserver['dbname']}',
'charset'  => 'utf8',
'pconncet' => 0,
);";
				$dbs[$imgserver['db']][$imgserver['dbname']] = 'material';
				$content = preg_replace("/\\\$gDBconfig\s*=\s*array\s*(.*?);/is", $string, $content);
			}
			$imgdir = rtrim($imgserver['img']['uploaddir'], '/') . '/';
			$content = preg_replace("/define\('IMG_DIR',\s*.*?\);/is","define('IMG_DIR','$imgdir');", $content);				
			$match = preg_match("/define\('INITED_APP',\s*.*?\s*\);/is", $content);
			if($match)
			{
				$content = preg_replace("/define\('INITED_APP',\s*.*?\s*\);/is","define('INITED_APP', false);", $content);
			}
			else
			{
				$content = preg_replace("/\?>/is", "\ndefine('INITED_APP', false);\n?>", $content);
			}
			write_serv_file( $imgserver, $config_conf, $content, 'utf8');
			
			hg_flushMsg('图片服务器配置完成');

		}
		if ($serv['mediaserver'])
		{
			hg_flushMsg('开始配置上传服务器');
			$mediaserver = @array_pop($serv['mediaserver']);
			$transcodeserver = $serv['transcode'];
			$transcodeserver = @array_pop($transcodeserver);
			$app = $mediaserver['mediaserver'];
			$global_conf_info['App_mediaserver'] = array(
				'protocol' => 'http://',
				'host' => $app['domain'],
				'dir' => 'admin/',
				'port' => '80',
				'token' => 'asdsdfsdfds'
			);
			$config_conf = $app['dir'] . 'api/mediaserver/conf/config.php';
			$needappkey['mediaserver'] = array(
				'confile' => $config_conf,
				'serv' => $mediaserver,
				'appid' => 5,
				'vname' => '转码系统',
				'name' => '自动收录'
			);
			$db = $serv['db'][$mediaserver['db']]['db'];
			$content = get_serv_file( $mediaserver, $config_conf);
			$global_conf[$mediaserver['ip']][$app['dir']] = $mediaserver; 
			if (!$content)
			{
				echo $config_conf . '文件无法读取并配置';
			}
			$content = preg_replace("/http\:\/\/img\.dev\.hogesoft\.com\:{0,1}\d*/is", 'http://' . $imgserver['img']['uploaddomain'], $content);
			if ($db)
			{
				$string = "\$gDBconfig = array(
'host'     => '{$db['host']}',
'user'     => '{$db['user']}',
'pass'     => '{$db['pass']}',
'database' => '{$mediaserver['dbname']}',
'charset'  => 'utf8',
'pconncet' => 0,
);";
				$dbs[$mediaserver['db']][$mediaserver['dbname']] = 'mediaserver';
				$content = preg_replace("/\\\$gDBconfig\s*=\s*array\s*(.*?);/is", $string, $content);
			}
			$uploaddir = rtrim($app['uploaddir'], '/') . '/';
			$targetdir = rtrim($app['targetdir'], '/') . '/';
			$content = preg_replace("/define\('UPLOAD_DIR',\s*.*?\);/is","define('UPLOAD_DIR','{$uploaddir}');", $content);
			$content = preg_replace("/define\('TARGET_DIR',\s*.*?\);/is","define('TARGET_DIR','{$targetdir}');", $content);
			$content = preg_replace("/define\('FFMPEG_CMD',\s*.*?\);/is","define('FFMPEG_CMD','/usr/local/bin/ffmpeg');", $content);
			$string = "\$gGlobalConfig['transcode'] = array(
	'protocol' => 'http://',
	'host'	   => '{$transcodeserver['ip']}',
	'port'	   => '{$transcodeserver['transcode']['port']}',
);";
			$content = preg_replace("/\\\$gGlobalConfig\['transcode'\]\s*=\s*array\s*(.*?);/is", $string, $content);
			$string = "\$gGlobalConfig['more_bitrate_callback'] = array(
	 'protocol' =>'http://',
     'host' 	=>'{$mediaserver['mediaserver']['domain']}',
     'dir' 		=>'admin/',
     'port' 	=>'80',
     'filename' =>'more_bitrate_callback.php'
);
";
			$content = preg_replace("/\\\$gGlobalConfig\['more_bitrate_callback'\]\s*=\s*array\s*(.*?);/is", $string, $content);
			$string = "\$gGlobalConfig['videouploads'] = array(
	 'protocol' =>'http://',
     'host' 	=>'{$vodserver['vodplay']['domain']}',
     'dir' 		=>'',
);
";
			$content = preg_replace("/\\\$gGlobalConfig\['videouploads'\]\s*=\s*array\s*(.*?);/is", $string, $content);		
			$match = preg_match("/define\('INITED_APP',\s*.*?\s*\);/is", $content);
			if($match)
			{
				$content = preg_replace("/define\('INITED_APP',\s*.*?\s*\);/is","define('INITED_APP', false);", $content);
			}
			else
			{
				$content = preg_replace("/\?>/is", "\ndefine('INITED_APP', false);\n?>", $content);
			}
			write_serv_file( $mediaserver, $config_conf, $content, 'utf8');
			hg_flushMsg('上传服务器配置完成');

		}
		//print_r($serv['transcode');
		if ($serv['transcode'])
		{
			hg_flushMsg('开始配置转码服务器');
			foreach ($serv['transcode'] AS $k => $v)
			{
				$conffile = $v['transcode']['confile'];
				if ($conffile)
				{
					$content = get_serv_file( $v, $conffile);
					if (!$content)
					{
						continue;
					}
					$confiledir = explode('/', $conffile);
					unset($confiledir[count($confiledir) -1]);
					$confiledir = implode('/', $confiledir);
					$content = preg_replace("/(default_transcode_file_source_path\s*=\s*\")(.*?)(\";)/is", '\\1' . $uploaddir . '\\3', $content);
					$content = preg_replace("/(default_transcode_file_destination_path\s*=\s*\")(.*?)(\";)/is", '\\1' . $targetdir . '\\3', $content);
					$content = preg_replace("/(transcode_file_upload_temp_path\s*=\s*\")(.*?)(\";)/is", '\\1' . $uploaddir . 'temp/\\3', $content);
					$content = preg_replace("/(transcode_tasks_dir\s*=\s*\")(.*?)(\";)/is", '\\1' . $confiledir . '/tasks/\\3', $content);
					$content = preg_replace("/(water_mark_img_dir\s*=\s*\")(.*?)(\";)/is", '\\1' . $confiledir . '/water_mark_img/\\3', $content);
					
					$content = preg_replace("/(default_record_file_path\s*=\s*\")(.*?)(\";)/is", '\\1' . $uploaddir . '\\3', $content);
					$content = preg_replace("/(default_timeshift_file_path\s*=\s*\")(.*?)(\";)/is", '\\1' . $uploaddir . '\\3', $content);
					$content = preg_replace("/(unfinished_record_tasks_file_path\s*=\s*\")(.*?)(\";)/is", '\\1' . $confiledir . '/record_tasks.xml/\\3', $content);
					write_serv_file( $v, $conffile, $content, 'utf8');
				}
			}
			hg_flushMsg('转码服务器配置完成');
		}
		if ($serv['record'])
		{
			hg_flushMsg('开始配置录制服务器');
			foreach ($serv['record'] AS $k => $v)
			{
				$conffile = $v['record']['confile'];
				if ($conffile)
				{
					$content = get_serv_file( $v, $conffile);
					if (!$content)
					{
						continue;
					}
					$confiledir = explode('/', $conffile);
					unset($confiledir[count($confiledir) -1]);
					$confiledir = implode('/', $confiledir);
					$content = preg_replace("/(default_transcode_file_source_path\s*=\s*\")(.*?)(\";)/is", '\\1' . $uploaddir . '\\3', $content);
					$content = preg_replace("/(default_transcode_file_destination_path\s*=\s*\")(.*?)(\";)/is", '\\1' . $targetdir . '\\3', $content);
					$content = preg_replace("/(transcode_file_upload_temp_path\s*=\s*\")(.*?)(\";)/is", '\\1' . $uploaddir . 'temp/\\3', $content);
					$content = preg_replace("/(transcode_tasks_dir\s*=\s*\")(.*?)(\";)/is", '\\1' . $confiledir . '/tasks/\\3', $content);
					$content = preg_replace("/(water_mark_img_dir\s*=\s*\")(.*?)(\";)/is", '\\1' . $confiledir . '/water_mark_img/\\3', $content);
					$content = preg_replace("/(default_record_file_path\s*=\s*\")(.*?)(\";)/is", '\\1' . $uploaddir . '\\3', $content);
					$content = preg_replace("/(default_timeshift_file_path\s*=\s*\")(.*?)(\";)/is", '\\1' . $uploaddir . '\\3', $content);
					$content = preg_replace("/(unfinished_record_tasks_file_path\s*=\s*\")(.*?)(\";)/is", '\\1' . $confiledir . '/record_tasks.xml\\3', $content);

					write_serv_file( $v, $conffile, $content, 'utf8');
				}
			}
			hg_flushMsg('录制服务器配置完成');
		}
		if ($global_conf && $global_conf_info)
		{
			$string = '';
			foreach ($global_conf_info AS $k => $v)
			{
				$string .= "\$gGlobalConfig['{$k}'] = " . var_export($v, 1) . ";\n";
			}
			file_put_contents('db/api.conf.php', "<?php\n{$string}\n?>");
			foreach ($global_conf AS $ip => $filedir)
			{
				if ($filedir)
				{
					foreach ($filedir AS $k => $v)
					{
						$config_conf = $k . 'conf/global.conf.php';
						$content = get_serv_file( $v, $config_conf);			
						if (!$content)
						{
							echo $config_conf . '文件无法读取并配置';
							continue;
						}
						$content = preg_replace("/\\\$gGlobalConfig\s*\['App_.*'\]\s*=\s*(.*?);/is", $string, $content);
						$string1 = "\$gGlobalConfig['is_open_xs'] = 0;";
						$content = preg_replace("/\\\$gGlobalConfig\s*\['is_open_xs'\]\s*=\s*(.*?);/is", $string1, $content);
						$content = preg_replace("/define\('DEBUG_MODE',\s*.*?\);/is","define('DEBUG_MODE',false);", $content);
						$content = preg_replace("/define\('DEVELOP_MODE',\s*.*?\);/is","define('DEVELOP_MODE',false);", $content);
						write_serv_file( $v, $config_conf, $content, 'utf8');
					}
				}
			}
		}
		hg_flushMsg('应用程序配置完成');
	}
	if ($serv['app'])
	{
		hg_flushMsg('开始配置管控程序');
		
		if ($global_conf_info)
		{
			$appstring = '';
			foreach ($global_conf_info AS $kkk => $vvv)
			{
				$appstring .= "\$gGlobalConfig['{$kkk}'] = " . var_export($vvv, 1) . ";\n";
			}
		}
		foreach ($serv['app'] AS $k => $v)
		{
			if (!$v['checked'])
			{
				continue;
			}
			foreach ($v['checked'] AS $kk)
			{
				$app = $v['app'][$kk];
				if (!$app['dir'])
				{
					continue;
				}
				$domain = $licenseinfo['domain']; //$app['domain'];
				$domain_sector = explode('.', $domain);
				unset($domain_sector[0]);
				$DOMAIN = @implode('.', $domain_sector);
				if ($kk == 'livcmscp')
				{
					$cmsconf = $app['dir'] . 'ws/includes/config.php';
					$needappkey['livcmscp'] = array(
						'confile' => $cmsconf,
						'serv' => $v,
						'appid' => 4,
						'vname' => $licenseinfo['display_name'],
						'name' => $licenseinfo['display_name']
					);
					$content = get_serv_file( $v, $cmsconf);
					$dbstring = "\$db_config = array(
						'servername'     => '{$livcmsdb['host']}',
						'dbusername'     => '{$livcmsdb['user']}',
						'dbpassword'     => '{$livcmsdb['pass']}',
						'db' => array(
							'cmsdbname' => '{$livcmsdb['dbname']}',
							'memberdbname' => '{$livcmsdb['dbname']}',
						),
						'dbcharset'  => 'utf8',
						'usepconnect' => 0,
					);";
					file_put_contents('db/livcmsdb.php', "<?php\n{$dbstring}\n?>");
					$livcmscp = $serv['app'];
					$livcmscp = @array_pop($livcmscp);
					$configstring = "\$liv_config['App_livcms_mk']=array(
	'host' => '{$livcmscp['app']['livcmscp']['domain']}',
	'dir' => 'mk/',
);\n";
					if (!$global_conf_info['App_publishconfig'])
					{
						$global_conf_info['App_publishconfig'] = array();
					}
					if (!$global_conf_info['App_publishcontent'])
					{
						$global_conf_info['App_publishcontent'] = array();
					}
					$configstring .= "\$liv_config['App_publishconfig'] = " . var_export($global_conf_info['App_publishconfig'], 1) . ";\n";
					$configstring .= "\$liv_config['App_publishcontent'] = " . var_export($global_conf_info['App_publishcontent'], 1) . ";\n";
					$content = preg_replace("/\\\$db_config\s*=\s*array\s*(.*?);/is", $dbstring, $content);
					write_serv_file( $v, $cmsconf, $content, 'utf8');
					$cmsconf = $app['dir'] . 'plugin/content_server.wsdl';
					$content = get_serv_file( $v, $cmsconf);
					$content = preg_replace("/<soap\:address\s+location=\"(.*?)\"\s*\/>/is", '<soap:address location="http://' . $livcmscp['app']['livcmscp']['domain'] . '/plugin/soap.server.php" />', $content);
					write_serv_file( $v, $cmsconf, $content, 'utf8');
					continue;
				}
				$global_conf = $app['dir'] . 'conf/global.conf.php';
				$content = get_serv_file( $v, $global_conf);
				if (!$content)
				{
					echo($global_conf . '读取失败');
					continue;
				}
				$content = preg_replace("/\\\$gGlobalConfig\['license'\]\s*=\s*\'.*\';/is", "\$gGlobalConfig['license'] = '{$licenseinfo['domain']}';", $content);
				write_serv_file( $v, $global_conf, $content, 'utf8');
				$db = $serv['db'][$v['db']]['db'];
				if (!$db)
				{
					exit('未设置M2O数据库');
				}
				if ($db['port'] && $db['port'] != '3306')
				{
					$db['host'] .= ':' . $db['port'];
				}
				$config_conf = $app['dir'] . 'conf/config.php';
				$content = get_serv_file( $v, $config_conf);
				if (!$content)
				{
					echo ($config_conf . '读取失败');
					continue;
				}
				$needappkey['livmcp'] = array(
					'confile' => $config_conf,
					'serv' => $v,
					'appid' => 1,
					'vname' => 'M2O',
					'name' => 'M2O'
				);
				$string = "\$gDBconfig = array(
	'host'     => '{$db['host']}',
	'user'     => '{$db['user']}',
	'pass'     => '{$db['pass']}',
	'database' => '{$v['dbname']}',
	'charset'  => 'utf8',
	'pconncet' => 0,
);";
					file_put_contents('db/livmcpdb.php', "<?php\n{$string}\n?>");
				$dbs[$v['db']][$v['dbname']] = 'livmcp';
				$content = preg_replace("/\\\$gDBconfig\s*=\s*array\s*(.*?);/is", $string, $content);
				$content = preg_replace("/\\\$gGlobalConfig\s*\['App_.*'\]\s*=\s*(.*?);/is", $appstring, $content);
				$content = preg_replace("/define\('CUSTOM_APPID',\s*\d+\);/is","define('CUSTOM_APPID','{$licenseinfo['appid']}');", $content);
				$content = preg_replace("/define\('CUSTOM_APPKEY',\s*'.*?'\);/is","define('CUSTOM_APPKEY','{$licenseinfo['appkey']}');", $content);
				$content = preg_replace("/define\('DEBUG_MODE',\s*.*?\);/is","define('DEBUG_MODE',false);", $content);
				$content = preg_replace("/define\('DEVELOP_MODE',\s*.*?\);/is","define('DEVELOP_MODE',0);", $content);
				$content = preg_replace("/define\('LIVCMS_API_HOST',\s*.*?\);/is","", $content);
				$content = preg_replace("/define\('LIVCMS_API_DIR',\s*.*?\);/is","", $content);
				write_serv_file( $v, $config_conf, $content, 'utf8');
				//print_r($v);
				//exit;
				$template_conf = $app['dir'] . 'conf/template.conf.php';
				$content = get_serv_file( $v, $template_conf);
				if (!$content)
				{
					echo $template_conf . '文件无法读取并配置';
					continue;
				}
				$content = preg_replace("/define\('CACHE_TEMPLATE',\s*.*?\);/is","define('CACHE_TEMPLATE',true);", $content);
				$content = preg_replace("/define\('RESOURCE_URL',\s*.*?\);/is","define('RESOURCE_URL','./res/images/');", $content);
				$content = preg_replace("/define\('SCRIPT_URL',\s*.*?\);/is","define('SCRIPT_URL','./res/scripts/');", $content);
				$content = preg_replace("/define\('COMBO_URL',\s*.*?\);/is","define('COMBO_URL','');", $content);
				$content = preg_replace("/define\('TEMPLATE_API',\s*.*?\);/is","define('TEMPLATE_API','http://221.226.87.26:233/publish_templates/template-1.3.0/');", $content);
				write_serv_file( $v, $template_conf, $content, 'utf8');

				$cron_conf = $app['dir'] . 'cron/config.py';
				$needappkey['cron'] = array(
					'confile' => $cron_conf,
					'serv' => $v,
					'appid' => 2,
					'vname' => '计划任务',
					'name' => '自动收录'
				);
				$content = get_serv_file( $v, $cron_conf);
				if (!$content)
				{
					echo $cron_conf . '文件无法读取并配置';
					continue;
				}
				$content = preg_replace("/CRON_TAB\s*=\s*\(.*?\)/is","CRON_TAB = ('{$app['domain']}', 80, 'token', 'crontab.php')", $content);
				write_serv_file( $v, $cron_conf, $content, 'utf8');
			}
		}
		file_put_contents('db/apply_auth', json_encode($needappkey));
		hg_flushMsg('管控程序配置完成');
	}
	if ($dbs)
	{
		hg_flushMsg('开始创建数据库');
		foreach($dbs AS $db => $needcdb)
		{
			if (!$needcdb)
			{
				continue;
			}
			$db = $serv['db'][$db]['db'];
			$sql = 'SHOW DATABASES';
			$link = @mysql_connect($db['host'], $db['user'], $db['pass']);
			mysql_query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary", $link);
			if (!$link)
			{
				hg_flushMsg($db['host'] . '连接失败');
				continue;
			}
			$q = mysql_query($sql, $link);
			$databases = array();
			$alldatabases = array();
			while($row = mysql_fetch_array($q))
			{
				$alldatabases[] = $row['Database'];
			}
			foreach ($needcdb AS $dbname => $v)
			{
				if (in_array($dbname, $alldatabases))
				{	
					hg_flushMsg($dbname . '已存在');
					continue;
				}
				$sql = 'CREATE DATABASE ' . $dbname . ' DEFAULT CHARACTER SET UTF8';
				$q = mysql_query($sql, $link);	
				if (!$q)
				{	
					hg_flushMsg($dbname . '创建失败');
					continue;
				}
			}
		}
		
		file_put_contents('db/installdbs', json_encode($dbs));
		hg_flushMsg('数据库创建完成');
	}
	echo '  <div align="center">
		<input type="button" name="s" value=" 下一步 " style="width:100px;height:40px;color:#fff;font-size:16px;margin-top:20px;" onclick="document.location.href=\'index.php?action=install7\'" />
	  </div>';
	include('tpl/foot.tpl.php');
}
//创建数据表
if ($action == 'install7')
{	
	include_once('curl.php');
	$serv = array();
	foreach ($servers AS $k => $v)
	{
		$serv[$v['type']][$k] = $v;
	}
	include('tpl/head.tpl.php');
	$apply_auth = @file_get_contents('db/apply_auth');
	$apply_auth = json_decode($apply_auth, 1);
	if (!$apply_auth)
	{
		exit('未生成授权文件，请重新运行安装程序');
	}
	ob_start();
	$dbs = @file_get_contents('db/installdbs');
	$dbs = json_decode($dbs, 1);

	if ($dbs)
	{
		hg_flushMsg('开始创建数据表');
		foreach($dbs AS $db => $needcdb)
		{
			if (!$needcdb)
			{
				continue;
			}
			$db = $serv['db'][$db]['db'];
			if(!$db)
			{
				continue;
			}
			$link = @mysql_connect($db['host'], $db['user'], $db['pass']);
			foreach ($needcdb AS $dbname => $app)
			{
				mysql_select_db($dbname, $link);
				hg_updateDb($app, $link, $dbname);
				if ($app == 'auth')
				{
					$sql = 'REPLACE INTO liv_authinfo (appid, appkey, custom_desc) VALUES (1, \'thisistempkey\', \'临时使用，请删除\')';
					mysql_query($sql, $link);
					define('APPID', 1);
					define('APPKEY', 'thisistempkey');
				}
			}
		}
		//@unlink('db/installdbs');
		hg_flushMsg('数据表创建完成');
	}
	include('db/api.conf.php');
	$auth = new curl($gGlobalConfig['App_auth']['host'], $gGlobalConfig['App_auth']['dir']);
	if ($apply_auth)
	{
		hg_flushMsg('申请接口授权');
		$license = @include('./db/license.php');
		foreach ($apply_auth AS $var => $v)
		{
			$auth->initPostData();
			$auth->addRequestData('a', 'create');
			$auth->addRequestData('custom_name', $v['vname']);
			$auth->addRequestData('display_name', $v['name']);
			$auth->addRequestData('domain', $licenseinfo['domain']);
			$auth->addRequestData('bundle_id', $var);
			$auth->addRequestData('custom_desc', $v['name']);
			$auth->addRequestData('expire_time', 0);
			$ret = $auth->request('admin/auth_update.php');
			$ret = $ret[0];
			$appid = $ret['appid'];
			$appkey = $ret['appkey'];
			if ($v['appid'] == 1 && !$defined)
			{
				$defined = true;
				file_put_contents('db/appauth.php', "<?php\ndefine('APPID', '{$appid}');\ndefine('APPKEY', '{$appkey}');\n?>");
			}
			$content = get_serv_file( $v['serv'], $v['confile']);
			if ($var == 'cron')
			{
				$content = preg_replace("/APPID\s*=\s*\d+/is","APPID = {$appid}", $content);
				$content = preg_replace("/APPKEY\s*=\s*\'.*?\'/is","APPKEY = '{$appkey}'", $content);
			}
			else
			{
				$content = preg_replace("/(define\('APPID',)\s*(.*?)(\);)/is", '\\1\'' . $appid . '\'\\3', $content);
				$content = preg_replace("/(define\('APPKEY',)\s*(.*?)(\);)/is", '\\1\'' . $appkey . '\'\\3', $content);
			}
			write_serv_file( $v['serv'], $v['confile'], $content, 'utf8');
		}
		@unlink('db/apply_auth');
		hg_flushMsg('接口授权申请成功');
	}


	echo '  <div align="center">
		<input type="button" name="s" value=" 下一步 " style="width:100px;height:40px;color:#fff;font-size:16px;margin-top:20px;" onclick="document.location.href=\'index.php?action=install8\'" />
	  </div>';
	include('tpl/foot.tpl.php');
}

//初始化系统
if ($action == 'install8')
{
	include_once('curl.php');
	include('tpl/head.tpl.php');
	ob_start();
	hg_flushMsg('初始化应用程序');
	include('db/livmcpdb.php');
	$link = @mysql_connect($gDBconfig['host'], $gDBconfig['user'], $gDBconfig['pass']);
	mysql_select_db($gDBconfig['database'], $link);
	mysql_query("SET character_set_connection=".$gDBconfig['charset'].", character_set_results=".$gDBconfig['charset'].", character_set_client=binary", $link);

	$serv = array();
	foreach ($servers AS $k => $v)
	{
		$serv[$v['type']][$k] = $v;
	}
	$appids = array();
	if ($serv['api'])
	{
		hg_flushMsg('正在初始化管控程序');
		$sql = '';
		foreach ($serv['api'] AS $k => $v)
		{
			if (!$v['checked'])
			{
				continue;
			}
			$data = get_store_data('applications', " WHERE softvar IN ('" . implode("','", $v['checked']) . "', 'material', 'mediaserver')");
			foreach ($data AS $kk => $mod)
			{
				$app = $v['api'];
				$appids[] = $mod['id'];
				$name = $Cfg['serverapp']['api'][$mod['softvar']]['name'];
				$mod['host'] = $app['domain'];
				$mod['dir'] = $mod['softvar'] . '/admin/';
				$mod['admin_dir'] = 'admin/';
				if (!$sql)
				{
					$sql = 'REPLACE INTO liv_applications (' . implode(',', array_keys($mod)) . ') VALUES ';
				}
				$sql .= "('" . implode("','", $mod) . "'),";
			}
		}
		if ($sql)
		{
			$sql = rtrim($sql, ',');
			mysql_query($sql, $link);
		}
	}
	if ($appids)
	{
		$sql = '';
		$modules = get_store_data('modules', 'WHERE application_id IN (' . implode(',', $appids) . ')');		
		$templates = array();
		$program = array();
		if($modules)
		{
			$modids = array();
			foreach ($modules AS $mod)
			{
				$mod['host'] = '';
				$mod['dir'] = '';
				$modids[] = $mod['id'];
				if ($mod['template'])
				{
					$templates[] = $mod['template'];
				}
				$program[$mod['id']][$mod['func_name']] = $mod['func_name'];
				if (!$sql)
				{
					$sql = 'REPLACE INTO liv_modules (' . implode(',', array_keys($mod)) . ') VALUES ';
				}
				$sql .= "('" . implode("','", $mod) . "'),";
			}
			$sql = rtrim($sql, ',');
			mysql_query($sql, $link);
		}
		
		$modules = get_store_data('node', 'WHERE application_id IN (' . implode(',', $appids) . ')');
		if($modules)
		{
			$sql = '';
			foreach ($modules AS $mod)
			{
				$mod['host'] = '';
				$mod['dir'] = '';
				if (!$sql)
				{
					$sql = 'REPLACE INTO liv_node (' . implode(',', array_keys($mod)) . ') VALUES ';
				}
				$sql .= "('" . implode("','", $mod) . "'),";
			}
			$sql = rtrim($sql, ',');
			mysql_query($sql, $link);
		}
	}


	if ($modids)
	{
		include('db/api.conf.php');
		$module_ops = get_store_data('module_op', 'WHERE module_id IN(' . implode(',', $modids) . ')');
		$sql = '';
		foreach ($module_ops AS $mod)
		{

			$mod['host'] = '';
			$mod['dir'] = '';
			if ($mod['module_id'] == 190 && $mod['op'] == 'record_list')
			{
				$mod['host'] = $gGlobalConfig['App_live']['host'];
				$mod['dir'] = $gGlobalConfig['App_live']['dir'] . 'admin/';
			}
			$program[$mod['module_id']][$mod['op']] = $mod['op'];
			if ($mod['template'])
			{
				$templates[] = $mod['template'];
			}
			if (!$sql)
			{
				$sql = 'REPLACE INTO liv_module_op (' . implode(',', array_keys($mod)) . ') VALUES ';
			}
			$sql .= "('" . implode("','", $mod) . "'),";
		}
		$sql = rtrim($sql, ',');
		mysql_query($sql, $link);	

		$modules = get_store_data('module_node', 'WHERE module_id IN(' . implode(',', $modids) . ')');
		if($modules)
		{
			$sql = '';
			foreach ($modules AS $mod)
			{
				if (!$sql)
				{
					$sql = 'REPLACE INTO liv_module_node (' . implode(',', array_keys($mod)) . ') VALUES ';
				}
				$sql .= "('" . implode("','", $mod) . "'),";
			}
		}
		$sql = rtrim($sql, ',');
		mysql_query($sql, $link);
		
		$modules = get_store_data('module_append', 'WHERE module_id IN(' . implode(',', $modids) . ')');
		if($modules)
		{
			$sql = '';
			foreach ($modules AS $mod)
			{
				if ($mod['module_id'] == 190 && $mod['op'] == 'load_time_shift')
				{
					$mod['host'] = $gGlobalConfig['App_live']['host'];
					$mod['dir'] = $gGlobalConfig['App_live']['dir']. 'admin/';
				}
				if (!$sql)
				{
					$sql = 'REPLACE INTO liv_module_append (' . implode(',', array_keys($mod)) . ') VALUES ';
				}
				$sql .= "('" . implode("','", $mod) . "'),";
			}
		}
		$sql = rtrim($sql, ',');
		mysql_query($sql, $link);
	}

	$modules = get_store_data('menu', 'WHERE father_id=0');
	if($modules)
	{
		$sql = '';
		foreach ($modules AS $mod)
		{
			if (!$sql)
			{
				$sql = 'REPLACE INTO liv_menu (`' . implode('`,`', array_keys($mod)) . '`) VALUES ';
			}
			$sql .= "('" . implode("','", $mod) . "'),";
		}
	}
	$sql = rtrim($sql, ',');
	mysql_query($sql, $link);

	$modules = get_store_data('menu', 'WHERE module_id IN(' . implode(',', $modids) . ') AND father_id > 0');
	if($modules)
	{
		$sql = '';
		foreach ($modules AS $mod)
		{
			if (!$sql)
			{
				$sql = 'REPLACE INTO liv_menu (`' . implode('`,`', array_keys($mod)) . '`) VALUES ';
			}
			$sql .= "('" . implode("','", $mod) . "'),";
		}
	}
	$sql = rtrim($sql, ',');
	mysql_query($sql, $link);

	$modules = get_store_data('crontab', 'WHERE application_id IN(' . implode(',', $appids) . ')');
	if($modules)
	{
		$sql = '';
		foreach ($modules AS $mod)
		{
			if (!$gGlobalConfig['App_' . $mod['app_uniqueid']])
			{
				continue;
			}
			$mod['host'] = $gGlobalConfig['App_' . $mod['app_uniqueid']]['host'];
			$sdir = $mod['dir'];
			$mod['dir'] = $gGlobalConfig['App_' . $mod['app_uniqueid']]['dir'];
			if (strstr($sdir, 'admin/'))
			{
				$mod['dir'] .= 'admin/';
			}
			if (!$sql)
			{
				$sql = 'REPLACE INTO liv_crontab (`' . implode('`,`', array_keys($mod)) . '`) VALUES ';
			}
			$sql .= "('" . implode("','", $mod) . "'),";
		}
	}
	$sql = rtrim($sql, ',');
	mysql_query($sql, $link);
	hg_flushMsg('管控程序初始化完成');

	echo '  <div align="center">
		<input type="button" name="s" value=" 下一步 " style="width:100px;height:40px;color:#fff;font-size:16px;margin-top:20px;" onclick="document.location.href=\'index.php?action=install9\'" />
	  </div>';
	include('tpl/foot.tpl.php');
}

//创建管理员
if ($action == 'install9')
{
	include('tpl/head.tpl.php');
	include('tpl/admin.tpl.php');
	include('tpl/foot.tpl.php');
}
if ($action == 'install10')
{
	$user_name = trim($_REQUEST['user_name']);
	$password = trim($_REQUEST['password']);
	$confirmpassword = trim($_REQUEST['confirmpassword']);
	if (!$password || $confirmpassword != $password)
	{
		$message = '未设置密码或两次密码不一致';
		include('tpl/head.tpl.php');
		include('tpl/admin.tpl.php');
		include('tpl/foot.tpl.php');
		exit;
	}
	include('db/api.conf.php');
	include('db/appauth.php');
	include_once('curl.php');
	$auth = new curl($gGlobalConfig['App_auth']['host'], $gGlobalConfig['App_auth']['dir']);
	//$auth = new curl('localhost', 'publish/install/livapi/api/auth/');
	$auth->initPostData();
	$auth->addRequestData('a', 'create');
	$auth->addRequestData('password', $password);
	$auth->addRequestData('user_name', $user_name);
	$auth->addRequestData('admin_role_id', 1);
	$ret = $auth->request('admin/admin_update.php');
	if (!$ret[0]['id'])
	{
		$message = '管理员创建失败';
		include('tpl/head.tpl.php');
		include('tpl/admin.tpl.php');
		include('tpl/foot.tpl.php');
		exit;
	}
	include('db/livcmsdb.php');
	$link = @mysql_connect($db_config['servername'], $db_config['dbusername'], $db_config['dbpassword']);
	mysql_select_db($db_config['db']['cmsdbname'], $link);

	mysql_query("SET character_set_connection=".$db_config['dbcharset'].", character_set_results=".$db_config['dbcharset'].", character_set_client=binary", $link);
	$sql = "INSERT INTO liv_user (siteid,username,password,usergroupid,email,joindate,last_mod_pass)
				   VALUES ('1','" . addslashes(htmlspecialchars($user_name)) . "','" . md5($password) . "','1','','" . time() . "', " . time() . ")";
	mysql_query($sql, $link);
	header('Location:index.php?action=complete');
}

//安装完成
if ($action == 'complete')
{
	$serv = array();
	foreach ($servers AS $k => $v)
	{
		if ($v['type'] != 'app')
		{
			continue;
		}
		$serv[$v['type']][$k] = $v;
	}
	$app = @array_pop($serv['app']);
	$app = $app['app'];
	include('tpl/head.tpl.php');
	include('tpl/complete.tpl.php');
	include('tpl/foot.tpl.php');
}

if ($action == 'rebuild')
{
	$i = 0;
	foreach($servers AS $id => $v)
	{
		$i++;
		$v['id'] = $i;
		$servers[$i] = $v;
	}
	$servers = json_encode($servers);
	hg_file_write('db/server.' . $customer, $servers);
	hg_file_write('db/autoid', $i);
	header('Location:./index.php');
}


if ($action == 'test')
{
}

$gDomains = array();
$IMGAPIDOMAIN = $VODDOMAIN = '';
function hg_app_parse_domain($typ, $data)
{
	if (!$data)
	{
		return;
	}
	global $gDomains;
	$ip = $data['ip'];
	$info = $data[$typ];
	unset($data[$typ]);
	foreach ($info AS $k => $v)
	{
		if (!$v['domain'] || !$v['dir'])
		{
			continue;
		}
		$data['dir'] = $v['dir'];
		$gDomains[$ip][$v['domain']] = $data;
	}
}

function hg_api_parse_domain($typ, $data)
{
	if (!$data)
	{
		return;
	}
	global $gDomains;
	$ip = $data['ip'];
	$info = $data[$typ];
	unset($data[$typ]);
	if (!$info['domain'] || !$info['dir'])
	{
		continue;
	}
	$data['dir'] = $info['dir'] . 'api/';
	$gDomains[$ip][$info['domain']] = $data;
}
function hg_mediaserver_parse_domain($typ, $data)
{
	if (!$data)
	{
		return;
	}
	global $gDomains;
	$ip = $data['ip'];
	$info = $data[$typ];
	unset($data[$typ]);
	if (!$info['domain'] || !$info['dir'])
	{
		continue;
	}
	$data['dir'] = $info['dir'] . 'api/mediaserver/';
	$gDomains[$ip][$info['domain']] = $data;
}

function hg_default_parse_domain($typ, $data)
{
	if (!$data)
	{
		return;
	}
	global $gDomains, $VODDOMAIN;
	$ip = $data['ip'];
	$info = $data[$typ];
	unset($data[$typ]);
	if (!$info['domain'] || !$info['dir'])
	{
		continue;
	}
	if ($data['type'] == 'vodplay')
	{
		$VODDOMAIN = $info['domain'];
	}
	$data['dir'] = $info['dir'];
	$gDomains[$ip][$info['domain']] = $data;
}

function hg_img_parse_domain($typ, $data)
{
	if (!$data)
	{
		return;
	}
	global $gDomains, $IMGAPIDOMAIN;
	$ip = $data['ip'];
	$info = $data[$typ];
	unset($data[$typ]);
	if ($info['uploaddomain'] && $info['uploaddir'])
	{
		$data['dir'] = $info['uploaddir'];
		$gDomains[$ip][$info['uploaddomain']] = $data;
	}
	if ($info['domain'] && $info['dir'])
	{
		$data['dir'] = $info['dir'] . 'api/material/';
		$data['type'] = 'imgapi';
		$IMGAPIDOMAIN = $info['domain'];
		$gDomains[$ip][$info['domain']] = $data;
	}
}
?>