<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/24
 * Time: 下午1:50
 */
require_once ('./global.php');
define('MOD_UNIQUEID', 'hogeorder');
define('SCRIPT_NAME', 'OrderPayApi');
class OrderPayApi extends  outerUpdateBase
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->user['user_id'])
        {
            //$this->errorOutput('NO LOGIN');
        }
        include_once (CUR_CONF_PATH . 'lib/order.class.php');
        $this->obj = new Order();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function create()
    {}

    public function update(){}

    public function delete(){}

    public function to_pay()
    {
        $trade_number = $this->input['trade_number'];
        $pay_type = trim($this->input['pay_type']);
        if ( !$trade_number )
        {
            $this->errorOutput('NO TRADE_NUMBER');
        }
        if (!$pay_type)
        {
            $this->errorOutput('NO PAY_TYPE');
        }
        if ( !in_array($pay_type, array_keys($this->settings['pay_type'])))
        {
            $this->errorOutput('ERROR PAY_TYPE');
        }

        $sql = "SELECT * FROM ".DB_PREFIX."pay_config WHERE 1 AND pay_type = '".$pay_type."' AND status = 1";
        $pay_type_info = $this->db->query_first($sql);
        if (empty($pay_type_info))
        {
            $this->errorOutput('ERROR PAY_TYPE');
        }


        $sql = "SELECT * FROM ".DB_PREFIX."orders WHERE 1 AND trade_number = '".$trade_number."'";
        $order_info = $this->db->query_first($sql);
        if (empty($order_info))
        {
            $this->errorOutput('NO EXISTS ORDER');
        }

        if ( $order_info['trade_status'] != 'NOT_PAY' )
        {
            $this->errorOutput('ORDER CAN NOT PAY');
        }

        $order_info['title'] = $order_info['title'] ? $order_info['title'] : '订单';
        $order_info['trade_create_time'] = date('YmdHis', $order_info['trade_create_time']);
        $order_info['trade_expire_time'] = date('YmdHis', $order_info['trade_expire_time']);
        $order_info['total_fee'] = intval($order_info['total_fee'] * 100);

        $pay_type_info['pay_config'] = $pay_type_info['pay_config'] ? unserialize($pay_type_info['pay_config']) : array();
        $pay_type_info['pay_config']['type'] = $pay_type_info['pay_type'];
        $pay_config[$pay_type_info['pay_type']] = $pay_type_info['pay_config'];
        include_once (CUR_CONF_PATH . 'lib/pay/hg_pay.class.php');
        $hgPayFactory = hgPayFactory::get_instance($pay_config);
        $pay_driver = $hgPayFactory->get_driver($pay_type);
        $ret = $pay_driver->getPayParam( $order_info);

        if (empty($ret))
        {
            $this->errorOutput('FAILURE');
        }

        if ($ret['errno'])
        {
            $this->errorOutput($ret['errmsg']);
        }

        if (!$ret['sdk_param'])
        {
            $this->errorOutput('RETURN PARAM ERROR');
        }


        foreach ((array)$ret as $key => $val)
        {
            $this->addItem_withkey($key, $val);
        }
        $this->output();

    }

    public function __call($name, $arguments)
    {
        $this->errorOutput('No method');
    }

}
require_once (ROOT_PATH . 'excute.php');