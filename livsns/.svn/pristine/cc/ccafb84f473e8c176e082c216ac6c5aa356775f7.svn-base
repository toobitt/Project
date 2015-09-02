<?php
define('ROOT_DIR', '../../../../');
require( ROOT_DIR.'conf/global.conf.php');
require('../class/curl.class.php');
class login
{
    var $curl;
    function __construct()
    {
        //setcookie('access_token','',-3600,'/');
        
        global $gGlobalConfig;
        $this->settings = &$gGlobalConfig;
        $this->curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
    }

    function __destruct()
    {
        unset($this->curl);
    }


    private function check_token(){
        $access_token = trim($_COOKIE['access_token']);

        //if($access_token){
            //header("Location:list_web.html");
            //exit;
       // }
    }

    /*
     * name :rules
     * 作用：入口文件，执行列表的数据查找
     * */
    public function dologin()
    {	
    	$this->check_token();
        $username = trim($_REQUEST['username']);
        $password = $_REQUEST['password'];
        $appid = 55;
        $appkey = 'GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7';

        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('username', $username);
        $this->curl->addRequestData('password', $password);
        $this->curl->addRequestData('appid', intval($appid));
        $this->curl->addRequestData('appkey', trim($appkey));
        $this->curl->addRequestData('a', 'show');
        $data_list = $this->curl->request("get_access_token.php");
        if($data_list){
            $data_list = (array)$data_list[0];
            setcookie('access_token','',-3600,'/');
            setcookie('access_token',$data_list['token'],$data_list['expired_time'],'/');
            $result = array('error'=>1,'message'=>'登陆成功');
        }else{
            $result = array('error'=>0,'message'=>'登陆失败');
        }
        echo json_encode($result);
        exit;

    }


    public function loginout(){
        setcookie('access_token','',-3600,'/');
        //$data_list = array('refro'=>0,'message'=>'未登录');    //跳转登陆页
        //echo json_encode($data_list);
        header("Location:login_web.html");
        exit;
    }
}

$out = new login();
if(!method_exists($out, $_REQUEST['a']))
{
    $action = 'dologin';
}
else
{
    $action = $_REQUEST['a'];
}
$out->$action();
?>