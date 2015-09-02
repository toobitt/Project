<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_DIR', './');
define('WITH_DB',true);
define('SCRIPT_NAME', '_fetch_node');
require('./global.php');
require(ROOT_PATH . 'lib/class/curl.class.php');
class _fetch_node extends uiBaseFrm
{
	private $curl;
	function __construct()
	{
		parent::__construct();
		$this->init_curl();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function init_curl()
	{
		$curl_info = array(
			'curl_host'=>  urldecode($this->input['curl_host']),
			'curl_dir'=>  urldecode($this->input['curl_dir']),
		);
		if(!$curl_info['curl_host'])
		{
			if(!$node_en = urldecode($this->input['multi']))
			{
				$this->ReportError(UNKNOWN_NODE);
			}
			$conditions = ' WHERE t1.node_uniqueid="'.$node_en.'"';
			$sql = 'SELECT t1.file_name,t1.file_type,t2.host,t2.dir,t2.admin_dir FROM ' . DB_PREFIX . 'node t1
				LEFT JOIN '.DB_PREFIX.'applications t2
				ON t1.application_id=t2.id'
				. $conditions;
			$node_settings = $this->db->query_first($sql);
			$curl_info = array(
				'curl_host'=>  $node_settings['host'],
				'curl_dir'=>  $node_settings['dir'],
			);
			$this->input['curl_file'] = $node_settings['file_name'] . $node_settings['file_type'];
		}
		$this->curl = new curl($curl_info['curl_host'], $curl_info['curl_dir']);
		$this->curl->initPostData();
		$this->curl->addRequestData('access_token', $this->user['token']);
	}
	//注意此处没有做limit限制 也就是在子栏目很多的情况下可能会影响加载速度
	public function show()
	{
		$hg_columns = $ret_node = array();
		$this->curl->addRequestData('fid', intval($this->input['fid']));
		$this->curl->addRequestData('expand_id', intval($this->input['expand_id']));
		$ret_node = $this->curl->request(trim(urldecode($this->input['curl_file'])));
		if($ret_node)
		{
			foreach ($ret_node as $key=>$value)
			{
				$value['is_last'] = $value['is_last'] ? 0 : 1;
				//$hg_columns[$value['id']] = $value;
				$hg_columns[] = $value;//排序需要
			}
		}
		//兼容老模板 新模板统一传递 老模板未传递
		if(!$this->input['ban'])
		{
			$hg_columns['para'] = array(
				'counter'=>trim(urldecode($this->input['counter'])),
				'formtype'=>trim(urldecode($this->input['formtype'])),
				'formname'=>trim(urldecode($this->input['formname'])),
				'formurl'=>trim(urldecode($this->input['formurl'])),
				'fid'=>intval($this->input['fid']),
				);
		}
		exit(json_encode($hg_columns));
	}
	private function get_conditions()
	{
		$conditions = '';
		return $conditions;
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>