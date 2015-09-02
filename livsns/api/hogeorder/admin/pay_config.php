<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/22
 * Time: 12:01
 */
require_once('global.php');
define(SCRIPT_NAME, 'payConfApi');
define('MOD_UNIQUEID','PayConfApi');
class PayConfApi extends adminReadBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function index(){}

    public function show()
    {
        $pay_type = $this->settings['pay_type'];
        $sql = "SELECT pay_type, status FROM ".DB_PREFIX."pay_config";
        $q = $this->db->query($sql);
        $config = array();
        while ($row = $this->db->fetch_array($q))
        {
            $config[$row['pay_type']] = $row;
        }
        foreach ((array)$pay_type as $k => $v)
        {
            $v['status'] = intval($config[$v['uniqueid']]['status']);
            $v['status_text'] = $config[$v['uniqueid']]['status'] ? '启用' : '未启用';
            $this->addItem($v);
        }
        $this->output();
    }

    public function count(){}

    public function detail()
    {
        $pay_type = trim($this->input['pay_type']);
        if (!$pay_type)
        {
            $this->errorOutput('No pay_type');
        }

        $sql = "SELECT * FROM ".DB_PREFIX."pay_config WHERE pay_type='".$pay_type."'" ;
        $ret = $this->db->query_first($sql);

        $ret['title'] = $this->settings['pay_type'][$pay_type]['title'];
        $ret['pay_type'] = $pay_type;
        $ret['pay_config'] = $ret['pay_config'] ? unserialize($ret['pay_config']) : array();

        $this->addItem($ret);
        $this->output();
    }

    public function setting()
    {
        $data = array(
            'pay_type' => $this->input['pay_type'],
            'pay_config' => $this->input['pay_config'] ? serialize($this->input['pay_config']) : '',
            'status'     => $this->input['status'],
        );

        $sql = "REPLACE INTO ".DB_PREFIX."pay_config (pay_type, pay_config, status)
                VALUES ('{$data['pay_type']}', '{$data['pay_config']}', '{$data['status']}')";
        $this->db->query($sql);

//        //写入缓存
//        include_once(ROOT_PATH . 'lib/class/cache/cache.class.php');
//        $cache_factory = cache_factory::get_instance($this->settings['cache_type_config']);
//        $cache_driver = $cache_factory->get_cache_driver('file');
//        $cache_id = md5('app_access_' . $data['pay_type']);
//        $cache_driver->set($cache_id, $data);
//        //写入缓存

        $this->addItem('success');
        $this->output();
    }

}

require_once (ROOT_PATH . 'excute.php');