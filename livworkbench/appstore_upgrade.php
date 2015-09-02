<?php
if (!defined('SCRIPT_NAME'))
{
	exit;
}
		if (DEVELOP_MODE)
		{
			$this->ReportError('对不起，开发模式不允许更新');
		}
		if ($this->input['app'])
		{
			$this->appstore->initPostData();
			$this->appstore->addRequestData('a', 'detail');
			$this->appstore->addRequestData('app', $this->input['app']);
			$appinfo = $this->appstore->request('index.php');
			$appinfo = $appinfo[0];
			if (!$appinfo)
			{
				$this->upgrade('指定应用不存在或被删除，无法更新');
			}
			$this->input['app'] = $appinfo['app_uniqueid'];
			if ($appinfo['status'] == 0)
			{
				$this->upgrade('应用' . $appinfo['name'] . '尚未安装');
			}
		}

		if (!$appinfo)
		{
			$this->upgrade('指定应用不存在或被删除，无法更新');
		}
		$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$ret = $curl->request('applications.php');
		$installinfo = array();
		if (is_array($ret))
		{
			foreach ($ret AS $v)
			{
				if ($v['bundle'] == $this->input['app'])
				{
					$installinfo = $v;
					break;
				}
			}
		}
		if (!$installinfo)
		{
			$installinfo = $this->settings['App_' . $this->input['app']];
		}
		if (!$installinfo)
		{
			$this->upgrade('应用安装信息丢失，请联系软件提供商');
		}
		$installinfo['dir'] = str_replace($installinfo['admin_dir'], '', $installinfo['dir']);
		$installinfo['ip'] = $installinfo['host'];
		$installinfo['port'] = 6233;
		$curl =  new curl($installinfo['host'], $installinfo['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'settings');
		$ret = $curl->request('configuare.php');
		$user_configs = array(
			'base' => $ret['base'],
			'define' => $ret['define'],
		);
		$db = $ret['db'];
		$DB_PREFIX = $ret['define']['DB_PREFIX'];
		$db['user'] = $this->input['dbuser'];
		$db['pass'] = $_REQUEST['dbpass'];

		if (!$appinfo['nodb'])
		{
			$link = @mysql_connect($db['host'], $db['user'], $db['pass']);
			if (!$link)
			{
				$message = '此数据库无法连接，请确认密码是否准确';
				$this->upgrade($message);
			}	
			mysql_query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary", $link);
		}
		$socket = new hgSocket();
		$con = $socket->connect($installinfo['host'], $installinfo['port']);
		if (!intval($con))
		{
			$message = '服务器无法连接，请确认服务器ip是否正确或服务程序hogeMonitor.py是否监听在'. $installinfo['ip'] . ':' . $installinfo['port'] . '上';
			$socket->close();
			$this->upgrade($message);
		}
		$socket->close();

		$curl->initPostData();
		$curl->addRequestData('a', 'getapp_path');
		$app_path = $curl->request('configuare.php');
		$app_path .= '/';
		$install_app = trim($this->input['app']);
		$curl = new curl($this->product_server['host'] . ':' . $this->product_server['port'], '');
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->setSubmitType('get');
		$curl->setReturnFormat('json');

		$curl->initPostData();
		$curl->addRequestData('app', $install_app);
		$ret = $curl->request('db.php');
		$appdb = $ret;
		if ($appinfo['nodb'])
		{
			$appdb['app'] = array();
		}
		
		$ret = $appdb;
		
		ob_start();
		if (is_array($ret))
		{
			$m2odata = $ret['m2o'];
			if ($ret['app'])
			{
				//更新数据库
				hg_flushMsg('开始更新数据库');
				mysql_select_db($db['database'], $link);
				$structs = $this->getDbStruct($db['database'], $link);
				foreach ($ret['app'] AS $tab => $v)
				{
					$pre = substr($tab, 0, 4);
					if ($pre == 'liv_')
					{
						$newtab = $DB_PREFIX . substr($tab, 4);
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
							$addsql = preg_replace('/CREATE\s+TABLE\s+([`]{0,1})' . $pre . '/is', 'CREATE TABLE \\1' . $DB_PREFIX, $addsql);
							hg_flushMsg('新增数据表' . $newtab);
							mysql_query($addsql, $link);
						}
						continue;
					}
					$struct = $v['struct'];
					$index = $v['index'];			
					$upgdir = CACHE_DIR . 'upgrade/' . date('Ymd') . '/';
					if (!is_dir($upgdir))
					{
						mkdir($upgdir, 0777, true);
					}
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
								$altersql[] = " ADD `$f` {$a['Type']}{$null} {$a['Extra']}{$default}{$comment}";
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
									$altersql[] = " CHANGE `$f` `$f` {$a['Type']}{$null} {$a['Extra']}{$default}{$comment}";
								}
							}
						}
						
						if ($altersql)
						{
							hg_flushMsg('开始更新数据表' . $newtab);
							$countsql = 'SELECT count(*) AS cnt FROM ' . $newtab;
							$q = mysql_query($countsql, $link);
							$count = mysql_fetch_array($q);
							$querycount = 1;
							$altersql = 'ALTER TABLE ' . $newtab . ' ' . implode(',', $altersql);
							if ($count['cnt'] > 200000)//数据大于20W，记录索引更新语句
							{
								$message = '-- 应用数据库结构更新语句， 更新前请核对';
								file_put_contents($upgdir . $install_app . '.sql', $altersql . "\n", FILE_APPEND);
								
								hg_flushMsg('<div style="color:red;font-weight:bold;font-size:14px;">数据表结构更新语句已记录，<a href="cache/upgrade/' . date('Ymd') . '/'. $install_app . 'sql" target="_blank">请查看</a></div>');
							}
							else
							{
								mysql_query($altersql, $link);
							}
						}
					}
					if ($index)
					{
						if (!$querycount)
						{
							$countsql = 'SELECT count(*) AS cnt FROM ' . $newtab;
							$q = mysql_query($countsql, $link);
							$count = mysql_fetch_array($q);
						}
						
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

									if ($count['cnt'] > 200000)//数据大于20W，记录索引更新语句
									{
										$message = '-- 应用数据库索引更新语句， 更新前请核对';
										file_put_contents($upgdir . $install_app . '.sql', $altersql . "\n", FILE_APPEND);
										
										hg_flushMsg('<div style="color:red;font-weight:bold;font-size:14px;">数据表索引更新语句已记录，<a href="cache/upgrade/' . date('Ymd') . '/'. $install_app . 'sql" target="_blank">请查看</a></div>');
									}
									else
									{
										mysql_query($altersql, $link);
									}
								}
								else
								{
									$change = array_diff($curind, $f);
									$change1 = array_diff($f, $curind);
									if($change || $change1)
									{
										$altersql = 'ALTER TABLE  ' . $newtab . ' DROP INDEX ' . $pk . ', ADD ' . $typ . ' (' . implode(',', $f) . ')';
		//								echo $altersql . '<br />';

										if ($count['cnt'] > 200000)//数据大于20W，记录索引更新语句
										{
											$message = '-- 应用数据库索引更新语句， 更新前请核对';
											file_put_contents($upgdir . $install_app . '.sql', $altersql . "\n", FILE_APPEND);
											
											hg_flushMsg('<div style="color:red;font-weight:bold;font-size:14px;">数据表索引更新语句已记录，<a href="cache/upgrade/' . date('Ymd') . '/'. $install_app . 'sql" target="_blank">请查看</a></div>');
										}
										else
										{
											mysql_query($altersql, $link);
										}
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

									$count = mysql_fetch_array($q);
									if ($count['cnt'] > 200000)//数据大于20W，记录索引更新语句
									{
										$message = '-- 应用数据库索引更新语句， 更新前请核对';
										file_put_contents($upgdir . $install_app . '.sql', $altersql . "\n", FILE_APPEND);
										
										hg_flushMsg('<div style="color:red;font-weight:bold;font-size:14px;">数据表索引更新语句已记录，<a href="cache/upgrade/' . date('Ymd') . '/'. $install_app . 'sql" target="_blank">请查看</a></div>');
									}
									else
									{
										mysql_query($altersql, $link);
									}
								}
							}
						}
					}
					$sql = 'OPTIMIZE TABLE  ' . $newtab;
					if ($count['cnt'] > 200000)//数据大于20W，记录索引更新语句
					{
						$message = '-- 应用数据库索引更新语句， 更新前请核对';
						file_put_contents($upgdir . $install_app . '.sql', $sql . "\n", FILE_APPEND);
						
						hg_flushMsg('<div style="color:red;font-weight:bold;font-size:14px;">数据表索引更新语句已记录，<a href="cache/upgrade/' . date('Ymd') . '/'. $install_app . 'sql" target="_blank">请查看</a></div>');
					}
					else
					{
						mysql_query($sql, $link);
					}
				}
			}
		}
		hg_flushMsg('数据库更新完毕');
		//下载程序
		
		hg_flushMsg('开始下载应用程序更新包');
		$curl = new curl($this->product_server['host'] . ':' . $this->product_server['port'], '');
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->setSubmitType('get');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('app', $install_app);
		$program_url = $curl->request('check_version.php');
		$app_path = str_replace('/api/' . $install_app . '/', '/', $app_path);
		if (!(strstr($program_url, 'http://') && strstr($program_url, '.zip')) || $program_url == 'NO_VERSION')
		{
			$message = '获取应用程序失败或程序版本不存在.';
			$this->upgrade($message);
		}
		if(!$this->input['haveapi'])
        {
            hg_run_cmd( $installinfo, 'download', $program_url, $app_path);
        }
		$domain = $installinfo['host'];
		$dir = $installinfo['dir'];
		if ($this->settings['localapp'])
		{
			$url = 'http://' . $this->settings['mcphost'] . '/' . $dir . 'version';
			$ch = curl_init($url);
			curl_setopt($ch,CURLOPT_HTTPHEADER,array("Host: {$domain}")); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$apiversion = curl_exec($ch);
			curl_close($ch);
		}
		else
		{
			$apiversion = @file_get_contents('http://' . $domain . '/' . $dir . 'version');
		}
		$match = preg_match('/^[0-9]+\.[0-9]+\.[0-9]+$/is', $apiversion);
		if (!$match)
		{
			$apiversion = '';
		}
		if ($apiversion < $appinfo['version'])
		{
			$message = '接口程序更新失败，请重试.<!-- ' . $program_url . ' -->';
			$this->upgrade($message);
		}
		$curl->initPostData();
		$curl->addRequestData('js', 1);
		$curl->addRequestData('app', $install_app);
		$program_url = $curl->request('check_version.php');
		$m2oserv = array(
			'ip' => $this->settings['mcphost'],	
			'port' => 6233
		);
		$m2oscriptdir = realpath('./') . '/res/scripts/app_' . $install_app . '/';
		if(!$this->input['havemat'])
        {
			hg_run_cmd( $m2oserv, 'download', $program_url, $m2oscriptdir);
        }
		$scriptversion = @file_get_contents($m2oscriptdir . 'version');
	    $match = preg_match('/^[0-9]+\.[0-9]+\.[0-9]+$/is', $scriptversion);
		if (!$match)
		{
			$scriptversion = '';
		}
		if ($scriptversion < $appinfo['version'])
		{
			$message = '接口图片和js等资源程序更新失败，请重试.<!-- ' . $program_url . ' -->';
			$this->upgrade($message);
		}
		hg_flushMsg('应用程序包下载完成');
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
			hg_flushMsg('更新应用设置');
			$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
			$application_id = 0;
			foreach ($m2odata AS $table => $data)
			{
				$sql = '';
				
				if ($table == 'applications')
				{
					$application_id = $data['id'];
					$data['name'] = $appinfo['name'];
					$data['host'] = $domain;
					$data['softvar'] = $install_app;
					$data['version'] = $appinfo['version'];
					$applications = $data;
					if ($appinfo['api_uniqueid'])
					{
						$data['dir'] = $appinfo['api_uniqueid'] . '/admin/';
						$applications['dir'] = $appinfo['api_uniqueid'] . '/';
					}
					else
					{
						$data['dir'] = $install_app . '/admin/';
						$applications['dir'] = $install_app . '/';
					}
					
					$applications['use_message'] = $appinfo['use_message'];
					$applications['use_material'] = $appinfo['use_material'];
					$applications['use_textsearch'] = $appinfo['use_textsearch'];
					$applications['use_logs'] = $appinfo['use_logs'];
					$applications['use_recycle'] = $appinfo['use_recycle'];
					$applications['use_access'] = $appinfo['use_access'];
					$curl->initPostData();
					foreach ($applications AS $k => $v)
					{
						$curl->addRequestData($k, $v);
					}
					$curl->addRequestData('bundle', $applications['softvar']);
					$ret = $curl->request('admin/apps.php');
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
							$modules[] = $row;
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
										'order_id' => $row['order_id'],
										'father_id' => $appinfo['class_id'],
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
							$curl->initPostData();
							foreach ($row AS $k => $v)
							{
								$curl->addRequestData($k, $v);
							}
							$curl->addRequestData('main_module', $main_module);
							$ret = $curl->request('admin/modules.php');
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
					$sql = 'UPDATE ' . DB_PREFIX . "menu SET name='{$mmenu['name']}', order_id={$mmenu['order_id']},father_id={$mmenu['father_id']},url='{$mmenu['url']}', module_id='{$mmenu['module_id']}' WHERE id={$q['id']} ";
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
			
			$this->cache->recache('applications');
			$this->cache->recache('modules');
			$this->cache->recache('menu');
		}
		else
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . "applications WHERE softvar='{$appinfo['app_uniqueid']}'";
			$exist = $this->db->query_first($sql);
			if ($exist)
			{
				$sql = 'UPDATE ' . DB_PREFIX . "applications SET version='{$appinfo['version']}' WHERE id={$exist['id']} ";
				$this->db->query($sql);
			}
		}
		
		hg_flushMsg('应用设置更新完成');

		if ($application_id)
		{
			hg_flushMsg('开始更新模板');
			$this->rebuild_templates($application_id);
			hg_flushMsg('模板更新完成');
		}
		
		//记录已安装应用
		$this->appstore->initPostData();
		$this->appstore->addRequestData('a', 'updated');
		$this->appstore->addRequestData('app', $this->input['app']);
		$this->appstore->request('index.php');
		
		$update_apps = @file_get_contents(CACHE_DIR . 'onekupdate');
		$update_apps = json_decode($update_apps, 1);
		$tdb = $update_apps['okupdatedbinfo'];
		unset($update_apps['okupdatedbinfo']);
		if ($update_apps)
		{
			unset($update_apps[$install_app]);
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
		$curl = new curl($this->product_server['host'] . ':' . $this->product_server['port'], '');
		$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$curl->setSubmitType('get');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('app', $install_app);
		$new_configs = $curl->request('config.php');

		if ($new_configs)
		{
			$doset = array();
			foreach ($new_configs AS $k => $v)
			{
				if (is_array($v))
				{
					foreach ($v AS $kk => $vv)
					{
						if (!$user_configs[$k][$kk])
						{
							$doset[$k][$kk] = $vv;
						}
					}
				}
			}
		}
		if ($doset)
		{
			$curl =  new curl($installinfo['host'], $installinfo['dir']);
			$curl->setSubmitType('post');
			$curl->setReturnFormat('json');
			$curl->initPostData();
			$curl->addRequestData('a', 'doset');
			
			foreach ($doset AS $k => $v)
			{
					foreach($v AS $kk => $vv)
					{
						if (is_array($vv))
						{
							foreach($vv AS $kkk => $vvv)
							{
								if (is_array($vvv))
								{
									foreach($vvv AS $kkkk => $vvvv)
									{
										if (is_array($vvvv))
										{
											foreach($vvvv AS $kkkkk => $vvvvv)
											{
												$curl->addRequestData($k . "[$kk][$kkk][$kkkk][$kkkkk]", $vvvvv);
											}
										}
										else
										{
											$curl->addRequestData($k . "[$kk][$kkk][$kkkk]", $vvvv);
										}
									}
								}
								else
								{
									$curl->addRequestData($k . "[$kk][$kkk]", $vvv);
								}
							}
						}
						else
						{
							$curl->addRequestData($k . "[$kk]", $vv);
						}
					}
			}
			
			$ret = $curl->request('configuare.php');
		}
		if ($this->input['onekupdate'])
		{
			$url = 'appstore.php?a=goonekupdate';
		}
		else
		{
			if (!is_file(CACHE_DIR . 'upgrade/' . date('Ymd') . '/' . $install_app . '.sql'))
			{
				$url = '?app=' . $install_app;
			}
		}
		hg_flushMsg('应用更新成功', $url);
?>