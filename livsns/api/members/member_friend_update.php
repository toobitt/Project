<?php
define('MOD_UNIQUEID','member_friend');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_friend_mode.php');
require_once(CUR_CONF_PATH . 'lib/member.class.php');
require_once(ROOT_PATH . 'lib/class/seekhelp.class.php');
class member_friend_update extends outerReadBase
{
	private $mode;
    private $mMember;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new member_friend_mode();
        $this->mMember = new member();
        $this->seekhelp = new seekhelp();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

    /**
     * 建立好友关系
     *
     * member_id friend_id 始终最小值排在前面
     * relation_type 1:A对B  2:B对A  3:AB相互
     */
	public function create()
	{
        if($this->user['user_id'])
        {
            $member_id = intval($this->user['user_id']);
        }
        else
        {
            $member_id = intval($this->input['member_id']);
        }
        if(!$member_id)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        $friend_id = intval($this->input['friend_id']);
        $app_id = intval($this->input['app_id']);
        $relation_type = 0;
        if(!$this->input['friend_id'])
        {
            $this->errorOutput(NO_FRIEND_ID);
        }
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }
        //检测好友是否存在
        $this->checkMember($friend_id,$app_id);


        //始终把小的id 排在前面
        if($member_id < $friend_id)
        {
            $data['member_id'] = $member_id;
            $data['friend_id'] = $friend_id;
        }
        else
        {
            $data['member_id'] = $friend_id;
            $data['friend_id'] = $member_id;
        }
        if($member_id == $friend_id)
        {
            $this->errorOutput(CANNOT_FOLLOED_SELF);
        }

        $condition = ' AND member_id='.$data['member_id'].' AND friend_id='.$data['friend_id'];
        $info = $this->mode->show($condition);
        if($info[0])
        {
            if($info[0]['relation_type'] == 1)
            {
                //已经单向 更新为双向
                if($info[0]['member_id'] == $member_id)
                {
                    $this->errorOutput(HAS_FRIENDSHIP);
                }
                $relation_type = 3;
            }
            elseif($info[0]['relation_type'] == 2)
            {
                //A已经取消关注 再次关注
                if($info[0]['friend_id'] == $member_id)
                {
                    $this->errorOutput(HAS_FRIENDSHIP);
                }
                $relation_type = 3;
            }
            elseif($info[0]['relation_type'] == 3)
            {
                //已经是双向好友 错误提示
                $this->errorOutput(HAS_FRIENDSHIP);
            }

        }
        else
        {
            if($member_id < $friend_id)
            {
                $relation_type = 1;
            }
            else
            {
                $relation_type = 2;
            }
        }

        if(empty($relation_type))
        {
            $this->errorOutput(NO_RELATION_TYPE);
        }

        $data['app_id'] = $app_id;
        $data['relation_type'] = $relation_type;
        $data['create_time'] = TIMENOW;

        if($info[0])
        {
            //存在关系 更新
            $res = $this->mode->update($data['member_id'],$data['friend_id'],array('relation_type' => $relation_type));
            $vid = $res['id'];
        }
        else
        {
            //没有记录 新建
            $vid = $this->mode->create($data);
        }


