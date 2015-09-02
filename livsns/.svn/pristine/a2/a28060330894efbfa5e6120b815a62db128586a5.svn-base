<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/27
 * Time: 上午1:08
 */

class Order extends InitFrm
{
    function __construct()
    {
        parent::__construct();
    }
    function __destruct()
    {
        parent::__destruct();
    }

    public function select($where = '', $order = '', $limit = '', $group = '', $key = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $group = $group == '' ? '' : ' GROUP BY ' . $group;
        $limit = $limit == '' ? '' : ' LIMIT ' . $limit;

        $sql = 'SELECT *  FROM '.DB_PREFIX.'orders'
                . $where . $order . $group . $limit;
//        echo $sql;exit;
        $q = $this->db->query($sql);

        $ret = array();
        $trade_numbers = array();
        while( ($row = $this->db->fetch_array($q)) != false )
        {
            $trade_numbers[] = $row['trade_number'];
            $row['trade_create_time'] = date('Y-m-d H:i:s', $row['trade_create_time']);
            $row['trade_deal_time'] = $row['trade_deal_time'] ? date('Y-m-d H:i:s', $row['trade_deal_time']) : '';
            $row['trade_delivery_time'] = $row['trade_delivery_time'] ? date('Y-m-d H:i:s', $row['trade_delivery_time']) : '';
            $row['trade_confirm_time'] = $row['trade_confirm_time'] ? date('Y-m-d H:i:s', $row['trade_confirm_time']) : '';
            $row['trade_expire_time'] = $row['trade_expire_time'] ? date('Y-m-d H:i:s', $row['trade_expire_time']) : '';
            $row['extend'] = $row['extend'] ? unserialize($row['extend']) : array();
            $row['out_trade_info'] = $row['out_trade_info'] ? unserialize($row['out_trade_info']) : array();
            if (!empty($row['out_trade_info']))
            {
                $row['out_trade_info']['format_depart_time'] = date('Y-m-d H:i:s', strtotime(($row['out_trade_info']['depart_date'] . $row['out_trade_info']['depart_time'])));
            }
            $row['trade_status_text'] = $this->settings['trade_status'][$row['trade_status']];
            if ($key) {
                $ret[$row[$key]][] = $row;
            } else {
                $ret[] = $row;
            }
        }

        if (!empty($trade_numbers))
        {
            $trade_numbers = implode("','", $trade_numbers);
            $sql = "SELECT * FROM ".DB_PREFIX."order_items WHERE trade_number IN('".$trade_numbers."')";
            $q = $this->db->query($sql);
            $items = array();
            while ($row = $this->db->fetch_array($q))
            {
                $row['extend'] = $row['extend'] ? unserialize($row['extend']) : array();
                $tmp = explode('*', $row['product_id']);
                $row['departdate'] = $tmp[0];
                $row['cc'] = $tmp[1];
                $items[$row['trade_number']][] = $row;
            }
        }
        foreach ((array)$ret as $k => $v)
        {
            $ret[$k]['items'] = $items[$v['trade_number']];
        }
        return $ret;
    }

