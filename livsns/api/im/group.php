<?php
define('MOD_UNIQUEID','group');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/group_mode.php');
require_once(CUR_CONF_PATH . 'lib/group_user_mode.php');
require_once(ROOT_PATH . 'lib/class/members.class.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
class group extends outerReadBase
{
	private $mode;
    private $members;
    private $group_user;
    private $material;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new group_mode();
        $this->members = new members();
        $this->group_user = new group_user_mode();
        $this->material = new material();
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
		$orderby = '  ORDER BY id DESC ';
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

        if($this->input['app_id'])
        {
            $condition .= " AND appid IN (".($this->input['app_id']).")";
        }

		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  name  LIKE "%'.trim(($this->input['k'])).'%"';
		}

        if($this->input['app_id'])
        {
            $condition .= " AND app_id IN (".($this->input['app_id']).")";
        }

        if($this->user['user_id'])
        {
            $condition .= " AND uid IN (".($this->user['user_id']).")";
        }


        if($this->input['type'])
        {
            $condition .= " AND type =".($this->input['type'])."";
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
		if(!$this->input['app_id'])
        {
            $this->errorOutput(NO_APP_ID);
        }
        $app_id = $this->input['app_id'];
        $create_uid     = $this->input['create_uid'];
        $create_type    = $this->input['create_utype'];
        $title          = $this->input['title'];
        $brief          = $this->input['brief'];
        $indexpic       = $this->input['indexpic'];
        $max_num        = $this->input['max_num'];
        $type           = $this->input['type'];
        $app_uniqueid   = $this->input['app_uniqueid'];
        $data = array(
			'appid'         => $app_id,
            'create_uid'    => $create_uid,
            'create_utype'  => $create_type,
            'title'         => $title,
            'brief'         => $brief,
            'indexpic'      => $indexpic,
            'create_time'   => TIMENOW,
            'max_num'       => $max_num,
            'type'          => $type ? $type : 1,
            'app_uniqueid'  => $app_uniqueid,
		);

        //创建群组
		$vid = $this->mode->create($data);

        //创建群成员
        if($this->input['user_id'])
        {
            $group_id = $vid;
            $user_id = implode(",",$this->input['user_id']);
            $this->createGroupUser($app_id, $group_id, $create_uid,$user_id);
        }


		if($vid)
		{
			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem($data);
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
        if(!$this->input['group_id'])
		{
			$this->errorOutput(NOID);
		}
        $group_id = $this->input['group_id'];

        //验证权限  只有群主有权限
        $condition = ' AND session_id='.$group_id.'';
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 1;
        $orderby = '  ORDER BY id ASC ';
        $limit = ' LIMIT ' . $offset . ' , ' . $count;
        $group_user_info = $this->group_user->show($condition,$orderby,$limit);
        if($group_user_info[0]['uid'] != $member_id)
        {
            $this->errorOutput(NO_PERMISSION);
        }

        if($this->input['group_name'])
        {
            $update_data = array(
                'title' => $this->input['group_name']
		    );
        }
        if($_FILES['avatar'])
        {
            $update_data['indexpic'] = $this->uploadimg("avatar");
        }

		$ret = $this->mode->update($group_id,$update_data);

        $info = $this->mode->detail($group_id);
		if($ret)
		{
			//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下

			$this->addItem($info);
			$this->output();
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
		if(!$this->input['group_id'])
		{
			$this->errorOutput(NO_GROUP_ID);
		}
        $group_id = $this->input['group_id'];

        if($this->user['user_id'])
        {
            $member_id = $this->user['user_id'];
        }
        elseif($this->input['member_id'])
        {
            $member_id = $this->input['member_id'];
        }
        else
        {
            $this->errorOutput(NO_MEMBER_ID);
        }

        //验证权限
        $condition = ' AND session_id='.$group_id.'';
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 1;
        $orderby = '  ORDER BY id ASC ';
        $limit = ' LIMIT ' . $offset . ' , ' . $count;
        $group_user_info = $this->group_user->show($condition,$orderby,$limit);
        if($group_user_info[0]['uid'] != $member_id)
        {
            $this->errorOutput(NO_PERMISSION);
        }

		$ret = $this->mode->delete($group_id);

		if($ret)
		{
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}

    /**
     * 获取群组详情
     */
    public function getGroupinfo()
    {
        $group_id = intval($this->input['group_id']);
        if(!$group_id)
        {
            $this->errorOutput(NO_GROUP_ID);
        }

        $info = $this->mode->detail($group_id);
        if($info)
        {
            $this->addItem($info);
            $this->output();
        }
        else
        {
            $this->errorOutput(NO_GROUP_INFO);
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
        $info = $this->group_user->show($condition);

        //获取会员的信息
        $memberInfo = $this->members->get_newUserInfo_by_ids($user_id);

        //合成群成员数组
        if($memberInfo)
        {
            foreach($memberInfo as $k=>$v)
            {
                if($v['member_id'] == $create_uid)
                {
                    //加上管理员
                    $adminData = array(
                        'session_id' => $group_id,
                        'uid'        => $v['member_id'],
                        'uname'      => $v['nick_name'],
                        'is_admin'   => 1,
                        'uavatar'    => $v['avatar'] ? serialize($v['avatar']) : '',
                        'utype'      => $v['type'],
                        'join_time'  => TIMENOW,
                        'app_id'     => $app_id,
                    );
                    //管理员在群成员第一个
                    array_unshift($createData,$adminData);
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
        $result = $this->group_user->createUsers($createData);

        if($result)
        {
            return true;
        }
        else
        {
            $this->errorOutput(CREATE_GROUP_USER_FAIL);
        }
    }

    /**
     * 上传图片
     * @param unknown $var_name
     * @return string
     */
    private function uploadimg($var_name)
    {
        if($_FILES[$var_name])
        {
            //处理avatar图片
            if($_FILES[$var_name] && !$_FILES[$var_name]['error'])
            {
                $_FILES['Filedata'] = $_FILES[$var_name];
                $material = new material();
                $img_info = $material->addMaterial($_FILES);
                if($img_info)
                {
                    $avatar = array(
                        'host' 		=> $img_info['host'],
                        'dir' 		=> $img_info['dir'],
                        'filepath' 	=> $img_info['filepath'],
                        'filename' 	=> $img_info['filename'],
                        'width'		=> $img_info['imgwidth'],
                        'height'	=> $img_info['imgheight'],
                        'id'        => $img_info['id'],
                    );
                    $avatar = @serialize($avatar);
                }
            }
            return $avatar;
        }
    }

}

$out = new group();
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