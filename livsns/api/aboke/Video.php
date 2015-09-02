<?php
/*******************************************************************
 * filename :Video.php
 * Created  :2013.09.22 Writen by scala
 *
 ******************************************************************/
define('MOD_UNIQUEID', 'aboke');
//模块标识
require ('global.php');
include (CUR_CONF_PATH . 'lib/Core.class.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
class VideoAPI extends  outerReadBase {
    private $obj = null;
    private $tbname = 'video';
    public function __construct() {
        parent::__construct();
        $this -> obj = new Core();
    }


    /**
     * 需要用户登陆，获取视频信息
     */
    public function detail() {
        $id = intval($this -> input['id']);
        if (!$id) {
            $this -> errorOutput(NO_ID);
        }
        $data_limit = 'where `id`=' . $id . ' and `user_id`=' . $this -> user['user_id'];

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
     * 
     * 获取他人视频 ...
     */
    public function getotherVideoList()
    {
    	if(!$this -> input['user_id'])
    	{
    		$this->errorOutput('请输入需要查看的用户id');
    	}
        //默认用户需要登陆
        $cond = " where 1 and state=1 and `user_id`=" . $this -> input['user_id'];

        //用户自定义分类
        if (isset($this -> input['cate_id'])) {
            $cond .= ' and cate_id=' . intval($this -> input['cate_id']);
        }
        
        $offset = $this -> input['offset'] ? $this -> input['offset'] : 0;
        $count = $this -> input['count'] ? intval($this -> input['count']) : 20;
        $data_limit = $cond . ' order by id desc LIMIT ' . $offset . ' , ' . $count;
        $datas = $this -> obj -> show($this -> tbname, $data_limit, $fields = '*');
		$video_id = array();
        foreach ($datas as $k => $v) {
        	$v['stateName'] = $this->settings['state'][$v['state']];
            $v['video_id'] &&  $videoInfo = $this->get_video_moreinfo($v['video_id']);
            $v['m3u8'] = $videoInfo[0]['videoaddr']['default']['m3u8'];
            $v['format_duration'] = time_format($videoInfo[0]['duration']);
            $this -> addItem($v);
            
        	
        }
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
        	$v['stateName'] = $this->settings['state'][$v['state']];
            $this -> addItem($v);
        }
        $this -> output();
    }

    /**
     * 通过id获取视频信息
     */
    public function get_by_id() {
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
    
    public function getnumclick()
    {
    	$sql = 'SELECT cate_id,SUM(num_click) as numclick  FROM  '.DB_PREFIX.'video WHERE 1 AND cate_id IN ('.$this->input['cate_id'].')  group by cate_id';//;
    	$query = $this->db->query($sql);
    	while ($row = $this->db->fetch_array($query))
    	{
    		$this->addItem_withkey($row['cate_id'], array('num_click' => $row['numclick']));
    	}
    	$this->output();
    }
    
    public function getvideonum()
    {
    	//$this->input['cate_id'] = '182,183';
    	//$this->user['user_id'] = '8269';
    	$sql = 'SELECT cate_id,count(*) as videonum  FROM  '.DB_PREFIX.'video WHERE 1 AND cate_id IN ('.$this->input['cate_id'].')  group by cate_id';//;
    	$query = $this->db->query($sql);
    	while ($row = $this->db->fetch_array($query))
    	{
    		$this->addItem_withkey($row['cate_id'], array('videonum' => $row['videonum']));
    	}
    	$this->output();
    }

    public function index() {

    }

    private function get_condition() {
        //默认用户需要登陆
        $cond = " where 1 and `user_id`=" . $this -> user['user_id'];

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

$out = new VideoAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>

