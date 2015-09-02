<?php
define('MOD_UNIQUEID','member_friend');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_friend_mode.php');
require_once(CUR_CONF_PATH . 'lib/member.class.php');
class member_friend extends outerReadBase
{
	private $mode;
    private $mMember;
    private $Members;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new member_friend_mode();
        $this->mMember = new member();
        $this->Members = new members();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}


    /**
     * 获取单向 双向好友列表
     */
    public function getFriendlist()
    {
        if($this->input['member_id'])
        {
            $member_id = intval($this->input['member_id']);
        }
        elseif($this->user['user_id'])
        {
            $member_id = intval($this->user['user_id']);
        }
        if(!$member_id)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }

        $type = $this->input['type'];
        $info = array();
        $result = array();
        $member_ids = array();
        $member_info = array();
        if(!$this->input['type'])
        {
            $this->errorOutput(NO_FIREND_TYPE);
        }

        if($type == 'my_friend')
        {
            //双向我的好友
            $condition = ' AND (member_id='.$member_id.' AND relation_type=3) OR (friend_id='.$member_id.' AND relation_type=3)';
        }
        elseif($type == 'my_attention')
        {
            //单向双向我关注的
            $condition = ' AND (member_id='.$member_id.' AND relation_type<>2) OR (friend_id='.$member_id.' AND relation_type<>1)';
        }
        elseif($type == 'my_fans')
        {
            //单向双向关注我的
            $condition = ' AND (member_id='.$member_id.' AND relation_type<>1) OR (friend_id='.$member_id.' AND relation_type<>2)';
        }

        $orderby = '  ORDER BY create_time DESC,id DESC ';
        $start = $this->input['start'] ? $this->input['start'] : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit = ' LIMIT ' . $start . ' , ' . $count;
        $info = $this->mode->show($condition,$orderby,$limit);
        $total = $this->mode->count($condition);

        if(!empty($info))
        {
            foreach($info as $k=>$v)
            {
                if($v['member_id'] == $member_id)
                {
                    $member_id_arr[] = $v['friend_id'];
                }
                elseif($v['friend_id'] == $member_id)
                {
                    $member_id_arr[] = $v['member_id'];
                }
            }

            //获取用户信息
            $member_ids = implode(",",$member_id_arr);
            $condition = ' AND m.member_id IN ('.$member_ids.')';
            $leftjoin = ' LEFT JOIN ' . DB_PREFIX . 'member_bind mb ON m.member_id=mb.member_id AND m.type=mb.type ';
            $member_info = $this->mMember->get_member_info($condition,'*',$leftjoin,'',0);

            //加上与好友的实际关系
            foreach($info as $k=>$v)
            {
                foreach($member_info as $ko=>$vo)
                {

                    if($v['member_id'] == $vo['member_id'] || $v['friend_id'] == $vo['member_id'])
                    {
                        $member_info[$ko]['relation_type'] = $v['relation_type'];
                        if($v['member_id'] == $vo['member_id'])
                        {
                            if($v['relation_type'] == 1)
                            {
                                $member_info[$ko]['is_followed'] = 0;
                                $member_info[$ko]['is_fans'] = 1;
                            }
                            elseif($v['relation_type'] == 2)
                            {
                                $member_info[$ko]['is_followed'] = 1;
                                $member_info[$ko]['is_fans'] = 0;
                            }
                            elseif($v['relation_type'] == 3)
                            {
                                $member_info[$ko]['is_followed'] = 1;
                                $member_info[$ko]['is_fans'] = 1;
                            }

                            $member_info[$ko]['remark'] = $v['friend_remark'];

                        }
                        elseif($v['friend_id'] == $vo['member_id'])
                        {

                            if($v['relation_type'] == 1)
                            {
                                $member_info[$ko]['is_followed'] = 1;
                                $member_info[$ko]['is_fans'] = 0;
                            }
                            elseif($v['relation_type'] == 2)
                            {
                                $member_info[$ko]['is_followed'] = 0;
                                $member_info[$ko]['is_fans'] = 1;
                            }
                            elseif($v['relation_type'] == 3)
                            {
                                $member_info[$ko]['is_followed'] = 1;
                                $member_info[$ko]['is_fans'] = 1;
                            }

                            $member_info[$ko]['remark'] = $v['member_remark'];
                        }


                    }
                }
            }
        }

        $info = array(
            'total' => $total['total'],
            'info'  => $member_info,
        );

        $this->addItem($info);
        $this->output();
    }

    /**
     * 获取两个人 好友关系
     * @param member_id 自己的id
     * @param friend_id 对方的id
     */
    public function getFriendship()
    {
        if($this->input['member_id'])
        {
            $member_id = intval($this->input['member_id']);
        }
        elseif($this->user['user_id'])
        {
            $member_id = intval($this->user['user_id']);
        }
        if(!$member_id)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }

        $friend_id = intval($this->input['friend_id']);
        $data = array();
        $member_remark = '';
        $friend_remark = '';
        $is_followed = 0;
        $is_friend = 0;
        $relation_type = 0;
        $is_fans = 0;
        if(!$this->input['friend_id'])
        {
            $this->errorOutput(NO_FRIEND_ID);
        }

        //始终把小的id 排在前面
        if($member_id < $friend_id)
        {
            $data['member_id'] = $member_id;
            $data['friend_id'] = $friend_id;
        }
        elseif($member_id == $friend_id)
        {
            return false;
        }
        else
        {
            $data['member_id'] = $friend_id;
            $data['friend_id'] = $member_id;
        }

        $condition = ' AND member_id='.$data['member_id'].' AND friend_id='.$data['friend_id'];
        $info = $this->mode->show($condition);

        if($info[0])
        {
            $result =  $info[0];
            $relation_type = intval($result['relation_type']);
            //解释关系
            if($result['member_id'] == $member_id)
            {
                if($result['relation_type'] == 1)
                {
                    $is_followed = 1;
                }
                elseif($result['relation_type'] == 3)
                {
                    $is_followed = 1;
                    $is_friend = 1;
                    $is_fans = 1;
                }
                else
                {
                    $is_followed = 0;
                    $is_fans = 1;
                }
                $member_remark = $result['member_remark'];
                $friend_remark = $result['friend_remark'];
            }
            elseif($result['friend_id'] == $member_id)
            {
                if($result['relation_type'] == 2)
                {
                    $is_followed = 1;
                }
                elseif($result['relation_type'] == 3)
                {
                    $is_followed = 1;
                    $is_friend = 1;
                    $is_fans = 1;
                }
                else
                {
                    $is_followed = 0;
                    $is_fans = 1;
                }
                $member_remark = $result['friend_remark'];
                $friend_remark = $result['member_remark'];
            }
        }
        else
        {
            $is_followed = 0;
        }

        //查询是否是黑名单
        $black_info = $this->Members->check_friend_blacklist($member_id,$friend_id);

        $res = array(
            'relation_type' => $relation_type,
            'is_followed' => $is_followed,
            'is_fans' => $is_fans,
            'is_friend' => $is_friend,
            'is_black'  => $black_info ? 1 : 0,
            'member_remark'    => is_null($member_remark) ? '' : $member_remark,
            'friend_remark' => is_null($friend_remark) ? '' : $friend_remark,
        );

        $this->addItem($res);
        $this->output();
    }

    /**
     * 获取关注数量 粉丝数量
     */
    public function getFriendCount()
    {
        if($this->input['member_id'])
        {
            $member_id = intval($this->input['member_id']);
        }
        elseif($this->user['user_id'])
        {
            $member_id = intval($this->user['user_id']);
        }
        if(!$member_id)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }

        //获取我的关注的数量
        $condition = " AND (member_id=".$member_id." AND relation_type<>2) or (friend_id=".$member_id." AND relation_type<>1)";
        $followed_num = $this->mode->count($condition);

        //获取粉丝数
        $condition = " AND (member_id=".$member_id." AND relation_type<>1) or (friend_id=".$member_id." AND relation_type<>2)";
        $fans_num = $this->mode->count($condition);

        $info = array(
            'followed_num' => intval($followed_num['total']),
            'fans_num' => intval($fans_num['total']),
        );

        $this->addItem($info);
        $this->output();
    }

	public function index(){}

	public function show()
	{
	}

	public function count()
	{
	}
	
	public function get_condition()
	{
	}
	
	public function detail()
	{
	}
}

$out = new member_friend();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>