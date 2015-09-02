<?php
require_once './global.php';
define('MOD_UNIQUEID','Timeline');//模块标识
require_once CUR_CONF_PATH.'lib/seekhelp.class.php';
require_once CUR_CONF_PATH.'lib/section_mode.php';
require_once CUR_CONF_PATH.'lib/timeline_mode.php';
require_once CUR_CONF_PATH.'lib/member_mode.php';
require_once CUR_CONF_PATH.'lib/seekhelp_comment.class.php';
require_once CUR_CONF_PATH.'lib/seekhelp_joint.class.php';
require_once(ROOT_PATH . 'frm/node_frm.php');
require_once(ROOT_PATH . 'lib/class/members.class.php');
class Timeline extends outerReadBase
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
		$this->section = new section_mode();
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

		if($this->input['member_id'])
		{
			$member_id = $this->input['member_id'];
		}
        elseif($this->user['user_id'])
        {
            $member_id = $this->user['user_id'];
        }
		
		$result = $this->init($member_id);
		
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 初始化所有方法
	 * @see coreFrm::init()
	 */
	public function init($member_id)
	{
		$offset = intval($this->input['start']) ? intval($this->input['start']) : 0;
		$count  = intval($this->input['count'])	 ? intval($this->input['count'])  : 30;
		$limit = ' limit ' .$offset.','.$count.'';
		$orderby = ' ORDER BY create_time  DESC';
		$condition = " AND user_id='".$member_id."'";
		$timelineInfo = $this->timeline->show($condition,$orderby,$limit);
		$requestNum = count($timelineInfo);
		
		$memberInfo = $this->getRelatemeNum($member_id);
		$relatemeNum = $memberInfo['relateme_num'];
		$background = $memberInfo['background'];
        $signature = $memberInfo['signature'];

		foreach ($timelineInfo as $k=>$v)
		{
			if($v['type'] == 'seekhelp')
			{
				$timelineInfo[$k]['data'] = $this->seekhelp->detail($v['relation_id'],$member_id);
			}
			elseif ($v['type'] == 'comment')
			{
				$comment = $this->comment->comment_detail($v['relation_id']);
				if($comment[0]['comment_type'] == 'main')
				{
					$timelineInfo[$k]['data'] = $comment[0];
				}
// 				elseif($comment[0]['comment_type'] == 'vice')
// 				{
// 					unset($timelineInfo[$k]);
// 				}
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
				 $timelineInfo[$k]['data']['memberId'] = $member_id;
				 $timelineInfo[$k]['data']['cid'] = empty($seekhelp_info['id']) ? 0 : $seekhelp_info['id'];
				 $timelineInfo[$k]['data']['seekhelpContent'] = $seekhelp_info['content'];
			}
		}
		return array(
				'relatemeNum' => $relatemeNum,
				'requestNum' => $requestNum,
				'background' => $background,
                'signature' => $signature,
				'content' => $timelineInfo
		);
	}
	
	/**
	 * 获取与我相关的数目
	 * @return Ambigous <>
	 */
	private function getRelatemeNum($member_id)
	{
		if($member_id)
		{
			$membercacheInfo = $this->member->detail($member_id);
            $memberInfo = $this->members->get_member_info($member_id);

            $info = array_merge($membercacheInfo,$memberInfo[$member_id]);
		}
		return $info;
	}
	
	/**
	 * 个人主页获取某天 更多评论
	 */
	public function getMoreComment()
	{
		$orderby = ' ORDER BY c.create_time  DESC';
		$time = $this->input['time'];
		$offset = intval($this->input['start']) ? $this->input['start'] : 0;
		$count = intval($this->input['count']) ? $this->input['count'] : 10;
		$ntime = strtotime($time) + 24 * 3600;
		$result = array();
		if($this->user['user_id'])
		{
			$member_id = $this->user['user_id'];
		}
		else
		{
			$member_id = $this->input['member_id'];
		}
		if($member_id)
		{
		    if($time)
		    {
		        $condition = " AND c.create_time > ".strtotime($time)." AND c.create_time < ".$ntime."
					   AND c.member_id=".$member_id." AND (c.comment_type='main' or c.comment_type='')";
		    }
		    else
		    {
		        $condition = " AND c.member_id=".$member_id." AND (c.comment_type='main' or c.comment_type='')";
		    }
		}
        else
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
		
		$result = $this->comment->show($condition, $orderby, $offset, $count);
		
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 个人主页获取某天 更多赞
	 */
	public function getMoreJoint()
	{
        $orderby = ' ORDER BY create_time  DESC';
		$time = $this->input['time'];
		$offset = intval($this->input['start']) ? $this->input['start'] : 0;
		$count = intval($this->input['count']) ? $this->input['count'] : 10;
		$ntime = strtotime($time) + 24 * 3600;
		$result = array();
		if($this->user['user_id'])
		{
			$member_id = $this->user['user_id'];
		}
		else
		{
			$member_id = $this->input['member_id'];
		}
		if($member_id)
		{
		    if($time)
		    {
		        $condition = " AND create_time > ".strtotime($time)." AND create_time < ".$ntime."
					   AND member_id=".$member_id." AND (joint_type='main' or joint_type='')";
		    }
		    else
		    {
		        $condition = " AND member_id=".$member_id." AND (joint_type='main' or joint_type='')";
		    }
		}
        else
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
		
		$result = $this->joint->show($condition, $orderby, $offset, $count);
		
		if ($result && is_array($result))
		{
		    foreach ($result as $k=>$v)
		    {
		        $seekhelp_info = $this->seekhelp->detail($v['cid'],$member_id);
		        if($seekhelp_info && isset($seekhelp_info['id']))
		        {
		            $result[$k]['seekhelp']['id'] = empty($seekhelp_info['id']) ? '' : $seekhelp_info['id'];
		            $result[$k]['seekhelp']['content'] = $seekhelp_info['content'];
		        }
		        else
		        {
		            $result[$k]['seekhelp']['id'] = "";
		            $result[$k]['seekhelp']['content'] = "此内容已被主人删除";
		        }
		        	
		    }
		}
		
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 个人主页获取某天 更多帖子
	 */
	public function getMoreSeekhelp()
	{
		$orderby = ' ORDER BY sh.create_time  DESC';
		$time = $this->input['time'];
		$offset = intval($this->input['start']) ? $this->input['start'] : 0;
		$count = intval($this->input['count']) ? $this->input['count'] : 10;
		$ntime = strtotime($time) + 24 * 3600;
		$result = array();
		if($this->user['user_id'])
		{
			$member_id = $this->user['user_id'];
		}
		else
		{
			$member_id = $this->input['member_id'];
		}
		
		if($member_id)
		{
		    if($time)
		    {
		        $condition = " AND sh.create_time > ".strtotime($time)." AND sh.create_time < ".$ntime."
					   AND sh.member_id=".$member_id."";
		    }
		    else
		    {
		        $condition = " AND sh.member_id=".$member_id."";
		    }
		}
        else
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
		
		$result = $this->seekhelp->getSeekhelplist($condition, $orderby, $offset, $count);
		
		$this->addItem($result);
		$this->output();
	}

    /**
     * 创建时间线
     */
    public function create()
    {
        $type = $this->input['type'];
        $relation_id = intval($this->input['relation_id']);
        $user_id = intval($this->input['user_id']);
        $user_name = trim($this->input['user_name']);
        $to_user_id = intval($this->input['to_user_id']);
        $relateme_display = intval($this->input['relateme_display']) ? intval($this->input['relateme_display']) : 1;
        $create_time = TIMENOW;
        if(empty($type))
        {
            $this->errorOutput(NO_TYPE);
        }

        $data = array(
            'type' => $type,
            'relation_id' => $relation_id,
            'user_id' => $user_id,
            'user_name' => $user_name,
            'to_user_id' => $to_user_id,
            'relateme_display' => $relateme_display,
            'create_time' => $create_time,
        );
        $vid = $this->timeline->create($data);

        if($vid)
        {
            $data['id'] = $vid;
            $this->addItem($data);
        }
        $this->output();
    }



	public function detail(){}
	public function count(){}
}
$ouput = new Timeline();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
	