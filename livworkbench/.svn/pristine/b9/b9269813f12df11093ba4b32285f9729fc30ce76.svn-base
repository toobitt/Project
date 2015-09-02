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
		$site_id = intval($this->input['id']);
		if(!$site_id)
		{
			$this->ReportError('请重新选择对应客户!');
		}
		else
		{
			$cond .= ' AND site_id=' . $site_id;
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'cloud_site WHERE id=' . $site_id;
		$f = $this->db->query_first($sql);
		if(!$f)
		{
			$this->ReportError('此客户信息不存在或被删除!');
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'cloud WHERE 1' . $cond . ' ORDER BY cloud_id asc';
		$q = $this->db->query($sql);

		while ($row = $this->db->fetch_array($q))
		{
			$row['is_close'] = $yesno[$row['is_close']];
			$modules[$row['cloud_id']] = $row;
		}
		$list_fields = array(
			'id' => array('title' => 'ID', 'exper' => '$v[cloud_id]'), 
			'name' => array('title' => '名称', 'exper' => '$v[cloud_name]'),
			'url' => array('title' => '链接', 'exper' => '$v[remote_host]'),
			'is_close' => array('title' => '是否关闭', 'exper' => '$v[is_close]')
		);
		$op = array(
			'copy' => array(
				'name' =>'复制', 
				'brief' =>'',
				'link' => '?a=copy'),
			'form' => array(
				'name' =>'编辑', 
				'brief' =>'',
				'link' => '?a=form&site_id=' . $site_id),
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
		$this->tpl->addVar('primary_key', 'cloud_id');
		$this->tpl->addVar('list', $modules);
		$this->tpl->addVar('site_id', $site_id);
		$this->tpl->outTemplate('cloud_manage');
	}
	public function form($message = '')
	{
		$site_id = intval($this->input['site_id']);
		if(!$site_id)
		{
			$this->ReportError('请选择指定客户!');
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'cloud_site WHERE id=' . $site_id;
		$f = $this->db->query_first($sql);
		if(!$f)
		{
			$this->ReportError('此客户信息不存在或被删除!');
		}
		$id = intval($this->input['cloud_id']);
		if ($id)
		{
			$formdata = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'cloud WHERE cloud_id=' . $id);
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
		
		$formdata['pwd'] = hg_encript_str($formdata['pwd'],false,$f['custom_appkey']);
		$formdata['localuserpwd'] = hg_encript_str($formdata['localuserpwd'],false);

		$sql = 'SELECT * FROM ' . DB_PREFIX . 'modules  ORDER BY order_id ASC';
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
		$this->tpl->addVar('site_id', $site_id);
		$this->tpl->addVar('a', $a);
		$this->tpl->outTemplate('cloud_manage_form');
		exit;
	}
	
	public function copy()
	{
		$cloud_id = intval($this->input['cloud_id']);
		if(!$cloud_id)
		{
			$this->ReportError('请指定复制对象!');
		}
		else
		{
			$formdata = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'cloud WHERE cloud_id=' . $cloud_id);
			if (!$formdata)
			{
				$this->ReportError('指定记录不存在或已删除!');
			}
		}
		
		$a = 'create';
		$optext = '添加';
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'modules  ORDER BY order_id ASC';
		$q = $this->db->query($sql);
		$modules = array(0 => ' 无 ');
		while ($row = $this->db->fetch_array($q))
		{
			$modules[$row['id']] = $row['id'] . '_' . $row['name'];
		}
		$formdata['cloud_name'] = $formdata['cloud_name'] . '_copy';
		
		$q = $this->db->query('SELECT id,name FROM ' . DB_PREFIX . 'cloud_site WHERE 1');
		$site_info = array(0 => ' 无 ');
		while ($row = $this->db->fetch_array($q))
		{
			$site_info[$row['id']] = $row['name'];
		}
		
		$this->tpl->addVar('optext', $optext);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->addVar('message', $message);
		$this->tpl->addVar('modules', $modules);
		$this->tpl->addVar('site_info', $site_info);
		$this->tpl->addVar('a', $a);
		$this->tpl->outTemplate('cloud_manage_form');
	}

	public function create()
	{
		$name = trim($this->input['cloud_name']);
		if (!$name)
		{
			$this->form('请填写名称');
		}

		$module_id = intval($this->input['module_id']);
		if(!$module_id)
		{
			$this->ReportError('请填写关联模块id');
		}
		
		$site_id = intval($this->input['site_id']);
		if(!$site_id)
		{
			$this->ReportError('请重新选择对应客户!');
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'cloud_site WHERE id=' . $site_id;
		$f = $this->db->query_first($sql);
		if(!$f)
		{
			$this->ReportError('此客户信息不存在或被删除!');
		}
		
		//检测是否添加过
		$data = array(
			'cloud_name' => $name, 	 	
			'site_id' => $site_id,
			'module_id' => $module_id,
			'remote_host' => trim(urldecode($this->input['remote_host'])),
			'remote_dir' => trim(urldecode($this->input['remote_dir'])),
			'remote_file' => trim(urldecode($this->input['remote_file'])),
			'remote_update_file' => trim(urldecode($this->input['remote_update_file'])),
			'remote_node_file' => trim(urldecode($this->input['remote_node_file'])),
			'appid' => intval($this->input['appid']) ?  intval($this->input['appid']) : $f['appid'],
			'appkey' => trim(urldecode($this->input['appkey'])) ? trim(urldecode($this->input['appkey'])) : $f['appkey'],
			'username' => trim(urldecode($this->input['username'])) ? trim(urldecode($this->input['username'])) : $f['username'],
			'pwd' => trim(urldecode($this->input['pwd'])) ? hg_encript_str(trim($this->input['pwd']),true,$f['custom_appkey']) : $f['pwd'],
			'authapi' => trim(urldecode($this->input['authapi'])) ? trim(urldecode($this->input['authapi'])) : $f['authapi'],
			'localusername' => trim(urldecode($this->input['localusername'])) ? trim(urldecode($this->input['localusername'])) : $f['localusername'],
			'localuserpwd' => trim($this->input['localuserpwd']) ? hg_encript_str(trim($this->input['localuserpwd'])) : $f['localuserpwd'],			
			'is_close' => intval($this->input['is_close']),
		);
		hg_fetch_query_sql($data, 'cloud');
		$this->cache->recache('cloud_manage');
		$this->redirect('添加成功', '?id=' . $site_id);
	}

	public function update()
	{
		$id = $this->input['cloud_id'];
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		$name = trim($this->input['cloud_name']);
		if (!$name)
		{
			$this->form('请填写名称');
		}

		$module_id = intval($this->input['module_id']);
		if(!$module_id)
		{
			$this->ReportError('请填写关联模块id');
		}

		
		$site_id = intval($this->input['site_id']);
		if(!$site_id)
		{
			$this->ReportError('请重新选择对应客户!');
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'cloud_site WHERE id=' . $site_id;
		$f = $this->db->query_first($sql);
		if(!$f)
		{
			$this->ReportError('此客户信息不存在或被删除!');
		}
		
		//检测是否添加过
		$data = array(
			'cloud_name' => $name, 	 	
			'site_id' => $site_id,
			'module_id' => $module_id,
			'remote_host' => trim(urldecode($this->input['remote_host'])),
			'remote_dir' => trim(urldecode($this->input['remote_dir'])),
			'remote_file' => trim(urldecode($this->input['remote_file'])),
			'remote_update_file' => trim(urldecode($this->input['remote_update_file'])),
			'remote_node_file' => trim(urldecode($this->input['remote_node_file'])),
			'appid' => intval($this->input['appid']) ?  intval($this->input['appid']) : $f['appid'],
			'appkey' => trim(urldecode($this->input['appkey'])) ? trim(urldecode($this->input['appkey'])) : $f['appkey'],
			'username' => trim(urldecode($this->input['username'])) ? trim(urldecode($this->input['username'])) : $f['username'],
			'pwd' => trim(urldecode($this->input['pwd'])) ? hg_encript_str(trim($this->input['pwd']),true,$f['custom_appkey']) : $f['pwd'],
			'authapi' => trim(urldecode($this->input['authapi'])) ? trim(urldecode($this->input['authapi'])) : $f['authapi'],
			'localusername' => trim(urldecode($this->input['localusername'])) ? trim(urldecode($this->input['localusername'])) : $f['localusername'],
			'localuserpwd' => trim($this->input['localuserpwd']) ? hg_encript_str(trim($this->input['localuserpwd'])) : $f['localuserpwd'],			
			'is_close' => intval($this->input['is_close']),
		);		hg_fetch_query_sql($data, 'cloud', 'cloud_id=' . $id);
		$this->cache->recache('cloud_manage');
		$this->redirect('更新成功', '?id=' . $site_id);
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