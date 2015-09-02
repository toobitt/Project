<?php
/**
 * 不需要用户登录
 */
define('MOD_UNIQUEID', 'aboke');
require_once ('global.php');
require_once (ROOT_PATH . 'lib/class/curl.class.php');
include (CUR_CONF_PATH . 'lib/Core.class.php');
class  FrontVideoUpdateAPI extends outerUpdateBase {
    private $tbname = 'video';
    public function __construct() {
        parent::__construct();
        $this -> obj = new Core();
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function create() {

    }

    public function update() {
        if (!isset($this -> input['id'])) {
            $this -> errorOutput("NO_ID");
        }
        
        $params = $this->get_condition();
        
        if(!$params)
        {
            $this -> errorOutput("NO_PARAM");
        }
    
        $id = intval($this -> input['id']);

        $cond = " where 1 and id=$id";

        $datas = $this -> obj -> update($this -> tbname, $params, $cond);

        
        $this -> addItem($params);
        $this -> output();
    }

    public function publish() {

    }

    public function delete() {

    }

    //获取boke记录的视频video_id
    private function get_video_id($id) {
        $videos = $this -> obj -> show($this -> tbname, ' where id in (' . $id . ') and user_id=' . $this -> user['user_id']);
        $video_ids = '';
        foreach ($videos as $video) {
            $video_ids .= $video['video_id'] . ',';
        }
        $video_ids = substr($video_ids, 0, -1);
        return $video_ids;
    }

    public function audit() {

    }

    public function sort() {

    }

    private function get_condition() {
        $params = array();

        $id = intval($this -> input['id']);

        $cond = " WHERE 1 AND `id`=$id";

        $info = $this -> obj -> detail($this -> tbname, $cond);
        

        if (!info) {
            return false;
        }

        if (isset($this -> input['num_zan'])) {
            $params['num_zan'] = $info['num_zan'] + 1;
        }

        if (isset($this -> input['num_ding'])) {
            $params['num_ding'] = $info['num_ding'] + 1;
        }

        if (isset($this -> input['num_click'])) {
            $params['num_click'] = $info['num_click'] + 1;
        }

        if (isset($this -> input['num_share'])) {
            $params['num_share'] = $info['num_share'] + 1;
        }

        if (isset($this -> input['num_favor'])) {
            $params['num_favor'] = $info['num_favor'] + 1;
        }

        if (isset($this -> input['num_comment'])) {
            $params['num_comment'] = $info['num_comment'] + 1;
        }
        return $params;

    }

    public function unknow() {
        $this -> errorOutput(NO_ACTION);
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

}

$out = new FrontVideoUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>

