<?php
require_once './global.php';
define('MOD_UNIQUEID','Timeline');//模块标识
require_once CUR_CONF_PATH.'lib/seekhelp.class.php';
require_once CUR_CONF_PATH.'lib/timeline_mode.php';
require_once CUR_CONF_PATH.'lib/seekhelp_comment.class.php';
require_once CUR_CONF_PATH.'lib/seekhelp_joint.class.php';
require_once CUR_CONF_PATH.'lib/member_mode.php';
require_once(ROOT_PATH . 'frm/node_frm.php');
require_once(ROOT_PATH . 'lib/class/members.class.php');
class Relateme extends outerReadBase
{
	private $seekhelp;
	private $section;
	private $comment;
	private $joint;
	private $members;
	private $timeline;
	private $member;
	public function __construct()
	{
		parent::__construct();
		$this->seekhelp = new ClassSeekhelp();
		$this->comment = new ClassSeekhelpComment();
		$this->joint = new ClassSeekhelpJoint();
		$this->node = new nodeFrm();
		$this->members = new members();
		$this->timeline = new timeline_mode();
		$this->member = new member_mode();
		$this->node->setNodeTable('sort');
		$this->node->setNodeVar('seekhelp_node');
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		if($this->user['user_id'])
		{
			$member_id = $this->user['user_id'];
		}
		$result = $this->init($member_id);
		$this->addItem($result);
		$this->output();
	}
	
	public function init($member_id)
	{
		$offset = intval($this->input['start']) ? intval($this->input['start']) : 0;
		$count  = intval($this->input['count'])	 ? intval($this->input['count'])  : 30;
		$limit = ' limit ' .$offset.','.$count.'';
		$orderby = ' ORDER BY create_time  DESC';
		$condition = " AND to_user_id='".$member_id."' AND user_id<>'".$member_id."' AND relateme_display<>-1 ";
		$timelineInfo = $this->timeline->show($condition,$orderby,$limit);
		foreach ($timelineInfo as $k=>$v)
		{
			if ($v['type'] == 'comment')
			{
				$comment = $this->comment->comment_detail($v['relation_id']);
				if($comment[0]['comment_type'] == 'main')
				{
					$cid = $comment[0]['cid'];
					$seekhelpInfo = $this->seekhelp->detail($cid);
					$seekhelpInfo['comment'] = $comment[0];
					$timelineInfo[$k]['data'] = $seekhelpInfo;
				}
			}
			elseif ($v['type'] == 'reply')
			{
				$comment = $this->comment->comment_detail($v['relation_id']);
				if($comment[0]['comment_type'] == 'vice')
				{
					$comment_fid = $comment[0]['comment_fid'];
					$commentInfo = $this->comment->comment_detail($comment_fid);
					$commentInfo[0]['reply'] = $comment[0];
					$timelineInfo[$k]['data'] = $commentInfo[0];
				}
			}
			elseif ($v['type'] == 'joint')
			{
				$seekhelp_info = $this->seekhelp->detail($v['relation_id'],$member_id);
				$timelineInfo[$k]['data'] = $seekhelp_info;
			}
			elseif ($v['type'] == 'joint_comment')
			{
				$comment_info = $this->comment->detail($v['relation_id']);
				$timelineInfo[$k]['data'] = $comment_info;
			}
            elseif($v['type'] == 'attention')
            {
                //
            }
		}
		
		$this->SetMemberInfo();
		return $timelineInfo;
	}
	
	/**
	 * 更新会员缓存表信息
	 */
	private function SetMemberInfo()
	{
		$member_id = intval($this->user['user_id']);
		if(!$member_id)
		{
			return false;
		}
		$condition = " AND member_id=".$member_id."";
		$res = $this->member->detail($member_id);
		if(!$res && $member_id)
		{
			$this->member->create(array(
					'member_id' => $member_id,
					'member_name' => $this->user['user_name'],
					'relateme_num' => 0,
			));
		}
		else
		{
			$this->member->update($res['id'],array(
					'relateme_num' => 0,
			));
		}
	}
	
	/**
	 * 清空会员 与我相关的列表 （relateme_display = -1）
	 * $member_id int
	 */
	public function clearRelateme()
	{
		$member_id = intval($this->user['user_id']);
		if(!$member_id)
		{
			$this->errorOutput(NO_MEMBER_INFO);
		}
	    if($this->input['message_id'])
	    {
	        $condition = " AND id IN(".$this->input['message_id'].")";
	        $result = $this->timeline->updateToUser($member_id,array(
	                'relateme_display' => '-1'
	        ),$condition);
	    }
	    else 
	    {
	        $result = $this->timeline->updateToUser($member_id,array(
	                'relateme_display' => '-1'
	        ));
	    }
		
		if($result)
		{
			$this->addItem(array('code' => 0,'msg' => 'success'));
		}
		else 
		{
			$this->errorOutput(UPDATE_FAIL);
		}
		$this->output();
	}
	
	public function detail(){}
	public function count(){}
}
$ouput = new Relateme();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
