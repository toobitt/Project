<?php
require_once (ROOT_PATH . 'lib/class/curl.class.php');
class multifunc
{
	protected $curl = null;
	function __construct($app = '', $is_admin = true)
	{
		if($app)
		{
			$this->setCurl($app, $is_admin);
		}
	}

	function __destruct()
	{
		
	}

	function setCurl($app, $is_admin = true)
	{
		global $gGlobalConfig;
		$app = 'App_' . $app;
		$admin_dir = '';
		if($is_admin)
		{
			$admin_dir = 'admin/';
		}
		$this->curl = new curl($gGlobalConfig[$app]['host'], $gGlobalConfig[$app]['dir'] . 'admin/');
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->setReturnFormat('json');
	}
	function upload($file, $type='img')
	{
		$file['name'] = urldecode($file['name']);
		switch ($type)
		{
			case 'img'://图片
				{
					$this->setCurl('material');
					$action = 'addMaterial';
					$api  = 'material_update.php';
					$attach['Filedata'] = $file;
					$extend = array(
					'app_bundle'=>APP_UNIQUEID,
					'module_bundle'=>MOD_UNIQUEID,
					);
					break;
				}
			case 'media'://
				{
					$this->setCurl('mediaserver');
					$action = 'submit_transcode';
					$api  = 'create.php';
					$attach['videofile'] = $file;
					$extend = array(
					
					);
					break;
				}
			default:
				{
					return false;
				}
		}
		$this->curl->addFile($attach);
		$this->curl->addRequestData('a', $action);
		if($extend)
		{
			foreach($extend as $k=>$v)
			{
				$this->curl->addRequestData($k, $v);
			}	
		}
		return $this->curl->request($api);
	}
	
}
?>