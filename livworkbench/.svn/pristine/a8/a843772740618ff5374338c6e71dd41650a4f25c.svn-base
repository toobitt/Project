<?php

define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'template');
require('./global.php');
require (ROOT_PATH . 'lib/class/curl.class.php');
class template extends uiBaseFrm
{	
	private $modestore;
	//private $product_server;
	function __construct()
	{
		parent::__construct();		
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
		$curl->addRequestData('count','1000');
		$curl->addRequestData('sign','1');
		$mode_info = $curl->request('template.php');
		if($mode_info[0][0] &&is_array($mode_info[0][0]))
		{
			foreach($mode_info[0][0] as $k=>$v)
			{
				$modes[$v['id']] = $v;
			}
		}
		$list_fields = array(
			'id' => array('title' => 'ID', 'exper' => '$v[id]'), 
			'name' => array('title' => '名称', 'exper' => '$v[title]'),
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
		$str = 'var gBatchAction = new Array();gBatchAction[\'publish\'] = \'?a=publish\';';
		hg_add_head_element('js-c',$str);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->addVar('list_fields', $list_fields);
		$this->tpl->addVar('op', $op);
		$this->tpl->addVar('batch_op', $batch_op);
		$this->tpl->addVar('primary_key', 'sign');
		$this->tpl->addVar('list', $modes);
		$this->tpl->outTemplate('template_pub');
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
		$curl->addRequestData('a','export_teminfo');
		$curl->addRequestData('sign',$sign);
		$mode_info = $curl->request('template.php');
		
		$this->redirect('发布成功');
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>