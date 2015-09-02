<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 325 2011-11-17 02:21:45Z develop_tong $
***************************************************************************/
abstract class upgradeFrm extends InitFrm
{	
	var $mProductDir;  
	var $mVersion;
	var $mRootDir = '/web/publish_product/';
	var $mVersionDir = 'release/';
	var $mApp = 'livworkbench';
	var $mAuthServer = array(
		'host' => 'auth.hogesoft.com',	
		'dir' => '',	
	);
	var $mUser = array();
	function __construct()
	{
		parent::__construct();
		$app = trim($this->input['app']);
		if (!$app)
		{
			$app = 'livworkbench';
		}
		$this->mApp = $app;
		$pre_release = intval($this->input['pre_release']);
		if (defined('NEED_AUTH') && NEED_AUTH)
		{
			include_once(ROOT_PATH . 'lib/class/curl.class.php');
			$curl = new curl($this->mAuthServer['host'], $this->mAuthServer['dir']);
			$curl->mAutoInput = false;
			$curl->setClient($this->input['appid'], $this->input['appkey']);
			$curl->setToken('');
			$curl->initPostData();
			$postdata = array(
				'a'				=>	'get_user_info',
			);
			foreach ($postdata as $k=>$v)
			{
				$curl->addRequestData($k, $v);
			}
			$ret = $curl->request('get_access_token.php');
			$ret = $ret[0];
			$this->mUser = $ret;
			if ($ret['install_type'] == 'pre-release')
			{
				$pre_release = 1;
			}
		}
		if ($pre_release)
		{
			$this->mVersionDir = 'pre-release/';
		}
		else
		{
			$this->mVersionDir = 'release/';
		}
		$version = trim($this->input['version']);
		$version_dir = $this->mRootDir . $this->mVersionDir;
		if (!$version)
		{
			$version = $this->getLastestVesion($version_dir);
		}
		$this->mProductDir =  $version_dir;
		$this->mVersion =  $version;
		if ($this->input['debug'])
		{
			echo '<pre>';
			print_r($this);
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	protected function getLastestVesion($version_dir)
	{
        if ($this->input['appid'] == 38 && $this->mApp == 'live')
        {
            return $version = '1.5.4';
        }
		$version_dir = $version_dir . $this->mApp . '/';
		if (!is_dir($version_dir))
		{
			return '';
		}
		$handle = dir($version_dir);
		$version = '0';
		while ($dir = $handle->read())
		{
			if ($dir == '.' || $dir == '..')
			{
				continue;
			}
			if (is_dir($version_dir . $dir))
			{
				if (!$version)
				{
					$version = $dir;
				}
				else
				{
					if ($dir > $version)
					{
						$version = $dir;
					}
				}
			}
		}
		return $version;
	}
}
?>
