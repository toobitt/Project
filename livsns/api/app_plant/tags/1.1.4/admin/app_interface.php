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
 * @description 后台界面接口
 **************************************************************************/
define('MOD_UNIQUEID', 'app_plant');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/appInterface.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(CUR_CONF_PATH . '/lib/UpYunOp.class.php');

class app_interface extends appCommonFrm
{
    private $api;
    private $_upYunOp;
    public function __construct()
    {
        parent::__construct();
        $this->api      = new appInterface();
        $this->_upYunOp = new UpYunOp();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
    }

    /**
     * 获取界面的列表
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
        $appInterface_info = $this->api->show($data);
        $this->setXmlNode('appInterface_info', 'interface');
        if ($appInterface_info)
        {
            foreach ($appInterface_info as $interface)
            {
                $this->addItem($interface);
            }
        }
        $this->output();
    }

    /**
     * 根据条件获取界面的个数
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
     * 根据某一个界面详情
     *
     * @access public
     * @param  id:模板的id
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
        $appInterface_info = $this->api->detail('app_interface', $data);
        if ($appInterface_info)
        {
            if (unserialize($appInterface_info['pic']))
            {
                $appInterface_info['pic'] = unserialize($appInterface_info['pic']);
            }
            //获取对应的属性
            $attr_info = $this->api->get_attribute($id);
            if ($attr_info)
            {
                $appInterface_info['attr'] = $attr_info;
            }
        }
        $this->addItem($appInterface_info);
        $this->output();
    }

    /**
     * 创建数据
     *
     * @access public
     * @param  见:filter_data
     *
     * @return array
     */
    public function create()
    {
        $data = $this->filter_data();
        //是否重名
        $verifyData = array('name' => $data['name']);
        $check = $this->api->verify($verifyData);
        if ($check > 0)
        {
            $this->errorOutput(NAME_EXISTS);
        }

        if ($_FILES['ui_pic'])
        {
            $_FILES['Filedata'] = $_FILES['ui_pic'];
            unset($_FILES['ui_pic']);
            $data['pic'] = $this->upload();
        }
        if ($data['attr_ids'])
        {
            $attr_ids = $data['attr_ids'];
        }

        unset($data['attr_ids']);
        $data['user_id']     = $this->user['user_id'];
        $data['user_name']   = $this->user['user_name'];
        $data['org_id']      = $this->user['org_id'];
        $data['create_time'] = TIMENOW;
        $data['ip']          = hg_getip();
        $result              = $this->api->create('app_interface', $data);
        //绑定属性
        if ($attr_ids)
        {
            $this->set_attr($result['id'], $attr_ids);
        }

        $this->addItem($result);
        $this->output();
    }

    /**
     * 更新数据
     *
     * @access public
     * @param  id:多个用逗号分隔
     *
     * @return array
     */
    public function update()
    {
        $id = intval($this->input['id']);
        if ($id <= 0)
        {
            $this->errorOutput(NOID);
        }

        $appInterface_info = $this->api->detail('app_interface', array('id' => $id));
        if (!$appInterface_info)
        {
            $this->errorOutput(INTERFACE_NOT_EXISTS);
        }

        $data = $this->filter_data();
        $validate = array();
        if ($appInterface_info['name'] != $data['name'])
        {
            //是否重名
            $verifyData = array('name' => $data['name']);
            $check = $this->api->verify($verifyData);
            if ($check > 0)
            {
                $this->errorOutput(NAME_EXISTS);
            }
            $validate['name'] = $data['name'];
        }

        if ($appInterface_info['mark'] != $data['mark'])
        {
            $validate['mark'] = $data['mark'];
        }

        if ($appInterface_info['sort_order'] != $data['sort_order'])
        {
            $validate['sort_order'] = $data['sort_order'];
        }

        if ($_FILES['ui_pic'])
        {
            $_FILES['Filedata'] = $_FILES['ui_pic'];
            unset($_FILES['ui_pic']);
            $validate['pic'] = $this->upload();
        }

        if ($validate || $data['attr_ids'])
        {
            if ($validate)
            {
                $result = $this->api->update('app_interface', $validate, array('id' => $id));
            }
            if ($data['attr_ids'])
            {
                $result = $this->set_attr($id, $data['attr_ids']);
            }
            $this->addItem($result);
        }
        $this->output();
    }

