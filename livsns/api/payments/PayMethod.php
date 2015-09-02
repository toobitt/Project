<?php
/*******************************************************************
 * filename :consignee.php
 * 收货人
 * Created  :2013年8月9日,Writen by scala
 *
 ******************************************************************/
require ('./global.php');
define('MOD_UNIQUEID', 'pay_order');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class PayMethodAPI extends  outerReadBase {
    private $obj = null;
    private $tbname = 'order';
    public function __construct() {
        parent::__construct();
        $this -> obj = new Core();
    }

    /**
     * 返回订单详情
     * @param  int              id          订单id
     * @param  order_type       string      订单类型
     * @return array
     */
    public function detail() {
        if (!isset($this -> input['id']) || !$this -> input['id']) {
            $this -> errorOutput(NO_ID);
        }

        $id = intval($this -> input['id']);

        $cond = " where 1 and id=$id and user_id=" . $this -> user['user_id'];
        $cond = " where 1 and id=$id";

        $orderinfo = $this -> obj -> detail($this -> tbname, $cond);

        if (!$orderinfo) {
            $this -> errorOutput(NO_DATA_EXIST);
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
        foreach ($datas as $k => $v) {
            $v['submit_time'] = date("Y-m-d H:i", $row['submit_time']);
            $v['pay_start_time'] = date("Y-m-d H:i", $row['pay_start_time']);
            $v['pay_end_time'] = date("Y-m-d H:i", $row['pay_end_time']);
            $v['goods_out_time'] = date("Y-m-d H:i", $row['goods_out_time']);
            $v['goods_wait_time'] = date("Y-m-d H:i", $row['goods_wait_time']);
            $v['completed_time'] = date("Y-m-d H:i", $row['completed_time']);
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

    private function get_goodslist($order_id = 1) {
        $cond = " where 1 and order_id=$order_id and user_id=" . $this -> user['user_id'];
        $cond = " WHERE 1 AND `order_id`=$order_id";
        /*
         $query = "SELECT G.*,E.*
         FROM `".DB_PREFIX."goodslist` G
         LEFT JOIN `".DB_PREFIX."goodsextensionvalue` E
         ON (G.`goods_id`=E.`goods_id`)
         $cond";
         *
         */
        $query = "SELECT *
                  FROM `" . DB_PREFIX . "goodslist` 
                  $cond";
        $result = $this -> obj -> query($query);

        if (!is_array($result) || !$result) {
            return array();
        }

        $goods_ids = array_keys($result);

        $extends = $this -> get_goodsextendtion($goods_ids);

        foreach ($extends as $extend) {
            $result[$extend['goods_id']][$extend['field']][] = $extend['value'];
        }

        echo json_encode($result);

        return $result;
    }

    private function get_goodsextendtion($goods_ids) {
        if (!$goods_ids)
            return array();
        if (is_array($goods_ids) && $goods_ids) {
            $goods_ids = implode(',', $goods_ids);
        }
        $query = "SELECT * 
                  FROM `" . DB_PREFIX . "goodsextensionvalue` 
                  WHERE goods_id in ($goods_ids)";

        $result = $this -> obj -> query($query);
        if (!is_array($result) || !$result) {
            return array();
        }
        return $result;
    }

    private function get_condition() {
        $cond = " where 1 and user_id=" . $this -> user['user_id'];
        return $cond;
    }

    public function unknow() {
        $this -> errorOutput(NO_ACTION);
    }

}

$out = new PayMethodAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>
