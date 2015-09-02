<?php

define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'datasource');
require('./global.php');
require (ROOT_PATH . 'lib/class/curl.class.php');
class datasource extends uiBaseFrm
{	
	private $modestore;
	//private $product_server;
	function __construct()
	{
		parent::__construct();		
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->ReportError('对不起，您没有权限进入数据源商店');
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{	
		
		$host = $this->settings['App_publishsys']['host'];
		$dir = $this->settings['App_publishsys']['dir'].'admin/';
		$curl = new curl($host,$dir);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','show');
		$curl->addRequestData('count','100');
		$data_source_info = $curl->request('data_source.php');
		if($data_source_info[0][0] &&is_array($data_source_info[0][0]))
		{
			foreach($data_source_info[0][0] as $k=>$v)
			{
				$datas[$v['id']] = $v;
			}
		}
		$list_fields = array(
			'id' => array('title' => 'ID', 'exper' => '$v[id]'), 
			'name' => array('title' => '名称', 'exper' => '$v[name]'),
		);
		$op = array(
			'pub_setting' => array(
				'name'=>'发布',
				'brief'=>'',
				'attr' =>' onclick="return hg_ajax_post(this, \'发布\', 1);"',
				'link'=>'?a=publish'
				), 
		); 
		$batch_op = array(
			'update' => array(
				'name' =>'发布', 
				'brief' =>'',
				'attr' =>' onclick="return hg_ajax_batchpost(this, \'publish\', \'发布\', 1,\'\',\'\',\'ajax\');"',
				),
		); 
		if($_SERVER['HTTP_HOST'] =='localhost' || $_SERVER['HTTP_HOST'] =='10.0.1.40')
		{
			$url = $_SERVER['HTTP_HOST'].'/livworkbench/';
		}
		else 
		{
			$url = $_SERVER['HTTP_HOST'].'/';
		}
		$url = '';
		$file = array();
		$file = array(
			'模板商店'		=> $url.'tempstore.php?a=show_template',
			'样式商店' 			=> $url.'tempstore.php?a=show_mode',
			'数据源商店'  	=> $url.'tempstore.php?a=show_datasource',
			'布局商店'  		=> $url.'tempstore.php?a=show_layout',
		
		);
		$str = 'var gBatchAction = new Array();gBatchAction[\'publish\'] = \'?a=publish\';';
		hg_add_head_element('js-c',$str);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->addVar('list_fields', $list_fields);
		$this->tpl->addVar('op', $op);
		$this->tpl->addVar('batch_op', $batch_op);
		$this->tpl->addVar('primary_key', 'sign');
		$this->tpl->addVar('list', $datas);
		$this->tpl->addVar('host', $url);
		$this->tpl->addVar('file', $file);
		$this->tpl->outTemplate('admin_temload');
	}
	
	public function publish()
	{
		$id = $this->input['id'];
		$sign = $this->input['sign'];
		
		if($id)
		{
			$sign = $id;
		}
		$host = $this->settings['App_publishsys']['host'];
		$dir = $this->settings['App_publishsys']['dir'].'admin/';
		$curl = new curl($host,$dir);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','export_datasource');
		$curl->addRequestData('sign',$sign);
		$mode_info = $curl->request('data_source.php');
		
		$this->redirect('发布成功');
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>