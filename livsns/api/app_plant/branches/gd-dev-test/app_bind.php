<?php
/***************************************************************************
 * HOGE WEB
 *
 * @package     DingDone WEB
 * @author      RDC3 - YaoJian
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-9-25
 * @encoding    UTF-8
 * @description 绑定数据
 **************************************************************************/
define('MOD_UNIQUEID', 'app_bind');
require_once('global.php');
include_once(CUR_CONF_PATH . 'lib/appBind.class.php');

class app_bind extends appCommonFrm
{
    private $api;
    
    public function __construct()
    {
        parent::__construct();
        $this->api = new appBind();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
    }
    
    /**
     * 获取列表数据
     */
    public function show()
    {
        $offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
        $count  = isset($this->input['count']) ? intval($this->input['count']) : 20;
        $data   = array(
			'offset'    => $offset,
			'count'     => $count,
			'condition' => $this->condition(),
            'fields'    => 'id,name,domain'
        );
        $bindInfo = $this->api->show($data);
        if ($bindInfo)
        {
            foreach ($bindInfo as $bind)
            {
                $this->addItem($bind);
            }
        }
        $this->output();
    }
    
    private function condition()
    {
        return array(
            'status' => 1,
            'order'  => array(
                'sort_id' => 'ASC'
            )
        );
    }
}

$out = new app_bind();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>