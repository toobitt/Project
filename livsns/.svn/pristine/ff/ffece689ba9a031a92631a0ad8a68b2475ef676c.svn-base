<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/22
 * Time: 上午12:01
 */
require_once('global.php');
define(SCRIPT_NAME, 'ReceiveAddressApi');
define('MOD_UNIQUEID','hogepay_order');
class ReceiveAddressApi extends adminReadBase
{
    public function __construct()
    {
        parent::__construct();
        include_once (CUR_CONF_PATH . 'lib/receive_address.class.php');
        $this->obj = new ReceiveAddress();
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
        $orderby = ' id DESC';
        $ret = $this->obj->select($condition, $orderby, $limit);

        foreach ((array) $ret as $k => $v)
        {
            $this->addItem($v);
        }
        $this->output();
    }

    public function detail()
    {
        $id = trim($this->input['id']);
        if ( !$id )
        {
            $this->errorOutput('NO ID');
        }

        $info = $this->obj->detail($id);
        $this->addItem($info);
        $this->output();
    }


    public function count()
    {
        $condition = $this->get_condition();
        $sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."receive_address WHERE 1 " . $condition;
        $total = $this->db->query_first($sql);
        echo json_encode($total);
    }

    private  function get_condition()
    {
        $where = '';

        if ($this->input['user_id'])
        {
            $where .= ' AND user_id =' . $this->user['user_id'];
        }

        if ($this->input['user_name'])
        {
            $where .= ' AND user_name =\'' . $this->user['user_id'] . '\'';
        }

        return $where;
    }

}

require_once (ROOT_PATH . 'excute.php');