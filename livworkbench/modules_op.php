<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'modules');
require('./global.php');
class modules extends uiBaseFrm
{
	private $fields = array();
	function __construct()
	{
		parent::__construct();
		if ($this->user['group_type'] != 1)
		{
			$this->ReportError('对不起，您没有权限管理模块操作!');
		}
		//需要序列化存储的数据字段 表单字段需要和数据库字段一致
		$this->fields = array(
		'file_name',
		'template',
		'callback',
		'request_type',
		'direct_return',
		'show_pub',
		'trigger_pub',
		'is_show',
		'need_confirm',
		'ban',
		);
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$modules_op = array();
		$yesno = array(0 => '否', 1 => '是');
		$this->cache->check_cache('applications');
		$applications = $this->cache->cache['applications'];
		$module_id = intval($this->input['id']);
		if($module_id)
		{
			$where = ' WHERE module_id IN (0,' . $module_id . ')';
		}
		else
		{
			$where = ' WHERE module_id = 0';
		}
		$sql = 'SELECT m.*, a.host AS ahost, a.dir AS adir FROM ' . DB_PREFIX . 'modules m LEFT JOIN ' . DB_PREFIX . 'applications a ON m.application_id=a.id WHERE m.id=' . $module_id;
		$modules = $this->db->query_first($sql);

		if (!$modules['host'])
		{
			$host = $modules['ahost'];
		}
		else
		{
			$host = $modules['host'];
		}
		if (!$modules['dir'])
		{
			$dir = $modules['adir'];
		}
		else
		{
			$dir = $modules['dir'];
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'module_op ' . $where . ' ORDER BY order_id ASC';
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			if (!$row['host'])
			{
				$row['host'] = $host;
			}
			if (!$row['dir'])
			{
				$row['dir'] = $dir;
			}
			if ($row['file_name'])
			{
				$file_name = unserialize($row['file_name']);
				if (!$file_name)
				{
					$file_name = $row['file_name'];
				}
				else
				{
					$file_name = $file_name[$module_id];
				}
				$row['file_name'] = $file_name;
			}

			if ($row['template'])
			{
				$template = unserialize($row['template']);
				if (!$template)
				{
					$template = $row['template'];
				}
				else
				{
					$template = $template[$module_id];
				}
			}
			else
			{
				$template = $row['template'];
			}
			if (!$row['file_name'])
			{
				if (!$row['template'])
				{
					$row['file_name'] = $modules['file_name'] . '_update';
				}
				else
				{
					$row['file_name'] = $modules['file_name'];
				}
			}

			$row['template'] = $template;
			if ($row['callback'])
			{
				$callback = unserialize($row['callback']);
				if (!$callback)
				{
					$callback = $row['callback'];
				}
				else
				{
					$callback = $callback[$module_id];
				}
			}
			else
			{
				$callback = $row['callback'];
			}
			$row['callback'] = $callback;
			if ($row['request_type'])
			{
				$request_type = unserialize($row['request_type']);
				if (!$request_type)
				{
					$request_type = $row['request_type'];
				}
				else
				{
					$request_type = $request_type[$module_id];
				}
			}
			else
			{
				$request_type = $row['request_type'];
			}
			$row['request_type'] = $request_type;
			if ($row['direct_return'])
			{
				$direct_return = unserialize($row['direct_return']);
				if (!$direct_return)
				{
					$direct_return = $row['direct_return'];
				}
				else
				{
					$direct_return = $direct_return[$module_id];
				}
			}
			else
			{
				$direct_return = $row['direct_return'];
			}
			$row['direct_return'] = $direct_return;

			$row['apifile'] = 'http://' . $row['host'] . '/' . $row['dir'] . $row['file_name'] . $row['file_type'];
			$row['create_time'] = hg_get_date($row['create_time']);
			$row['nameop'] = $row['name'] . '(' . $row['op'] . ')';
			if ($template)
			{
				$row['nameop'] .= '<br />Tpl:' . $template;
			}
			if ($callback)
			{
				$row['nameop'] .= ', callback:' . $callback;
			}
			$row['has_batch'] = $yesno[$row['has_batch']];
			$row['is_show'] = $yesno[$row['is_show']];
			if($row['module_id'])
			{
				$row['module'] = $modules['name'];
			}
			else
			{
				$row['module'] = '全局';
			}
			$modules_op[$row['id']] = $row;
		}

		$list_fields = array(
			'id' => array('title' => 'ID', 'exper' => '$v[id]'),
			'nameop' => array('title' => '名称', 'exper' => '$v[nameop]'),
			'apifile' => array('title' => '接口', 'exper' => '$v[apifile]'),
			'func_name' => array('title' => '方法名', 'exper' => '$v[func_name]'),
			'has_batch' => array('title' => '批', 'brief' => '是否支持批量操作', 'exper' => '$v[has_batch]'),
			'is_show' => array('title' => '显示', 'exper' => '$v[is_show]'),
			//'module' => array('title' => '全局', 'exper' => '$v[module]'),
			);
		$op = array(
			'form' => array(
				'name' =>'编辑',
				'brief' =>'',
				'link' => '?a=form&module_id=' . $module_id),
			'delete' => array(
				'name' =>'删除',
				'brief' =>'',
				'attr' =>' onclick="return hg_ajax_post(this, \'删除\', 1);"',
				'link' => '?a=delete'),
			'append' => array(
				'name' =>'关联数据',
				'brief' =>'',
				'link' => '?a=append&module_id=' . $module_id),
			);
		$batch_op = array(
			'delete' => array(
				'name' =>'删除',
				'brief' =>'',
				'attr' =>' onclick="return hg_ajax_batchpost(this, \'delete\', \'删除\', 1);"',
				),
			);
		$str = 'var gBatchAction = new Array();gBatchAction[\'delete\'] = \'?a=delete\';';
		hg_add_head_element('js-c',$str);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->addVar('module_id', $module_id);
		$this->tpl->addVar('modules', $modules);
		$this->tpl->addVar('list_fields', $list_fields);
		$this->tpl->addVar('op', $op);
		$this->tpl->addVar('batch_op', $batch_op);
		$this->tpl->addVar('close_search', true);
		$this->tpl->addVar('primary_key', 'id');
		$this->tpl->addVar('list', $modules_op);
		$this->tpl->outTemplate('modules_op');
	}

