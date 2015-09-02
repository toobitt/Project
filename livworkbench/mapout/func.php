<?php
function hg_sendCmd($cmd, $ip, $port)
{
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if ($socket < 0) 
	{
		return false;
	}
	$result = socket_connect($socket, $ip, $port);
	if ($result < 0) 
	{
		return false;
	}
	if (!isset($cmd['charset']))
	{
		$cmd['charset'] = '';
	}
	$str = json_encode($cmd);
	//echo ($str);
	//$str = base64_encode($str);
	socket_write($socket, $str, strlen($str));
	$data = '';
	while ($out = socket_read($socket, 256))
	{
		$data .= $out;
		if (strlen($out) < 256)
		{
			break;
		}
	}
	socket_close($socket);
	//$data = base64_decode($data);
	return $data;
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
			socket_close($this->socket);
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
function hg_flushMsg($msg)
{
	echo $msg . str_repeat(' ', 4096). '<br /><script type="text/javascript">window.scrollTo(0,10000);</script>';
	ob_flush();
}

function hg_file_write($filename, $content, $mode = 'rb+')
{
	$length = strlen($content);
	@touch($filename);
	if (!is_writeable($filename))
	{
		@chmod($filename, 0666);
	}

	if (($fp = @fopen($filename, $mode)) === false)
	{
		trigger_error('hg_file_write() failed to open stream: Permission denied', E_USER_WARNING);
		
		return false;
	}

	flock($fp, LOCK_EX | LOCK_NB);

	$bytes = 0;
	if (($bytes = @fwrite($fp, $content)) === false)
	{
		$errormsg = sprintf('file_write() Failed to write %d bytes to %s', $length, $filename);
		trigger_error($errormsg, E_USER_WARNING);
		return false;
	}

	if ($mode == 'rb+')
	{
		@ftruncate($fp, $length);
	}

	@fclose($fp);

	// 检查是否写入了所有的数据
	if ($bytes != $length)
	{
		$errormsg = sprintf('file_write() Only %d of %d bytes written, possibly out of free disk space.', $bytes, $length);
		trigger_error($errormsg, E_USER_WARNING);
		return false;
	}

	// 返回长度
	return $bytes;
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

function hg_mk_hosts($servers, $ipf = 'ip' ,$config = array())
{
	if (!$servers)
	{
		return 0;
	}
	$domains = array();
	foreach ($servers AS $serv)
	{
		$domain = trim($serv['domain']);
		$ip = trim($serv[$ipf]);
		$mark = trim($serv['mark']);
		if (!$ip || $ip == $domain)
		{
			continue;
		}
		if ($mark && !@in_array($mark, $domains[$ip]) && !$config[$mark])
		{
			$domains[$ip][] = $mark;
		}
		if ($domain && !@in_array($domain, $domains[$ip]) && !$config[$domain])
		{
			$domains[$ip][] = $domain;
		}
		$type = trim($serv['type']);
		if (is_array($serv[$type]))
		{
			foreach ($serv[$type] AS $k => $v)
			{
				if ($k == 'domain')
				{
					if ($v && !@in_array($v, $domains[$ip]) && !$config[$v])
					{
						$domains[$ip][] = $v;
					}
					if ($type == 'live')
					{
						for($i=1; $i < 5; $i++)
						{
							$dm = $i . $v;
							if ($dm && !@in_array($dm, $domains[$ip]) && !$config[$dm])
							{
								$domains[$ip][] = $dm;
							}
						}
					}
				}
				if ($k == 'uploaddomain')
				{
					if ($v && !@in_array($v, $domains[$ip]) && !$config[$v])
					{
						$domains[$ip][] = $v;
					}
				}
				else if (is_array($v) && $v['domain'])
				{
					if (!@in_array($v['domain'], $domains[$ip]) && !$config[$v['domain']])
					{
						$domains[$ip][] = $v['domain'];
					}
				}
				else if (is_array($v) && $v['uploaddomain'])
				{
					if (!@in_array($v['uploaddomain'], $domains[$ip]) && !$config[$v['uploaddomain']])
					{
						$domains[$ip][] = $v['uploaddomain'];
					}
				}
			}
		}
	}
	return $domains;
}

function get_serv_file($server, $file)
{
	$socket = new hgSocket();
	$ip = trim($server['ip']);
	$port = intval($server['port']);
	$user = trim($server['user']);
	$pass = trim($server['pass']);
	if (!$ip || !$port || !$user || !$pass)
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
	$user = trim($server['user']);
	$pass = trim($server['pass']);
	if (!$ip || !$port || !$user || !$pass)
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
	$user = trim($server['user']);
	$pass = trim($server['pass']);
	if (!$ip || !$port || !$user || !$pass || !$content)
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

function hg_encript_str($str, $en = true)
{
	$salt = 'WssR$#QGsRT';
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

function hg_getDbStruct($link, $dbname)
{
	$sql = "SHOW TABLES FROM " . $dbname;
	$queryid = mysql_query($sql, $link);
	$tables = array();
	while($row = mysql_fetch_array($queryid))
	{
		$row['TABLE_NAME'] = $row['Tables_in_' . $dbname];
		$tables[] = $row['TABLE_NAME'];
	}
	$columns = array();
	foreach ($tables AS $table)
	{
		$sql = "SHOW FULL COLUMNS FROM {$table}";
		$queryid = mysql_query($sql, $link);
		while($row = mysql_fetch_array($queryid))
		{
			$columns[$table][$row['Field']] = $row;
		}
	}
	return $columns;
}

function hg_getServDbStruct($app)
{
	global $Cfg;
	if ($app == 'livmcp')
	{
		$dir = '';
		$filename = 'db.php';
	}
	else
	{
		$dir = 'source_code/' . $app . '/';
		$filename = 'configuare.php';
	}
	$host = $Cfg['upgradeServer']['host'] . ':' . $Cfg['upgradeServer']['port'];
	$curl = new curl($host, $dir);
	$curl->initPostData();
	$curl->addRequestData('a', 'getDbStruct');
	$struct = $curl->request($filename);
	return $struct;
}
function hg_updateDb($app, $link, $dbname)
{
	$struct = hg_getServDbStruct($app);
	$cur_struct = hg_getDbStruct($link, $dbname);
	if (!is_array($struct))
	{
		return ;
	}
	$ndbs = array_keys($struct);
	if (is_array($cur_struct))
	{
		$dbs = array_keys($cur_struct);
	}
	else
	{
		$dbs = array();
	}
	$add = array_diff($ndbs,$dbs);
	foreach ($add AS $table)
	{
		hg_flushMsg('新增数据表 ' . $dbname . '.' . $table);
		$sql = hg_createSql($app, $table);
		mysql_query($sql, $link);
	}

	foreach ($struct AS $table => $s)
	{
		if ($cur_struct[$table])
		{
			$fields = array_keys($s);
			$cur_fields = array_keys($cur_struct[$table]);
			$add = array_diff($fields,$cur_fields);
			if ($add)
			{
				$altersql = array();
				foreach ($add AS $f)
				{
					hg_flushMsg('数据表 ' . $table . '新增字段 ' . $f);
					$a = $s[$f];
					if ($a['Null'] == 'NO')
					{
						$null = ' NOT NULL';
					}
					else
					{
						$null = ' NULL';
					}
					if ($a['Default'])
					{
						$default = " DEFAULT '{$a['Default']}'";
					}
					else
					{
						$default = '';
					}
					if ($a['Comment'])
					{
						$comment = " COMMENT '{$a['Comment']}'";
					}
					else
					{
						$comment = '';
					}
					$altersql[] = " ADD `$f` {$a['Type']}{$null}{$default}{$comment}";
				}
				$altersql = 'ALTER TABLE ' . $table . ' ' . implode(',', $altersql);
				mysql_query($altersql, $link);
			}
		}
	}
	//$this->redirect('数据库更新完毕');
}

function hg_createSql($app, $table)
{
	global $Cfg;
	if ($app == 'livmcp')
	{
		$dir = '';
		$filename = 'db.php';
	}
	else
	{
		$dir = 'source_code/' . $app . '/';
		$filename = 'configuare.php';
	}

	$host = $Cfg['upgradeServer']['host'] . ':' . $Cfg['upgradeServer']['port'];
	$curl = new curl($host, $dir);
	$curl->setSubmitType('get');
	$curl->setReturnFormat('json');
	$curl->initPostData();
	$curl->addRequestData('a', 'createSql');
	$curl->addRequestData('table', $table);
	
	$sql = $curl->request($filename);
	return $sql;
	
}
function get_store_data($table, $condition = '', $dbname = '')
{
	global $Cfg;
	$curl = new curl($Cfg['upgradeServer']['host'] . ':' . $Cfg['upgradeServer']['port'], '', $Cfg['upgradeServer']['token']);
	$curl->setSubmitType('post');
	$curl->setReturnFormat('json');
	$curl->initPostData();
	$curl->addRequestData('dbname', $dbname);
	$curl->addRequestData('table', $table);
	$curl->addRequestData('condition', $condition);
	$data = $curl->request('initdata.php');
	return $data;
}
?>