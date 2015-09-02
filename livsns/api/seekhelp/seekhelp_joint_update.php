<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/seekhelp_joint.class.php';
require_once CUR_CONF_PATH.'lib/seekhelp_comment.class.php';
require_once CUR_CONF_PATH.'lib/timeline_mode.php';
require_once CUR_CONF_PATH.'lib/seekhelp.class.php';
require_once CUR_CONF_PATH.'lib/member_mode.php';
include_once (ROOT_PATH.'lib/class/members.class.php');
define('MOD_UNIQUEID','seekhelp_jiont_update');//模块标识
class seekhelpJointUpdateApi extends outerUpdateBase
{
	private $joint;
	private $comment;
	private $seekhelp;
	private $timeline;
	private $member;
	private $members;
	public function __construct()
	{
		parent::__construct();
		$this->joint = new ClassSeekhelpJoint();
		$this->comment = new ClassSeekhelpComment();
		$this->seekhelp = new ClassSeekhelp();
		$this->timeline = new timeline_mode();
		$this->member = new member_mode();
		$this->members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$data = array(
			'cid'			=> intval($this->input['cid']),
			'member_id'		=> intval($this->user['user_id']),
			'joint_type'	=> trim($this->input['joint_type']),
			'tel'			=> $this->input['tel'],
			'create_time'	=> TIMENOW,
			'ip'			=> isset($this->user['ip']) ? $this->user['ip'] : $this->input['ip'],
			
		);
		//joint_type默认值 此处兼容老社区
		if(!$this->input['joint_type'])
		{
			$data['joint_type'] = 'main';
		}
		
		if (!$data['cid'] || !$data['member_id'])
		{
			$this->errorOutput(NOID);
		}
		
		//查询是否已经赞过
		$orderby = ' ORDER BY id  DESC';
		$condition = " AND cid=".$data['cid']." AND joint_type='".$data['joint_type']."' AND member_id=".$data['member_id']."";
		$joint_info = $this->joint->show($condition, $orderby, 0, 0);
		if($joint_info)
		{
			$this->errorOutput(HAS_JOINT);
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "seekhelp WHERE id = " . $data['cid'];
		$seekhelp = $this->db->query_first($sql);
		
		$ret = $this->joint->create($data);
		if($ret)
		{
			$this->SetJointNum($ret['joint_type'], $ret['cid']);
			if($ret['joint_type']  == 'main')
			{
				$this->SetTimeline($ret['cid'],$seekhelp,'main');
				$this->SetMemberInfo($seekhelp);
			}
			elseif($ret['joint_type']  == 'vice')
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "comment WHERE id = " . $ret['cid'];
				$comment = $this->db->query_first($sql);
				$this->SetTimeline($ret['cid'],$comment,'vice');
				$this->SetMemberInfo($comment);
			}
		}
		
		//更新会员统计
        if($data['joint_type'] == 'main')
        {
            $this->updateMemberCount($data['member_id'], 'create');
        }
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 增加,减少点赞数
	 */
	private function SetJointNum($type,$id,$func = 'plus')
	{
		if ($type == 'main')
		{
			$seekhelp_data = $this->seekhelp->seekhelp_detail($id);
			if($func == 'plus')
			{
				//seekhelp表点赞数+1
				$new_joint_num = $seekhelp_data['joint_num'] + 1;
			}
			else 
			{
				//seekhelp表点赞数-1
				$new_joint_num = $seekhelp_data['joint_num'] - 1;
				if($new_joint_num < 0)
				{
					$new_joint_num = 0;
				}
				
			}
			$res = $this->seekhelp->update_status(array('joint_num' => $new_joint_num), $id);
		}
		elseif ($type == 'vice')
		{
			
			$comment_data = $this->comment->comment_detail($id);
			if($func == 'plus')
			{
				//comment表点赞+1
				$new_joint_num = $comment_data[0]['joint_num'] + 1;
			}
			else
			{
				//comment表点赞-1
				$new_joint_num = $comment_data['joint_num'] - 1;
				if($new_joint_num < 0)
				{
					$new_joint_num = 0;
				}
			}
			$res = $this->comment->update($id, array('joint_num' => $new_joint_num));
		}
	}
	
	/**
	 * 创建帖子的时间线
	 * @param unknown $relation_id
	 */
	private function SetTimeline($relation_id,$data,$type)
	{
		$to_member_id = intval($data['member_id']);
		if (!$to_member_id)
		{
			return false;
		}
		if($type == 'main')
		{
			$this->timeline->create(array(
					'type' => 'joint',
					'relation_id' => $relation_id,
					'user_id' => $this->user['user_id'],
					'user_name' => $this->user['user_name'],
					'to_user_id' => $to_member_id,
					'create_time' => TIMENOW,
			));
		}
		elseif($type == 'vice')
		{
			$this->timeline->create(array(
					'type' => 'joint_comment',
					'relation_id' => $relation_id,
					'user_id' => $this->user['user_id'],
					'user_name' => $this->user['user_name'],
					'to_user_id' => $to_member_id,
					'create_time' => TIMENOW,
			));
		} 
	}
	
	/**
	 * 更新会员缓存表信息
	 */
	private function SetMemberInfo($data,$type = 'plus')
	{
		$member_id = intval($data['member_id']);
		if(!$member_id)
		{
			return false;
		}
		$user_id = intval($this->user['user_id']);
		if(!$user_id)
		{
			return false;
		}
		//如果赞的自己内容 不加
		if($member_id == $user_id)
		{
			return false;
		}	
		$condition = " AND member_id=".$member_id."";
		$res = $this->member->detail($member_id);
		if($type == 'reduce')
		{
			if(!$res && $member_id)
			{
				if ($this->settings['App_members'])
				{
					$memberInfo = $this->members->get_newUserInfo_by_ids($member_id);
				}
				$ret = $this->member->create(array(
						'member_id' => $member_id,
						'member_name' => $memberInfo[0]['member_name'],
						'relateme_num' => 0,
				));
			}
			else
			{
				$new_relateme_num = $res['relateme_num'] - 1;
				if($new_relateme_num <= 0)
				{
					$new_relateme_num = 0;
				}
				$this->member->update($res['id'],array(
						'relateme_num' => $new_relateme_num,
				));
			}
		}
		else 
		{
			if(!$res && $member_id)
			{
				if ($this->settings['App_members'])
				{
					$memberInfo = $this->members->get_newUserInfo_by_ids($member_id);
				}
				$ret = $this->member->create(array(
						'member_id' => $member_id,
						'member_name' => $memberInfo[0]['member_name'],
						'relateme_num' => 1,
				));
			}
			else
			{
				$new_relateme_num = $res['relateme_num'] + 1;
				if($new_relateme_num >= 99)
				{
					$new_relateme_num = 99;
				}
				$this->member->update($res['id'],array(
						'relateme_num' => $new_relateme_num,
				));
			}
		}
		
	}
	
	/**
	 * 会员帖子数量统计
	 */
	private function updateMemberCount($member_id, $operation)
	{
	    $mycountInfo = $this->members->getMycount($member_id);
	    $action = 'praise';
	    $res = array();
	    if(empty($mycountInfo))
	    {
	        
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
	
	public function update()
	{
	
	}
	
	public function delete()
	{
		$data = array();
		$cid = intval($this->input['cid']);  //求助id
		$member_id = intval($this->user['user_id']);
		$joint_type = $this->input['joint_type'];
		if (!$cid || !$member_id)
		{
			$this->errorOutput(NOID);
		}
		
// 		$condition = ' AND joint_type="'.$joint_type.'" AND member_id='.$member_id.' AND cid='.$cid.'';
// 		$jointInfo = $this->joint->show($condition, $orderby, $offset = '', $count = '');
		
		$data = $this->joint->delete($cid, $member_id, $joint_type);
		if(!$data)
		{
			$this->errorOutput(DELETE_JOINT_FAIL);
		}
		$this->SetJointNum($joint_type, $cid,'reduce');
		if($joint_type == 'main')
		{
			$this->timeline->delete($cid,'joint',$member_id);
		}
		elseif($joint_type == 'vice')
		{
			$this->timeline->delete($cid,'joint_comment',$member_id);
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "seekhelp WHERE id = " . $cid;
		$seekhelp = $this->db->query_first($sql);
		$this->SetMemberInfo($seekhelp,'reduce');
		//更新会员统计
		$this->updateMemberCount($data['member_id'], 'delete');
		$this->addItem($data);
		$this->output();
	}
	
}
$ouput= new seekhelpJointUpdateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'create';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();