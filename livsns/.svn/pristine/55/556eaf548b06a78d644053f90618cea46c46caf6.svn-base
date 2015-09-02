<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-8-2
 * Time: 下午5:43
 */
class im
{
    function __construct()
    {
        global $gGlobalConfig;
        include_once (ROOT_PATH . 'lib/class/curl.class.php');
        if($gGlobalConfig['App_im'])
        {
            $this->curl = new curl($gGlobalConfig['App_im']['host'], $gGlobalConfig['App_im']['dir']);
        }
    }

    function __destruct()
    {

    }

    public function create_session($data = array())
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','create_session');
        foreach ((array)$data as $k => $v)
        {
            if (is_array($v))
            {
                $this->array_to_add($k, $v);
            }
            else
            {
                $this->curl->addRequestData($k, $v);
            }
        }
        $ret = $this->curl->request('message.php');
        isset($ret[0]) && $ret = $ret[0];
        return $ret;
    }
    
	/**
     * 获取消息列表
     */
    public function show()
    {
    		
    }


    /**
     * 前段界面使用
     * @param array $params
     * @return array
     */
    public function chatroom_messages($params = array())
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        foreach((array)$params as $k => $v)
        {
            $this->curl->addRequestData($k,$v);
        }
        $this->curl->addRequestData('a','detail');
        $ret = $this->curl->request('message.php');
        return $ret[0];
    }

    /**
     * 直播互动后台使用
     * @param array $params
     * @return array
     */
    public function session_messages($params = array())
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        foreach((array)$params as $k => $v)
        {
            $this->curl->addRequestData($k,$v);
        }
        $this->curl->addRequestData('a','session_messages');
        $ret = $this->curl->request('message.php');
        return $ret[0];
    }

    /**
     * 获取从上次刷新后未读消息数
     *
     * @param session_id   会话id
     * @param refresh_time  上次刷新时间
     */
    public function get_unread_num($session_id, $refresh_time, $is_presenter = '')
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('session_id', $session_id);
        $this->curl->addRequestData('refresh_time', $refresh_time);
        $this->curl->addRequestData('is_presenter', $is_presenter);
        $this->curl->addRequestData('a', 'get_unread_num');
        $ret = $this->curl->request('message.php');
        return $ret[0];
    }
    
    /**
     * 增加消息
     */
    public function create($chatroom_id, $message, $files = '', $params = array())
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('session_id',$chatroom_id);
        $this->curl->addRequestData('message',$message);
        foreach((array)$params as $k => $v)
        {
            $this->curl->addRequestData($k,$v);
        }
        $this->curl->addFile($files);
        $this->curl->addRequestData('a','reply_session');
        $ret = $this->curl->request('message.php');
        return $ret[0];
    }
    
	/**
     * 删除消息
     */
    public function delete($id)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id',$id);
        $this->curl->addRequestData('a','delete_message');
        $ret = $this->curl->request('message.php');
        return $ret[0];
    }
    
	/**
     * 审核消息
     */
    public function audit($id = '', $audit = '')
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id',$id);
        $this->curl->addRequestData('audit',$audit);
        $this->curl->addRequestData('a','audit');
        $ret = $this->curl->request('message.php');
        return $ret[0];
    }
    
	/**
     * 推荐消息
     */
    public function recommend($id, $op)
    {
        if (!$this->curl )
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id',$id);
        $this->curl->addRequestData('op',$op);
        $this->curl->addRequestData('a','recommend');
        $ret = $this->curl->request('message.php');
        return $ret[0];
    }
    
	/**
     * 消息标签
     */
    public function tag($id, $tag_id, $tag_name)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id',$id);
        $this->curl->addRequestData('tag_id',$tag_id);
        $this->curl->addRequestData('tag_name',$tag_name);
        $this->curl->addRequestData('a','tag');
        $ret = $this->curl->request('message.php');
        return $ret[0];
    }
    
	/**
     * 批注消息
     */
    public function remarks($id, $remarks)
    {
        if (!$this->curl || !$id || !$remarks)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id',$id);
        $this->curl->addRequestData('remarks',$remarks);
        $this->curl->addRequestData('a','remarks');
        $ret = $this->curl->request('message.php');
        return $ret[0];
    }

    public function create_comment($message_id, $comment)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('message_id',$message_id);
        $this->curl->addRequestData('comment',$comment);
        $this->curl->addRequestData('a','create_comment');
        $ret = $this->curl->request('message.php');
        return $ret[0];
    }

    public function update_comment($id, $comment)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id',$id);
        $this->curl->addRequestData('comment',$comment);
        $this->curl->addRequestData('a','update_comment');
        $ret = $this->curl->request('message.php');
        return $ret[0];
    }

    public function delete_comment($id)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id',$id);
        $this->curl->addRequestData('a','delete_comment');
        $ret = $this->curl->request('message.php');
        return $ret[0];
    }

    public function get_rckey($app_id)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('app_id', $app_id);
        $this->curl->addRequestData('a','get_rckey');
        $ret = $this->curl->request('rongcloud.php');
        if($ret && is_array($ret) && isset($ret['rcAppKey']))
        {
            return $ret['rcAppKey'];
        }
        else 
        {
            return FALSE;
        }
    }
    
    /**
     * 检查黑名单
     */
    public function check_black($app_id)
    {
    	if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('app_id', $app_id);
        $this->curl->addRequestData('a','check_blackByappId');
        $ret = $this->curl->request('rongcloud_blacklist.php');
       	return $ret[0];
    }
    
    /**
     * 更新rongcloud_info 
     * @param unknown $str
     * @param unknown $data
     */
    public function updateRcInfo($app_id,$app_name)
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('post');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('app_id', $app_id);
    	$this->curl->addRequestData('app_name', $app_name);
    	$this->curl->addRequestData('a','updateByAppid');
    	$ret = $this->curl->request('admin/rongcloud_info_update.php');
    	return $ret[0];
    }
    
    /**
     * 更新IMtoken
     * @param unknown $str
     * @param unknown $data
     * data : app_id userId userName avatar
     */
    public function refreshImInfo($data = array())
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        if ($data && is_array($data))
        {
            foreach ($data as $k => $v)
            {
                if (is_array($v))
                {
                    $this->array_to_add($k, $v);
                }
                else
                {
                    $this->curl->addRequestData($k, $v);
                }
            }
        }
        $this->curl->addRequestData('a','refresh');
        $ret = $this->curl->request('rongcloud.php');
        return $ret[0];
    }

    /**
     * 获取会员加入的群组
     *
     * @param $member_id
     * @return array
     * @internal param $str
     * @internal param $data
     */
    public function getGroupCountBymemberId($member_id)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('member_id', $member_id);
        $this->curl->addRequestData('a','getGroupCountBymemberId');
        $ret = $this->curl->request('message.php');
        return $ret[0];
    }

    /**
     * 申请融云应用密钥
     *
     * @param $app_id  应用ID
     * @param $app_name 应用名称
     * @param $brief 应用描述
     * @return array
     */
    public function apply_signature($app_id, $app_name, $brief)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('app_id', $app_id);
        $this->curl->addRequestData('app_name', $app_name);
        $this->curl->addRequestData('brief', $brief);
        $this->curl->addRequestData('a','apply_signature');
        $ret = $this->curl->request('rongcloud.php');
        return $ret[0];
    }


    public function array_to_add($str , $data)
    {
        $str = $str ? $str : 'data';
        if(is_array($data))
        {
            foreach ($data AS $kk => $vv)
            {
                if(is_array($vv))
                {
                    $this->array_to_add($str . "[$kk]" , $vv);
                }
                else
                {
                    $this->curl->addRequestData($str . "[$kk]", $vv);
                }
            }
        }
    }

}