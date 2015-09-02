<?php

/* * *************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * @public function show|detail|count|unknow
 * @private function get_condition
 *
 * $Id: news.php 6930 2012-05-31 07:16:07Z repheal $
 * ************************************************************************* */
require('global.php');
define('MOD_UNIQUEID', 'mkcomplete'); //模块标识

class mkcompleteApi extends adminBase
{

    /**
     * 构造函数
     * @author repheal
     * @category hogesoft
     * @copyright hogesoft
     * @include news.class.php
     */
    public function __construct()
    {
        $this->mPrmsMethods = array(
            'manage' => '管理',
        );
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/mkpublish.class.php');
        $this->obj = new mkpublish();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
        $offset   = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count    = $this->input['count'] ? intval(urldecode($this->input['count'])) : 20;
        $plandata = $this->obj->get_mkcomplete_plan($offset, $count, $this->get_condition());
        $this->addItem($plandata);
        $this->output();
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "mking_complete WHERE 1" . $this->get_condition()." ORDER BY publish_time DESC ";
        echo json_encode($this->db->query_first($sql));
    }

    private function get_condition()
    {
        $condition = ' ';
        if($k = $this->input['k'])
        {
            $condition .= " AND title like '%".$k."%' ";
        }
        return $condition;
    }

    public function check_is_mk()
    {
        $ret = array();
        $rid = intval($this->input['rid']);
        if ($rid)
        {
            $sql = 'select id from ' . DB_PREFIX . 'mking_complete where rid=' . $rid;
            $ret = $this->db->query_first($sql);
        }
        $this->addItem($ret);
        $this->output();
    }

}

$out    = new mkcompleteApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>