<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-7-31
 * Time: 上午11:15
 */
require('global.php');
define('MOD_UNIQUEID','tag');
class tagApi extends adminReadBase
{
    public function __construct()
    {
        parent::__construct();
        include_once CUR_CONF_PATH . 'lib/mode.class.php';
        $this->mode = new mode();
    }

    public function index(){}

    public function __destruct() {
        parent::__destruct();
    }

    public function show()
    {
        $condition = $this->get_condition();
        $order = ' order_id DESC ';
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit = $offset . ', ' . $count;

        $ret = $this->mode->select_tags($condition, $order, $limit);
        foreach ((array)$ret as $key => $val) {
            isset($val['create_time']) && ($val['create_time_show'] = date('Y-m-d H:i:s', $val['create_time']));
            isset($val['update_time']) && $val['update_time_show'] = date('Y-m-d H:i:s', $val['update_time']);
            isset($val['status']) && $val['status_text'] = $this->settings['status_show'][$val['status']];
            isset($val['status']) && $val['state'] = $val['status'];
            $this->addItem($val);
        }
        $this->output();

    }

    public function tag_lists()
    {
        $condition = $this->get_condition();
        $sql = "SELECT id, title, color  FROM ".DB_PREFIX."tags WHERE 1 " . $condition;
        $q = $this->db->query($sql);
        $tags= array();
        while (($row = $this->db->fetch_array($q)) != false)
        {
            $tags[$row['id']] = $row;
        }
        $this->addItem($tags);
        $this->output();
    }


    public function detail(){

        $id = $this->input['id'];


        if(!$id) {
            $this->errorOutput('NOID');
        }

        $ret = $this->mode->getOneTag(' AND id = ' . $id);

        if ($ret) {
            $this->addItem($ret);
        }

        $this->output();
    }


    public function count() {


        $condition = $this->get_condition();

        $total = $this->mode->countTags($condition);

        echo json_encode($total);
    }

    private function get_condition()
    {
        $condition = '';
        //搜索标签
        if ($this->input['searchtag_id']) {
            $searchtag = $this->searchtag_detail(intval($this->input['searchtag_id']));
            foreach ((array)$searchtag['tag_val'] as $k => $v) {
                if ( in_array( $k, array('_id') ) )
                {
                    //防止左边栏分类搜索无效
                    continue;
                }
                $this->input[$k] = $v;
            }
        }
        //搜索标签

        if ($this->input['key']) {
            $condition .= " AND title LIKE '%".trim($this->input['key'])."%'";
        }

        if ($this->input['status'])
        {
            switch (intval($this->input['status']))
            {
                case 1: //待审核
                    $status = 0;
                    break;
                case 2://已审核
                    $status = 1;
                    break;
                case 3: //已打回
                    $status = 2;
                    break;
                default:
                    break;
            }
            $condition .= " AND status= " . $status;
        }

        if ($this->input['user_name'])
        {
            $condition .= " AND user_name LIKE '%".trim($this->input['user_name'])."%' ";
        }

        //查询创建的起始时间
        if($this->input['start_time'])
        {
            $condition .= " AND create_time > " . strtotime($this->input['start_time']);
        }

        //查询创建的结束时间
        if($this->input['end_time'])
        {
            $condition .= " AND create_time < " . strtotime($this->input['end_time']);
        }

        //查询发布的时间
        if($this->input['date_search'])
        {
            $today = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));
            switch(intval($this->input['date_search']))
            {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
                    $condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
                    $condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
                    $condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
                    break;
                default://所有时间段
                    break;
            }
        }

        return $condition;
    }

}

$out = new tagApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action)) {
    $action = 'show';
}
$out->$action();

/* End of file topic.php */
