<?php
require('./global.php');
$action = $_REQUEST['action'];
if ($action == 'getservertype')
{
	$type = trim($_REQUEST['type']);
	$servertype = $Cfg['servertype'][$type];
	$apps = $Cfg['serverapp'];
	
	$dbserver = array();
	if ($servertype['db'] && $servers)
	{
		foreach ($servers AS $id => $v)
		{
			if ($v['type'] == 'db')
			{
				$dbserver[$id] = $v;
			}
		}
	}
	$apps = $apps[$type];
	if (isset($_REQUEST['id']))
	{
		$id = trim($_REQUEST['id']);
		$server = $servers[$id];

		foreach ($server[$type] AS $k => $v)
		{
			if (is_array($v))
			{
				if (@in_array($k, $server['checked']))
				{
					$server[$type][$k]['checked'] = ' checked="checked"';
				}
				else
				{
					$server[$type][$k]['checked'] = '';
				}
			}
		}
		$$type = $server[$type];
	}
	else
	{
		if ($apps)
		{
			foreach ($apps AS $k => $v)
			{
				$apps[$k] = $v + $servertype;
			}
			if (count($apps) == 1)
			{
				$$type = array_shift($apps);
			}
			else
			{
				$$type = $apps;
			}
		}
		else
		{
			$$type = $servertype;
		}
	}
	ob_end_clean();
	ob_start();
	include('tpl/server_' . $type . '.tpl.php');
	if ($servertype['db'] == 1)
	{
		include('tpl/sdb.tpl.php');
	}
	$html = ob_get_contents();
	ob_end_clean();
	$html = str_replace(array("\r\n", "\r", "\n"), '', $html);
	$data = array(
		'servtyp' => $server['servtyp'] ? $server['servtyp'] : $servertype['servtyp'],
		'html' => $html	
	);
	echo json_encode($data);
}

if ($action == 'check_server_connect')
{
	$ip = trim($_REQUEST['ip']);
	$port = intval($_REQUEST['port']);	
	if (!$ip || !$port)
	{
		$data = array(
			'connected' => 0,	
			'msg' => 'no data'	
		);
		echo json_encode($data);
		exit;
	}
	$socket = new hgSocket();
	$con = $socket->connect($ip, $port);
	$data = array(
		'connected' => intval($con),
	);
	echo json_encode($data);
}

if ($action == 'check_server_pass')
{
	$socket = new hgSocket();
	$ip = trim($_REQUEST['ip']);
	$port = intval($_REQUEST['port']);
	$user = trim($_REQUEST['user']);
	$pass = trim($_REQUEST['pass']);
	if (!$ip || !$port || !$user || !$pass)
	{
		$data = array(
			'match' => 0
		);
		echo json_encode($data);
		exit;
	}
	$con = $socket->connect($ip, $port);
	$cmd = array(
		'action' => 'get.pid',
		'user' => $user,
		'pass' => $pass,
	);
	$socket->sendCmd($cmd);
	$result = $socket->readall();
	if (hg_chk_result($result) < 0)
	{
		$result = 0;
	}
	$data = array(
		'match' => $result,
	);
	echo json_encode($data);
}

if ($action == 'mkdir')
{
	$socket = new hgSocket();
	$ip = trim($_REQUEST['ip']);
	$port = intval($_REQUEST['port']);
	$user = trim($_REQUEST['user']);
	$pass = trim($_REQUEST['pass']);
	$para = trim($_REQUEST['para']);
	$para = trim($para, '/');
	$fdir = trim($_REQUEST['fdir']);
	$objid = trim($_REQUEST['objid']);
	if (!$ip || !$port || !$user || !$pass)
	{
		$data = array(
			'objid' => $objid,
			'dir' => $fdir,
		);
		echo json_encode($data);
		exit;
	}
	$con = $socket->connect($ip, $port);
	$cmd = array(
		'action' => 'mkdirs',
		'para' => $fdir . $para . '/',
		'user' => $user,
		'pass' => $pass,
	);
	$socket->sendCmd($cmd);
	$result = $socket->readall();
	if (hg_chk_result($result) > 0)
	{
		$data = array(
			'objid' => $objid,
			'dir' => $fdir . $para . '/',
		);
	}
	else
	{
		$data = array(
			'objid' => $objid,
			'dir' => $fdir,
		);
	}
	echo json_encode($data);
	exit;
}

