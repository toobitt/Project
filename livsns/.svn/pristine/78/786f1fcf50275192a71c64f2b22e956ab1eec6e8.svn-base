<?php
class Paydatalog extends InitFrm {
    public function __construct() {
        parent::__construct();
        $this -> dao = new Core();
    }

    public function show() {
    }

    public function create($datas, $type = 'alipay') {
        $function = "formatdata_".$type;
        if(!method_exists($this, $function))
        {
            return false;
        }
        $params = $this->$function($datas);
        if(!$params)
        {
            return false;
        }
        $params['id'] = $this->dao->insert('paydata',$params);
        return $params;
    }

    private function formatdata_alipay($data) {
        if(!$data['trade_status'])
            return false;
        $params['discount'] = $data['discount'];
        $params['payment_type'] = $data['payment_type'];
        $params['trade_no'] = $data['trade_no'];
        $params['buyer_email'] = $data['buyer_email'];

        $params['gmt_create'] = strtotime($data['gmt_create']);
        $params['notify_type'] = $data['notify_type'];
        $params['quantity'] = $data['quantity'];
        $params['out_trade_no'] = $data['out_trade_no'];

        $params['seller_id'] = $data['seller_id'];
        $params['notify_time'] = strtotime($data['notify_time']);
        $params['trade_status'] = $data['trade_status'];
        $params['total_fee'] = $data['total_fee'];

        $params['gmt_payment'] = strtotime($data['gmt_payment']);
        $params['seller_email'] = $data['seller_email'];
        $params['gmt_close'] = strtotime($data['gmt_close']);
        $params['price'] = $data['price'];

        $params['buyer_id'] = $data['buyer_id'];
        $params['notify_id'] = $data['notify_id'];
        $params['lpip'] = $data['lpip'];
        
        $params['create_time'] = TIMENOW;
        $params['update_time'] = TIMENOW;

        return $params;
    }

    //更新基本信息
    public function update() {
    }

    //信息基本信息读取 $id
    public function detail() {

    }

    public function __destruct() {
        parent::__destruct();
    }

}
?>