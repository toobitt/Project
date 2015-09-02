<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/26
 * Time: 下午2:15
 */
require_once ('./global.php');
define('MOD_UNIQUEID', 'hogeorder');
define('SCRIPT_NAME', 'OrderApi');
class OrderApi extends outerReadBase {
    public function __construct()
    {
        parent::__construct();
        if (!$this->user['user_id'])
        {
            $this->errorOutput('NO LOGIN');
        }
        include_once  CUR_CONF_PATH . '/lib/order.class.php';
        $this->obj = new Order();
    }

    public function __destruct()
    {
        parent::__destruct();

    }

    public function show()
    {
        $offset = $this->input['offset'] ? intval($this->input['offset']) :0;
        $count = $this->input['count'] ? intval($this->input['count']) :20;
        $limit = $offset . ', ' . $count;
        $condition = $this->get_condition();
//        echo $condition;exit;
        $orderby = 'trade_create_time DESC';
        $ret = $this->obj->select($condition, $orderby, $limit);

        if($ret['out_trade_notify'] < 2 && $ret['out_trade_info'])
        {
            $ret['out_trade_info']['ticket_number'] =  '出票中';
            $ret['out_trade_info']['ticket_pwd'] = '请稍等';
        }
        else if($ret['out_trade_notify'] == 3)
        {
            $ret['out_trade_info']['ticket_number'] = '出票失败';
            $ret['out_trade_info']['ticket_pwd'] = '';
        }

        foreach ((array) $ret as $k => $v)
        {
            $this->addItem($v);
        }
        $this->output();
    }

    public function detail()
    {
        if (!$this->user['user_id'])
        {
            $this->errorOutput('NO LOGIN');
        }
        $trade_number = trim($this->input['trade_number']);
        if ( !$trade_number )
        {
            $this->errorOutput('NO TRADE_NUMBER');
        }

        $info = $this->obj->detail($trade_number);
        list($order, $item, $address, $pay_type) = $info;
        $this->addItem_withkey('order', $order);
        $this->addItem_withkey('item', $item);
        $this->addItem_withkey('address', $address);
        $this->addItem_withkey('pay_type', $pay_type);
        $this->output();
    }

    public function count(){}

    private  function get_condition()
    {
        $where = '';

        if ($this->user['user_id'])
        {
            $where .= ' AND user_id =' . $this->user['user_id'];
        }

        if (trim($this->input['app_uniqueid']))
        {
            $where .= 'AND app_uniqueid = \''.trim($this->input['app_uniqueid']).'\'';
        }

        if (trim($this->input['trade_status']))
        {
            $where .= 'AND trade_status = \''.trim($this->input['trade_status']).'\'';
        }

        return $where;
    }
}

require_once (ROOT_PATH . 'excute.php');