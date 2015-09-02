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
 * @description 后台图标管理接口
 **************************************************************************/
define('MOD_UNIQUEID', 'app_plant');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/iconManage.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(CUR_CONF_PATH . '/lib/UpYunOp.class.php');

class icon_manage extends appCommonFrm
{
    private $api;
    private $_upYunOp;
    public function __construct()
    {
        parent::__construct();
        $this->api = new iconManage();
        $this->_upYunOp = new UpYunOp();
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
        $icon_info = $this->api->show($data);
        $category = $this->getCategory();
        $cateArr = array();
        foreach ($category AS $k => $v)
        {
            $cateArr[$v['id']] = $v['name'];
        }
        $this->addItem_withkey('icon', $icon_info);
        $this->addItem_withkey('category', $cateArr);
        $this->output();
    }

    /**
     * 获取分类
     *
     * @access public
     * @param  无
     * @return array
     */
    private function getCategory()
    {
        include_once(CUR_CONF_PATH . 'lib/iconCategory.class.php');
        $obj = new iconCategory();
        return $obj->show(array('count' => -1));
    }

    /**
     * 根据条件获取数据总数
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
     * 获取单个数据详情
     *
     * @access public
     * @param  id
     * @return array
     */
    public function detail()
    {
        $id = intval($this->input['id']);
        if ($id <= 0)
        {
            $this->errorOutput(PARAM_WRONG);
        }
        $queryData = array('id' => $id);
        $icon_info = $this->api->detail('app_material', $queryData);
        $this->addItem($icon_info);
        $this->output();
    }

    /**
     * 创建数据
     *
     * @access public
     * @param  category_id:分类id
     *         $_FILES['iconFiles']:图标文件
     * @return array
     */
    public function create()
    {
        $category_id = intval($this->input['category_id']);
        $iconFiles   = $_FILES['iconFiles'];
        if ($category_id <= 0 || empty($iconFiles))
        {
            $this->errorOutput(PARAM_WRONG);
        }
        $_FILES['Filedata'] = $iconFiles;
        unset($_FILES['iconFiles']);
        $imgzip = $iconFiles['type'] == 'application/zip' ? 1 : 0;
        
        /*
        $material = new material();
        $iconInfo = $material->addMaterial($_FILES, '', '', '', $imgzip);
        */
        if($imgzip)
        {
            $iconInfo = $this->_upYunOp->uploadPicWithZip($_FILES['Filedata']);
        }
        else 
        {
            $iconInfo = $this->_upYunOp->uploadToBucket($_FILES['Filedata']);
        }
        
        if ( FALSE === $iconInfo)
        {
            $this->errorOutput(FAILED);
        }
        
        $data = array(
		    'cate_id'    => $category_id,
		    'user_id'    => $this->user['user_id'],
		    'user_name'  => $this->user['user_name'],
		    'org_id'     => $this->user['org_id']
        );
        $condition = array('id' => $category_id);
        if ($imgzip)
        {
            foreach ($iconInfo as $icon)
            {
                $this->prepareData($data, $icon);
                $result = $this->api->create('app_material', $data);
            }
            //更新分类下图片数
            $pic_count = count($iconInfo);
            $updateData = array('pic_count' => $pic_count);
            $this->api->update('icon_category', $updateData, $condition, true);
        }
        else
        {
            $this->prepareData($data, $iconInfo);
            $result = $this->api->create('app_material', $data);
            //更新分类下图片数
            $updateData = array('pic_count' => 1);
            $this->api->update('icon_category', $updateData, $condition, true);
        }
        $this->addItem($result);
        $this->output();
    }

    /**
     * 解析数据
     *
     * @access private
     * @param  $data &array
     *         $icon array
     * @return array
     */
    private function prepareData(&$data, $icon)
    {
        $data['material_id'] = $icon['id'];
        $data['name']        = $icon['name'];
        $data['mark']        = $icon['mark'];
        $data['type']        = $icon['type'];
        $data['filesize']    = $icon['filesize'];
        $data['imgwidth']    = $icon['imgwidth'];
        $data['imgheight']   = $icon['imgheight'];
        $data['host']        = $icon['host'];
        $data['dir']         = $icon['dir'];
        $data['filepath']    = $icon['filepath'];
        $data['filename']    = $icon['filename'];
        $data['create_time'] = $icon['create_time'];
        $data['ip']          = $icon['ip'];
        $data['sort_order']  = $icon['id'];
    }

    public function update()
    {

    }

    /**
     * 删除数据
     *
     * @access public
     * @param  id
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
        $queryData = array(
		    'condition' => array(
		        'id' => $ids
        ),
		    'count' => -1
        );
        $iconInfo = $this->api->show($queryData);
        if (!$iconInfo)
        {
            $this->errorOutput(ICON_NOT_EXISTS);
        }
        $category = array();
        foreach ($iconInfo as $icon)
        {
            if ($icon['cate_id'])
            {
                $category[$icon['cate_id']][] = $icon['id'];
            }
        }
        $result = $this->api->delete('app_material', array('id' => $ids));
        //更新分类下的图片数
        if ($category)
        {
            foreach ($category as $k => $v)
            {
                $updateData = array('pic_count' => -intval(count($v)));
                $condition = array('id' => $k);
                $ret = $this->api->update('icon_category', $updateData, $condition, true);
            }
        }
        $this->addItem($result);
        $this->output();
    }

    /**
     * 查询条件
     *
     * @access private
     * @param  k:关键字
     *         type:类型
     * @return array
     */
    private function condition()
    {
        $keyword = trim(urldecode($this->input['k']));
        $type    = intval($this->input['type']);
        return array(
			'keyword'     => $keyword,
		    'category_id' => $type
        );
    }
}

$out = new icon_manage();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();