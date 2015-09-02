<?php
/**
 * Created by PhpStorm.
 * User: Steve
 * Date: 15/6/2
 * Time: 下午3:52
 */
define('MOD_UNIQUEID','sendCloudMail');//模块标识
require('./global.php');
require_once(CUR_CONF_PATH . 'lib/SendCloud.class.php');

class SendCloudMail extends outerReadBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
    /**
     * 发送邮件
     */
    public function send()
    {
        $email = $this->input['email'];
        $template_name = $this->input['template_name'];
        $content = $this->input['content'];
        $from = $this->input['from'];
        $fromname = $this->input['fromname'];
        //发送邮件
        $sendCloud = new DDSendCloud($template_name);
        $ret = $sendCloud->sendTo(array(
            'to'      => $email,
            'content' => $content,
            'from' => $from,
            'fromname' => $fromname
        ));

        $this->addItem($ret);
        $this->output();
    }

    public function show(){}
    public function detail(){}
    public function count(){}
}
$out = new SendCloudMail();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'send';
}
$out->$action();