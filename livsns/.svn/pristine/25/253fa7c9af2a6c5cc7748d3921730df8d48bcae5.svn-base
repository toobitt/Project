<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/12/2
 * Time: 下午3:07
 */
require_once('global.php');
define(SCRIPT_NAME, 'OrderUpdateApi');
define('MOD_UNIQUEID','order_list');
class OrderUpdateApi extends adminUpdateBase
{
    public function __construct()
    {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/order.class.php');
        $this->obj = new Order();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function create(){}
    public function update(){}
    public function delete(){}
    public function sort(){}
    public function publish(){}
    public function audit() {}

    public function update_trade_status()
    {
        $this->input['trade_number'] = trim($this->input['trade_number']);
        $this->input['status'] = trim($this->input['status']);
        if (!$this->input['trade_number'])
        {
            $this->errorOutput('NO TRADE_NUMBER');
        }

        if (!$this->input['status'])
        {
            $this->errorOutput('NO STATUS');
        }
        $this->order_info = $this->obj->order_info($this->input['trade_number']);

        $funname = 'update_' . strtolower(trim($this->input['status']));
        $this->$funname();

        $this->addItem('success');
        $this->output();
    }

    private function update_not_pay()
    {
        $data = array('trade_status' => 'NOT_PAY');
        $this->db->update_data($data, 'orders', " trade_number='".$this->input['trade_number']."'");
    }

    private function update_has_pay()
    {
        $data = array('trade_status' => 'HAS_PAY', 'trade_deal_time' => TIMENOW);
        $this->db->update_data($data, 'orders', " trade_number='".$this->input['trade_number']."'");
    }

    private function update_has_deliver()
    {
        $data = array('trade_status' => 'HAS_DELIVER', 'trade_delivery_time' => TIMENOW);
        $this->db->update_data($data, 'orders', " trade_number='".$this->input['trade_number']."'");
    }

    private function update_trade_success()
    {
        $data = array('trade_status' => 'TRADE_SUCCESS', 'trade_confirm_time' => TIMENOW);
        $this->db->update_data($data, 'orders', " trade_number='".$this->input['trade_number']."'");
    }

    private function update_trade_cancled()
    {
        $data = array('trade_status' => 'TRADE_CANCLED');
        $this->db->update_data($data, 'orders', " trade_number='".$this->input['trade_number']."'");
    }

}
require_once ROOT_PATH . 'excute.php';