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
 * @description 后台应用接口
 **************************************************************************/
define('MOD_UNIQUEID', 'app_plant');
require_once('global.php');
include_once(CUR_CONF_PATH . 'lib/app.class.php');
include_once(ROOT_PATH . 'lib/class/material.class.php');

class apps extends appCommonFrm
{
    private $api;
    public function __construct()
    {
        parent::__construct();
        $this->api = new app();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
    }

    /**
     * 获取app的列表
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
        $app_info = $this->api->show($data);
        $app_client = $this->api->client();
        $app = array('info' => $app_info, 'client' => $app_client);
        $this->addItem($app);
        $this->output();
    }

    /**
     * 根据条件获取app的个数
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

    public function update()
    {

    }

    /**
     * 删除数据
     *
     * @access public
     * @param  id:app id 多个逗号分隔
     * @return array
     */
    public function delete()
    {
        $id = trim(urldecode($this->input['id']));
        $id_arr = explode(',', $id);
        $id_arr = array_filter($id_arr, 'filter_arr');
        if (!$id_arr)
        {
            $this->errorOutput(NOID);
        }

        $ids = implode(',', $id_arr);
        $app_info = $this->api->show(array('count' => -1, 'condition' => array('id' => $ids)));
        if (!$app_info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }

        $validate_ids = array();
        foreach ($app_info as $app)
        {
            $validate_ids[$app['id']] = $app['id'];
        }

        $validate_ids = implode(',', $validate_ids);
        //删除APP对应的引导图
        $this->api->delete('app_pic', array('app_id' => $validate_ids));
        //删除APP对应的模板属性值
        $this->api->delete('temp_value', array('app_id' => $validate_ids));
        //删除打包客户端关系
        $this->api->delete('client_relation', array('app_id' => $validate_ids));
        //删除打包记录
        $this->api->delete('publish_log', array('app_id' => $validate_ids));
        //删除缓存数据
        $this->api->delete('app_cache', array('app_id' => $validate_ids));
        //删除意见反馈
        $this->api->deleteFeedback($validate_ids);
        //删除模块
        include_once(CUR_CONF_PATH . 'lib/appModule.class.php');
        $mod_api = new appModule();
        $mod_info = $mod_api->show(array('count' => -1, 'condition' => array('app_id' => $validate_ids)));
        if ($mod_info)
        {
            $mod_ids = array();
            foreach ($mod_info as $mod)
            {
                $mod_ids[$mod['id']] = $mod['id'];
            }
            $mod_ids = implode(',', $mod_ids);
            //删除模块对应的界面属性值
            $this->api->delete('ui_value', array('module_id' => $mod_ids));
            //删除关联模块
            $this->api->delete('app_module', array('app_id' => $validate_ids));
        }
        //删除APP
        $result = $this->api->delete('app_info', array('id' => $validate_ids));
        $this->addItem($result);
        $this->output();
    }

    /**
     * 还原已废弃的APP
     *
     * @access public
     * @param  id:app id 多个逗号分隔
     * @return array
     */
    public function recover()
    {
        $id = trim(urldecode($this->input['id']));
        $id_arr = explode(',', $id);
        $id_arr = array_filter($id_arr, 'filter_arr');
        if (!$id_arr)
        {
            $this->errorOutput(NOID);
        }

        $ids     = implode(',', $id_arr);
        $result  = $this->api->update('app_info', array('del' => 0), array('id' => $ids));
        $this->addItem($result);
        $this->output();
    }

    /**
     * 查询条件
     *
     * @access private
     * @param  k
     * @return array
     */
    private function condition()
    {
        $name            = isset($this->input['k']) ? trim(urldecode($this->input['k'])) : '';
        $time            = isset($this->input['date_search']) ? intval($this->input['date_search']) : '';
        $start_time      = trim($this->input['start_time']);
        $end_time        = trim($this->input['end_time']);
        $client_id       = intval($this->input['c_id']);
        $by_package_time = intval($this->input['by_package_time']);
        $is_shelves		 = intval($this->input['is_shelves']);
        $data = array();
        if (!empty($name))
        {
            $data['keyword'] = $name;
        }

        if ($start_time)
        {
            $data['start_time'] = $start_time;
        }

        if ($end_time)
        {
            $data['end_time'] = $end_time;
        }

        if ($time)
        {
            $data['date_search'] = $time;
        }

        if ($client_id > 0)
        {
            $data['client_id'] = $client_id;
        }
        
        if($is_shelves)
        {
        	$data['is_shelves'] = $is_shelves;
        }

        //排序
        if($by_package_time == 1)//降序
        {
            $data['order'] = array('pack_time' => 'DESC');
        }
        elseif ($by_package_time == 2)//升序
        {
            $data['order'] = array('pack_time' => 'ASC');
        }
        return $data;
    }
    
    /**
     * 对某个用户的app添加 新增模块个数权限
     */
    public function editModuleNum()
    {
    	$uid = intval($this->input['user_id']);
    	$num = intval($this->input['module_num']);
    	$res = $this->api->update('app_info', array('max_module_num' => $num),array('user_id' => $uid));
    	if($res)
    	{
    		return json_encode(array('error' => '0','data' => $res));
    	}
    	else
    	{
    		return json_encode(array('error' => '1','data' =>array()));
    	}
    }
    
    /**
     * 存储 appstore上架地址
     */
    public function shelves()
    {
    	$id =intval($this->input['id']);
    	$appstore_address = $this->input['appstore_address'];
    	$res = $this->api->update('app_info',array('appstore_address' => $appstore_address),array('id' => $id));
    	$this->addItem("success");
    	$this->output();
    }
    
}

$out = new apps();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();