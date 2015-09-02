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
 * @description 后台固化模块接口
 **************************************************************************/
define('MOD_UNIQUEID', 'app_plant');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/solidify.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(CUR_CONF_PATH . '/lib/UpYunOp.class.php');

class app_solidify extends appCommonFrm
{
    private $api;
    private $_upYunOp;
    public function __construct()
    {
        parent::__construct();
        $this->api = new solidify();
        $this->_upYunOp = new UpYunOp();
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
        $count  = isset($this->input['count']) ? intval($this->input['count']) : 20;
        $data   = array(
			'offset'    => $offset,
			'count'     => $count,
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
     * 创建数据
     *
     * @access public
     * @param  name:固化模块的名称
     * 		   mark:固化模块的标识
     *         $_FILES['module_icon']:固化模块的图标
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

        if ($_FILES['module_icon'])
        {
            $_FILES['Filedata'] = $_FILES['module_icon'];
            unset($_FILES['module_icon']);
            /*
            $material = new material();
            $pic_info = $material->addMaterial($_FILES);
            */
            
            $pic_info = $this->_upYunOp->uploadToBucket($_FILES['Filedata']);
            if ($pic_info)
            {
                $data['pic'] = serialize($pic_info);
            }
        }

        $data['user_id']     = $this->user['user_id'];
        $data['user_name']   = $this->user['user_name'];
        $data['org_id']      = $this->user['org_id'];
        $data['create_time'] = TIMENOW;
        $data['ip']          = hg_getip();
        $result              = $this->api->create('solidify_module', $data);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 更新数据
     *
     * @access public
     * @param  id:固化模块的id
     * 		   name:固化模块的名称
     * 		   mark:固化模块的标识
     *         $_FILES['module_icon']:固化模块的图标
     * @return array
     */
    public function update()
    {
        $id = intval($this->input['id']);
        if ($id <= 0)
        {
            $this->errorOutput(NOID);
        }

        $module_info = $this->api->detail('solidify_module', array('id' => $id));
        if (!$module_info)
        {
            $this->errorOutput(MODULE_NOT_EXISTS);
        }

        $data = $this->filter_data();
        $validate = array();
        if ($module_info['name'] != $data['name'])
        {
            //名称是否重复
            $check = $this->api->verify(array('name' => $data['name']));
            if ($check > 0)
            {
                $this->errorOutput(NAME_REPEAT);
            }
            $validate['name'] = $data['name'];
        }

        if ($module_info['mark'] != $data['mark'])
        {
            //标识是否重复
            $check = $this->api->verify(array('mark' => $data['mark']));
            if ($check > 0)
            {
                $this->errorOutput(MARK_EXISTS);
            }
            $validate['mark'] = $data['mark'];
        }

        if ($module_info['brief'] != $data['brief'])
        {
            $validate['brief'] = $data['brief'];
        }

        if ($module_info['sort_order'] != $data['sort_order'])
        {
            $validate['sort_order'] = $data['sort_order'];
        }

        if ($_FILES['module_icon'])
        {
            $_FILES['Filedata'] = $_FILES['module_icon'];
            unset($_FILES['module_icon']);
            /*
            $material = new material();
            $pic_info = $material->addMaterial($_FILES);
            */
            $pic_info = $this->_upYunOp->uploadToBucket($_FILES['Filedata']);
            if ($pic_info)
            {
                $validate['pic'] = serialize($pic_info);
            }
        }

        if ($validate)
        {
            $result = $this->api->update('solidify_module', $validate, array('id' => $id));
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
     * @param  id:固化模块的id，多个用逗号分隔
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
        $this->api->update('app_module', array('solidify' => 0), array('solidify' => $ids));
        $result = $this->api->delete('solidify_module', array('id' => $ids));
        $this->addItem($result);
        $this->output();
    }

    /**
     * 过滤数据
     *
     * @access public
     * @param  module_name:固化模块的名称
     * @param  module_mark:模块的标识
     * @param  module_brief:模块的描述
     * @param  sort_order:模块的排序
     *
     * @return array
     */
    private function filter_data()
    {
        $module_name  = trim($this->input['module_name']);
        $module_mark  = trim($this->input['module_mark']);
        $module_brief = trim($this->input['module_brief']);
        $sort_order   = intval($this->input['sort_order']);
        if (empty($module_name))
        {
            $this->errorOutput(NO_MODULE_NAME);
        }

        if (empty($module_mark))
        {
            $this->errorOutput(NO_MODULE_MARK);
        }

        $data = array(
			'name'       => $module_name,
		    'mark'       => $module_mark,
		    'brief'      => $module_brief,
			'sort_order' => $sort_order
        );
        return $data;
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
        $keyword = trim(urldecode($this->input['k']));
        return array(
			'keyword' => $keyword
        );
    }
}

$out = new app_solidify();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();