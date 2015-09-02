<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/22
 * Time: 上午12:01
 */
require_once('global.php');
define(SCRIPT_NAME, 'AppAccessApi');
define('MOD_UNIQUEID','app_access');
class AppAccessApi extends adminReadBase
{
    public function __construct()
    {
        parent::__construct();
        include_once (CUR_CONF_PATH . 'lib/app_access.class.php');
        $this->obj = new AppAccess();
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
        $orderby = 'id DESC';
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
            $this->errorOutput('NO id');
        }

        $info = $this->obj->detail($id);
        $this->addItem($info);
        $this->output();
    }


    public function count()
    {
        $condition = $this->get_condition();
        $sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."app_access WHERE 1 " . $condition;
        $total = $this->db->query_first($sql);
        echo json_encode($total);
    }

    private  function get_condition()
    {
        $where = '';
        return $where;
    }

}

require_once (ROOT_PATH . 'excute.php');