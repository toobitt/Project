<?php
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'help');
require('./global.php');
require(ROOT_PATH . 'lib/class/curl.class.php');
class help extends uiBaseFrm
{
	private $hosts = array();
	function __construct()
	{
		parent::__construct();
		$this->hosts = array(
			'host' => 'localhost/livsns/api/help',
			'port' => '83',
			'dir' => '',
			'customer' => $this->settings['license']['customer_var'],
		);
		$this->curl = new curl($this->hosts['host'], '', 'kaasdfj823&23^l');
	//	$this->db = hg_checkDB();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	

	private function getHelp()
	{
		$sortid = $this->input['sortid'];
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('sortid', $sortid);
		$this->curl->addRequestData('a', 'all');
		$help = $this->curl->request('help.php');
		return $help;
	}
	
	private function getHelpSort()
	{
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getSort');
		$help = $this->curl->request('help_menu.php');
		return $help;
	}

	private function getFatherMenu()
	{
		$fatherid = $this->input['fatherid'] ? $this->input['fatherid'] : -1;
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('fatherid', $fatherid);
		$this->curl->addRequestData('a', 'getFather');
		$help = $this->curl->request('help_menu.php');
		return $help;
	}

	public function show()
	{
		$sorts = $this->getHelpSort();
		//hg_pre($sorts,0);
		$this->tpl->addVar('fatherid', ($this->input['fatherid'] ? $this->input['fatherid'] : -1));
		$this->tpl->addVar('sorts', $sorts);
		$this->tpl->outTemplate('help');
	}

	public function menu_form()
	{
		if(!$this->input['sub'])
		{
			$modules = array( -1 => '请选择');			
			foreach($this->getHelpSort() as $k => $v)
			{
				$modules[$v['id']] = $v['name'];
			}
			$menu_form = array();
			$optext = '新增';
			if($this->input['id'])
			{
				$this->curl->setSubmitType('get');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('id', $this->input['id']);
				$this->curl->addRequestData('a', 'detail');
				$menu_form = $this->curl->request('help_menu.php');
				$optext = '更新';
			}
			$this->tpl->addVar('optext',$optext);
			$this->tpl->addVar('modules',$modules);
			$this->tpl->addVar('formdata', $menu_form[0]);
			$this->tpl->addVar('sortid', ($this->input['sortid'] ? $this->input['sortid'] : 0));
			$this->tpl->outTemplate('help_menu_form');
		}
		else
		{
			$this->curl->setSubmitType('get');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('id', $this->input['id']);
			$this->curl->addRequestData('fatherid', $this->input['fatherid']);
			$this->curl->addRequestData('name', $this->input['name']);
			$this->curl->addRequestData('state', $this->input['state']);
			$this->curl->addRequestData('mark', $this->input['mark']);
			$this->curl->addRequestData('order_id', $this->input['order_id']);
			$this->curl->addRequestData('a', 'form');
			$menu_form = $this->curl->request('help_menu.php');
			$this->Redirect($this->input['sub'] . '成功！','help.php?a=show');
		}
	}

	public function help_doc_form()
	{
		if(!$this->input['sub'])
		{
			$sql = "select * from " . DB_PREFIX . "help_menu where fatherid=-1";
			$q = $this->db->query($sql);
			$menu = array();
			while($row = $this->db->fetch_array($q))
			{
				$menu[$row['id']] = $row['name'];
			}
			$id = $this->input['id'];
			if($id)
			{
				$sql = "select * from " . DB_PREFIX . "help_api where id=" . $id;
				$menu_form = $this->db->query_first($sql);
				$this->tpl->addVar('menu_form', $menu_form);
			}
			$format_array = array('json' => 'json' ,);
			$request_type_array = array('post' => 'post' ,'get' => 'get');
			$this->tpl->addVar('menu', $menu);
			$this->tpl->addVar('format_array', $format_array);
			$this->tpl->addVar('request_type_array', $request_type_array);
			$this->tpl->outTemplate('help_doc_form');
		}
		else
		{
			$id = $this->input['id'];
			$info = array(
				'fatherid' => $this->input['fatherid'],	
				'filename' => $this->input['filename'],
				'api_intro]' => $this->input['api_intro'],
				'return_intro' => $this->input['return_intro'],
				'format' => $this->input['format'],
				'request_type' => $this->input['request_type'],
				'is_login' => $this->input['is_login'],
				'warning' => $this->input['warning'],
				'other' => $this->input['other'],
				'create_time' => TIMENOW,
				'update_time' => TIMENOW,
				'ip' => hg_getip(),
			);
			if($id)
			{
				unset($info['create_time'],$info['ip']);
			}			
			$sql_extra = $space = "";
			foreach($info as $key => $value)
			{
				$sql_extra .= $space . $key . "='" . $value . "'";
				$space = ',';
			}
			if($id)
			{
				$sql = "UPDATE " . DB_PREFIX . "help_api SET " . $sql_extra . ' WHERE id=' . $this->input['id'];
			}
			else
			{
				$sql = "INSERT INTO " . DB_PREFIX . "help_api SET " . $sql_extra;
			}
			$this->db->query($sql);
			$this->input['goon'] = 1;
			$this->Redirect('更新成功！','help.php?a=help_doc_list');
		}
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>