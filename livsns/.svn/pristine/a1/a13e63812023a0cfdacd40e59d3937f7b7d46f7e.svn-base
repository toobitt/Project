<?php
require_once './global.php';
define('MOD_UNIQUEID','Community');//模块标识
require_once CUR_CONF_PATH.'lib/seekhelp.class.php';
require_once CUR_CONF_PATH.'lib/section_mode.php';
require_once CUR_CONF_PATH.'lib/seekhelp_comment.class.php';
require_once CUR_CONF_PATH.'lib/seekhelp_joint.class.php';
require_once CUR_CONF_PATH.'lib/member_mode.php';
require_once CUR_CONF_PATH.'lib/seekhelp_blacklist_mode.php';
require_once(ROOT_PATH . 'frm/node_frm.php');
require_once(ROOT_PATH . 'lib/class/members.class.php');
class Community extends outerReadBase
{
	private $seekhelp;
	private $section;
	private $comment;
	private $joint;
	private $members;
	private $blacklist;
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
		$this->member = new member_mode();
		$this->blacklist = new seekhelp_blacklist_mode();
		$this->node->setNodeTable('sort');
		$this->node->setNodeVar('seekhelp_node');
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 获取微社区 详情 
	 * @see outerReadBase::show()
	 * @param app_id  应用id
	 * @param order 排序 latest_comment最新回复  seekhelp最新帖子
	 * @param section_id 版块id
	 * @param request_type
	 * @param start查询开始位置 这里名称区别offset
	 * @param count查询数量
	 */
	public function show()
	{
		$app_id = $this->input['app_id'];
		$order = $this->input['order'];
		$sectionId = $this->input['section_id'];
		$request_type = $this->input['request_type'];
		$offset = $this->input['start'];
		$count = $this->input['count'];
		if(!$app_id)
		{
			$this->errorOutput(NO_APPID);
		}
		
		//检查社区黑名单
		$blackInfo = $this->blacklist->check_blackByappId($app_id);
		if($blackInfo && $blackInfo['deadline'] == -1)
		{
			$this->addItem(array('is_black' => 1,'msg' => '您的应用是黑名单','data' => $blackInfo));
        	$this->output();
		}
		
		//验证版块是否存在
		if($sectionId)
		{
			$sectionInfo = $this->section->detail($sectionId);
			if(!$sectionInfo)
			{
				$this->errorOutput(NO_SECTION);
			}
		}
		//通过应用id获取社区
		$sort_data = $this->get_sortidByappId($app_id);
		$sortId = $sort_data['id'];
		if(!$sortId)
		{
			$this->errorOutput(NO_SORT_ID);
		}
		$result = $this->init($sortId, $sectionId, $order, $request_type, $offset, $count);
		
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 初始化获取数据
	 */
	private function init($sort_id, $sectionId, $order, $request_type, $offset, $count)
	{
		$result = array();
		//切换到版块获取版块的名称和索引图
		if($sectionId)
		{
			$community_result = $this->get_sectionInfo($sectionId, $sort_id);
		}
		else 
		{
			//获取此社区数据
			$community_result = $this->get_community($sort_id);
		}
		
		//获取帖子数据
		$seekhelp_result = $this->get_seekhelp($sort_id, $sectionId, $request_type, $order, $offset, $count);
		
		$community_result['seekhelp_total'] = $seekhelp_result['total'];
		return array_merge($community_result,array('content' => $seekhelp_result['data']));
	}
	
	/**
	 * 获取版块详情
	 * @param sort_id 社区id
	 */
	public function get_section()
	{
		$sort_id = intval($this->input['sort_id']);
		if(!$sort_id)
		{
			$this->errorOutput(NO_SORT_ID);
		}
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 20;
		$limit = ' limit ' .$offset.','.$count.'';
		$orderby = ' ORDER BY order_id  ASC';
		$condition = " AND sort_id=".$sort_id."";
		$section_data = array();
		
		$section_data = $this->section->show($condition,$orderby,$limit);
		
		if($section_data && is_array($section_data))
		{
			foreach ($section_data as $k=>$v)
			{
				if($v['avatar'])
				{
					$section_data[$k]['avatar'] = unserialize($v['avatar']);
				}
				else
				{
					$section_data[$k]['avatar'] = array(
							'host'=> '',
							'dir' => '',
							'filepath' => '',
							'filename' => '',
					);
				}
				//话题数量
				$seekhelp_total = $this->seekhelp->count(" AND section_id=".$v['id']."");
				$section_data[$k]['seekhelp_total'] = $seekhelp_total['total'];
			}
		}
		$this->addItem($section_data);
		$this->output();
	}
	
	/**
	 * 获取版块详情
	 */
	private function get_sectionInfo($section_id,$sort_id)
	{
		$section_data = array();
		//获取此社区数据
		$community_result = $this->get_community($sort_id);
		
		$section_data = $this->section->detail($section_id);
	
		//话题数量
		$seekhelp_total = $this->seekhelp->count(" AND section_id=".$section_id."");
		
		if($section_data)
		{
			$section_data = array(
					'id'             => $section_data['id'],
					'name'           => $section_data['name'],
					'avatar'         => $section_data['avatar'],
					'background'     => $community_result['background'],
					'createTime'     => $section_data['create_time'],
					'seekhelp_total' => intval($seekhelp_total['total']),
			);
		}
		return $section_data;
	}
	
	/**
	 * 获取微社区详情
	 */
	public function get_community($sort_id = 0)
	{
		if(!$sort_id)
		{
			//POST
			$sort_id = intval($this->input['sort_id']);
			$app_id = intval($this->input['app_id']);
		}
		if(!$sort_id)
		{
			$this->errorOutput(NO_SORT_ID);
		}
		$community_data = array();
		$condition = " AND id='".$sort_id."'";
		//社区数据
		$community_data = $this->node->getNodesList($condition,true);
		sort($community_data);
		//社区成员数量
// 		if($app_id)
// 		{
// 			$member_total = $this->members->count(array('identifier' => $app_id));
// 		}
// 		else 
// 		{
// 			$member_total = $this->members->count(array('identifier' => $community_data[0]['app_id']));
// 		}
		
		if($community_data && is_array($community_data))
		{
			foreach ($community_data as $k=>$v)
			{
				$community_data = array(
						'id'             => $v['id'],
						'name'           => $v['name'],
						'brief'          => $v['brief'],
						'avatar'         => $v['avatar'],
						'background'     => $v['background'],
						'type'           => $v['type'],
						'app_id'         => $v['app_id'],
						'createTime'     => $v['create_time'],
						'userName'       => $v['user_name'],
						// 					'member_total'   => empty((int)$member_total[0]['total']) ? 0 : intval((int)$member_total[0]['total']),
				);
			}
		}
		
		if($this->input['sort_id'])
		{
			$this->addItem($community_data);
			$this->output();
		}
		else 
		{
			return $community_data;
		}
	}
	
	/**
	 * 获取社区帖子
	 */
	private function get_seekhelp($sort_id,$sectionId, $request_type, $order = 'latest_comment',$offset,$count)
	{
		$seekhelp_result = array();
		$top_seekhelp_result = array();
		//获取这个社区回收版块的id
		$recycleId = $this->getRecycleId($sort_id);
		
		//获取帖子详情
		if($request_type == 'detail')
		{
			$offset = '';
			$count = '';
			$seekhelpId = $this->input['seekhelp_id'];
			$condition = " AND sh.sort_id='".$sort_id."' AND sh.id=".$seekhelpId."";
		}
		else
		{
			//获取社区列表 并且不在回收站的帖子
			$offset = $offset ? intval($offset) : 0;
			$count  = $count  ? intval($count)  : 10;
			$condition = " AND sh.sort_id=".$sort_id." AND sh.section_id not in(".$recycleId.") AND sh.status=1";
		}
        //不看黑名单的帖子
		if($this->user['user_id'])
		{
			$member_blacklist = $this->members->get_friend_blacklist();
			if($member_blacklist)
			{
				foreach($member_blacklist as $k=>$v)
				{
					$member_id[] = $v['member_id'];
				}
				$member_ids = implode(",",$member_id);
				$condition .= ' AND sh.member_id NOT IN ('.$member_ids.')';
			}
		}

		$memberId = $this->user['user_id'];
		
		if($order == 'latest_comment')   //按最后回复排序
		{
			$order_condition = 'comment_latest_time';
		}
		elseif ($order == 'seekhelp')   //按最新话题排序
		{
			$order_condition = 'order_id';
		}
		$orderby = ' ORDER BY is_top DESC,'.$order_condition.'  DESC';
		
		//获取单一版本下的帖子列表
		if ($sectionId)
		{
			$condition .= " AND sh.section_id='".$sectionId."'";
		}
		
		$seekhelp_result = $this->seekhelp->getSeekhelplist($condition, $orderby, $offset, $count,$sort_id);
		//话题数量
		$seekhelp_total = $this->seekhelp->count($condition);
		
		if($seekhelp_result && is_array($seekhelp_result))
		{
			foreach ($seekhelp_result as $k=>$v)
			{
				$seekhelp_result[$k]['comment'] = array();
				//获取评论数据
				if($request_type == 'list')
				{
					//获取最新三条的评论
					if($v['comment_latest_id'])
					{
						$seekhelp_result[$k]['comment'] = $this->getCommentByids($v['comment_latest_id']);
					}
					else
					{
						$seekhelp_result[$k]['comment'] = $this->get_comment($v['id'], 0 ,3,'DESC');
					}
                    //逆向排序
                    if($seekhelp_result[$k]['comment'])
                    {
                        rsort($seekhelp_result[$k]['comment']);
                    }
				}
				elseif ($request_type == 'detail')   //详情获取评论
				{
					$seekhelp_result[$k]['comment'] = $this->get_comment($v['id']);
				}
				//获取点赞数据
				$seekhelp_result[$k]['joint'] = $this->get_joint($v['id'],'cid');
					
				//查询此用户是否赞过这个帖子
				$seekhelp_result[$k]['is_joint'] = false;
				if($memberId)
				{
					$condition = " AND joint_type='main' AND cid=".$v['id']." AND member_id=".$memberId."";
					$jointInfo = $this->joint->show($condition, $orderby = '', $offset ='', $count = '');
					if ($jointInfo)
					{
						$seekhelp_result[$k]['is_joint'] = true;
					}
				}
			}
		}
		
		return array('total' => intval($seekhelp_total['total']),'data' => $seekhelp_result);
	}
	
	/**
	 * 获取评论
	 */
	public function get_comment($cid = 0, $offset = 0, $count = 10, $sort = 'ASC')
	{
		if(!$cid)
		{
			//POST
			$cid = $this->input['cid'];
			$offset = isset($this->input['offset']) ? $this->input['offset']  : 0;
			$count = isset($this->input['count']) ? $this->input['count'] : 10;
		}
		if(!$cid)
		{
			$this->errorOutput(NOID);
		}
		$comment_data = array();
		$orderby = ' ORDER BY create_time  '.$sort.'';
		$condition = " AND cid='".$cid."' AND (comment_type='main' or comment_type='') AND c.status=1";
		$memberId = $this->user['user_id'];
		$comment_data = $this->comment->show($condition, $orderby, $offset, $count);
		if($comment_data && is_array($comment_data))
		{
			foreach ($comment_data as $k=>$v)
			{
				$comment_data[$k]['reply'] = array();
				$comment_data[$k]['joint'] = array();
			
				//获取点赞数据
				$comment_data[$k]['joint'] = $this->get_joint($v['id'],'comment_id');
			
				//查询此用户是否赞过这个评论
				$comment_data[$k]['is_joint'] = false;
				if($memberId)
				{
					$condition = " AND joint_type='vice' AND cid=".$v['id']." AND member_id=".$memberId."";
					$jointInfo = $this->joint->show($condition, $orderby = '', $offset ='', $count = '');
					if ($jointInfo)
					{
						$comment_data[$k]['is_joint'] = true;
					}
				}
			}
		}
		
		if($comment_data && is_array($comment_data))
		{
			foreach ($comment_data as $k=>$v)
			{
				//处理回复数据
				$orderby = ' ORDER BY create_time  ASC';
				$condition = " AND cid='".$cid."' AND comment_type='vice' AND comment_fid=".$v['id']."";
				$reply_data = $this->comment->show($condition, $orderby, '', '');
					
				$comment_data[$k]['reply'] = $reply_data;
			}
		}
		
		if($this->input['cid'])
		{
			//POST返回
			$this->addItem($comment_data);
			$this->output();
		}
		else 
		{
			return $comment_data;		
		}
	}
	
	private function getCommentByids($ids)
	{
		$result = array();
		if(!$ids)
		{
			return false;
		}
		$result = $this->comment->comment_detail($ids);
        foreach($result as $k=>$v)
        {
            if($v['status'] != 1)
            {
                unset($result[$k]);
            }
        }
		return $result;
	}
	
	/**
	 * 获取点赞
	 */
	private function get_joint($id,$type,$offset = 0, $count = 20)
	{
		$joint_data = array();
		$orderby = ' ORDER BY create_time  DESC';
		if($type == 'cid')
		{
			$condition = " AND cid='".$id."' AND (joint_type='main' or joint_type='')";
		}
		else 
		{
			$condition = " AND cid='".$id."' AND joint_type='vice'";
		}
		
		$joint_data = $this->joint->show($condition, $orderby, $offset, $count);
		
		return $joint_data;
	}
	
	/**
	 * 获取sort_id
	 */
	private function get_sortidByappId($app_id)
	{
		$condition = " AND app_id='".$app_id."'";
		//社区数据 sort_id
		$sort_data = $this->node->getNodesList($condition,true);
		sort($sort_data);
		return $sort_data[0];
	}
	
	/**
	 * 获取这个社区的回收版块id
	 * @param unknown $sortId
	 */
	private function getRecycleId($sortId)
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 10;
		$limit = ' limit ' .$offset.','.$count.'';
		$orderby = ' ORDER BY order_id  DESC';
		$condition = " AND sort_id='".$sortId."' AND type='recycle'";
		
		$section_data = $this->section->show($condition,$orderby,$limit);
		return $section_data[0]['id'];
	}
	
	/**
	 * 获取所有帖子 评论 赞的数量 ／个人中心
	 */
	public function getMyCount()
	{
	    $member_id = intval($this->input['member_id']);
	    if (!$member_id)
	    {
	        $this->errorOutput(NO_MEMBER_ID);
	    }
	    
	    //获取我的消息
	    $Member = $this->member->detail($member_id);
	    $myNotice = $Member['relateme_num'];
	    
	    //获取帖子总数
	    $condition = " AND member_id=".$member_id."";
	    $Posts = $this->seekhelp->count($condition);
	       
	    //获取评论总数
        $condition = " AND member_id=".$member_id." AND comment_type='main'";
	    $Comment = $this->comment->count($condition);
	    
	    //获取赞总数
        $condition = " AND member_id=".$member_id." AND joint_type='main'";
	    $Praise = $this->joint->count($condition);
	    
	    $return = array(
	            'notice' => $myNotice,
	            'posts' => $Posts['total'],
	            'comment' => $Comment['total'],
	            'praise' => $Praise['total'],
	    );
	    
	    $this->addItem($return);
	    $this->output();
	}
	
	
	public function detail(){}
	public function count(){}
}
$ouput = new Community();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
