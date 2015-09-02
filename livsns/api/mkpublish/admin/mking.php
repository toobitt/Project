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
define('MOD_UNIQUEID', 'mkpublish'); //模块标识

class mkingApi extends adminBase
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
        if ($this->mNeedCheckIn && !$this->prms['manage'])
        {
            $this->errorOutput(NO_OPRATION_PRIVILEGE);
        }
        $offset   = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count    = $this->input['count'] ? intval(urldecode($this->input['count'])) : 20;
        $plandata = $this->obj->get_mk_plan($offset, $count, $this->get_condition());
        $this->addItem($plandata);
        $this->output();
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "mking WHERE 1 " . $this->get_condition();
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

    public function get_mking_plan()
    {
        $offset   = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count    = $this->input['count'] ? intval(urldecode($this->input['count'])) : 20;
        $plandata = $this->obj->get_mk_plan($offset, $count, '');
        foreach ($plandata as $k => $v)
        {
            $v['publish_time'] = date('Y-m-d H:i:s',$v['publish_time']);
            $this->addItem($v);
        }
        $this->output();
    }
    
    public function insert_plan()
    {
        $plan = $this->input['plan'];
        $this->obj->insert('mking',$plan);
    }
    
    public function delete()
    {
        $ids = $this->input['id'];
        $type = $this->input['type'];
        if($ids)
        {
            $this->obj->delete_mk($ids);
        }
        else if($type == 'all')
        {
            for($i=0;$i<20;$i++)
            {
                $sql = "TRUNCATE TABLE ".DB_PREFIX."mking";
                $this->db->query($sql);
            }
        }
        $this->addItem('true');
        $this->output();
    }
    
}

$out    = new mkingApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>