	public function form($message = '')
	{
		$id = intval($this->input['id']);
		$module_id = intval($this->input['module_id']);
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'modules WHERE id=' . $module_id;
		$modules = $this->db->query_first($sql);
		if ($id)
		{
			$formdata = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'module_op WHERE id=' . $id);
			//print_r($formdata);exit;
			if (!$formdata)
			{
				$this->ReportError('指定记录不存在或已删除!');
			}
			/*$formdata['ban'] = unserialize($formdata['ban']); //禁用某操作以窜行化数组纪录
			if ($formdata['ban'] && in_array($module_id, $formdata['ban']))
			{
				$formdata['ban'] = 1;
			}
			else
			{
				$formdata['ban'] = 0;
			}
			foreach($this->fields as $field)
			{
				if ($formdata[$field])
				{
					$$field = unserialize($formdata[$field]);
					if (!$$field)
					{
						$$field = $formdata[$field];
					}
					else
					{
						$_field = $$field;
						$$field = $_field[$module_id];
					}
					$formdata[$field] = $$field;
				}
			}*/
			$a = 'update';
			$optext = '更新';
		}
		else
		{
			$formdata = $this->input;
			$formdata['file_type'] = '.php';
			$formdata['primary_key'] = 'id';
			$a = 'create';
			$optext = '添加';
		}
		//$formdata['is_global'] = $formdata['module_id'] ? 0 : 1;
		//print_r($formdata);exit;
		$request_types = array('ajax' => 'ajax请求', 'post' => 'post请求');
		$this->tpl->addVar('request_types', $request_types);
		$this->tpl->addVar('module_id', $module_id);
		$this->tpl->addVar('modules', $modules);
		$this->tpl->addVar('optext', $optext);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->addVar('message', $message);
		$this->tpl->addVar('a', $a);
		$this->tpl->outTemplate('modules_op_form');
		exit;
	}

	public function create()
	{
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->form('请填写名称');
		}
		//$is_global = intval($this->input['is_global']);
		$module_id = intval($this->input['module_id']);

		foreach($this->fields as $v)
		{
			$$v = trim(urldecode($this->input[$v]));
		}
		/*if ($is_global)
		{
			$module_id = 0;
		}

		$ban = array();
		$isban = intval($this->input['ban']);
		if ($isban)
		{
			$ban[$module_id] = $module_id;
		}
		$ban = serialize($ban);
		*/
		//检测是否添加过
		$data = array(
			'name' => $name,
			'module_id' => $module_id,
			'op' => $this->input['op'],
			'brief' => $this->input['brief'],
			'host' => $this->input['host'],
			'dir' => $this->input['dir'],
			'file_name' => $file_name,
			'func_name' => $this->input['func_name'],
			'template' => $template,
			'has_batch' => $this->input['has_batch'],
			'need_confirm' => $this->input['need_confirm'],
			'callback' => $callback,
			'order_id' => $this->input['order_id'],
			'request_type' => $request_type,
			'direct_return' => $direct_return,
			'group_op' => $this->input['group_op'],
			'is_show' => $this->input['is_show'],
			'trigger_pub' => $trigger_pub,
			'show_pub' => $show_pub,
			'ban' => intval($this->input['ban']),
			'create_time' => TIMENOW,
		);
		hg_fetch_query_sql($data, 'module_op');
		$this->redirect('添加成功');
	}

	public function update()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->form('请填写名称');
		}

		$op =  $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'module_op WHERE id=' . $id);
		if (!$op)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}

		$module_id = intval($this->input['module_id']);
		foreach($this->fields as $field)
		{
			$$field = trim(urldecode($this->input[$field]));
			/*$$field = unserialize($op[$field]);
			if (!$$field)
			{
				$$field = array();
			}
			$_field = $$field;
			if ($this->input[$field])
			{
				$_field[$module_id] = $this->input[$field];
			}
			else
			{
				unset($_field[$module_id]);
			}
			if ($_field)
			{
				$$field = serialize($_field);
			}
			else
			{
				$$field = '';
			}*/
		}
		/*$ban = unserialize($op['ban']); //禁用某操作以窜行化数组纪录
		$isban = intval($this->input['ban']);
		if ($isban)
		{
			$ban[$module_id] = $module_id;
		}
		else
		{
			unset($ban[$module_id]);
		}
		$is_global = intval($this->input['is_global']);
		if ($is_global)
		{
			$module_id = 0;
		}
		$ban = serialize($ban);//禁用某操作以窜行化数组纪录
		*/
		//检测是否添加过
		$data = array(
			'name' => $name,
			'module_id' => $module_id,
			'op' => $this->input['op'],
			'brief' => $this->input['brief'],
			'host' => $this->input['host'],
			'dir' => $this->input['dir'],
			'file_name' => $file_name,
			'func_name' => $this->input['func_name'],
			'template' => $template,
			'has_batch' => $this->input['has_batch'],
			'need_confirm' => $this->input['need_confirm'],
			'callback' => $callback,
			'request_type' => $request_type,
			'direct_return' => $direct_return,
			'group_op' => $this->input['group_op'],
			'trigger_pub' => $trigger_pub,
			'show_pub' => $show_pub,
			'is_show' => $this->input['is_show'],
			'ban' => intval($this->input['ban']),
			'order_id' => $this->input['order_id'],
		);
		hg_fetch_query_sql($data, 'module_op', 'id=' . $id);

		include(ROOT_PATH . 'lib/class/program.class.php');
		$program = new program();
		$id = $program->compile($module_id, $data['op']);
		$this->redirect('更新成功');
	}

	public function delete()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		$id = explode(',', $id);
		$ids = array();
		foreach ($id AS $v)
		{
			if ($v)
			{
				$ids[] = $v;
			}
		}
		if ($ids)
		{
			$ids = implode(',', $ids);
			$sql = 'DELETE FROM ' . DB_PREFIX . 'module_op WHERE id IN (' . $ids . ')';
			$this->db->query($sql);
			$affect_rows = $this->db->affected_rows();
			$this->redirect('成功删除' . $affect_rows . '条记录', 0, 0, '', 'hg_remove_row("' . $ids . '")');
		}
		else
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
	}

	public function append()
	{
		$opid = intval($this->input['id']);
		$module_id = intval($this->input['module_id']);
		if(!$module_id)
		{
			$this->ReportError('模块ID不存在!');
		}
		$op = trim(urldecode($this->input['op']));
		if(!$op)
		{
			if(!$opid)
			{
				$this->ReportError('指定记录不存在或已删除!');
			}
			$sql = 'SELECT op FROM '.DB_PREFIX.'module_op WHERE id = '.$opid;
			$op = $this->db->query_first($sql);
			$op = $op['op'];
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'module_append WHERE module_id = '.$module_id.' AND op = "'.$op.'"';
		$q = $this->db->query($sql);
		$appends = array();
		while($row = $this->db->fetch_array($q))
		{
			$appends[$row['id']] = $row;
		}
		$this->tpl->addVar('module_id', $module_id);
		$this->tpl->addVar('op', $op);
		$this->tpl->addVar('formdata', $appends);
		$this->tpl->outTemplate('module_appends');
		exit;
	}
	function doappend()
	{
		$module_id = intval($this->input['module_id']);
		$opid = intval($this->input['id']);
		$op = trim(urldecode($this->input['op']));
		$sql = 'SELECT * FROM '.DB_PREFIX.'module_append WHERE module_id = '.$module_id . ' AND op = "'.$op.'"';
		$q = $this->db->query($sql);
		$appends = array();
		while($row = $this->db->fetch_array($q))
		{
			$appends[$row['id']] = $row;
		}
		$file_name = $this->input['file_name'];
		$host = $this->input['host'];
		$dir = $this->input['dir'];
		$func_name = $this->input['func_name'];
		$paras = $this->input['paras'];
		$return_type = $this->input['return_type'];
		$return_var = $this->input['return_var'];
		$count = $this->input['count'];
		if(!empty($appends))
		{
			foreach($appends as $k=>$v)
			{
				if($file_name[$k])
				{
					$sql = 'UPDATE '.DB_PREFIX.'module_append
					SET module_op_id="'.$opid.'",
					 module_id="'.$module_id.'",
					 host="'.$host[$k].'",
					 op="'.$op.'",
					 dir="'.$dir[$k].'",
					 file_name="'.$file_name[$k].'",
					 func_name="'.$func_name[$k].'",
					 paras="'.$paras[$k].'",
					 return_type="'.$return_type[$k].'",
					 return_var="'.$return_var[$k].'",
					 count="'.$count[$k].'" WHERE id =
					'.intval($k);
				}
				else
				{
					$sql = 'DELETE FROM '.DB_PREFIX.'module_append WHERE id = '.intval($k);
				}
				//echo $sql;
				$this->db->query($sql);
			}
		}
		//print_r($this->input);
		$add_file_name = $this->input['add_file_name'];
		$add_host = $this->input['add_host'];
		$add_dir = $this->input['add_dir'];
		$add_func_name = $this->input['add_func_name'];
		$add_paras = $this->input['add_paras'];
		$add_return_type = $this->input['add_return_type'];
		$add_return_var = $this->input['add_return_var'];
		$add_count = $this->input['add_count'];
		if(!empty($add_file_name))
		{
			$sql = 'INSERT INTO '.DB_PREFIX.'module_append(module_id,module_op_id,op,host,dir,file_name,func_name,paras,return_type,return_var,count) values';
			foreach($add_file_name as $k=>$v)
			{
				$sql .= '(';
				$sql .= $module_id.','.$opid.',"'.$op.'","'.$add_host[$k].'","'.$add_dir[$k].'","'.$add_file_name[$k].'","'.$add_func_name[$k].'","'.$add_paras[$k].'","'.$add_return_type[$k].'","'.$add_return_var[$k].'","'.$add_count[$k].'"';
				$sql .= '),';
			}
			//echo trim($sql, ',');
			$this->db->query(trim($sql, ','));
		}
		$this->redirect('编辑成功');
	}
	function cancellGlobalOp()
	{
		if(!$this->cancellSerializeField())
		{
			$this->reporterror('取消序列化字段失败！');
		}
		$table_fields = array('op', 'group_op', 'has_batch', 'name', 'brief', 'host', 'dir', 'token', 'file_name', 'file_type', 'func_name', 'paras', 'template', 'return_type', 'return_var', 'need_confirm', 'order_id', 'request_type', 'is_log', 'is_show', 'callback', 'create_time', 'relate_node', 'ban', 'op_link', 'direct_return', 'exec_callback', 'fetch_lastdata', 'trigger_pub', 'show_pub');
		$op = array();
		$sql = 'SELECT * FROM '.DB_PREFIX.'module_op WHERE module_id = 0';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$mids = array();
			foreach($this->fields as $field)
			{
				$$field = array();
				if($tmp = unserialize($row[$field]))
				{
					$$field = $tmp;
					$mids = array_merge(array_keys($tmp), $mids);
				}
			}
			$mids = array_unique($mids);
			if(is_array($mids) && $mids)
			{
				foreach($mids as $_mid)
				{
					/*
					$this->fields = array(
					'file_name',
					'template',
					'callback',
					'request_type',
					'direct_return',
					'show_pub',
					'trigger_pub',
					'is_show',
					'need_confirm',
					);
					*/
					$sql = 'INSERT INTO '.DB_PREFIX.'module_op SET ';
					foreach($table_fields as $field)
					{
						if(in_array($field, $this->fields))
						{
							$_serialize_data = $$field;
							if($field == 'ban')
							{
								$isban = $_serialize_data[$_mid] ? 1 : 0;
								$sql .= "`{$field}` = '{$isban}',";
							}
							else
							{
								$sql .= "`{$field}` = '{$_serialize_data[$_mid]}',";
							}
						}
						else
						{
							$sql .= "`{$field}` = '{$row[$field]}',";
						}
					}
					$sql .= 'module_id = '.$_mid;
					//echo $sql . '<br />';
					$this->db->query($sql);
				}
			}
			else
			{
				//全局操作但是非序列化存储的操作添加至默认模块
				$owner = array(31,20,51,33);
				foreach($owner as $mmid)
				{
					$sql = 'INSERT INTO '.DB_PREFIX.'module_op SET ';
					foreach($table_fields as $field)
					{
						if($field == 'ban')
						{
							$_serialize_data = unserialize($row['ban']);
							$isban = $_serialize_data[$_mid] ? 1 : 0;
							$sql .= "`{$field}` = '{$isban}',";
						}
						else
						{
							$sql .= "`{$field}` = '{$row[$field]}',";
						}
					}
					//echo $sql .= 'module_id = '.$mmid . '<br>';
					$sql .= 'module_id = '.$mmid;
					$this->db->query($sql);
				}
			}
		}
		$this->db->query('DELETE FROM '.DB_PREFIX.'module_op WHERE module_id = 0');
		if($this->db->affected_rows())
		{
			exit("取消全局操作成功！");
		}
		else
		{
			exit("不存在全局操作！");
		}
	}
	function cancellSerializeField()
	{
		//$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		//$count = $this->input['count'] ? intval($this->input['count']) : 50;
		//$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT * FROM '.DB_PREFIX.'module_op WHERE module_id != 0 order by id desc';
		$q = $this->db->query($sql);
		//unset($this->fields['ban']);
		while($row = $this->db->fetch_array($q))
		{
			$sql = 'UPDATE '.DB_PREFIX.'module_op SET ';
			foreach($this->fields as $field)
			{
				if(($_unserial_field = unserialize($row[$field]))!== false)
				{
					if($field == 'ban')
					{
						$isban = $_unserial_field[$row['module_id']] ? 1 : 0;
						$sql .= "`{$field}`='{$isban}',";
					}
					else
					{
						$sql .= "`{$field}`='{$_unserial_field[$row['module_id']]}',";
					}
				}
				else
				{
					if($field == 'ban')
					{
						$sql .= "`{$field}`='0',";
					}
					else
					{
						$sql .= "`{$field}`='{$row[$field]}',";
					}
				}
			}
			//echo trim($sql, ',') . ' WHERE id = '.$row['id'];
			$this->db->query(trim($sql, ',') . ' WHERE id = '.$row['id']);
		}
		return true;
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>