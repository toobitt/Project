<?php
/*******************************************************************
 * filename :consignee.php
 * 配送分类
 * Created  :2013年8月9日,Writen by scala
 *
 ******************************************************************/
define('MOD_UNIQUEID', 'pay_order');
require ('./global.php');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class DeliveryCategoryAPI extends  outerReadBase {
    private $obj = null;
    private $tbname = 'deliverycategory';
    public function __construct() {
        parent::__construct();
        $this -> obj = new Core();
    }

    public function detail() {
        if (!isset($this -> input['id']) || !$this -> input['id']) {
            $this -> errorOutput(NO_ID);
        }

        $id = intval($this -> input['id']);

        $cond = " where 1 and id=$id";

        $orderinfo = $this -> obj -> detail($this -> tbname, $cond);

        if (!$orderinfo) {
            exit(json_encode(array()));
        }
        $this -> addItem($info);
        $this -> output();
    }

    public function show() {
        $condition = $this -> get_condition();
        $offset = $this -> input['offset'] ? $this -> input['offset'] : 0;
        $count = $this -> input['count'] ? intval($this -> input['count']) : 20;
        $data_limit = $condition . ' order by id desc LIMIT ' . $offset . ' , ' . $count;
        $datas = $this -> obj -> show($this -> tbname, $data_limit, $fields = '*');
        if (!$datas) {
            exit(json_encode(array()));
        }
        foreach ($datas as $k => $v) {
            $this -> addItem($v);
        }
        $this -> output();
    }

    public function count() {
        $condition = $this -> get_condition();
        $info = $this -> obj -> count($this -> tbname, $condition);
        echo json_encode($info);
    }

    public function index() {

    }

    private function get_condition() {
        $cond = " where 1 ";
        return $cond;
    }

    public function unknow() {
        $this -> errorOutput(NO_ACTION);
    }

}

$out = new DeliveryCategoryAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>
s