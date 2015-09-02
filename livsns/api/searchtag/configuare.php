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
    

    public function __install()
    {
    }

    public function __upgrade()
    {
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