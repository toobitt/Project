<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
class hg_get_node extends uiBaseFrm
{
	private $curl;
	function __construct($node_en = '',$expand = array())
	{
		parent::__construct();
		/** expand是扩展数据，一维数组格式，如：$hg_attr['expand']=array('a'=>1,'b'=>2)，如根据条件选择对应节点，传递数据到节点方法，节点直接$this->input['a'] */
		$this->init_curl($node_en,$expand);
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	//
	function init_curl($node_en,$expand)
	{
		$conditions = ' WHERE t1.node_uniqueid="'.$node_en.'"';
		$sql = 'SELECT t1.file_name,t1.file_type,t2.host,t2.dir,t2.admin_dir FROM ' . DB_PREFIX . 'node t1
			LEFT JOIN '.DB_PREFIX.'applications t2
			ON t1.application_id=t2.id'
			. $conditions;
		$node_settings = $this->db->query_first($sql);
		$curl_info = array(
			'curl_host'=>  $node_settings['host'],
			'curl_dir'=>  $node_settings['dir'],
			'curl_file' => $node_settings['file_name'] . $node_settings['file_type'],
		);
		if(!$curl_info['curl_host'] || !$curl_info['curl_file'])
		{
			$this->ReportError('获取节点接口信息失败');
		}
		$this->input['curl_info'] = $curl_info;
		if(!class_exists('curl'))
		{
			require_once ROOT_DIR . 'lib/class/curl.class.php';
		}
		$this->curl = new curl($curl_info['curl_host'], $curl_info['curl_dir']);
		$this->curl->initPostData();
		$this->curl->addRequestData('access_token', $this->user['token']);
		foreach($expand as $k=>$v)
		{
			$this->curl->addRequestData($k, $v);
		}
	}
	//请求admin下的节点
	//模板默认加载的节点数据 顶级节点
	public function get_level1_node($exclude = 0)
	{
		$ret_node = array();
		$this->curl->addRequestData('_exclude', $exclude);
		$ret_node['data'] = $this->curl->request(trim(urldecode($this->input['curl_info']['curl_file'])));
		$ret_node['curl_info'] = $this->input['curl_info'];
		//$this->ReportError(var_export($ret_node['data'],1));
		return ($ret_node);
	}
	//模板加载已选中的数据
	public function getNodeInfoByIds($ids = array())
	{
		$node_info = array();
		$ids = is_array($ids) && $ids ? implode(',', $ids) : $ids;
		if(!$ids)
		{
			return $node_info;
		}
		$this->curl->addRequestData('id', $ids);
		$this->curl->addRequestData('a', 'getSelectedNodes');
		return $this->curl->request(trim(urldecode($this->input['curl_info']['curl_file'])));
	}
}
?>
