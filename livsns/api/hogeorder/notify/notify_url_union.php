<?php
/**
 * 银联支付通知类
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/20
 * Time: 下午2:32
 */
//$_POST = array (
//    'orderTime' => '20141205114633',
//    'settleDate' => '1204',
//    'orderNumber' => 'HG141775119442709',
//    'exchangeRate' => '0',
//    'signature' => 'ced075bb8200e434ad62f5d26fa27199',
//    'settleCurrency' => '156',
//    'signMethod' => 'MD5',
//    'transType' => '01',
//    'respCode' => '00',
//    'charset' => 'UTF-8',
//    'sysReserved' => '{traceTime=1205114633&acqCode=00215800&traceNumber=088310}',
//    'version' => '1.0.0',
//    'settleAmount' => '61600',
//    'transStatus' => '00',
//    'merId' => '880000000002996',
//    'qn' => '201412051146330883107',
//);

require_once('global.php');
file_put_contents('../cache/111222.txt', var_export($_POST,1),FILE_APPEND);
define('MOD_UNIQUEID', 'hogeorder');
define('SCRIPT_NAME', 'NotifyUnion');
class NotifyUnion extends InitFrm
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
        $sql = "SELECT pay_config FROM ".DB_PREFIX."pay_config WHERE 1 AND pay_type = 'unionpay'";
        $pay_type_info = $this->db->query_first($sql);
        if (empty($pay_type_info))
        {
            echo "fail";exit;
        }
        $pay_type_info['pay_config'] = $pay_type_info['pay_config'] ? unserialize($pay_type_info['pay_config']) : array();
        $pay_type_info['pay_config']['type'] = 'unionpay';
        $pay_config['unionpay'] = $pay_type_info['pay_config'];
        include_once (CUR_CONF_PATH . 'lib/pay/hg_pay.class.php');
        $hgPayFactory = hgPayFactory::get_instance($pay_config);
        $pay_driver = $hgPayFactory->get_driver('unionpay');

        if ($pay_driver->verifySignature($_POST))
        {
            // 服务器签名验证成功
            if ($_POST['transStatus'] == '00') //交易成功
            {
                $sql = "SELECT app_uniqueid, order_type, out_trade_number,trade_number, trade_status, total_fee FROM ".DB_PREFIX."orders WHERE trade_number = '".$_POST['orderNumber']."'";
                $order_info = $this->db->query_first($sql);
                if ($order_info['trade_status'] == 'NOT_PAY')
                {
                    if (intval($order_info['total_fee'] * 100) != $_POST['settleAmount'])
                    {
                        $trade_status = 'TRADE_EXCEPTION';
                    }
                    //更改订单状态
                    $info = array(
                        'trade_deal_time'   => time(),
                        'trade_status'      => $trade_status ? $trade_status : "HAS_PAY",
                    );
                    $condition = " trade_number = '".$_POST['orderNumber']."'";
                    $this->db->update_data($info,'orders', $condition);

                    //记录交易流水
                    $trade_flow = array(
                        'trade_number'      => $order_info['trade_number'],
                        'pay_platform'      => 'unionpay',
                        'trade_type'        => ($_POST['transType'] == '01') ? 1 : 2,   //1付款  2退款
                        'qn'                => $_POST['qn'],
                        'trade_fee'         => ($_POST['settleAmount'] / 100),
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
                            $curl->setSubmitType('post');
                            $curl->setReturnFormat('json');
                            $curl->initPostData();
                            $curl->addRequestData('a', $app['pay_func']);
                            $curl->addRequestData('trade_number', $order_info['out_trade_number']);
                            $curl->addRequestData('total_fee', $order_info['total_fee']);
                            $ret = $curl->request($app['request_file']);
                            if ($ret['success'] == 1)
                            {
                                $this->db->update_data(array('out_trade_notify'=>2,'out_trade_notify_time' => TIMENOW), 'orders', " trade_number = '" . $order_info['trade_number'] . "'");
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