if ($action == 'ls')
{
	$socket = new hgSocket();
	$ip = trim($_REQUEST['ip']);
	$port = intval($_REQUEST['port']);
	$user = trim($_REQUEST['user']);
	$pass = trim($_REQUEST['pass']);
	$para = trim($_REQUEST['para']);
	$objid = trim($_REQUEST['objid']);
	if (!$ip || !$port || !$user || !$pass)
	{
		$data = array(
			'ls' => ''
		);
		echo json_encode($data);
		exit;
	}
	$con = $socket->connect($ip, $port);
	$cmd = array(
		'action' => 'ls',
		'para' => $para,
		'user' => $user,
		'pass' => $pass,
	);
	$tmp = explode('/', $para);
	$c = count($tmp) - 1;
	$back = 0;
	$dir = array();
	for($i = $c; $i >= 0; $i--)
	{
		if ($tmp[$i] == '..')
		{
			$back++;
		}
		else
		{
			if ($back == 0)
			{
				$dir[] = $tmp[$i];
			}
			else
			{
				$back--;
			}
		}
	}
	krsort($dir);
	$para = implode('/', $dir);
	$para = $para ? $para : '/';
	$socket->sendCmd($cmd);
	$result = $socket->readall();
	if (hg_chk_result($result) < 0)
	{
		$data = array(
			'para' => $para,
			'objid' => $objid,
			'html' => ''
		);
		echo json_encode($data);
		exit;
	}
	$result = str_replace(array("\r\n", "\r"), "\n", $result);
	$dirs = explode("\n", $result);

	ob_end_clean();
	ob_start();
	include('tpl/dir.tpl.php');
	$html = ob_get_contents();
	ob_end_clean();
	$html = str_replace(array("\r\n", "\r", "\n"), '', $html);
	$data = array(
		'para' => $para,
		'objid' => $objid,
		'html' => $html	
	);
	echo json_encode($data);
}
if ($action == 'showdb')
{
	$action();
}
if ($action == 'createdb')
{
	$action();
}
function showdb()
{
	global $servers;
	$serv = array();
	foreach ($servers AS $k => $v)
	{
		if ($v['type'] != 'db')
		{
			continue;
		}
		$serv[$k] = $v;
	}
	$dbid = $_REQUEST['db'];
	$objid = $_REQUEST['objid'];
	$dbsel = $_REQUEST['dbsel'];
	$db = $serv[$dbid]['db'];
	if (!$db)
	{
		$ret = array(
			'error' => '未选择数据库',
			'version' => $version,
			'dbs' => $databases,
			'dbid' => $dbid,
			'objid' => $objid,
			'dbsel' => $dbsel,
		);
		echo json_encode($ret);
		exit;
	}
	$sql = 'SHOW DATABASES';
	$link = @mysql_connect($db['host'], $db['user'], $db['pass']);
	if (!$link)
	{
		$ret = array(
			'error' => $db['host'] . '数据库无法连接',
			'version' => $version,
			'dbs' => $databases,
			'dbid' => $dbid,
			'objid' => $objid,
			'dbsel' => $dbsel,
		);
		echo json_encode($ret);
		exit;
	}
	$q = mysql_query($sql, $link);
	$databases = array();
	while($row = mysql_fetch_array($q))
	{
		if (in_array($row['Database'], array('performance_schema','information_schema', 'mysql')))
		{
			continue;
		}
		$databases[] = $row['Database'];
	}
	
	$version = mysql_get_server_info($link);
	$ret = array(
		'error' => '',
		'version' => $version,
		'dbs' => $databases,
		'objid' => $objid,
		'dbid' => $dbid,
		'dbsel' => $dbsel,
	);
	echo json_encode($ret);
}

function createdb()
{
	global $servers;
	$serv = array();
	foreach ($servers AS $k => $v)
	{
		if ($v['type'] != 'db')
		{
			continue;
		}
		$serv[$k] = $v;
	}
	$dbid = $_REQUEST['db'];
	$objid = $_REQUEST['objid'];
	$dbsel = $_REQUEST['dbsel'];
	$dbname = trim($_REQUEST['dbname']);
	$db = $serv[$dbid]['db'];
	if (!$dbname)
	{
		$ret = array(
			'error' => '请填写数据库名',
			'version' => $version,
			'dbs' => $databases,
			'dbid' => $dbid,
			'objid' => $objid,
			'dbsel' => $dbsel,
		);
		echo json_encode($ret);
		exit;
	}
	if (!$db)
	{
		$ret = array(
			'error' => '未选择数据库',
			'version' => $version,
			'dbs' => $databases,
			'dbid' => $dbid,
			'objid' => $objid,
			'dbsel' => $dbsel,
		);
		echo json_encode($ret);
		exit;
	}
	$sql = 'SHOW DATABASES';
	$link = @mysql_connect($db['host'], $db['user'], $db['pass']);
	if (!$link)
	{
		$ret = array(
			'error' => $db['host'] . '数据库无法连接',
			'version' => $version,
			'dbs' => $databases,
			'dbid' => $dbid,
			'objid' => $objid,
			'dbsel' => $dbsel,
		);
		echo json_encode($ret);
		exit;
	}
	$q = mysql_query($sql, $link);
	$databases = array();
	$alldatabases = array();
	while($row = mysql_fetch_array($q))
	{
		$alldatabases[] = $row['Database'];
		if (in_array($row['Database'], array('performance_schema','information_schema', 'mysql')))
		{
			continue;
		}
		$databases[] = $row['Database'];
	}
	if (in_array($dbname, $alldatabases))
	{	
		$ret = array(
			'error' => '数据库已存在',
			'version' => $version,
			'dbs' => $databases,
			'objid' => $objid,
			'dbid' => $dbid,
			'dbsel' => $dbsel,
		);
		echo json_encode($ret);
		return;
	}
	$sql = 'CREATE DATABASE ' . $dbname . ' DEFAULT CHARACTER SET UTF8';
	$q = mysql_query($sql, $link);	
	if (!$q)
	{	
		$ret = array(
			'error' => '数据库创建失败',
			'version' => $version,
			'dbs' => $databases,
			'objid' => $objid,
			'dbid' => $dbid,
			'dbsel' => $dbsel,
		);
		echo json_encode($ret);
		return;
	}
	$databases[] = $dbname;
	$version = mysql_get_server_info($link);
	$ret = array(
		'error' => '',
		'version' => $version,
		'dbs' => $databases,
		'objid' => $objid,
		'dbid' => $dbid,
		'dbsel' => $dbsel,
	);
	echo json_encode($ret);
}
?>