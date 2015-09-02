<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - dxtan
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014年12月11日
 * @encoding    UTF-8
 * @description rongcloud_update.php
 **************************************************************************/
require 'global.php';
define('MOD_UNIQUEID', 'rongcloudApi');
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(ROOT_PATH.'lib/class/applant.class.php');
require_once(ROOT_PATH.'lib/class/members.class.php');
include_once(CUR_CONF_PATH . 'lib/rongcloud_mode.php');
include_once(CUR_CONF_PATH . 'lib/rongcloud_info_mode.php');
include_once(CUR_CONF_PATH . 'lib/rongcloud_blacklist_mode.php');
require_once(CUR_CONF_PATH.'lib/group_mode.php');
class rongcloudApi extends outerReadBase 
{
    private $url = '';
    private $curl = NULL;
    private $blacklist;
    private $applant;
    private $member;
    private $group;
    public function __construct()
    {
        parent::__construct();
        $this->curl = new curl();
        $this->applant = new applant();
        $this->rc = new rongcloud_mode();
        $this->rcinfo = new rongcloud_info_mode();
        $this->blacklist = new rongcloud_blacklist_mode();
        $this->members = new members();
        $this->group = new group_mode();
    }

    public function __destruct()
    {
        parent::__destruct();
        if ($this->curl !== NULL)
        {
            $this->curl = NULL;
        }
    }
    
     /**
     * 创建群组，并将用户加入该群组，用户将可以收到该群的消息。注：其实本方法是加入群组方法 /group/join 的别名。
     * @param $userId       要加入群的用户 Id。（必传）
     * @param $groupId      要加入的群 Id。（必传）
     * @param $groupName    要加入的群 Id 对应的名称。（可选）
     * @return json|xml
     */
    public function create()
    {
        //接收参数
        $app_id = intval($this->input['app_id']);
        $func = $this->settings['rc_func']['create'];
        $groupName = $this->input['groupName'];
        $groupId = intval($this->input['groupId']);
        $userId = $this->input['userId'] ? $this->input['userId'] : $this->user['user_id'];
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }
        if(!$groupId)
        {
            $this->errorOutput(NO_GROUP_ID);
        }
        if(!$userId)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        $brief = intval($this->input['brief']);
        //查询是否有appSecret
        $RCinfo = $this->getRCinfo($app_id,true);
        $appInfo = $this->applant->getAppinfo($app_id);
        if(!$RCinfo['production_app_key'] && !$RCinfo['production_app_secret'])
        {
            $timestamp = time(); //获取时间戳
            srand((double)microtime()*1000000);
            $nonce = rand();//获取一个随机数
            $appkey = APP_KEY;//系统分配的密码
            $appSecret = APP_SECRET;
            $signature = sha1($appSecret.$nonce.$timestamp);
            
            
            //新申请appkey appsecret
            $condition = array(
                    'name' =>$appInfo['name'].'_'.$appInfo['id'].'_'.RC_APP_VERSION,
                    'category' =>CATEGORY,
                    'description'=>$brief,
                    'userId' =>RC_USERID,
            );
            $httpHeader = array(
                    'App-Key:'.$appkey,
                    'Nonce:'.$nonce,
                    'Timestamp:'.$timestamp,
                    'Signature:'.$signature,
            );
            $signature_info = $this->curlRC(RC_APPLY_URL,$condition,$httpHeader);
            $signature = (array)json_decode($signature_info,1);
            if($signature['code'] == '3004')
            {
                //应用名称重复
                $condition = array(
                    'appName' => $appInfo['name'].'_'.$app_id.'_'.RC_APP_VERSION,
                    'userId' =>RC_USERID,
                );
                $signature_info = $this->curlRC(RC_GET_APPINFO_URL,$condition,$httpHeader);
                $signature = (array)json_decode($signature_info,1);
            }

            if($signature['code'] != '2000')
            {
                 $this->errorOutput(REQUEST_IM_FAIL);
                 //$signature_info = $this->curlURL(RC_DELETE_URL,$condition,$httpHeader);
            }
            //申请到的appkey appsecret插入到appinfo
            $update_info = $this->rcinfo->create(array('appid' => $app_id,'app_name' => $appInfo['name'],'rongcloud_return' => addslashes($signature_info)));
            if(!$update_info)
            {
                $this->errorOutput(UPDATE_APP_FAIL);
            }
        }
        
