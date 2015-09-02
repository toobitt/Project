<?php
/**
 * 不需要用户登录
 */
define('MOD_UNIQUEID', 'aboke');
require ('global.php');
include (CUR_CONF_PATH . 'lib/Core.class.php');
class FrontVideoAPI extends  outerReadBase {
    private $obj = null;
    private $tbname = 'video';
    public function __construct() {
        parent::__construct();
        $this -> obj = new Core();
    }

    public function detail() {
        $id = intval($this -> input['id']);
        if (!$id) {
            $this -> errorOutput(NO_ID);
        }

        $data_limit = 'where `id`=' . $id;

        $info = $this -> obj -> detail($this -> tbname, $data_limit);

        if (!$info) {
            $this -> errorOutput(NO_DATA_EXIST);
        }
        //初始视频信息，默认没有视频信息
        $info['info'] = false;

        if ($info['state']) {
            $videoinfo = $this -> get_video_moreinfo($info['video_id']);
            $info['info'] = $videoinfo[0];
        }

        $this -> addItem($info);
        $this -> output();
    }

    /**
     * 获取播放次数最多的
     */
    public function get_most_play() {
        $offset = $this -> input['offset'] ? intval($this -> input['offset']) : 0;
        $count = $this -> input['count'] ? intval($this -> input['count']) : 20;
        $data_limit = ' where 1 and num_click>0 order by num_click desc LIMIT ' . $offset . ' , ' . $count;
        $datas = $this -> obj -> show($this -> tbname, $data_limit, $fields = '*');

        foreach ($datas as $k => $v) {
            $this -> addItem($v);
        }
        $this -> output();
    }

    public function show() {
        $condition = $this -> get_condition();
        $offset = $this -> input['offset'] ? $this -> input['offset'] : 0;
        $count = $this -> input['count'] ? intval($this -> input['count']) : 20;
        $data_limit = $condition . ' order by id desc LIMIT ' . $offset . ' , ' . $count;
        $datas = $this -> obj -> show($this -> tbname, $data_limit, $fields = '*');

        foreach ($datas as $k => $v) {
            $this -> addItem($v);
        }
        $this -> output();
    }

    /**
     * 通过id获取视频信息
     */
    public function get_info_by_id() {
        $ids = $this -> input['ids'];
        $data_limit = ' where id in (' . $ids . ') ';
        $datas = $this -> obj -> show($this -> tbname, $data_limit, $fields = '*');

        foreach ($datas as $k => $v) {
            $this -> addItem($v);
        }
        $this -> output();
    }

    /**
     * 获取视频库中的视频信息
     */
    private function get_video_moreinfo($ids) {
        $return = array();
        if ($ids) {
            $this -> create_curl_obj('livmedia');
            $params['id'] = $ids;
            $params['a'] = 'detail';
            $params['r'] = 'vod';
            $return = $this -> get_common_datas($params);
            return $return;
        }
        return $return;
    }

    public function count() {
        $condition = $this -> get_condition();
        $info = $this -> obj -> count($this -> tbname, $condition);
        echo json_encode($info);
    }

    public function index() {

    }

    private function get_condition() {
        //默认用户需要登陆
        $cond = " where 1 ";

        if (isset($this -> input['type'])) {
            $cond .= ' and type=' . intval($this -> input['type']);
        }

        //用户自定义分类
        if (isset($this -> input['cate_id'])) {
            $cond .= ' and cate_id=' . intval($this -> input['cate_id']);
        }

        //系统分类
        if (isset($this -> input['admin_cate_id'])) {
            $cond .= " and admin_cate_id=" . intval($this -> input['admin_cate_id']);
        }

        if (isset($this -> input['ids'])) {
            $cond .= " and id in (" . $this -> input['ids'] . ")";
        }

        //状态
        if (isset($this -> input['state'])) {
            $cond .= ' and state=' . intval($this -> input['state']);
        }
        
        //关键字
        if(isset($this->input['keyword']))
        {
            $cond .= " and keyword like '%" .trim($this->input['keyword'])."%'" ;
        }
        
        //关键字
        if(isset($this->input['title']))
        {
            $cond .= " and title like '%" .trim($this->input['title'])."%'" ;
        }
        //关键字
        if(isset($this->input['user_name']))
        {
            $cond .= " and user_name ='" .trim($this->input['user_name']) ."'";
        }                

        return $cond;
    }

    /**
     * 创建curl
     */
    public function create_curl_obj($app_name) {
        $key = 'App_' . $app_name;
        global $gGlobalConfig;
        if (!$gGlobalConfig[$key]) {
            return false;
        }
        $this -> curl = new curl($gGlobalConfig[$key]['host'], $gGlobalConfig[$key]['dir']);
    }

    /**
     * 解析curl数据
     */
    public function get_common_datas($params) {
        $this -> curl -> setSubmitType('post');
        $this -> curl -> setReturnFormat('json');
        $this -> curl -> initPostData();
        foreach ($params as $key => $val) {
            if ($key != 'r') {
                $this -> curl -> addRequestData($key, $val);
            } else {
                return $this -> curl -> request($val . ".php");
            }
        }
    }

    public function unknow() {
        $this -> errorOutput(NO_ACTION);
    }

}

$out = new FrontVideoAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>

