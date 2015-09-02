<?php
/*******************************************************************
 * filename :consignee.php
 * 配送管理
 * Created  :2013年8月9日,Writen by scala
 *
 ******************************************************************/
require ('./global.php');
define('MOD_UNIQUEID', 'pay_order');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class DeliveryAPI extends  outerReadBase {
    private $obj = null;
    private $tbname = 'order_delivery';
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
        if (isset($this -> input['id']) && $this -> input['id']) {
            $id = intval($this -> input['id']);
        }
        
        
        if(isset($this->input['order_id'])&&$this->input['order_id'])
        {
            $order_id = intval($this->input['order_id']);
        }
        
        if($id)
        {
            $qurey = " SELECT od.*,df.delivery_fee as delivery_fee,df.province_id as province_id ,df.province_title as province_title
                   FROM ".DB_PREFIX."order_delivery od 
                   LEFT JOIN ".DB_PREFIX."delivery_fee df 
                   ON od.delivery_fee_id=df.id
                   WHERE od.id=$id";
        }
        
        if($order_id)
        {
            $qurey = " SELECT od.*,df.delivery_fee as delivery_fee,df.province_id as province_id , df.province_title as province_title 
                   FROM ".DB_PREFIX."order_delivery od 
                   LEFT JOIN ".DB_PREFIX."delivery_fee df 
                   ON od.delivery_fee_id=df.id
                   WHERE od.order_id=$order_id";
        }
            
        $orderinfos = $this->obj->query($qurey);
        
        if (!$orderinfos) {
            exit(json_encode(array()));
        }
        
        foreach($orderinfos as $orderinfo)
        {
            $this -> addItem($orderinfo);
        }
        
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

$out = new DeliveryAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>
s