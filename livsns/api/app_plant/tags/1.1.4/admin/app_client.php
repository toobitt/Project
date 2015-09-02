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
 * @description 后台应用客户端接口
 **************************************************************************/
define('MOD_UNIQUEID', 'app_plant');
require_once('global.php');
include_once(CUR_CONF_PATH . 'lib/appClient.class.php');

class app_client extends appCommonFrm
{
    private $api;
    public function __construct()
    {
        parent::__construct();
        $this->api = new appClient();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
    }

    /**
     * 显示数据
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
        $appClient_info = $this->api->show($data);
        $this->setXmlNode('appClient_info', 'client');
        if ($appClient_info)
        {
            foreach ($appClient_info as $client)
            {
                $this->addItem($client);
            }
        }
        $this->output();
    }

    /**
     * 根据条件获取客户端类型的个数
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
     * 根据某一个客户端类型详情
     *
     * @access public
     * @param  id:客户端类型id
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
        $appClient_info = $this->api->detail('app_client', $data);
        $this->addItem($appClient_info);
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
        //名称是否重复
        $check = $this->api->verify(array('name' => $data['name']));
        if ($check > 0)
        {
            $this->errorOutput(NAME_REPEAT);
        }

        //标识是否重复
        $check = $this->api->verify(array('mark' => $data['mark']));
        if ($check > 0)
        {
            $this->errorOutput(MARK_EXISTS);
        }

        $data['user_id']       = $this->user['user_id'];
        $data['user_name']     = $this->user['user_name'];
        $data['org_id']        = $this->user['org_id'];
        $data['create_time']   = TIMENOW;
        $data['ip']            = hg_getip();
        $result                = $this->api->create('app_client', $data);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 更新数据
     *
     * @access public
     * @param  见:filter_data
     * @return array
     */
    public function update()
    {
        $id = intval($this->input['id']);
        if ($id <= 0)
        {
            $this->errorOutput(NOID);
        }

        $appClient_info = $this->api->detail('app_client', array('id' => $id));
        if (!$appClient_info)
        {
            $this->errorOutput(CLIENT_INFO_NOT_EXISTS);
        }

        $data = $this->filter_data();
        $validate = array();
        if ($appClient_info['name'] != $data['name'])
        {
            //名称是否重复
            $check = $this->api->verify(array('name' => $data['name']));
            if ($check > 0)
            {
                $this->errorOutput(NAME_REPEAT);
            }
            $validate['name'] = $data['name'];
        }

        if ($appClient_info['mark'] != $data['mark'])
        {
            //标识是否重复
            $check = $this->api->verify(array('mark' => $data['mark']));
            if ($check > 0)
            {
                $this->errorOutput(MARK_EXISTS);
            }
            $validate['mark'] = $data['mark'];
        }

        if ($appClient_info['url'] != $data['url'])
        {
            $validate['url'] = $data['url'];
        }

        if ($validate)
        {
            $result = $this->api->update('app_client', $validate, array('id' => $id));
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
     * @param  id:多个可以用逗号分隔
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
        $relation_info = $this->api->detail('client_relation', array('client_id' => $ids));
        if ($relation_info)
        {
            $this->errorOutput(NO_VERSION_INFO);
        }

        $result = $this->api->delete('app_client', array('id' => $ids));
        $this->addItem($result);
        $this->output();
    }

    /**
     * 过滤数据
     *
     * @access public
     * @param  client_name:客户端名称
     *         client_mark:客户端标识
     *         client_url:客户端url
     * @return array
     */
    private function filter_data()
    {
        $client_name = trim(urldecode($this->input['client_name']));
        $client_mark = trim(urldecode($this->input['client_mark']));
        $client_url  = trim(urldecode($this->input['client_url']));
        if (empty($client_name))
        {
            $this->errorOutput(NO_CLIENT_NAME);
        }

        if (empty($client_mark))
        {
            $this->errorOutput(NO_CLIENT_MARK);
        }

        $data = array(
			'name' => $client_name,
		    'mark' => $client_mark,
			'url'  => $client_url
        );
        return $data;
    }

    /**
     * 获取查询条件
     *
     * @access private
     * @param  无
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

$out = new app_client();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();