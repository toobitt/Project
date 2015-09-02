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
		$fatherid = intval($this->input['father_id']);
		$cond .= ' AND father_id=' . $fatherid;
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'menu WHERE 1' . $cond . ' ORDER BY close asc, order_id ASC, id asc';
		$q = $this->db->query($sql);
		
		while ($row = $this->db->fetch_array($q))
		{
			$row['close'] = $yesno[$row['close']];
			$modules[$row['id']] = $row;
		}
		
		$list_fields = array(
			'id' => array('title' => 'ID', 'exper' => '$v[id]'), 
			'name' => array('title' => '名称', 'exper' => '<a href=\"?father_id={$v[id]}\">{$v[name]}</a>'),
			'url' => array('title' => '链接', 'exper' => '$v[url]'),
			'close' => array('title' => '是否关闭', 'exper' => '$v[close]')
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
		$this->tpl->outTemplate('menu');
	}
	public function form($message = '')
	{
		$id = intval($this->input['id']);
		if ($id)
		{
			$formdata = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'menu WHERE id=' . $id);
			if (!$formdata)
			{
				$this->ReportError('指定记录不存在或已删除!');
			}
			$a = 'update';
			$optext = '更新';
			//查询出当前菜单的father的father
			$sql = "SELECT father_id FROM ".DB_PREFIX."menu WHERE id = '".$id."'";
			$arr = $this->db->query_first($sql);

			if($arr['father_id'])
			{
				$sql = "SELECT father_id FROM ".DB_PREFIX."menu WHERE id = '".$arr['father_id']."'";
				$father = $this->db->query_first($sql);
				$father_id = $father['father_id'];
			}
			else
			{
				$father_id = $arr['father_id'];
			}
		}
		else
		{
			$formdata = $this->input;
			$a = 'create';
			$optext = '添加';
			if($this->input['father_id'])
			{
				$sql = "SELECT father_id FROM ".DB_PREFIX."menu WHERE id = '".$this->input['father_id']."'";
				$father = $this->db->query_first($sql);
				$father_id = $father['father_id'];
			}
			else
			{
				$father_id = 0;
			}
		}
		
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'menu  ORDER BY order_id ASC, id asc';
		$q = $this->db->query($sql);
		$modules = array(0 => ' 无 ');
		while ($row = $this->db->fetch_array($q))
		{
			$modules[$row['id']] = $row['id'] . '_' . $row['name'];
		}
		$this->tpl->addVar('optext', $optext);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->addVar('message', $message);
		$this->tpl->addVar('modules', $modules);
		$this->tpl->addVar('a', $a);
		$this->tpl->outTemplate('menu_form');
		exit;
	}

	public function create()
	{
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->form('请填写名称');
		}
		
		$father_id = intval($this->input['father_id']);
		$module_id = intval($this->input['module_id']);
		if($father_id && !$module_id)
		{
			$this->ReportError('请填写关联模块id');
		}

		//对于非顶级模块，要插入模块标识与应用标识
		$mod_uniqueid = '';
		$app_uniqueid = '';
		if($father_id && $module_id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "modules WHERE id = '" .$module_id. "'";
			$arr = $this->db->query_first($sql);
			$mod_uniqueid = $arr['mod_uniqueid'];
			$app_uniqueid = $arr['app_uniqueid'];
		}

		//检测是否添加过
		$data = array(
			'name' => $name, 	 	
			'module_id' => $this->input['module_id'],
			'mod_uniqueid' => $mod_uniqueid,
			'app_uniqueid' => $app_uniqueid,
			'url' => $this->input['url'],
			'close' => $this->input['close'],
			'order_id' => $this->input['order_id'],
			'module_id' => $this->input['module_id'],
			'class' => $this->input['class'],
			'father_id' => $this->input['father_id'],
			'include_apps'=>$this->input['apps'],
			'`index`'=>$this->input['index'],
		);
		hg_fetch_query_sql($data, 'menu');
		$this->cache->recache('menu');
		$this->redirect('添加成功', '?pp=' . $this->input['pp']);
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

		$father_id = intval($this->input['father_id']);
		$module_id = intval($this->input['module_id']);
		if($father_id && !$module_id)
		{
			$this->ReportError('请填写关联模块id');
		}

		//对于非顶级模块，要插入模块标识与应用标识
		$mod_uniqueid = '';
		$app_uniqueid = '';
		if($father_id && $module_id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "modules WHERE id = '" .$module_id. "'";
			$arr = $this->db->query_first($sql);
			$mod_uniqueid = $arr['mod_uniqueid'];
			$app_uniqueid = $arr['app_uniqueid'];
		}

		$data = array(
			'name' => $name, 	 	
			'module_id' => $this->input['module_id'],
			'mod_uniqueid' => $mod_uniqueid,
			'app_uniqueid' => $app_uniqueid,
			'url' => $this->input['url'],
			'close' => $this->input['close'],
			'module_id' => $this->input['module_id'],
			'order_id' => $this->input['order_id'],
			'class' => $this->input['class'],
			'father_id' => $this->input['father_id'],
			'include_apps'=>$this->input['apps'],
			'`index`'=>$this->input['index'],
		);
		hg_fetch_query_sql($data, 'menu', 'id=' . $id);
		$this->cache->recache('menu');
		$this->redirect('更新成功',  '?father_id=' . $this->input['father_id'] . '&pp=' . $this->input['pp']);
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
			$sql = 'DELETE FROM ' . DB_PREFIX . 'menu WHERE id IN (' . $ids . ')';
			$this->db->query($sql);
			$affect_rows = $this->db->affected_rows();
			$this->cache->recache('menu');
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