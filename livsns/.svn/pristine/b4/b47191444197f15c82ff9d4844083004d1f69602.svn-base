<?php
/**
 * HOGE DingDone seekhelp_member.php
 *
 * 个人主页会员表
 *
 * @package DingDone
 * @author RDC3 - dxtan
 * @copyright Copyright (c) 2015, HOGE CO., LTD (http://hoge.cn/)
 * @since Version 0.0.1
 */
require_once './global.php';
define('MOD_UNIQUEID','memberApi');//模块标识
require_once CUR_CONF_PATH.'lib/member_mode.php';
require_once(ROOT_PATH.'lib/class/members.class.php');
class memberApi extends outerReadBase
{
    private $member;
    private $members;
    public function __construct()
    {
        parent::__construct();
        $this->member = new member_mode();
        $this->members = new members();
    }
    
    public function __destruct()
    {
        parent::__destruct();
    }
    
    public function show()
    {
        
    }
    
    /**
     * 获取会员详情
     * @see outerReadBase::detail()
     */
    public function detail()
    {
        if($this->user['user_id'])
        {
            $memberId = $this->user['user_id'];
        }
        else
        {
            $memberId = $this->input['member_id'];
        }
        $info = array();
        if(!$memberId)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        $info = $this->member->detail($memberId);
        if($info && is_array($info))
        {
            foreach ($info as $k=>$v)
            {
                $this->addItem_withkey($k, $v);
            }
        }
        
        $this->output();
    }
    
    public function count(){}
}
$ouput = new memberApi();
if(!method_exists($ouput, $_INPUT['a']))
{
    $action = 'show';
}
else
{
    $action = $_INPUT['a'];
}
$ouput->$action();