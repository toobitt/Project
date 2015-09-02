<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-7-31
 * Time: 上午11:15
 */
require('global.php');
define('MOD_UNIQUEID','topic');
class topicApi extends adminReadBase
{
    public function __construct()
    {
        $this->mPrmsMethods = array(
//            'show'		=>'查看',
//            'create'	=>'增加',
//            'update'	=>'修改',
//            'delete'	=>'删除',
//            'audit'		=>'审核',
            'show'       => '查看',
            'presenter'  => '主持人页',
            'director'   => '导播页',
            'manage'     => '管理',
            '_node'=>array(
                'name'=>'频道',
                'filename'=>'node.php',
                'node_uniqueid'=>'topic',
            ),
        );
        parent::__construct();
        include_once CUR_CONF_PATH . 'lib/mode.class.php';
        $this->mode = new mode();
    }

    public function index(){}

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
        #####
        $this->verify_content_prms();
        #####
        $condition = $this->get_condition();
        file_put_contents('../cache/1234.txt', $condition);
        $order = ' order_id DESC ';
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit = $offset . ', ' . $count;

        $ret = $this->mode->select($condition, $order, $limit);
        $indexpic_id = array();
        foreach ((array)$ret as $key => $val) {
            if ($val['indexpic'])
            {
                $indexpic_id[] = $val['indexpic'];
            }
        }
        $pic = array();
        if ($indexpic_id)
        {
            $indexpic_id = implode(',', $indexpic_id);
            $condition = ' AND id IN('.$indexpic_id.')';
            $pic = $this->mode->select_material($condition, '', '', '', 'topic_id');
        }
        foreach ((array)$ret as $key => $val) {
            isset($val['create_time']) && ($val['create_time_show'] = date('Y-m-d H:i:s', $val['create_time']));
            isset($val['update_time']) && $val['update_time_show'] = date('Y-m-d H:i:s', $val['update_time']);
            if (isset($val['start_time'])) {
                $val['start_time_show'] = date('Y-m-d H:i', $val['start_time']);
            }
            if (isset($val['end_time'])) {
                $val['end_time_show'] = date('Y-m-d H:i', $val['end_time']);
            }
            $time_status = hg_process_time_status($val['start_time'], $val['end_time']);
            $val['time_status'] = $time_status['time_status'];
            $val['time_status_text'] = $time_status['time_status_text'];
            isset($val['status']) && $val['status_text'] = $this->settings['status_show'][$val['status']];
            isset($val['status']) && $val['state'] = $val['status'];
            $val['indexpic_url'] = isset($pic[$val['id']][0]['pic']) ? $pic[$val['id']][0]['pic'] : '';
            $val['indexpic_url'] = $val['indexpic_url'] != '' ? json_decode($val['indexpic_url'],1) : array();
            $this->addItem($val);
        }
        $this->output();

    }

    public function detail()
    {

        #####
        $this->verify_content_prms(array('_action'=>'show'));
        #####
        $id = $this->input['id'];
        if(!$id)
        {
            $this->errorOutput('NOID');
        }
        $ret = $this->mode->getOne(' AND id = ' . $id);

        if ($ret)
        {
            if (isset($ret['start_time'])) {
                $ret['start_time_show'] = date('Y-m-d H:i', $ret['start_time']);
            }
            if (isset($ret['end_time'])) {
                $ret['end_time_show'] = date('Y-m-d H:i', $ret['end_time']);
            }
            $ret['indexpic_url'] = $ret['indexpic_url'] != '' ? json_decode($ret['indexpic_url'], 1) : array();

            //查询图片信息
            $material = $this->mode->select_material(' AND topic_id = ' . $id);
            foreach ((array)$material as $k => $v) {
                $v['pic'] = $v['pic'] != '' ? json_decode($v['pic'], 1) : array();
                $material[$k] = $v;
            }
            $ret['material'] = $material;
            $this->addItem($ret);
        }

        $this->output();
    }


    public function count() {


        $condition = $this->get_condition();

        $total = $this->mode->count($condition);

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
            $condition .= " AND title LIKE '%".$this->input['key']."%'";
        }
		
        if($this->input['time_status'] != '-1')
        {
        		if($this->input['time_status'] == '0') //即将开始
        		{
        			$condition .= " AND start_time > " .TIMENOW;
        		}
        		switch($this->input['time_status'])
        		{
        			case 1: //进行中
        				$condition .= " AND start_time < " .TIMENOW.  " AND end_time > " .TIMENOW;
        				break;
        			case 2: //已结束
        				$condition .= " AND end_time < " .TIMENOW;
        				break;
        		}
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

        ####增加权限控制 用于显示####
        if($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if(!$this->user['prms']['default_setting']['show_other_data'])
            {
                $condition .= ' AND user_id = '.$this->user['user_id'];
            }
            else
            {
                //组织以内
                if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
                {
                    $condition .= ' AND org_id IN('.$this->user['slave_org'].')';
                }
            }
            if($authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
            {
                $authnode_str = $authnode ? implode(',', $authnode) : '';
                if($authnode_str === '0')
                {
                    $condition .= ' AND channel_id IN(' . $authnode_str . ')';
                }
                if($authnode_str)
                {
                    if(!$this->input['_id'])
                    {
                        $condition .= ' AND channel_id IN(' . $authnode_str . ')';
                    }
                    else
                    {
                        $authnode_array = explode(',', $authnode_str);
                        if(!in_array($this->input['_id'], $authnode_array))
                        {
                            $this->errorOutput(NO_PRIVILEGE);
                        }
                    }
                }
            }
        }

        if ($this->input['_id'])
        {
            $condition .= " AND channel_id = " . intval($this->input['_id']);
        }
        if ($this->input['user_name'])
        {
            $condition .= " AND user_name LIKE '%".trim($this->input['user_name'])."%' ";
        }


        if ($this->input['start_time'] == $this->input['end_time']) {
            $his = date('His', strtotime($this->input['start_time']));
            if (! intval($his)) {
                $this->input['start_time'] = date('Y-m-d', strtotime($this->input['start_time'])). ' 00:00';
                $this->input['end_time'] = date('Y-m-d', strtotime($this->input['end_time'])). ' 23:59';
            }
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

        //话题有效时间
        if($this->input['time_frame'])
        {
            $today = date('Y-m-d');
            $tomorrow = date('Y-m-d',TIMENOW+24*3600);
            switch(intval($this->input['time_frame']))
            {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday = date('y-m-d',TIMENOW-24*3600);
                    $this->input['time_frame_start'] = $yesterday;
                    $this->input['time_frame_end'] = $today;
                    break;
                case 3://今天的数据
                    $this->input['time_frame_start'] = $today;
                    $this->input['time_frame_end'] = $tomorrow;
                    break;
                case 4://最近3天
                    $last_threeday = date('y-m-d',TIMENOW-2*24*3600);
                    $this->input['time_frame_start'] = $last_threeday;
                    $this->input['time_frame_end'] = $tomorrow;
                    break;
                case 5://最近7天
                    $last_sevenday = date('y-m-d',TIMENOW-6*24*3600);
                    $this->input['time_frame_start'] = $last_sevenday;
                    $this->input['time_frame_end'] = $tomorrow;
                    break;
                default://所有时间段
                    break;
            }
        }


        if ($this->input['time_frame_start'] == $this->input['time_frame_end']) {
            $his = date('His', strtotime($this->input['time_frame_start']));
            if (! intval($his)) {
                $this->input['time_frame_start'] = date('Y-m-d', strtotime($this->input['time_frame_start'])). ' 00:00';
                $this->input['time_frame_end'] = date('Y-m-d', strtotime($this->input['time_frame_end'])). ' 23:59';
            }
        }
        
   		//话题的起始时间
        if($this->input['time_frame_start'] || $this->input['time_frame_end'])
        {
            $this->input['time_frame_start'] = intval(strtotime($this->input['time_frame_start']));
            $this->input['time_frame_end'] = intval(strtotime($this->input['time_frame_end']));
            if ( !$this->input['time_frame_end'] )
            {
                $this->input['time_frame_end'] = 9999999999;
            }
            $condition .= " AND (
                    (start_time <= " .$this->input['time_frame_start']. " AND end_time >= " .$this->input['time_frame_end']. " AND ".$this->input['time_frame_start']." <=".$this->input['time_frame_end'].")
                OR (start_time >= ".$this->input['time_frame_start']." AND end_time <= ".$this->input['time_frame_end']." AND end_time >= ".$this->input['time_frame_start'].")
                OR (start_time >= " .$this->input['time_frame_start']. " AND start_time <= " .$this->input['time_frame_end']. " AND end_time >=".$this->input['time_frame_end'].")
                OR (start_time <=".$this->input['time_frame_start']." AND end_time >= ".$this->input['time_frame_start']." AND end_time <=".$this->input['time_frame_end'].")
            ) ";
        }
        return $condition;
    }

    public function guest_list()
    {
        $condition = '';
        $condition .= ' AND status = 1';
        $guests = $this->mode->select_guests($condition);
        foreach((array)$guests as $guest)
        {
            $guest['indexpic'] = $guest['indexpic'] != '' ? json_decode($guest['indexpic'],1) : array();
            $info = array(
              'id' => $guest['id'],
              'title' => $guest['title'],
              'brief' => $guest['brief'],
              'indexpic' => $guest['indexpic'],
              'link'   => $guest['link'],
            );
            $this->addItem($info);
        }
        $this->output();
    }

}

$out = new topicApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action)) {
    $action = 'show';
}
$out->$action();

/* End of file topic.php */
