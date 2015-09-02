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
 * @description 后台图标分类接口
 **************************************************************************/
define('MOD_UNIQUEID', 'app_plant');
require_once('global.php');
include_once(CUR_CONF_PATH . 'lib/iconCategory.class.php');

class icon_category extends appCommonFrm
{
    private $api;
    public function __construct()
    {
        parent::__construct();
        $this->api = new iconCategory();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
    }

    /**
     * 获取图标分类的列表
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
        $category_info = $this->api->show($data);
        $this->setXmlNode('category_info', 'category');
        if ($category_info)
        {
            foreach ($category_info as $category)
            {
                $this->addItem($category);
            }
        }
        $this->output();
    }

    /**
     * 根据条件获取图标分类的个数
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
     * 获取某一个图标分类详情
     *
     * @access public
     * @param  id:图标分类的id
     * @return array
     */
    public function detail()
    {
        $id = intval($this->input['id']);
        if ($id <= 0)
        {
            $this->errorOutput(NOID);
        }
        $queryData = array('id' => $id);
        $category_info = $this->api->detail('icon_category', $queryData);
        $this->addItem($category_info);
        $this->output();
    }

    /**
     * 创建图标分类
     *
     * @access public
     * @param  filter_data
     * @return array
     */
    public function create()
    {
        $data = $this->filter_data();
        //验证标识是否重复
        $validateData = array(
		    'mark' => $data['mark']
        );
        $check = $this->api->verify($validateData);
        if ($check > 0)
        {
            $this->errorOutput(MARK_EXISTS);
        }
        $data['user_id']     = $this->user['user_id'];
        $data['user_name']   = $this->user['user_name'];
        $data['org_id']      = $this->user['org_id'];
        $data['create_time'] = TIMENOW;
        $data['ip']          = hg_getip();
        $result              = $this->api->create('icon_category', $data);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 更新数据
     *
     * @access public
     * @param  id:图标分类id
     * @return array
     */
    public function update()
    {
        $id         = intval($this->input['id']);
        $queryData  = array('id' => $id);
        $info       = $this->api->detail('icon_category', $data);
        if (!$info)
        {
            $this->errorOutput(OBJECT_NULL);
        }

        $data = $this->filter_data();
        $validate = array();
        if ($info['name'] != $data['name'])
        {
            $validate['name'] = $data['name'];
        }

        if ($info['mark'] != $data['mark'])
        {
            //验证标识是否重复
            $validateData = array(
    		    'mark' => $data['mark']
            );
            $check = $this->api->verify($validateData);
            if ($check > 0)
            {
                $this->errorOutput(MARK_EXISTS);
            }
            $validate['mark'] = $data['mark'];
        }

        if ($validate)
        {
            $result = $this->api->update('icon_category', $validate, $queryData);
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
     * @access private
     * @param  id:分类图标id
     *
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
        //删除分类对应的图片
        //$this->api->delete('app_material', array('material_id' => $ids));
        //删除分类
        $result = $this->api->delete('icon_category', array('id' => $ids));
        $this->addItem($result);
        $this->output();
    }

    /**
     * 过滤数据
     *
     * @access private
     * @param  category_name:图标分类名称
     * @param  category_mark:图标分类标识
     * @return array
     */
    private function filter_data()
    {
        $name = trim(urldecode($this->input['category_name']));
        $mark = trim(urldecode($this->input['category_mark']));
        if (empty($name))
        {
            $this->errorOutput(NO_CATEGORY_NAME);
        }

        if (empty($mark))
        {
            $this->errorOutput(NO_CATEGORY_MARK);
        }
        return array(
		    'name' => $name,
		    'mark' => $mark
        );
    }

    /**
     * 查询条件
     *
     * @access private
     * @param  k:图标分类关键字
     *
     * @return array
     */
    private function condition()
    {
        $keyword = trim(urldecode($this->input['k']));
        return array(
			'keyword' => $keyword,
        );
    }
}

$out = new icon_category();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();