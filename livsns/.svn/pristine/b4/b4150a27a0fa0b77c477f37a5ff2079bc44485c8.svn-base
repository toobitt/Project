<?php
require 'global.php';
require_once(ROOT_PATH.'lib/class/material.class.php');
include_once(CUR_CONF_PATH . 'lib/rongcloud_info_mode.php');
include_once(CUR_CONF_PATH . 'lib/member_mode.php');
require_once(CUR_CONF_PATH . 'lib/member_group_mode.php');
define('MOD_UNIQUEID', 'message');
class messageApi extends outerReadBase {
    
    private $material;
    private $mode;
    private $member;
    private $member_group;
    function __construct() {
        parent::__construct();
        
        include_once(CUR_CONF_PATH . 'lib/message.class.php');
        $this->mode = new message();
        $this->material = new material();
        $this->rcinfo = new rongcloud_info_mode();
        $this->member = new member_mode();
        $this->member_group = new member_group_mode();

        if ( isset($this->user['is_member']) )
        {
            $this->user['user_type'] = $this->user['is_member'] ? 'm2o' : 'admin';
        }
        else
        {
            $this->user['user_type'] = 'm2o';  //默认用户类型为会员
        }
    }
   
    function __destruct() {
        parent::__destruct();
    }
    
    function show() {}
    
    function count() {}

    /**
     * 创建聊天室接口
     */
    public function create_session()
    {
        if (!$this->input['title'])
        {
            $this->errorOutput(NO_NAME);
        }
        $data = array();
        //群组默认50人
        $max_num = intval($this->input['group_num']);
        $with_grouplord = $this->input['with_grouplord'] ?  $this->input['with_grouplord'] : false; //是否加入群组
        $indexpic = '';
        $data = array(
            'create_uid'    => $this->user['user_id'],
            'create_utype'  => $this->user['user_type'],
            'title'         => $this->input['title'],
            'appid'         => $this->input['app_id'], 
            'brief'         => $this->input['brief'],
            'create_time'   => TIMENOW,
            'last_time'     => TIMENOW,
            'settings'      => ($this->input['settings'] && is_array($this->input['settings']))  ? (serialize($this->input['settings'])) : '',
            'app_uniqueid'  => $this->input['app_uniqueid'] ? $this->input['app_uniqueid'] : APP_UNIQUEID,
            'max_num'       => $max_num,   
        );
        
        //检测群组的数量
        $groupCount = $this->mode->session_count(' AND appid='.$data['appid'].' AND type=1');
        if(!$this->input['id'] && $groupCount['total'] >= MAX_GROUP_NUM)
        {
            $this->errorOutput(HAS_MAX_GROUP);
        }
        
        if($_FILES['group_avatar'])
        {
        	//处理avatar图片
        	if($_FILES['group_avatar'] && !$_FILES['group_avatar']['error'])
        	{
        		$_FILES['Filedata'] = $_FILES['group_avatar'];
        		$img_info = $this->material->addMaterial($_FILES);
        		if($img_info)
        		{
        			$avatar = array(
        					'host' 		=> $img_info['host'],
        					'dir' 		=> $img_info['dir'],
        					'filepath' 	=> $img_info['filepath'],
        					'filename' 	=> $img_info['filename'],
        					'width'		=> $img_info['width'],
        					'height'	=> $img_info['height'],
        			);
        			$indexpic = @serialize($avatar);
        		}
        	}
        }
        elseif($this->input['indexpic'])
        {
        	$indexpic = trim(json_encode($this->input['indexpic']));
        }
        elseif($this->input['id'])
        {
        	$group_info = $this->mode->session_info($this->input['id']);
        	$data['indexpic'] = $group_info['indexpic'];
        }
        
        if($indexpic)
        {
            $data['indexpic'] = $indexpic;
        }

        if (!is_array($this->input['touser_name']))
        {
            $this->input['touser_name'] = explode(',', $this->input['touser_name']);
        }

        if (!is_array($this->input['touser_id']))
        {
            $this->input['touser_id'] = explode(',', $this->input['touser_id']);
        }

        if (!is_array($this->input['touser_type']))
        {
            $this->input['touser_type'] = explode(',', $this->input['touser_type']);
        }
        if (!is_array($this->input['touser_device_token']))
        {
            $this->input['touser_device_token'] = explode(',', $this->input['touser_device_token']);
        }
        if (!is_array($this->input['touser_appid']))
        {
            $this->input['touser_appid'] = explode(',', $this->input['touser_appid']);
        }
        $touser_name = $this->input['touser_name'];
        $touser_id = $this->input['touser_id'];
        $touser_type = $this->input['touser_type'];
        $touser_device_token = $this->input['touser_device_token'];
        $touser_appid = $this->input['touser_appid'];

        $init_user = $this->mode->get_member_info($touser_name, $touser_id, $touser_type, $touser_device_token,$touser_appid);

        if($this->input['id'])
        {
            $this->mode->update_session($data,$this->input['id']);
            $data['id'] = $this->input['id'];
            $session_id = $this->input['id'];
        }
        else 
        {
            $data['id'] = $session_id = $this->mode->create_session($data, $init_user);
            if (!$session_id)
            {
            	$this->errorOutput(CREATE_FALSE);
            }
            elseif($with_grouplord)
            {
            	//发送人信息
            	$send_uid = $this->user['user_id'];
            	$send_uname = $this->user['user_name'];
            	$send_utype = $this->user['user_type'];
            	$send_device_token = $this->input['user_device_token'];
            	$send_appid = $this->input['user_appid'] ? $this->input['user_appid'] : $this->user['appid'];
            	$send_user = $this->mode->get_member_info($send_uname, $send_uid, $send_utype, $send_device_token, $send_appid);
            	$this->mode->ender_session($session_id, $send_user);
            }
        }

        $this->addItem($data);
        $this->output();
    }
    
    
    /**
     * 审核会话 (直播互动用)
     */
    public function audit()
    {
        $id = urldecode($this->input['id']);
        if(!$id)
        {
            $this->errorOutput("未传入ID");
        }
        $idArr = explode(',',$id);

        if(intval($this->input['audit']) == 1) //审核
        {
            $sql = "UPDATE " .DB_PREFIX. "message SET status = 1, status_time = " . TIMENOW . " WHERE id IN({$id})";
            $this->db->query($sql);
            $return = array('status' => 1,'id'=> $idArr);
        }
        else if(intval($this->input['audit']) == 0) //打回
        {
            $sql = "UPDATE " .DB_PREFIX. "message SET status = 2 WHERE id IN({$id})";
            $this->db->query($sql);
            $return = array('status' =>2,'id' => $idArr);
        }
        $this->addItem($return);
        $this->output();
    }
    
