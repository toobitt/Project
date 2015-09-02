<?php
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(ROOT_PATH.'lib/class/applant.class.php');
class message extends InitFrm {
    
	private $curl;
	private $applant;
	function __construct() {
        parent::__construct();
        $this->curl = new curl();
        $this->applant = new applant();
    }
    
    function __destruct() {
        parent::__destruct();
    }
    
    /**
     * 查询会话列表
     * 
     * @param string $con  查询条件
     * @param string $field 查询字段 默认返回所有字段
     * @param string $key   返回的数组key值
     * @return 
     */
    function session_list($con, $field = '*', $key = '') {
        $sql = 'SELECT ' . $field . ' FROM ' . DB_PREFIX . 'session WHERE ' . $con;
        $q = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q)) {
            $row['title'] = hg_clean_value($row['title']);
            $group_id = $row['id'];
            $sqls = 'SELECT COUNT(*) as total FROM ' . DB_PREFIX . 'session_user WHERE session_id='.$group_id.'';
            $count = $this->db->query($sqls);
            while ($r = $this->db->fetch_array($count))
            {
                $row['count'] = $r['total'];
            }
            isset($row['last_uavatar']) && ($row['last_uavatar'] = $row['last_uavatar'] ? unserialize($row['last_uavatar']) : array());
            if ($key) {
                $ret[$row[$key]] = $row;
            }
            else {
                $ret[] = $row;
            }
        }
        return $ret;
    }
    
    /**
     * 设置群组管理员
     */
    public function group_admin($data = array())
    {
        $is_admin = $data['is_admin'];
        $groupId = $data['groupId'];
        $userId = $data['userId'];
        $sql = 'UPDATE session_user SET is_admin='.is_admin.' WHERE session_id='.$groupId.' AND uid in('.$userId.')';
        $q = $this->db->query($sql);
        return true;
    }

    /**
     * 获取用户所在的用户群组
     * @param unknown $user_id
     * @return array
     */
    function userinfo($user_id)
    {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'session_user WHERE uid='.$user_id.'';
        $q = $this->db->query($sql);
        $res = array();
        while ($row = $this->db->fetch_array($q))
        {
            isset($row['uavatar']) && ($row['uavatar'] = $row['uavatar'] ? unserialize($row['uavatar']) : array());
            if ($key) {
                $ret[$row[$key]] = $row;
            }
            else {
                $ret[] = $row;
            }
        }
        return $ret;
    }
    
    
    /**
     * 会话信息
     * 
     * @param int $session_id  会话ID
     * @param string $field 需要查询的字段 
     * @return array  会话新
     */
    function session_info($session_id, $field = '*',$condition = '') {
        if (!$session_id) {
            return false;
        }
        $sql = 'SELECT ' . $field . ' FROM ' . DB_PREFIX .'session WHERE id = ' . $session_id . $condition;
        $info = $this->db->query_first($sql);
        $sqls = 'SELECT COUNT(*) as total FROM ' . DB_PREFIX . 'session_user WHERE session_id='.$session_id.'';
        $count = $this->db->query($sqls);
        while ($r = $this->db->fetch_array($count))
        {
        	if($info)
            {
                $info['count'] = $r['total'];
            }
        }
        if ($info) {
            $info['title'] = hg_back_value($info['title']);
            $info['settings'] = $info['settings'] ? unserialize($info['settings']) : array();
            if(isset($info['last_uavatar'])) {
            	$info['last_uavatar'] = $info['last_uavatar'] ? unserialize($info['last_uavatar']) : array();
            }
        }
        return $info;
    } 
    
    
    /**
     * 获取会话里的用户
     * 
     * @param string or array $session_id   会话id  可以是数组或逗号隔开的字符串
     * @return array  返回按会话分组的 用户数组
     */
    function session_users($session_id) {
        if (!is_array($session_id)) {
            $session_id = explode(',', $session_id);
        }
        $session_id = implode("','", $session_id);
        if(!$session_id) {
            return false;
        }
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'session_user 
                WHERE 1 AND session_id IN(\''.$session_id.'\') ORDER BY id ASC';
        $q = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q)) {
            $row['uname'] = hg_hide_mobile($row['uname']);
            $row['uavatar'] = $row['uavatar'] ? unserialize($row['uavatar']) : array();
            $ret[$row['session_id']][] = $row;
        }
        return $ret;
    }
    
    /**
     * 根据条件查询会话总数
     * 
     * @param string $con 查询条件
     * 
     */
    function session_count($con) {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'session WHERE 1 ' . $con;
        $total = $this->db->query_first($sql);
        return $total;
    }
    
    /**
     * 获取会话里的消息列表
     * 
     * @param string or array $session_id 会话id 可以是数组或逗号隔开的字符串
     * @return array  按会话分组  按消息时间排序的消息列表
     */
    function get_session_messages($session_id, $sort = 'DESC', $condition = '') {
        if (!is_array($session_id)) {
            $session_id = explode(',', $session_id);
        }
        $session_id = implode("','", $session_id);
        if(!$session_id) {
            return false;
        }
        if (!in_array($sort, array('DESC', 'ASC'))) {
            $sort = 'DESC';
        }
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'message 
                WHERE 1 AND session_id IN(\''.$session_id.'\') ' .$condition. ' ORDER BY send_time ' . $sort;
        $q = $this->db->query($sql);   
        $ret = array();
        while ($row = $this->db->fetch_array($q)) {
            $row['send_time_show'] = date('Y年m月d日 H:i:s', $row['send_time']);
            $row['send_uavatar'] = $row['send_uavatar'] ? unserialize($row['send_uavatar']) : array();
            $ret[$row['session_id']][] = $row;
        }
        return $ret;             
    }
    
    
    /**
     * 创建会话
     * 
     * @param 
     * @return 
     */
    function create_session($data, $user = array()) {
        if (empty($data)) {
            return false;
        }
        $session_id = $this->db->insert_data($data, 'session');
        
        if (!$session_id) {
            return false;
        }
                
        $this->ender_session($session_id, $user);

        return $session_id;
 
    }
    
    
    function update_session($data, $session_id) {
        if (empty($data) || !$session_id) {
            return false;
        }
        return $this->db->update_data($data,'session', ' id = ' . $session_id);
    }
    
    function ender_session($session_id, $users, $exists_users = array()) {
        foreach ((array)$users as $k => $v) {
            if (!empty($v)) {
                if(array_key_exists($v['user_id'], $exists_users)  && ($v['user_type'] == $exists_users[$v['user_id']]['utype']) ){
                    //更新device_token
                    if ($v['device_token'])
                    {
                        $condition = ' session_id = ' . $session_id .' AND uid = '.$v['user_id'].' AND utype = \''.$v['user_type'].'\'';
                        $this->db->update_data(array('device_token' => $v['device_token'], 'app_id' => $v['app_id']), 'session_user', $condition);
                    }
                    continue;
                }
                $user_info = array(
                    'session_id' => $session_id,
                    'uid'        => $v['user_id'],
                    'uname'      => $v['user_name'],
                    'uavatar'    => $v['user_avatar'] ? addslashes(serialize($v['user_avatar'])) : '',
                    'utype'      => $v['user_type'],
                    'join_time'  => TIMENOW,
                    'unread_counts' => 0,
                    'device_token'  => $v['device_token'],
                    'app_id'        => $v['app_id'],
                    'app_name'   => $v['app_name'], 
                );
                $this->db->insert_data($user_info, 'session_user');
            }  
        }
        return true;        
    }
    
    
    
    /**
     * 查询用户参与的会话
     */
    function get_user_sessions($uid, $limit = '', $key = '') {
        $sql = 'SELECT s.*,su.join_time,su.uavatar FROM '.DB_PREFIX.'session_user su
                LEFT JOIN '.DB_PREFIX.'session s
                    ON su.session_id = s.id
                WHERE 1 AND del_status = 0 AND uid = '. $uid . ' ORDER BY s.last_time DESC ' . $limit;
        $q = $this->db->query($sql);
        $sessions = array();
        while ( false !== ($row = $this->db->fetch_array($q)) ) {
            $row['last_uavatar'] = $row['last_uavatar'] ? unserialize($row['last_uavatar']) : array();
            $row['indexpic'] = $row['indexpic'] ? unserialize($row['indexpic']) : array('host' =>'','dir' => '','filepath'=> '','filename'=>'');
            $row['uavatar'] = $row['uavatar'] ? unserialize($row['uavatar']) : array('host' =>'','dir' => '','filepath'=> '','filename'=>'');
            $row['join_time'] = date('y-m-d H:i',$row['join_time']);
            if($row['id'])
            {
                if ($key) {
                    $sessions[$row[$key]] = $row;
                }
                else {
                    $sessions[] = $row;
                }
            }
        }
        return $sessions;
    } 

    function get_user_sessions_count($uid) {
        $sql = 'SELECT COUNT(*) as total FROM '.DB_PREFIX.'session_user WHERE uid = ' . $uid;
        $total = $this->db->query_first($sql);
        return $total;     
    }   
    
    
    /**
     * 查询用户的详细信息
     */
    public function get_member_info($user_name, $user_id, $user_type = '', $user_device_token = '', $user_appid = '') {
        if ($user_name && !is_array($user_name)) {
            $user_name = explode(',', $user_name);
        }
        if ($user_id && !is_array($user_id)) {
            $user_id = explode(',', $user_id);
        }
        if ($user_type && !is_array($user_type)) {
            $user_type = explode(',', $user_type);
        }
        if ($user_device_token && !is_array($user_device_token)) {
            $user_device_token = explode(',', $user_device_token);
        }
        if ($user_appid && !is_array($user_appid)) {
            $user_appid = explode(',', $user_appid);
        }

        if(empty($user_name) && empty($user_id)) {
        	return array();
        }
        
        if(!empty($user_id)) {
        	$arr = $user_id;
        }                    
        else {
        	$arr = $user_name;
        }
        $init_user = array();
        foreach((array)$arr as $k => $v) {
             $user_type[$k] = $user_type[$k] ? $user_type[$k] : 'm2o';  //默认为m2o会员
             switch ($user_type[$k]) {
                 case 'admin':
                    include_once (ROOT_PATH . 'lib/class/auth.class.php');
                    $this->auth = new Auth();
                    if($user_id[$k]) {
                    	$ret = $this->auth->getMemberById($user_id[$k]); 
                    }
                    else{
                    	$ret = $this->auth->getMemberByName($user_name[$k]); 
                    }
                    $ret = $ret[0]; 
                    $init_user[] = array('user_id'=> $ret['id'], 'user_name' => $ret['user_name'], 'user_type' => $user_type[$k], 'user_avatar' => $ret['avatar'], 'device_token' => $user_device_token[$k], 'app_id' => $user_appid[$k]);
                    break;
                 
//                 case 'sina':
//                    break;
//
//                 case 'qq':
//                    break;
                 
                 default: 
                    include_once (ROOT_PATH . 'lib/class/members.class.php');
                    $this->member = new members();
                    if ($user_id[$k]) {
                    	$key = $user_id[$k];
                    	$ret = $this->member->get_member_info($user_id[$k]);
                    }
                    else {
                    	$key = $user_name[$k];
                    	$ret = $this->member->get_member_info('', $user_name[$k], $user_type[$k]);
                    }
                    $ret = $ret[$key];
                    if (!$ret['member_id'])
                    {
                        $init_user[] = array();
                    }
                    else
                    {
                        $app_info = $this->applant->getAppinfo($ret['identifier']);
                        $app_name = $app_info && $app_info['name'] ? $app_info['name'] : '';
                        $init_user[] = array('user_id' => $ret['member_id'], 'user_name' => $ret['nick_name'], 'user_type' => $ret['type'], 'user_avatar' => $ret['avatar'], 'device_token' => $ret['last_login_udid'], 'app_id' => $ret['identifier'],'app_name' => $app_name);
                    }
                    break;
             }
        }       
        return $init_user;
    }  

    /**
     * 临时处理方案 不合理
     */
    public function check_same_session($send_user, $touser) {
        $session = $this->get_user_sessions($send_user['user_id'][0], '', 'id');
        $session_id = array_keys($session);
        
        $user_id = array_merge($send_user['user_id'], $touser['user_id']);
        $user_type = array_merge($send_user['user_type'], $touser['user_type']);
        
        //取出会话中所有的用户
        $usersofsession = $this->session_users($session_id);  
        
        
        foreach ((array) $usersofsession as $k => $v) {
            foreach ((array) $v as $kk => $vv) {
                $session_user_id[] = $vv['uid'];
                $session_user_type[] = $vv['utype'];
                
                if (!array_diff($user_id, $session_user_id) && !array_diff($user_type, $session_user_type)) {
                    return $k;
                }
            }
        }
        
        return false;
              
    }




    public function update_device_token($session_id, $user, $device_token = '')
    {
        if ($device_token)
        {

        }
    }


    public function upload_imgs(&$files)
    {
        include_once (ROOT_PATH . 'lib/class/material.class.php');
        $this->material = new material();
        $imgs = array();
        if ($files['imgs'])
        {
            foreach ((array)$files['imgs']['name'] as $k => $v)
            {
                if (!$files['imgs']['error'][$k])
                {
                    foreach($files['imgs'] AS $kk =>$vv)
                    {
                        $file['Filedata'][$kk] = $files['imgs'][$kk][$k];
                    }
                    $ret = $this->material->addMaterial($file);
                    if(!empty($ret) && is_array($ret))
                    {
                        $imgs[] = array(
                            'material_id' => $ret['id'],
                            'host'        => $ret['host'],
                            'dir'         => $ret['dir'],
                            'filepath'    => $ret['filepath'],
                            'filename'    => $ret['filename'],
                        );
                    }
                }
                else
                {
                    //return array('error' => 'IMG_UPLOAD_ERROR');
                }
            }
            unset($files['imgs']);
        }

        return $imgs;
    }

    public function upload_video(&$files, $title)
    {
        $videos = array();
        if ($files['videofile'])
        {
            $max_size = ini_get('upload_max_filesize');
            if($max_size)
            {
                if($files['videofile']['size'] > $max_size*1024*1024)
                {
                    return array('error' => '上传视频不能超过'.$max_size.'M');
                }
            }
            if (!$this->settings['App_livmedia'])
            {
                return array('error' => 'NO_APP_LIVMEDIA');
            }
            $curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
            $curl->setSubmitType('post');
            $curl->setReturnFormat('json');
            $curl->initPostData();
            $curl->addFile($files);
            $curl->addRequestData('title', $title);
            $curl->addRequestData('comment','');
            $curl->addRequestData('vod_leixing',2);
            $ret = $curl->request('create.php');
            isset($ret[0]) && $ret = $ret[0];
            if ($ret)
            {
                $ret['video_mp4'] = $ret['protocol'] . $ret['host']  . '/' . $ret['dir'] . $ret['file_name'] . '.' . $ret['type'];
                $ret['video_m3u8'] = $ret['protocol'] . $ret['host'] . '/' . $ret['dir'] . $ret['file_name'] . '.m3u8';
                $videos[] = $ret;
                $video_type = $_FILES['videofile']['type'];
                if(strstr($video_type, 'audio'))
                {
                    $videos['is_audio'] = 1;
                    $videos['upload_type'] = '音频';
                }
            }
            else
            {
                //return array('error' => 'VIDEO_UPLOAD_ERROR');
            }
            unset($files['videofile']);
        }
        return $videos;
    }


    public function upload_audio(&$file)
    {
        $audit = array();
        if ($file['audiofile'])
        {
            if (!$file['audiofile']['error'])
            {
//                //验证是否是音频
//                if(strpos($file['audiofile']['type'], 'audio') === FALSE)
//                {
//                    return array('error' => 'Not audit file');
//                }
                //验证文件格式
                if (isset($this->settings['allow_audio_type']))
                {
                    $allow_type = $this->settings['allow_audio_type'];
                }
                else
                {
                    $allow_type = array('mp3', 'aac');
                }
                $ext = explode('.', $file['audiofile']['name']);
                $ext = $ext[1];
                if ( !in_array($ext, $allow_type) )
                {
                    return array('error' => 'Not allow type');
                }


                //验证文件大小
                if (!defined(UPLOAD_FILE_LIMIT))
                {
                    define('UPLOAD_FILE_LIMIT', 5);
                }
                $max_size = UPLOAD_FILE_LIMIT * 1024 * 1024;
                if ($file['audiofile']['size'] > $max_size)
                {
                    return array('error' => 'max size ' . UPLOAD_FILE_LIMIT . 'M');
                }

                $file_name_ext = $ext;
                $ext == 'aac' && $file_name_ext = 'mp4';
                $info = array(
                    'host'      => AUDIO_DOMAIN,
                    'filepath'  => date('Y') . '/' . date('m') . '/' . date('d') . '/',
                    'filename'  => date('YmdHis') . hg_generate_user_salt(4) .  '.' . $file_name_ext,
                    'name'      => urldecode($file['audiofile']['name']),
                    'type'      => $ext,
                );
                $des_path = DATA_DIR . $info['filepath'];

                if ( !hg_mkdir($des_path) )
                {
                    return array('error' => 'Directory cannot write');
                }
                if (!move_uploaded_file($file["audiofile"]["tmp_name"], $des_path . $info['filename']))
                {
                    return array('error' => 'Directory cannot write');
                }
                $info['user_id'] = $this->user['user_id'];
                $info['user_name'] = $this->user['user_name'];
                $info['filesize'] = $file["audiofile"]["size"];
                $info['create_time'] = TIMENOW;
                $info['ip'] = hg_getip();
                $info['id'] = $this->db->insert_data($info, 'audios');
                $audit[] = array(
                    'id'        => $info['id'],
                    'host'      => $info['host'],
                    'filepath'  => $info['filepath'],
                    'filename'  => $info['filename'],
                    'type'      => $info['type'],
                    'audio_duration'  => $this->input['audio_duration'],
                );
            }
            else if ($file['audiofile']['error'] == 1)
            {
                return array('error' => 'max upload size in ini');
            }
            else
            {
                return array('error' => 'upload false');
            }
            unset($file['auditfile']);
        }
        return $audit;
    }


    public function push_notice($message,$users, $send_user)
    {
        if(!$message)
        {
            return ;
        }
        $device_token = $appid = array();
        if (is_array($users) && count($users) > 0)
        {
            foreach($users as $k => $v)
            {
                if ($v['device_token'] && $v['app_id'])
                {
                    if ( ( $v['user_id'] != $send_user[0]['user_id'] || $v['user_type'] != $send_user[0]['user_type'] ) && $v['user_type'] != 'admin')
                    {
                        $device_token[] = $v['device_token'];
                        $appid[] = $v['app_id'];
                    }
                }
            }
        }

        if (!empty($device_token) && !empty($this->settings['App_mobile']))
        {
            $device_token = implode(',', $device_token);
            $appid = implode(',', $appid);
            $curl = new curl($this->settings['App_mobile']['host'], $this->settings['App_mobile']['dir']);
            $curl->setSubmitType('post');
            $curl->setReturnFormat('json');
            $curl->initPostData();
            $curl->addRequestData('device_token',$device_token);
            $curl->addRequestData('app_id', $appid);
            $curl->addRequestData('send_time', TIMENOW);
            $curl->addRequestData('message', $message);
            $curl->request('add_message.php');
        }
        else
        {
            return false;
        }

        return true;
    }
    
    //更新用户信息
    public function updateSessionUser($cond = '',$data = array())
    {
        if(!$data || !$cond)
		{
			return FALSE;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "session_user SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE 1 " . $cond;
		$this->db->query($sql);
		return TRUE;
    }
}
