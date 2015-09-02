<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-15
 * @encoding    UTF-8
 * @description 按照版本号排名接口
 **************************************************************************/
define('MOD_UNIQUEID','app_rank_by_version');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/app.class.php');

class app_rank_by_version extends adminReadBase
{
    private $_app;
    public function __construct()
    {
        parent::__construct();
        $this->_app = new app();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function index(){}
    public function count(){}

    public function show()
    {
        $ret = $this->_app->rankByVersion();
        if($ret)
        {
            $this->addItem_withkey('rank',$ret);
            $this->output();
        }
    }
    public function detail(){}
}

$out = new app_rank_by_version();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'show';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();