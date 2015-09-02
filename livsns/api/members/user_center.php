<?php
/**
 * HOGE DingDone user_center.php
 *
 * 个人中心
 *
 * @package DingDone
 * @author RDC3 - dxtan
 * @copyright Copyright (c) 2015, HOGE CO., LTD (http://hoge.cn/)
 * @since Version 0.0.1
 */
define('MOD_UNIQUEID','UserCenter');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member.class.php';
require_once CUR_CONF_PATH . 'lib/member_mycount_mode.php';
require_once CUR_CONF_PATH . 'lib/member_favorite_mode.php';
require_once CUR_CONF_PATH . 'lib/member_info.class.php';
require_once ROOT_PATH . 'lib/class/seekhelp.class.php';
class UserCenter extends outerReadBase{
    private $mycount;
    private $seekhelp;
    private $mMember;
    private $mfavorite;
    private $mMemberInfo;
    public function __construct()
    {
        parent::__construct();
        $this->mMember = new member();
        $this->mycount = new member_mycount_mode();
        $this->seekhelp = new seekhelp();
        $this->mfavorite = new member_favorite_mode();
        $this->mMemberInfo = new memberInfo();
    }
    
    public function __destruct()
    {
        parent::__destruct();
    }
    
    /**
     * 获取个人中心数据
     * @see outerReadBase::show()
     */
    public function show()
    {
        $memberId = intval($this->user['user_id']);
        if(!$memberId)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }

        $result = $this->init($memberId);

        if(is_array($result))
        {
            foreach ($result as $key=>$value)
            {
                $this->addItem_withkey($key, $value);
            }
        }
        $this->output();
    }
    
    private function init($uid)
    {
        $result = array();
        $result['userInfo'] = array();
        $result['countInfo'] = array();
        
        $userInfo = $this->getUserInfo($uid);
        $result['userInfo'] = $userInfo;
        $countInfo = $this->getCards($uid);
        $result['countInfo'] = $countInfo;
        
        return $result;
    } 

    private function getUserInfo($uid)
    {
        $app_id = intval($this->input['app_id']);
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }
        $field='m.member_id,m.guid,m.member_name,m.signature,m.im_token,m.email,m.mobile,m.type,m.type_name,m.gid,m.gradeid,m.groupexpiry,m.avatar,m.credits,m.status,m.isVerify,m.appid,m.appname,m.create_time,m.update_time,m.last_login_time,m.final_login_time,mb.nick_name,mb.background';
        $condition = " AND m.member_id = " . $uid;
        $leftjoin=' LEFT JOIN '.DB_PREFIX.'member_bind as mb ON mb.member_id = m.member_id';
        $member = $this->mMember->get_member_info($condition,$field,$leftjoin);
        $member = $member[0];
        $member['extension'] = array();

        //扩展信息
        $condition = " AND member_id = " . $uid;
        $member_info = $this->mMemberInfo->show($condition);
        if($app_id)
        {
            $extension = $this->mMemberInfo->extendDataProcessByApp($member_info,1,$app_id);
        }
        $member['extension'] = $extension;

        if (empty($member))
        {
            $this->errorOutput(NO_MEMBER);
        }
        
        return $member;
    }
    
    private function getCards($member_id)
    {
        $countInfo = array();
        $data = array();
        //获取我的＊数量
        $countInfo['notice'] = 0;
        $myCount = $this->getMycount($member_id);
        if($myCount && is_array($myCount) && $myCount['member_id'] == $member_id)
        {
            foreach ($myCount as $key=>$value)
            {
                $countInfo[$key] = $value;
            }
            
            $NoticeInfo = $this->getNotice($member_id);
            $myNotice = $NoticeInfo['relateme_num'];
            $countInfo['notice'] = $myNotice ? $myNotice : 0;
        }
        
        if(empty($myCount))
        {
            /**从数据库count**/
            $countInfo = $this->seekhelp->getMycount($member_id);
            $condition = " AND member_id=".$member_id."";
            $favorite_count = $this->mfavorite->count($condition);
            if ($countInfo && is_array($countInfo))
            {
                //新建到数据库
                $data = array(
                        'member_id' => $member_id,
                        'posts' => $countInfo['posts'],
                        'favorite' => $favorite_count['total'],
                        'comment' => $countInfo['comment'],
                        'praise' => $countInfo['praise'],
                );
                
                //$countInfo['notice'] = $favorite_count['notice'];
                $countInfo['favorite'] = $favorite_count['total'];
                $createData = $this->mycount->create($data);
        
            }
        }
        
        return $countInfo;
    }
    
    /**
     * 获取个人中心的我的消息 
     */
    private function getNotice($uid)
    {
        $info = array();
        $info = $this->seekhelp->getMemberInfo($uid);
        
        return $info;
    }
    
    /**
     * 获取个人中心我的（帖子 评论 赞 收藏）数量
     */
    private function getMycount($uid)
    {
        $userCount = array();
        $userCount = $this->mycount->detail($uid);
        
        return $userCount;
    }
    
    
    public function detail(){}
    public function count(){}
}
$out = new UserCenter();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>