    /**
     * 图片上传
     *
     * @access public
     * @param  $_FILES
     *
     * @return array
     */
    public function upload()
    {
        /*
        $material = new material();
        $result = $material->addMaterial($_FILES);
        if (!$result)
        {
            $this->errorOutput(FAIL_UPLOAD_TO_MATARIAL);
        }
        return serialize($result);
        */
        //切换提交到upyun
        $result = $this->_upYunOp->uploadToBucket($_FILES['Filedata']);
        if( FALSE === $result)
        {
            $this->errorOutput(FAIL_UPLOAD_TO_MATARIAL);
        }
        return serialize($result);
    }

    /**
     * 删除数据
     *
     * @access public
     * @param  id:多个用逗号分隔
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
        $ui_info = $this->api->show(array('count' => -1, 'condition' => array('id' => $ids)));
        if (!$ui_info)
        {
            $this->errorOutput(INTERFACE_NOT_EXISTS);
        }

        $interface_id = array();
        foreach ($ui_info as $ui)
        {
            $interface_id[$ui['id']] = $ui['id'];
        }
        $interface_id = implode(',', $interface_id);
        /*
         $interface_info = $this->api->detail('app_module', array('ui_id' => $interface_id));
         if ($interface_info) $this->errorOutput(PARAM_WRONG);
         */
        //删除界面对应的属性
        $this->api->delete('ui_attr', array('ui_id' => $interface_id));
        //删除界面
        $result = $this->api->delete('app_interface', array('id' => $interface_id));
        $this->addItem($result);
        $this->output();
    }

    /**
     * 绑定属性
     *
     * @access private
     * @param  Int $ui_id
     * @param  String $attr_ids
     *
     * @return array
     */
    private function set_attr($ui_id, $attr_ids)
    {
        $ui_info = $this->api->detail('app_interface', array('id' => $ui_id));
        if (!$ui_info)
        {
            $this->errorOutput(INTERFACE_NOT_EXISTS);
        }

        include_once CUR_CONF_PATH . 'lib/appAttr.class.php';
        $attr = new appAttr();
        $info = $attr->show(array('count' => -1, 'condition' => array('id' => $attr_ids)));
        if (!$info)
        {
            $attr_ids = array();
        }
        else
        {
            $ids = array();
            foreach ($info as $v)
            {
                $ids[] = $v['id'];
            }
            $attr_ids = $ids;
        }
        $attr_info = $this->api->get_attribute($ui_id);
        if ($attr_info)
        {
            $original = array();
            foreach ($attr_info as $attr)
            {
                $original[] = $attr['attr_id'];
            }
            $delete_ids = array_diff($original, $attr_ids);
            $insert_ids = array_diff($attr_ids, $original);
        }
        else
        {
            $insert_ids = $attr_ids;
        }

        if ($delete_ids)
        {
            $data = array(
				'ui_id'   => $ui_id,
				'attr_id' => implode(',', $delete_ids)
            );
            $result = $this->api->delete('ui_attr', $data);
        }

        if ($insert_ids)
        {
            foreach ($insert_ids as $id)
            {
                $data = array(
					'ui_id'   => $ui_id,
					'attr_id' => $id
                );
                $result = $this->api->create('ui_attr', $data);
            }
        }
        return $result;
    }

    /**
     * 过滤数据
     *
     * @access public
     * @param  无
     * @return array
     */
    private function filter_data()
    {
        $ui_name  = trim($this->input['interface_name']);
        $ui_mark  = trim($this->input['interface_mark']);
        $ui_order = intval($this->input['interface_order']);
        $attr_ids = $this->input['attribute_ids'];
        if (empty($ui_name))
        {
            $this->errorOutput(NO_INTERFACE_NAME);
        }

        if (empty($ui_mark))
        {
            $this->errorOutput(NO_INTERFACE_TYPE);
        }

        if ($attr_ids)
        {
            $id_arr = array_filter($attr_ids, 'filter_arr');
            if (!$id_arr)
            {
                $this->errorOutput(NOID);
            }
            $attr_ids = implode(',', $id_arr);
        }

        $data = array(
			'name'       => $ui_name,
			'mark'       => $ui_mark,
		    'sort_order' => $ui_order,
			'attr_ids'   => $attr_ids
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
        return array();
    }
}

$out = new app_interface();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();