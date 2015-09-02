<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'node');
require('./global.php');
class node extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
		if ($this->user['group_type'] != 1)
		{
			$this->ReportError('对不起，您没有权限管理节点!');
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$modules = array();
		$sql = 'SELECT n.*, a.host AS ahost, a.dir AS adir, a.name AS aname  FROM ' . DB_PREFIX . 'node n LEFT JOIN ' . DB_PREFIX . 'applications a ON n.application_id = a.id ORDER BY order_id ASC';
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = hg_get_date($row['create_time']);
			if (!$row['host'])
			{
				$row['host'] = $row['ahost'];
			}
			if (!$row['dir'])
			{
				$row['dir'] = $row['adir'];
			}
			$row['apifile'] = 'http://' . $row['host'] . '/' . $row['dir'] . $row['file_name'] . $row['file_type'];
			$row['return_var'] = '<span title="初始化选中数据时，定义 $hg_' . $row['return_var'] . '_selected = (选中值，多个使用数组);变量">$hg_' . $row['return_var'] . '</span>';
			$row['application_id'] = $row['aname'];
			$modules[$row['id']] = $row;
		}
		
		$list_fields = array(
			'id' => array('title' => 'ID', 'exper' => '$v[id]'), 
			'name' => array('title' => '名称', 'exper' => '$v[name]'),
			'application_id' => array('title' => '所属系统', 'exper' => '$v[application_id]'),
			'apifile' => array('title' => '接口文件', 'exper' => '$v[apifile]'),
			'func_name' => array('title' => '方法名', 'exper' => '$v[func_name]'),
			'return_var' => array('title' => '返回变量', 'exper' => '$v[return_var]'),
			'create_time' => array('title' => '创建时间', 'exper' => '$v[create_time]')
			);
		$op = array(
			'form' => array(
				'name' =>'编辑', 
				'brief' =>'',
				'link' => '?a=form'),
			'delete' => array(
				'name' =>'删除', 
				'brief' =>'',
				'attr' =>' onclick="return hg_ajax_post(this, \'删除\', 1);"',
				'link' => '?a=delete'),
			); 
		$batch_op = array(
			'delete' => array(
				'name' =>'删除', 
				'brief' =>'',
				'attr' =>' onclick="return hg_ajax_batchpost(this, \'delete\', \'删除\', 1,\'\',\'\',\'ajax\');"',
				),
			); 
		$str = 'var gBatchAction = new Array();gBatchAction[\'delete\'] = \'?a=delete\';';
		hg_add_head_element('js-c',$str);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->addVar('list_fields', $list_fields);
		$this->tpl->addVar('op', $op);
		$this->tpl->addVar('batch_op', $batch_op);
		$this->tpl->addVar('close_search', true);
		$this->tpl->addVar('primary_key', 'id');
		$this->tpl->addVar('list', $modules);
		$this->tpl->outTemplate('node');
	}

	public function form($message = '')
	{
		$id = intval($this->input['id']);
		if ($id)
		{
			$formdata = $this->db->query_first('SELECT n.*,mn.module_id FROM ' . DB_PREFIX . 'node n LEFT JOIN '.DB_PREFIX.'module_node mn ON n.id = mn.node_id WHERE n.id=' . $id);
			if (!$formdata)
			{
				$this->ReportError('指定记录不存在或已删除!');
			}
			$a = 'update';
			$optext = '更新';
			//执行接口程序
			
			$this->cache->check_cache('applications');
			$applications = $this->cache->cache['applications'];
			$host = $formdata['host'];
			$dir = $formdata['dir'];
			$token = $formdata['token'];
			if (!$host)
			{
				$host = $applications[$formdata['application_id']]['host'];
			}
			if (!$dir)
			{
				$dir = $applications[$formdata['application_id']]['dir'];
			}
			if (!$token)
			{
				$token = $applications[$formdata['application_id']]['token'];
			}
		}
		else
		{
			$formdata = $this->input;
			if (!$formdata['file_type'])
			{
				$formdata['file_type'] = '.php';
			}
			$a = 'create';
			$optext = '添加';
		}
		$this->cache->check_cache('applications');
		$applications = array();
		foreach ($this->cache->cache['applications'] AS $k => $v)
		{
			$applications[$k] = $v['name'];
		}
		$this->tpl->addVar('optext', $optext);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->addVar('message', $message);
		$this->tpl->addVar('applications', $applications);
		$this->tpl->addVar('a', $a);
		$this->tpl->outTemplate('node_form');
		exit;
	}

	public function create()
	{
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->form('<font color="red">请填写名称</font>');
		}
		
		$module_id = trim($this->input['module_id']);
		if(!$module_id)
		{
			$this->form('<font color="red">请填写该节点关联的模块id</font>');
		}

		$application_id = intval($this->input['application_id']);
		//检测是否添加过
		$data = array(
			'name' => $name, 	
			'brief' => $this->input['brief'], 	
			'application_id' => $application_id, 	
			'file_name' => $this->input['file_name'], 	
			'func_name' => $this->input['func_name'], 
			'node_uniqueid' => $this->input['node_uniqueid'], 
			'host' => $this->input['host'], 
			'dir' => $this->input['dir'],  
			'token' => $this->input['token'],  	
			'template' => $this->input['template'], 
			'return_var' => $this->input['return_var'], 
			'order_id' => $this->input['order_id'], 	
			'create_time' => TIMENOW, 	
		);
		hg_fetch_query_sql($data, 'node');
		$node_id = $this->db->insert_id();

		//根据关联模块id查出模块标识
		$sql = "SELECT * FROM ".DB_PREFIX."modules WHERE id = '".$module_id."'";
		$arr = $this->db->query_first($sql);
		$mod_uniqueid = '';
		if($arr['id'])
		{
			$mod_uniqueid = $arr['mod_uniqueid'];
		}

		$module_node_data = array(
			'module_id' => $module_id,
			'mod_uniqueid' => $mod_uniqueid,
			'node_id' => $node_id,
		);
		hg_fetch_query_sql($module_node_data, 'module_node');
		$this->redirect('添加成功');
	}

	public function update()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->form('<font color="red">请填写名称</font>');
		}

		$module_id = trim($this->input['module_id']);
		if(!$module_id)
		{
			$this->form('<font color="red">请填写该节点关联的模块id</font>');
		}

		$application_id = intval($this->input['application_id']);
		//检测是否添加过
		$data = array(
			'name' => $name, 	
			'brief' => $this->input['brief'], 	
			'application_id' => $application_id, 	
			'file_name' => $this->input['file_name'], 	
			'func_name' => $this->input['func_name'], 	
			'template' => $this->input['template'], 
			'host' => $this->input['host'], 
			'dir' => $this->input['dir'],  
			'token' => $this->input['token'], 
			'return_var' => $this->input['return_var'],
			'order_id' => $this->input['order_id'], 	
		);
		hg_fetch_query_sql($data, 'node', 'id=' . $id);
		
		//根据关联模块id查出模块标识
		$sql = "SELECT * FROM ".DB_PREFIX."modules WHERE id = '".$module_id."'";
		$arr = $this->db->query_first($sql);
		$mod_uniqueid = '';
		if($arr['id'])
		{
			$mod_uniqueid = $arr['mod_uniqueid'];
		}

		$module_node_data = array(
			'module_id' => $module_id,
			'mod_uniqueid' => $mod_uniqueid,
			'node_id' => $id,
		);
		hg_fetch_query_sql($module_node_data, 'module_node', 'node_id=' . $id);
		
		include_once(ROOT_PATH . 'lib/class/node.class.php');
		$program = new nodeapi();
		$program->compile($id);
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
			$sql = 'DELETE FROM ' . DB_PREFIX . 'node WHERE id IN (' . $ids . ')';
			$this->db->query($sql);
			//再删除module_node的对应数据
			$sql = "DELETE FROM " . DB_PREFIX . "module_node WHERE node_id IN (" . $ids . ")";
			$this->db->query($sql);
			$affect_rows = $this->db->affected_rows();
			$this->redirect('成功删除' . $affect_rows . '条记录', 0, 0, '', 'hg_remove_row("' . $ids . '")');
		}
		else
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>