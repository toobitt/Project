<?php
/*******************************************************************
 * filename :configure.php
 * Created  :2013年8月15日,Writen by scala 
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
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
    protected function settings_process()
    {
        parent::settings_process();

    }
    
    function __destruct()
    {
        parent::__destruct();
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