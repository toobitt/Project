<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-15
 * @encoding    UTF-8
 * @description 图标接口
 **************************************************************************/
define('MOD_UNIQUEID', 'app_plant');
require_once('global.php');
include_once(CUR_CONF_PATH . 'lib/iconManage.class.php');

class app_icon extends appCommonFrm
{
    private $api;
    public function __construct()
    {
        parent::__construct();
        $this->api = new iconManage();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
    }

    /**
     * 获取图标的列表
     *
     * @access public
     * @param  offset | count
     * @return array
     */
    public function show()
    {
        $offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
        $count = isset($this->input['count']) ? intval($this->input['count']) : 20;
        $data = array(
			'offset'    => $offset,
			'count'     => $count,
			'condition' => $this->condition()
        );
        $icon_info = $this->api->show($data);
        if ($icon_info)
        {
            foreach ($icon_info as $icon)
            {
                $this->addItem($icon);
            }
        }
        $this->output();
    }

    /**
     * 获取查询条件
     *
     * @access private
     * @param  type:类型
     * @return array
     */
    private function condition()
    {
        $type = trim(urldecode($this->input['type']));
        if (empty($type))
        {
            return array();
        }

        $info = $this->api->getCategoryIdByTag($type);
        if ($info)
        {
            return array(
    		    'category_id' => implode(',', $info)
            );
        }
    }
}

$out = new app_icon();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();