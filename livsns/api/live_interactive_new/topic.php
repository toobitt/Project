<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-8-27
 * Time: 下午3:08
 */
require('global.php');
define('MOD_UNIQUEID','topic');
define(SCRIPT_NAME,'Topic');
class Topic extends outerUpdateBase
{
    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    function show(){}

    public function detail(){}
    public function count(){}

    public function create(){}
    public function update(){}
    public function delete(){}


    /**
     * 获取频道当前时间的话题(已审核)
     * 当前时间不存在话题则返回默认话题
     * 默认话题也不存在就创建默认话题
     * 前台调用
     *
     * @param channel_id int  频道id
     *
     * @return  活动详细信息
     */
    public function channel_curr_topic()
    {
        if (!$this->input['channel_id'])
        {
            $this->errorOutput('NO_CHANNEL_ID');
        }
        $channel_id = intval($this->input['channel_id']);

        include_once CUR_CONF_PATH . 'lib/mode.class.php';
        $this->mode = new mode();

        $condition = '';
        $condition .= ' AND channel_id = ' . $channel_id;
        $condition .= ' AND start_time <=' . TIMENOW;
        $condition .= ' AND end_time >' . TIMENOW;
        $condition .= ' AND status = 1';
        $condition .= ' AND default_topic != 1';
        $topic = $this->mode->getOne($condition);

        if (empty($topic))
        {
            $condition ='';
            $condition .= ' AND channel_id = ' . $channel_id;
            $condition .= ' AND default_topic = 1';
            $condition .= ' AND status = 1';
            $topic = $this->mode->getOne($condition);

            if (empty($topic))
            {
                if ( $this->settings['App_live'])
                {
                    include_once(ROOT_PATH . 'lib/class/live.class.php');
                    $this->live = new live();
                    $channel = $this->live->getChannelById($channel_id);
                    $channel = $channel[0];
                }
                if (!empty($channel['logo_rectangle']))
                {
                       $material = array(
                            'pic'  => json_encode($channel['logo_rectangle']),
                       );
                       $indexpic_id = $this->mode->insert($material, 'materials');
                }
                $data = array(
                    'channel_id'        => $channel_id,
                    'channel_name'      => $channel['name'],
                    'title'             => '频道默认话题(自动创建)',
                    'brief'             => '频道默认话题(自动创建)',
                    'indexpic'          => $indexpic_id,
                    'create_time'       => TIMENOW,
                    'update_time'       => TIMENOW,
                    'user_id'           => $this->user['user_id'],
                    'user_name'         => $this->user['user_name'],
                    'org_id'            => $this->user['org_id'],
                    'status'            => 1,
                    'default_topic'     => 1,
                );
                $insert_id = $this->mode->insert($data);
                $topic = $this->mode->getOne(' AND id =' . $insert_id);
                //更改素材与内容关联字段topic_id
                $this->mode->update(array('topic_id' => $insert_id), ' id = ' . $indexpic_id, 'materials');

                //创建聊天室
                $topic['chatroom_id'] = $this->mode->create_chatroom($topic['id'], $topic);
            }
        }

        if (!empty($topic))
        {
            if ( $topic['start_time'] )
            {
                $topic['start_time_show'] = date('Y-m-d H:i', $topic['start_time']);
            }
            else
            {
                $topic['start_time_show'] = '';
            }
            if ( $topic['end_time'] )
            {
                $topic['end_time_show'] = date('Y-m-d H:i', $topic['end_time']);
            }
            else
            {
                $topic['end_time_show'] = '';
            }
            $topic['indexpic_url'] = $topic['indexpic_url'] != '' ? json_decode($topic['indexpic_url'], 1) : array();

            //查询图片信息
            $material = $this->mode->select_material(' AND topic_id = ' . $topic['id']);
            foreach ((array)$material as $k => $v) {
                $v['pic'] = $v['pic'] != '' ? json_decode($v['pic'], 1) : array();
                $material[$k] = $v;
            }
            $topic['material'] = $material;

            foreach ((array)$topic['refer'] as $key => $val)
            {
                unset($topic['refer'][$key]['indexpic_json']);
            }
        }

        $this->addItem_withkey('info', $topic);
        $this->output();
    }


    /**
     * 获取聊天室消息列表
     * 当消息是第一页是 会更新当前用户最后阅读时间
     */
    public function get_chatroom_messages()
    {
        if (!$this->input['chatroom_id'])
        {
            $this->errorOutput('NO_CHATROOM_ID');
        }

        $params = array(
            'session_id'  => intval($this->input['chatroom_id']),
            'status'      => 2,
            'offset'      => $this->input['offset'],
            'count'       => $this->input['count'],
        );

        include_once ROOT_PATH . 'lib/class/im.class.php';
        $this->im = new im();
        $ret = $this->im->chatroom_messages($params);
        $messages = $ret['messages'];
        $this->addItem_withkey('messages', $messages);
        $this->output();
    }


    /**
     * 前台聊天室发送信息接口
     */
    public function replay_message()
    {
        if (!$this->input['chatroom_id'])
        {
            $this->errorOutput('NO_CHATROOM_ID');
        }
//        if (!$this->input['message'])
//        {
//            $this->errorOutput('NO_MESSAGE');
//        }
        $chatroom_id = intval($this->input['chatroom_id']);
        $message = $this->input['message'];
        include_once ROOT_PATH . 'lib/class/im.class.php';
        $this->im = new im();
        //消息默认状态
//        $status = intval($this->settings['default_message_status']);
        $params = array(
            'longitude'   => $this->input['longitude'],
            'latitude'    => $this->input['latitude'],
            'status'      => intval($this->settings['default_message_status']),
            'location'    => $this->input['location'],
            'audio_duration' => $this->input['audio_duration'],
        );
        $message = $this->im->create($chatroom_id, $message,  $_FILES, $params);
        if (!$message)
        {
            $this->errorOutput('发送失败');
        }
        $this->addItem_withkey('message', $message);
        $this->output();
    }


}
require(ROOT_PATH . 'excute.php');
?>

 