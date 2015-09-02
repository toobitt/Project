<?php
/***************************************************************************
* $Id: sys_update.php 22758 2013-05-24 07:36:11Z lijiaying $
***************************************************************************/
define('WITHOUT_DB', true);
define('MOD_UNIQUEID','drm');
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . 'global.php');
require(CUR_CONF_PATH . 'lib/functions.php');
class drm extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
	
		if ($this->settings['limit_referer'])
		{
			$drm = false;
			$salt = 'aiudfhakj';
			$referer = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $this->input['refererurl'];
			$setreferer = explode(',', $this->settings['limit_referer']);
			foreach ($setreferer AS $re)
			{
				if (strpos($referer, $re))
				{
					$drm = true;
					$salt = '';
					break;
				}
			}
		}
		else
		{
			$drm = true;
			$salt = '';
		}
		$url = $this->input['url'];
		if (!$url)
		{
			$this->errorOutput(NO_URL);
		}
		$urlinfo = parse_url($url);
		$sign = hg_sign_uri($urlinfo['path'] . $salt, $this->settings['live_expire'], $this->settings['sign_type']);
		$url = $urlinfo['scheme'] . '://' . $urlinfo['host'];
		if ($urlinfo['port'])
		{
			$url .= ':' . $urlinfo['port'];
		}
		if ($this->settings['encipt_url'])
		{
			$url = $this->encipt($urlinfo['path'],$sign[0]);
		}
		else
		{
			$url .= $urlinfo['path'] . $sign[0];
		}
		if ($this->input['return'])
		{
			$this->additem(array('url' => $url));
			$this->output();
		}
		else
		{
			echo $url;
		}
	}
	
	private function encipt($url, $_upt)
	{
		//_upt=d5269e641406864994
		$url .= 'm' . $urlinfo['path'] . $_upt;
		return $url;
	}
	/**
	* 无需验证授权
	*/
	protected function verifyToken()
	{
	}

	public function detail()
	{
	}
	public function count()
	{
	}
}
$out = new drm();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>