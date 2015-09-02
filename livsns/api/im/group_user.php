<?php
define('MOD_UNIQUEID','group_user');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/group_user_mode.php');
require_once(ROOT_PATH . 'lib/class/members.class.php');
class group_user extends outerReadBase
{
	private $mode;
    private $members;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new group_user_mode();
        $this->members = new members();
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
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
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
		$data = array(
			/*
				code here;
				key => value
			*/
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
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
	{
		if(!$this->input['group_id'])
		{
			$this->errorOutput(NOID);
		}
        $group_id = $this->input['group_id'];

        if(!$this->user['user_id'])
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        $member_id = $this->user['user_id'];
        $update_data = array();
        if($this->input['uname'])
        {
            $update_data['uname'] = $this->input['uname'];
        }
        if($this->input['is_admin'])
        {
            $update_data['is_admin'] =$this->input['is_admin'];
        }

        $condition = ' AND session_id='.$group_id.' AND uid='.$member_id.'';
		$ret = $this->mode->update($condition,$update_data);
		if($ret)
		{
			//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
        else
        {
            $this->errorOutput(NO_GROUP_USER);
        }
	}


	/**
	 * Delete the resource.
	 *
	 * @param  condition
	 * @return Response
	 */
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

    /**
     * 拉入群成员
     */
    public function addGroupUser()
    {
        if(!$this->input['app_id'])
        {
            $this->errorOutput(NO_APP_ID);
        }
        if(!$this->input['group_id'])
        {
            $this->errorOutput(NO_GROUP_ID);
        }
        $group_id = $this->input['group_id'];
        $app_id = $this->input['app_id'];

        //创建群成员
        if($user_id = $this->input['user_id'])
        {
            $res = $this->createGroupUser($app_id, $group_id, 0, $user_id);
        }

        if($res)
        {
            $this->addItem('success');
            $this->output();
        }
    }

    /**
     * 拉入群成员
     */
    public function removeGroupUser()
    {
        if ($this->input['member_id'])
        {
            $member_id = $this->input['member_id'];
        }
        elseif($this->user['user_id'])
        {
            $member_id = $this->user['user_id'];
        }
        else
        {
            $this->errorOutput(NO_MEMBER_ID);
        }

        if(!$this->input['app_id'])
        {
            $this->errorOutput(NO_APP_ID);
        }
        if(!$this->input['group_id'])
        {
            $this->errorOutput(NO_GROUP_ID);
        }
        $group_id = $this->input['group_id'];
        $app_id = $this->input['app_id'];

        //验证权限  只有群主有权限
        $condition = ' AND session_id='.$group_id.'';
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 1;
        $orderby = '  ORDER BY id ASC ';
        $limit = ' LIMIT ' . $offset . ' , ' . $count;
        $group_user_info = $this->mode->show($condition,$orderby,$limit);
        if($group_user_info[0]['uid'] != $member_id)
        {
            $this->errorOutput(NO_PERMISSION);
        }

        //创建群成员
        if($user_id = $this->input['user_id'])
        {
            $data = array(
                'session_id' => $group_id,
                'app_id'    => $app_id,
            );
            $res = $this->mode->removeGroupUser($data, $user_id);
        }

        if($res)
        {
            $this->addItem('success');
            $this->output();
        }
    }

    /**
     * 创建群成员
     */
    private function createGroupUser($app_id, $group_id, $create_uid = 0,$user_id)
    {
        //user_id
        if(!$user_id)
        {
            return false;
        }

        $condition = ' AND uid IN ('.$user_id.') AND session_id='.$group_id.'';
        $info = $this->mode->show($condition);

        //获取会员的信息
        $memberInfo = $this->members->get_newUserInfo_by_ids($user_id);
        if(empty($memberInfo))
        {
            $this->errorOutput(NO_MEMBER_INFO);
        }

        //合成群成员数组
        if($memberInfo)
        {
            foreach($memberInfo as $k=>$v)
            {
                if($v['member_id'] == $create_uid)
                {
                    //加上管理员
                    $createData[] = array(
                        'session_id' => $group_id,
                        'uid'        => $v['member_id'],
                        'uname'      => $v['nick_name'],
                        'is_admin'   => 1,
                        'uavatar'    => $v['avatar'] ? serialize($v['avatar']) : '',
                        'utype'      => $v['type'],
                        'join_time'  => TIMENOW,
                        'app_id'     => $app_id,
                    );
                }
                else
                {
                    $createData[] = array(
                        'session_id' => $group_id,
                        'uid'        => $v['member_id'],
                        'uname'      => $v['nick_name'],
                        'is_admin'   => 0,
                        'uavatar'    => serialize($v['avatar']),
                        'utype'      => $v['type'],
                        'join_time'  => TIMENOW,
                        'app_id'     => $app_id,
                    );
                }
            }

            if($info)
            {
                foreach($info as $k=>$v)
                {
                    foreach($createData as $ko=>$vo)
                    {
                        if($v['uid'] == $vo['uid'])
                        {
                            unset($createData[$ko]);
                        }
                    }
                }
            }

        }

        if($user_id && empty($createData))
        {
            $this->errorOutput(HAS_CREATE);
        }


        //创建群成员
        $result = $this->mode->createUsers($createData);

        if($result)
        {
            return true;
        }
        else
        {
            $this->errorOutput(CREATE_GROUP_USER_FAIL);
        }
    }

}

$out = new group_user();
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