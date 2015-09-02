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

    public function settings_process()
    {
        parent::settings_process();

        //appid appkey写入lib/m2o/conf/config.php
        $appidappkey = '//appid   appkey
define(\'APPID\',\'' . APPID . '\');
define(\'APPKEY\',\'' . APPKEY . '\');';
        $p = 'define(\'APPKEY\',\''.APPKEY.'\');';
        $config_str = file_get_contents(CUR_CONF_PATH.'lib/m2o/conf/config.php');
        $pr = strstr($config_str,$p);
        if($pr===false)
        {
            $config_str = str_replace('//appid   appkey', $appidappkey, $config_str);
            file_put_contents(CUR_CONF_PATH.'lib/m2o/conf/config.php', $config_str);
        }
        
        
        $DATA_URL   = trim($this->input['define']['DATA_URL']);
		if ($DATA_URL)
		{
			$DATA_URL                          = rtrim($DATA_URL, '/') . '/';
			$this->input['define']['DATA_URL'] = $DATA_URL;
	
			file_put_contents('./data/ping.txt', 'ok');
			set_time_limit(5);
			$ping = @file_get_contents($DATA_URL . 'ping.txt');
			@unlink('./data/ping.txt');
			if ($ping != 'ok')
			{
				$this->errorOutput('模板服务器:'.realpath(CUR_CONF_PATH . 'data').'目录不可写');
			}
        }
        
    }

    public function get_config()
    {
        $config = $this->settings();
        $this->addItem($config);
        $this->output();
    }

}

$module  = 'configuare';
$$module = new $module();

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
    $func = 'show';
}
$$module->$func();
?>