<?php
/**
 * 支付宝异步通知类
 * User: kangxiaoqiang
 * Date: 15/05/13
 * Time: 下午17:32
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
file_put_contents('../cache/notify.txt', var_export($_POST,1),FILE_APPEND);
define('MOD_UNIQUEID', 'hogeorder');
define('SCRIPT_NAME', 'NotifyAlipay');
class NotifyAlipay extends InitFrm
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
    	$_POST = array(
			  'discount' => '0.00',
			  'payment_type' => '1',
			  'subject' => '烟台总站 --- 潍坊',
			  'trade_no' => '2015051900001000460052699577',
			  'buyer_email' => '997313238@qq.com',
			  'gmt_create' => '2015-05-19 14:50:16',
			  'notify_type' => 'trade_status_sync',
			  'quantity' => '1',
			  'out_trade_no' => 'HG143201806422973',
			  'seller_id' => '2088311932496637',
			  'notify_time' => '2015-05-19 14:50:16',
			  'body' => '烟台总站 --- 潍坊',
			  'trade_status' => 'WAIT_BUYER_PAY',
			  'is_total_fee_adjust' => 'Y',
			  'total_fee' => '0.01',
			  'seller_email' => 'wifixz@126.com',
			  'price' => '0.01',
			  'buyer_id' => '2088202251232465',
			  'notify_id' => '3c3fb0e8c69af7e943ede698a34060814k',
			  'use_coupon' => 'N',
			  'sign_type' => 'RSA',
			  'sign' => 'OhYoy9zkSl800eFteXYG4JkJtNPVC++fbCM3gfcEfvAgpuaOl07v2VkH9KnwcGYYhMHp1GoZNh6h9SuPYBM5444BZgXtz1ilqUTpQBjuF5nZ+WA4Mnz4iEA0Ib+V7LDQIiIO5aM+JlGvpADwGKZ8EnVHjL2GA0ZMPpDMhWQy11s=',
		);
        //查询支付配置
        $sql = "SELECT pay_config FROM ".DB_PREFIX."pay_config WHERE 1 AND pay_type = 'alipay'";
        $pay_type_info = $this->db->query_first($sql);
        if (empty($pay_type_info))
        {
            echo "fail";exit;
        }
        $pay_type_info['pay_config'] = $pay_type_info['pay_config'] ? unserialize($pay_type_info['pay_config']) : array();
        $pay_type_info['pay_config']['type'] = 'alipay';
        $pay_config['alipay'] = $pay_type_info['pay_config'];
        include_once (CUR_CONF_PATH . 'lib/pay/hg_pay.class.php');
        $hgPayFactory = hgPayFactory::get_instance($pay_config);
        $pay_driver = $hgPayFactory->get_driver('alipay');
		//签名验证(RSA方式)  (参数: 待签名字符串;支付宝公钥;sign值)
		//这里只要将待签名数组按键值排序即可 (支付宝需要按字母升序)
        if ($pay_driver->verifyNotify($_POST))
        {
        	//验证订单号是不是我们自己的订单号
        	
        	echo 'PASS';exit;
            // 服务器签名验证成功
            if ($_POST['transStatus'] == '00') //交易成功
            {
                $sql = "SELECT app_uniqueid, order_type, out_trade_number,out_trade_info,trade_number, trade_status, total_fee FROM ".DB_PREFIX."orders WHERE trade_number = '".$_POST['orderNumber']."'";
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
                        'pay_platform'      => 'alipay',
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
                            $curl->addRequestData('out_trade_info', $order_info['out_trade_info']); //第三方订单详情
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