<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'log');
require('./global.php');
class log extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
		if ($this->user['group_type'] >= MAX_ADMIN_TYPE) //非管理员，验证权限
		{
			$this->ReportError('对不起，您没有权限查看日志!');
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$count = intval($this->input['count']);
		$count = $count ? $count : 20;
		$extralink = '';
		if ($this->input['count'])
		{
			$extralink .= '&amp;count=' . $this->input['count'];
		}
		if ($this->input['hgorder'])
		{
			$extralink .= '&amp;hgorder=' . $this->input['hgorder'];
		}
		if ($this->input['hgupdn'])
		{
			$extralink .= '&amp;hgupdn=' . $this->input['hgupdn'];
		}
		$condition = array();
		if($this->input['type'])
		{
			$condition[] = ' type = ' . $this->input['type'];
			$extralink .= '&amp;type=' . $this->input['type'];
		}
		if($this->user['group_type'] != 1)
		{
			$condition[] = ' group_type != 1';
		}
		if ($condition)
		{
			$conditions = ' WHERE ' . implode(',', $condition);
		}
		$page = intval($this->input['pp']);
		$sql = 'SELECT count(*) AS total FROM ' . DB_PREFIX . 'log'
						. $conditions;
		$total = $this->db->query_first($sql);
		$total = intval($total['total']);
		$data = array();
		$data['totalpages'] = $total;
		$data['perpage'] = $count;
		$data['curpage'] = $page;
		$data['pagelink'] = '?' . $extralink;
		$pagelink = hg_build_pagelinks($data);
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'log'
						. $conditions . " ORDER BY id DESC LIMIT $page,$count";
		$q = $this->db->query($sql);
		$admin = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = hg_get_date($row['create_time']);
			$admin[$row['id']] = $row;
		}
		$list_fields = array(
			'id' => array('title' => 'ID', 'exper' => '$v[id]'), 
			'content' => array('title' => '日志', 'exper' => '$v[content]<br />$v[script_name]'),
			'user_name'=>array('title'=>'操作人','exper'=>'$v[user_name]'),
			'ip'=>array('title'=>'操作IP','exper'=>'$v[ip]'),
			'create_time' => array('title' => '操作时间', 'exper' => '$v[create_time]')
			);
			/*
			
			'authorize' => array(
				'name' =>'授权', 
				'brief' =>'权限管理',
				'link' => '?a=sys_authorize'),
			*/
		$op = array(
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
		$this->tpl->addVar('pagelink', $pagelink);
		$this->tpl->addVar('op', $op);
		$this->tpl->addVar('batch_op', $batch_op);
		$this->tpl->addVar('close_search', true);
		$this->tpl->addVar('primary_key', 'id');
		$this->tpl->addVar('list', $admin);
		$this->tpl->outTemplate('log');
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
			$sql = 'DELETE FROM ' . DB_PREFIX . 'log WHERE id IN (' . $ids . ')';
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