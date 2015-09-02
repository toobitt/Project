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
 * @description 推送消息接口
 **************************************************************************/
define('MOD_UNIQUEID','push_message');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/pushMessage.php');
require_once(CUR_CONF_PATH . 'lib/app.class.php');
require_once(CUR_CONF_PATH . 'lib/push_msg_mode.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');

class push_message extends outerUpdateBase
{
    private $api;
    private $push_msg_mode;
    public function __construct()
    {
        parent::__construct();
        $this->api = new app();
        $this->push_msg_mode = new push_msg_mode();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function update(){}
    public function delete(){}
    public function create(){}

    /**
     * 执行推送消息
     *
     * @access public
     * @param  msg:推送的消息内容
     *         open_mode:打开模式（1:打开模块 2:打开内容 3:打开链接）
     *         module_id:模块id
     *         app_uniqueid:模块标识
     * @return array
     */
    public function push()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }

        $msg = $this->input['msg'];
        if(!$msg)
        {
            $this->errorOutput(MSG_CAN_NOT_EMPTY);
        }

        //获取应用信息
        $appInfo = $this->api->getAppInfoByUserId($user_id);
        if(!$appInfo)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }

        //构造消息体
        $msg_body = array(
			'push_title' 	=> $appInfo['name'],
			'push_content' 	=> $msg,
			'action'		=> 'com.dingdone.UPDATE_STATUS',
			'push_app_id'	=> $appInfo['id'],//推送给哪个应用的id
        );

        //根据不同的打开方式构造不同的消息体
        $open_mode = intval($this->input['open_mode']);
        //打开模块的方式
        if($open_mode == 1)
        {
            if(!$this->input['module_id'])
            {
                $this->errorOutput(NO_MODULE_ID);
            }
            $msg_body['push_extend'] = $this->input['module_id'] . '#';
        }
        elseif ($open_mode == 2)//打开内容的方式
        {
            if(!$this->input['module_id'])
            {
                $this->errorOutput(NO_MODULE_ID);
            }
             
            if(!$this->input['content_id'])
            {
                $this->errorOutput(NO_CONTENT_ID);
            }
             
            if(!$this->input['app_uniqueid'])
            {
                $this->errorOutput(NO_MODULE_MARK);
            }
            $msg_body['push_extend'] = $this->input['app_uniqueid'] . '#' . $this->input['content_id'] . '#' . $this->input['module_id'];
        }
        elseif ($open_mode == 3)//打开链接的方式
        {
            if(!$this->input['push_url'])
            {
                $this->errorOutput(NO_PUSH_URL);
            }
            $msg_body['push_extend'] = $this->input['push_url'];
        }
        else
        {
            $this->errorOutput(NO_SELECT_OPEN_MODE);//未选择打开方式
        }

        //根据用户的user_id获取用户的推送接口配置
        $pushApi = $this->getPushApiConfig($user_id);
        if(!$pushApi)
        {
            $this->errorOutput(THIS_USER_NOT_PUSH_API);
        }

        //终端类型
        $device_type = strtolower($this->input['device_type']);
        if(!$device_type)
        {
            $this->errorOutput(NO_SELECT_DEVICE_TYPE);
        }
        else
        {
            $deviceTypeArr = explode(',', $device_type);
            foreach($deviceTypeArr AS $k => $v)
            {
                if(!in_array($v, array('ios','android')))
                {
                    $this->errorOutput(DEVICE_TYPE_ERR);
                }
            }
        }

        //分别针对ios与android发送
        foreach ($deviceTypeArr AS $k => $_device_type)
        {
            if(empty($pushApi['master_key']) && $_device_type == 'ios')
            {
                continue;
            }
            #master_key 默认unknow是为了兼容单独发送安卓推送
            $_push = new pushMessage(array(
                    'app_id' 		     => $pushApi['app_id'],
                    'app_key'            => $pushApi['app_key'],
                    'master_key'         => $pushApi['master_key'] ? $pushApi['master_key'] : 'unknow',
                    'device_type'        => $_device_type,
                    'msg'				 => $msg_body,
            ));
            //推送
            $ret = $_push->push();
        }
        //保存推送的消息
        $this->push_msg_mode->create(array(
                    'app_id'             => $appInfo['id'],
                    'user_id'            => $user_id,
                    'user_name' 	     => $this->user['user_name'],
                    'device_type' 	     => $device_type,
                    'title' 		     => $appInfo['name'],
                    'msg' 			     => $msg,
                    'status' 		     => $ret['errcode']?2:1,//1:成功 2：失败
                    'open_mode'		     => $open_mode,//记录打开模式
                    'create_time' 	     => TIMENOW,
        ));

        $this->addItem($ret);
        $this->output();
    }

    /**
     * 根据用户的user_id获取用户的推送接口配置
     *
     * @access public
     * @param  user_id:用户id
     * @return array
     */
    public function getPushApiConfig($user_id = '')
    {
        if(!$user_id)
        {
            return false;
        }

        $curl = new curl($this->settings['App_company']['host'], $this->settings['App_company']['dir']);
        $curl->setSubmitType('get');
        $curl->initPostData();
        $curl->addRequestData('a','getPushApiConfig');
        $curl->addRequestData('user_id',$user_id);
        $ret  = $curl->request('user.php');
        if($ret && isset($ret[0]))
        {
            return $ret[0];
        }
        else
        {
            return array();
        }
    }
    
    //推送客户端预览通知
    public function pushPreviewNotice()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        //获取应用信息
        $appInfo = $this->api->getAppInfoByUserId($user_id);
        if(!$appInfo)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }
        
        $module_id = $this->input['module_id'];
        $previewType = $module_id?'listui':'mainui';

        //构造消息体
        $msg_body = array(
            'msg_type'     => 'preview',
            'app_id'       => $appInfo['id'],
            'app_name'     => $appInfo['name'],
            'preview_type' => $previewType,
            'action'	   => 'com.dingdonehelper.UPDATE_STATUS',
        );
        
        if($module_id)
        {
            $msg_body['module_id'] = $module_id;
        }

        //发送消息
        $_push = new pushMessage(array(
                'app_id' 		     => $this->settings['preview_push']['app_id'],
                'app_key'            => $this->settings['preview_push']['app_key'],
                'master_key'         => $this->settings['preview_push']['master_key'],
                'channels'           => array($this->settings['preview_push']['channel'] . $appInfo['id']),//通道设置为预览
                'msg'				 => $msg_body,
                //'alert'				 => $appInfo['name'] . '更新',
                //'badge'				 => 'Increment',
                //'sound'				 => 'default',
        ));
        //推送
        $ret = $_push->push();
        
        //发送成功
        if($ret && !$ret['errcode'])
        {
            $this->addItem(array('return' => 1));
            $this->output();
        }
        else 
        {
            $this->errorOutput(PUSH_FAIL);
        }
    }
    
    //推送打包成功的通知
    public function pushPackNotice()
    {
        $app_id = intval($this->input['app_id']);
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }
        
        $app_name = $this->input['app_name'];
        if(!$app_name)
        {
            $this->errorOutput(NO_NAME);
        }
        
        $client_type = $this->input['client_type'];
        if(!$client_type)
        {
            $this->errorOutput(NO_CLIENT_MARK);
        }
        
        $version_name = $this->input['version_name'];
        if(!$version_name)
        {
            $this->errorOutput(NO_VERSION_NUM);
        }
        
        $download_url = $this->input['download_url'];//下载地址
       
        $msg_body = array(
            'msg_type'     => 'build',
            'app_id'       => $app_id,
            'app_name'     => $app_name,
            'version_name' => $version_name,
            'client_type'  => $client_type,
            'download_url' => $download_url,
            'action'	   => 'com.dingdonehelper.UPDATE_STATUS',
            'alert'		   => '[' . $app_name . '-V' . $version_name . '-' . $client_type . '] 打包完成',
            'badge'		   => 'Increment',
            'sound'		   => 'default',
        );
        
        //发送消息
        $_push = new pushMessage(array(
                'app_id' 		     => $this->settings['preview_push']['app_id'],
                'app_key'            => $this->settings['preview_push']['app_key'],
                'master_key'         => $this->settings['preview_push']['master_key'],
                'channels'           => array($this->settings['preview_push']['channel'] . $app_id),//通道设置为预览
                'msg'				 => $msg_body,
        ));
        //推送
        $ret = $_push->push();
        
        //发送成功
        if($ret && !$ret['errcode'])
        {
            $this->addItem(array('return' => 1));
            $this->output();
        }
        else 
        {
            $this->errorOutput(PUSH_FAIL);
        }
    }
}

$out = new push_message();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'push';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();