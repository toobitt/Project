<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/seekhelp_comment.class.php';
require_once CUR_CONF_PATH.'lib/seekhelp.class.php';
require_once CUR_CONF_PATH.'lib/timeline_mode.php';
require_once CUR_CONF_PATH.'lib/member_mode.php';
require_once CUR_CONF_PATH.'lib/app_config_mode.php';
include_once ROOT_PATH.'lib/class/members.class.php';
require_once CUR_CONF_PATH.'lib/seekhelp_blacklist_mode.php';
define('MOD_UNIQUEID','seekhelp_commemt_update');//模块标识
class seekhelpCommentUpdateApi extends outerUpdateBase
{
	private $seekhelp;
	private $comment;
	private $timeline;
	private $member;
	private $members;
    private $appconfig;
    private $blacklist;
	public function __construct()
	{
		parent::__construct();
		$this->comment = new ClassSeekhelpComment();
		$this->seekhelp = new ClassSeekhelp();
		$this->timeline = new timeline_mode();
		$this->member = new member_mode();
		$this->members = new members();
        $this->appconfig = new app_config_mode();
        $this->blacklist = new seekhelp_blacklist_mode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 创建评论
	 * cid 
	 * @see outerUpdateBase::create()
	 */
	public function create()
	{
        if($this->input['app_id'])
        {
            //检查社区黑名单
            $blackInfo = $this->blacklist->check_blackByappId($this->input['app_id']);
            if($blackInfo && $blackInfo['deadline'] == -1)
            {
                $this->errorOutput(SEEKHELP_IS_BLACK);
            }
        }

        $data = array(
			'cid'			=> intval($this->input['cid']),
			'member_id'		=> intval($this->user['user_id']),
			'comment_type'  => trim($this->input['comment_type']),
			'comment_fid'   => intval($this->input['comment_fid']),	
			'location'      => trim($this->input['location']),
			'status'		=> 0,
			'content'       => $this->input['content'],
			'create_time'	=> TIMENOW,
			'ip'			=> $this->user['ip']
		);
		
		//comment_type默认值 此处兼容老社区
		if(!$this->input['comment_type'])
		{
			$data['comment_type'] = 'main';
		}

		if (defined('SEEKHELP_COMMENT_STATUS') && SEEKHELP_COMMENT_STATUS)
		{
			$data['status'] = 1;
		}
        if($this->input['app_id'] && $data['comment_type'] == 'main')
        {
            $app_id = $this->input['app_id'];
            $appconfig = $this->appconfig->detail($app_id);
            if($appconfig['comment_audit'] == 0)
            {
                $data['status'] = 1;
            }
            else
            {
                $data['status'] = 0;
            }
        }

		if (!$data['cid'] || !$data['member_id'] || !$data['content'])
		{
			$this->errorOutput(NOID);
		}
// 		if(!$data['comment_type'])
// 		{
// 			$this->errorOutput(NO_COMMENT_TYPE);
// 		}

		//会员黑名单验证
		include_once(ROOT_PATH.'lib/class/members.class.php');
		$obj = new members();
		$res = $obj->check_blacklist($data['member_id']);

		if($res[$data['member_id']]['isblack'])
		{
            //$this->addItem_withkey('error', "您的评论被屏蔽，请联系管理员！");
            //$this->addItem_withkey('msg', "您的评论被屏蔽，请联系管理员！");
            //$this->output();
			$this->errorOutput(IS_BLACK_MEMBER);
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "seekhelp WHERE id = " . $data['cid'];
		$seekhelp = $this->db->query_first($sql);
		
		$sort_id = $seekhelp['sort_id'];
		$data['sort_id'] = $sort_id;
		
		//屏蔽字验证
		if ($this->settings['App_banword'])
		{
			require_once(ROOT_PATH.'lib/class/banword.class.php');
			$this->banword = new banword();
			$str = $data['content'];
			$banword = $this->banword->exists($str);
			if ($banword && is_array($banword))
			{
				$banword_content = '';
				foreach ($banword as $key=>$val)
				{
					$banword_content .= $val['banname'].',';
				}
				$data['banword'] = $banword_content ? rtrim($banword_content,',') : '';
				$data['status'] = 0;
			}
		}
		$data['content'] = urlencode($data['content']);
		$ret = $this->comment->create($data);
		if($ret)
		{
			if($data['status'])
            {
                if ($data['comment_type'] == 'vice')
                {
                    $sql = "SELECT * FROM " . DB_PREFIX . "comment WHERE id = " . $data['comment_fid'];
                    $comment = $this->db->query_first($sql);
                    $this->SetCommentNum($data['comment_type'],$ret);
                    $this->SetTimeline($ret['id'],$comment,'comment');
                    $this->SetMemberInfo($comment);
                }
                else
                {
                    $this->SetCommentNum($data['comment_type'], $ret);
                    $this->SetTimeline($ret['id'],$seekhelp,'seekhelp');
                    $this->SetMemberInfo($seekhelp);
                }

                //更新会员统计
                if($data['comment_type'] == 'main')
                {
                    $this->updateMemberCount($data['member_id'], 'create');
                }
            }
		}
		
		if ($this->settings['App_members'])
		{
			$memberInfo = $this->members->get_newUserInfo_by_ids($data['member_id']);
		}
		$ret['member_avatar'] = $memberInfo[0]['avatar'];
        $ret['member_name'] = IS_HIDE_MOBILE ? hg_hide_mobile($memberInfo[0]['nick_name']) : $memberInfo[0]['nick_name'];
		$ret['content'] = seekhelp_clean_value(stripcslashes(urldecode($ret['content'])));
		
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 增加评论数
	 * @param unknown $type
	 * @param unknown $id
	 */
	private function SetCommentNum($type, $comment_data, $count = 1)
	{
        if(!$comment_data)
		{
			return false;
		}
		$id = $comment_data['id'];
		$comment_fid = $comment_data['comment_fid'];
		$commentId = $comment_data['id'];
		$contentId = $comment_data['cid'];
		$create_time = $comment_data['create_time'];
		if ($type == 'vice')
		{
			//comment表评论数+1
			$commentFid_data = $this->comment->comment_detail($comment_fid);
			$new_comment_num = $commentFid_data[0]['comment_num'] + $count;
			$res = $this->comment->update($comment_fid, array('comment_num' => intval($new_comment_num)));
		}
		else
		{
			//seekhelp表评论数+1
			$seekhelp_data = $this->seekhelp->seekhelp_detail($contentId);
			if ($seekhelp_data['comment_latest_id'])
			{
				$comment_id_arr = explode(",", $seekhelp_data['comment_latest_id']);
				if(count($comment_id_arr) >= 3)
				{
					unset($comment_id_arr[0]);
				}
				array_push($comment_id_arr, $commentId);
				$comment_latest_Ids_new = implode(",", $comment_id_arr);
			}
			else
			{
				$comment_latest_Ids_new = $commentId;
			}
				
			$new_comment_num = $seekhelp_data['comment_num'] + $count;
			$res = $this->seekhelp->update_status(array(
					'comment_num' => intval($new_comment_num),
					'comment_latest_id'=> $comment_latest_Ids_new,   //最新评论的三个id
					'comment_latest_time' => $create_time,  //记录最新评论时间
			), $contentId);
		}
		return $res;
	}
	
	/**
	 * 创建帖子的时间线
	 * @param unknown $relation_id
	 */
	private function SetTimeline($relation_id, $data, $type, $operation = 'create',$user_id = 0,$user_name ='')
	{
        if($operation == 'create')
        {
            $to_member_id = $data['member_id'];
            if (!$to_member_id)
            {
                return false;
            }
            if($type == 'seekhelp')
            {
                $_type = 'comment';
            }
            elseif($type == 'comment')
            {
                $_type = 'reply';
            }
            $this->timeline->create(array(
                'type' => $_type,
                'relation_id' => $relation_id,
                'user_id' => $user_id ? $user_id : $this->user['user_id'],
                'user_name' => $user_name ? $user_name : $this->user['user_name'],
                'to_user_id' => $to_member_id,
                'create_time' => TIMENOW,
            ));
        }
        else
        {
            $member_id = $data['member_id'];
            if(!$member_id)
            {
                return false;
            }
            if($type == 'seekhelp')
            {
                $this->timeline->delete($relation_id,'comment',$member_id);
            }
            elseif($type == 'comment')
            {
                $this->timeline->delete($relation_id,'reply',$member_id);
            }
        }

	}
	
	/**
	* 会员帖子数量统计
	*/
	private function updateMemberCount($member_id, $operation = 'create')
	{
	    $mycountInfo = $this->members->getMycount($member_id);
	    $action = 'comment';
	    if(empty($mycountInfo))
	    {
            $res = array();
	    }
	    else
	    {
	        $old_num = $mycountInfo[$action];
	        if($operation == 'create')
	        {
	            $new_num = $old_num + 1;
	        }
	        elseif ($operation == 'delete')
	        {
	            $new_num = $old_num - 1;
	        }
	        $res = $this->members->updateMycount($member_id, $action, $new_num);
	    }
	     
	    return $res;
	}
	
	/**
	 * 更新会员缓存表信息
	 */
	public function SetMemberInfo($data, $count = 1, $user_id = 0)
	{
		$member_id = intval($data['member_id']);
		if(!$member_id)
		{
			return false;
		}
        if(!$user_id)
        {
            $user_id = intval($this->user['user_id']);
        }

		if(!$user_id)
		{
			return false;
		}
		//如果赞的自己内容 不加
		if($member_id == $user_id)
		{
			return false;
		}	
		$res = $this->member->detail($member_id);
		if(!$res && $member_id)
		{
			if ($this->settings['App_members'])
			{
				$memberInfo = $this->members->get_newUserInfo_by_ids($member_id);
			}
            if($count < 0)
            {
                $count = 0;
            }
			$this->member->create(array(
					'member_id' => $member_id,
					'member_name' => $memberInfo[0]['member_name'],
					'relateme_num' => $count,
			));
		}
		else
		{
			$new_relateme_num = $res['relateme_num'] + $count;
            if($new_relateme_num < 0)
            {
                $new_relateme_num = 0;
            }
			$this->member->update($res['id'],array(
					'relateme_num' => $new_relateme_num,
			));
		}
	}
	
	public function update()
	{
	
	}
	
	public function delete()
	{
		if($this->user['user_id'])
		{
			$member_id = $this->user['user_id'];
		}

		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		//查询出评论详情
		$comment = $this->comment->comment_detail($id);
		$comment = $comment[0];
		if(!$comment['member_id'])
		{
			$this->errorOutput(NO_COMMENT_INFO);
		}
		if($comment['member_id'] != $this->user['user_id'])
		{
			//判断是否有权限删除贴子
			if($this->input['access_token'])
			{
				$access_token = $this->input['access_token'];
			}
			$ret = $this->members->check_purview_Bytoken($access_token,'members_comment_del');
			if(!$ret['allow'])
			{
				$this->errorOutput("您没有权限删除帖子评论，请联系管理员");
			}
		}

		//删除评论记录
		$result = $this->comment->delete($id);

		if($result)
		{
			//更新帖子或主评论的评论数
			$this->SetCommentNum($comment['comment_type'],$comment,-1);

			//删除个人主页的数据
			if($comment['comment_type'] == 'main')
			{
				$seekhelp = $this->seekhelp->seekhelp_detail($comment['cid']);
				$this->SetTimeline($id,$seekhelp,'seekhelp','delete');
			}
			else
			{
				$father_comment = $this->comment->comment_detail(['comment_fid']);
				$this->SetTimeline($id,$father_comment[0],'comment','delete');
			}

			//更新会员缓存表的评论数
			$this->updateMemberCount($comment['member_id'],'delete');
		}

		$this->addItem($result);
		$this->output();
	}
	
	public function add_gold_reply()
	{
		$id = $this->input['id'] ? intval($this->input['id']) : 0;
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = array(
			'gold_reply' => $this->input['gold_reply'] ? trim($this->input['gold_reply']) : '',
			'org_id' => $this->user['org_id'],
			'user_name' => $this->user['user_name'],
			'user_id' => $this->user['user_id'],
			'ip' => $this->user['ip'],
			'id' => $id,
		);
		$ret = $this->seekhelp->add_gold_reply($data);
		if($ret)
		{
			$this->addItem(array('id' => $id,'gold_reply' => $data['gold_reply']));
			$this->output();
		}
		else
		{
			$this->errorOutput(CREATE_FAIL);
		}		
	}

    /**
     * 审核评论状态
     */
    public function audit()
    {
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
            {
                $this->errorOutput(NO_PRIVILEGE);
            }
        }
        if(!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }

        $status = intval($this->input['status']);
        if(!$status)
        {
            $this->errorOutput(NO_STATUS);
        }

        $data = array(
            'status' 		=> $status,
        );

        $ret = $this->comment->audit($this->input['id'],$status);

        $info = $this->comment->comment_detail($this->input['id']);
        $info = $info[0];
        $sql = "SELECT * FROM " . DB_PREFIX . "seekhelp WHERE id = " . $info['cid'];
        $seekhelp = $this->db->query_first($sql);
        if($ret)
        {
            //$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
            if($status == 1)
            {
                if ($info['comment_type'] == 'vice')
                {
//                    $sql = "SELECT * FROM " . DB_PREFIX . "comment WHERE id = " . $info['comment_fid'];
//                    $comment = $this->db->query_first($sql);
//                    $this->SetCommentNum($info['comment_type'],$info, 1);
//                    $this->SetTimeline($info['id'],$info,'comment');
//                    $this->SetMemberInfo($comment,1,$info['member_id']);
                }
                else
                {
                    $this->SetCommentNum($info['comment_type'], $info, 1);
                    $this->SetTimeline($info['id'],$seekhelp,'seekhelp','create',$info['member_id'],$info['member_name']);
                    $this->SetMemberInfo($seekhelp,1,$info['member_id']);
                }

                //更新会员统计
                if($info['comment_type'] == 'main')
                {
                    $this->updateMemberCount($info['member_id'], 'create');
                }
            }
            elseif($status == 2)
            {
                if ($info['comment_type'] == 'vice')
                {
//                    $sql = "SELECT * FROM " . DB_PREFIX . "comment WHERE id = " . $info['comment_fid'];
//                    $comment = $this->db->query_first($sql);
//                    $this->SetCommentNum($info['comment_type'],$info, -1);
//                    $this->SetTimeline($info['id'],$info,'comment','delete');
//                    $this->SetMemberInfo($comment,-1,$info['member_id']);
                }
                else
                {
                    $this->SetCommentNum($info['comment_type'], $info, -1);
                    $this->SetTimeline($info['id'],$seekhelp,'seekhelp','delete',$info['member_id'],$info['member_name']);
                    $this->SetMemberInfo($seekhelp,-1,$info['member_id']);
                }

                //更新会员统计
                if($info['comment_type'] == 'main')
                {
                    $this->updateMemberCount($info['member_id'], 'delete');
                }
            }


            $this->addItem($ret);
            $this->output();
        }
    }

}
$ouput= new seekhelpCommentUpdateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'create';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
