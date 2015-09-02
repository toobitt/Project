<?php
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','rules');
require(ROOT_DIR . 'global.php');
require('../conf/config.php');
require(ROOT_DIR.'lib/class/curl.class.php');
class rules extends BaseFrm
{
    var $curl;
    function __construct()
    {
        parent::__construct();
        global $groupType;
        $this->setGroup = &$groupType;
        $this->appid = intval($this->input['appid']);
        $this->appkey = trim($this->input['appkey']);
        $this->access_token = trim($this->input['access_token']);
        $this->device_token = trim($this->input['device_token']);
        $this->curl = new curl($this->settings['App_rules']['host'],$this->settings['App_rules']['dir']);
    }

    function __destruct()
    {
        parent::__destruct();
    }

    /*
     * name :rules
     * 作用：入口文件，执行列表的数据查找
     * */
    public function rules()
    {
        $bundle_id = $this->input['bundle_id'];
        $state = $this->input['statu'];
        $keywords = $this->input['keywords'];

        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('device_token', $this->device_token);
        $this->curl->addRequestData('access_token', $this->input['access_token']);
        $this->curl->addRequestData('_outercall', 1);

        if(!$bundle_id){
            //查询有审核规则的应用
            $this->curl->addRequestData('a', 'detail_app');

            $data = $this->curl->request('rules_api.php');
            $data = (array)$data[0];
            $bundle_id = $data[0]['bundle_id'];
        }
        //查询列表数据
        $count = intval($this->input['count']);
        $page_num = intval($this->input['page']);  //当前页数

        $page_num = $page_num ? $page_num : 1;
        $offset = $page_num-1;
        $count = $count ? $count : 20;

        $this->curl->addRequestData('bundle_id', $bundle_id);
        $this->curl->addRequestData('state', $state);
        $this->curl->addRequestData('keywords', $keywords);
        $this->curl->addRequestData('_outercall', 1);
        $this->curl->addRequestData('offset', $offset);
        $this->curl->addRequestData('count', $count);
        $this->curl->addRequestData('a', 'detail');

        $data_list = $this ->curl->request('rules_api.php');
        $temp_data_list = (array)$data_list[0];
        $list_info = $temp_data_list['data_list'];   //列表数据
        $total_num = $temp_data_list['total_num'];
        $total_page = $temp_data_list['total_page'];

        //分页信息
        $page_info = array(
                        'current_page' => $page_num,  //当前页数
                        'page_num' => $count,       //每页显示条数
                        'total_num' =>$total_num,
                        'total_page' => $total_page
        );

        //当前管理员类型
        if(in_array($this->user['group_type'],$this->setGroup)){//$this->user['user_group']
            $user_group = 1;
        }

        //查询可指派人列表
        $this->curl1 = $this->curl;
        $this->curl1->setSubmitType('post');
        $this->curl1->setReturnFormat('json');
        $this->curl1->initPostData();
        $this->curl1->addRequestData('device_token', $this->device_token);
        $this->curl1->addRequestData('access_token', $this->input['access_token']);
        $this->curl1->addRequestData('_outercall', 1);
        $this->curl1->addRequestData('bundle_id', $bundle_id);
        $this->curl1->addRequestData('a', 'appiont');
        $user_list = $this ->curl1->request('rules_api.php');
        $user_list = (array)$user_list[0];
        $islast = 0;
        if(!$user_list || !$user_list[0]){
            $islast = 1;
            $user_list = '';
        }

        $data_list = json_encode(array('app_list'=>$data,'list_info'=>$list_info,'bundle_id'=>$bundle_id,'page_info'=>$page_info,'user_group'=>$user_group,'user_list'=>$user_list,'islast'=>$islast));
        echo $data_list;
        exit();
    }


    /*
     * name:detail
     * 作用：详情展示接口
     * */
    public function detail(){
        $id = $this->input['id'];
        $bundle_id = $this->input['bundle_id'];

        //如果bundle_id 为空
        if((!$bundle_id) || (!$id)){
            $data_list = array('error'=>'参数错误');
            echo json_encode($data_list);
            exit;
        }

        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('device_token', $this->device_token);
        $this->curl->addRequestData('access_token', $this->input['access_token']);
        $this->curl->addRequestData('_outercall', 1);
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('bundle_id', $bundle_id);
        $this->curl->addRequestData('a', 'detail_info');
        $data_info = $this ->curl->request('rules_api.php');
        $temp_arr = (array)$data_info[0];
        $content = $temp_arr['content'];   //征文
        $data_info = $temp_arr['data_list'];

        $this->curl1 = $this->curl;
        $this->curl1->setSubmitType('post');
        $this->curl1->setReturnFormat('json');
        $this->curl1->initPostData();
        $this->curl1->addRequestData('device_token', $this->device_token);
        $this->curl1->addRequestData('access_token', $this->input['access_token']);
        $this->curl1->addRequestData('_outercall', 1);
        $this->curl1->addRequestData('id', $id);
        $this->curl1->addRequestData('bundle_id', $bundle_id);
        $this->curl1->addRequestData('a', 'trak_info');
        $trak_list = $this ->curl1->request('rules_api.php');
        $trak_list = (array)$trak_list[0];

        echo json_encode(array('data_info'=>$data_info,'trak_list'=>$trak_list,'content'=>$content));
        //print_r($trak_list);
        exit;
    }


    /*
     * name:audit
     * 作用：完成审核的功能
     * */
    public function audit(){
        $status = $this->input['status'];
        $id = $this->input['id'];
        $bundle_id = $this->input['bundle_id'];
        $remark = trim($this->input['remark']);

        //如果没有传参数，参数错误
        if(!$status || !$id || !$bundle_id){
            $data = array('error'=>'参数错误');
            echo $data;
            exit;
        }

        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('device_token', $this->device_token);
        $this->curl->addRequestData('access_token', $this->input['access_token']);
        $this->curl->addRequestData('_outercall', 1);
        $this->curl->addRequestData('status', $status);
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('bundle_id', $bundle_id);
        $this->curl->addRequestData('remark', $remark);
        $this->curl->addRequestData('a', 'audit');
        $data = $this->curl->request('rules_api.php');

        $data = json_encode($data);
        echo $data;
        //print_r($data);
        exit();




    }

    /*
     * name:designate
     * 作用：完成指派
     * */
    public function designate(){
        $bundle_id = $this->input['bundle_id'];
        $id = $this->input['id'];
        $user_id = intval($this->input['user_id']);
        $user_name = $this->input['user_name'];

        //如果缺少必须参数，参数错误
        if(!$bundle_id || !$id || !$user_id || !$user_name){
            $result = array('error'=>'参数错误');
            echo json_encode($result);
            exit;
        }

        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('device_token', $this->device_token);
        $this->curl->addRequestData('access_token', $this->input['access_token']);
        $this->curl->addRequestData('_outercall', 1);
        $this->curl->addRequestData('bundle_id', $bundle_id);
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('user_id', $user_id);
        $this->curl->addRequestData('user_name', $user_name);
        $this->curl->addRequestData('a', 'designate');
        $result = $this ->curl->request('rules_api.php');
        echo json_encode($result);
        exit;
    }




    //获取用户信息
    public function getUserInfo(){
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('access_token', trim($this->input['access_token']));
        $this->curl->addRequestData('_outercall', 1);
        $this->curl->addRequestData('a', 'getUserInfo');
        $data_list = $this ->curl->request('rules_api.php');
        $data_list = (array)$data_list[0];
        echo json_encode($data_list);
    }

}

$out = new rules();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'rules';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();
?>