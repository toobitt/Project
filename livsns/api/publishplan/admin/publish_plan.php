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
define('MOD_UNIQUEID', 'publish_plan'); //模块标识

class publish_planApi extends adminBase
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
        include(CUR_CONF_PATH . 'lib/publish_plan.class.php');
        $this->obj          = new publish_plan();
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
        $count    = $this->input['count'] ? intval(urldecode($this->input['count'])) : 15;
        $plandata = $this->obj->get_plan($offset, $count, $this->get_condition());
        $this->addItem($plandata);
        $this->output();
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "plan " . $this->get_condition();
        echo json_encode($this->db->query_first($sql));
    }

    private function get_condition()
    {
        $action_type = urldecode($this->input['client_type']);
        if ($action_type && $action_type != '-1')
        {
            $condition = " WHERE action_type='" . $action_type . "'";
        }
        return $condition;
    }

    public function insert_queue()
    {
        $data = $this->input['data'];
        if (empty($data['set_id']) || empty($data['from_id']) || empty($data['action_type']))
        {
            $result['msg']   = '相关信息未传入';
            $result['error'] = '2';
            $this->addItem($result);
            $this->output();
        }
        if($data['action_type']=='insert')
        {
            if(empty($data['column_id']))
            {
                $this->errorOutput('NO_COLUMN_ID');
            }
        }
        $data['publish_time'] = $data['publish_time']?$data['publish_time']:TIMENOW;

        //检查队列，如有发布这条队列，之后又增加了此内容的删除队列，则删除这两个队列
        $find_arr = array(
            'set_id' => $data['set_id'],
            'from_id' => $data['from_id'],
            'column_id' => $data['column_id'],
            'action_type' => $data['action_type'],
        );
        $sql      = "SELECT id FROM " . DB_PREFIX . "plan WHERE 1 ";
        $con = '';
        foreach($find_arr as $k=>$v)
        {
            $con .= ' AND '.$k.'=\''.$v.'\'';
        }
        $sql .= $con;
        $info = $this->db->query_first($sql);
        if($info)
        {
            $sql = "UPDATE ".DB_PREFIX."plan set publish_time='".$data['publish_time']."' WHERE id=".$info['id'];
            $this->db->query($sql);
            exit;
        }

        $queuedata     = array(
            'fid' => 0,
            'set_id' => $data['set_id'],
            'from_id' => $data['from_id'],
            'sort_id' => $data['sort_id'],
            'column_id' => $data['column_id'],
            'title' => $data['title'],
            'action_type' => $data['action_type'],
            'delete_all' => $data['delete_all'],
            'publish_time' => $data['publish_time'],
            'publish_user' => $data['publish_people'] ? $data['publish_people'] : $this->user['user_name'],
            'ip' => $data['ip'],
            'status' => 1,
        );
        $this->obj->insert_queue($queuedata);
        $result['msg'] = 'ok';
        $this->addItem($result);
        $this->output();
    }

    public function get_plan_set()
    {
        $ids = $this->input['ids'];
        if (empty($ids))
        {
            $result['error'] = '1';
            $this->addItem($result);
            $this->output();
        }
        $result = $this->obj->get_plan_set($ids);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 空方法
     * @name unknow
     * @access public
     * @author repheal
     * @category hogesoft
     * @copyright hogesoft
     */
    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new publish_planApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>