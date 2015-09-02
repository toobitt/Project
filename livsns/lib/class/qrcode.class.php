<?php
/***************************************************************************
* LivSNS 0.1
* (C)2009-2010 HOGE Software.
*
* $Id: material.class.php 18105 2013-03-01 03:42:03Z wangleyuan $
***************************************************************************/
class qrcode
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_qrcode']['host'], $gGlobalConfig['App_qrcode']['dir']);
	}

	function __destruct()
	{
	}
	
   	/**
   	 * 
   	 * Enter description here ...
   	 * @param array $config
   	 * @param int $type
   	 * @param array $data
   	 * @param int $flag
   	 */
	public function create($data,$type=0,$config=array(),$flag=0)
	{
		if (!is_array($data) || empty($data))
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','code');
		foreach ($data as $key=>$val)
		{
			$this->curl->addRequestData('data['.$key.']',$val);
		}
		$this->curl->addRequestData('type',$type);
		if (is_array($config) && !empty($config))
		{
			foreach ($config as $key=>$val)
			{
				$this->curl->addRequestData('config['.$key.']',$val);
			}
		}
		$this->curl->addRequestData('flag',$flag);
		$this->curl->addRequestData('app_bundle',APP_UNIQUEID);
		$this->curl->addRequestData('module_bundle',MOD_UNIQUEID);
		$ret = $this->curl->request('qrcode.php');
		return $ret[0];
	}
}

?>