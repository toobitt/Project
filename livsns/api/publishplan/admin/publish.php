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
define('MOD_UNIQUEID', 'publishplan_publish'); //模块标识
require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');

class publishApi extends adminBase
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
        parent::__construct();
        $this->pubplan     = new publishplan();
        $this->pub_content = new publishcontent();
        include(CUR_CONF_PATH . 'lib/publish.class.php');
        $this->obj         = new publish();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function update_content()
    {
        $bundle_id      = $this->input['bundle_id'];
        $module_id      = $this->input['module_id'];
        $content_fromid = intval($this->input['content_fromid']);
        if (!$bundle_id || !$module_id || !$content_fromid)
        {
            $this->errorOutput('NO_DATA');
        }
        $data                   = $this->input['data'];
        $data['content_fromid'] = $content_fromid;
        if ($this->input['delete_column_id'])
        {
            $data['delete_column_id'] = $this->input['delete_column_id'];
        }

        $sql      = "SELECT * FROM " . DB_PREFIX . "plan_set WHERE bundle_id='" . $bundle_id . "' AND module_id='" . $module_id . "' AND (struct_ast_id='' or struct_ast_id is null)";
        $plan_set = $this->db->query_first($sql);
        if (!$plan_set)
        {
            $this->errorOutput('NO_PLAN_SET');
        }
        $this->pubplan->setAttribute($plan_set['host'], $plan_set['path'], $plan_set['filename'], 'up_content');
        $this->pubplan->insert_pub_content_id($data);

        $this->addItem('ture');
        $this->output();
    }

    public function update_block_content()
    {
        $block      = $this->input['block'];
        $data      = $this->input['data'];
        if (!$data || !$block || !is_array($data) || !is_array($block))
        {
            $this->errorOutput('NO_DATA');
        }
        $con = $t = '';
        foreach($data as $k=>$v)
        {
            $con .= $t."(bundle_id='" . $k . "' AND module_id='".$v['module_id']."') ";
            $t = ' OR ';
        }
        if(!$con)
        {
            $this->errorOutput('NO_CON');
        }
        $sql      = "SELECT * FROM " . DB_PREFIX . "plan_set WHERE " . $con . " AND (struct_ast_id='' or struct_ast_id is null)";
        $info = $this->db->query($sql);
        while($row = $this->db->fetch_array($info))
        {
            $alldata = array();
            $this->pubplan->setAttribute($row['host'], $row['path'], $row['filename'], 'update_block_content');
            $alldata['data'] = $data[$row['bundle_id']]['content'];
            $alldata['block'] = $block;
            $this->pubplan->insert_pub_content_id($alldata);
        }
        $this->addItem('ture');
        $this->output();
    }
    
    public function get_content_by_fromid()
    {
        $from_id = intval($this->input['data']['from_id']);
        $bundle_id = ($this->input['data']['bundle_id']);
        $module_id = ($this->input['data']['module_id']);
        $struct_id = ($this->input['data']['struct_id']);
        if(!$from_id)
        {
            $this->addItem('NO_FROMID');
            $this->output();
        }
        $sql      = "SELECT * FROM " . DB_PREFIX . "plan_set WHERE bundle_id='" . $bundle_id . "' AND module_id='" . $module_id . "' AND (struct_ast_id='' or struct_ast_id is null)";
        $plan_set = $this->db->query_first($sql);
        
        $this->pubplan->setAttribute($plan_set['host'], $plan_set['path'], $plan_set['filename'], 'get_content');
        $result = $this->pubplan->get_content($from_id,0,0,1);
        if(is_array($result[0]))
        {
            $this->addItem($result[0]);
        }
        else
        {
            $this->addItem(array());
        }
        
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

$out    = new publishApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'check_plan';
}
$out->$action();
?>