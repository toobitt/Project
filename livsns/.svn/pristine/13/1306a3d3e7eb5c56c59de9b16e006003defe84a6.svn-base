<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: photoedit.class.php 16201 2012-12-28 06:01:09Z jeffrey $
***************************************************************************/

class photoedit
{
	public function __construct()
	{
		global $gGlobalConfig;
		$this->curl = new curl($gGlobalConfig['App_photoedit']['host'], $gGlobalConfig['App_photoedit']['dir']);
	}

	public function __destruct()
	{
		unset($this->curl);
	}
	
	/**
	 * 上传图片
	 * * @param String imgdata 图片的base64_encode格式
	 * * @param String oldurl  图片的绝对地址
	 */
	public function editpicture($imgdata,$oldurl)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('imgdata', $imgdata);
		$this->curl->addRequestData('oldurl', $oldurl);
		$result = $this->curl->request('photoedit_update.php');
		return $result;
	}
	
}
?>