    function detail($trade_number)
    {
        $sql = "SELECT * FROM ".DB_PREFIX."orders
        WHERE trade_number = '".$trade_number."'";
        $order = $this->db->query_first($sql);

        if ( !empty($order) )
        {
            $order['out_trade_info'] =  $order['out_trade_info'] ? unserialize($order['out_trade_info']) : array();
            if (!empty($order['out_trade_info']))
            {
                $order['out_trade_info']['format_depart_time'] = date('Y-m-d H:i:s', strtotime(($order['out_trade_info']['depart_date'] . $order['out_trade_info']['depart_time'])));
            }
            if (!$order['out_trade_info']['full_price'])
            {
                $order['out_trade_info']['full_price'] = explode('-', $order['out_trade_info']['price']);
                asort($order['out_trade_info']['full_price']);
                $order['out_trade_info']['full_price'] = end($order['out_trade_info']['full_price']);
            }

            $order['countdown'] = $order['trade_expire_time'] - TIMENOW;
            $order['countdown_text'] = '请在%@分钟内完成网上支付,否则系统将自动取消本次交易。';
            $order['trade_status_text'] = $this->settings['trade_status'][$order['trade_status']];

            $order['trade_create_time_format'] = $order['trade_create_time'] ? date('Y-m-d H:i', $order['trade_create_time']) : '';
            $order['trade_deal_time_format'] = $order['trade_deal_time'] ? date('Y-m-d H:i', $order['trade_deal_time']) : '';
            $order['trade_confirm_time_format'] = $order['trade_confirm_time'] ? date('Y-m-d H:i', $order['trade_confirm_time']) : '';
            $order['trade_expire_time_format'] = $order['trade_expire_time'] ? date('Y-m-d H:i', $order['trade_expire_time']) : '';
            $order['out_trade_status_text'] = $this->settings['out_trade_status_text'][$order['out_trade_notify']];
        }

        $sql = "SELECT * FROM ".DB_PREFIX."order_items WHERE trade_number = '".$trade_number."'";
        $q = $this->db->query($sql);
        $item = array();
        while ($row = $this->db->fetch_array($q))
        {
            $row['extend'] = $row['extend'] ? unserialize($row['extend']) : array();
			$tmp = explode('*', $row['product_id']);
			$row['departdate'] = $tmp[0];
			$row['cc'] = $tmp[1];
            $item[] = $row;
        }
        $sql = "SELECT * FROM ".DB_PREFIX."order_address WHERE trade_number = '".$trade_number."'";
        $address = $this->db->query_first($sql);

        $sql = "SELECT pay_type FROM ".DB_PREFIX."app_access WHERE app_uniqueid = '".$order['app_uniqueid']."'";
        $app = $this->db->query_first($sql);
        $app['pay_type'] = $app['pay_type'] ? explode(',', $app['pay_type']) : array();


        $sql = "SELECT pay_type,pay_config FROM ".DB_PREFIX."pay_config WHERE status = 1";
        $q = $this->db->query($sql);
        $pay_type = array();
        while ($row = $this->db->fetch_array($q))
        {
            if (in_array($row['pay_type'], $app['pay_type']))
            {
                $row_config = $this->settings['pay_type'][$row['pay_type']];
                if($row['pay_type'] == 'alipay')
                {
                	$tmp = unserialize($row['pay_config']);
                	$row_config['notify_url'] = $tmp['notify_url'];
                }
                $pay_type[] = $row_config;
            }
        }
        return array(
            0 => $order,
            1 => $item,
            2 => $address,
            3 => $pay_type,
        );
    }


    function order_info($trade_number)
    {
        $sql = "SELECT * FROM ".DB_PREFIX."orders
        WHERE trade_number = '".$trade_number . "'";
        $order = $this->db->query_first($sql);

        if ( !empty($order) )
        {
            $order['out_trade_info'] =  $order['out_trade_info'] ? unserialize($order['out_trade_info']) : array();
            if (!empty($order['out_trade_info']))
            {
                $order['out_trade_info']['format_depart_time'] = date('Y-m-d H:i:s', strtotime(($order['out_trade_info']['depart_date'] . $order['out_trade_info']['depart_time'])));
            }
            if (!$order['out_trade_info']['full_price'])
            {
                $order['out_trade_info']['full_price'] = explode('-', $order['out_trade_info']['price']);
                asort($order['out_trade_info']['full_price']);
                $order['out_trade_info']['full_price'] = end($order['out_trade_info']['full_price']);
            }

            $order['countdown'] = $order['trade_expire_time'] - TIMENOW;
            $order['countdown_text'] = '请在%@分钟内完成网上支付,否则系统将自动取消本次交易。';
            $order['trade_status_text'] = $this->settings['trade_status'][$order['trade_status']];
        }

        return $order;
    }


    function get_trade_flow($trade_number)
    {
        if (!$trade_number)
        {
            return false;
        }
        $sql = "SELECT * FROM ".DB_PREFIX."order_bank_trade_flow
        WHERE trade_number = '".$trade_number . "'";
        $q = $this->db->query($sql);

        $trade_flow = array();

        while ( ($row = $this->db->fetch_array($q)) != false )
        {
            $row['trade_time'] = date('Y-m-d H:i', $row['trade_time']);
            $row['trade_deal_time'] = date('Y-m-d H:i', $row['trade_deal_time']);
            $row['trade_status_text'] = $this->settings['trade_flow_status'][$row['trade_status']];
            $row['trade_type_text'] = $row['trade_type'] == 1 ? '付款' : '退款';
            $trade_flow[] = $row;
        }

        return $trade_flow;

    }

}