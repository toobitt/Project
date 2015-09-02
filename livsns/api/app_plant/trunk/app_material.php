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
 * @description 图片接口
 **************************************************************************/
define('MOD_UNIQUEID', 'app_plant');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/appMaterial.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(CUR_CONF_PATH . '/lib/UpYunOp.class.php');
require_once(CUR_CONF_PATH.'/lib/Qiniu/global.php');

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
class app_material extends appCommonFrm
{
    private $api;
    private $_upYunOp;
    public function __construct()
    {
        parent::__construct();
        $this->api = new appMaterial();
        $this->_upYunOp = new UpYunOp();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
    }

    /**
     * 获取图片的列表
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
			'offset'    => $offset,
			'count'     => $count,
			'condition' => $this->condition()
        );
        $material_info = $this->api->show($data);
        $this->setXmlNode('material_info', 'material');
        if ($material_info)
        {
            foreach ($material_info as $material)
            {
                $this->addItem($material);
            }
        }
        $this->output();
    }

    /**
     * 根据某一个图片详情
     *
     * @access public
     * @param  id:图片的id
     * @return array
     */
    public function detail()
    {
        $id            = intval($this->input['id']);
        $data          = array('id' => $id);
        $material_info = $this->api->detail('app_material', $data);
        $this->addItem($material_info);
        $this->output();
    }

    /**
     * 获取查询条件
     *
     * @access public
     * @param  id:图片id
     * @return array
     */
    private function condition()
    {
        $id = trim($this->input['id']);
        $data = array();
        if (!empty($id))
        {
            $data['id'] = $id;
        }

        return $data;
    }

    /**
     * 上传图片
     *
     * @access public
     * @param  FILES['Filedata']:文件
     * @return array
     */
    public function upload()
    {
        if (!$_FILES['Filedata'])
        {
            $this->errorOutput(UPLOAD_ERROR);
        }

        /*
        $material = new material();
        $result = $material->addMaterial($_FILES, '', '', '', '', 'png');
        */
        $filename = $_FILES['Filedata']['name'];
        copy($_FILES['Filedata']['tmp_name'],'./uploads/'.$filename);
        $result = $this->_upYunOp->uploadToBucket($_FILES['Filedata'],'',$this->user['user_id']);
        if ( FALSE === $result)
        {
            $this->errorOutput(FAIL_UPLOAD_TO_MATARIAL);
        }

        //上传至七牛
//        $filename = $_FILES['Filedata']['name'];
//        if(file_exists('./uploads/'.$filename))
//        {
//            $Qiniu_result = $this->uploadQiniu($_FILES['Filedata']['name']);
//        }


        $picData = array(
			//'material_id'  => $result['id'],
			'name'         => $result['name'],
			'mark'         => $result['mark'],
			'type'         => $result['type'],
			'filesize'     => $result['filesize'],
			'imgwidth'     => $result['imgwidth'],
			'imgheight'    => $result['imgheight'],
			'host'         => $result['host'],
			'dir'          => $result['dir'],
			'filepath'     => $result['filepath'],
			'filename'     => $result['filename'],
            //'qn_key'       => $Qiniu_result['key'] ? $Qiniu_result['key'] : '',
			'user_id'      => $this->user['user_id'],
			'user_name'    => $this->user['user_name'],
			'org_id'       => $this->user['org_id'],
			'create_time'  => $result['create_time'],
			'ip'           => $result['ip']
        );

        $ret = $this->api->create('app_material', $picData);
        if($ret)
        {
            $ret['material_id'] = $ret['id'];
            $this->addItem($ret);
            $this->output();
        }
    }

    /**
     * 删除图片
     *
     * @access public
     * @param  id:图片id
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
        $result = $this->api->delete('app_material', array('id' => $ids));
        $this->addItem($result);
        $this->output();
    }
    
    /**
     * 本地化图片（将一个图片链接上传到upyun）
     *
     * @access public
     * @param  url:图片url
     * @return array
     */
    public function localMaterial()
    {
        if (!$this->input['url'])
        {
            $this->errorOutput(NO_PIC_URL);
        }
        
        $result = $this->_upYunOp->uploadToBucketByUrl($this->input['url'],$this->user['user_id']);
        if ( FALSE === $result)
        {
            $this->errorOutput(FAIL_UPLOAD_TO_MATARIAL);
        }

        $picData = array(
			'name'         => $result['name'],
			'mark'         => $result['mark'],
			'type'         => $result['type'],
			'filesize'     => $result['filesize'],
			'imgwidth'     => $result['imgwidth'],
			'imgheight'    => $result['imgheight'],
			'host'         => $result['host'],
			'dir'          => $result['dir'],
			'filepath'     => $result['filepath'],
			'filename'     => $result['filename'],
			'user_id'      => $this->user['user_id'],
			'user_name'    => $this->user['user_name'],
			'org_id'       => $this->user['org_id'],
			'create_time'  => $result['create_time'],
			'ip'           => $result['ip']
        );
        
        $ret = $this->api->create('app_material', $picData);
        if($ret)
        {
            $ret['material_id'] = $ret['id'];
            $this->addItem($ret);
            $this->output();
        }  
    }

    private function uploadQiniu($filename)
    {
        $accessKey = 'Pyz_FCh-k91aWKK4tZSM3V0gH9sJl2RYgQ642R5d';
        $secretKey = '7a6wUWEHyjOA-2nQW5CbKYCQMHIwNNGcMhLgC721';
        $auth = new Auth($accessKey, $secretKey);

        $bucket = 'dingdone';

        // 设置put policy的其他参数, 上传回调
//        $opts = array(
//          'callbackUrl' => 'http://7xkvfm.com1.z0.glb.clouddn.com/',
//          'callbackBody' => 'name=$(fname)&hash=$(etag)'
//        );
//        $token = $auth->uploadToken($bucket, null, 3600, $opts);

        //上传本地文件
        $token = $auth->uploadToken($bucket);
        $uploadMgr = new UploadManager();

        $file = './uploads/'.$filename;
        if(file_exists($file))
        {
            list($ret, $err) = $uploadMgr->putFile($token, null, $file);
            if ($err !== null) {
                $this->errorOutput($err);
            } else {
                return $ret;
            }
        }

    }
}

$out = new app_material();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();