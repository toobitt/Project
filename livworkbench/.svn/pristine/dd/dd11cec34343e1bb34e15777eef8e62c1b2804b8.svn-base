<?php
define('RES', '../res/magic/');
define('JS', RES . 'js/');
define('CSS', RES . 'css/');

class main extends stdClass{
    public function __call($key, $params){
        return call_user_func_array($this->{$key}, $params);
    }

    public function res($res, $type = 'js'){
        static $_res = array();
        if($res == 'echo'){
            $resHtml = '';
            foreach($_res as $k => $v){
                if($k == 'js'){
                    foreach($v as $vv){
                        $resHtml .= '<script src="'. JS . $vv .'.js"></script>' . "\n";
                    }
                }else{
                    foreach($v as $vv){
                        $resHtml .= '<link rel="stylesheet" href="'. CSS . $vv .'.css"/>' . "\n";
                    }
                }
            }
            $_res = array();
            return $resHtml;
        }
        if(!is_array($res)){
            $res = array($res);
        }
        foreach($res as $v){
            if($v){
                if(!isset($_res[$type])){
                    $_res[$type] = array();
                }
                $_res[$type][] = $v;
            }
        }
    }
}


$main = new main();
$main->ext = urldecode($_REQUEST['ext']);
$main->gmid = intval($_REQUEST['gmid']);

($bs = $_REQUEST['bs']) || ($bs = 'm');
$actionTpl = '../run.php?mid={{$gmid}}&a={{$action}}';
$actionMap = array(
    'layout' => 'layout_list',
    'search' => 'search_cell',
    'save' => 'cell_update',
    'cancel' => 'cell_cancle',
    'build' => 'built_template',
    'edit' => 'template_edit',
    'getLayoutInfo' => 'get_layout_preview',
    'updateLayout' => 'update_special_layout',
    'updateLayoutTitle' => 'update_layout_title'
);
foreach($actionMap as $kk => $action){
    $main->$kk = str_replace(array('{{$gmid}}', '{{$action}}'), array($main->gmid, $action), $actionTpl);
}