<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-15
 * @encoding    UTF-8
 * @description 意见反馈接口
 **************************************************************************/
define('MOD_UNIQUEID', 'app_plant');
require_once('global.php');
include_once CUR_CONF_PATH . 'lib/appFeedback.class.php';

class app_feedback extends appCommonFrm
{
    private $api;
    public function __construct()
    {
        parent::__construct();
        $this->api = new appFeedback();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
    }

    /**
     * 获取意见反馈的列表
     *
     * @access public
     * @param  offset | count | flag
     * @return array
     */
    public function show()
    {
        $offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
        $count  = isset($this->input['count']) ? intval($this->input['count']) : 20;
        $flag   = isset($this->input['flag']) ? intval($this->input['flag']) : 0;
        $data   = array(
			'offset'     => $offset,
			'count'      => $count,
			'condition'  => $this->condition(),
		    'flag'       => $flag
        );
        $appFeedback_info = $this->api->show($data);
        $this->setXmlNode('appFeedback_info', 'feedback');
        if ($appFeedback_info)
        {
            foreach ($appFeedback_info as $feedback)
            {
                $this->addItem($feedback);
            }
        }
        $this->output();
    }

    /**
     * 根据条件获取意见反馈的个数
     *
     * @access public
     * @param  无
     * @return array 例如：array('total' => 20)
     */
    public function count()
    {
        $condition = $this->condition();
        $info = $this->api->count($condition);
        echo json_encode($info);
    }

    /**
     * 根据某一个意见反馈详情
     *
     * @access public
     * @param  id:模板的id
     * @return array
     */
    public function detail()
    {
        $id = intval($this->input['id']);
        if ($id <= 0)
        {
            $this->errorOutput(NOID);
        }
         
        $info = $this->api->detail('app_feedback', array('id' => $id));
        $this->addItem($info);
        $this->output();
    }

    /**
     * 获取查询条件
     *
     * @access private
     * @param  $this->input
     * @return array
     */
    private function condition()
    {
        $keyword      = isset($this->input['k']) ? trim(urldecode($this->input['k'])) : '';
        $time         = isset($this->input['date_search']) ? intval($this->input['date_search']) : '';
        $start_time   = trim($this->input['start_time']);
        $end_time     = trim($this->input['end_time']);
        $app_id       = trim($this->input['app_id']);
        $client_type  = trim($this->input['client_type']);
        $device_token = trim($this->input['device_token']);
        $order = $this->input['order'];
        $data = array();
        if (!empty($keyword))
        {
            $data['keyword'] = $keyword;
        }

        if ($start_time)
        {
            $data['start_time'] = $start_time;
        }

        if ($end_time)
        {
            $data['end_time'] = $end_time;
        }

        if ($time)
        {
            $data['date_search'] = $time;
        }

        if ($app_id)
        {
            $data['app_id'] = $app_id;
        }

        if ($client_type)
        {
            $data['client_type'] = $client_type;
        }

        if ($device_token)
        {
            $data['device_token'] = $device_token;
        }

        if (is_array($order))
        {
            foreach ($order as $k => $v)
            {
                $order['f.'.$k] = $v;
                unset($order[$k]);
            }
            $data['order'] = $order;
        }
        return $data;
    }

    /**
     * 创建意见反馈
     *
     * @access public
     * @param  $this->input
     * @return array
     */
    public function create()
    {
        $app_id              = intval($this->input['app_id']);
        $client_type         = intval($this->input['client_type']);
        $device_token        = trim(urldecode($this->input['device_token']));
        $program_version     = trim(urldecode($this->input['program_version']));
        $content             = trim(urldecode($this->input['content']));
        $system              = trim(urldecode($this->input['system']));
        $types               = trim(urldecode($this->input['types']));
        $contact_way         = trim(urldecode($this->input['contact']));
        $app_info            = $this->api->detail('app_info', array('id' => $app_id, 'del' => 0));
        if (!$app_info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }
         
        $client_info = $this->api->detail('app_client', array('id' => $client_type));
        if (!$client_info)
        {
            $this->errorOutput(CLIENT_INFO_NOT_EXISTS);
        }

        if (empty($device_token))
        {
            $this->errorOutput(PARAM_WRONG);
        }
         
        if (empty($program_version))
        {
            $this->errorOutput(PARAM_WRONG);
        }
         
        if (empty($content))
        {
            $this->errorOutput(PARAM_WRONG);
        }
         
        $data = array(
	        'app_id'          => $app_id, 
	        'device_token'    => $device_token,
	        'client_type'     => $client_type,
	        'program_version' => $program_version,
	        'content'         => $content,
	        'system'          => $system,
	        'types'           => $types,
	        'create_time'     => TIMENOW,
	        'contact_way'     => $contact_way
        );
        $result = $this->api->create('app_feedback', $data);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 回复意见反馈
     *
     * @access public
     * @param  $this->input
     * @return array
     */
    public function reply()
    {
        $reply_id     = intval($this->input['reply_id']);
        $content      = trim(urldecode($this->input['reply_content']));
        $info         = $this->api->detail('app_feedback', array('id' => $reply_id));
        if (!$info)
        {
            $this->errorOutput();
        }
         
        $app_info = $this->api->detail('app_info', array('id' => $info['app_id'], 'del' => 0));
        if (!$app_info) $this->errorOutput(NO_APPID);
        if (empty($content)) $this->errorOutput(PARAM_WRONG);
        $data = array(
	        'reply_id'      => $reply_id,
	        'reply_content' => $content,
	        'user_id'       => $this->user['user_id'],
	        'user_name'     => $this->user['user_name'],
	        'org_id'        => $this->user['org_id'],
	        'reply_time'    => TIMENOW,
	        'reply_ip'      => hg_getip()
        );
        $result = $this->api->create('app_reply', $data);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 删除数据
     *
     * @access public
     * @param  f_id:反馈id
     * @return array
     */
    public function delete()
    {
        $id     = trim(urldecode($this->input['f_id']));
        $id_arr = explode(',', $id);
        $id_arr = array_filter($id_arr, 'filter_arr');
        if (!$id_arr)
        {
            $this->errorOutput(NOID);
        }

        $ids = implode(',', $id_arr);
        $result = $this->api->delete('app_feedback', array('id' => $ids));
        //删除回复数据
        $this->api->delete('app_reply', array('reply_id' => $ids));
        $this->addItem($result);
        $this->output();
    }
}

$out = new app_feedback();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();