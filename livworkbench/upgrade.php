<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2454 2013-03-26 08:03:23Z develop_tong $
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'appstore');
require('./global.php');
require (ROOT_PATH . 'lib/class/curl.class.php');
class appstore extends uiBaseFrm
{	
	private $appstore;
	private $dbconfig;
	private $product_server;
	function __construct()
	{
		parent::__construct();		
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->ReportError('对不起，您没有权限进行升级');
		}
		if (DEVELOP_MODE)
		{
			$this->ReportError('对不起，开发模式不允许更新');
		}
		global $gDBconfig;
		$this->product_server = array(
			'host' => 'upgrade.hogesoft.com',
			'port' => 233,
			'dir' => '',
		);
		$this->dbconfig = $gDBconfig;
		
		$this->appstore = new curl('appstore.hogesoft.com:233', '');
		$this->appstore->mAutoInput = false;
		$this->appstore->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$this->appstore->initPostData();
		$this->appstore->addRequestData('a', 'get_installed_version');
		$this->appstore->addRequestData('app', 'livworkbench');
		$app = $this->appstore->request('index.php');
		$app = $app[0];

		if ($app['version'])
		{
			$this->settings['version'] = min($this->settings['version'], $app['version']);
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show($message = '')
	{				
		$curl = new curl($this->product_server['host'] . ':' . $this->product_server['port'], $this->product_server['dir']);
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->initPostData();
		$postdata = array(
			'app'		=>	'livworkbench',
			'a'				=>	'checklastversion',
		);
		foreach ($postdata as $k=>$v)
		{
			$curl->addRequestData($k, $v);
		}
		$version = $curl->request('check_version.php');
		if ($this->settings['version'] >= $version)
		{
			$this->ReportError('当前已是最新版本');
		}
		$installinfo = array();
		$installinfo['host'] = $this->settings['mcphost'];
		$this->dbconfig['dbprefix'] = DB_PREFIX;
		$installinfo['version'] = $this->settings['version'];
		$installinfo['dir'] = realpath(ROOT_PATH) . '/';
		$this->tpl->addVar('version', $version);
		$this->tpl->addVar('message', $message);
		$this->tpl->addVar('installinfo', $installinfo);
		$this->tpl->addVar('dbinfo', $this->dbconfig);
		$this->tpl->outTemplate('upgrade');
	}

	public function doupgrade()
	{
		$installinfo = array(
			'host' => $this->settings['mcphost'],	
			'ip' => $this->settings['mcphost'],	
			'port' => 6233,	
		);
		if (!is_writeable('conf/config.php'))
		{
			hg_run_cmd( $installinfo, 'runcmd', 'chmod -Rf 777 ' .  realpath('conf/config.php'));
		}
		if (!is_writeable('conf/config.php'))
		{
			$message = '平台无法升级，请将此文件（conf/config.php）设为可写';
			$this->upgrade($message);
		}
		$curl = new curl($this->product_server['host'] . ':' . $this->product_server['port'], $this->product_server['dir']);
		$curl->mAutoInput = false;
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->initPostData();
		$postdata = array(
			'app'		=>	'livworkbench',
			'a'				=>	'checklastversion',
		);
		foreach ($postdata as $k=>$v)
		{
			$curl->addRequestData($k, $v);
		}
		$lastversion = $curl->request('check_version.php');
		if ($this->settings['version'] >= $lastversion)
		{
			$this->ReportError('当前已是最新版本');
		}
		$db = $this->dbconfig;
		$db['user'] = $this->input['dbuser'];
		$db['pass'] = $_REQUEST['dbpass'];
		$link = @mysql_connect($db['host'], $db['user'], $db['pass']);
		if (!$link)
		{
			$message = '此数据库无法连接，请确认密码是否准确';
			$this->show($message);
		}
		mysql_query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary", $link);
		$socket = new hgSocket();
		$con = $socket->connect($installinfo['host'], $installinfo['port']);
		if (!intval($con))
		{
			$message = '服务器无法连接，请确认服务器ip是否正确或服务程序hogeMonitor.py是否监听在'. $installinfo['ip'] . ':' . $installinfo['port'] . '上';
			$this->show($message);
		}
		$socket->close();
		$app_path = realpath(ROOT_PATH);
		$app_path .= '/';
		$curl = new curl($this->product_server['host'] . ':' . $this->product_server['port'], '');
		$curl->mAutoInput = false;
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->setSubmitType('get');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('app', 'livworkbench');
		$ret = $curl->request('db.php');
		
		ob_start();
		if (is_array($ret))
		{
			//更新数据库
			hg_flushMsg('开始更新数据库');
			mysql_select_db($db['database'], $link);
			$structs = $this->getDbStruct($db['database'], $link);
			if (!$ret['app'])
			{
				$ret['app'] = array();
			}
			foreach ($ret['app'] AS $tab => $v)
			{
				$pre = substr($tab, 0, 4);
				if ($pre == 'liv_')
				{
					$newtab = DB_PREFIX . substr($tab, 4);
				}
				if ($pre == 'm2o_')
				{
					$newtab = DB_PREFIX. substr($tab, 4);
				}
				if (!$structs[$newtab])
				{
					$addsql = $ret['create'][$tab];
					if ($addsql)
					{
						$addsql = preg_replace('/CREATE\s+TABLE\s+([`]{0,1})' . $pre . '/is', 'CREATE TABLE \\1' . DB_PREFIX, $addsql);
						hg_flushMsg('新增数据表' . $newtab);
						mysql_query($addsql, $link);
					}
					continue;
				}
				$struct = $v['struct'];
				$index = $v['index'];
				if ($struct)
				{
					$altersql = array();
					foreach ($struct AS $f => $a)
					{
						if (!$structs[$newtab]['struct'][$f])
						{
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
						else
						{
							$cur = $structs[$newtab]['struct'][$f];
							
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
							if ($a['Type'] != $cur['Type'] || $a['Default'] != $cur['Default'])
							{
								$altersql[] = " CHANGE `$f` `$f` {$a['Type']}{$null}{$default}{$comment}";
							}
						}
					}
					if ($altersql)
					{
						hg_flushMsg('开始更新数据表' . $newtab);
						$altersql = 'ALTER TABLE ' . $newtab . ' ' . implode(',', $altersql);
						mysql_query($altersql, $link);
					}
				}
				if ($index)
				{
					foreach ($index AS $unique => $ind)
					{
						if (!$ind)
						{
							continue;
						}
						
						if (!$unique)
						{
							$typ = 'UNIQUE';
						}
						else
						{
							$typ = 'INDEX';
						}
						foreach ($ind AS $pk => $f)
						{
							if ($pk == 'PRIMARY')
							{
								continue;
							}
							$curind = $structs[$newtab]['index'][$unique][$pk];
							if (!$curind)
							{
								$altersql = 'ALTER TABLE  ' . $newtab . ' ADD ' . $typ . ' (' . implode(',', $f) . ')';
								
//									echo $altersql . '<br />';
								mysql_query($altersql, $link);
							}
							else
							{
								$change = array_diff($curind, $f);
								$change1 = array_diff($f, $curind);
								if($change || $change1)
								{
									$altersql = 'ALTER TABLE  ' . $newtab . ' DROP INDEX ' . $pk . ', ADD ' . $typ . ' (' . implode(',', $f) . ')';
	//								echo $altersql . '<br />';
									mysql_query($altersql, $link);
								}
							}
						}
					}
				}
				$newindex = $index;
				$index = $structs[$newtab]['index'];
				if ($index)
				{
					foreach ($index AS $unique => $ind)
					{
						if (!$ind)
						{
							continue;
						}
						
						if (!$unique)
						{
							$typ = 'UNIQUE';
						}
						else
						{
							$typ = 'INDEX';
						}
						foreach ($ind AS $pk => $f)
						{
							if ($pk == 'PRIMARY')
							{
								continue;
							}
							$newind = $newindex[$unique][$pk];
							if (!$curind)
							{
								$altersql = 'ALTER TABLE  ' . $newtab . ' DROP INDEX ' . $pk;
								mysql_query($altersql, $link);
							}
						}
					}
				}
				$sql = 'OPTIMIZE TABLE  ' . $newtab;
				mysql_query($sql, $link);
			}
			hg_flushMsg('数据库更新完毕');
		}
		//下载程序
		
		hg_flushMsg('开始下载应用程序更新包');
		$curl = new curl($this->product_server['host'] . ':' . $this->product_server['port'], '');
		$curl->mAutoInput = false;
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->setSubmitType('get');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('app', 'livworkbench');
		$program_url = $curl->request('check_version.php');
		if (!(strstr($program_url, 'http://') && strstr($program_url, '.zip')) || $program_url == 'NO_VERSION')
		{
			$message = '获取应用程序失败或程序版本不存在.';
			$this->show($message);
		}		
		hg_run_cmd( $installinfo, 'download', $program_url, $app_path);
		$appversion = @file_get_contents($app_path . 'version');
		$match = preg_match('/^[0-9]+\.[0-9]+\.[0-9]+$/is', $appversion);
		if (!$match)
		{
			$appversion = '';
		}
		if ($appversion < $lastversion)
		{
			$message = '平台程序更新失败，请重试.';
			$this->upgrade($message);
		}
		hg_run_cmd( $installinfo, 'runcmd', 'chmod +x ' .  $app_path . 'cron/*.py');
		$domain = $installinfo['host'];
		$dir = $installinfo['dir'];

		hg_flushMsg('应用程序包下载完成');

		hg_flushMsg('应用设置更新完成');

		hg_flushMsg('开始更新模板');
		
		hg_flushMsg('模板更新完成');
		
		$content = @file_get_contents('conf/config.php');
		$match = preg_match("/\\\$gGlobalConfig\['version'\]\s*=\s*\'.*\';/is", $content);
		if($match)
		{
			$content = preg_replace("/\\\$gGlobalConfig\['version'\]\s*=\s*\'.*?\';/is", "\$gGlobalConfig['version'] = '{$lastversion}';", $content);
		}
		else
		{
			$content = preg_replace("/\?>/is", "\n\$gGlobalConfig['version'] = '{$lastversion}';\n?>", $content);
		}
		@file_put_contents('conf/config.php', $content);

		$sql = 'SELECT * FROM ' . DB_PREFIX . 'modules';
		$q = $this->db->query($sql);
		$program = array();
		while ($mod = $this->db->fetch_array($q))
		{
			$program[$mod['id']][$mod['func_name']] = $mod['func_name'];
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'module_op';
		$q = $this->db->query($sql);
		while ($mod = $this->db->fetch_array($q))
		{
			$program[$mod['module_id']][$mod['op']] = $mod['op'];
		}
		if ($program)
		{
			hg_flushMsg('开始重建应用程序');
			include(ROOT_PATH . 'lib/class/program.class.php');
			$rebuildprogram = new program();
			foreach ($program AS $mid => $ops)
			{
				if ($ops)
				{
					foreach ($ops AS $op)
					{
						$rebuildprogram->compile($mid, $op);
					}
				}
			}
			hg_flushMsg('应用程序重建完成');
		}


		//清理模板
		$this->clearfile(CACHE_DIR . 'tpl/livworkbench/');
		$this->clearfile(CACHE_DIR . 'tpl/css/livworkbench/');
		
		$update_apps = @file_get_contents(CACHE_DIR . 'onekupdate');
		$update_apps = json_decode($update_apps, 1);
		$tdb = $update_apps['okupdatedbinfo'];
		unset($update_apps['okupdatedbinfo']);
		if ($update_apps)
		{
			unset($update_apps['livworkbench']);
			if ($update_apps)
			{
				$update_apps['okupdatedbinfo'] = $tdb;
				$onekupdate = json_encode($update_apps);
			}
			else
			{
				$onekupdate = '';
			}
			file_put_contents(CACHE_DIR . 'onekupdate', $onekupdate);
		}
		if ($this->input['onekupdate'])
		{
			$url = 'appstore.php?a=goonekupdate';
		}
		else
		{
			$url = 'index.php';
		}
		$this->appstore->initPostData();
		$this->appstore->addRequestData('a', 'updated');
		$this->appstore->addRequestData('app', 'livworkbench');
		$this->appstore->request('index.php');
		hg_flushMsg('平台更新成功', $url);
		//$this->redirect('应用更新成功', $url);
	}

	private function clearfile($dir)
	{
		if(file_exists($dir))
		{
			$open = opendir($dir);
			while(($file = readdir($open)) !== false)
			{
				if($file != '.' && $file != '..')
				{			
					$pdir = $dir . $file;
					if(is_dir($pdir))//判断是否 目录
					{
						$this->clearfile($pdir . '/');
					}
					else
					{
						unlink($pdir);
					}
				}
			}
		}
	}

	private function getDbStruct($dbname, $link)
	{
		if (!$dbname)
		{
			return array();
		}
		$tables = $this->getTables($dbname, $link);
		$struct = array();
		$indexs = array();
		foreach ($tables AS $table)
		{
			$sql = "SHOW FULL COLUMNS FROM {$table}";
			$queryid = mysql_query($sql, $link);
			while($row = mysql_fetch_array($queryid))
			{
				$struct[$table]['struct'][$row['Field']] = $row;
				if ($row['Key'])
				{
					$indexs[] = $row['Field'];
				}
			}
			$struct[$table]['index'] = $this->getIndexs($dbname, $table, $link);
		}

		return $struct;
	}
	private function getIndexs($dbname, $table = 'test', $link = '')
	{
		$sql = "SELECT DISTINCT * 
			FROM information_schema.statistics
			WHERE table_schema =  '{$dbname}'
			AND table_name =  '{$table}'";		
		$queryid = mysql_query($sql, $link);
		$indexs = array();
		while($row = mysql_fetch_array($queryid))
		{
			$indexs[$row['NON_UNIQUE']][$row['INDEX_NAME']][$row['SEQ_IN_INDEX'] - 1] = $row['COLUMN_NAME'];
		}
		return $indexs;
	}

	private function getTables($dbname, $link)
	{
		if (!$dbname)
		{
			return array();
		}
		$sql = "SHOW TABLES FROM " . $dbname;
		$queryid = mysql_query($sql, $link);
		$tables = array();
		while($row = mysql_fetch_array($queryid))
		{
			$row['TABLE_NAME'] = $row['Tables_in_' . $dbname];
			$tables[] = $row['TABLE_NAME'];
		}
		return $tables;
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>