<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: banword.class.php 27481 2013-08-16 02:29:10Z yaojian $
***************************************************************************/
class banword
{
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_banword']['host'], $gGlobalConfig['App_banword']['dir']);
	}

	public function __destruct()
	{
		unset($this->curl);
	}
	
	/**
	 * 检测是否存在屏蔽字
	 * @param String $str 要检测的内容
	 */
	public function exists($str)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('banword', $str);
		$this->curl->addRequestData('a', 'exists');
		$result = $this->curl->request('banword.php');
		return $result[0];
	}
	
	/**
	 * 替换屏蔽字
	 * @param String $str 要替换的内容
	 * @param String $symbol  替换的符号
	 */
	public function replace($str, $symbol = '*')
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('banword', $str);
		$this->curl->addRequestData('symbol', $symbol);
		$this->curl->addRequestData('a', 'replace');
		$result = $this->curl->request('banword.php');
		return $result[0];
	}
}
?>