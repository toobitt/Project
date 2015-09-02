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
 * @description webView接口
 **************************************************************************/
define('MOD_UNIQUEID', 'app_plant');
require_once('global.php');
include_once(CUR_CONF_PATH . 'lib/webView.class.php');

class app_webview extends appCommonFrm
{
    private $api;
    public function __construct()
    {
        parent::__construct();
        $this->api = new webView();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
    }

    /**
     * 获取webview的列表
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
			'offset' => $offset,
			'count' => $count,
			'condition' => $this->condition()
        );
        $webview_info = $this->api->show($data);
        $this->setXmlNode('webview_info', 'webview');
        if ($webview_info)
        {
            foreach ($webview_info as $webview)
            {
                $this->addItem($webview);
            }
        }
        $this->output();
    }

    /**
     * 根据条件获取webview的个数
     *
     * @access public
     * @param  无
     * @return array 例如：array('total' => 20)
     */
    public function count()
    {
        $condition = $this->condition();
        $info = $this->api->count($condition);
        echo json_encode($info);
    }

    /**
     * 根据某一个webview详情
     *
     * @access public
     * @param  id:webview的id
     * @return array
     */
    public function detail()
    {
        $id = intval($this->input['id']);
        if ($id <= 0)
        {
            $this->errorOutput(NOID);
        }

        $data = array('id' => $id);
        $webview_info = $this->api->detail('app_webview', $data);
        $this->addItem($webview_info);
        $this->output();
    }

    /**
     * 获取查询条件
     *
     * @access public
     * @param  无
     * @return array
     */
    private function condition()
    {
        return array();
    }
}

$out = new app_webview();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();