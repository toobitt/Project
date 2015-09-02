<?php
/*******************************************************************
 * filename :DeliveryTracing.php
 * 订单显示列表 详情
 * Created  :2014年5月27日,Writen by scala
 ******************************************************************/
require ('./global.php');
define('MOD_UNIQUEID', 'pay_order');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class DeliveryTracingAPI extends  adminReadBase {
    private $obj = null;
    private $tbname = 'delivery_tracing';
    public function __construct() {
        parent::__construct();
        $this -> obj = new Core();
        $this -> delivery_tracing_conf = $this -> settings['trace_step'];
    }

    /**
     * 返回订单详情
     * @param  int              id          订单追踪详情
     * @return array
     */
    public function detail() {
        
        if (!isset($this -> input['id']) || !$this -> input['id']) {
            $this -> errorOutput(NO_ID);
        }

        $id = intval($this -> input['id']);
        
        $cond = " where 1 and id=$id ";

        $info = $this -> obj -> detail($this -> tbname, $cond);
        $this->addItem($info);
        $this -> output();
    }

    public function show() {
        $offset = $this -> input['offset'] ? $this -> input['offset'] : 0;
        $count = $this -> input['count'] ? intval($this -> input['count']) : 20;
        $data_limit =  $this->get_condition().' order by id desc LIMIT ' . $offset . ' , ' . $count;
        
        $query = "SELECT * 
                  FROM ".DB_PREFIX."delivery_trace ";
        $datas = $this -> obj ->query($query.$data_limit);
        if (!$datas) {
            exit(json_encode(array()));
        }
        
        foreach ($datas as $k => $v) {
            $v['tracestep_title'] = $this -> delivery_tracing_conf[$v['tracestep']];
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
        $cond = " WHERE 1 ";
        if(isset($this->input['id']))
        {
            $cond .= " AND order_id='".(int)$this->input['id']."' ";
        }
        
        if(isset($this->input['order_id']))
        {
            $cond .= " AND order_code='".(int)$this->input['order_id']."' ";
        }
        return $cond;
    }

    public function unknow() {
        $this -> errorOutput(NO_ACTION);
    }

}

$out = new DeliveryTracingAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>
