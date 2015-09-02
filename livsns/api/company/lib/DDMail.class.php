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
 * @description PHPMail二次封装类，方便调用
 **************************************************************************/
require_once(dirname(__FILE__) . '/PHPMailer/PHPMailerAutoload.php');

class DDMail
{
    private $mail;
    private $settings;
    public function __construct()
    {
        global $gGlobalConfig;
        $this->settings = &$gGlobalConfig;
        $this->_init();
    }
    
    //初始化
    private function _init()
    {
        $this->mail = new PHPMailer();
        $this->mail->isSMTP();                                     
        $this->mail->Host       = $this->settings['register_email']['host'];
        $this->mail->Port       = 587;
        $this->mail->SMTPAuth   = TRUE;
        $this->mail->CharSet    = 'UTF-8';
        $this->mail->Encoding   = 'base64';
        $this->mail->Username   = $this->settings['register_email']['stmp_username'];
        $this->mail->Password   = $this->settings['register_email']['stmp_password'];
        $this->mail->SMTPSecure = 'tls';
        $this->mail->From       = $this->settings['register_email']['form'];
        $this->mail->FromName   = $this->settings['register_email']['from_name'];
        $this->mail->Subject    = $this->settings['register_email']['subject'];
        $this->mail->IsHTML(TRUE);//支持HTML
    }
    
    //执行发送邮件
    public function sendTo($data = array())
    {
        if(!$data)
        {
            return FALSE;
        }
        
        $this->mail->addAddress($data['to']);
        $this->mail->Body = $data['content'];
        
        //查询有无添加图片
        if(isset($data['img']) && is_array($data['img']))
        {
            foreach ($data['img'] AS $k => $_img)
            {
                $this->mail->AddEmbeddedImage($_img['path'],$_img['cid']);
            }
        }
        
        //发送
        if($this->mail->send())
        {
            return TRUE;
        }
        else 
        {
            return FALSE;
        }
    }
}