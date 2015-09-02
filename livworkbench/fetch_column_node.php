<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'columnnode');
require('./global.php');
require('./lib/class/curl.class.php');
class columnnode extends uiBaseFrm
{	
	private $site;
	function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['App_publishcontent']['host'],$this->settings['App_publishcontent']['dir'].'admin/');
	}
	function __destruct()
	{
		parent::__destruct();
	}
	//注意此处没有做limit限制 也就是在子栏目很多的情况下可能会影响加载速度
	public function show()
	{
		$hg_columns = array();
		$condition = $this->get_conditions() . ' ORDER BY sort_id ASC ';
		$data = array(
			'condition' => $condition,
		);
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('data', json_encode($data));
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('a', 'get_authored_columns');
		$columns = $this->curl->request('column_node.php');
		exit(json_encode($columns));
	}
	private function get_conditions()
	{
		if($this->input['siteid'])
		{
			$conditions .= ' AND site_id = '.intval($this->input['siteid']);
		}
		if($this->input['fid'])
		{
			$conditions .= ' AND fid = '.intval($this->input['fid']);
		}
		else
		{
			$conditions .= ' AND fid = 0';
		}
		return $conditions;
	}
	
	public function create()
	{
		$site_id = intval($this->input['siteid']);
		$column_fid = intval($this->input['fid']);
		$column_name = urldecode($this->input['column_name']);
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'operate');
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('site_id', $site_id);
		$this->curl->addRequestData('column_fid', $column_fid);
		$this->curl->addRequestData('column_name', $column_name);
		$this->curl->addRequestData('fast_add_column', '1');//表示ajax请求，增加栏目后返回栏目id
		$columns = $this->curl->request('column.php');
		echo $columns[0];
	}
	
	public function column_sort()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'column_sort');
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('sort', $this->input['sort']);
		$columns = $this->curl->request('column.php');
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>