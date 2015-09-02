<?php
require 'global.php';
define('MOD_UNIQUEID', 'message');
class messageUpdateApi extends adminUpdateBase {
    
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/message.class.php');
        $this->mode = new message();

        if ( isset($this->user['is_member']) )
        {
            $this->user['user_type'] = $this->user['is_member'] ? 'm2o' : 'admin';
        }
        else
        {
            $this->user['user_type'] = 'admin';  //默认用户类型后台管理员
        }
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function create() {} 
    public function update() {}
    public function delete() {}
    public function audit() {}
    public function sort() {}
    public function publish() {}
    
   /**
     * 发送消息
     */
    public function send_message() {
    		//权限
		$this->verify_content_prms(array('_action'=>'manage'));
        //发送人信息
        if(!$this->user['user_id']) {
            $this->errorOutput(NOT_LOGIN);
        }

        if (!$this->input['message']) {
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
            'send_uavatar'=> $send_user[0]['user_avatar'] ? addslashes(serialize($send_user[0]['user_avatar'])) : '',
            'message'     => $message,
            'send_time'   => TIMENOW,
            'ip'          => hg_getip(),
            'location'    => $this->input['location'],
            'longitude'   => $this->input['longitude'],
            'latitude'    => $this->input['latitude'],
            'status'      => intval($this->input['status']),
        );
        (!$message_info['location'] && $message_info['ip']) && $message_info['location'] = hg_getIpInfo($message_info['ip']);

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

        //推送消息
        $ret['settings'] = $ret['settings'] ? unserialize($ret['settings']) : '';
        if ($ret['settings']['push_notice'])
        {
            file_put_contents('./cache/111.txt', var_export($ret,1) . var_export($part_users,1) . var_export($send_user,1));
            $this->mode->push_notice($message_info['message'], $part_users, $send_user);
        }
        //推送消息

        $this->addItem($ret);
        $this->output();         
    } 
    
    
    /**
     * 创建聊天室接口
     */
    public function create_session() {
		//权限
		$this->verify_content_prms(array('_action'=>'manage'));
        if (!$this->input['title']) {
            $this->errorOutput(NO_NAME);
        }
        
        $data = array(
            'create_uid'    => $this->user['user_id'],
            'create_utype'  => $this->user['user_type'],  //默认创建者类型后台管理员
            'title'         => $this->input['title'],
            'create_time'   => TIMENOW,
            'last_time'     => TIMENOW,
            'settings'      => ($this->input['settings'] && is_array($this->input['settings'])) ? serialize($this->input['settings']) : '',
            'app_uniqueid'  => $this->input['app_uniqueid'] ? $this->input['app_uniqueid'] : APP_UNIQUEID,
        );
        
        if (!is_array($this->input['touser_name'])) {
            $this->input['touser_name'] = explode(',', $this->input['touser_name']);
        }
        
        if (!is_array($this->input['touser_id'])) {
            $this->input['touser_id'] = explode(',', $this->input['touser_id']);
        }  
        
        if (!is_array($this->input['touser_type'])) {
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

        $init_user = $this->mode->get_member_info($touser_name, $touser_id, $touser_type, $touser_device_token, $touser_appid);

        $data['id'] = $session_id = $this->mode->create_session($data, $init_user);         
        
        if (!$session_id) {
            $this->errorOutput(CREATE_FALSE);
        }
        else {
            //发送人信息
            $send_uid = $this->user['user_id'];
            $send_uname = $this->user['user_name'];
            $send_utype = $this->user['user_type'];
            $send_device_token = $this->input['user_device_token'];
            $send_appid = $this->input['user_appid'] ? $this->input['user_appid'] : $this->user['appid'];
            $send_user = $this->mode->get_member_info($send_uname, $send_uid, $send_utype, $send_device_token, $send_appid);
            $this->mode->ender_session($session_id, $send_user);
            
            $this->addItem($data);
            $this->output();
        }
    }


    /**
     * 更新会话
     */
    public function update_session() {
    		//权限
		$this->verify_content_prms(array('_action'=>'manage'));
        if (!$this->input['session_id']) {
            $this->errorOutput(NO_SESSION_ID);
        }
        if (!$this->input['title']) {
            $this->errorOutput(NO_NAME);
        }
        
        $data = array(
            'title'         => $this->input['title'],
            'settings'      => $this->input['settings'] ? addslashes(serialize($this->input['settings'])) : '',
        );
        
        $ret = $this->mode->update_session($data, intval($this->input['session_id']));
        
        $this->addItem($data);
        $this->output();
    }     
     


    /**
     * 回复会话消息
     * 
     * @param session_id  会话id
     * @param mesage      回复内容
     */
    public function reply_session() {
    		//权限
		$this->verify_content_prms(array('_action'=>'manage'));
        if (!$this->input['session_id']) {
            $this->errorOutput(NO_SESSIONID);
        }
        
        if (!$this->input['message']) {
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
        
//        //判断用户是否已经在会话中
//        $session_users = $this->mode->session_users($session_id);
//        $session_users = $session_users[$session_id];
//        $exists_users = array();
//        foreach ((array) $session_users as $k => $v) {
//            $exists_users[$v['uid']] = $v;
//        }
//        if ( !array_key_exists($send_uid, $exists_users) || ($send_utype != $exists_users[$send_uid]['utype']) ) {
//            $this->errorOutput('你不在此会话中');
//        }

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
            'message'     => $message,
            'send_time'   => TIMENOW,
            'ip'          => hg_getip(),
            'location'    => $this->input['location'],
            'longitude'   => $this->input['longitude'],
            'latitude'    => $this->input['latitude'],
            'status'      => intval($this->input['status']),
        );
        (!$message_info['location'] && $message_info['ip']) && $message_info['location'] = hg_getIpInfo($message_info['ip']);
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
//        $sql = "UPDATE ".DB_PREFIX.'session_user SET unread_counts = unread_counts + 1 WHERE session_id = ' . $session_id . ' AND uid != ' . $this->user['user_id'] . ' AND utype != \''.$this->user['user_type'].'\'';
        $sql = "UPDATE ".DB_PREFIX.'session_user SET unread_counts = unread_counts + 1 WHERE session_id = ' . $session_id . ' AND ( uid != ' . $this->user['user_id'] . ' OR utype != \''.$this->user['user_type'].'\')';
        $this->db->query($sql);
        //有新会话时把所有用户的删除状态改为0
        $sql = "UPDATE ".DB_PREFIX."session_user SET del_status = 0 WHERE session_id = " . $session_id;
        $this->db->query($sql);

        $message_info['send_uavatar'] = $message_info['send_uavatar'] ? unserialize(stripslashes($message_info['send_uavatar'])) : array();

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
    		//权限
		$this->verify_content_prms(array('_action'=>'manage'));
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
        
        if(!$this->user['user_id']) {
        	$this->errorOutput(NOT_LOGIN);
        }
        $user_id = $this->user['user_id'];
        $user_name = $this->user['user_name'];
        $user_type = $this->user['user_type'];
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

    public function delete_session() {
        $session_id = $this->input['id'];
        if (!$session_id) {
        	$this->errorOutput(NO_SESSIONID);
        }
        /**************删除权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'message WHERE session_id IN ('.$session_id.')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage'));
			}
		}
		/*********************************************/	
        $sql = 'DELETE s, su, m FROM '.DB_PREFIX.'session s
                LEFT JOIN ' .DB_PREFIX.'session_user su
                    ON s.id = su.session_id
                LEFT JOIN '.DB_PREFIX.'message m
                    ON s.id = m.session_id
                WHERE s.id IN('.$session_id.')';
        $this->db->query($sql);
        $this->addItem($session_id);
        $this->output();         
    }
    
    public function unknow() {
        $this->errorOutput('方法不存在');
    }
    
}

$obj = new messageUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($obj, $action)) {
    $action = 'unknow';
}
$obj->$action();