        //创建群组
        $RCinfo_new = $this->getRCinfo($app_id,true);
        $appkey = $RCinfo_new['production_app_key'];
        $appSecret = $RCinfo_new['production_app_secret'];
        $data = array(
                'userId' => $userId,
                'groupId'=> $groupId,
                'groupName' => $groupName
        );
        $server = new ServerAPI( $appkey,$appSecret,$func,$data);
        $res = $server->request();
        foreach (json_decode($res) as $key => $v)
    	{
    	    $this->addItem_withkey($key,$v);
    	}
        $this->output();
    }
    
     /**
     * 将用户从群中移除，不再接收该群组的消息。
     * @param $userId       要退出群的用户 Id。（必传）
     * @param $groupId      要退出的群 Id。（必传）
     * @return mixed
     */
    public function quit()
    {
        $func = $this->settings['rc_func']['quit'];
        $userId = intval($this->input['userId']);
        $groupId = intval($this->input['groupId']);
        $appid = intval($this->input['app_id']);
        if(!$userId)
        {
            $this->errorOuput(NO_USERID);
        }
        //查询是否有appSecret
        $RCinfo = $this->getRCinfo($appid);
        $data = array(
                'userId' => $userId,
                'groupId'=> $groupId,
        );
        $server = new ServerAPI( $RCinfo['production_app_key'],$RCinfo['production_app_secret'],$func,$data);
        $res = $server->request();
        foreach (json_decode($res) as $key => $v)
        {
            $this->addItem_withkey($key,$v);
        }
        $this->output();
    }
    
    /**
     * 将用户加入指定群组，用户将可以收到该群的消息。
     * @param $userId           要加入群的用户 Id。（必传）
     * @param $groupId          要加入的群 Id。（必传）
     * @param $groupName        要加入的群 Id 对应的名称。（可选）
     * @return json|xml
     */
    public function join()
    {
        $func = $this->settings['rc_func']['join'];
        $userId = $this->input['userId'];
        $groupId = intval($this->input['groupId']);
        $groupName = trim($this->input['groupName']);
        $appid = intval($this->input['app_id']);
        if(!$appid)
        {
            $this->errorOutput(NO_APP_ID);
        }
        if(!$userId)
        {
            $this->errorOutput(NO_USERID);
        }
        //查询是否有appSecret
        $RCinfo = $this->getRCinfo($appid);
        $data = array(
                'userId'    => $userId,
                'groupId'   => $groupId,
                'groupName' => $groupName,
        );
        $server = new ServerAPI( $RCinfo['production_app_key'],$RCinfo['production_app_secret'],$func,$data);
        $res = $server->request();
        foreach (json_decode($res) as $key => $v)
        {
            $this->addItem_withkey($key,$v);
        }
        $this->output();
    }
    
    /**
     * 解散群组 方法  将该群解散，所有用户都无法再接收该群的消息。
     * @param $userId           操作解散群的用户 Id。（必传）
     * @param $groupId          要解散的群 Id。（必传）
     * @return mixed
     */
    public function dismiss()
    {
        $func = $this->settings['rc_func']['dismiss'];
        $groupId = intval($this->input['groupId']);
        $appid = intval($this->input['app_id']);
        $userId = intval($this->input['userId']);
        if(!$appid)
        {
            $this->errorOutput(NO_APP_ID);
        }
        if(!$userId)
        {
            $groupInfo = $this->group->detail($groupId);
            if(empty($groupInfo))
            {
                $this->errorOutput(NO_GROUP_INFO);
            }
            $userId = $groupInfo['create_uid'];
        }
        if(!$groupId)
        {
            $this->errorOutput(NO_GROUP_ID);
        }
        if(!$userId)
        {
            $this->errorOutput(NO_USERID);
        }
        //查询是否有appSecret
        $RCinfo = $this->getRCinfo($appid);
        $data = array(
                'userId'  => $userId,
                'groupId' => $groupId,
        );
        $server = new ServerAPI( $RCinfo['production_app_key'],$RCinfo['production_app_secret'],$func,$data);
        $res = $server->request();
        foreach (json_decode($res) as $key => $v)
        {
            $this->addItem_withkey($key,$v);
        }
        $this->output();
    }

    /**
     * 加入黑名单
     *
     * @param app_id           应用Id。（必传）
     * @param userId           用户Id。（必传）
     * @param blackUserId      黑名单用户Id。（必传）
     * @return mixed
     */
    public function addBlacklist()
    {
        $func = $this->settings['rc_func']['addBlacklist'];
        $appid = intval($this->input['app_id']);

        if($userId = intval($this->input['userId']))
        {
            $userId = intval($this->input['userId']);
        }
        else
        {
            $userId = intval($this->user['user_id']);
        }
        $blackUserId = intval($this->input['blackUserId']);
        if(!$appid)
        {
            $this->errorOutput(NO_APP_ID);
        }
        if(!$userId)
        {
            $this->errorOutput(NO_USERID);
        }
        //查询是否有appSecret
        $RCinfo = $this->getRCinfo($appid);
        $data = array(
            'userId'  => $userId,
            'blackUserId' => $blackUserId,
        );
        $server = new ServerAPI( $RCinfo['production_app_key'],$RCinfo['production_app_secret'],$func,$data);
        $res = $server->request();
        foreach (json_decode($res) as $key => $v)
        {
            $this->addItem_withkey($key,$v);
        }
        $this->output();
    }

    /**
     * 移除黑名单
     *
     * @param app_id           应用Id。（必传）
     * @param userId           用户Id。（必传）
     * @param blackUserId      黑名单用户Id。（必传）
     * @return mixed
     */
    public function removeBlacklist()
    {
        $func = $this->settings['rc_func']['removeBlacklist'];
        $appid = intval($this->input['app_id']);
        if($userId = intval($this->input['userId']))
        {
            $userId = intval($this->input['userId']);
        }
        else
        {
            $userId = intval($this->user['user_id']);
        }
        $blackUserId = intval($this->input['blackUserId']);
        if(!$appid)
        {
            $this->errorOutput(NO_APP_ID);
        }
        if(!$userId)
        {
            $this->errorOutput(NO_USERID);
        }
        //查询是否有appSecret
        $RCinfo = $this->getRCinfo($appid);
        $data = array(
            'userId'  => $userId,
            'blackUserId' => $blackUserId,
        );
        $server = new ServerAPI( $RCinfo['production_app_key'],$RCinfo['production_app_secret'],$func,$data);
        $res = $server->request();
        foreach (json_decode($res) as $key => $v)
        {
            $this->addItem_withkey($key,$v);
        }
        $this->output();
    }


    
    /**
     * 同步用户所属群组 
     * 
     *2014年12月11日
     *return_type
     */
    public function sync()
    {
        $func = $this->settings['rc_func']['sync'];
        $userId = intval($this->input['userId']);
        $groupId = intval($this->input['groupId']);
        $groupName = trim($this->input['groupName']);
        $appid = intval($this->input['app_id']);
        if(!$userId)
        {
            $this->errorOuput(NO_USERID);
        }
        if (!is_array($userId))
        {
            $userId = explode(',', $userId);
        }
        
        //查询是否有appSecret
        $RCinfo = $this->getRCinfo($appid);
        $info = array();
        foreach ($userId as $k=>$v)
        {
            $data = array(
                    'userId'  => $v,
                    'group['.$groupId.']' => $groupName,
            );
            $server = new ServerAPI( $RCinfo['production_app_key'],$RCinfo['production_app_secret'],$func,$data);
            $res = $server->request();
            $info[$v] = json_decode($res);
        }
        foreach ($info as $key => $v)
        {
            $this->addItem_withkey($key,$v);
        }
        $this->output();
    }
    
    /**
     * 申请应用密钥
     * 
     *2014年12月11日
     *return_type
     */
    public function apply_signature()
    {
        //叮当应用信息
        $app_id = intval($this->input['app_id']);
        $app_name = trim($this->input['app_name']);
        $brief = trim($this->input['brief']);
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }

        $timestamp = time(); //获取时间戳
        srand((double)microtime()*1000000);
        $nonce = rand();//获取一个随机数
        $appkey = APP_KEY;//系统分配的密码
        $appSecret = APP_SECRET;
        $signature = sha1($appSecret.$nonce.$timestamp);


        //新申请appkey appsecret
        $condition = array(
            'name' =>$app_name.'_'.$app_id.'_'.RC_APP_VERSION,
            'category' =>CATEGORY,
            'description'=>$brief,
            'userId' =>RC_USERID,
        );
        $httpHeader = array(
            'App-Key:'.$appkey,
            'Nonce:'.$nonce,
            'Timestamp:'.$timestamp,
            'Signature:'.$signature,
        );
        $signature_info = $this->curlRC(RC_APPLY_URL,$condition,$httpHeader);
        $signature = (array)json_decode($signature_info,1);

        if($signature['code'] == '3004')
        {
            //应用名称重复
            $condition = array(
                'appName' => $app_name.'_'.$app_id.'_'.RC_APP_VERSION,
                'userId' =>RC_USERID,
            );
            $signature_info = $this->curlRC(RC_GET_APPINFO_URL,$condition,$httpHeader);
            $signature = (array)json_decode($signature_info,1);
        }
        if($signature['code'] != '2000')
        {
            $this->errorOutput(NO_RC_INFO);
        }
        //申请到的appkey appsecret插入到appinfo
        $result = $this->rcinfo->create(array('appid' => $app_id,'app_name' => $app_name,'rongcloud_return' => addslashes($signature_info)));
        if(!$result)
        {
            $this->errorOutput(UPDATE_APP_FAIL);
        }

        $info = array(
            'app_id' =>  $app_id,
            'rc_key' =>  $signature['data']['production_app_key'],
            'rc_secret' => $signature['data']['production_app_secret']
        );
        $this->addItem($info);
        $this->output();
    }
    
    public function verify_signature()
    {
         
    }
    
     /**
     * 获取 Token 方法
     * @param $userId   用户 Id，最大长度 32 字节。是用户在 App 中的唯一标识码，必须保证在同一个 App 内不重复，重复的用户 Id 将被当作是同一用户。
     * @param $name     用户名称，最大长度 128 字节。用来在 Push 推送时，或者客户端没有提供用户信息时，显示用户的名称。
     * @param $portraitUri  用户头像 URI，最大长度 1024 字节。
     * @return json|xml
     */
    public function getToken()
    {
    	$userId = intval($this->input['userId']);
        $userName = trim($this->input['userName']);
        $func = $this->settings['rc_func']['getToken'];
        $portraitUri = $this->input['avatarUrl'];
        $appid = $this->input['app_id'];
        if(!$appid)
        {
            $this->errorOutput(NO_APP_ID);
        }
        if(!$userId)
        {
            $this->errorOuput(NO_USERID);
        }
        
       	//检查黑名单
        $blackInfo = $this->blacklist->check_blackByappId($appid);
        if($blackInfo && $blackInfo['deadline'] == -1)
        {
        	$this->addItem(array('is_black' => 1,'msg' => '您的应用是黑名单','data' => $blackInfo));
        	$this->output();
        }
        
        //查询会员缓存表是否有
        $memberInfo = $this->members->get_member_info($userId);
        $tokenInfo = $memberInfo[$userId]['im_token'];
        if(!$tokenInfo)
        {
        	//查询是否有appSecret
        	$RCinfo = $this->getRCinfo($appid);
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
        		$this->errorOutput(REQUEST_FAIL);
        	}
        	
        	$param = array(
        	        'im_token' => $result['token'],
        	);
        	$res = $this->members->update($userId,$param);
        }
        else 
        {
        	$result = array(
        			'code' => 200,
        			'userId' => $userId,
        			'token' => $tokenInfo
        	);
        }
        
        foreach ($result as $key => $v)
        {
        	$this->addItem_withkey($key,$v);
        }
        $this->output();
    }
    
    /**
     * 更新 token
     * @param unknown $userId
     * @param unknown $avatar
     * @return boolean|array
     */
    public function refresh()
    {
        $userId = intval($this->input['userId']);
        $userName = trim($this->input['userName']);
        $func = $this->settings['rc_func']['refresh'];
        $app_id = intval($this->input['app_id']);
        $portraitUri = $this->input['avatar'];
        if(!$userId || !$userName || !$app_id)
        {
            return false;
        }
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
         
        $info = array(
                'user_id' => $userId,
        );
        
        $this->addItem($info);
        $this->output();
    }

    /**
     * 根据应用ID获取融云的production_app_key
     *
     * @param   int $app_id
     *
     * @return  string
     */
    public function get_rckey()
    {
        $app_id = $this->input['app_id'];
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }
        $res = $this->rcinfo->getInfoByAppid($app_id);
        if(!$res)
        {
            $this->errorOutput(NO_RC_INFO);
        }
        $info = $res['rongcloud_return'];
        if ($info && isset($info['data']['production_app_key']))
        {
            $this->addItem_withkey('rcAppKey',$info['data']['production_app_key']);
            $this->output();
        }
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
    
    /**
     * @param $action
     * @param $params
     * @param $httpHeader
     * @return mixed
     */
    public  function curlRC($action,$params,$httpHeader) {
        //$action = self::SERVERAPIURL.$action.'.'.$this->format;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $action);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false); //处理http证书问题
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
        if (false === $ret) {
            $ret =  curl_errno($ch);
        }
        curl_close($ch);
        return $ret;
    }
    
    public function show(){}
    public function detail(){}
    public function count(){}
    public function unknow(){}
    
}

$out = new rongcloudApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
