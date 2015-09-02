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
 * @description 固化模块接口
 **************************************************************************/
define('MOD_UNIQUEID', 'app_plant');
require_once('global.php');
include_once(CUR_CONF_PATH . 'lib/solidify.class.php');

class app_solidify extends appCommonFrm
{
    private $api;
    public function __construct()
    {
        parent::__construct();
        $this->api = new solidify();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
    }

    /**
     * 获取固化模块的列表
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
        $solidify_info = $this->api->show($data);
        $this->setXmlNode('solidify_info', 'solidify');
        if ($solidify_info)
        {
            foreach ($solidify_info as $solidify)
            {
                $this->addItem($solidify);
            }
        }
        $this->output();
    }

    /**
     * 根据条件获取固化模块的个数
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
     * 获取某一个固化模块详情
     *
     * @access public
     * @param  id:固化模块的id
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
        $solidify_info = $this->api->detail('solidify_module', $data);
        if ($solidify_info['pic'] && unserialize($solidify_info['pic']))
        {
            $solidify_info['pic'] = unserialize($solidify_info['pic']);
        }
        $this->addItem($solidify_info);
        $this->output();
    }

    /**
     * 获取固化模块配置
     *
     * @access public
     * @param  solidify_id:固化模块的id
     * 		   access_token:登陆令牌
     * @return array
     */
    public function get_solidify_config()
    {
        $solidify_id = $this->input['solidify_id'];
        $user_id = $this->input['user_id'];
        if(!$solidify_id)
        {
            $this->errorOutput(NO_SOLIDIFY_ID);
        }

        if(!$user_id)
        {
            $this->errorOutput(NO_USER_ID);
        }

        //查询出对应的参数
        $cond = " AND user_id = '" .$user_id. "' AND solidify_id = '" .$solidify_id. "' ";
        $param = $this->api->get_config_param($cond);
        $this->addItem($param);
        $this->output();
    }

    /**
     * 创建固化模块配置
     *
     * @access public
     * @param  solidify_id:固化模块的id
     * 		   user_id:用户id
     * 		   param:配置参数 （array）
     * @return array
     */
    public function create_solidify_config()
    {
        $solidify_id = $this->input['solidify_id'];
        $user_id = $this->input['user_id'];
        $param = $this->input['param'];
        if(!$solidify_id)
        {
            $this->errorOutput(NO_SOLIDIFY_ID);
        }

        if(!$user_id)
        {
            $this->errorOutput(NO_USER_ID);
        }

        if(!$param)
        {
            $this->errorOutput(NO_SOLIDIFY_PARAM);
        }

        $arr = array(
			'user_id'	  => $user_id,
			'solidify_id' => $solidify_id,
			'param'	      => addslashes(serialize($param)),
        );

        $ret = $this->api->create_solidify_config($arr);
        $this->addItem($ret);
        $this->output();
    }

    /**
     * 更新固化模块配置
     *
     * @access public
     * @param  id:固化模块配置的id
     * 		   param:配置参数 （array）
     *
     * @return array
     */
    public function update_solidify_config()
    {
        $id = $this->input['id'];
        $param = $this->input['param'];

        if(!$id)
        {
            $this->errorOutput(NO_CONFIG_ID);
        }

        if(!$param)
        {
            $this->errorOutput(NO_SOLIDIFY_PARAM);
        }

        $arr = array(
			'param'	=> addslashes(serialize($param)),
        );

        $ret = $this->api->update_solidify_config($id,$arr);
        $this->addItem($ret);
        $this->output();
    }

    /**
     * 获取某人的固化模块
     *
     * @access public
     * @param  user_id:用户的id
     *
     * @return array
     */
    public function get_solidify_by_user()
    {
        if(!$this->input['user_id'])
        {
            $this->errorOutput(NO_USER_ID);
        }

        $ret = $this->api->get_solidify_by_user($this->input['user_id']);
        $this->addItem($ret);
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

$out = new app_solidify();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();