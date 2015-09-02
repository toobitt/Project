<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-10-21
 * @encoding    UTF-8
 * @description 集成SendCloud来发送邮件
 **************************************************************************/

class DDSendCloud
{
    private $emailParam;//保存邮件设置参数
    private $settings;
    private $type;//定义发送邮件的类型，'login':注册激活 'change':修改邮箱
    public function __construct($emailType = 'login')
    {
        global $gGlobalConfig;
        $this->settings = &$gGlobalConfig;
        $this->type = $emailType;
        $this->_init();
    }
    
    //初始化
    private function _init()
    {
        $this->emailParam = array(
                'api_user' => $this->settings['sendcloud']['api_user'],
                'api_key'  => $this->settings['sendcloud']['api_key'],
                'from'     => $this->settings['sendcloud']['from'],
                'fromname' => $this->settings['sendcloud']['fromname'],
                'subject'  => $this->settings['sendcloud']['subject'][$this->type],
        );
    }
    
    //执行发送邮件
    public function sendTo($data = array())
    {
        if(!$data)
        {
            return FALSE;
        }
        
        $this->emailParam['to']   = $data['to'];
        $this->emailParam['html'] = $data['content'];
        $options = array('http' => array('method'  => 'POST','content' => http_build_query($this->emailParam)));
        $context  = stream_context_create($options);
        $result = file_get_contents($this->settings['sendcloud']['url'], false, $context);
        $result = json_decode($result,1);
        if($result && is_array($result) && $result['message'] == 'success')
        {
            return TRUE;//发送成功
        }
        else 
        {
            return FALSE;//发送失败
        }
    }
}