    /**
     * 获取用户会话列表
     * 移动app接口 手机端调用
     */
    function get_user_sessions() {
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count  = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit = ' LIMIT ' . $offset .', ' . $count;
        
        if(!$this->user['user_id']) {
        	$this->errorOutput(NOT_LOGIN);
        }
        
        $sessions = $this->mode->get_user_sessions($this->user['user_id'], $limit, 'id');
        
        $session_id = array_keys($sessions);
        //取出会话中所有的用户
        $users = $this->mode->session_users($session_id);
        
        foreach ((array)$sessions as $k => $v) {
            if ($v) {
                $sessions[$k]['users'] = $v['users']= $users[$k];
            }
            $this->addItem($v);
        }         
        $this->output();
    }

    /**
     * 获取用户的群组列表
     *
     * @return array
     * @internal param id $int
     */
    public function  getGroupBymemberId()
    {
        $member_id = $this->input['member_id'];

        if(!$member_id)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }

        $grouplist = $this->mode->get_user_sessions($member_id,'','');

        if(!empty($grouplist))
        {
            $this->addItem($grouplist);
        }
        $this->output();
    }

    /**
     * 获取用户的群组数量
     *
     * @return array
     * @internal param id $int
     */
    public function  getGroupCountBymemberId()
    {
        $member_id = $this->input['member_id'];

        if(!$member_id)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }

        $total = $this->mode->get_user_sessions_count($member_id);

        $this->addItem($total);
        $this->output();
    }
    
    /**
     * 获取用户会话列表总数
     */
    function get_user_session_count() {
    	if(!$this->user['user_id']) {
    		$this->errorOutput(NOT_LOGIN);
    	}
        $total = $this->mode->get_user_sessions_count($this->user['user_id']);
        $this->addItem($total);
        $this->output();
    }

    /**
     * 发送消息
     */
    public function send_message() {
        //发送人信息
        if(!$this->user['user_id']) {
            $this->errorOutput(NOT_LOGIN);
        }

        if (!$this->input['message'] && $_FILES['imgs']['error'] == 4 && $_FILES['videofile']['error'] == 4 && $_FILES['audiofile']['error'] == 4) {
            $this->errorOutput(NO_MESSAGE);
        }
        
        if(!$this->input['touser_id'] && !$this->input['touser_name']) {
        	$this->errorOutput(NO_TOUSER);
        }        
        
        //收信人信息
        $touser_id = $this->input['touser_id'];
        $touser_name = $this->input['touser_name'];
        $touser_type = $this->input['touser_type'];
        $touser_device_token = $this->input['touser_device_token'];
        $touser_appid = $this->input['touser_appid'];
        if(empty($touser_name) && empty($touser_id)) {
            $this->errorOutput(NO_TOUSER);
        }
        $message = $this->input['message'];
        $title = $this->input['title'];
        $user = $this->mode->get_member_info($touser_name, $touser_id, $touser_type, $touser_device_token, $touser_appid);
        
        $send_uid = $this->user['user_id'];
        $send_uname = $this->user['user_name'];
        $send_utype = $this->user['user_type'];
        $send_device_token = $this->input['user_device_token'];
        $send_appid = $this->input['user_appid'] ? $this->input['user_appid'] : $this->user['appid'];
        $send_user = $this->mode->get_member_info($send_uname, $send_uid, $send_utype, $send_device_token, $send_appid);
        
        /* 创建会话 */
	    $data = array(
	        'create_uid'    => $this->user['user_id'],
			'create_utype'  => $this->user['user_type'],
			'title'         => $title,
            'brief'         => $this->input['brief'],
			'create_time'   => TIMENOW,
			'last_time'     => TIMENOW,
            'settings'      => ($this->input['settings'] && is_array($this->input['settings'])) ? serialize($this->input['settings']) : '',
            'app_uniqueid'  => $this->input['app_uniqueid'] ? $this->input['app_uniqueid'] : APP_UNIQUEID,
	    ); 
	    $part_users = array_merge($user, $send_user);
	    $session_id = $this->mode->create_session($data, $part_users);
	    /* 创建会话 */
        
        //存入消息
        $message_info = array(
            'session_id'  => $session_id,
            'send_uid'    => $send_user[0]['user_id'],
            'send_uname'  => $send_user[0]['user_name'],
            'send_uavatar'=> $send_user[0]['user_avatar'] ? (serialize($send_user[0]['user_avatar'])) : '',
            'message'     => $message,
            'send_time'   => TIMENOW,
            'ip'          => hg_getip(),
            'location'    => $this->input['location'],
            'longitude'   => $this->input['longitude'],
            'latitude'    => $this->input['latitude'],
            'status'      => intval($this->input['status']),
        );
        (!$message_info['location'] && $message_info['ip']) && $message_info['location'] = hg_getIpInfo($message_info['ip']);

        $imgs = $videos= array();
        //图片上传
        if ($_FILES['imgs'])
        {
            $imgs = $this->mode->upload_imgs($_FILES);
            if ($imgs['error'])
            {
                $this->errorOutput($imgs['error']);
            }
        }
        //音视频上传
        if ($_FILES['videofile'])
        {
            $videos = $this->mode->upload_video($_FILES, $message_info['message']);
            if ($videos['error'])
            {
                $this->errorOutput($imgs['error']);
            }
        }

        //音频上传  不提交到转码
        if ($_FILES['audiofile'])
        {
            $audios = $this->mode->upload_audio($_FILES);
            if ($audios['error'])
            {
                $this->errorOutput($audios['error']);
            }
        }
        $message_info['imgs'] = $imgs ? serialize($imgs) : '';
        $message_info['videos'] = $videos ? serialize($videos) : '';
        $message_info['audios'] = $audios ? serialize($audios) : '';

        $message_info['id'] = $insert_id = $this->db->insert_data($message_info, 'message');   
        
        //更新会话最新发消息人信息        
        $session_info = array(
            'last_message'  => $message_info['message'],
            'last_time'     => TIMENOW,
            'last_uid'      => $message_info['send_uid'],
            'last_uname'    => $message_info['send_uname'],
            'last_uavatar'  => $message_info['send_uavatar'],
        );  
        $this->db->update_data($session_info, 'session', ' id=' . $session_id);   

        //把会话中所有人的信息未读数+1
        $sql = "UPDATE ".DB_PREFIX.'session_user SET unread_counts = unread_counts + 1 WHERE session_id = ' . $session_id . ' AND ( uid != ' . $this->user['user_id'] . ' OR utype != \''.$this->user['user_type'].'\')';
        $this->db->query($sql);
        
        $ret = array_merge($data, $session_info);
        $ret['last_uavatar'] = $ret['last_uavatar'] ? unserialize(stripslashes($ret['last_uavatar'])) : '';
        $ret['users_list'] = $part_users;
        $ret['session_id'] = $session_id;

        //推送消息
        $ret['settings'] = $ret['settings'] ? unserialize($ret['settings']) : '';
        if ($ret['settings']['push_notice'])
        {
            $this->mode->push_notice($message_info['message'], $part_users, $send_user);
        }
        //推送消息

        $this->addItem($ret);
        $this->output();         
    } 
    
    
    /**
     * 回复会话消息
     * 
     * @param session_id  会话id
     * @param mesage      回复内容
     */
    public function reply_session() {
        if ( !$this->user['user_id'] )
        {
            $this->errorOutput(NO_LOGIN);
        }
        if (!$this->input['session_id']) {
            $this->errorOutput(NO_SESSIONID);
        }
        
        if (!trim($this->input['message']) && ( empty($_FILES['imgs']) || $_FILES['imgs']['error'] == 4) && ( empty($_FILES['videofile']) || $_FILES['videofile']['error'] == 4) && (empty($_FILES['audiofile']) || $_FILES['audiofile']['error'] == 4) ) {
            $this->errorOutput(NO_MESSAGE);
        }

        $session_id = intval($this->input['session_id']);
        $message = $this->input['message'];
        
        $send_uid = $this->user['user_id'];
        $send_uname = $this->user['user_name'];
        $send_utype = $this->user['user_type'];
        $send_device_token = $this->input['user_device_token'];
        $send_appid = $this->input['user_appid'] ? $this->input['user_appid'] : $this->user['appid'];
        $send_user = $this->mode->get_member_info($send_uname, $send_uid, $send_utype, $send_device_token, $send_appid);
        
        //判断用户是否已经在会话中
        /*
        $session_users = $this->mode->session_users($session_id);
        $session_users = $session_users[$session_id];
        $exists_users = array();
        foreach ((array) $session_users as $k => $v) {
            $exists_users[$v['uid']] = $v;
        }

        if ( !array_key_exists($send_uid, $exists_users) || ($send_utype != $exists_users[$send_uid]['utype']) ) {
            $this->errorOutput('你不在此会话中');
        }
        */
        //如果用户不在此会话中 将用户加入此会话
        $session_users = $this->mode->session_users($session_id);
        $session_users = $session_users[$session_id];
        $exists_users = array();
        foreach ((array) $session_users as $k => $v) {
            $exists_users[$v['uid']] = $v;
        }
        $this->mode->ender_session($session_id, $send_user, $exists_users);
        
        $message_info = array(
            'session_id'  => $session_id,
            'send_uid'    => $send_user[0]['user_id'],
            'send_uname'  => $send_user[0]['user_name'],
            'send_uavatar'=> $send_user[0]['user_avatar'] ? addslashes(serialize($send_user[0]['user_avatar'])) : '',
            'send_utype'  => $send_user[0]['user_type'],
            'message'     => $message,
            'send_time'   => TIMENOW,
            'ip'          => hg_getip(),
            'location'    => $this->input['location'],
            'longitude'   => $this->input['longitude'],
            'latitude'    => $this->input['latitude'],
            'status'      => intval($this->input['status']),
        );
        (!$message_info['location'] && $message_info['ip']) && $message_info['location'] = hg_getIpInfo($message_info['ip']);

        $imgs = $videos= array();
        //图片上传
        if ($_FILES['imgs'])
        {
            $imgs = $this->mode->upload_imgs($_FILES);
            if ($imgs['error'])
            {
                $this->errorOutput($imgs['error']);
            }
        }

        //视频上传
        if ($_FILES['videofile'])
        {
            $videos = $this->mode->upload_video($_FILES, $message_info['message']);
            if ($videos['error'])
            {
                $this->errorOutput($videos['error']);
            }
        }

        //音频上传  不提交到转码
        if ($_FILES['audiofile'])
        {
            $audios = $this->mode->upload_audio($_FILES);
            if ($audios['error'])
            {
                $this->errorOutput($audios['error']);
            }
        }
        $message_info['imgs'] = $imgs ? serialize($imgs) : '';
        $message_info['videos'] = $videos ? serialize($videos) : '';
        $message_info['audios'] = $audios ? serialize($audios) : '';

        $message_info['id'] = $insert_id = $this->db->insert_data($message_info, 'message');
        
        //更新会话最新发消息人信息        
        $session_info = array(
            'last_message'  => $message_info['message'],
            'last_time'     => TIMENOW,
            'last_uid'      => $message_info['send_uid'],
            'last_uname'    => $message_info['send_uname'],
            'last_uavatar'  => $message_info['send_uavatar'],
        );  
        $this->db->update_data($session_info, 'session', ' id=' . $session_id);           
   
        //把会话中所有人的信息未读数+1
//        $sql = "UPDATE ".DB_PREFIX.'session_user SET unread_counts = unread_counts + 1 WHERE session_id = ' . $session_id . ' AND uid != ' . $this->user['user_id']  . ' AND utype != \''.$this->user['user_type'].'\'';
        $sql = "UPDATE ".DB_PREFIX.'session_user SET unread_counts = unread_counts + 1 WHERE session_id = ' . $session_id . ' AND ( uid != ' . $this->user['user_id'] . ' OR utype != \''.$this->user['user_type'].'\')';
        $this->db->query($sql);  
        //有新会话时把所有用户的删除状态改为0
        $sql = "UPDATE ".DB_PREFIX."session_user SET del_status = 0 WHERE session_id = " . $session_id;
        $this->db->query($sql);     
         
        $message_info['send_uavatar'] = $message_info['send_uavatar'] ? unserialize(stripslashes($message_info['send_uavatar'])) : array();
        $message_info['imgs'] = $message_info['imgs'] ? unserialize(stripslashes($message_info['imgs'])) : array();
        $message_info['videos'] = $message_info['videos'] ? unserialize(stripslashes($message_info['videos'])) : array();
        $message_info['audios'] = $message_info['audios'] ? unserialize(stripslashes($message_info['audios'])) : array();


        //推送消息
        $session_info = $this->mode->session_info($session_id, 'settings');
        if ($session_info['settings']['push_notice'])
        {
            foreach((array)$session_users as $k => $v)
            {
                $session_users[$k]['user_id'] = $v['uid'];
                $session_users[$k]['user_type'] = $v['utype'];
            }
            $this->mode->push_notice($message_info['message'], $session_users, $send_user);
        }
        //推送消息

        $this->addItem($message_info);
        $this->output();
    }   


    /**
     * 增加联系人
     */
    public function add_person() {
        if (!$this->input['session_id']) {
            $this->errorOutput(NO_SESSION_ID);
        }
        
        $session_id = intval($this->input['session_id']);
        $user_id = $this->input['user_id'];
        $user_name = $this->input['user_name'];
        $user_type = $this->input['user_type'];

        if ($user_id && !is_array($user_id)) {
            $user_id = explode(',', $user_id);
        }  
                
        if ($user_name && !is_array($user_name)) {
            $user_name = explode(',', $user_name);
        }

        
        if ($user_type && !is_array($user_type)) {
            $user_type = explode(',', $user_type);
        }  
        
        if(empty($user_id) && empty($user_name)) {
        	$this->errorOutput(NO_USER);
        }

        $user = $this->mode->get_member_info($user_name, $user_id, $user_type);
        
        $session_users = $this->mode->session_users($session_id);
        $session_users = $session_users[$session_id];
        $exists_users = array();
        foreach ((array) $session_users as $k => $v) {
            $exists_users[$v['uid']] = $v;
        }
        $this->mode->ender_session($session_id, $user, $exists_users);
        $this->addItem('success');
        $this->output();        
    }


    /**
     * 申请加入会话 聊天室
     */
    public function ender_session() {
        if (!$this->input['session_id']) {
            $this->errorOutput(NO_SESSION_ID);
        }  
        $session_id = intval($this->input['session_id']);
       
        $sessionInfo = $this->mode->session_info($session_id);
        //判断群人数
        if(intval($sessionInfo['count']) >= intval($sessionInfo['max_num']))
        {
        	$this->errorOutput(NUMBERS_FULL);
        }
        
        $user_id = $this->user['user_id'];
        $user_info = $this->input['userInfo'];
        
        if(!$this->user['user_id']) {
        	$this->errorOutput(NOT_LOGIN);
        }
        if($user_info)
        {
            foreach ($user_info as $k=>$v)
            {
                $user_id = $v['user_id'];
                $user_name = '';
                $user_type = $v['user_type'];
                $user = $this->mode->get_member_info($user_name, $user_id, $user_type);
                $session_users = $this->mode->session_users($session_id);
                $session_users = $session_users[$session_id];
                $exists_users = array();
                foreach ((array) $session_users as $k => $v) {
                    $exists_users[$v['uid']] = $v;
                }
                $this->mode->ender_session($session_id, $user, $exists_users);
            }
        }
        else 
        {
            $user_id = $this->user['user_id'];
            $user_name = '';
            $user_type = $this->user['user_type'];
            $userinfo = $this->mode->userinfo($user_id);
            if ($userinfo)
            {
            	foreach ($userinfo as $k=>$v)
            	{
            		if($v['session_id'] == $session_id)
            		{
		            	$this->errorOutput(IS_JOIN);
            		}
            	}
            }
            $user = $this->mode->get_member_info($user_name, $user_id, $user_type);
            $session_users = $this->mode->session_users($session_id);
            $session_users = $session_users[$session_id];
            $exists_users = array();
            foreach ((array) $session_users as $k => $v) {
                $exists_users[$v['uid']] = $v;
            }
            $this->mode->ender_session($session_id, $user, $exists_users);
        }
        $this->addItem('success');
        $this->output();                           
    } 
    

    /**
     * 查询会话详情信息接口
     */
    public function session_detail() {
        $session_id = intval($this->input['session_id']); 
        if (!$session_id) {
            $this->errorOutput(NO_SESSION_ID);
        }
        $info = $this->mode->session_info($session_id);
        if(empty($info))
        {
            $this->errorOutput(NO_GROUP_INFO);
        }

        $messages = $this->mode->get_session_messages($session_id, $this->input['sort_type']);
        $message = $messages[$session_id];
        $users = $this->mode->session_users($session_id);        
        $users = $users[$session_id];
        $info['is_save'] = 0;
        //获取此群组用户是否保存过
        if($this->user['user_id'])
        {
            $member_id = $this->user['user_id'];
            $condition = ' AND member_id='.$member_id.' AND group_id='.$session_id.'';
            $member_group_info = $this->member_group->show($condition);
            if($member_group_info)
            {
                $info['is_save'] = 1;
            }
        }

        $ret = array('session_info' => $info, 'messages' => $messages, 'users' => $users);

        $this->addItem($ret);
        $this->output();
    }
    

    /**
     * 获取群组列表
     */
    public function session_list()
    {
        $flag = false;
        $appid = intval($this->input['app_id']);
        $user_id = intval($this->user['user_id']);
        $flag = $this->input['flag'];
        $info = array();
        if(!$appid)
        {
            $this->errorOutput(NO_APPID);
        }
        $con = " appid=".$appid." AND type=1";
        $group = $this->mode->session_list($con);
        if(!$flag)
        {
            $user = $this->mode->userinfo($user_id);
            foreach ($group as $k=>$v)
            {
                $v['is_join'] = 0;
                if($user)
                {
                    foreach ($user as $ko=>$vo)
                    {
                        if($v['id'] == $vo['session_id'])
                        {
                            $v['is_join'] = 1;
                        }
                    }
                }
                $info[$k] = $v;
            }
        }
        else 
        {
            $info = $group;
        }
    	$this->addItem($info);
    	$this->output();
    }
    
    /**
     * 设置群管理员
     */
    public function group_admin()
    {
        $is_admin = intval($this->input['is_admin']);
        $groupId = intval($this->input['groupId']);
        $userId = $this->input['userId'];
        $data = array(
                'is_admin' => $is_admin,
                'groupId' => $groupId,
                'userId'  => $userId
        );
        $res = $this->mode->group_admin($data);
        if($res)
        {
            $this->addItem('success');
            $this->output();
        }
    }
    

    /**
     * 获取某一个聊天室的消息内容
     * 移动app接口 手机端调用  前段聊天室调用
     */
    public function detail()
    {
        $session_id = intval($this->input['session_id']);
        if (!$session_id) {
            $this->errorOutput(NO_SESSION_ID);
        }
//        if(!$this->user['user_id']) {
//            $this->errorOutput(NOT_LOGIN);
//        }

        $condition = $this->get_condition();
        $condition .= ' AND session_id = ' . $session_id;
        $condition .= ' ORDER BY id DESC';
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $data_limit = ' LIMIT ' . $offset . ', ' . $count;
        $sql = 'SELECT * FROM '.DB_PREFIX.'message WHERE 1 ' . $condition . $data_limit;
        $q = $this->db->query($sql);
        $messages = $messages_id = array();
        while($row = $this->db->fetch_array($q)) {
            if (isset($row['send_time']))
            {
                $row['send_time_show'] = date('Y-m-d H:i:s', $row['send_time']);
            }
            isset($row['status']) && $row['status_text'] = $this->settings['status_show'][$row['status']];
            $row['send_uavatar'] = $row['send_uavatar'] ? unserialize($row['send_uavatar']) : array();
            $row['imgs'] = $row['imgs'] ? unserialize($row['imgs']) : array();
            $row['videos'] = $row['videos'] ? unserialize($row['videos']) : array();
            $row['audios'] = $row['audios'] ? unserialize($row['audios']) : array();

            //手机会员昵称处理  隐藏手机号中间四位
            if ($row['send_utype'] == 'shouji')
            {
                $row['send_uname'] = hg_hidtel($row['send_uname']);
            }
            //手机会员昵称处理 隐藏手机号中间四位

            $messages[] = $row;
        }
        $messages = hg_array_sort($messages, 'id', 'ASC');


        if ($offset === 0)
        {
            //更新用户的最近阅读时间
            if ($this->user['user_id'])
            {
                $utype = $this->user['user_type'];
                $sql = 'UPDATE '.DB_PREFIX.'session_user SET unread_counts = 0 AND last_read_time = '.TIMENOW.'' .
                    ' WHERE session_id = ' . $session_id . ' AND uid = ' . $this->user['user_id']  .' AND utype = \''.$utype.'\'';
                $this->db->query($sql);
            }
        }
        $this->addItem(array('messages' => $messages));
        $this->output();
    }
    
    public function get_condition()
    {
		$condition = "";
        		
		####增加权限控制 用于显示####
		
		####增加权限控制 用于显示####
		
		if(trim($this->input['k']))
		{
			$condition .= ' AND message LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
    	if($this->input['status'])
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
			$condition .= " AND status = " .$status;
		}
		
		if(trim($this->input['tag_name']))
		{
			$condition .= ' AND  tag_name  LIKE "%'.trim(($this->input['tag_name'])).'%"';
		}
		
    		if($this->input['tag_id'])
		{
			$condition .= " AND  tag_id = " .$this->input['tag_id'];
		}
		
    		if($this->input['is_recommend'])
		{
			$condition .= " AND  is_recommend = 1";
		}
		
		return $condition;
	}
    
    
    /**
     * 退出聊天室接口, 
     * 不会接受到此会话里的消息
     * 
     */
    public function logout_session() {
    	$session_id = $this->input['session_id'];
    	if(!$session_id) {
    		$this->errorOutput(NO_SESSION_ID);
    	}
    	if(!$this->user['user_id']) {
    		$this->errorOutput(NOT_LOGIN);
    	}
    	
		$user = $this->mode->get_member_info($this->user['user_name'], $this->user['user_id']);    	
		$user = $user[0];

        $sql = "DELETE FROM ".DB_PREFIX.'session_user WHERE session_id IN('.$session_id.') AND uid = '.$user['user_id'].' AND utype = \''.$user['user_type'].'\'';
        $this->db->query($sql);

        //删除用户关注的群组
        $sql = "DELETE FROM ".DB_PREFIX.'member_group WHERE group_id IN('.$session_id.') AND member_id='.$user['user_id'];
        $this->db->query($sql);

        //查询群里是否有人 没有就删除群 只针对叮当讨论组
        $session_info = $this->mode->session_info($session_id);
        if($session_info['type'] == 2)
        {
            $info = $this->mode->session_users($session_id);
            if(empty($info))
            {
                //删除融云服务的群组
                $this->dismiss_rc_group($session_id,$user['user_id']);

                //删除群组主纪录
                $sql = "DELETE FROM ".DB_PREFIX.'session WHERE id IN('.$session_id.') ';
                $this->db->query($sql);

                //删除所有用户关注的群组
                $sql = "DELETE FROM ".DB_PREFIX.'member_group WHERE group_id IN('.$session_id.') ';
                $this->db->query($sql);

            }
        }

        $this->addItem('success');
        $this->output();	
    }

    /**
     * 解散群组 方法  将该群解散，所有用户都无法再接收该群的消息。
     * @param $userId           操作解散群的用户 Id。（必传）
     * @param $groupId          要解散的群 Id。（必传）
     * @return mixed
     */
    private function dismiss_rc_group($groupId)
    {
        $func = $this->settings['rc_func']['dismiss'];
        if(!$groupId)
        {
            $this->errorOutput(NO_GROUP_ID);
        }
        $groupInfo = $this->mode->session_info($groupId);
        $appid = $groupInfo['appid'];
        if(empty($groupInfo))
        {
            $this->errorOutput(NO_GROUP_INFO);
        }
        $userId = $groupInfo['create_uid'];

        if(!$userId)
        {
            $this->errorOutput(NO_USERID);
        }
        if(!$appid)
        {
            $this->errorOutput(NO_APP_ID);
        }

        //查询是否有appSecret
        $RCinfo = $this->getRCinfo($appid);
        $data = array(
            'userId'  => $userId,
            'groupId' => $groupId,
        );
        $server = new ServerAPI( $RCinfo['production_app_key'],$RCinfo['production_app_secret'],$func,$data);
        $res = $server->request();

        $result = json_decode($res,1);
        if($result['code'] == 200)
        {
            return $result;
        }

    }
    
    /**
     * 移除会话
     * 从用户消息列表中暂时移除此会话，并没有实际删除此会话 当此会话里有新的消息时还会显示出来
     */
    public function remove_session() {
    	$session_id = $this->input['session_id'];
    	if(!$session_id) {
    		$this->errorOutput(NO_SESSSION_ID);
    	}
    	if(!$this->user['user_id']) {
    		$this->errorOutput(NOT_LOGIN);
    	}
    	
		$user = $this->mode->get_member_info($this->user['user_name'], $this->user['user_id'], $this->user['user_type']);
		$user = $user[0];
		
		$sql = 'UPDATE '.DB_PREFIX.'session_user SET del_status = 1
				WHERE session_id IN('.$session_id.') AND uid = '. $user['user_id'] . ' AND utype =\''.$user['user_type'].'\'';  
		$this->db->query($sql);
		
		$this->addItem('success');
		$this->output();		  	
    } 
    
    /**
     * 删除群组
     */
    public function session_delete()
    {
        $session_id = $this->input['session_id'];
        if(!$session_id) {
            $this->errorOutput(NO_SESSSION_ID);
        }
        $sql = "DELETE ".DB_PREFIX."session,".DB_PREFIX."session_user WHERE 1 ".DB_PREFIX."session.id=".DB_PREFIX."session_user.session_id AND ".DB_PREFIX."session.id=".$session_id."";
        $this->db->query($sql);
        
        $this->addItem('success');
        $this->output();
    }
    
    /**
     * 屏蔽、取消屏蔽会话消息
     * 
     * 此会话有新消息时不在提示  屏蔽期间消息会接受到
     */
    public function block_session() {
    	$session_id = $this->input['session_id'];
    	if(!$session_id) {
    		$this->errorOutput(NO_SESSSION_ID);
    	}
    	if(!$this->user['user_id']) {
    		$this->errorOutput(NOT_LOGIN);
    	}
    	$block = $this->input['block'];  //block等于1是屏蔽 不等于取消屏蔽
    	$status = ($block == 1) ? 1 : 0;    
    	
		$user = $this->mode->get_member_info($this->user['user_name'], $this->user['user_id'], $this->user['user_type']);
		$user = $user[0];
		    	
		$sql = 'UPDATE '.DB_PREFIX.'session_user SET block_status = '. $status .'
				WHERE session_id IN('.$session_id.') AND uid = '. $user['user_id'] . ' AND utype =\''.$user['user_type'].'\'';  
		$this->db->query($sql); 	
		$this->addItem('success');
		$this->output();	
    }


    /**
     * 查看会话里消息列表
     * 直播互动 后台使用
     */
    public function session_messages()
    {
        $session_id = intval($this->input['session_id']);
        if (!$session_id) {
            $this->errorOutput(NO_SESSION_ID);
        }
        if(!$this->user['user_id']) {
            $this->errorOutput(NOT_LOGIN);
        }

        $condition = $this->get_condition();
        $condition .= ' AND session_id = ' . $session_id;
        if ($this->input['is_presenter'])
        {
            $condition .= ' AND status = 1';
            if (isset($this->input['is_recommend']) && $this->input['is_recommend'] == 0)   //等于0时获取全部审核消息
            {

            }
            else
            {
                $condition .= ' AND is_recommend = 1';
            }
        }
        $condition .= ' ORDER BY id DESC';
        $offset = $this->input['page'] ? $this->input['page_num'] * ($this->input['page'] -1) : 0;
        $count = $this->input['page_num'] ? intval($this->input['page_num']) : 20;
        $data_limit = ' LIMIT ' . $offset . ', ' . $count;
        $sql = 'SELECT * FROM '.DB_PREFIX.'message WHERE 1 ' . $condition . $data_limit;
        $q = $this->db->query($sql);
        $messages = $messages_id = array();
        while($row = $this->db->fetch_array($q)) {
            if (isset($row['send_time']))
            {
                $row['send_time_show'] = date('Y-m-d H:i:s', $row['send_time']);
            }
            isset($row['status']) && $row['status_text'] = $this->settings['status_show'][$row['status']];
            $row['send_uavatar'] = $row['send_uavatar'] ? unserialize($row['send_uavatar']) : array();
            $row['imgs'] = $row['imgs'] ? unserialize($row['imgs']) : array();
            $row['videos'] = $row['videos'] ? unserialize($row['videos']) : array();
            $row['audios'] = $row['audios'] ? unserialize($row['audios']) : array();
            $messages[] = $row;
            $messages_id[] = $row['id'];
        }
        if ($messages && $messages_id)
        {
            $sql = "SELECT * FROM " .DB_PREFIX."comment WHERE 1 AND message_id IN(".implode(',', $messages_id).")";
            $q = $this->db->query($sql);
            $comments = array();
            while (($row = $this->db->fetch_array($q)) != false)
            {
                $comments[$row['message_id']][] = $row;
            }
            foreach ((array) $messages as $key => $val)
            {
                $messages[$key]['comments'] = $comments[$val['id']] ? $comments[$val['id']] : array();
            }
        }

        if ($offset === 0)
        {
            //更新用户的最近阅读时间
            $utype = $this->user['user_type'];
            $sql = 'UPDATE '.DB_PREFIX.'session_user SET unread_counts = 0 AND last_read_time = '.TIMENOW.'' .
                ' WHERE session_id = ' . $session_id . ' AND uid = ' . $this->user['user_id']  .' AND utype = \''.$utype.'\'';
            $this->db->query($sql);
        }
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'message WHERE 1 ' . $condition;
        $total = $this->db->query_first($sql);
        $page['page_num'] = $count;
        $page['total_num'] = $total['total'];
        $page['total_page'] = ceil($total['total']/$count);
        $page['current_page'] = floor($offset/$count) + 1;
        $this->addItem(array('info' => $messages, 'page_info' => $page));
        $this->output();
    }

    /**
     * 获取从上次刷新后未读消息数
     *
     * @param session_id   会话id
     * @param refresh_time  上次刷新时间
     */
    public function get_unread_num()
    {
        if (!$this->input['session_id'])
        {
            $this->errorOutput('NO SESSION_ID');
        }
        if (!$this->input['refresh_time'])
        {
            //$this->errorOutput('No refresh_time');
        }
        $session_id = intval($this->input['session_id']);
        $refresh_time = $this->input['refresh_time'] ? intval($this->input['refresh_time']) : TIMENOW;
        $condition = '';
        $condition .= ' AND session_id = ' . $session_id;

        if ($this->input['is_presenter'])
        {
            //主持人页根据审核时间和推荐时间读取消息未读数
            $condition .= ' AND status = 1';
            if (isset($this->input['is_recommend']) && $this->input['is_recommend'] == 0)
            {
                //等于0时获取全部审核消息
                $condition .= ' AND status_time > ' . $refresh_time;
            }
            else
            {
                $condition .= ' AND is_recommend = 1';

                $condition .= ' AND (recommond_time > ' . $refresh_time . ' OR status_time > ' . $refresh_time .')';
            }
        }
        else
        {
            //导播页根据消息发送时间读取消息未读数
            $condition .= ' AND send_time > ' . $refresh_time;
        }
        $sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."message WHERE 1 " . $condition;
        $total = $this->db->query_first($sql);
        $ret = array(
            'total' => $total['total'],
        );
        $this->addItem($ret);
        $this->output();
    }

    /**
     * 取会话 新消息接口
     */
    public function get_new_message() {
    	$session_id = intval($this->input['session_id']);
    	if(!$session_id) {
    		$this->errorOutput(NO_SESSSION_ID);
    	}
    	if(!$this->user['user_id']) {
    		$this->errorOutput(NOT_LOGIN);
    	}

        $utype = $this->user['user_type'];
    	$sql = 'SELECT s.settings, su.join_time, su.last_read_time FROM '.DB_PREFIX.'session s ' .
    			'LEFT JOIN '.DB_PREFIX.'session_user su' .
    				' ON s.id = su.session_id ' .
    			'WHERE s.id = ' . $session_id .' AND su.uid ='.$this->user['user_id'] . ' AND utype = \''.$utype.'\'';
    	$session = $this->db->query_first($sql);
    	
    	$session['settings'] = $session['settings'] ? unserialize($session['settings']) : array();
    	
    	$condition = '';
    	$condition .= ' AND session_id = ' . $session_id;
    	$condition .= ' AND send_time >=' . $session['last_read_time'];
    	if(!$session['settings']['access_ago_message']) {
    		$condition .= ' AND send_time >= ' . $session['join_time'];
    	}
    	$sql = 'SELECT * FROM '.DB_PREFIX.'message WHERE 1 ' . $condition;	
    	$q = $this->db->query($sql);
    	$ret = array();
    	while($row = $this->db->fetch_array($q)) {
    		$row['send_uavatar'] = $row['send_uavatar'] ? unserialize($row['send_uavatar']) : array();
            $row['imgs'] = $row['imgs'] ? unserialize($row['imgs']) : array();
            $row['videos'] = $row['videos'] ? unserialize($row['videos']) : array();
            $row['audios'] = $row['audios'] ? unserialize($row['audios']) : array();
    		$ret[] = $row;
    	}
    	
    	//更新用户的最近阅读时间
    	$sql = 'UPDATE '.DB_PREFIX.'session_user SET unread_counts = 0 AND last_read_time = '.TIMENOW.'' .
    			' WHERE session_id = ' . $session_id . ' AND uid = ' . $this->user['user_id']  .' AND utype = \''.$utype.'\'';
    	$this->db->query($sql);		
    	$this->addItem($ret);
    	$this->output();	
    }
    
    /**
     * 删除会话消息
     * Enter description here ...
     */
    public function delete_message()
    {
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
        $id = $this->input['id'];
		$sql =  "SELECT * FROM " . DB_PREFIX . "message WHERE id IN (" . $this->input['id'] . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while($row = $this->db->fetch_array($q))
		{
			$pre_data[] = $row;
		}
		$sql = "DELETE FROM " .DB_PREFIX. "message  WHERE  1  AND  id  in (".$this->input['id'].")";
		$this->db->query($sql);
		//记录日志
		//$this->addLogs('删除会话消息', $pre_data, '','删除会话消息' . $this->input['id']);
		//$this->addItem($pre_data);
        $this->addItem($id);
		$this->output();
	}
    
	/**
	 * 推荐会话消息
	 * Enter description here ...
	 */
	public function recommend()
	{
        $id = urldecode($this->input['id']);
        if(!$id)
        {
            $this->errorOutput("未传入ID");
        }
        $idArr = explode(',',$id);

        if(intval($this->input['op']) == 1) //推荐
        {
        		$sql = "UPDATE " .DB_PREFIX. "message SET is_recommend = 1, recommond_time = " .TIMENOW." WHERE id IN({$id})";
        		$this->db->query($sql);
            $return = array('is_recommend' => 1,'id'=> $idArr);
        }
        else if(intval($this->input['op']) == 0) //取消推荐
        {
        		$sql = "UPDATE " .DB_PREFIX. "message SET is_recommend = 0 WHERE id IN({$id})";
            $this->db->query($sql);
            $return = array('is_recommend' =>0,'id' => $idArr);
        }
        $this->addItem($return);
        $this->output();
    }
    
    /**
     * 给会话消息添加标签 (直播互动用) 
     * Enter description here ...
     */
    public function tag()
    {
    		$id = urldecode($this->input['id']); //消息id
        $tag_id = intval($this->input['tag_id']);
        if(!$tag_id)
        {
            //$this->errorOutput("未传入标签ID");
        }
    		if(!$id)
        {
            $this->errorOutput("没有消息ID");
        }
//        if(!$this->input['tag_name'])
//        {
//        		$this->errorOutput('未传入标签名');
//        }
        $idArr = explode(',',$id);
        $sql = "UPDATE " .DB_PREFIX. "message SET tag_id = " . $tag_id . " WHERE id IN({$id})";
        $this->db->query($sql);
        $return = array('id'=> $idArr);
        $this->addItem($return);
        $this->output();
    }


    /**
     * 添加消息批注
     */
    public function create_comment()
    {
        if (!$this->input['message_id'])
        {
            $this->errorOutput('No message_id');
        }
        if (!$this->input['comment'])
        {
            $this->errorOutput('No comment');
        }
        $data = array(
            'message_id' => $this->input['message_id'],
            'comment' => $this->input['comment'],
            'user_id'  => $this->user['user_id'],
            'user_name' => $this->user['user_name'],
            'org_id'    => $this->user['org_id'],
            'create_time' => TIMENOW,
            'update_time' => TIMENOW,
            'last_update_userid' => $this->user['user_id'],
            'last_update_username' => $this->user['user_name'],
        );

        $data['id'] = $insert_id = $this->db->insert_data($data, 'comment');
        $this->addItem($data);
        $this->output();
    }

    /**
     * 修改消息批注
     */
    public function update_comment()
    {
        if (!$this->input['id'])
        {
            $this->errorOutput('No id');
        }
        if (!$this->input['comment'])
        {
            $this->errorOutput('No comment');
        }
        $data = array(
            'comment' => $this->input['comment'],
        );
        if ($this->db->update_data($data, 'comment', ' id =' . intval($this->input['id'])))
        {
            $info = array(
                'last_update_userid' => $this->user['user_id'],
                'last_update_username' => $this->user['user_name'],
                'update_time' => TIMENOW,
            );
            $this->db->update_data($info, 'comment', ' id = ' . intval($this->input['id']));
        }
        $data['id'] = $this->input['id'];
        $this->addItem($data);
        $this->output();
    }

    public function delete_comment()
    {
        if (!$this->input['id'])
        {
            $this->errorOutput('No id');
        }
        $id = $this->input['id'];

        if (is_array($id)) {
            $id = implode(', ', $id);
        }

        $sql = "DELETE FROM ".DB_PREFIX."comment WHERE id IN(".$id.")";
        $this->db->query($sql);
        $this->addItem($id);
        $this->output();
    }
    
    //修改用户头像，替换所有用户所在的群组的头像
    public function updateUserSessionAvatar()
    {
        $uid = intval($this->input['uid']);
        
        if(!$uid)
        {
            $this->errorOutput(NO_USERID);
        }

        $avatar = $this->input['avatar'];//接收的是一个头像数组
        if(!$avatar)
        {
            $this->errorOutput(NO_USER_AVATAR);
        }

        $data = array(
                'uavatar' => html_entity_decode($avatar),
        );
        if($this->input['uname'])
        {
        	$data['uname'] = $this->input['uname'];
        }
        
        //更新用户头像
        $ret = $this->mode->updateSessionUser(" AND uid = '" .$uid. "' ",$data);

        if($ret && $avatar)
        {
            $this->updateToken($uid,$data['uavatar']);
        }

        if($ret)
        {
            $this->addItem(array('return' => 1));
            $this->output();
        }
        else 
        {
            $this->errorOutput(UPDATE_ERROR);
        }
    }


    private function updateToken($userId,$avatar)
    {
        $userName = $this->user['user_name'];
        $func = $this->settings['rc_func']['refresh'];
        $app_id = intval($this->input['app_id']);
        if(!$userId || !$userName || !$app_id)
        {
            return false;
        }
        $user_avatar = unserialize($avatar);
        $portraitUri = $user_avatar['host'].$user_avatar['dir'].$user_avatar['filepath'].$user_avatar['filename'];
        if(!$portraitUri)
        {
            return false;
        }
        //查询是否有appSecret
        $RCinfo = $this->getRCinfo($app_id);
        $data = array(
            'userId'      => $userId,
            'name'        =>$userName,
            'portraitUri' =>$portraitUri,
        );
        $server = new ServerAPI( $RCinfo['production_app_key'],$RCinfo['production_app_secret'],$func,$data);
        $res = $server->request();
        $result = (array)json_decode($res);
        if($result['code'] != 200)
        {
            return false;
        }

        return $result;
    }

    /**
     * 获取融云返回信息表
     *
     *2014年12月11日
     *return_type
     */
    private function getRCinfo($appid,$flag = false)
    {
        $res = $this->rcinfo->getInfoByAppid($appid);
        if(!$res && !$flag)
        {
            $this->errorOutput(NO_RC_INFO);
        }
        $info = array();
        $info = $res['rongcloud_return'];
        if($info)
        {
            foreach ($info['data'] as $k=>$v)
            {
                $result[$k] = $v;
            }
        }
        return $result;
    }


    public function unknow()
    {
		$this->errorOutput('方法不存在');    	
    }
    
}

$out = new messageApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
