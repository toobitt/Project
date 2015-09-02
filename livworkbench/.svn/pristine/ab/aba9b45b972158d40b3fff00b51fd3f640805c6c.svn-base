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
				'name'=>'更新',
				'brief'=>'',
				'attr' =>' onclick="return hg_ajax_post(this, \'更新\', 1);"',
				'link'=>'temstore?a=ds'
				), 
			'pub_setting_all' => array(
			'name'=>'更新全部',
			'brief'=>'',
			'attr' =>' onclick="return hg_ajax_post(this, \'更新\', 1);"',
			'link'=>'temstore?a=ds&flag=1'
			), 
		); 
		$batch_op = array(
			'delete' => array(
				'name' =>'更新', 
				'brief' =>'',
				'attr' =>' onclick="return hg_ajax_batchpost(this, \'update\', \'更新\', 1,\'\',\'\',\'ajax\');"',
				),
		); 
		$str = 'var gBatchAction = new Array();gBatchAction[\'update\'] = \'?a=update\';';
		hg_add_head_element('js-c',$str);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->addVar('list_fields', $list_fields);
		$this->tpl->addVar('op', $op);
		$this->tpl->addVar('batch_op', $batch_op);
		$this->tpl->addVar('primary_key', 'sign');
		$this->tpl->addVar('list', $datas);
		$this->tpl->outTemplate('datasource');
	}
	
	public function update()
	{
		//file_put_contents('0a11',var_export($this->input,1));
		$sign = $this->input['sign'];
		$flag = $this->input['flag'];
		
		$host = 'localhost/livworkbench';
		$dir = '';
		$curl = new curl($host,$dir);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','ds');
		$curl->addRequestData('sign',$sign);
		if($flag)
		{
			$curl->addRequestData('flag',$flag);
		}
		
		//file_put_contents('01c',$sign.'----'.$flag.'--------');
		$mode_info = $curl->request('temstore.php');
		
		
		/*include(ROOT_PATH . 'lib/class/program.class.php');
		$program = new program();
		$id = $program->compile($module_id, $data['op']);*/
		$this->redirect('发布成功');
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>