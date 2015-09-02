<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/22
 * Time: 上午12:01
 */
require_once('global.php');
define(SCRIPT_NAME, 'OrderApi');
define('MOD_UNIQUEID','order_list');
class OrderApi extends adminReadBase
{
    public function __construct()
    {
        parent::__construct();
        include_once (CUR_CONF_PATH . 'lib/order.class.php');
        $this->obj = new Order();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function index(){}

    public function show()
    {
        $offset = $this->input['offset'] ? intval($this->input['offset']) :0;
        $count = $this->input['count'] ? intval($this->input['count']) :20;
        $limit = $offset . ', ' . $count;
        $condition = $this->get_condition();
        $orderby = 'trade_create_time DESC';
        $ret = $this->obj->select($condition, $orderby, $limit);

        foreach ((array) $ret as $k => $v)
        {
            $this->addItem($v);
        }
        $this->output();
    }

    public function detail()
    {
        $trade_number = trim($this->input['trade_number']);
        $trade_number = $trade_number ? $trade_number : $this->input['id'];
        if ( !$trade_number )
        {
            $this->errorOutput('NO TRADE_NUMBER');
        }

        $info = $this->obj->detail($trade_number);
        $ret = array();
        list($ret['order'], $ret['item'], $ret['address'], $ret['pay_type']) = $info;
        $ret['trade_flow'] = $this->obj->get_trade_flow($trade_number);
        $this->addItem($ret);
        $this->output();
    }


    public function count()
    {
        $condition = $this->get_condition();
        $sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."orders WHERE 1 " . $condition;
        $total = $this->db->query_first($sql);
        echo json_encode($total);
    }

    private  function get_condition()
    {
        $where = '';

        if (trim($this->input['_id']))
        {
            $where .= ' AND trade_status = \''.trim($this->input['_id']).'\'';
        }

        if ($this->input['user_id'])
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

        if (trim($this->input['trade_number']))
        {
            $where .= 'AND trade_number = \''.trim($this->input['trade_number']).'\'';
        }

        if (trim($this->input['key']))
        {
            $where .= 'AND trade_number = \''.trim($this->input['key']).'\'';
        }

        return $where;
    }

}

require_once (ROOT_PATH . 'excute.php');