<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
class block
{	
	private $site;
	private $db;
	private $input;
	function __construct()
	{
		global $_INPUT;
		global $gGlobalConfig;
		$this->db = hg_checkDB();
		$this->input = $_INPUT;
		if(!$gGlobalConfig['App_block'])
		{
			return false;
		}
		$this->curl = new curl($gGlobalConfig['App_block']['host'],$gGlobalConfig['App_block']['dir']);
	}
	function __destruct()
	{
		
	}
	
	public function get_node($fid)
	{
		global $gGlobalConfig;
		if(!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('fid', $fid);
		$this->curl->addRequestData('a', 'show');
		$ret = $this->curl->request('admin/block_node.php');
		return $ret;
	}
	
	//获取支持推送的页面区块
	public function get_block($id)
	{
		global $gGlobalConfig;
		if(!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('_id', $id);
		$this->curl->addRequestData('count', 100);
		$this->curl->addRequestData('is_support_push', 1);
		$this->curl->addRequestData('a', 'get_block');
		$ret = $this->curl->request('admin/block.php');
                if(is_array($ret[0]['block']))
                {
                    return $ret[0]['block'];
                }
		return array();
	}
	
}
?>