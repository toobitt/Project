<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: admin_crontab.php 4820 2013-11-01 02:13:29Z develop_tong $
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'admin_crontab');
require(ROOT_DIR . 'lib/class/cron.class.php');
require('./global.php');
class admin_crontab extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
		if ($this->user['group_type'] >= MAX_ADMIN_TYPE)
		{
			$this->ReportError('对不起，您没有权限管理计划任务!');
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$modules = array();
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'crontab ORDER BY create_time DESC';
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = hg_get_date($row['create_time']);
			$row['run_time'] = hg_get_date($row['run_time'],2,1);
			$row['apifile'] = 'http://' . $row['host'] .($row['port']!= 80 ? ':'.$row['port']:''). '/' . $row['dir'] . $row['file_name'];
			
			$row['is_use'] = $row['is_use'] ? '是' : '否';
			$modules[$row['id']] = $row;
		}
		
		$list_fields = array(
			'id' => array('title' => 'ID', 'exper' => '$v[id]'), 
			'file_name' => array('title' => '脚本地址', 'exper' => '$v[apifile]'),
			'space' => array('title' => '间隔时间', 'exper' => '$v[space]s'),
			'run_time' => array('title' => '下次执行时间', 'exper' => '$v[run_time]'),
			'is_use' => array('title' => '是否启用', 'exper' => '$v[is_use]'),
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



		$crond = new crond();
		$is_run = $crond->isRun();

		hg_add_head_element('js-c',$str);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->addVar('list_fields', $list_fields);
		$this->tpl->addVar('op', $op);
		$this->tpl->addVar('batch_op', $batch_op);
		$this->tpl->addVar('is_run', $is_run);
		$this->tpl->addVar('close_search', true);
		$this->tpl->addVar('primary_key', 'id');
		$this->tpl->addVar('list', $modules);
		$this->tpl->outTemplate('crontab');
	}

	
	public function start()
	{
		$crond = new crond();
		if ($this->settings['croncmd'])
		{
			$crond->setCronCmd($this->settings['croncmd']);
		}
		$pid = $crond->start();
		if($pid)
		{
			$title = '开启成功！';
			$callback = 'hg_stop_start_crontab(true)';
		}
		else
		{
			$title = '开启失败！';
			$callback = 'hg_stop_start_crontab(false)';
		}
		$this->redirect($title, 0, 0, '', $callback);
	}
	
	public function stop()
	{
		$crond = new crond();
		if ($this->settings['croncmd'])
		{
			$crond->setCronCmd($this->settings['croncmd']);
		}
		$pid = $crond->stop();
		if(!$pid)
		{
			$title = '关闭成功！';
			$callback = 'hg_stop_start_crontab(false)';
		}
		else
		{
			$title = '关闭失败！';
			$callback = 'hg_stop_start_crontab(true)';
		}
		$this->redirect($title, 0, 0, '', $callback);
	}

	public function form($message = '')
	{
		$id = intval($this->input['id']);
		if ($id)
		{
			$formdata = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'crontab WHERE id=' . $id);
			if (!$formdata)
			{
				$this->ReportError('指定记录不存在或已删除!');
			}
			$formdata['run_time'] = date('Y-m-d H:i:s',$formdata['run_time']);
			$a = 'update';
			$optext = '更新';
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
		$this->tpl->addVar('optext', $optext);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->addVar('message', $message);
		$this->tpl->addVar('a', $a);
		$this->tpl->outTemplate('crontab_form');
		exit;
	}

	public function create()
	{
		$info = array(
			array('name' => 'host','title'=>'请填写主机地址','is_show' => 1),
			array('name' => 'port','title'=>'请填写端口号','is_show' => 1),
			array('name' => 'dir','title'=>'请填写路径','is_show' => 0),
			array('name' => 'file_name','title'=>'请填写文件名称','is_show' => 1),
			array('name' => 'token','title'=>'请填写token','is_show' => 1),
			array('name' => 'space','title'=>'请填写间隔时间','is_show' => 1),
			array('name' => 'is_log','title'=>'请选择是否需要日志','is_show' => 0)
			);
		$data = array();
		foreach($info as $key=> $value)
		{
			if(!trim($this->input[$value['name']]))
			{
				if($value['is_show'])
				{
					$this->form($value['title']);
				}
				else
				{
					$data[$value['name']] = $this->input[$value['name']];
				}
			}
			else
			{
				$data[$value['name']] = $this->input[$value['name']];
			}
		}
		if ($this->input['run_time'])
		{
			$data['run_time'] = strtotime($this->input['run_time']);
		}
		else
		{
			$data['run_time'] = time();
		}
		$data['admin_id'] = $this->user['id'];
		$data['user_name'] = $this->user['user_name'];
		$data['ip'] = hg_getip();
		$data['create_time'] = TIMENOW;
		hg_fetch_query_sql($data, 'crontab');
		$this->redirect('添加成功');
	//exit;
	}

	public function update()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		$info = array(
			array('name' => 'host','title'=>'请填写主机地址','is_show' => 1),
			array('name' => 'port','title'=>'请填写端口号','is_show' => 1),
			array('name' => 'dir','title'=>'请填写路径','is_show' => 0),
			array('name' => 'file_name','title'=>'请填写文件名称','is_show' => 1),
			array('name' => 'token','title'=>'请填写token','is_show' => 1),
			array('name' => 'space','title'=>'请填写间隔时间','is_show' => 1),
			array('name' => 'is_log','title'=>'请选择是否需要日志','is_show' => 0)
			);
		$data = array();
		foreach($info as $key=> $value)
		{
			if(!trim($this->input[$value['name']]))
			{
				if($value['is_show'])
				{
					$this->form($value['title']);
				}
				else
				{
					$data[$value['name']] = $this->input[$value['name']];
				}
			}
			else
			{
				$data[$value['name']] = $this->input[$value['name']];
			}
		}
		if ($this->input['run_time'])
		{
			$data['run_time'] = strtotime($this->input['run_time']);
		}
		hg_fetch_query_sql($data, 'crontab', 'id=' . $id);
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
			$sql = 'DELETE FROM ' . DB_PREFIX . 'crontab WHERE id IN (' . $ids . ')';
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