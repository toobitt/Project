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
 * @description 用于二维码登陆接口
 **************************************************************************/
define('MOD_UNIQUEID', 'app_plant');
require_once('global.php');
include_once(CUR_CONF_PATH . 'lib/qrcodeLogin.class.php');

class qrcodeLogin extends appCommonFrm
{
    private $_login;
    public function __construct()
    {
        parent::__construct();
        $this->_login = new app();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->_login);
    }

    public function show() {}

    /**
     * 获取详情信息
     *
     * @access public
     * @param  id
     * @return array
     */
    public function detail()
    {
        $sessid = trim($this->input['id']);
        if (empty($sessid))
        {
            $this->errorOutput(NO_SESSID);
        }

        $queryData = array('sessid' => $sessid);
        $login_info = $this->_login->detail('qrcode_login', $queryData);
        $this->addItem($login_info);
        $this->output();
    }

    /**
     * 创建登陆信息
     *
     * @access public
     * @param  id | token
     * @return array
     */
    public function create()
    {
        $sessid = trim($this->input['id']);
        $token  = trim($this->input['token']);
        if (empty($sessid))
        {
            $this->errorOutput(NO_SESSID);
        }
         
        if(empty($token))
        {
            $this->errorOutput(NO_TOKEN);
        }
         
        $insertData = array(
	        'sessid'    => $sessid,
	        'token'     => $token
        );
         
        $result = $this->_login->create('qrcode_login', $insertData);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 删除登陆信息
     *
     * @access public
     * @param  id | token
     * @return array
     */
    public function delete()
    {
        $sessid = trim($this->input['id']);
        $token  = trim($this->input['token']);
        if (empty($sessid))
        {
            $this->errorOutput(NO_SESSID);
        }
         
        if(empty($token))
        {
            $this->errorOutput(NO_TOKEN);
        }
         
        $deleteData = array();
        if ($sessid)
        {
            $deleteData['sessid'] = $sessid;
        }
         
        if ($token)
        {
            $deleteData['token']  = $token;
        }
         
        $result = $this->_login->delete('qrcode_login', $deleteData);
        $this->addItem($result);
        $this->output();
    }
}

$out = new qrcodeLogin();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();