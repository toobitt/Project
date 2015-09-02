<?php
define('ROOT_DIR', '../../');
define('WITHOUT_DB', true);
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . 'frm/configuare_frm.php');
class configuare extends configuareFrm
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	//重写修改配置方法
	public function doset()
	{
		$url = trim($this->input['define']['DOWNLOAD_URL']);
		is_file(CACHE_DIR.'client_link.txt')&& @unlink(CACHE_DIR.'client_link.txt');
		if($url)
		{
			//生成二维码
			include_once(ROOT_PATH . 'lib/class/qrcode.class.php');
			$qrcode_server = new qrcode();
			$data = array('content'=>$url);
			$qrcode = $qrcode_server->create($data,-1);
			$img_url = is_array($qrcode) ? hg_fetchimgurl($qrcode) : '';
			file_put_contents(CACHE_DIR.'client_link.txt', $img_url);
		}
		parent::doset();
	}
}
$module = 'configuare';
$$module = new $module();  

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
	$func = 'show';	
}
$$module->$func();
?>