		if($vid)
		{
			$data['id'] = $vid;
            $data['relation_type'] = $relation_type;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
            //添加到我的消息
            $this->SetTimeline($member_id,$friend_id);

			$this->addItem($data);
			$this->output();
		}
	}


	public function cancel()
	{
        if($this->user['user_id'])
        {
            $member_id = intval($this->user['user_id']);
        }
        else
        {
            $member_id = intval($this->input['member_id']);
        }
        if(!$member_id)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        $friend_id = intval($this->input['friend_id']);
        $app_id = intval($this->input['app_id']);
        $relation_type = 0;
        if(!$this->input['friend_id'])
        {
            $this->errorOutput(NO_FRIEND_ID);
        }
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }
        //检测好友是否存在
        $this->checkMember($friend_id,$app_id);


        //始终把小的id 排在前面
        if($member_id < $friend_id)
        {
            $data['member_id'] = $member_id;
            $data['friend_id'] = $friend_id;
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
            if($info[0]['relation_type'] == 1)
            {
                //已经单向 删除记录
                if($info[0]['friend_id'] == $member_id)
                {
                    $this->errorOutput(NO_HAS_FRIENDSHIP);
                }
                $ret = $this->mode->delete($data['member_id'],$data['friend_id']);
                $this->addItem($ret[0]);
                $this->output();
            }
            elseif($info[0]['relation_type'] == 2)
            {
                //B已经关注A  A没有关注过B 报错
                if($info[0]['member_id'] == $member_id)
                {
                    $this->errorOutput(NO_HAS_FRIENDSHIP);
                }
                $ret = $this->mode->delete($data['member_id'],$data['friend_id']);
                $this->addItem($ret[0]);
                $this->output();
            }
            elseif($info[0]['relation_type'] == 3)
            {
                //是双向关系
                if($info[0]['member_id'] == $member_id)
                {
                    //取消A对B
                    $relation_type = 2;
                }
                elseif($info[0]['friend_id'] == $member_id)
                {
                    //取消B对A
                    $relation_type = 1;
                }
            }

        }
        else
        {
            $this->errorOutput(NO_FRIENDSHIP);
        }

        if(empty($relation_type))
        {
            $this->errorOutput(NO_RELATION_TYPE);
        }


		$ret = $this->mode->update($data['member_id'],$data['friend_id'],array('relation_type' => $relation_type));
		if($ret)
		{
			//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}

    /**
     * 设置好友备注
     */
    public function SetFriendRemark()
    {
        if($this->user['user_id'])
        {
            $member_id = intval($this->user['user_id']);
        }
        else
        {
            $member_id = intval($this->input['member_id']);
        }
        if(!$member_id)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        $friend_id = intval($this->input['friend_id']);
        $friend_remark = trim($this->input['friend_remark']);

        //始终把小的id 排在前面
        if($member_id < $friend_id)
        {
            $data['member_id'] = $member_id;
            $data['friend_id'] = $friend_id;
        }
        else
        {
            $data['member_id'] = $friend_id;
            $data['friend_id'] = $member_id;
        }

        $condition = ' AND member_id='.$data['member_id'].' AND friend_id='.$data['friend_id'];
        $info = $this->mode->show($condition);
        if(empty($info))
        {
            $this->errorOutput(NO_FRIENDSHIP);
        }
        if($data['member_id'] == $member_id)
        {
            $this->mode->update($data['member_id'],$data['friend_id'],array('member_remark' => $friend_remark));
        }
        elseif($data['friend_id'] == $member_id)
        {
            $this->mode->update($data['member_id'],$data['friend_id'],array('friend_remark' => $friend_remark));
        }


        $this->addItem('success');
        $this->output();
    }

	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}

    /**
     * 检测会员是否存在
     * @param $member_id
     * @param $identifier
     */
    private function checkMember($member_id,$identifier)
    {
        $condition  = " AND m.member_id=" . $member_id." AND identifier=".$identifier."";
        $ret_member = $this->mMember->get_member_info($condition,'m.*','',0);
        $ret_member = $ret_member[0];

        if(empty($ret_member))
        {
            $this->errorOutput(NO_FRIEND_MEMBER_INFO);
        }
    }

    /**
     * 叮当 添加到我的消息 系统消息
     */
    private function SetTimeline($member_id,$friend_id)
    {
        $to_member_id = $friend_id;
        if (!$to_member_id)
        {
            return false;
        }

        $res = $this->seekhelp->setTimeline(array(
            'type' => 'attention',
            'relation_id' => $member_id,
            'user_id' => $member_id,
            'user_name' => $this->user['user_name'] ? $this->user['user_name'] : '',
            'to_user_id' => $to_member_id,
            'create_time' => TIMENOW,
        ));

        return $res;
    }


	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}

    public function show()
    {
    }

    public function detail()
    {
    }

    public function count()
    {
    }
}

$out = new member_friend_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>