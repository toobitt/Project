<?php
define('MOD_UNIQUEID','member_friend_blacklist');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_friend_blacklist_mode.php');
require_once(CUR_CONF_PATH . 'lib/member_friend_mode.php');
class member_friend_blacklist extends outerReadBase
{
	private $mode;
    private $Members;
    private $member_friend;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new member_friend_blacklist_mode();
        $this->Members = new members();
        $this->member_friend = new member_friend_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,'',$limit);

        if($ret)
        {
            foreach($ret as $k=>$v)
            {
                $friend_ids_arr[] =  $v['fb_uid'];
            }
            if($friend_ids_arr)
            {
                $friend_ids = implode(",",$friend_ids_arr);
                $condition = ' AND m.member_id IN('.$friend_ids.')';
                $field = 'm.member_id,m.member_name,m.avatar,mb.nick_name';
                $leftjoin = ' LEFT JOIN ' . DB_PREFIX . 'member_bind mb ON m.member_id=mb.member_id';
                $members_info = $this->Members->get_member_info($condition,$field,$leftjoin,'member_id',true);
            }

        }
		if(!empty($ret) && !empty($members_info))
		{
            $this->addItem($members_info);
			$this->output();
		}
	}

	/**
	 * Display the count resource.
	 *
	 * @param  condition
	 * @return Response
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * input your param.
	 *
	 * @param  param
	 * @return Response
	 */
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  name  LIKE "%'.trim(($this->input['k'])).'%"';
		}

        if($this->user['user_id'])
        {
            $condition .= " AND uid IN (".($this->user['user_id']).")";
        }
		
		return $condition;
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function detail()
	{
		if($this->input['id'])
		{
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}

	/**
	 * Create the resource.
	 *
	 * @param  condition
	 * @return Response
	 */
	public function create()
	{
        $uid = $this->user['user_id'];
        $fb_uid = $this->input['fb_uid'];
        if(!$uid)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        if(!$fb_uid)
        {
            $this->errorOutput(NO_FRIEND_ID);
        }
        $data = array(
            'uid' => $uid,
            'fb_uid' => $fb_uid,
		);
		if(!$this->check_friend_blacklist($uid,$fb_uid))
        {
            $ret = $this->mode->create($data);
        }
        else
        {
            $this->errorOutput(HAS_BLACK);
        }
		if($ret)
		{
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下

            //把此人从关注列表移除
            $this->remove_follow_list($uid,$fb_uid);

			$this->addItem('success');
			$this->output();
		}
	}
	

	/**
	 * Update the resource.
	 *
	 * @param  condition
	 * @return Response
	 */
	public function update()
	{}


	/**
	 * Delete the resource.
	 *
	 * @param  condition
	 * @return Response
	 */
	public function delete()
	{
        $uid = $this->user['user_id'];
        $fb_uid = $this->input['fb_uid'];
        if(!$uid)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        if(!$fb_uid)
        {
            $this->errorOutput(NO_FRIEND_ID);
        }
        if($this->check_friend_blacklist($uid,$fb_uid))
        {
            $ret = $this->mode->delete($uid, $fb_uid);
        }
        else
        {
            $this->errorOutput(NO_HAS_BLACK);
        }

		if($ret)
		{
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}

    /**
     * 检测好友黑名单
     */
    private function check_friend_blacklist($member_id,$friend_id)
    {
        if(!$member_id)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        if(!$friend_id)
        {
            $this->errorOutput(NO_FRIEND_ID);
        }
        $condition = ' AND uid='.$member_id.' AND fb_uid='.$friend_id.'';
        $info = $this->mode->show($condition);
        if($info)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 从关注列表移除
     */
    private function remove_follow_list($member_id,$friend_id)
    {
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
        $info = $this->member_friend->show($condition);
        if($info[0])
        {
            if($info[0]['relation_type'] == 1)
            {
                //已经单向 删除
                if($info[0]['member_id'] == $member_id)
                {
                    $ret = $this->member_friend->delete($data['member_id'],$data['friend_id']);
                    return $ret;
                }
                elseif($info[0]['friend_id'] == $member_id)
                {

                }
            }
            elseif($info[0]['relation_type'] == 2)
            {
                //A已经取消关注 再次关注
                if($info[0]['member_id'] == $member_id)
                {

                }
                elseif($info[0]['friend_id'] == $member_id)
                {
                    $ret = $this->member_friend->delete($data['member_id'],$data['friend_id']);
                    return $ret;
                }

            }
            elseif($info[0]['relation_type'] == 3)
            {
                //已经是双向好友 错误提示
                if($info[0]['member_id'] == $member_id)
                {
                    $relation_type = 2;
                }
                elseif($info[0]['friend_id'] == $member_id)
                {
                    $relation_type = 1;
                }
            }

        }

        if($info[0] && $relation_type)
        {
            //存在关系 更新
            $res = $this->member_friend->update($data['member_id'],$data['friend_id'],array('relation_type' => $relation_type));
            $vid = $res['id'];
        }

        return $vid;
    }
}

$out = new member_friend_blacklist();
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