<?php
//此接口控制CRE推送对象
define('MOD_UNIQUEID', 'outpush');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/outpush_origin_mode.php');

class outpush_origin extends adminReadBase {

    private $mode;

    public function __construct()
    {
        parent::__construct();
        $this->mode = new outpush_origin_mode();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function index(){}
    public function show(){}

    public function detail(){}

    public function sort(){}

    public function count(){}
}

