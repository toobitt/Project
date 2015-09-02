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
 * @description 后台正文模板操作接口（增、删、改）
 **************************************************************************/
define('MOD_UNIQUEID','body_tpl');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/body_tpl_mode.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
require_once(CUR_CONF_PATH . '/lib/UpYunOp.class.php');

class body_tpl_update extends adminUpdateBase
{
    private $mode;
    private $_upYunOp;
    public function __construct()
    {
        parent::__construct();
        $this->mode = new body_tpl_mode();
        $this->_upYunOp = new UpYunOp();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * 创建正文模板
     *
     * @access public
     * @param  name:正文模板名称
     *         body_html:正文模板html
     * @return array
     */
    public function create()
    {
        $name 		= $this->input['name'];
        $body_html 	= $this->input['body_html'];
        if(!$name)
        {
            $this->errorOutput(NO_TPL_NAME);
        }

        if(!$body_html)
        {
            $this->errorOutput(NO_TPL_HTML);
        }

        $data = array(
			'name' 			=> $name,
			'body_html' 	=> $body_html,
			'type'			=> intval($this->input['type']),
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
        );

        //上传图片
        if(isset($_FILES['img_info']) && !$_FILES['img_info']['error'])
        {
            /*
            $_FILES['Filedata'] = $_FILES['img_info'];
            $material_pic = new material();
            $img = $material_pic->addMaterial($_FILES);
            */
            $img = $this->_upYunOp->uploadToBucket($_FILES['img_info']);
            if($img)
            {
                $img_info = array(
					'host' 		=> $img['host'],
					'dir' 		=> $img['dir'],
					'filepath' 	=> $img['filepath'],
					'filename' 	=> $img['filename'],	
					'imgwidth'	=> $img['imgwidth'],
					'imgheight'	=> $img['imgheight'],
                );
                $data['img_info'] = @serialize($img_info);
            }
        }

        $vid = $this->mode->create($data);
        if($vid)
        {
            $data['id'] = $vid;
            $this->addItem('success');
            $this->output();
        }
    }

    /**
     * 更新正文模板
     *
     * @access public
     * @param  id:正文模板id
     * 		   name:正文模板名称
     *         body_html:正文模板html
     * @return array
     */
    public function update()
    {
        if(!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }

        $name 		= $this->input['name'];
        $body_html 	= $this->input['body_html'];
        if(!$name)
        {
            $this->errorOutput(NO_TPL_NAME);
        }

        if(!$body_html)
        {
            $this->errorOutput(NO_TPL_HTML);
        }

        $update_data = array(
			'name' 			=> $name,
			'body_html' 	=> $body_html,
			'type'			=> intval($this->input['type']),
			'update_time' 	=> TIMENOW,
        );

        //更改图片
        if(isset($_FILES['img_info']) && !$_FILES['img_info']['error'])
        {
            /*
            $_FILES['Filedata'] = $_FILES['img_info'];
            $material_pic = new material();
            $img = $material_pic->addMaterial($_FILES);
            */
            $img = $this->_upYunOp->uploadToBucket($_FILES['img_info']);
            if($img)
            {
                $img_info = array(
					'host' 		=> $img['host'],
					'dir' 		=> $img['dir'],
					'filepath' 	=> $img['filepath'],
					'filename' 	=> $img['filename'],	
					'imgwidth'	=> $img['imgwidth'],
					'imgheight'	=> $img['imgheight'],
                );
                $update_data['img_info'] = @serialize($img_info);
            }
        }

        $ret = $this->mode->update($this->input['id'],$update_data);
        if($ret)
        {
            $this->addItem('success');
            $this->output();
        }
    }

    /**
     * 删除正文模板
     *
     * @access public
     * @param  id:正文模板id，多个用逗号分隔
     *
     * @return array
     */
    public function delete()
    {
        if(!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }

        $ret = $this->mode->delete($this->input['id']);
        if($ret)
        {
            $this->addItem('success');
            $this->output();
        }
    }

    /**
     * 审核正文模板
     *
     * @access public
     * @param  id:正文模板id
     *
     * @return array
     */
    public function audit()
    {
        if(!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }
        $ret = $this->mode->audit($this->input['id']);
        if($ret)
        {
            $this->addItem($ret);
            $this->output();
        }
    }

    public function sort(){}
    public function publish(){}

    public function unknow()
    {
        $this->errorOutput(UNKNOW);
    }
}

$out = new body_tpl_update();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'unknow';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();