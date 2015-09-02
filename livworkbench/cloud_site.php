<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
 ***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'menus');
require('./global.php');
class menus extends uiBaseFrm
{
	function __construct()
	{
		parent::__construct();
		if ($this->user['group_type'] != 1)
		{
			$this->ReportError('对不起，您没有权限管理模块!');
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$modules = array();
		$yesno = array(0 => '否', 1 => '是');
		$cloud_id = intval($this->input['site_id']);
		if($cloud_id)
		{
			$cond .= ' AND id=' . $cloud_id;
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'cloud_site WHERE 1' . $cond . ' ORDER BY id asc';
		$q = $this->db->query($sql);

		while ($row = $this->db->fetch_array($q))
		{
			$row['is_close'] = $yesno[$row['is_close']];
			$modules[$row['id']] = $row;
		}
		$list_fields = array(
			'id' => array('title' => 'ID', 'exper' => '$v[id]'), 
			'name' => array('title' => '名称', 'exper' => '$v[name]'),
			'url' => array('title' => '链接', 'exper' => '$v[authapi]'),
			'is_close' => array('title' => '是否关闭', 'exper' => '$v[is_close]')
		);
		$op = array(
			'show' => array(
				'name' =>'查看', 
				'brief' =>'',
				'link' => './cloud_manage.php?a=show'),
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
		$this->tpl->outTemplate('cloud_site');
	}
	public function form($message = '')
	{
		$id = intval($this->input['id']);
		if ($id)
		{
			$formdata = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'cloud_site WHERE id=' . $id);
			if (!$formdata)
			{
				$this->ReportError('指定记录不存在或已删除!');
			}
			$a = 'update';
			$optext = '更新';
			//查询出当前菜单的father的father
		}
		else
		{
			$formdata = $this->input;
			$a = 'create';
			$optext = '添加';
		}
		$formdata['pwd'] = hg_encript_str($formdata['pwd'],false,$formdata['custom_appkey']);
		$formdata['localuserpwd'] = hg_encript_str($formdata['localuserpwd'],false);
		$this->tpl->addVar('optext', $optext);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->addVar('message', $message);
		$this->tpl->addVar('a', $a);
		$this->tpl->outTemplate('cloud_site_form');
		exit;
	}

	public function create()
	{
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->form('请填写名称');
		}

		//检测是否添加过
		$data = array(
			'name' => $name,
			'custom_appkey' => trim(urldecode($this->input['custom_appkey'])),
			'appid' => intval($this->input['appid']),
			'appkey' => trim(urldecode($this->input['appkey'])),
			'username' => trim(urldecode($this->input['username'])),
			'pwd' => hg_encript_str(trim($this->input['pwd']),true,trim($this->input['custom_appkey'])),
			'localusername' => trim(urldecode($this->input['localusername'])),
			'localuserpwd' => hg_encript_str(trim($this->input['localuserpwd'])),
			'authapi' => trim(urldecode($this->input['authapi'])),
			'is_close' => intval($this->input['is_close']),
		);
		hg_fetch_query_sql($data, 'cloud_site');
		$this->cache->recache('cloud_site');
		$this->redirect('添加成功', '?pp');
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
			$this->form('请填写名称');
		}
		$data = array(
			'name' => $name,
			'custom_appkey' => trim(urldecode($this->input['custom_appkey'])),
			'appid' => intval($this->input['appid']),
			'appkey' => trim(urldecode($this->input['appkey'])),
			'username' => trim(urldecode($this->input['username'])),
			'pwd' => hg_encript_str(trim(urldecode($this->input['pwd'])),true,trim($this->input['custom_appkey'])),
			'localusername' => trim(urldecode($this->input['localusername'])),
			'localuserpwd' => hg_encript_str(trim(urldecode($this->input['localuserpwd']))),
			'authapi' => trim(urldecode($this->input['authapi'])),
			'is_close' => intval($this->input['is_close']),
		);
		hg_fetch_query_sql($data, 'cloud_site', 'id=' . $id);
		$this->cache->recache('cloud_site');
		$this->redirect('更新成功','?pp');
	}


	public function delete()
	{
		$id = $this->input['cloud_id'];
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
			$sql = 'DELETE FROM ' . DB_PREFIX . 'cloud WHERE cloud_id IN (' . $ids . ')';
			$this->db->query($sql);
			$affect_rows = $this->db->affected_rows();
			$this->cache->recache('cloud_manage');
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