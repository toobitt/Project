<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: message_receiced.class.php 16201 2012-12-28 06:01:09Z jeffrey $
***************************************************************************/
class message_received
{
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_message_received']['host'], $gGlobalConfig['App_message_received']['dir']);
	}

	public function __destruct()
	{
		unset($this->curl);
	}
	
	/**
	 * 根据条件查询接收手机短信数据
	 * @param String $val   字段名
	 * @param String $value 字段值
	 */
	public function exists($val,$value)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('coul', $val);
		$this->curl->addRequestData('coulvalue', $value);
		$this->curl->addRequestData('a', 'exists');
		$result = $this->curl->request('message_received.php');
		return $result[0];
	}
	
	/**
	 * 根据条件查询发送手机短信数据
	 * @param String $val   字段名
	 * @param String $value 字段值
	 */
	public function exists_send($val,$value)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('coul', $val);
		$this->curl->addRequestData('coulvalue', $value);
		$this->curl->addRequestData('a', 'exists_send');
		$result = $this->curl->request('message_send.php');
		return $result[0];
	}
	
}
?>