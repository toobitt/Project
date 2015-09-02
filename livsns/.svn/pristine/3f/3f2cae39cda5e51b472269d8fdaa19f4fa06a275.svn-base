<?php
/*******************************************************************
 * filename :consignee.php
 * 收货人
 * Created  :2013年8月9日,Writen by scala
 *
 ******************************************************************/
define('MOD_UNIQUEID', 'pay_express_fee');
require ('./global.php');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class DeliveryFeeAPI extends  adminReadBase {
    private $obj = null;
    private $tbname = 'delivery_fee';
    public function __construct() {
        parent::__construct();
        $this -> obj = new Core();
    }

    public function detail() {
        $id = intval($this -> input['id']);

        $cond = " where 1 and id=$id";
    
        $infos = $this -> obj -> detail($this -> tbname, $cond);
        
        $query = "SELECT * FROM ".DB_PREFIX."province ";
        $province = $this->obj->query($query);
        $infos['provinces'] = $province;
        
        $query = "SELECT * FROM ".DB_PREFIX."deliverycategory ";
        $deliveries = $this->obj->query($query);
        $infos['deliveries'] = $deliveries;
        
        foreach($infos as $key=>$val)
        {
            $this->addItem_withkey($key,$val);
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
        $condition = $this->get_condition();
        $v = $this->obj->count($this->tbname,$condition);
        $this -> addItem($v);
        $this -> output();
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

$out = new DeliveryFeeAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>
s