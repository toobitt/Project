<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: favorites.class.php 2869 2011-03-16 13:33:10Z chengqing $
***************************************************************************/
class favorites{
	
	private $curl;
	
	function __construct()
	{
		global $gApiConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gApiConfig['host'], $gApiConfig['apidir']);
	}
	
	function __destruct()
	{
	}
	
	/**
	* 添加收藏
	* @param $title
	* @param $cid
	* @param $type_id
	* @param $link
	* @param $schematic
	* @param $fa_id
	* @return $info 收藏信息
	*/
	public function create($title,$cid,$type_id,$link,$schematic,$fa_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('title', $title);
		$this->curl->addRequestData('cid', $cid);
		$this->curl->addRequestData('type_id', $type_id);
		$this->curl->addRequestData('link', $link);
		$this->curl->addRequestData('schematic', $schematic);
		$this->curl->addRequestData('fa_id', $fa_id);
		$ret = $this->curl->request('collections/favorites.php');
		return $ret;
	}
	
	
	/**
	* 删除收藏
	* @param $id 收藏id
	* @param $type (0用fa_id删，1用id删)
	* @return $id 收藏ID
	*/
	public function del($id,$type=0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'del');
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('collections/favorites.php');
		return $ret;
	}
	
	
}
?>
