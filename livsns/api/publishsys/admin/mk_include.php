<?php

class mk_include
{

    function show()
    {
        $file_result = $_REQUEST['file_result'];
        define('M2O_ROOT_PATH', '../lib/m2o/');
        define('ROOT_PATH', '../lib/m2o/');
        if (!file_exists($file_result))
        {
            echo "NO_FILE";
            exit;
        }
        
        include_once('../../../conf/global.conf.php');
        $GLOBALS['gGlobalConfig']  = $gGlobalConfig;
        ob_clean();
        ob_start();
        include $file_result;
        $result                    = ob_get_contents();
        ob_clean();
        $r['html']                 = ltrim($result);
        $r['_get_analysis_result'] = $_get_analysis_result;
        unset($__info['content']);
        unset($__info['__pagetitle']);
        unset($__info['__pagekeyword']);
        unset($__info['__pagedescription']);
        $r['__info']               = $__info;
        if ($_GET['debug'])
        {
            print_r($r);
            exit;
        }
        $content_type = 'Content-Type:text/plain';
        header($content_type);
        echo json_encode($r);
        exit;
    }

}

$out    = new mk_include();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
