<?php
/**
 * 微信支付通知类
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/20
 * Time: 下午2:32
 */
require_once('global.php');
file_put_contents('../cache/111222.txt', var_export($_POST,1),FILE_APPEND);
define('MOD_UNIQUEID', 'hogeorder');
define('SCRIPT_NAME', 'NotifyWeixin');
class NotifyWeixin extends InitFrm
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__construct();
    }


    public function show()
    {
        //查询支付配置
        $sql = "SELECT pay_config FROM ".DB_PREFIX."pay_config WHERE 1 AND pay_type = 'weixin'";
        $pay_type_info = $this->db->query_first($sql);
        if (empty($pay_type_info))
        {
            echo "fail";exit;
        }
        $pay_type_info['pay_config'] = $pay_type_info['pay_config'] ? unserialize($pay_type_info['pay_config']) : array();
        $pay_type_info['pay_config']['type'] = 'weixin';
        $pay_config['weixin'] = $pay_type_info['pay_config'];
        include_once (CUR_CONF_PATH . 'lib/pay/hg_pay.class.php');
        $hgPayFactory = hgPayFactory::get_instance($pay_config);
        $pay_driver = $hgPayFactory->get_driver('weixin');

        if ($pay_driver->verifySignature($_REQUEST))
        {

            //商户交易单号
            $out_trade_no = $_REQUEST["out_trade_no"];

            //财付通订单号
            $transaction_id = $_REQUEST["transaction_id"];

            //商品金额,以分为单位
            $total_fee = $_REQUEST["total_fee"];

            //如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
            $discount = $_REQUEST["discount"];

            //支付结果
            $trade_state = $_REQUEST["trade_state"];

            // 服务器签名验证成功
            if ($trade_state == '0') //交易成功
            {
                $sql = "SELECT app_uniqueid, order_type, out_trade_number,trade_number, trade_status, total_fee FROM ".DB_PREFIX."orders WHERE trade_number = '".$out_trade_no."'";
                $order_info = $this->db->query_first($sql);
                if ($order_info['trade_status'] == 'NOT_PAY')
                {
                    if (intval($order_info['total_fee'] * 100) != ($total_fee + $discount))
                    {
                        $trade_status = 'TRADE_EXCEPTION';
                    }
                    //更改订单状态
                    $info = array(
                        'trade_deal_time'   => time(),
                        'trade_status'      => $trade_status ? $trade_status : "HAS_PAY",
                    );
                    $condition = " trade_number = '".$out_trade_no."'";
                    $this->db->update_data($info,'orders', $condition);

                    //记录交易流水
                    $trade_flow = array(
                        'trade_number'      => $order_info['trade_number'],
                        'pay_platform'      => 'unionpay',
                        'trade_type'        => ($_REQUEST['is_refund'] == true) ? 1 : 2,   //付款
                        'qn'                => $_POST['qn'],
                        'trade_fee'         => (($total_fee + $discount) / 100),
                        'trade_time'        => TIMENOW,
                        "trade_status"      => "TRADE_SUCCESS",
                    );
                    $this->db->insert_data($trade_flow, 'order_bank_trade_flow');

                    if ($order_info['order_type'] == 'THIRD_PARTY')
                    {
                        //通知第三方订单
                        $sql = "SELECT * FROM ".DB_PREFIX."app_access WHERE app_uniqueid = '" . $order_info['app_uniqueid'] . "'";
                        $app = $this->db->query_first($sql);
                        if ( !empty($app) )
                        {
                            include_once (ROOT_PATH . 'lib/class/curl.class.php');
                            $curl = new curl($app['host'], $app['dir']);
                            file_put_contents('../cache/4444.txt', var_export($curl,1),FILE_APPEND);
                            $curl->setSubmitType('post');
                            $curl->setReturnFormat('json');
                            $curl->initPostData();
                            $curl->addRequestData('a', $app['pay_func']);
                            $curl->addRequestData('trade_number', $order_info['out_trade_number']);
                            $curl->addRequestData('total_fee', $order_info['total_fee']);
                            $ret = $curl->request($app['request_file']);
                            if ($ret['success'] == 1)
                            {
                                $this->db->update_data(array('out_trade_notify'=>1,'out_trade_notify_time' => TIMENOW), 'orders', " trade_number = '" . $order_info['trade_number'] . "'");
                            }
                            else
                            {
                                $this->db->update_data(array('out_trade_notify'=>0,'out_trade_notify_time' => TIMENOW), 'orders', " trade_number = '" . $order_info['trade_number'] . "'");
                            }
                        }
                    }

                    echo 'success';
                }
                else
                {
                    echo "fail";
                }
            }
            else
            {
                echo "fail";
            }
        }
        else
        {
            // 服务器签名验证成功
            echo "fail";
        }
    }
}
require_once ROOT_PATH . 'excute.php';



?>