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
define('MOD_UNIQUEID', 'mklog'); //模块标识

class mklogApi extends adminBase
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
        $this->obj          = new mkpublish();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
        $offset   = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count    = $this->input['count'] ? intval(urldecode($this->input['count'])) : 20;
        $plandata = $this->obj->get_mklog($offset, $count, $this->get_condition());
        $this->addItem($plandata);
        $this->output();
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "mklog WHERE 1 " . $this->get_condition();
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

    public function insert_mk()
    {
        $wait_ids = $this->input['id'];
        if (!$wait_ids)
        {
            $this->errorOutput('NO_IDS');
        }
        $waits = $this->obj->get_waits($wait_ids);
        foreach ($waits as $k => $v)
        {
            $idsarr[] = $v['id'];
        }
        if ($idsarr)
        {
            $this->obj->delete_wait(implode(',', $idsarr));
        }

        foreach ($waits as $k => $v)
        {
            //判断正在生成里有无此任务
            $this->obj->check_mking($v);
        }
    }

}

$out    = new mklogApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>