<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: recache.class.php 1524 2011-01-04 09:46:16Z yuna $
***************************************************************************/

class program
{
	private $db;

	function __construct()
	{
		global $gCache;
		$this->db = hg_checkDB();
		$this->cache = &$gCache;
	}

	function __destruct()
	{
	}

	public function rebuild_program($application_id = 0, $module_id = 0)
	{
		if ($application_id)
		{
			$condition = ' WHERE application_id IN (' . $application_id . ')';
		}
		if ($module_id)
		{
			$condition = ' WHERE id IN (' . $module_id . ')';
		}


		$sql = "SELECT id FROM " . DB_PREFIX . "modules{$condition}";
		$q = $this->db->query($sql);
		$modules = array();
		while ($row = $this->db->fetch_array($q))
		{
			$modules[] = $row['id'];
		}


		$sql = "SELECT op FROM " . DB_PREFIX . "module_op";
		$q = $this->db->query($sql);
		$module_ops = array();
		while ($row = $this->db->fetch_array($q))
		{
			$module_ops[$row['op']] = $row['op'];
		}

		foreach ($modules AS $id)
		{
			$this->compile($id);
			foreach ($module_ops AS $type)
			{
				$this->compile($id, $type);
			}
		}

		if ($module_id)
		{
			return true;
		}
		$sql = "SELECT id FROM " . DB_PREFIX . "node{$condition}";
		$q = $this->db->query($sql);
		$nodes = array();
		while ($row = $this->db->fetch_array($q))
		{
			$nodes[] = $row['id'];
		}
		include_once(ROOT_PATH . 'lib/class/node.class.php');
		$program = new nodeapi();
		foreach ($nodes AS $id)
		{
			$program->compile($id);
		}
		return true;

	}
	/**
	* 有分页必须提供count方法
	*
	*/
	public function compile($module_id, $type = 'show')
	{
		$module_id = intval($module_id);
		$sql = "SELECT * FROM " . DB_PREFIX . "modules WHERE id=" . $module_id;

		$module = $this->db->query_first($sql);
		if (!$module)
		{
			$program  = '<?php
				$this->ReportError(\'运行模块不存在\');
			?>';
			if (hg_mkdir(CACHE_DIR . 'program/' . $type))
			{
				hg_file_write(CACHE_DIR . 'program/' . $type . '/' . $module_id . '.php', $program);
				return $module_id;
			}
			else
			{
				exit(CACHE_DIR . 'program/' . $type . '目录创建失败，请检查目录权限.');
			}
		}
		if ($module['settings'])
		{
			$module['settings'] = unserialize($module['settings']);
		}
		else
		{
			$module['settings'] = array();
		}

		$module['primary_key'] = $module['primary_key'] ? $module['primary_key'] : 'id';
		$application = hg_check_application(intval($module['application_id']));
		if (!$application)
		{
			exit('应用不存在或已被删除');
		}
		$func = 'compile_' . $type;
		if (!method_exists($this, $func))
		{
			$func = 'compile_default';

		}
		return $this->$func($module, $application, $type);
	}
	private function compile_update($module, $application, $type)
	{
		$module_id = $module['id'];
		$sql = "SELECT * FROM " . DB_PREFIX . "module_op  WHERE module_id IN (0, " . $module_id . ') AND op=\'' . $type . '\' ORDER BY order_id ASC';
		$op = $this->db->query_first($sql);
		$op = $this->cal_op_info($module_id, $op);
		//默认发布到网站
		if(!$medium_type)
		{
			$medium_type = 1;
		}
		if (!$op)
		{
			$program  = '<?php
				$this->ReportError(\'此模块暂不支持此操作\');
			?>';
			if (hg_mkdir(CACHE_DIR . 'program/' . $type))
			{
				hg_file_write(CACHE_DIR . 'program/' . $type . '/' . $module_id  . '.php', $program);
				return $module_id;
			}
			else
			{
				exit(CACHE_DIR . 'program/' . $type . '目录创建失败，请检查目录权限.');
			}
		}
		//发布模块ID的优先级 模块ID<关联模块ID<自定义模块ID
		$relate_molude_id = intval($module['relate_molude_id']);
		$pub_moduleid = $relate_molude_id ? $relate_molude_id : $module_id;
		$pub_moduleid = $module['pub_module_id'] ? $module['pub_module_id'] : $pub_moduleid;
		//$sql = "SELECT * FROM " . DB_PREFIX ."publish_fieldmap WHERE moduleid = {$pub_moduleid} AND medium_type = {$medium_type}";
		//是否设置了模块发布选项 如果设置则在操作发布时会执行一下程序
		//$pub = $this->db->query_first($sql);
		$program  = '<?php
		';
		$api = $this->cal_api($application, $module, $op);
		$program  .= '
		$this->curl = new curl(\'' . $api['host'] . '\', \'' . $api['dir'] . '\');
		';
		if ($application['appid'] && $application['appkey'])
		{		
			$program  .= '
		$this->curl->setClient(\'' . $application['appid'] . '\', \'' . $application['appkey'] . '\');
		';
		}
		if (!$op['file_name'])
		{
			if (!$op['template'])
			{
				$op['file_name'] = $module['file_name'] . '_update';
			}
			else
			{
				$op['file_name'] = $module['file_name'];
			}
		}
		$module['primary_key'] = $module['primary_key'] ? $module['primary_key'] : 'id';
		if ($op['func_name'])
		{
			if ($module['template'])
			{
				$title = $module['settings']['title'];
				$brief = $module['settings']['brief'];
				$link = $module['settings']['link'];
				$content = $module['settings']['content'];
				$content = $content ? $content : 'content';
				//处理图片字段
				$picstr = '$hg_pic_str = array(';
				if ($module['settings']['show'])
				{
					$pic = $module['settings']['pic'];

					foreach ($module['settings']['show'] AS $k => $v)
					{
						if (is_array($v))
						{
							foreach ($v AS $kk => $vv)
							{
								if ($pic[$k][$kk])
								{
									$picstr .= "'$k . $kk',";
								}
							}
						}
						else
						{
							if ($pic[$v])
							{
								$picstr .= "'$v',";
							}
						}
					}
				}
				$picstr .= ');';
			}
			$program  .= '
				$this->curl->setSubmitType(\'post\');
				$this->curl->setReturnFormat(\'json\');
				';
			if($module['primary_key'])
			{
				$program  .= '
				$id = $this->input[\'' . $module['primary_key'] . '\'];
				$this->curl->initPostData();
				$this->curl->addRequestData(\'a\', \'' . $op['func_name'] . '\');
				$this->curl->addRequestData(\'' . $module['primary_key'] . '\', $id);';
				$program  .= '
				$hg_data_return = $this->curl->request(\'' . $op['file_name'] . $op['file_type'] . '\');
				';
			}
			else
			{
				$program  .= '
				$this->curl->initPostData();
				$this->curl->addRequestData(\'a\', \'' . $op['func_name'] . '\');';
				$program  .= '
				$hg_data_return = $this->curl->request(\'' . $op['file_name'] . $op['file_type'] . '\');
				';
			}
			if($pub)
			{
				$log_pub = '';
				$program .= '
				//file_put_contents(\'3.txt\', var_export($hg_data_return, 1));
				if($hg_data_return)
				{
					$arcinfo = $hg_data_return[0];
					$colname = $this->input[\'colname\'] ? trim(urldecode($this->input[\'colname\'])) : \'columnid\';
					$columnids = $this->input[$colname];
					if(is_array($columnids) && (count($columnids) > 5))
					{
						$this->ReportError(\'发布栏目不能大于5个\', \'\', 0, 0, "' . $func . '");
					}
					if(!$publish)
					{
						if(!class_exists(\'publish\'))
						{
							include_once(ROOT_DIR.\'lib/class/publish.class.php\');
						}
						$publish = new publish();
					}
					//调用publish类 注意参数顺序 返回的数据中必须有id即内容id 和状态字段publish表需要纪录
					$publish->update(/*intval($siteid),*/ '.$pub_moduleid.', $arcinfo[\'id\'], $columnids, 0, array("admin_name"=>$this->user["user_name"], "admin_id"=>$this->user["id"]));
					//file_put_contents(\'1.txt\', $this->user["user_name"] . $this->user["id"]);
					$log_pub = true;
				}
				';
			}
			if ($module['is_log'] && $op['is_log']) //记录日志
			{
				$log_program = '
				include_once(ROOT_PATH . \'lib/class/log.class.php\');
				$log = new hglog();
				$logcontent = \'' . $op['name'] . '内容\';
				$log->add_log($logcontent, \'' . $op['op'] . '\');
				if($log_pub)
				{
					$log->add_log("'.$module['name'].'发布内容[{$id}]至CMS栏目");
				}
				';
			}
			if (!$op['return_var']) //记录日志
			{
				$op['return_var'] = 'formdata';
			}
			if ($op['template'])
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "module_append  WHERE module_id=" . $module_id . ' AND op=\'' . $type . "'";
				$query = $this->db->query($sql);
				while ($row = $this->db->fetch_array($query))
				{
					$api1 = $this->cal_api($application, $module, $row);
					if ($api1['host'] != $api['host'])
					{
						$program  .= '
								$this->curl1 = new curl(\'' . $api1['host'] . '\', \'' . $api1['dir'] . '\');
						';
					}
					elseif ($api1['dir'] != $api['dir'])
					{
						$program  .= '
								$this->curl1 = new curl(\'' . $api1['host'] . '\', \'' . $api1['dir'] . '\');
						';
					}
					else
					{
						$program  .= '
								$this->curl1 = $this->curl;
						';
					}
					if ($application['appid'] && $application['appkey'])
					{		
						$program  .= '
							$this->curl1->setClient(\'' . $application['appid'] . '\', \'' . $application['appkey'] . '\');
					';
					}
					$program  .= '
					$this->curl1->initPostData();
					$this->curl1->addRequestData(\'trigger_action\', \'update\');
					$this->curl1->addRequestData(\'trigger_mod_uniqueid\', \''.$module['mod_uniqueid'].'\');
					';
					
					if ($row['count'])
					{
						$program  .= '
							$this->curl1->addRequestData(\'count\', \''.$row['count'].'\');
						';
					}
					$row['func_name'] = $row['func_name']?$row['func_name']:'show';
					$program .= '
							$this->curl1->setReturnFormat(\'' . $row['return_type'] . '\');
							$this->curl1->addRequestData(\'a\', \''.$row['func_name'].'\');';
					if($row['paras'])
					{
						$tmp = explode(',',$row['paras']);
						foreach($tmp as $k => $v)
						{
							$program .= '$this->curl1->addRequestData(\'' . $v . '\', $this->input["' . $v . '"]);';
						}
					}
					$program .= '$datas = $this->curl1->request(\'' . $row['file_name'] . $row['file_type'] . '\');
					';
					if ($row['return_var'])
					{
						$program  .= '
								$this->tpl->addVar(\'' . $row['return_var'] . '\', $datas);
						';
					}
				}
				$program  .= '
					$this->curl->initPostData();
					$this->curl->addRequestData(\'a\', \'__getConfig\');
					$_configs = $this->curl->request(\'' . $op['file_name'] . $op['file_type'] . '\');
					$_configs = $_configs[0];
					$this->tpl->addVar(\'_configs\', $_configs);
					$this->tpl->setSoftVar(\'' . $application['softvar'] . '\'); //设置软件界面
					$this->tpl->addVar(\'' . $module['primary_key']  . '\', $id);
					$this->tpl->addVar(\'hg_commend_fields\', $hg_commend_fields);
					$this->tpl->addVar(\'fixcommendfields\', $fixcommendfields);
					$this->tpl->addVar(\'primary_key\', \'' . $module['primary_key'] . '\');
					$hg_set_template = \'' . $op['template'] . '\';
					$hg_set_callback = \'' . $op['callback'] . '\';
					$hg_set_return = \'' . $op['return_var'] . '\';
					$hg_primary_key = \'' . $module['primary_key'] . '\';
				';
				if (!DEVELOP_MODE)
				{
					$program  .= '
						$this->tpl->setScriptDir(\'app_' . $application['softvar'] . '/\'); 
						$this->tpl->setTemplateVersion(\'' . $application['softvar'] . '/' . $application['version'] . '\'); 
					';
				}
				else
				{
					$program  .= '
						$this->tpl->setTemplateVersion(\'\'); 
						$this->tpl->setScriptDir(\'\'); 
					';
				}
				if($op['callback'])
				{
					$func = '';
					$func_param = '';
					$var = '';
					$arr_callback = explode(',',$op['callback']);
					foreach($arr_callback as $call_key => $call_value)
					{
						if($call_key)
						{
							$program .=  '$' . $call_value . '  = $this->input[\'' . $call_value . '\'];';
							$var .= '$this->tpl->addVar(\'' . $call_value . '\', $' . $call_value . ');';
							$func_param .= ',$' . $call_value;
						}
						else
						{
							$func = $call_value ;
						}
					}
				}
				$program .= '
				if (is_array($hg_data_return) && !$hg_data_return["ErrorCode"])
				{
					$hg_data_return = $hg_data_return[0];
				}' . $var;

				$program .=  '
				$this->tpl->addVar(\'_relate_module\', $_relate_module);
				$this->tpl->addVar(\'' . $op['return_var'] . '\', $hg_data_return);
				if($hg_data_return["ErrorCode"])
				{
					$this->tpl->outTemplate(\'' . $op['template']. '\', "' . $func . $func_param . ',error");
				}
				else
				{
					$this->tpl->outTemplate(\'' . $op['template']. '\', "' . $func . $func_param . '");
				}
				';
			}
			else
			{
				$program  .= $log_program;
				if ($op['direct_return'])
				{
					if ($op['callback'])
					{
						if(!$op['exec_callback'])
						{
							$program .= 'if(is_array($hg_data_return) && !$hg_data_return["ErrorCode"])
							{
								$hg_data_return = $hg_data_return[0];
							}';
							$var = '';
							$callback = explode(',',$op['callback']);
							$op['callback'] = $callback[0];
							unset($callback[0]);
							foreach($callback as $call_key => $call_value)
							{
								$program .=  '$' . $call_value . '  = $this->input[\'' . $call_value . '\'];';
								$var .= ', $' . $call_value ;
							}

							$func = $op['callback'] . '(\'".json_encode($hg_data_return)."\'' . $var . ')';

							$program  .= '
										$arr = array("msg"=>"","callback"=>"' . $func . '");
										echo json_encode($arr);
									';

							//$program  .= 'echo \'<script type=text/javascript>' . $func . '</script>\';';
						}
						else
						{
							$program  .= '
							
									$HTTP_HOST = $_SERVER[\'HTTP_HOST\'];
									$info = explode(\':\', $HTTP_HOST);
									$hg_data_return = json_encode($hg_data_return);
									if ($info[1])
									{
										$hg_data_return = str_replace(array(\'img.hoge.cn\', \'vod.hoge.cn\'), array(\'img.hoge.cn:234\', \'vod.hoge.cn:234\'), $hg_data_return);
									}
									';
							$func = $op['callback'] . '(\' . $hg_data_return . \')';
							$program  .= 'echo \'' . $func . '\';';
						}

					}
					else
					{
						$program  .= '
						
								$HTTP_HOST = $_SERVER[\'HTTP_HOST\'];
								$info = explode(\':\', $HTTP_HOST);
								$hg_data_return = json_encode($hg_data_return);
								if ($info[1])
								{
									$hg_data_return = str_replace(array(\'img.hoge.cn\', \'vod.hoge.cn\'), array(\'img.hoge.cn:234\', \'vod.hoge.cn:234\'), $hg_data_return);
								}
								';
						$program  .= 'echo $hg_data_return;';
					}
				}
				else
				{
					if ($op['callback'])
					{
						$func = $op['callback'] . '(\'$id\')';
					}
					$program  .= '
					if ($hg_data_return)
					{
						$this->redirect(\'' . $op['name'] . '成功\', \'\', 0, 0, "' . $func . '");
					}
					else
					{
						$this->ReportError(\'' . $op['name'] . '失败\', \'\', 0, 0, "' . $func . '");
					}
					';
				}
			}
		}
		$program  .= ' ?>';
		if (hg_mkdir(CACHE_DIR . 'program/' . $op['op']))
		{
			hg_file_write(CACHE_DIR . 'program/' . $op['op'] . '/' . $module['id'] . '.php', $program);
		}
		else
		{
			exit(CACHE_DIR . 'program/' . $op['op'] . '目录创建失败，请检查目录权限.');
		}
		return $module['id'];
	}
	private function compile_form($module, $application, $type)
	{
		$module_id = $module['id'];
		$relate_moldue_id = $module['relate_molude_id'];
		$sql = "SELECT * FROM " . DB_PREFIX . "module_op  WHERE module_id IN (0, " . $module_id . ') AND op=\'' . $type .'\' ORDER BY module_id ASC';
		$q = $this->db->query($sql);
		$op = array();
		while ($row = $this->db->fetch_array($q))
		{
			$op = $row;
		}
		$op = $this->cal_op_info($module_id, $op);
		//默认发布到网站
		if(!$medium_type)
		{
			$medium_type = 1;
		}
		$relate_molude_id = intval($module['relate_molude_id']);
		$pub_moduleid = $relate_molude_id ? $relate_molude_id : $module_id;
		//$sql = "SELECT * FROM " . DB_PREFIX ."publish_fieldmap WHERE moduleid = {$pub_moduleid} AND medium_type = {$medium_type}";
		//$pub = $this->db->query_first($sql);
		$program  = '<?php
		';

		$sql = "SELECT * FROM " . DB_PREFIX . "module_append  WHERE module_id IN (" . $module_id . ', ' . intval($relate_moldue_id) . ') AND op=\'' . $type . "'";
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$api1 = $this->cal_api($application, $module, $row);
			if ($api1['host'] != $api['host'])
			{
				$program  .= '
						$this->curl1 = new curl(\'' . $api1['host'] . '\', \'' . $api1['dir'] . '\');
				';
			}
			elseif ($api1['dir'] != $api['dir'])
			{
				$program  .= '
						$this->curl1 = new curl(\'' . $api1['host'] . '\', \'' . $api1['dir'] . '\');
				';
			}
			else
			{
				$program  .= '
						$this->curl1 = $this->curl;
				';
			}		
			if ($application['appid'] && $application['appkey'])
			{		
				$program  .= '
			$this->curl1->setClient(\'' . $application['appid'] . '\', \'' . $application['appkey'] . '\');
			';
			}
			$program  .= '
			$this->curl1->initPostData();
			$this->curl1->addRequestData(\'trigger_action\', \'form\');
			$this->curl1->addRequestData(\'trigger_mod_uniqueid\', \''.$module['mod_uniqueid'].'\');
			';
			if ($row['count'])
			{
				$program  .= '
					$this->curl1->addRequestData(\'count\', \''.$row['count'].'\');
				';
			}
			$row['func_name'] = $row['func_name'] ? $row['func_name'] : 'show';
			$program .= '
				$this->curl1->setReturnFormat(\'' . $row['return_type'] . '\');
				$this->curl1->addRequestData(\'a\', \''.$row['func_name'].'\');';
			if($row['paras'])
			{
				$tmp = explode(',',$row['paras']);
				foreach($tmp as $k => $v)
				{
					$program .= '$this->curl1->addRequestData(\'' . $v . '\', $this->input["' . $v . '"]);';
				}
			}
			$program .= '$datas = $this->curl1->request(\'' . $row['file_name'] . $row['file_type'] . '\');
			';
			if ($row['return_var'])
			{
				$program  .= '
						$this->tpl->addVar(\'' . $row['return_var'] . '\', $datas);
				';
			}
		}
		$api = $this->cal_api($application, $module, $op);
		$form_set = unserialize($module['form_set']);
		$module_settings = $module['settings'];
		if ($form_set['order'])
		{
			@asort($form_set['order']);
			$form_set_str = '$form_set = array(';
			foreach ($form_set['order'] AS $k => $v)
			{
				if ($form_set['canedit'][$k])
				{
					$form_set_str .= "'$k' => array(
						'title' => '{$form_set['title'][$k]}',
						'group' => '{$form_set['group'][$k]}',
						'show_type' => '{$form_set['show_type'][$k]}',
						'rowscols' => '{$form_set['rowscols'][$k]}',
						'width' => '{$form_set['width'][$k]}',
						'height' => '{$form_set['height'][$k]}',
					),";
				}
			}
			$form_set_str .= ');';
		}
		$program  .= $form_set_str . '
		$this->curl = new curl(\'' . $api['host'] . '\', \'' . $api['dir'] . '\');
		';		
		if ($application['appid'] && $application['appkey'])
		{		
			$program  .= '
		$this->curl->setClient(\'' . $application['appid'] . '\', \'' . $application['appkey'] . '\');
		';
		}
		if ($module['paras'])
		{
		}
		if (!$op['file_name'])
		{
			if (!$op['template'])
			{
				$op['file_name'] = $module['file_name'] . '_update';
			}
			else
			{
				$op['file_name'] = $module['file_name'];
			}
		}
		$sql = "SELECT mn.*, n.return_var, n.primary_key FROM " . DB_PREFIX . "module_node mn LEFT JOIN " . DB_PREFIX . "node n ON mn.node_id=n.id WHERE module_id=" . $module_id . ' AND module_op=\'' .  $type . '\'';
		$query = $this->db->query($sql);
		$node_info = array();
		while ($row = $this->db->fetch_array($query))
		{
			$node_info[$row['node_id']] = $row;
		}
		$module['primary_key'] = $module['primary_key'] ? $module['primary_key'] : 'id';
		if ($op['func_name'])
		{
			$program  .= '
				$id = $this->input[\'' . $module['primary_key'] . '\'];
				$this->curl->setSubmitType(\'post\');
				$this->curl->setReturnFormat(\'json\');
				$this->curl->initPostData();
				$this->curl->addRequestData(\'a\', \'__getConfig\');
				$_configs = $this->curl->request(\'' . $op['file_name'] . $op['file_type'] . '\');
				$_configs = $_configs[0];
				$this->tpl->addVar(\'_configs\', $_configs);
				if ($id)
				{
					$this->curl->initPostData();
					$this->curl->addRequestData(\'a\', \'' . $op['func_name'] . '\');
					$this->curl->addRequestData(\'' . $module['primary_key']  . '\', $id);
					$formdata = $this->curl->request(\'' . $op['file_name'] . $op['file_type'] . '\');
					if ($formdata)
					{
						if(count($formdata) == 1)
						{
							$formdata = $formdata[0];
						}
					}
					if (!$formdata)
					{
						$this->ReportError(\'指定记录不存在或已删除!\');
					}
					$a = \'update\';
					$optext = \'更新\';
				}
				else
				{
					$a = \'create\';
					$formdata = $this->input;
					$optext = \'增加\';
				}
			';
			if(0 && $op['show_pub'])
			{
				$program  .= '
				if(!class_exists(\'publish\'))
				{
					include_once(ROOT_DIR.\'lib/class/publish.class.php\');
				}
				$publish = new publish();
				$formdata[\'haspub\'] = $publish->getcontentpub('.$pub_moduleid.', $id);
				';
			}
			foreach($node_info AS $nid => $row)
			{
				$var = 'hg_' . $row['return_var'] . '_selected';
				$program  .= '
				$' . $var . ' = $formdata[\'' . $row['primary_key'] . '\'];
				include hg_load_node(' . $row['node_id'] . ');
				';
			}

			if ($op['template'])
			{
				if (!$op['return_var']) //记录日志
				{
					$op['return_var'] = 'formdata';
				}

				$relate_menu = unserialize($module['relate_menu']);
				$_relate_menu = '$relate_menu = array(';
				if ($relate_menu)
				{
					foreach ($relate_menu AS $k => $v)
					{
						$_relate_menu .= $k . '=> \'' . $v . '\',';
					}
				}
				$_relate_menu .= ');';
				$program  .= $_relate_menu . '
					$nav = array(
						\'name\' => $optext,
						\'link\' => \'#\'
					);
					if($formdata["end"])
					{
						$this->navdata["title"] = array(
						"name" =>$formdata["title"],
			            "class" => "",
			            "link" => "#",
			            "target" => "mainwin",
						);
					}
					//$this->append_nav($nav);
					$this->tpl->addVar(\'_nav\', $this->nav);
					$this->tpl->addVar(\'_navdata\', $this->navdata);
					$this->tpl->setSoftVar(\'' . $application['softvar'] . '\'); //设置软件界面
					$this->tpl->addVar(\'a\', $a);
					$this->tpl->addVar(\'optext\', $optext);
					$this->tpl->addVar(\'form_set\', $form_set);
					$this->tpl->addVar(\'' . $op['return_var'] . '\', $formdata);
					$this->tpl->addVar(\'' . $module['primary_key']  . '\', $id);
					$this->tpl->addVar(\'hg_title\', \'' . $module_settings['title']  . '\');
					$this->tpl->addVar(\'primary_key\', \'' . $module['primary_key'] . '\');
					$this->tpl->addVar(\'relate_menu\', $relate_menu);
				';
				if (!DEVELOP_MODE)
				{
					$program  .= '
						$this->tpl->setTemplateVersion(\'' . $application['softvar'] . '/' . $application['version'] . '\'); 
						$this->tpl->setScriptDir(\'app_' . $application['softvar'] . '/\'); 
					';
				}
				else
				{
					$program  .= '
						$this->tpl->setTemplateVersion(\'\'); 
						$this->tpl->setScriptDir(\'\'); 
					';
				}
				if ($op['callback'])
				{
					$callback = explode(",",$op['callback']);
					$op['callback'] = $callback[0];
					unset($callback[0]);
					if ($callback)
					{
						$jsstr = '';
						foreach ($callback AS $v)
						{
							$v = trim($v);
							if($v == 'id')
							{
								$jsstr .= ",\$id";
							}
							else
							{
								$jsstr .= ',".$this->input[\'' . $v . '\']."';
							}
						}
					}
					$op['callback'] .= $jsstr;
					$program .= '$this->tpl->outTemplate(\'' . $op['template']. '\',"' . $op['callback'] . '");';
				}
				else
				{
					$program .= '$this->tpl->outTemplate(\'' . $op['template']. '\');';
				}
			}
		}
		$program  .= ' ?>';
		if (hg_mkdir(CACHE_DIR . 'program/' . $op['op']))
		{
			hg_file_write(CACHE_DIR . 'program/' . $op['op'] . '/' . $module['id'] . '.php', $program);
			return $module['id'];
		}
		else
		{
			exit(CACHE_DIR . 'program/' . $op['op'] . '目录创建失败，请检查目录权限.');
		}
	}

	private function compile_default($module, $application, $type)
	{
		$module_id = $module['id'];
		$sql = "SELECT * FROM " . DB_PREFIX . "module_op  WHERE module_id IN (0, " . $module_id . ') AND op=\'' . $type . '\' ORDER BY module_id ASC';
		$q = $this->db->query($sql);
		$op = array();
		while ($row = $this->db->fetch_array($q))
		{
			$op = $row;
		}
		if (!$op)
		{
			$program  = '<?php
				$this->ReportError(\'此模块暂不支持此操作\');
			?>';
			if (hg_mkdir(CACHE_DIR . 'program/' . $type))
			{
				hg_file_write(CACHE_DIR . 'program/' . $type . '/' . $module_id  . '.php', $program);
				return $module_id;
			}
			else
			{
				exit(CACHE_DIR . 'program/' . $type . '目录创建失败，请检查目录权限.');
			}
		}
		$op = $this->cal_op_info($module_id, $op);
		$program  = '<?php
		';
		$api = $this->cal_api($application, $module, $op);
		$program  .= '
		$api = array(
			\'host\' => \'' . $api['host'] . '\',
			\'port\' => \'' . $api['port'] . '\',
			\'dir\' => \'' . $api['dir'] . '\',
			);
		$this->tpl->addVar(\'__api\', urlencode(json_encode($api)));
		$this->curl = new curl(\'' . $api['host'] . '\', \'' . $api['dir'] . '\');
		';
		if ($application['appid'] && $application['appkey'])
		{		
			$program  .= '
		$this->curl->setClient(\'' . $application['appid'] . '\', \'' . $application['appkey'] . '\');
		';
		}
		/*
		if ($op['relate_node'])
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "module_node  WHERE module_id=" . $module_id;
			$query = $this->db->query($sql);
			while ($row = $this->db->fetch_array($query))
			{
				$program  .= '
				include hg_load_node(' . $row['node_id'] . ');
				';
			}
		}*/
		if ($module['paras'])
		{
		}

		if (!$op['file_name'])
		{
			if (!$op['template'])
			{
				$op['file_name'] = $module['file_name'] . '_update';
			}
			else
			{
				$op['file_name'] = $module['file_name'];
			}
		}
		$module['primary_key'] = $module['primary_key'] ? $module['primary_key'] : 'id';
		if ($op['func_name'])
		{
			if ($module['template'])
			{
				$title = $module['settings']['title'];
				$brief = $module['settings']['brief'];
				$link = $module['settings']['link'];
				$content = $module['settings']['content'];
				$content = $content ? $content : 'content';
				//处理图片字段
				$picstr = '$hg_pic_str = array(';
				if ($module['settings']['show'])
				{
					$pic = $module['settings']['pic'];

					foreach ($module['settings']['show'] AS $k => $v)
					{
						if (is_array($v))
						{
							foreach ($v AS $kk => $vv)
							{
								if ($pic[$k][$kk])
								{
									$picstr .= "'$k . $kk',";
								}
							}
						}
						else
						{
							if ($pic[$v])
							{
								$picstr .= "'$v',";
							}
						}
					}
				}
				$picstr .= ');';
			}
			$program  .= '
				$this->curl->setSubmitType(\'post\');
				$this->curl->setReturnFormat(\'json\');
				';
			if($module['primary_key'])
			{
				$program  .= '
				$id = $this->input[\'' . $module['primary_key'] . '\'];
				$this->curl->initPostData();
				$this->curl->addRequestData(\'a\', \'' . $op['func_name'] . '\');
				$this->curl->addRequestData(\'' . $module['primary_key'] . '\', $id);';
				$program  .= '$hg_data_return = $this->curl->request(\'' . $op['file_name'] . $op['file_type'] . '\');
				';
			}
			else
			{
				$program  .= '
				$this->curl->initPostData();
				$this->curl->addRequestData(\'a\', \'' . $op['func_name'] . '\');';
				$program  .= '$hg_data_return = $this->curl->request(\'' . $op['file_name'] . $op['file_type'] . '\');
				';
			}
			//频道发布时调用的方法
			if(0 && $op['op'] == 'publish')
			{
				$program  .= '
				if(!$publish)
				{
					if(!class_exists(\'publish\'))
					{
						include_once(ROOT_DIR.\'lib/class/publish.class.php\');
					}
					$publish = new publish();
				}
				$pub_col = $publish->get_publish_col($id);
				$this->tpl->addVar(\'pub_col\', $pub_col);
				';
			}
			//发布模块ID的优先级 模块ID<关联模块ID<自定义模块ID
			$relate_molude_id = intval($module['relate_molude_id']);
			$pub_moduleid = $relate_molude_id ? $relate_molude_id : $module_id;
			$pub_moduleid = $module['pub_module_id'] ? $module['pub_module_id'] : $pub_moduleid;

			//是否触发更新发布操作
			if(0 && $op['trigger_pub'])
			{
				$program  .= '

				//加载发布类
				if(!$publish)
				{
					if(!class_exists(\'publish\'))
					{
						include_once(ROOT_DIR.\'lib/class/publish.class.php\');
					}
					$publish = new publish();
				}
				//初始化发布栏目数据
				$haspub = array();

				/*
					标记打回和审核
					为0 代表更新或者删除 创建
					为1 代表打回操作
					打回：不删除发布信息
					删除：删除发布信息
				*/
				$state = 0;

				//获取操作接口返回的内容 取内容ID
				$api_return = $hg_data_return[0];
				if(isset($api_return["id"])&& $api_return["id"])
				{
					//接口返回的数组中存在索引
					$api_return = (array)$api_return["id"];
				}
				//打回和审核是同一个操作 pubstatus用于区分 等于0代表是打回 删除发布
				//此处也不需要发布信息
				if(isset($api_return["pubstatus"]) && $api_return["pubstatus"] == 0)
				{
					$state = 1;
				}
				//取出内容的发布信息 除删除操作 删除操作也不需要发布信息
				else if("'.strtolower($op['func_name']).'" != "delete")
				{
					$haspub = $publish->getcontentpub('.$pub_moduleid.', $api_return);
				}
				//表单提交栏目 优先级高于数据的读取
				$colname = $this->input[\'colname\'] ? trim(urldecode($this->input[\'colname\'])) : \'columnid\';
				$columnids = $this->input[$colname];
				if($columnids)
				{
					if($api_return)
					{
						foreach($api_return as $v)
						{
							$haspub[$v] = $columnids;
						}
					}
				}

				//file_put_contents(\'1.txt\', var_export($api_return,1), FILE_APPEND);
				//file_put_contents(\'1.txt\', $recordid, FILE_APPEND);

				if(is_array($api_return))
				{
					foreach($api_return as $v)
					{
						$haspub[$v] = $haspub[$v] ? $haspub[$v] : array();
						$publish->update('.$pub_moduleid.', $v, $haspub[$v],$state, array("admin_name"=>$this->user["user_name"], "admin_id"=>$this->user["id"]));
					}
				}
				';
			}
			//如果是推荐 则调用已推荐至的栏目 显示 或者设置数据库的show_pub字段
			if ($op['op'] == 'recommend' || $op['show_pub'])
			{
				$program .= '
				$this->tpl->addVar(\'relate_module_id\', ' . intval($module['relate_molude_id']) . ');
				';
			}
			else
			{
				if ($module['is_log'] && $op['is_log']) //记录日志
				{
					$log_program = '
					include_once(ROOT_PATH . \'lib/class/log.class.php\');
					$log = new hglog();
					$logcontent = \'' . $op['name'] . '内容\';
					$log->add_log($logcontent, \'' . $op['op'] . '\');
					';
				}
			}
			if (!$op['return_var']) //记录日志
			{
				$op['return_var'] = 'formdata';
			}
			if ($op['template'])
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "module_append  WHERE module_id=" . $module_id . ' AND op=\'' . $type . "'";
				$query = $this->db->query($sql);
				while ($row = $this->db->fetch_array($query))
				{
					$api1 = $this->cal_api($application, $module, $row);
					if ($api1['host'] != $api['host'])
					{
						$program  .= '
								$this->curl1 = new curl(\'' . $api1['host'] . '\', \'' . $api1['dir'] . '\');
						';
					}
					elseif ($api1['dir'] != $api['dir'])
					{
						$program  .= '
								$this->curl1 = new curl(\'' . $api1['host'] . '\', \'' . $api1['dir'] . '\');
						';
					}
					else
					{
						$program  .= '
								$this->curl1 = $this->curl;
						';
					}
					if ($application['appid'] && $application['appkey'])
					{		
						$program  .= '
					$this->curl1->setClient(\'' . $application['appid'] . '\', \'' . $application['appkey'] . '\');
					';
					}
					$program  .= '
					$this->curl1->initPostData();
					$this->curl1->addRequestData(\'trigger_action\', \''.$type.'\');
					$this->curl1->addRequestData(\'trigger_mod_uniqueid\', \''.$module['mod_uniqueid'].'\');
					';
					if ($row['count'])
					{
						$program  .= '
							$this->curl1->addRequestData(\'count\', \''.$row['count'].'\');
						';
					}
					$row['func_name'] = $row['func_name']?$row['func_name']:'show';
					$program .= '
							$this->curl1->setReturnFormat(\'' . $row['return_type'] . '\');
							$this->curl1->addRequestData(\'a\', \''.$row['func_name'].'\');';
					if($row['paras'])
					{
						$tmp = explode(',',$row['paras']);
						foreach($tmp as $k => $v)
						{
							$program .= '$this->curl1->addRequestData(\'' . $v . '\', $this->input["' . $v . '"]);';
						}
					}
					$program .= '$datas = $this->curl1->request(\'' . $row['file_name'] . $row['file_type'] . '\');
					';
					if ($row['return_var'])
					{
						$program  .= '
								$this->tpl->addVar(\'' . $row['return_var'] . '\', $datas);
						';
					}
				}

				$relate_menu = unserialize($module['relate_menu']);
				$_relate_menu = '$relate_menu = array(';
				if ($relate_menu)
				{
					foreach ($relate_menu AS $k => $v)
					{
						$_relate_menu .= $k . '=> \'' . $v . '\',';
					}
				}
				$_relate_menu .= ');';
				$program  .= $_relate_menu . '
					$nav = array(
						\'name\' => \''.$op['name'].'\',
						\'link\' => \'#\'
					);
					$this->append_nav($nav);
					$this->tpl->addVar(\'_nav\', $this->nav);
					$this->tpl->addVar(\'_navdata\', $this->navdata);
					$this->curl->initPostData();
					$this->curl->addRequestData(\'a\', \'__getConfig\');
					$_configs = $this->curl->request(\'' . $op['file_name'] . $op['file_type'] . '\');
					$_configs = $_configs[0];
					$this->tpl->addVar(\'_configs\', $_configs);
					$this->tpl->setSoftVar(\'' . $application['softvar'] . '\'); //设置软件界面
					$this->tpl->addVar(\'' . $module['primary_key']  . '\', $id);
					$this->tpl->addVar(\'hg_commend_fields\', $hg_commend_fields);
					$this->tpl->addVar(\'fixcommendfields\', $fixcommendfields);
					$this->tpl->addVar(\'primary_key\', \'' . $module['primary_key'] . '\');
					$this->tpl->addVar(\'relate_menu\', $relate_menu);
					$hg_set_template = \'' . $op['template'] . '\';
					$hg_set_callback = \'' . $op['callback'] . '\';
					$hg_set_return = \'' . $op['return_var'] . '\';
					$hg_primary_key = \'' . $module['primary_key'] . '\';
				';
				if (!DEVELOP_MODE)
				{
					$program  .= '
						$this->tpl->setTemplateVersion(\'' . $application['softvar'] . '/' . $application['version'] . '\'); 
						$this->tpl->setScriptDir(\'app_' . $application['softvar'] . '/\'); 
					';
				}
				else
				{
					$program  .= '
						$this->tpl->setTemplateVersion(\'\'); 
						$this->tpl->setScriptDir(\'\'); 
					';
				}
				if ($op['op'] != 'recommend')
				{
					if($op['callback'])
					{
						$func = '';
						$func_param = '';
						$var = '';
						$arr_callback = explode(',',$op['callback']);
						foreach($arr_callback as $call_key => $call_value)
						{
							if($call_key)
							{
								$program .=  '$' . $call_value . '  = $this->input[\'' . $call_value . '\'];';
								$var .= '$this->tpl->addVar(\'' . $call_value . '\', $' . $call_value . ');';
								$func_param .= ',$' . $call_value;
							}
							else
							{
								$func = $call_value ;
							}
						}
					}
					$program .= '
					if (is_array($hg_data_return) && !$hg_data_return["ErrorCode"])
					{
						$hg_data_return = $hg_data_return[0];
					}' . $var;
		
					$relate_module = unserialize($module['relate_module']);
					$_relate_module = '$_relate_module = array(';
					if ($relate_module)
					{
						foreach ($relate_module AS $k => $v)
						{
							$_relate_module .= $k . '=> \'' . $v . '\',';
						}
					}
					$_relate_module .= ');';
					$program .= $_relate_module . '
					$this->tpl->addVar(\'_relate_module\', $_relate_module);
					$this->tpl->addVar(\'' . $op['return_var'] . '\', $hg_data_return);
					if($hg_data_return["ErrorCode"])
					{
						$this->tpl->outTemplate(\'' . $op['template']. '\', "' . $func . $func_param . ',error");
					}
					else
					{
						$this->tpl->outTemplate(\'' . $op['template']. '\', "' . $func . $func_param . '");
					}
					';
				}
			}
			else
			{
				$program  .= $log_program;
				if ($op['direct_return'])
				{
					if ($op['callback'])
					{
						if(!$op['exec_callback'])
						{
							$program .= 'if(is_array($hg_data_return) && !$hg_data_return["ErrorCode"])
							{
								$hg_data_return = $hg_data_return[0];
							}';
							$var = '';
							$callback = explode(',',$op['callback']);
							$op['callback'] = $callback[0];
							unset($callback[0]);
							foreach($callback as $call_key => $call_value)
							{
								$program .=  '$' . $call_value . '  = $this->input[\'' . $call_value . '\'];';
								$var .= ', $' . $call_value ;
							}

							$func = $op['callback'] . '(\'".json_encode($hg_data_return)."\'' . $var . ')';

							$program  .= '
										$arr = array("msg"=>"","callback"=>"' . $func . '");
										echo json_encode($arr);
									';

							//$program  .= 'echo \'<script type=text/javascript>' . $func . '</script>\';';
						}
						else
						{
							$program  .= '
							
									$HTTP_HOST = $_SERVER[\'HTTP_HOST\'];
									$info = explode(\':\', $HTTP_HOST);
									$hg_data_return = json_encode($hg_data_return);
									if ($info[1])
									{
										$hg_data_return = str_replace(array(\'img.hoge.cn\', \'vod.hoge.cn\'), array(\'img.hoge.cn:234\', \'vod.hoge.cn:234\'), $hg_data_return);
									}
									';
							$func = $op['callback'] . '(\' . $hg_data_return . \')';
							$program  .= 'echo \'' . $func . '\';';
						}

					}
					else
					{
						$program  .= '
						
								$HTTP_HOST = $_SERVER[\'HTTP_HOST\'];
								$info = explode(\':\', $HTTP_HOST);
								$hg_data_return = json_encode($hg_data_return);
								if ($info[1])
								{
									$hg_data_return = str_replace(array(\'img.hoge.cn\', \'vod.hoge.cn\'), array(\'img.hoge.cn:234\', \'vod.hoge.cn:234\'), $hg_data_return);
								}
								';
						$program  .= 'echo $hg_data_return;';
					}
				}
				else
				{
					if (strpos($op['callback'], '.php'))
					{
						$callback = $op['callback'];
					}
					else if ($op['callback'])
					{
						$func = $op['callback'] . '(\'$id\')';
					}
					$program  .= '
					//file_put_contents(\'1.txt\', var_export($hg_data_return,1));
					if ($hg_data_return)
					{
						$callback = \'' . $callback . '\';

						$url = explode(\'?\', $callback);
						if (!$url[1])
						{
							$url = $url[0] . \'?mid=' . $module_id . '\';
						}
						else
						{
							$url[1] = str_replace(\'&amp;\', \'&\', $url[1]);
							$para = explode(\'&\', $url[1]);
							$url = $url[0] . \'?mid=' . $module_id . '\';
							$hg_d = $hg_data_return[0];
							foreach ($para AS $p)
							{
								$p = explode(\'=\', $p);
								$url .= \'&\' . $p[0] . \'=\' . $hg_d[$p[0]];
							}
						}
						$this->redirect(\'' . $op['name'] . '成功\', $url, 0, 0, "' . $func . '");
					}
					else
					{
						$this->ReportError(\'' . $op['name'] . '失败\', \'\', 0, 0, "' . $func . '");
					}
					';
				}
			}
		}
		$program  .= '?>';
		if (hg_mkdir(CACHE_DIR . 'program/' . $op['op']))
		{
			hg_file_write(CACHE_DIR . 'program/' . $op['op'] . '/' . $module['id'] . '.php', $program);
		}
		else
		{
			exit(CACHE_DIR . 'program/' . $op['op'] . '目录创建失败，请检查目录权限.');
		}
		return $module['id'];
	}

	private function compile_show($module, $application, $type = 'show')
	{
		$module_id = $module['id'];
		$api = $this->cal_api($application, $module);
		$program  = '<?php
			$api = array(
				\'host\' => \'' . $api['host'] . '\',
				\'port\' => \'' . $api['port'] . '\',
				\'dir\' => \'' . $api['dir'] . '\',
				);
			$this->tpl->addVar(\'__api\', urlencode(json_encode($api)));
			$this->curl = new curl(\'' . $api['host'] . '\', \'' . $api['dir'] . '\');
		';
		if ($application['appid'] && $application['appkey'])
		{		
			$program  .= '
		$this->curl->setClient(\'' . $application['appid'] . '\', \'' . $application['appkey'] . '\');
		';
		}
		if ($module['paras'])
		{
		}
		if (0 && $module['is_pub'])
		{
			$program .= '
			if(!class_exists(\'publish\'))
			{
				include_once(ROOT_DIR.\'lib/class/publish.class.php\');
			}
			$publish = new publish();
			';
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "module_append  WHERE module_id=" . $module_id . ' AND op=\'' . $type . '\'';
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$api1 = $this->cal_api($application, $module, $row);
			if ($api1['host'] != $api['host'])
			{
				$program  .= '
						$this->curl1 = new curl(\'' . $api1['host'] . '\', \'' . $api1['dir'] . '\');
				';
			}
			elseif ($api1['dir'] != $api['dir'])
			{
				$program  .= '
						$this->curl1 = new curl(\'' . $api1['host'] . '\', \'' . $api1['dir'] . '\');
				';
			}
			else
			{
				$program  .= '
						$this->curl1 = $this->curl;
				';
			}
			if ($application['appid'] && $application['appkey'])
			{		
				$program  .= '
			$this->curl1->setClient(\'' . $application['appid'] . '\', \'' . $application['appkey'] . '\');
			';
			}
			$program  .= '
			$this->curl1->initPostData();
			$this->curl1->addRequestData(\'trigger_action\', \'show\');
			$this->curl1->addRequestData(\'trigger_mod_uniqueid\', \''.$module['mod_uniqueid'].'\');
			';
			if ($row['count'])
			{
				$program  .= '
					$this->curl1->addRequestData(\'count\', \''.$row['count'].'\');
				';
			}
			$row['func_name'] = $row['func_name']?$row['func_name']:'show';
			$program .= '
					$this->curl1->setReturnFormat(\'' . $row['return_type'] . '\');
					$this->curl1->addRequestData(\'a\', \''.$row['func_name'].'\');';
			if($row['paras'])
			{
				$tmp = explode(',',$row['paras']);
				foreach($tmp as $k => $v)
				{
					$program .= '$this->curl1->addRequestData(\'' . $v . '\', $this->input["' . $v . '"]);';
				}
			}
			$program .= '$datas = $this->curl1->request(\'' . $row['file_name'] . $row['file_type'] . '\');
			';
			if ($row['return_var'])
			{
				$program  .= '
						$this->tpl->addVar(\'' . $row['return_var'] . '\', $datas);
				';
			}
		}
		/*
		$sql = "SELECT * FROM " . DB_PREFIX . "module_node  WHERE module_id=" . $module_id;
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$program  .= '
			include hg_load_node(' . $row['node_id'] . ');
			';
		}*/
		$page_count =intval($module['page_count']);
		if (!$page_count)
		{
			$program .= '
				$height = $this->settings[\'liv_client_info\'][\'h\'];
				if ($height > 250)
				{
					$page_count = intval(($height - 120) / 20);
				}
				else
				{
					$page_count = 10;
				}
				';

			$page_count = '$page_count';
		}
		$program  .= '
			$count = intval($this->input[\'count\']);
			$count = $count ? $count : ' . $page_count . ';
			$this->curl->setSubmitType(\'post\');
			$this->curl->setReturnFormat(\'json\');
			$this->curl->initPostData();
			$this->curl->addRequestData(\'a\', \'__getConfig\');
			$_configs = $this->curl->request(\'' . $module['file_name'] . $module['file_type'] . '\');
			$_configs = $_configs[0];
			$this->tpl->addVar(\'_configs\', $_configs);
			';
		if ($module['is_pages'])
		{
			$program  .= '
				$_page = $page = intval($this->input[\'pp\']);
				';
			$program  .= '
				//切换节点换
				$node_type = intval($this->input[\'node_type\']);
				$_colid = $this->input["_colid"];

				$this->curl->initPostData();
				
				$this->curl->addRequestData(\'trigger_action\',\''.$type.'\');
				$this->curl->addRequestData(\'trigger_mod_uniqueid\',\''.$module['mod_uniqueid'].'\');
				$this->curl->addRequestData(\'a\', \'count\');
				$total = $this->curl->request(\'' . $module['file_name'] . $module['file_type'] . '\');
				$total = intval($total[\'total\']);
				$data = array();
				$data[\'totalpages\']   = $total;
				$data[\'perpage\'] = $count;
				$data[\'curpage\'] = $_page;
				$extralink = \'\';

				foreach ($this->input AS $k => $v)
				{
					if ($k != \'mid\' && $k != \'hg_search\')
					{
					    if($k == \'referto\' && $v){
                            $v = urlencode($v);
                        }
						$extralink .= \'&amp;\' . $k . \'=\' . $v;
					}
				}
				$data[\'pagelink\'] = \'?mid=' . $module['id'] . '\' . $extralink;
				$pagelink = hg_build_pagelinks($data);
				$this->tpl->addVar(\'pagelink\', $pagelink);
				$this->tpl->addVar(\'total\', $total);
			';
		}

		$op = '$op = array(';
		$batch_op = '$batch_op = array(';
		$sql = "SELECT * FROM " . DB_PREFIX . "module_op  WHERE module_id IN (0, " . $module_id . ') AND is_show=1 ORDER BY order_id ASC';
		$query = $this->db->query($sql);
		$ops = array();
		$jsstr = 'var gBatchAction = new Array();';
		while ($row = $this->db->fetch_array($query))
		{
			$request_type = unserialize($row['request_type']);
			if($request_type)
			{
				$row['request_type'] = $request_type[$module_id];
			}
			$ops[$row['op']] = $row;
			if ($row['op'] == 'create')
			{
				continue;
			}
			if($row['op_link'])
			{
				$link = $row['op_link'];
				$pre = '_';
			}
			else
			{
				$link = './run.php?mid=' . $module['id'] . '&a=' . $row['op'];
				$pre = '';
			}
			$event_type = ' onclick';
			$event = 'hg_ajax_post(this, ';
			$batch_event = 'hg_ajax_batchpost';
			$group_op = '';
			if ($row['group_op'])
			{
				$event_type = ' onchange';
				$event = 'hg_ajax_post_select(this, \\\'' . $link . '\\\', ';
				$batch_event = 'hg_ajax_batchpost_select';
				$group_ops = explode("\n", $row['group_op']);
				$group_op = '\'group_op\' => array(';
				foreach ($group_ops AS $v)
				{
					$v = explode('=', $v);
					$group_op .= "'{$v[0]}' => '{$v[1]}',";
				}
				$group_op .= '),';
			}
			$attr = '';
			if ($row['request_type'] == 'ajax')
			{
				$attr =  $event_type . '="return ' . $event . '\\\''. $row['name'] . '\\\', ' . $row['need_confirm'] . ');"';
			}
			else
			{
				if ($row['need_confirm'])
				{
					$attr = $event_type . '="return confirm(\\\'您确认' . $row['name'] . '此条记录吗？\\\');"';
				}
			}
			$op .= "'{$row['op']}' => array('name' =>'{$row['name']}',
					'brief' =>'{$row['brief']}',
					'attr' => '{$attr}',
					'pre' => '{$pre}',
					'link' => '{$link}',
					{$group_op}
					), \r\n";
			if ($row['has_batch'])
			{
				$attr = $event_type . '="return ' . $batch_event . '(this, \\\'' . $row['op'] . '\\\',  \\\'' . $row['name'] . '\\\', ' . $row['need_confirm'] . ', \\\'' . $module['primary_key'] . '\\\', \\\'\\\', \\\'' . $row['request_type']  . '\\\');"';
				$jsstr .= 'gBatchAction[\\\'' . $row['op'] . '\\\'] = "' . $link . '";';
				$batch_op .= "'{$row['op']}' => array('name' =>'{$row['name']}', 'brief' =>'{$row['brief']}', 'attr' => '{$attr}',{$group_op}), \r\n";
			}
		}
		$op .= ');';
		$batch_op .= ');';
		if ($module['template'])
		{
			$program  .= $op . $batch_op . '
					$this->tpl->addVar(\'op\', $op);
					$this->tpl->addVar(\'batch_op\', $batch_op);
			';
		}

		$list = $module['return_var'] ? $module['return_var'] : $module['template'];
		$program  .= '
				$this->curl->setReturnFormat(\'' . $module['return_type'] . '\');
				$this->curl->initPostData();
				$this->curl->addRequestData(\'trigger_action\',\''.$type.'\');
				$this->curl->addRequestData(\'trigger_mod_uniqueid\',\''.$module['mod_uniqueid'].'\');
		';
		
		if ($module['is_pages'])
		{
			$program  .= '
					$this->curl->addRequestData(\'offset\', $page);
			';
		}
		if (!$module['func_name'])
		{
			$module['func_name'] = 'show';
		}
		if ($module['paras'])
		{
			$paras_arr = explode(",",$module['paras']);
			foreach($paras_arr as $key => $value)
			{
				$program  .= '$this->curl->addRequestData(\''.$value.'\', $_REQUEST[\'' . $value . '\']);';
			}
		}
		$program  .= '
				$this->curl->addRequestData(\'count\', $count);
				if(isset($this->input["_colid"]))
				{
					if($conids)
					{
						$this->curl->addRequestData(\'id\', implode(",",$conids["conid"]));
					}
					else
					{
						$this->curl->addRequestData(\'id\', -1);
					}
				}
				else
				{
				}

				$this->curl->addRequestData(\'a\', \'' . $module['func_name'] . '\');
		';

		$program  .= '
				$datas = $this->curl->request(\'' . $module['file_name'] . $module['file_type'] . '\');
		';
		if ($module['template'])
		{
			//处理显示字段
			if ($module['settings']['show'])
			{
				$show_title = $module['settings']['show_title'];
				$pic = $module['settings']['pic'];
				$time = $module['settings']['time'];
				$show_append = $module['settings']['show_append'];
				$show_width = $module['settings']['width'];
				$canorder = $module['settings']['canorder'];
				$list_str = '';

				foreach ($module['settings']['show'] AS $k => $v)
				{
					if (is_array($v))
					{
						foreach ($v AS $kk => $vv)
						{
							if (!$show_title[$k][$kk])
							{
								$show_title[$k][$kk] = $vv;
							}
							if ($show_width[$k][$kk])
							{
								$width = ' width="' . $show_width[$k][$kk] . '"';
							}
							else
							{
								$width = '';
							}
								//处理字段显示表达式
							$exper = $this->deal_append_show($k . '.' . $kk, $show_append[$k][$kk], $pic[$k][$kk]);
							$list_str .= "'{$k}.{$kk}' => array('title' => '{$show_title[$k][$kk]}','pic' => '{$pic[$k][$kk]}','time' => '{$time[$k][$kk]}', 'exper' => '{$exper}', 'width' => '{$width}'),\r\n";
						}
					}
					else
					{
						if (!$show_title[$v])
						{
							$showtext = $v;
						}
						else
						{
							$showtext = $show_title[$v];
						}
						if ($show_width[$v])
						{
							$width = ' width="' . $show_width[$v] . '%"';
						}
						else
						{
							$width = '';
						}
						if ($canorder[$v])
						{
							$showtext = '<a href="./run.php?mid=' . $module['id'] . '&hgorder=' . $v . '&hgupdn=\' . $hgorderby . \'" title="点击\' . $order_clew . \'排列">' . $showtext . '</a>';
						}
						//处理字段显示表达式
						$exper = $this->deal_append_show($v, $show_append[$v], $pic[$v], $module['settings']['title']);

						$tmp_list_str = "'{$v}' => array(
									'title' => '{$showtext}',
									'pic' => '{$pic[$v]}',
									'time' => '{$time[$v]}',
									'exper' => '{$exper}',
									'width' => '{$width}',
									),\r\n";

						$list_str .= $tmp_list_str;
					}
				}
				$list_str = '$list_fields = array(' . $list_str . ");\r\n";
			$program  .= '
				if($this->input[\'hgupdn\'] == \'ASC\')
				{
					$hgorderby = \'DESC\';
					$order_clew = \'倒序\';
				}
				else
				{
					$hgorderby = \'ASC\';
					$order_clew = \'正序\';
				}
				if ($this->input[\'search_hash\'])
				{
					$hgorderby .= \'&amp;search_hash=\' . $this->input[\'search_hash\'];
				}
				if ($this->input[\'_id\'])
				{
					$hgorderby .= \'&amp;_id=\' . $this->input[\'_id\'];
				}
			' . $list_str . '
				$this->tpl->addVar(\'list_fields\', $list_fields);
				';
			}
			hg_add_head_element('js-c',$jsstr);
			$jsstr = hg_add_head_element('echo');
			$relate_module = unserialize($module['relate_module']);
			$_relate_module = '$_relate_module = array(';
			if ($relate_module)
			{
				foreach ($relate_module AS $k => $v)
				{
					$_relate_module .= $k . '=> \'' . $v . '\',';
				}
			}
			$_relate_module .= ');';
			$pub_moduleid = intval($module['relate_molude_id']);
			$pub_moduleid = $pub_moduleid ? $pub_moduleid : $module_id;
			//发布信息列表显示
			if(0 && $module['is_pub'])
			{
				$program  .= '
				$ids = $_ids = array();
				if(!isset($this->input["_colid"]) || !$node_type)
				{
					if($datas)
					{
						foreach($datas as $k=>$v)
						{
							$ids[] = $v[\'' . $module['primary_key'] . '\'];
						}
						$pub_info = $publish->getPublishedCol('.$pub_moduleid.', $ids);
						if($pub_info)
						{
							foreach($datas as $k=>$v)
							{
								$datas[$k][\'pubinfo\'] = $pub_info[$v[\'' . $module['primary_key'] . '\']];
							}
						}
					}

				}
				else
				{
					if($datas)
					{
						foreach($datas as $k=>$v)
						{
							$ids[] = $v[\'' . $module['primary_key'] . '\'];
							$_ids[$v[\'' . $module['primary_key'] . '\']] = $v;
						}
					}
					$pub_info = $publish->getPublishedCol('.$pub_moduleid.', $ids);
					$datas = array();
					if($conids["conid"])
					{
						$_tmp = array();//临时存储id
						foreach ($conids["conid"] AS $k => $v)
						{
							if(!$_ids[$v])
							{
								continue;
							}
							//$_ids[$v]["colname"] = $conids["cid"][$k];
							$_ids[$v]["colname"] = $pub_info[$v][$node_type][$conids["cid"][$k]];
							if(!in_array($v, $_tmp))
							{
								$datas[] = $_ids[$v];
								$_tmp[] = $v;
							}
							else
							{
								$index = array_keys($_tmp, $v);
								$datas[$index[0]]["childs"][] = $_ids[$v];
							}
						}
					}
				}
				if(!class_exists("column"))
				{
					include_once(ROOT_DIR.\'lib/class/column.class.php\');
					$type_search = new column();
					$type_search = $type_search->get_col_type();
					$this->tpl->addVar(\'type_search\', $type_search);
				}
				';
			}
			
			$relate_menu = unserialize($module['relate_menu']);
			$_relate_menu = '$relate_menu = array(';
			if ($relate_menu)
			{
				foreach ($relate_menu AS $k => $v)
				{
					$_relate_menu .= $k . '=> \'' . $v . '\',';
				}
			}
			$_relate_menu .= ');';
			$program  .= $_relate_module . $_relate_menu  . '
				$this->tpl->addHeaderCode(\'' . $jsstr . '\');
				$this->tpl->setSoftVar(\'' . $application['softvar'] . '\'); //设置软件界面
				$this->tpl->addVar(\'' . $list . '\', $datas);
				$this->tpl->addVar(\'module_id\', ' . $module_id . ');
				$this->tpl->addVar(\'_cur_module_name\', \'' . $module['name'] . '\');
				$this->tpl->addVar(\'_m_menu_pos\', ' . $module['menu_pos'] . ');
				$this->tpl->addVar(\'relate_module_id\', ' . intval($module['relate_molude_id']) . ');
				$this->tpl->addVar(\'_relate_module\', $_relate_module);
				$this->tpl->addVar(\'relate_menu\', $relate_menu);
				$this->tpl->addVar(\'primary_key\', \'' . $module['primary_key'] . '\');
			';
			if (!DEVELOP_MODE)
			{
				$program  .= '
					$this->tpl->setTemplateVersion(\'' . $application['softvar'] . '/' . $application['version'] . '\'); 
					$this->tpl->setScriptDir(\'app_' . $application['softvar'] . '/\'); 
				';
			}
			else
			{
				$program  .= '
					$this->tpl->setTemplateVersion(\'\'); 
					$this->tpl->setScriptDir(\'\'); 
				';
			}
			$program  .= '
			$this->tpl->outTemplate(\'' . $module['template']. '\');
				?>
			';
		}
		if (hg_mkdir(CACHE_DIR . 'program/' . $type))
		{
			hg_file_write(CACHE_DIR . 'program/' . $type . '/' . $module['id'] . '.php', $program);
			return $module['id'];
		}
		else
		{
			exit(CACHE_DIR . 'program/' . $type . '目录创建失败，请检查目录权限.');
		}
	}

	private function deal_append_show($key, $append, $is_pic, $is_title = '')
	{
		$str = '{$v';
		foreach (explode('.', $key) AS $v)
		{
			if ($v)
			{
				$str .= '[\'' . $v . '\']';
			}
		}
		$str .= '}';
		if ($is_title == $key)
		{
			$str = '<span class="title">' . $str . '</span>';
		}
		if ($is_pic)
		{
			$str = '<img class="resize" src="' . $str . '" />';
		}
		//表达式处理
		if ($append)
		{
			$str .= preg_replace("/\{\\$([a-zA-Z0-9_\[\]\-\'\$\>]+)[\.]{0,1}([a-zA-Z0-9_\[\]\-\'\.\$\>]*)\}/ise",  "\$this->replace_field_show('\\1', '\\2')", $append);
		}
		return addslashes($str);
	}

	private function replace_field_show($one, $two = '')
	{
		$str = "{\$v['$one']";
		if ($two)
		{
			$append = explode('.', $two);
			foreach($append AS $v)
			{
				$str .= "['$v']";
			}
		}
		return $str . '}';
	}

	private function cal_op_info($module_id, $op)
	{
		if ($op['file_name'])
		{
			$file_name = unserialize($op['file_name']);
			if (!$file_name)
			{
				$file_name = $op['file_name'];
			}
			else
			{
				$file_name = $file_name[$module_id];
			}
			$op['file_name'] = $file_name;
		}

		if ($op['template'])
		{
			$template = unserialize($op['template']);
			if (!$template)
			{
				$template = $op['template'];
			}
			else
			{
				$template = $template[$module_id];
			}
			$op['template'] = $template;
		}
		if ($op['callback'])
		{
			$callback = unserialize($op['callback']);
			if (!$callback)
			{
				$callback = $op['callback'];
			}
			else
			{
				$callback = $callback[$module_id];
			}
			$op['callback'] = $callback;
		}
		if ($op['request_type'])
		{
			$request_type = unserialize($op['request_type']);
			if (!$request_type)
			{
				$request_type = $op['request_type'];
			}
			else
			{
				$request_type = $request_type[$module_id];
			}
			$op['request_type'] = $request_type;
		}
		if ($op['direct_return'])
		{
			$direct_return = unserialize($op['direct_return']);
			if (!$direct_return)
			{
				$direct_return = $op['direct_return'];
			}
			else
			{
				$direct_return = $direct_return[$module_id];
			}
			$op['direct_return'] = $direct_return;
		}
		if ($op['show_pub'])
		{
			$show_pub = unserialize($op['show_pub']);
			if (!$show_pub)
			{
				$show_pub = $op['show_pub'];
			}
			else
			{
				$show_pub = $show_pub[$module_id];
			}
			$op['show_pub'] = $show_pub;
		}
		if ($op['trigger_pub'])
		{
			$trigger_pub = unserialize($op['trigger_pub']);
			if (!$trigger_pub)
			{
				$trigger_pub = $op['trigger_pub'];
			}
			else
			{
				$trigger_pub = $trigger_pub[$module_id];
			}
			$op['trigger_pub'] = $trigger_pub;
		}
		return $op;
	}
	private function compile_form_outerlink($module, $application, $type)
	{
		$module_id = $module['id'];
		$relate_moldue_id = $module['relate_molude_id'];
		$sql = "SELECT * FROM " . DB_PREFIX . "module_op  WHERE module_id IN (0, " . $module_id . ') AND op=\'' . $type .'\' ORDER BY module_id ASC';
		$q = $this->db->query($sql);
		$op = array();
		while ($row = $this->db->fetch_array($q))
		{
			$op = $row;
		}
		$op = $this->cal_op_info($module_id, $op);
	
		$create_update = unserialize($module['create_update']);
		if(!$create_update)
		{
			$create_update[0] = 'create';
			$create_update[1] = 'update';
		}
	
		//默认发布到网站
		if(!$medium_type)
		{
			$medium_type = 1;
		}
		$relate_molude_id = intval($module['relate_molude_id']);
		$pub_moduleid = $relate_molude_id ? $relate_molude_id : $module_id;
		//$sql = "SELECT * FROM " . DB_PREFIX ."publish_fieldmap WHERE moduleid = {$pub_moduleid} AND medium_type = {$medium_type}";
		//$pub = $this->db->query_first($sql);
		$program  = '<?php
		';

		$sql = "SELECT * FROM " . DB_PREFIX . "module_append  WHERE module_id IN (" . $module_id . ', ' . intval($relate_moldue_id) . ') AND op=\'' . $type . "'";
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$api1 = $this->cal_api($application, $module, $row);
			if ($api1['host'] != $api['host'])
			{
				$program  .= '
						$this->curl1 = new curl(\'' . $api1['host'] . '\', \'' . $api1['dir'] . '\');
				';
			}
			elseif ($api1['dir'] != $api['dir'])
			{
				$program  .= '
						$this->curl1 = new curl(\'' . $api1['host'] . '\', \'' . $api1['dir'] . '\');
				';
			}
			else
			{
				$program  .= '
						$this->curl1 = $this->curl;
				';
			}
			if ($application['appid'] && $application['appkey'])
			{		
				$program  .= '
			$this->curl1->setClient(\'' . $application['appid'] . '\', \'' . $application['appkey'] . '\');
			';
			}
			$program  .= '
			$this->curl1->initPostData();
			$this->curl1->addRequestData(\'trigger_action\', \'form_outerlink\');
			$this->curl1->addRequestData(\'trigger_mod_uniqueid\', \''.$module['mod_uniqueid'].'\');
			';
			if ($row['count'])
			{
				$program  .= '
					$this->curl1->addRequestData(\'count\', \''.$row['count'].'\');
				';
			}
			$row['func_name'] = $row['func_name'] ? $row['func_name'] : 'show';
			$program .= '
				$this->curl1->setReturnFormat(\'' . $row['return_type'] . '\');
				$this->curl1->addRequestData(\'a\', \''.$row['func_name'].'\');';
			if($row['paras'])
			{
				$tmp = explode(',',$row['paras']);
				foreach($tmp as $k => $v)
				{
					$program .= '$this->curl1->addRequestData(\'' . $v . '\', $this->input["' . $v . '"]);';
				}
			}
			$program .= '$datas = $this->curl1->request(\'' . $row['file_name'] . $row['file_type'] . '\');
			';
			if ($row['return_var'])
			{
				$program  .= '
						$this->tpl->addVar(\'' . $row['return_var'] . '\', $datas);
				';
			}
		}
		$api = $this->cal_api($application, $module, $op);
		$form_set = unserialize($module['form_set']);
		$module_settings = $module['settings'];
		if ($form_set['order'])
		{
			@asort($form_set['order']);
			$form_set_str = '$form_set = array(';
			foreach ($form_set['order'] AS $k => $v)
			{
				if ($form_set['canedit'][$k])
				{
					$form_set_str .= "'$k' => array(
						'title' => '{$form_set['title'][$k]}',
						'group' => '{$form_set['group'][$k]}',
						'show_type' => '{$form_set['show_type'][$k]}',
						'rowscols' => '{$form_set['rowscols'][$k]}',
						'width' => '{$form_set['width'][$k]}',
						'height' => '{$form_set['height'][$k]}',
					),";
				}
			}
			$form_set_str .= ');';
		}
		$program  .= $form_set_str . '
		$this->curl = new curl(\'' . $api['host'] . '\', \'' . $api['dir'] . '\');
		';
		if ($application['appid'] && $application['appkey'])
		{		
			$program  .= '
		$this->curl->setClient(\'' . $application['appid'] . '\', \'' . $application['appkey'] . '\');
		';
		}
		if ($module['paras'])
		{
		}
		if (!$op['file_name'])
		{
			if (!$op['template'])
			{
				$op['file_name'] = $module['file_name'] . '_update';
			}
			else
			{
				$op['file_name'] = $module['file_name'];
			}
		}
		$sql = "SELECT mn.*, n.return_var, n.primary_key FROM " . DB_PREFIX . "module_node mn LEFT JOIN " . DB_PREFIX . "node n ON mn.node_id=n.id WHERE module_id=" . $module_id . ' AND module_op=\'' .  $type . '\'';
		$query = $this->db->query($sql);
		$node_info = array();
		while ($row = $this->db->fetch_array($query))
		{
			$node_info[$row['node_id']] = $row;
		}
		$module['primary_key'] = $module['primary_key'] ? $module['primary_key'] : 'id';
		if ($op['func_name'])
		{
			$program  .= '
				$id = $this->input[\'' . $module['primary_key'] . '\'];
				$this->curl->setSubmitType(\'post\');
				$this->curl->setReturnFormat(\'json\');
				$this->curl->initPostData();
				$this->curl->addRequestData(\'a\', \'__getConfig\');
				$_configs = $this->curl->request(\'' . $op['file_name'] . $op['file_type'] . '\');
				$_configs = $_configs[0];
				$this->tpl->addVar(\'_configs\', $_configs);
				if ($id)
				{
					$this->curl->initPostData();
					$this->curl->addRequestData(\'a\', \'' . $op['func_name'] . '\');
					$this->curl->addRequestData(\'' . $module['primary_key']  . '\', $id);
					$formdata = $this->curl->request(\'' . $op['file_name'] . $op['file_type'] . '\');
					if ($formdata)
					{
						if(count($formdata) == 1)
						{
							$formdata = $formdata[0];
						}
					}
					if (!$formdata)
					{
						$this->ReportError(\'指定记录不存在或已删除!\');
					}
					$a = \''.$create_update[1].'\';
					$optext = \'更新\';
				}
				else
				{
					$a = \''.$create_update[0].'\';
					$formdata = $this->input;

					$optext = \'增加\';
				}
			';
			if(0 && $op['show_pub'])
			{
				$program  .= '
				if(!class_exists(\'publish\'))
				{
					include_once(ROOT_DIR.\'lib/class/publish.class.php\');
				}
				$publish = new publish();
				$formdata[\'haspub\'] = $publish->getcontentpub('.$pub_moduleid.', $id);
				';
			}
			foreach($node_info AS $nid => $row)
			{
				$var = 'hg_' . $row['return_var'] . '_selected';
				$program  .= '
				$' . $var . ' = $formdata[\'' . $row['primary_key'] . '\'];
				include hg_load_node(' . $row['node_id'] . ');
				';
			}

			if ($op['template'])
			{
				if (!$op['return_var']) //记录日志
				{
					$op['return_var'] = 'formdata';
				}

				$relate_menu = unserialize($module['relate_menu']);
				$_relate_menu = '$relate_menu = array(';
				if ($relate_menu)
				{
					foreach ($relate_menu AS $k => $v)
					{
						$_relate_menu .= $k . '=> \'' . $v . '\',';
					}
				}
				$_relate_menu .= ');';
				$program  .= $_relate_menu . '
					$nav = array(
						\'name\' => $optext,
						\'link\' => \'#\'
					);
					$this->append_nav($nav);
					$this->tpl->addVar(\'_nav\', $this->nav);
					$this->tpl->addVar(\'_navdata\', $this->navdata);
					$this->tpl->setSoftVar(\'' . $application['softvar'] . '\'); //设置软件界面
					$this->tpl->addVar(\'a\', $a);
					$this->tpl->addVar(\'optext\', $optext);
					$this->tpl->addVar(\'form_set\', $form_set);
					$this->tpl->addVar(\'return_var\', \'' . $op['return_var'] . '\');
					$this->tpl->addVar(\'' . $op['return_var'] . '\', $formdata);
					$this->tpl->addVar(\'' . $module['primary_key']  . '\', $id);
					$this->tpl->addVar(\'hg_title\', \'' . $module_settings['title']  . '\');
					$this->tpl->addVar(\'primary_key\', \'' . $module['primary_key'] . '\');
					$this->tpl->addVar(\'relate_menu\', $relate_menu);
				';
				if (!DEVELOP_MODE)
				{
					$program  .= '
						$this->tpl->setTemplateVersion(\'' . $application['softvar'] . '/' . $application['version'] . '\'); 
						$this->tpl->setScriptDir(\'app_' . $application['softvar'] . '/\'); 
					';
				}
				else
				{
					$program  .= '
						$this->tpl->setTemplateVersion(\'\'); 
						$this->tpl->setScriptDir(\'\'); 
					';
				}
			if ($op['callback'])
				{
					$callback = explode(",",$op['callback']);
					$op['callback'] = $callback[0];
					unset($callback[0]);
					if ($callback)
					{
						$jsstr = '';
						foreach ($callback AS $v)
						{
							$v = trim($v);
							if($v == 'id')
							{
								$jsstr .= ",\$id";
							}
							else
							{
								$jsstr .= ',".$this->input[\'' . $v . '\']."';
							}
						}
					}
					$op['callback'] .= $jsstr;
					$program .= '$this->tpl->outTemplate(\'' . $op['template']. '\',"' . $op['callback'] . '");';
				}
				else
				{
					$program .= '$this->tpl->outTemplate(\'' . $op['template']. '\');';
				}
			}
		}
		$program  .= ' ?>';
		if (hg_mkdir(CACHE_DIR . 'program/' . $op['op']))
		{
			hg_file_write(CACHE_DIR . 'program/' . $op['op'] . '/' . $module['id'] . '.php', $program);
			return $module['id'];
		}
		else
		{
			exit(CACHE_DIR . 'program/' . $op['op'] . '目录创建失败，请检查目录权限.');
		}
	}

    private function compile_download($module, $application, $type)
    {
        $module_id = $module['id'];
        $sql = "SELECT * FROM " . DB_PREFIX . "module_op  WHERE module_id IN (0, " . $module_id . ') AND op=\'' . $type . '\' ORDER BY module_id ASC';
        $q = $this->db->query($sql);
        $op = array();
        while ($row = $this->db->fetch_array($q))
        {
            $op = $row;
        }
        if (!$op)
        {
            $program  = '<?php
				$this->ReportError(\'此模块暂不支持此操作\');
			?>';
            if (hg_mkdir(CACHE_DIR . 'program/' . $type))
            {
                hg_file_write(CACHE_DIR . 'program/' . $type . '/' . $module_id  . '.php', $program);
                return $module_id;
            }
            else
            {
                exit(CACHE_DIR . 'program/' . $type . '目录创建失败，请检查目录权限.');
            }
        }
        $op = $this->cal_op_info($module_id, $op);
        $program  = '<?php
		';
        $api = $this->cal_api($application, $module, $op);
        $program  .= '
            $api = array(
                \'host\' => \'' . $api['host'] . '\',
                \'port\' => \'' . $api['port'] . '\',
                \'dir\' => \'' . $api['dir'] . '\',
                );
            $this->tpl->addVar(\'__api\', urlencode(json_encode($api)));
            $this->curl = new curl(\'' . $api['host'] . '\', \'' . $api['dir'] . '\');
		';
        if ($application['appid'] && $application['appkey'])
        {
            $program  .= '$this->curl->setClient(\'' . $application['appid'] . '\', \'' . $application['appkey'] . '\');';
        }
        $program .= '$this->curl->setReponseHeader(true);';


        if (!$op['file_name'])
        {
            $op['file_name'] = $module['file_name'] . '_update';
        }
        $module['primary_key'] = $module['primary_key'] ? $module['primary_key'] : 'id';
        if ($op['func_name'])
        {
            $program  .= '
				$this->curl->setSubmitType(\'post\');
				$this->curl->setReturnFormat(\'json\');
				';
            if($module['primary_key'])
            {
                $program  .= '
				$id = $this->input[\'' . $module['primary_key'] . '\'];
				$this->curl->initPostData();
				$this->curl->addRequestData(\'a\', \'' . $op['func_name'] . '\');
				$this->curl->addRequestData(\'' . $module['primary_key'] . '\', $id);';
                $program  .= '$hg_data_return = $this->curl->request(\'' . $op['file_name'] . $op['file_type'] . '\');
				';
            }
            else
            {
                $program  .= '
				$this->curl->initPostData();
				$this->curl->addRequestData(\'a\', \'' . $op['func_name'] . '\');';
                $program  .= '$hg_data_return = $this->curl->request(\'' . $op['file_name'] . $op['file_type'] . '\');
				';
            }


            if ($module['is_log'] && $op['is_log']) //记录日志
            {
                $log_program = '
                include_once(ROOT_PATH . \'lib/class/log.class.php\');
                $log = new hglog();
                $logcontent = \'' . $op['name'] . '内容\';
                $log->add_log($logcontent, \'' . $op['op'] . '\');
                ';
            }

            $program  .= $log_program;
            $program  .= '
            if ($hg_data_return)
            {
                $header_size = $this->curl->getInfo(\'header_size\');
		        $header = substr($hg_data_return, 0, $header_size);
		        $body = substr($hg_data_return, $header_size);
		        $error = json_decode($body,1);
		        if($error[\'ErrorCode\'])
		        {
			        $this->ReportError($error[\'ErrorCode\']);
		        }
                $header = explode("\r\n", $header);
                foreach ((array)$header as $k => $v)
                {
                	if($v=="Transfer-Encoding: chunked") continue; //解决错误net::ERR_INVALID_CHUNKED_ENCODING
                    if($v)
                    {
                        header($v);
                    }
                }
                echo $body;
                exit();
            }
            else
            {
                $this->ReportError(\'' . $op['name'] . '失败\');
            }
            ';
        }
        $program  .= '?>';
        if (hg_mkdir(CACHE_DIR . 'program/' . $op['op']))
        {
            hg_file_write(CACHE_DIR . 'program/' . $op['op'] . '/' . $module['id'] . '.php', $program);
        }
        else
        {
            exit(CACHE_DIR . 'program/' . $op['op'] . '目录创建失败，请检查目录权限.');
        }
        return $module['id'];
    }

	private function cal_api($application, $module, $module_op = array())
	{
		$api = $application;
		if($module['host'])
		{
			$api['host'] = $module['host'];
			$api['dir'] = $module['dir'];
		}
		if($module_op['host'])
		{
			$api['host'] = $module_op['host'];
			$api['dir'] = $module_op['dir'];
		}
		return $api;
	}

}
?>