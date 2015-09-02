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
	protected function settings_process()
	{
		parent::settings_process();
		$IMG_DIR = trim($this->input['define']['IMG_DIR']);
		$IMG_URL = trim($this->input['define']['IMG_URL']);
		
		$pregfind = array('<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
		$pregreplace = array('&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '');
		$IMG_DIR = str_replace($pregreplace, $pregfind, $IMG_DIR);
		if (strstr($IMG_DIR, 'ROOT_PATH'))
		{
			$IMG_DIR = trim($IMG_DIR, "'");
			$IMG_DIR = trim($IMG_DIR);
			$IMG_DIR = trim($IMG_DIR,  '.');
			$IMG_DIR = trim($IMG_DIR);
			$dir = str_replace(array('.', "'", ' '), '', $IMG_DIR);
			$dir = str_replace('ROOT_PATH', ROOT_PATH, $dir);
		}
		else
		{
			$dir = $IMG_DIR;
		}
		$dir = rtrim($IMG_DIR, '/') . '/';
		hg_mkdir($dir);
		if (!is_dir($dir))
		{
			$this->errorOutput('DIR_NOT_EXIST');
		}
		if (!is_writeable($dir))
		{
			$this->errorOutput('DIR_CANNOT_WRITE');
		}
		if (strstr($IMG_DIR, 'ROOT_PATH'))
		{
			$IMG_DIR = "' . " . $IMG_DIR;
		}
		$IMG_DIR = rtrim($IMG_DIR, '/') . '/';
		$IMG_URL = rtrim($IMG_URL, '/') . '/';
		$this->input['define']['IMG_DIR'] = $IMG_DIR;
		$this->input['define']['IMG_URL'] = $IMG_URL;
		$default_img = $_FILES['base']['default_img'];
		if ($_FILES['base']['tmp_name']['default_img'])
		{
			$dimgdir = 'default/img/';
			$type = strrchr($_FILES['base']['name']['default_img'], '.');
			$type = strtolower($type);
			if (!in_array($type, array('.jpg', '.jpeg', '.png')))
			{
				$this->errorOutput('IMG_FORMAT_ERROR');
			}
			$imgname = 'default' . $type;
			hg_mkdir($dir . $dimgdir);
			if (!@move_uploaded_file($_FILES['base']['tmp_name']['default_img'], $dir . $dimgdir . $imgname))
			{
				$this->errorOutput('IMG_UPLOAD_ERROR');
			}
			if ($this->settings['default_img'])
			{
				@unlink($dir . $this->settings['default_img']);
			}
			$this->input['base']['default_img'] = $dimgdir . $imgname;
		}
		else
		{
			$this->input['base']['default_img'] = $this->settings['default_img'];
		}
		
		file_put_contents($dir . 'ping.txt', 'ok');
		set_time_limit(5);
		$ping = @file_get_contents($IMG_URL . 'ping.txt');
		@unlink($dir . 'ping.txt');
		if ($ping != 'ok')
		{
			$this->errorOutput('IMG_URL_CAN_NOT_VISIT');
		}
		$this->input['base']['imgdirs'] = $this->settings['imgdirs'];
		$this->input['base']['imgurls'] = $this->settings['imgurls'];
        $tmp = $this->input['base']['imgdirs'][$IMG_URL];
        if($tmp){
            if($tmp == $IMG_DIR){
                $keyindex = array_flip($this->input['base']['imgurls']);
                $this->input['base']['curImgserver'] = $keyindex[$IMG_URL];
            } else {
                //应该报错
                $this->errorOutput('之前已经存在相应域名及对应目录');
            }
        } else {
            $this->input['base']['imgdirs'][$IMG_URL] = $IMG_DIR;
            $imgindex = count($this->input['base']['imgdirs']);
            $this->input['base']['imgurls']['img' . $imgindex] = $IMG_URL;
            $keyindex = array_flip($this->input['base']['imgurls']);
            $imgserver = $keyindex[$IMG_URL];
            $this->input['base']['curImgserver'] = $imgserver;
        }
		@copy(ROOT_PATH . 'crossdomain.xml', $dir . 'crossdomain.xml');
	}
	
	public function get_config()
	{
		$config = $this->settings();
		$this->addItem($config);
		$this->output();
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