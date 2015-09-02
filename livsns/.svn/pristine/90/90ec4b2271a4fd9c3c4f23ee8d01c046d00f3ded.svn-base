<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','OutTradeNotify');
define('SCRIPT_NAME', 'OutTradeNotify');
class OutTradeNotify extends cronBase
{

    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,
            'name' => '外部订单通知',
            'brief' => '外部订单通知',
            'space' => '30', //运行时间间隔，单位秒
            'is_use' => 1,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }

    public function show()
    {
        $sql = "SELECT app_uniqueid, trade_number, out_trade_number, total_fee FROM ".DB_PREFIX."orders
            WHERE order_type = 'THIRD_PARTY' AND trade_status = 'HAS_PAY' AND out_trade_notify IN(0,1)
            ORDER BY out_trade_notify_time ASC LIMIT 1";
        $order = $this->db->query_first($sql);
        //通知第三方订单
        $sql = "SELECT * FROM ".DB_PREFIX."app_access WHERE app_uniqueid = '" . $order['app_uniqueid'] . "'";
        $app = $this->db->query_first($sql);
        if ( !empty($app) )
        {
            $curl = new curl($app['host'], $app['dir']);
            $curl->setSubmitType('post');
            $curl->setReturnFormat('json');
            $curl->initPostData();
            $curl->addRequestData('a', $app['order_detail_func']);
            $curl->addRequestData('trade_number', $order['out_trade_number']);
            $curl->addRequestData('total_fee', $order['total_fee']);
            $ret = $curl->request($app['request_file']);
            if ($ret['success'] == 1)
            {
            	$status = $ret['data']['status'];
            	if ($status == 0)
            	{
					$curl->initPostData();
					$curl->addRequestData('a', $app['pay_func']);
					$curl->addRequestData('trade_number', $order['out_trade_number']);
					$curl->addRequestData('total_fee', $order['total_fee']);
					$ret = $curl->request($app['request_file']);
            	}
                $this->db->update_data(array('out_trade_notify'=>$status,'out_trade_notify_time' => TIMENOW), 'orders', " trade_number = '" . $order['trade_number'] . "'");
            }
            else
            {
                $this->db->update_data(array('out_trade_notify'=>0,'out_trade_notify_time' => TIMENOW), 'orders', " trade_number = '" . $order['trade_number'] . "'");
            }
        }

        var_dump($ret);
        var_dump($order);
    }
}

require_once ROOT_PATH . 'excute.php';

?>
