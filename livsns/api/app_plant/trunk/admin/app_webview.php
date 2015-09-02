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
 * @description 后台webView接口
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
        $count  = isset($this->input['count']) ? intval($this->input['count']) : 20;
        $data   = array(
			'offset'    => $offset,
			'count'     => $count,
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
     * 创建数据
     *
     * @access public
     * @param  见:filter_data
     * @return array
     */
    public function create()
    {
        $data = $this->filter_data();
        //是否重名
        $check = $this->api->verify(array('name' => $data['name']));
        if ($check > 0)
        {
            $this->errorOutput(NAME_EXISTS);
        }

        $data['user_id']     = $this->user['user_id'];
        $data['user_name']   = $this->user['user_name'];
        $data['org_id']      = $this->user['org_id'];
        $data['create_time'] = TIMENOW;
        $data['ip']          = hg_getip();
        $result              = $this->api->create('app_webview', $data);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 更新数据
     *
     * @access public
     * @param  id:webview id
     * @return array
     */
    public function update()
    {
        $id = intval($this->input['id']);
        if ($id <= 0)
        {
            $this->errorOutput(NOID);
        }
        $webview_info = $this->api->detail('app_webview', array('id' => $id));
        if (!$webview_info)
        {
            $this->errorOutput(WEBVIEW_NOT_EXISTS);
        }

        $data = $this->filter_data();
        $validate = array();
        if ($webview_info['name'] != $data['name'])
        {
            //是否重名
            $check = $this->api->verify(array('name' => $data['name']));
            if ($check > 0)
            {
                $this->errorOutput(NAME_EXISTS);
            }
            $validate['name'] = $data['name'];
        }

        if ($webview_info['brief'] != $data['brief'])
        {
            $validate['brief'] = $data['brief'];
        }

        if ($webview_info['url'] != $data['url'])
        {
            $validate['url'] = $data['url'];
        }

        if ($validate)
        {
            $result = $this->api->update('app_webview', $validate, array('id' => $id));
        }
        else
        {
            $result = true;
        }
        $this->addItem($result);
        $this->output();
    }

    /**
     * 删除数据
     *
     * @access public
     * @param  id:webview id 多个逗号分隔
     * @return array
     */
    public function delete()
    {
        $id     = trim(urldecode($this->input['id']));
        $id_arr = explode(',', $id);
        $id_arr = array_filter($id_arr, 'filter_arr');
        if (!$id_arr)
        {
            $this->errorOutput(NOID);
        }

        $ids = implode(',', $id_arr);
        $this->api->update('app_module', array('web_url' => ''), array('web_view' => $ids));
        $result = $this->api->delete('app_webview', array('id' => $ids));
        $this->addItem($result);
        $this->output();
    }

    /**
     * 过滤数据
     *
     * @access private
     * @param  module_name:模块名称
     * @param  module_brief:模块描述
     * @param  module_url:模块url
     * @return array
     */
    private function filter_data()
    {
        $module_name  = trim(urldecode($this->input['module_name']));
        $module_brief = trim(urldecode($this->input['module_brief']));
        $module_url   = trim(urldecode($this->input['module_url']));
        if (empty($module_name))
        {
            $this->errorOutput(NO_MODULE_NAME);
        }

        if (empty($module_url))
        {
            $this->errorOutput(NO_MODULE_URL);
        }

        $data = array(
			'name'  => $module_name,
		    'brief' => $module_brief,
			'url'   => $module_url
        );
        return $data;
    }

    /**
     * 获取查询条件
     *
     * @access public
     * @param  k:查询关键字
     * @return array
     */
    private function condition()
    {
        $keyword = trim(urldecode($this->input['k']));
        return array(
			'keyword' => $keyword
        );
    }
}

$out = new app_webview();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();