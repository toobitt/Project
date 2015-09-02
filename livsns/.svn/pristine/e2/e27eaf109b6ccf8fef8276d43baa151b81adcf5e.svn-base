<?php
define('ROOT_DIR', '../../../../');
require( ROOT_DIR.'conf/global.conf.php');

require('../class/curl.class.php');
class rules
{
    var $curl;
    var $access_token;
    function __construct()
    {
        //$this->check_token();
        global $gGlobalConfig;
        $this->settings = &$gGlobalConfig;
        $this->curl = new curl($this->settings['App_rules']['host'],$this->settings['App_rules']['dir'].'core/');
    }

    function __destruct()
    {
        unset($this->curl);
    }

    private function check_token(){
        $access_token = trim($_COOKIE['access_token']);
        if(!$access_token){
            $data_list = array('refro'=>1,'message'=>'未登录');    //跳转登陆页
            echo json_encode($data_list);
            exit;
        }else{
            $this->access_token = $access_token;
        }
    }

    /*
     * name :rules
     * 作用：入口文件
     * */
    public function rules()
    {
        $this->check_token();
        $data = $_REQUEST;   //获取提交数据

        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        foreach($data as $key=>$vo){
            $this->curl->addRequestData($key, $vo);
        }
        $this->curl->addRequestData('access_token', $this->access_token);
        $data_list = $this->curl->request('rules.php');
        echo json_encode($data_list);
        exit();
    }
}

$out = new rules();
if(!method_exists($out, $_REQUEST['a']))
{
    $action = 'rules';
}
else
{
    $action = $_REQUEST['a'];
}
$out->$action();
?>