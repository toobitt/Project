<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: weight.class.php 16201 2012-12-28 06:01:09Z jeffrey $
***************************************************************************/
class weight
{
	public function __construct()
	{
		global $gGlobalConfig;
		$this->curl = new curl($gGlobalConfig['App_settings']['host'], $gGlobalConfig['App_settings']['dir']);
	}

	public function __destruct()
	{
		unset($this->curl);
	}
	
	/**
	 * 数据列表
	 */
	public function show($val,$value)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$result = $this->curl->request('weight.php');
		return $result;
	}
	
}
?>